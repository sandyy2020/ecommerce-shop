<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductSubCategory extends Controller
{
    public function getSubcategories(Request $request)
    {
        $categoryId = $request->category_id;

        // Fetch subcategories based on category_id
        $subcategories = SubCategory::where('category_id', $categoryId)->get(); // Assuming parent_id holds the category relation

        return response()->json(['subcategories' => $subcategories]);
    }
}
