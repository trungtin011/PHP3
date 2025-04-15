<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function list(Request $request)
    {
        $query = Product::where('status', 'in_stock')->with(['category', 'brand']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $categoryId = $request->category;
            $childCategoryIds = Category::where('parent_id', $categoryId)->pluck('id')->toArray();
            $categoryIds = array_merge([$categoryId], $childCategoryIds);
            $query->whereIn('category_id', $categoryIds);
        }

        if ($request->filled('brand')) {
            $query->whereIn('brand_id', (array)$request->brand);
        }

        if ($request->filled('price_min') && $request->filled('price_max')) {
            $priceMin = max(0, (int)$request->price_min);
            $priceMax = (int)$request->price_max;
            if ($priceMax > $priceMin) {
                $query->whereBetween('price', [$priceMin, $priceMax]);
            }
        }

        $categories = Category::whereNull('parent_id')->with('children')->get();
        $brands = Brand::all();

      
        $products = $query->paginate(9)->appends($request->query());

        return view('user.products.list', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'brand', 'reviews.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'in_stock')
            ->with(['category', 'brand', 'reviews'])
            ->limit(4)
            ->get();

        return view('user.products.show', compact('product', 'relatedProducts'));
    }

    public function storeReview(Request $request, $slug)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để gửi đánh giá.');
        }

        $product = Product::where('slug', $slug)->firstOrFail();

        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi.');
    }
}