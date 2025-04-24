<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Tìm kiếm sản phẩm
        $products = Product::query()
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->take(10)
            ->get();

        // Tạo HTML cho kết quả
        $html = '';
        if ($products->isEmpty()) {
            $html = '<div class="no-results">Không tìm thấy sản phẩm nào.</div>';
        } else {
            foreach ($products as $product) {
                $html .= '
                    <a href="' . route('products.show', $product->slug) . '" class="result-item">
                        <img src="' . asset('storage/' . $product->main_image) . '" alt="' . htmlspecialchars($product->title) . '">
                        <div>
                            <div class="title">' . htmlspecialchars($product->title) . '</div>
                            <div class="price">' . number_format($product->price, 0, ',', '.') . ' VNĐ</div>
                        </div>
                    </a>';
            }
        }

        return response()->json(['html' => $html]);
    }
}