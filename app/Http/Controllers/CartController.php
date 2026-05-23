<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Fetch cart items eager loaded with product details
        $cartItems = Cart::where('user_id', $user->id)
            ->with('product')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $cartItems,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $user = $request->user();
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        // Check if product is in stock
        $product = Product::find($productId);
        if ($product->stock < $quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Requested quantity exceeds available stock',
            ], 400);
        }

        // Check if item already exists in cart for this user
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Verify new total quantity against stock
            if ($product->stock < ($cartItem->quantity + $quantity)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot add more. Total quantity exceeds available stock.',
                ], 400);
            }
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        // Load product details for the response
        $cartItem->load('product');

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart',
            'data' => $cartItem,
        ]);
    }

    public function remove(Request $request, $id)
    {
        $user = $request->user();
        
        $cartItem = Cart::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart item not found',
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item removed from cart',
        ]);
    }
}
