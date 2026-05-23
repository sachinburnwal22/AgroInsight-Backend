<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::all();
        return response()->json([
            'status' => 'success',
            'data' => $shops,
        ]);
    }

    public function products($id)
    {
        $shop = Shop::find($id);
        if (!$shop) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop not found',
            ], 404);
        }

        $products = $shop->products;
        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }
}
