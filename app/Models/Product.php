<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'price', 'import_price', 'weight', 'stock', 'status',
        'category_id', 'brand_id', 'image', 'main_image', 'additional_images', 'has_variants',
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

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->average('rating') ?: 0;
    }

    public function getSoldCountAttribute()
    {
        return $this->orderItems()->sum('quantity') ?: 0;
    }
}