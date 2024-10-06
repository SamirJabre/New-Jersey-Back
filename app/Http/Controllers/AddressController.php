<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function addAddress(Request $request){
        $request->validate([
            'user_id' => 'required|integer',
            'state' => 'required|string|max:255',
            'city'=> 'required|string|max:255',
            'address'=> 'required|string|max:255',
            'zip'=> 'required|integer',
        ]);

        $user = auth()->user();

        $address = new Address();
        $address->state = $request->state;
        $address->city = $request->city;
        $address->address = $request->address;
        $address->zip = $request->zip;
        $address->user_id = $request->user_id;

        $address->save();

        return response()->json([
            'status' => 'success',
            'address' => $address,
        ]);
    }
}
