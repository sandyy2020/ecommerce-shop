<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $table = 'sub_categories';

    // Define the relationship to the Category model
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
