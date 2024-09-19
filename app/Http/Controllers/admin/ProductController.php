<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request){
        //start a query on the product model
        $query=Product::query(); // correctly utilize the query
        // Check if there is a search term, and if so, modify the query
        if(!empty($request->get('table_search'))){
            $query->where('title','like','%'.$request->get('table_search').'%');
        }
        $products=$query->latest()->paginate(5);
        $data['products']=$products;
        return view ('admin.products.list',$data);
    }
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $products = new Product();
            $products->title = $request->title;
            $products->slug = $request->slug;
            $products->description = $request->description;
            $products->price = $request->price;
            $products->compare_price = $request->compare_price;
            $products->sku = $request->sku;
            $products->barcode = $request->barcode;
            $products->track_qty = $request->track_qty;
            $products->qty = $request->qty;
            $products->status = $request->status;
            $products->category_id = $request->category;
            $products->sub_category_id = $request->sub_category;
            $products->brand_id = $request->brand;
            $products->is_featured = $request->is_featured;

            // Process uploaded images
            if (!empty($request->image_ids)) {
                foreach ($request->image_ids as $imageId) {
                    $tempImage = TempImage::find($imageId);

                    if ($tempImage) {
                        // Get the image file extension
                        $extArray = explode('.', $tempImage->name);
                        $ext = last($extArray);

                        // Create a new image name based on product ID and current time
                        $newImageName = $products->id . '-' . time() . '.' . $ext;

                        // Source and destination paths
                        $sPath = public_path('/temp/' . $tempImage->name);
                        $dPath = public_path('/uploads/images/' . $newImageName);

                        // Move the image from temp to uploads
                        File::move($sPath, $dPath);

                        // Save the image information in the ProductImages table
                        $productImage = new ProductImage();
                        $productImage->product_id = $products->id;
                        $productImage->image = $newImageName;
                        $productImage->save();

                        // Optionally delete the temporary image record
                        $tempImage->delete();
                    }
                }
            }
            $products->save();
            return response()->json([
                'status'=>true,
                'message'=>"product added successfully"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
