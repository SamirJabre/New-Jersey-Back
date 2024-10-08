<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createOrder(Request $request){
        $request->validate([
            'user_id' => 'required|integer',
            'address_id' => 'required|integer',
        ]);
        $products = Cart::where('user_id', $request->user_id)->first()->products;
        $total = Cart::where('user_id', $request->user_id)->first()->total;
        $order = Order::create([
            'user_id'=> $request->user_id,
            'address_id'=> $request->address_id,
            'products'=> $products,
            'total'=> $total * 1.1,
        ]);
        $cart = Cart::where('user_id', $request->user_id)->first();
        $cart->products = json_encode([]);
        $cart->total = 0;
        $cart->save();

        return response()->json($order);
    }

    public function cancelOrder(Request $request){
        $request->validate([
            'order_id' => 'required|integer',
        ]);
        $order = Order::where('id', $request->order_id)->first();
        $order->status = 'cancelled';
        $order->save();
        return response()->json(['message' => 'Order cancelled successfully']);
    }
}
