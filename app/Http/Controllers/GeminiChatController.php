<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeminiChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $userMessage = strtolower($request->input('message'));

        // Analyze the user's message and determine the intent
        $intent = $this->detectIntent($userMessage);

        // Fetch data based on the detected intent
        $response = $this->handleIntent($intent, $userMessage);

        return response()->json(['reply' => $response]);
    }

    private function detectIntent($message)
    {
        $keywords = [
            'products' => ['sản phẩm', 'product', 'mặt hàng'],
            'categories' => ['danh mục', 'category', 'loại'],
            'coupons' => ['mã giảm giá', 'coupon', 'khuyến mãi'],
        ];

        foreach ($keywords as $intent => $words) {
            foreach ($words as $word) {
                if (str_contains($message, $word)) {
                    return $intent;
                }
            }
        }

        return 'unknown';
    }

    private function handleIntent($intent, $message)
    {
        switch ($intent) {
            case 'products':
                return $this->getProducts();
            case 'categories':
                return $this->getCategories();
            case 'coupons':
                return $this->getCoupons();
            default:
                return 'Xin lỗi, tôi không hiểu câu hỏi của bạn. Vui lòng thử lại.';
        }
    }

    private function getProducts()
    {
        $products = \App\Models\Product::where('status', 'in_stock')->take(5)->get(['title', 'price', 'main_image']);
        if ($products->isEmpty()) {
            return 'Hiện tại cửa hàng không có sản phẩm nào.';
        }

        $response = "<div>Dưới đây là một số sản phẩm của cửa hàng:</div>";
        foreach ($products as $product) {
            $imageUrl = asset('storage/' . $product->main_image);
            $response .= "
                <div style='margin-bottom: 15px;'>
                    <img src='{$imageUrl}' alt='{$product->title}' style='width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin-right: 10px;'>
                    <div>
                        <strong>{$product->title}</strong><br>
                        Giá: " . number_format($product->price, 0, ',', '.') . " đ
                    </div>
                </div>
            ";
        }
        return $response;
    }

    private function getCategories()
    {
        $categories = \App\Models\Category::whereNull('parent_id')->take(5)->get(['name']);
        if ($categories->isEmpty()) {
            return 'Hiện tại cửa hàng không có danh mục nào.';
        }

        $response = "<div>Dưới đây là một số danh mục sản phẩm:</div>";
        foreach ($categories as $category) {
            $response .= "<div>- {$category->name}</div>";
        }
        return $response;
    }

    private function getCoupons()
    {
        $coupons = \App\Models\Coupon::where('expires_at', '>', now())->take(5)->get(['code', 'discount_type', 'discount_value']);
        if ($coupons->isEmpty()) {
            return 'Hiện tại không có mã giảm giá nào.';
        }

        $response = "<div>Dưới đây là một số mã giảm giá hiện có:</div>";
        foreach ($coupons as $coupon) {
            $discount = $coupon->discount_type === 'percentage' ? "{$coupon->discount_value}%" : number_format($coupon->discount_value, 0, ',', '.') . " đ";
            $response .= "<div>- Mã: {$coupon->code}, Giảm: {$discount}</div>";
        }
        return $response;
    }
}
