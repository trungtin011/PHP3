<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->get();

        $hotProducts = Product::select('products.*')
            ->with(['reviews' => function ($query) {
                $query->select('product_id', DB::raw('AVG(rating) as average_rating'))
                    ->groupBy('product_id');
            }])
            ->whereIn('id', function ($query) {
                $query->select('products.id')
                    ->from('products')
                    ->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
                    ->groupBy('products.id')
                    ->havingRaw('AVG(reviews.rating) >= 4 OR products.stock > 20');
            })
            ->limit(10)
            ->get()
            ->map(function ($product) {
                $product->average_rating = $product->reviews->first()->average_rating ?? 0;
                $product->sold_quantity = $product->orderItems()->sum('quantity') ?? 0;
                return $product;
            });

        return view('user.welcome', compact('categories', 'hotProducts'));
    }
}