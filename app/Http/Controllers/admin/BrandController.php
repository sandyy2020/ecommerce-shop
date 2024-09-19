<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(Request $request){
        // Start a query on the Category model
        $query = Brand::query(); // Correctly initialize the query
        // Check if there is a search term, and if so, modify the query
        if (!empty($request->get('table_search'))) {
            $query->where('name', 'like', '%' . $request->get('table_search') . '%');
         }
         // Execute the query to get the results, ordered by latest
        $brands = $query->latest()->paginate(5);

        return view('admin.brands.list', compact('brands'));
    }
    public function create(){
        $brands=Brand::orderBy('name','ASC')->get();
        $data['brand']=$brands;
        return view('admin.brands.create',$data);
    }
    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:brands',
            'status'=>'required'
        ]);
        if($validator->passes()){
            $brands=new Brand();
            $brands->name=$request->name;
            $brands->slug=$request->slug;
            $brands->status=$request->status;
            $brands->save();

            return response()->json([
                'status'=>true,
                'message'=>"Brand added successfully"
            ]);


        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function edit($brandId,Request $request){
        $brands=Brand::find($brandId);
        if(empty($brands)){
            return redirect()->route('brand.index');
        }
        return view('admin.brands.edit',compact('brands'));
        
    }
    public function update($brandId,Request $request){
        // dd($request->all);
        $brands=Brand::find($brandId);
        if(empty($brands)){
            return response()->json([
                'status'=>false,
                'notFound'=>true,
                'message'=>'Brand not found'
            ]);
        }

        $validator= Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:brands,slug,'.$brands->id.',id',
            'status'=>'required'
        ]);
       
        if($validator->passes()){
            $brands->name=$request->name;
            $brands->slug=$request->slug;
            $brands->status=$request->status;
            $brands->save();

            return response()->json([
                'status'=>true,
                'message'=>"Brand updated successfully"
            ]);


        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
        
    }
    public function destroy($brandId)
{
    $brands = Brand::find($brandId);

    if (!$brands) {
        return response()->json([
            'status' => false,
            'message' => 'Brand not found',
        ]);
    }
    $brands->delete();

    return response()->json([
        'status' => true,
        'message' => 'Brand deleted successfully',
    ]);
}
}
