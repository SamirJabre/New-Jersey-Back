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


    public function removeProduct(Request $request){
        $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
        ]);
    
        $user = auth()->user();
       
        $product = Product::where('id', $request->product_id)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        $cart = Cart::where('user_id', $request->user_id)->first();
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
    
        $products = json_decode($cart->products, true);
        if ($products === null) {
            return response()->json(['message' => 'No products in cart'], 404);
        }
        
        $productIndex = array_search($request->product_id, array_column($products, 'product_id'));
        if ($productIndex === false) {
            return response()->json(['message' => 'Product not found in cart'], 404);
        }
    
        $productQuantity = $products[$productIndex]['quantity'];
        $totalToSubtract = $product->price * $productQuantity;
    
        $cart->total -= $totalToSubtract;
    
        $products = array_filter($products, function($product) use ($request) {
            return $product['product_id'] != $request->product_id;
        });
    
        $cart->products = json_encode(array_values($products));
        $cart->save();
    
        return response()->json(['message' => 'Product removed from cart successfully']);
    }

    public function decrementQuantity(Request $request){
        $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
        ]);
    
        $user = auth()->user();
    
        $product = Product::where('id', $request->product_id)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        $cart = Cart::where('user_id', $request->user_id)->first();
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
    
        $products = json_decode($cart->products, true);
        if ($products === null) {
            return response()->json(['message' => 'No products in cart'], 404);
        }
    
        $productIndex = array_search($request->product_id, array_column($products, 'product_id'));
        if ($productIndex === false) {
            return response()->json(['message' => 'Product not found in cart'], 404);
        }
    
        $products[$productIndex]['quantity'] -= 1;
        $totalToSubtract = $product->price;
    
        if ($products[$productIndex]['quantity'] <= 0) {
            unset($products[$productIndex]);
        }
    
        $cart->total -= $totalToSubtract;
        $cart->products = json_encode(array_values($products));
        $cart->save();
    
        return response()->json(['message' => 'Product quantity decremented successfully']);
    }



    public function incrementQuantity(Request $request){
        $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
        ]);
    
    
        $product = Product::where('id', $request->product_id)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        $cart = Cart::where('user_id', $request->user_id)->first();
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
    
        $products = json_decode($cart->products, true);
        if ($products === null) {
            return response()->json(['message' => 'No products in cart'], 404);
        }
    
        $productIndex = array_search($request->product_id, array_column($products, 'product_id'));
        if ($productIndex === false) {
            return response()->json(['message' => 'Product not found in cart'], 404);
        }
    
        $products[$productIndex]['quantity'] += 1;
        $totalToAdd = $product->price;
    
        if ($products[$productIndex]['quantity'] <= 0) {
            unset($products[$productIndex]);
        }
    
        $cart->total += $totalToAdd;
        $cart->products = json_encode(array_values($products));
        $cart->save();
    
        return response()->json(['message' => 'Product quantity incremented successfully']);
    }



}
