<?php

use App\Http\Controllers\Client\CartController;
use App\Mail\OrderEmail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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
Route::prefix('shoping-cart')->name('shoping-cart.')->middleware('auth')->group(function(){
    Route::get('/',[CartController::class , 'index'])->name('index');
    Route::get('/product/add-to-cart/{productId}/{qty?}',[CartController::class, 'addProductToCart'])->name('add-to-cart');
    Route::get('/product/delete-product-to-cart/{productId}',[CartController::class, 'deleteProductInCart'])->name('delete-product-in-cart');
    Route::get('/product/update-product-to-cart/{productId}/{qty?}', [CartController::class , 'updateProductInCart'])->name('update-product-in-cart');
    Route::get('delete-cart', [CartController::class , 'deleteCart'])->name('delete-cart');
    Route::post('placeorder',[CartController::class , 'placeorder'])->name('place-order');
});

// Route::get('test-send-mail',function(){
//     Mail::to('mowo.khanhnguyen1712@gmail.com')->send(new OrderEmail());
// });
