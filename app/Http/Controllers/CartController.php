<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);
        
        $user = auth()->user();

        $product_price = Product::where('id', $request->product_id)->first()->price;


        $cart = Cart::where('user_id', $request->user_id)->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $request->user_id;
            $cart->products = json_encode([]);
        }

        $products = json_decode($cart->products, true);

        if ($products === null) {
            $products = [];
        }

        $productExists = false;
        foreach ($products as &$product) {
            if ($product['product_id'] == $request->product_id) {
                $product['quantity'] += $request->quantity;
                $productExists = true;
                break;
            }
        }

        if (!$productExists) {
            $newProduct = [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ];
            $products[] = $newProduct;
        }

        $cart->products = json_encode($products);
        $cart->total = $cart->total + ($product_price * $request->quantity);
        $cart->save();

        return response()->json(['message' => 'Product added to cart successfully']);
    }
}
