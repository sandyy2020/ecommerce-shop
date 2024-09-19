<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'categories';

    // Define the relationship to the SubCategory model
    public function subcategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }
}
