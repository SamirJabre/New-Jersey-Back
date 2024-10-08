<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('/add_address', [AddressController::class, 'addAddress']);
    Route::post('/add_to_cart' , [CartController::class,'addToCart']);
    Route::post('/remove_product', [CartController::class, 'removeProduct']);
    Route::post('/decrement_quantity' , [CartController::class,'decrementQuantity']);
    Route::post('/increment_quantity' , [CartController::class,'incrementQuantity']);
    Route::post('/add_to_wishlist', [WishlistController::class, 'addToWishlist']);
    Route::post('/remove_from_wishlist', [WishlistController::class, 'removeFromWishlist']);
    Route::post('/make_order', [OrderController::class, 'createOrder']);
    Route::post('/cancel_order', [OrderController::class, 'cancelOrder']);
});

