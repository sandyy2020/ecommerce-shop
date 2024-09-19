<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TempImagesController extends Controller
{
    public function create(Request $request){
        $image=$request->image;
        if(!empty($image)){
            $ext=$image->getClientOriginalExtension();
            $newName=time().'.'.$ext;
            $tempImage=new TempImage();
            $tempImage->name=$newName;
            $tempImage->save();
            $image->move(public_path().'/temp',$newName);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'image_name' => $newName,
                'message' => "Image uploaded successfully"
            ]);   

        }
        return response()->json([
            'status' => false,
            'message' => "No image uploaded"
        ]);

    }
    public function destroy($id)
    {
        $filePath = public_path('temp/' . $id);

        if (File::exists($filePath)) {
            File::delete($filePath);
            return response()->json([
                'status' => true,
                'message' => 'Image removed successfully.',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Image not found.',
        ]);
    }
}
