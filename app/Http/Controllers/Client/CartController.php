<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function deleteProductInCart($productId){
        $cart = session()->get('cart') ?? [];
        if(array_key_exists($productId, $cart)){
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }else{
            return response()->json(['message' => 'Remove product failed!'], Response::HTTP_BAD_REQUEST);
        }
        $totalProduct = count($cart);
        $totalPrice = $this->calculateTotalPrice($cart);
        return response()->json(['message' => 'Remove product success!', 'total_product' => $totalProduct, 'total_price' =>  $totalPrice]);
    }
    public function updateProductInCart($productId,$qty){
        $cart = session()->get('cart') ?? [];
        if(array_key_exists($productId,$cart)){
            $cart[$productId]['qty'] = $qty;
            if(!$qty){
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
        }
        $totalProduct = count($cart);
        $totalPrice = $this->calculateTotalPrice($cart);
        return response()->json(['message' => 'Remove product success!', 'total_product' => $totalProduct, 'total_price' =>  $totalPrice]);
    }

    public function deleteCart(){
        session()->put('cart', []);
        return response()->json(['message' => 'Delete product success!', 'total_product' => 0, 'total_price' =>  0]);
    }
    public function placeOrder(Request $request){
        //validate from request


        //try 
        
        try{
        DB::beginTransaction();
            //get cart and calculate price total 
            $cart = session()->get('cart', []);
            $totalPrice = 0;
            foreach($cart as $item){
                $totalPrice += $item['qty'] * $item['price'];
            }
            //create records order
            $order = Order::create([
                'user_id' => Auth::user()->id,
                'address'=> $request ->address,
                'city'=>$request->city,
                'note'=>$request->note,
                'payment_method'=>$request->payment_method,
                'status' => Order::STATUS_PENDING,
                'subtotal' =>$totalPrice,
                'total' =>$totalPrice,
            ]);

            //Create records order items 

            foreach ($cart as $productId => $item){
                $orderItem = OrderItem::create([
                    'order_id' =>$order->id,
                    'product_id'=>$productId,
                    'qty'=>$item['qty'],
                    'name'=>$item['name'],
                    'price'=>$item['price']
                ]);
            }

            //create order payment method 
            $orderPaymentMethod = OrderPaymentMethod::create([
                'order_id'=>$order->id,
                'payment_provider'=>$request->get('payment_provider'),
                'total_balance' => $totalPrice,
                'status' =>OrderPaymentMethod::STATUS_PENDING
            ]);

            $user = User::find(Auth::user()->id);
            $user->phone = $request->phone;
            $user->save();

            //reset session 
            session()->put('cart',[]);
            DB::commit();
        }catch(\Exception $message){
            DB::rollback();
        }
        return redirect()->route('home')->with('msg','Order Success!');

    }     
}
