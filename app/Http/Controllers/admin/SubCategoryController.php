<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request){
        // Start a query on the Category model
        $query = SubCategory::query(); // Correctly initialize the query
        // Check if there is a search term, and if so, modify the query
        if (!empty($request->get('table_search'))) {
            $query->where('name', 'like', '%' . $request->get('table_search') . '%');
         }
         // Execute the query to get the results, ordered by latest
        $subcategories = $query->with('category')->latest()->paginate(5);

        return view('admin.sub_category.list', compact('subcategories'));
    }
    public function create(){
        $categories=Category::orderBy('name','ASC')->get();
        $data['categories']=$categories;
        return view('admin.sub_category.create',$data);
    }
    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:sub_categories',
            'category'=>'required',
            'status'=>'required'
        ]);
        if($validator->passes()){
            $subcategory=new SubCategory();
            $subcategory->name=$request->name;
            $subcategory->slug=$request->slug;
            $subcategory->status=$request->status;
            $subcategory->showHome=$request->showHome;
            $subcategory->category_id=$request->category;
            $subcategory->save();

            return response()->json([
                'status'=>true,
                'message'=>"Subcategory added successfully"
            ]);


        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function edit($subcategoryId,Request $request){
        $subcategory=SubCategory::find($subcategoryId);
        if(empty($subcategory)){
            return redirect()->route('sub-categories.index');
        }
        return view('admin.sub_category.edit',compact('subcategory'));
        
    }
    public function update($subcategoryId,Request $request){
        // dd($request->all);
        $subcategory=SubCategory::find($subcategoryId);
        if(empty($subcategory)){
            return response()->json([
                'status'=>false,
                'notFound'=>true,
                'message'=>'subcategory not found'
            ]);
        }

        $validator= Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:sub_categories,slug,'.$subcategory->id.',id',
            'category'=>'required',
            'status'=>'required'
        ]);
       
        if($validator->passes()){
            $subcategory->name=$request->name;
            $subcategory->slug=$request->slug;
            $subcategory->category_id=$request->category;
            $subcategory->status=$request->status;
            $subcategory->showHome=$request->showHome;
            $subcategory->save();

            return response()->json([
                'status'=>true,
                'message'=>"Subcategory updated successfully"
            ]);


        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
        
    }
    public function destroy($subcategoryId)
{
    $subcategory = SubCategory::find($subcategoryId);

    if (!$subcategory) {
        return response()->json([
            'status' => false,
            'message' => 'SubCategory not found',
        ]);
    }
    $subcategory->delete();

    return response()->json([
        'status' => true,
        'message' => 'SubCategory deleted successfully',
    ]);
}


}
