<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;

class GeminiChatController extends Controller
{
    public function index()
    {
        return view('chat');  // Hiển thị giao diện chat
    }

    public function send(Request $request)
    {
        $user = Auth::user();
        $name = $user->name ?? 'bạn';
        $userMessage = $request->input('message');

        // Tạo prompt yêu cầu AI phân tích intent
        $prompt = <<<PROMPT
Bạn là một trợ lý thông minh, phân tích câu hỏi và trả về JSON đúng định dạng: { "intent": "..." }.
Không thêm giải thích hoặc ký tự thừa nào. Các intent hợp lệ gồm:
- 'products'
- 'categories'
- 'brands'
- 'coupons'
- 'other'

Câu hỏi: "$userMessage"
PROMPT;

        // Gửi prompt tới Gemini API để phân tích intent
        $aiIntentResponse = $this->queryGemini($prompt);
        $intent = $this->extractIntent($aiIntentResponse);

        // Nếu người dùng chưa đăng nhập và yêu cầu thông tin nhạy cảm
        if (!Auth::check() && in_array($intent, ['products', 'categories', 'brands', 'coupons'])) {
            return $this->reply("Bạn cần đăng nhập để xem thông tin này.", $name);
        }

        // Các xử lý theo intent đã xác định
        $handlers = [
            'products' => fn() => $this->getProducts($name),
            'categories' => fn() => $this->getCategories($name),
            'brands' => fn() => $this->getBrands($name),
            'coupons' => fn() => $this->getCoupons($name),
            'other' => fn() => $this->chatWithGemini($userMessage, $name),
        ];

        return $handlers[$intent]() ?? $handlers['other']();
    }

    private function reply(string $message, string $name)
    {
        return response()->json(['reply' => "Chào $name! $message"]);
    }

    private function chatWithGemini(string $message, string $name)
    {
        $response = $this->queryGemini($message);
        $reply = $this->extractText($response) ?? 'Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Bạn có thể diễn đạt lại không?';
        return $this->reply($reply, $name);
    }

    private function getProducts(string $name)
    {
        $products = Product::where('status', 'in_stock')->take(5)->get(['title', 'price', 'main_image']);
        if ($products->isEmpty()) {
            return $this->reply("Chà, cửa hàng của chúng tôi hôm nay vắng vẻ quá, nhưng đừng lo, bạn sẽ tìm được món yêu thích sớm thôi!", $name);
        }

        $response = "<div>Chào {$name}, tôi đã tìm được vài sản phẩm cho bạn:</div>";
        foreach ($products as $product) {
            $imageUrl = asset('storage/' . $product->main_image);
            $response .= "
                <div style='margin-bottom: 15px; display: flex; align-items: center;'>
                    <img src='{$imageUrl}' alt='{$product->title}' style='width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin-right: 10px;'>
                    <div>
                        <strong>{$product->title}</strong><br>
                        Giá: " . number_format($product->price, 0, ',', '.') . " đ<br>
                        <em>Chắc chắn bạn sẽ yêu thích sản phẩm này!</em>
                    </div>
                </div>
            ";
        }

        return response()->json(['reply' => $response]);
    }

    private function getCategories(string $name)
    {
        $categories = Category::whereNull('parent_id')->take(5)->get(['name']);
        if ($categories->isEmpty()) {
            return $this->reply("Ôi, cửa hàng của chúng tôi chẳng có danh mục gì mới hôm nay. Chắc chắn sẽ có thêm nhiều lựa chọn trong thời gian tới!", $name);
        }

        $response = "<div>Dưới đây là một số danh mục sản phẩm bạn có thể tham khảo:</div>";
        foreach ($categories as $category) {
            $response .= "<div>- {$category->name}</div>";
        }

        return response()->json(['reply' => $response]);
    }

    private function getBrands(string $name)
    {
        $brands = Product::select('brand')->distinct()->take(5)->get(); // Lấy tất cả thương hiệu duy nhất
        if ($brands->isEmpty()) {
            return $this->reply("Hôm nay không có thương hiệu mới, nhưng đừng lo, chúng tôi luôn có các sản phẩm chất lượng!", $name);
        }

        $response = "<div>Dưới đây là một số thương hiệu của cửa hàng:</div>";
        foreach ($brands as $brand) {
            $response .= "<div>- {$brand->brand}</div>";
        }

        return response()->json(['reply' => $response]);
    }

    private function getCoupons(string $name)
    {
        $coupons = Coupon::where('expires_at', '>', now())->take(5)->get(['code', 'discount_type', 'discount_value']);
        if ($coupons->isEmpty()) {
            return $this->reply("Hiện tại không có mã giảm giá nào, nhưng đừng lo, cửa hàng sẽ có nhiều chương trình khuyến mãi sắp tới!", $name);
        }

        $response = "<div>Dưới đây là một số mã giảm giá hiện có:</div>";
        foreach ($coupons as $coupon) {
            $discount = $coupon->discount_type === 'percentage'
                ? "{$coupon->discount_value}%"
                : number_format($coupon->discount_value, 0, ',', '.') . " đ";
            $response .= "<div>- Mã: <strong>{$coupon->code}</strong>, Giảm: <strong>{$discount}</strong></div>";
        }

        return response()->json(['reply' => $response]);
    }

    private function queryGemini(string $prompt)
    {
        return Http::post(env('GEMINI_API_URL') . '?key=' . env('GEMINI_API_KEY'), [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ])->json();
    }

    private function extractIntent($aiResponse): string
    {
        $text = $this->extractText($aiResponse);
        preg_match('/\{.*?\}/s', $text, $matches);
        if (!empty($matches[0])) {
            $json = json_decode($matches[0], true);
            if (json_last_error() === JSON_ERROR_NONE && isset($json['intent'])) {
                return $json['intent'];
            }
        }
        return 'other';
    }

    private function extractText($data): ?string
    {
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }
}
