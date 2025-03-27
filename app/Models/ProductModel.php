<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'price', 'stock', 'status', 'image', 'slug', 'category_id', 'brand_id'
    ];



    }

   
