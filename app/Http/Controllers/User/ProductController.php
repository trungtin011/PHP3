<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function list(Request $request)
    {
        $query = Product::where('status', 'in_stock');

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(9);

        return view('user.products.list', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::with('reviews.user')->where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'in_stock')
            ->with('reviews')
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
    public function export()
    {
        $products = Product::all();
    
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=products.xls");
    
        echo "ID\tName\tPrice\tCategory\n";
        foreach ($products as $product) {
            echo "{$product->id}\t{$product->name}\t{$product->price}\t{$product->category->name}\n";
        }
        exit;
    }
    
   
   
    
}