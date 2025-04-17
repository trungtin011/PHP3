<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'import_price', 'price', 'weight', 'stock', 'status',
        'category_id', 'brand_id', 'image', 'main_image', 'additional_images',
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

    // Optional: Accessor for average rating
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->average('rating') ?: 0;
    }

    // Optional: Accessor for sold count
    public function getSoldCountAttribute()
    {
        return \App\Models\OrderItem::where('product_id', $this->id)->sum('quantity') ?: '1k+';
    }

    // Optional: Accessor for discount (if needed later)
    public function getDiscountAttribute()
    {
        return 0; // Replace with actual discount logic if available
    }
}