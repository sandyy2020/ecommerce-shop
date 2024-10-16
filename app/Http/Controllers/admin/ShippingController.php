<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create(){
        $countries=Country::get();
        $data['countries']=$countries;
        $shippingCharges=ShippingCharge::select('shipping_charges.*','countries.name')
                        ->leftjoin('countries','countries.id','shipping_charges.country_id')
                         ->get();
        // dd($shippingCharges);
        $data['shippingCharges']=$shippingCharges;                 
        return view('admin.shipping.create',$data);
    }

    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'country'=>'required',
            'amount'=>'required|numeric'
        ]);

        if($validator->passes()){

            $count=ShippingCharge::where('country_id',$request->country)->count();
            if($count>0){
                return response()->json([
                    'status'=>false,
                    'errors' => [
                    'country' => ['Shipping already exists for this country.']
                ]   
                ]);
            }
            $shipping= new ShippingCharge();
            $shipping->country_id=$request->country;
            $shipping->amount=$request->amount;
            $shipping->save();
            return response()->json([
                'status'=>true,
               
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function edit($id){
        $shippingCharge=ShippingCharge::find($id);
        $countries=Country::get();
        $data['countries']=$countries;
        $data['shippingCharge']=$shippingCharge;
        return view('admin.shipping.edit',$data);
    }
    public function update(Request $request,$id){
        $validator=Validator::make($request->all(),[
            'country'=>'required',
            'amount'=>'required|numeric'
        ]);

        if($validator->passes()){
            $shipping=ShippingCharge::find($id);
            $shipping->country_id=$request->country;
            $shipping->amount=$request->amount;
            $shipping->save();
            return response()->json([
                'status'=>true,
                'message'=>"Shipping updated successfully"
               
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function destroy($id){
        $shippingCharge= ShippingCharge::find($id);

        if($shippingCharge==null){
            return response()->json([
                'status'=>false,
                'message'=>'Shipping not found'
            ]);

        }
        $shippingCharge->delete();
        return response()->json([
            'status'=>true,
             'message' => 'Shipping deleted successfully'
        ]);
    }

}
