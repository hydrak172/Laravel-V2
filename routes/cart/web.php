<?php

use App\Http\Controllers\Client\CartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//cart
Route::prefix('shoping-cart')->name('shoping-cart.')->group(function(){
    Route::get('/',[CartController::class , 'index'])->name('index');
    Route::get('/product/add-to-cart/{productId}/{qty?}',[CartController::class, 'addProductToCart'])->name('add-to-cart');
    Route::get('/product/add-to-cart/{productId}',[CartController::class, 'deleteProductInCart'])->name('delete-product-in-cart');
});

