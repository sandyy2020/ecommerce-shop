<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request){
        // Start a query on the Category model
        $query = Category::query(); // Correctly initialize the query
        // Check if there is a search term, and if so, modify the query
        if (!empty($request->get('table_search'))) {
            $query->where('name', 'like', '%' . $request->get('table_search') . '%');
         }
         // Execute the query to get the results, ordered by latest
        $categories = $query->latest()->paginate(5);

        return view('admin.category.list', compact('categories'));
    }
    public function create(){
        return view('admin.category.create');
    }
    public function store(Request $request){
        // dd($request->all());
        $validator= Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:categories',
            // 'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image if present
            'image_id' => 'required|exists:temp_images,id', // Ensure the image exists in temp_images
        ]);
       
        if($validator->passes()){
            $category=new Category();
            $category->name=$request->name;
            $category->slug=$request->slug;
            $category->status=$request->status;
            $category->showHome=$request->showHome;

            // // If the image file is uploaded, store it
            // if($request->hasFile('image')){
            //     //here we store image
            //     $image=$request->file('image');
            //     // dd($image);
            //     $ext= $image->getClientOriginalExtension(); //get image extension
            //     $imagename= time().'.'.$ext; //unique image name
            //     //save image to category directory
            //     $image->move(public_path('uploads/images'), $imagename);
            //     //save image in db
            //     $category->image = $imagename;
            //     }

            // If image was uploaded via Dropzone
        if ($request->image_id) {
            // Retrieve the temporary image from the database
            $tempImage =TempImage::find($request->image_id);
            
            // Move the image from the temp folder to the final destination
            $tempImagePath = public_path('temp') . '/' . $tempImage->name;
            $newImagePath = public_path('uploads/images') . '/' . $tempImage->name;

            // Move the image to the final directory
            if (file_exists($tempImagePath)) {
                rename($tempImagePath, $newImagePath); // Move the image
                $category->image = $tempImage->name; // Store the image name in the category
            }

            // Optionally, delete the temporary image record from the database
            $tempImage->delete();
        }

            // Save the category
            $category->save();

            return response()->json([
                'status'=>true,
                'message'=>"category added successfully"
            ]);


        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
        
    }
    public function edit($categoryId,Request $request){
        $category=Category::find($categoryId);
        if(empty($category)){
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit',compact('category'));
        
    }
    // public function update($categoryId,Request $request){
    //     // dd($request->all);
    //     $category=Category::find($categoryId);
    //     if(empty($category)){
    //         return response()->json([
    //             'status'=>false,
    //             'notFound'=>true,
    //             'message'=>'category not found'
    //         ]);
    //     }

    //     $validator= Validator::make($request->all(),[
    //         'name'=>'required',
    //         'slug'=>'required|unique:categories,slug,'.$category->id.',id',
    //         'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image if present
    //     ]);
       
    //     if($validator->passes()){
    //         $category->name=$request->name;
    //         $category->slug=$request->slug;
    //         $category->status=$request->status;

    //         // Check if a new Dropzone image is uploaded (this assumes the image name is passed in the request)
    //         if ($request->image_name) {
    //         // Delete the old image if it exists
    //         if ($category->image && file_exists(public_path('uploads/images/' . $category->image))) {
    //             unlink(public_path('uploads/images/' . $category->image)); // Delete old image
    //         }

    //         // Move the new Dropzone image from temp to the final folder
    //         $tempImagePath = public_path('temp/' . $request->image_name);
    //         $finalImagePath = public_path('uploads/images/' . $request->image_name);

    //         if (file_exists($tempImagePath)) {
    //             rename($tempImagePath, $finalImagePath); // Move the image to the final destination
    //             $category->image = $request->image_name; // Update image name in the database
    //         }
    //         }
    //         $category->save();

    //         return response()->json([
    //             'status'=>true,
    //             'message'=>"category updated successfully"
    //         ]);


    //     }else{
    //         return response()->json([
    //             'status'=>false,
    //             'errors'=>$validator->errors()
    //         ]);
    //     }
        
    // }

    public function update($categoryId, Request $request)
    {
        // Find the category by ID
        $category = Category::find($categoryId);
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }
    
        // Validate the request inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image if present
        ]);
    
        if ($validator->passes()) {
           // Update category fields
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome=$request->showHome;
            $category->save();
        

        $oldImage=$category->image;
        //save Image Here
        if(!empty($request->image_id)){
            $tempImage=TempImage::find($request->image_id);
            $extArray=explode('.',$tempImage->name);
            $ext=last($extArray);

            $newImageName=$category->id.'-'.time().'.'.$ext;
            $sPath=public_path().'/temp/'.$tempImage->name;
            $dPath=public_path().'/uploads/images/'.$newImageName;
            File::copy($sPath,$dPath);

            // //Generate Image Thumbnail
            // $dPath=public_path().'/uploads/images/'.$newImageName;
            // $img=Image::make($sPath);
            // $img->resize(450,600);
            // $img->save($dPath);

            $category->image=$newImageName;
            $category->save();
            //Delete Old Images
            File::delete(public_path().'/uploads/images/'.$oldImage);

        }
        return response()->json([
            'status' => true,
            'message' => "Category updated successfully"
        ]);
    }else{
        return response()->json([
                        'status'=>false,
                        'errors'=>$validator->errors()
                    ]);
    }
    }
    

    public function destroy($categoryId)
{
    $category = Category::find($categoryId);

    if (!$category) {
        return response()->json([
            'status' => false,
            'message' => 'Category not found',
        ]);
    }

    // Delete associated image (if necessary)
    if ($category->image && file_exists(public_path('uploads/images/' . $category->image))) {
        unlink(public_path('uploads/images/' . $category->image));
    }

    $category->delete();

    return response()->json([
        'status' => true,
        'message' => 'Category deleted successfully',
    ]);
}

}
