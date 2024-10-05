<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                $status=true;
                $message='<strong>'.$product->title.'</strong> added in your cart successfully.';
                session()->flash('success',$message);
            }else{
                $status=false;
                $message=$product->title .' already added in cart';
            }
        }else{
           
            //card is empty
            Cart::add($product->id, $product->title, 1,$product->price);
                $status=true;
                $message='<strong>'.$product->title.'</strong> added in your cart successfully.';
                session()->flash('success',$message);
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

    public function updateCart(Request $request){
        $rowId= $request->rowId;
        $qty= $request->qty;

        $itemInfo=Cart::get($rowId);
        $product=Product::find($itemInfo->id);
        //check qty available in stock
        if($product->track_qty=='Yes'){
            if($product->qty>=$qty){
                Cart::update($rowId,$qty);
                $message='Cart updated Successfully';
                $status=true;
                session()->flash('success',$message);
            }else{
                $message='Requested qty('.$qty.') not available in stock.';
                $status=false;
                session()->flash('error',$message);
            }
        }else{
            Cart::update($rowId,$qty);
            $message='Cart updated Successfully';
            $status=true;
            session()->flash('success',$message);
        }
        
        return response()->json([
            'status'=>$status,
            'message'=>$message
        ]);
    }
    public function deleteItem(Request $request){
        $itemInfo=Cart::get($request->rowId);
        if($itemInfo==null){
            $errorMessage='Item not found in cart';
            session()->flash('error',$errorMessage);

            return response()->json([
                'status'=>false,
                'message'=>$errorMessage
            ]);
        }
        Cart::remove($request->rowId);
        $message='Item removed from cart successfully';
        session()->flash('error',$message);
        return response()->json([
            'status'=>true,
            'message'=>$message
        ]);
    }
    public function checkout(){
        //if cart is empty redirect to cart page
        if(Cart::count()==0){
           return redirect()->route('front.cart');
        }
        //if user is not logged in then redirect to login page
        if(Auth::check()==false){
            if(!session()->has('url.intended')){
                session(['url.intended'=> url()->current()]);
            }
            
            return redirect()->route('account.login');
        }
        session()->forget('url.intended');

        $countries= Country::orderBy('name','ASC')->get();

        return view('front.checkout',[
            'countries'=>$countries
        ]);
    }
}
