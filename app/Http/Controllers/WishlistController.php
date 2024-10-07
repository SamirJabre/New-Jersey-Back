<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request){
        $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
        ]);
        
        $user = auth()->user();


        $wishlist = Wishlist::where('user_id', $request->user_id)->first();

        $products = json_decode($wishlist->products, true);

        if ($products === null) {
            $products = [];
        }

        $productExist = false;
        foreach ($products as $product) {
            if($product['product_id'] == $request->product_id){
                $productExist = true;
                return response()->json([
                    'message' => 'Product already exists in Wishlist'
                ],200);
            }
        }
        
        if(!$productExist){

            $newProduct = [
                'product_id' => $request->product_id,
            ];
            $products[] = $newProduct;
        }
        

        $wishlist->products = json_encode($products);
        $wishlist->save();

        return response()->json(['message' => 'Product added to Wishlist successfully'],200);
    }
}
