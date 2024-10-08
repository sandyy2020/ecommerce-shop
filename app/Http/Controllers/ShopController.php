<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request,$categorySlug = null,$subCategorySlug = null){
        $categorySelected='';
        $SubCategorySelected='';
        $categories=Category::orderBy('name','ASC')->with('subcategories')->where('status',1)->get();
        $brands=Brand::orderBy('name','ASC')->where('status',1)->get();
        $products=Product::where('status',1);

        if(!empty($categorySlug)){
            $category=Category::where('slug',$categorySlug)->first();
            $products=$products->where('category_id',$category->id);
            $categorySelected=$category->id;
        }

        if(!empty($subCategorySlug))
        {
            $subcategory=SubCategory::where('slug',$subCategorySlug)->first();
            $products=$products->where('sub_category_id',$subcategory->id);
            $SubCategorySelected=$subcategory->id;
        }

        $products=$products->orderBy('title','ASC');
        $products=$products->get();

        $data['categories']=$categories;
        $data['brands']=$brands;
        $data['products']=$products;
        $data['categorySelected']=$categorySelected;
        $data['SubCategorySelected']=$SubCategorySelected;

        return view('front.shop',$data);
    }
    public function product($slug){
        $product=Product::where('slug',$slug)->first();
        if($product==null){
            abort(404);
        }
        return view('front.product',compact('product'));
    }
}
