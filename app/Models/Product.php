<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'price', 'weight', 'stock', 'status',
        'category_id', 'brand_id', 'image', 'main_image', 'additional_images'
    ];

    protected $casts = [
        'additional_images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
 public function getAverageRatingAttribute()
    {
  
        return $this->reviews()->avg('rating') ?: 0;
    }
}