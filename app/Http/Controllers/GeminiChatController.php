<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class GeminiChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        try {
            $user = Auth::user();
            $name = $user ? e($user->name) : 'bạn';
            $userMessage = $request->input('message');

            if (empty($userMessage)) {
                return response()->json(['reply' => 'Vui lòng nhập tin nhắn.']);
            }

            $prompt = <<<PROMPT
            Bạn là một người trợ lý ảo thông minh, có khả năng phân tích câu hỏi và trả lời một cách tự nhiên.
            Phân tích câu sau và CHỈ trả về JSON đúng định dạng: { "intent": "..." }.
            Không thêm giải thích hoặc ký tự thừa.
            Các intent hợp lệ gồm: 'product_count', 'category_count', 'order_count', 'list_products', 'product_info', 'other'.
            Câu: "$userMessage"
            PROMPT;

            $aiIntentResponse = $this->queryGemini($prompt);
            $intent = $this->extractIntent($aiIntentResponse);

            if (!Auth::check() && $intent !== 'other') {
                return $this->reply("Bạn cần đăng nhập để sử dụng chatbot.", $name);
            }

            $intentHandlers = [
                'product_count' => fn() => $this->reply("Hiện tại, chúng tôi có " . Product::count() . " sản phẩm.", $name),
                'category_count' => fn() => $this->reply("Hiện tại, chúng tôi có " . Category::count() . " danh mục.", $name),
                'order_count' => fn() => $this->reply("Bạn có " . Order::where('user_id', $user->id)->count() . " đơn hàng.", $name),
                'list_products' => fn() => $this->listProducts($name),
                'product_info' => fn() => $this->productInfo($userMessage, $name),
                'other' => fn() => $this->chatWithGemini($userMessage, $name)
            ];

            return ($intentHandlers[$intent] ?? $intentHandlers['other'])();
        } catch (\Exception $e) {
            Log::error('Chat send error: ' . $e->getMessage());
            return response()->json(['reply' => 'Có lỗi xảy ra, vui lòng thử lại sau.'], 500);
        }
    }

    private function reply(string $message, string $name)
    {
        return response()->json(['reply' => $message]);
    }

    private function listProducts($name)
{
    try {
        $products = Product::with('category')
            ->select('id', 'title', 'price', 'image', 'category_id')
            ->take(10)
            ->get();

        if ($products->isEmpty()) {
            return $this->reply("Hiện tại chưa có sản phẩm nào.", $name);
        }

        $response = "<div>Chào $name! Các sản phẩm hiện có:</div>";
        foreach ($products as $product) {
            $imageUrl = $product->image
                ? asset('storage/' . $product->image)
                : asset('http://localhost:8000/storage/products/images/D3lKY1xIpx9vuuEGHdl1BPkJqXbGHp29fb0EPxHl.jpg');

            $title = e($product->title);
            $price = number_format($product->price, 2, ',', '.');
            $categoryName = $product->category->name ?? 'Không rõ danh mục';

            $response .= "<div class=\"product-item\" style=\"margin-bottom: 10px;\">";
            $response .= "<img src=\"$imageUrl\" alt=\"$title\" style=\"max-width: 100px; margin-right: 10px;\">";
            $response .= "<div>";
            $response .= "<strong>$title</strong><br>";
            $response .= "Danh mục: <em>$categoryName</em><br>";
            $response .= "Giá: <span style=\"color: #D4AF37;\">$price VNĐ</span>";
            $response .= "</div>";
            $response .= "</div>";
        }

        Log::info('List products response: ' . strip_tags($response));
        return response()->json(['reply' => $response]);
    } catch (\Exception $e) {
        Log::error('List products error: ' . $e->getMessage());
        return $this->reply("Không thể tải danh sách sản phẩm, vui lòng thử lại.", $name);
    }
}


    private function productInfo(string $message, string $name)
    {
        try {
            $prompt = <<<PROMPT
            Bạn là trợ lý bán hàng AI của một cửa hàng trực tuyến.
            Yêu cầu:
            - Dựa vào câu hỏi để xác định sản phẩm mà người dùng muốn tìm hiểu.
            - Trả lời ngắn gọn, rõ ràng, cung cấp thông tin về sản phẩm (tên, giá, mô tả, hoặc trạng thái tồn kho).
            - Nếu không rõ sản phẩm, trả lời chung chung nhưng thân thiện.
            Câu hỏi của người dùng:
            "$message"
            PROMPT;

            $aiResponse = $this->queryGemini($prompt);
            $reply = $this->extractText($aiResponse) ?? 'Xin lỗi, mình chưa hiểu sản phẩm bạn đang hỏi.';

            preg_match('/\b[\w\s]+\b/', $message, $matches);
            $keyword = $matches[0] ?? '';
            if ($keyword) {
                $product = Product::where('title', 'LIKE', "%$keyword%")->first();
                if ($product) {
                    $imageUrl = $product->image ? asset($product->image) : 'http://localhost:8000/storage/products/images/D3lKY1xIpx9vuuEGHdl1BPkJqXbGHp29fb0EPxHl.jpg';
                    $title = e($product->title);
                    $price = number_format($product->price, 2, ',', '.');
                    $description = e($product->description ?? 'Không có mô tả.');
                    $status = $product->status === 'in_stock' ? 'Còn hàng' : 'Hết hàng';
                    $reply = "<div>Sản phẩm: $title</div>";
                    $reply .= "<div class=\"product-item\">";
                    $reply .= "<img src=\"$imageUrl\" alt=\"$title\">";
                    $reply .= "<div>";
                    $reply .= "<strong>$title</strong><br>";
                    $reply .= "Giá: <span style=\"color: #D4AF37;\">$price VNĐ</span><br>";
                    $reply .= "Mô tả: $description<br>";
                    $reply .= "Trạng thái: $status";
                    $reply .= "</div>";
                    $reply .= "</div>";
                }
            }

            Log::info('Product info response: ' . $reply);
            return response()->json(['reply' => $reply]);
        } catch (\Exception $e) {
            Log::error('Product info error: ' . $e->getMessage());
            return $this->reply("Không thể tải thông tin sản phẩm, vui lòng thử lại.", $name);
        }
    }

    private function chatWithGemini(string $message, string $name)
    {
        try {
            if (!Auth::check()) {
                return $this->reply("Bạn cần đăng nhập để sử dụng đầy đủ chức năng của chatbot.", $name);
            }

            $response = $this->queryGemini($message);
            $reply = $this->extractText($response) ?? 'Xin lỗi, tôi chưa hiểu câu hỏi của bạn.';

            Log::info('Chat with Gemini response: ' . $reply);
            return $this->reply($reply, $name);
        } catch (\Exception $e) {
            Log::error('Chat with Gemini error: ' . $e->getMessage());
            return $this->reply("Có lỗi xảy ra, vui lòng thử lại.", $name);
        }
    }

    private function queryGemini(string $prompt)
    {
        try {
            $response = Http::post(env('GEMINI_API_URL') . '?key=' . env('GEMINI_API_KEY'), [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                throw new \Exception('Gemini API request failed: ' . $response->status());
            }

            $jsonResponse = $response->json();
            Log::info('Gemini API response: ', $jsonResponse);
            return $jsonResponse;
        } catch (\Exception $e) {
            Log::error('Gemini API error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function extractIntent($aiResponse): string
    {
        try {
            $text = $this->extractText($aiResponse);
            if (!$text) {
                Log::warning('No text extracted from Gemini response');
                return 'other';
            }

            preg_match('/\{.*?\}/s', $text, $matches);
            if (!empty($matches[0])) {
                $json = json_decode($matches[0], true);
                if (json_last_error() === JSON_ERROR_NONE && isset($json['intent'])) {
                    return $json['intent'];
                }
            }
            Log::warning('Invalid intent JSON: ' . $text);
            return 'other';
        } catch (\Exception $e) {
            Log::error('Extract intent error: ' . $e->getMessage());
            return 'other';
        }
    }

    private function extractText($data): ?string
    {
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if (!$text) {
            Log::warning('No text found in Gemini response: ', $data);
        }
        return $text;
    }
}