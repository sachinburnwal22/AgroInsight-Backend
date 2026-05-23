<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        $user = $request->user();
        
        // 1. Calculate total cart amount
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart is empty',
            ], 400);
        }

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            // Verify stock levels before generating order
            if ($item->product->stock < $item->quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Insufficient stock for product: {$item->product->name}",
                ], 400);
            }
            $totalAmount += $item->product->price * $item->quantity;
        }

        // Amount in paise for Razorpay (1 INR = 100 paise)
        $amountInPaise = intval($totalAmount * 100);

        $keyId = env('RAZORPAY_KEY_ID', '');
        $keySecret = env('RAZORPAY_KEY_SECRET', '');

        // Simulated test mode order creation if credentials are empty or contain placeholder values
        if (empty($keyId) || empty($keySecret) || Str::contains($keyId, 'placeholder') || Str::contains($keyId, 'dummy')) {
            $mockOrderId = 'order_mock_' . Str::random(14);
            
            return response()->json([
                'status' => 'success',
                'is_mock' => true,
                'razorpay_order_id' => $mockOrderId,
                'amount' => $totalAmount,
                'amount_paise' => $amountInPaise,
                'key_id' => 'rzp_test_mockKeyId', // Client dummy key
            ]);
        }

        try {
            // Make HTTP call to Razorpay API
            $response = Http::withBasicAuth($keyId, $keySecret)
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => $amountInPaise,
                    'currency' => 'INR',
                    'receipt' => 'receipt_' . time() . '_' . $user->id,
                ]);

            if ($response->successful()) {
                $razorpayOrder = $response->json();
                
                return response()->json([
                    'status' => 'success',
                    'is_mock' => false,
                    'razorpay_order_id' => $razorpayOrder['id'],
                    'amount' => $totalAmount,
                    'amount_paise' => $amountInPaise,
                    'key_id' => $keyId,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create order with payment gateway',
                    'details' => $response->json(),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment gateway connection exception: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'nullable|string',
            'is_mock' => 'boolean',
        ]);

        $user = $request->user();
        $orderId = $request->input('razorpay_order_id');
        $paymentId = $request->input('razorpay_payment_id');
        $signature = $request->input('razorpay_signature');
        $isMock = $request->input('is_mock', false);

        // Verify signature if not in mock test mode
        if (!$isMock) {
            $keySecret = env('RAZORPAY_KEY_SECRET', '');
            $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);
            
            if ($expectedSignature !== $signature) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment signature verification failed',
                ], 400);
            }
        }

        // Fetch user's cart items
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Checkout failed. Cart is empty.',
            ], 400);
        }

        // Deduct inventory stock and calculate order sum
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $product = $item->product;
            if ($product->stock < $item->quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Insufficient stock for product: {$product->name}",
                ], 400);
            }
            $product->stock -= $item->quantity;
            $product->save();
            
            $totalAmount += $product->price * $item->quantity;
        }

        // Create the official Order record in DB
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'payment_status' => 'paid',
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature,
        ]);

        // Clear user's shopping cart
        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Order placed successfully',
            'data' => $order,
        ]);
    }
}
