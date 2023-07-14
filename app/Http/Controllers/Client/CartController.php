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
    public function addProductToCart($productId)
    {
        $product = Product::find($productId);
        $imageLink = (is_null($product->image_url)) || (!file_exists("images/".$product->image_url)) ? 'default_image.png' : $product->image_url;
        if($product){
            $cart = session()->get('cart') ?? [];
            $cart[$product->id] = [
            'name' => $product->name,
            'price' => number_format($product->price,2),
            'image_url' => $imageLink,
            'qty' => ($cart[$productId]['qty'] ?? 0) + 1
        ];
        //add cart in to session
        session()->put('cart',$cart);
        return response()->json(['message' => 'Add product Success!']);
    }else{
        return response()->json(['message' => 'Add product failed!'],Response::HTTP_NOT_FOUND);
    }
    }
}
