<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Category;
use App\Models\ChatHistory;
use App\Models\Coupon;

class GeminiChatController extends Controller
{
    public function index()
    {
        $chatHistory = Auth::check() ? ChatHistory::where('user_id', Auth::id())->latest()->take(20)->get() : [];
        return view('layouts.header', compact('chatHistory'));
    }

    public static function getRecentChats()
    {
        return Auth::check() ? ChatHistory::where('user_id', Auth::id())->latest()->take(5)->get() : collect();
    }

    public function send(Request $request)
    {
        $message = $request->input('message');
        $user = Auth::user();
        $name = $user ? $user->name : 'bạn';

        if (empty($message)) {
            return response()->json(['reply' => 'Vui lòng nhập nội dung câu hỏi.']);
        }

        $intent = $this->detectIntent($message);

        if (!Auth::check() && $intent !== 'other') {
            return response()->json(['reply' => "Bạn cần đăng nhập để sử dụng chức năng này."]);
        }

        $reply = $this->handleIntent($intent, $message, $name);

        // Save chat history
        if (Auth::check()) {
            ChatHistory::create([
                'user_id' => Auth::id(),
                'message' => $message,
                'reply' => $reply,
            ]);
        }

        return response()->json(['reply' => $reply]);
    }

    public function getChatHistory()
    {
        if (!Auth::check()) {
            return response()->json(['history' => []]);
        }

        $chatHistory = ChatHistory::where('user_id', Auth::id())->latest()->take(20)->get(['message', 'reply']);
        return response()->json(['history' => $chatHistory]);
    }

    private function detectIntent(string $message): string
    {
        $prompt = <<<PROMPT
Bạn là hệ thống AI, hãy phân tích và trả về đúng JSON: { "intent": "..." }

Các intent hợp lệ:
- product_count: hỏi số lượng sản phẩm
- category_count: hỏi số danh mục
- list_products: xem danh sách sản phẩm
- product_info: hỏi chi tiết sản phẩm
- other: các câu không thuộc nhóm trên

Câu: "$message"
Trả lời:
PROMPT;

        try {
            $response = Http::post(env('GEMINI_API_URL') . '?key=' . env('GEMINI_API_KEY'), [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            preg_match('/\{.*\}/s', $text, $match);
            $intent = json_decode($match[0] ?? '', true)['intent'] ?? 'other';

            return $intent;
        } catch (\Exception $e) {
            Log::error('Intent detect error: ' . $e->getMessage());
            return 'other';
        }
    }

    private function handleIntent(string $intent, string $message, string $name): string
    {
        return match ($intent) {
            'product_count' => "Hiện có " . Product::count() . " sản phẩm trong hệ thống.",
            'category_count' => "Tổng số danh mục là " . Category::count() . ".",
            'list_products' => $this->listProducts($name),
            'product_info' => $this->productInfo($message, $name),
            'list_coupons' => $this->listCoupons($name),
            default => $this->chatWithGemini($message, $name),
        };
    }

    private function listProducts(string $name): string
    {
        $products = Product::with('category')->select('title', 'price', 'main_image', 'category_id')->take(10)->get();

        if ($products->isEmpty()) {
            return "Hiện tại chưa có sản phẩm nào.";
        }

        $html = "<div>Chào $name! Đây là một số sản phẩm:</div>";
        foreach ($products as $product) {
            $image = $product->main_image ? asset('storage/' . $product->main_image) : asset('default-product.jpg');
            $price = number_format($product->price, 0, ',', '.');
            $title = e($product->title);
            $category = $product->category->name ?? 'Không rõ';

            $html .= "<div style='margin-bottom: 10px; display: flex; gap: 10px;'>";
            $html .= "<img src='$image' style='width: 100px; height: auto;' alt='$title'>";
            $html .= "<div><strong>$title</strong><br>Giá: $price VNĐ<br>Danh mục: $category</div>";
            $html .= "</div>";
        }

        return $html;
    }

    private function productInfo(string $message, string $name): string
    {
        $keyword = $this->extractKeyword($message);

        if (!$keyword) {
            return "Bạn có thể nói rõ tên sản phẩm cần tìm không?";
        }

        $product = Product::with('category')->where('title', 'LIKE', "%$keyword%")->first();

        if (!$product) {
            return "Mình không tìm thấy sản phẩm nào phù hợp với \"$keyword\".";
        }

        $image = $product->main_image ? asset('storage/' . $product->main_image) : asset('default-product.jpg');
        $price = number_format($product->price, 0, ',', '.');
        $title = e($product->title);
        $description = e($product->description ?? 'Không có mô tả.');
        $status = $product->status === 'in_stock' ? 'Còn hàng' : 'Hết hàng';
        $category = $product->category->name ?? 'Không rõ';

        $html = "<div><strong>Sản phẩm: $title</strong></div>";
        $html .= "<div style='display: flex; gap: 10px; margin-top: 10px;'>";
        $html .= "<img src='$image' style='width: 100px;'>";
        $html .= "<div>Giá: $price VNĐ<br>Mô tả: $description<br>Trạng thái: $status<br>Danh mục: $category</div>";
        $html .= "</div>";

        return $html;
    }

    private function chatWithGemini(string $message, string $name): string
    {
        try {
            $response = Http::post(env('GEMINI_API_URL') . '?key=' . env('GEMINI_API_KEY'), [
                'contents' => [
                    ['parts' => [['text' => $message]]]
                ]
            ]);

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            return $text ?? "Xin lỗi, mình chưa hiểu rõ câu hỏi của bạn.";
        } catch (\Exception $e) {
            Log::error('Gemini chat error: ' . $e->getMessage());
            return "Có lỗi xảy ra khi trả lời, vui lòng thử lại.";
        }
    }

    private function extractKeyword(string $message): ?string
    {
        preg_match('/\b[\w\s]{3,}\b/u', $message, $matches);
        return trim($matches[0] ?? '');
    }

    private function listCoupons(string $name): string
    {
        $coupons = Coupon::where('expires_at', '>', now())
            ->orWhereNull('expires_at')
            ->whereColumn('used_count', '<', 'usage_limit')
            ->get(['code', 'discount_type', 'discount_value', 'expires_at']);

        if ($coupons->isEmpty()) {
            return "Hiện tại không có mã giảm giá nào khả dụng.";
        }

        $html = "<div>Chào $name! Đây là danh sách mã giảm giá khả dụng:</div>";
        foreach ($coupons as $coupon) {
            $type = $coupon->discount_type === 'percentage' ? '%' : 'VNĐ';
            $value = $coupon->discount_value . $type;
            $expires = $coupon->expires_at ? $coupon->expires_at->format('d/m/Y H:i') : 'Không giới hạn';

            $html .= "<div style='margin-bottom: 10px;'>
                        <strong>Mã: {$coupon->code}</strong><br>
                        Giảm: $value<br>
                        Hạn sử dụng: $expires
                      </div>";
        }

        return $html;
    }
}
