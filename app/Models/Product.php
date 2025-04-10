<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'title', 'description', 'price', 'stock', 'status', 'category_id', 'brand_id', 'image', 'slug', 'main_image', 'additional_images'
    ];

    protected $casts = [
        'additional_images' => 'array', // Cast additional_images to array
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
