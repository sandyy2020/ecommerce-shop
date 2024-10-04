<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $product=Product::find($request->id);
        if($product==null){
            return response()->json([
                'status'=>false,
                'message'=>'Product Not Found'
            ]);
        }
        if(Cart::count()>0){
            $cartContent= Cart::content();
            $productAlreadyExist=false;
            foreach($cartContent as $item){
                if($item->id == $product->id){
                    $productAlreadyExist=true;
                    $status=true;
                    $message=$product->title .' added in cart';
                }
            }
            if($productAlreadyExist==false){
                Cart::add($product->id, $product->title, 1,$product->price);
            }else{
                $status=false;
                $message=$product->title .' already added in cart';
            }
        }else{
           
            //card is empty
            Cart::add($product->id, $product->title, 1,$product->price);
            $status=true;
            $message=$product->title .' added in cart';
        }
        return response()->json([
            'status'=>$status,
            'message'=>$message
        ]);
       

    }
    public function cart(){
        $cartContent=cart::Content();
        $data['cartContent']=$cartContent;
        return view('front.cart',$data);
    }
}
