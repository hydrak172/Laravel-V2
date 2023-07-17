<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    public function index(){
        // dd(session()->get('cart'));
        $cart = session()->get('cart') ?? [];
        return view('client.pages.shoping-cart' ,compact('cart'));
    }
    public function addProductToCart($productId , $qty = 1)
    {
        $product = Product::find($productId);
        if($product){
            $cart = session()->get('cart') ?? [];
            $imageLink = (is_null($product->image_url)) || (!file_exists("images/".$product->image_url)) ? 'default_image.png' : $product->image_url;
            $cart[$product->id] = [
            'name' => $product->name,
            'price' => number_format($product->price,2),
            'image_url' => $imageLink,
            'qty' => ($cart[$productId]['qty'] ?? 0) + $qty
        ];
        //add cart in to session
        session()->put('cart',$cart);
        $totalProduct = count($cart);
        $totalPrice = $this->calculateTotalPrice($cart);

        return response()->json(['message' => 'Add product Success!' ,'total_product' => $totalProduct,'total_price' => $totalPrice]);
    }else{
        return response()->json(['message' => 'Add product failed!'],Response::HTTP_NOT_FOUND);
    }
    }

    public function calculateTotalPrice (array $cart){
        $totalPrice = 0;
        foreach($cart as $item){
            $totalPrice = $item['qty'] * $item['price'];
        }
        return number_format($totalPrice ,2);
    }
    public function deleteProductInCart ($productId){
        $cart = session()->get('cart') ?? [] ;
        if(array_key_exists($productId,$cart)){
            unset($cart[$productId]);
            session()->put('cart',$cart);
        }else{
            return response()->json(['message' => 'Remove product failed!'],Response::HTTP_BAD_REQUEST);
        }
        $totalProduct = count($cart);
        $totalPrice = $this->calculateTotalPrice($cart);
        return response()->json(['message' => 'Remove product Success!' ,'total_product' => $totalProduct,'total_price' => $totalPrice]);
    }
}
