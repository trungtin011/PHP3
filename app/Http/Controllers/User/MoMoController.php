<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;

class MoMoController extends Controller
{
    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

        $amount = $request->input('amount');
        $order = Order::create([
            'user_id' => Auth::id(),
            'address' => 'Địa chỉ mặc định',
            'payment_method' => 'momo',
            'total' => $amount,
            'status' => 'pending',
        ]);

        $payUrl = $this->generateMomoUrl($order, $amount);

        // Log MoMo URL
        Log::info("Redirecting to MoMo: $payUrl");

        // Redirect to MoMo
        return redirect()->away($payUrl);
    }

    private function generateMomoUrl($order, $amount)
    {
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $endpoint = env('MOMO_URL', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $returnUrl = route('momo.callback');

        if (empty($partnerCode) || empty($accessKey) || empty($secretKey)) {
            Log::error('MoMo configuration is missing in .env');
            abort(500, 'Cấu hình MoMo không hợp lệ');
        }

        $orderId = $order->id . 'MOMOPAY' . rand(10000, 99999);
        $orderInfo = "Thanh toán đơn hàng #$order->id";
        $requestId = $partnerCode . time();
        // $requestType = "captureWallet";
        $requestType = "payWithATM"; // Hoặc "payWithMethod" nếu bạn có hỗ trợ đa phương thức
        $extraData = "";

        $rawSignature = "accessKey=$accessKey" .
            "&amount=$amount" .
            "&extraData=$extraData" .
            "&ipnUrl=$returnUrl" .
            "&orderId=$orderId" .
            "&orderInfo=$orderInfo" .
            "&partnerCode=$partnerCode" .
            "&redirectUrl=$returnUrl" .
            "&requestId=$requestId" .
            "&requestType=$requestType";

        $signature = hash_hmac("sha256", $rawSignature, $secretKey);

        $data = [
            "partnerCode" => $partnerCode,
            "accessKey" => $accessKey,
            "requestId" => $requestId,
            "amount" => $amount,
            "orderId" => $orderId,
            "orderInfo" => $orderInfo,
            "redirectUrl" => $returnUrl,
            "ipnUrl" => $returnUrl,
            "extraData" => $extraData,
            "requestType" => $requestType,
            "signature" => $signature,
            "lang" => "vi"
        ];

        $response = $this->execPostRequest($endpoint, json_encode($data));
        $result = json_decode($response, true);

        if (!empty($result['payUrl'])) {
            return $result['payUrl'];
        } else {
            Log::error('MoMo payment URL creation failed', ['response' => $result]);
            abort(500, 'Không thể tạo URL thanh toán MoMo');
        }
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            Log::error('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function momoCallback(Request $request)
    {
        $data = $request->all();
        $secretKey = env('MOMO_SECRET_KEY');

        Log::info('MoMo Callback Data: ', $data);

        if (!isset($data['orderId']) || !isset($data['resultCode'])) {
            Log::error('Invalid MoMo callback data', $data);
            return redirect()->route('user.checkout')->with('error', 'Dữ liệu callback không hợp lệ');
        }

        $orderIdParts = explode('MOMOPAY', $data['orderId']);
        $originalOrderId = $orderIdParts[0];

        $rawSignature = "accessKey=" . env('MOMO_ACCESS_KEY') .
            "&amount={$data['amount']}" .
            "&extraData={$data['extraData']}" .
            "&message={$data['message']}" .
            "&orderId={$data['orderId']}" .
            "&orderInfo={$data['orderInfo']}" .
            "&orderType={$data['orderType']}" .
            "&partnerCode={$data['partnerCode']}" .
            "&payType={$data['payType']}" .
            "&requestId={$data['requestId']}" .
            "&responseTime={$data['responseTime']}" .
            "&resultCode={$data['resultCode']}" .
            "&transId={$data['transId']}";

        $calculatedSignature = hash_hmac('sha256', $rawSignature, $secretKey);

        if ($calculatedSignature === $data['signature']) {
            $order = Order::find($originalOrderId);

            if ($order) {
                if ($data['resultCode'] == '0') {
                    $order->update(['status' => 'completed']);
                    Mail::send('emails.order_confirmation', ['order' => $order], function ($message) use ($order) {
                        $message->to($order->user->email)
                                ->subject('Xác nhận đơn hàng #' . $order->id);
                    });
                    return redirect()->route('user.thankyou')->with('success', 'Thanh toán MoMo thành công!');
                } else {
                    $order->update(['status' => 'failed']);
                    return redirect()->route('user.checkout')->with('error', 'Thanh toán MoMo không thành công.');
                }
            } else {
                Log::error('Order not found', ['orderId' => $originalOrderId]);
                return redirect()->route('user.checkout')->with('error', 'Không tìm thấy đơn hàng.');
            }
        } else {
            Log::error('Invalid MoMo signature', [
                'calculated' => $calculatedSignature,
                'received' => $data['signature'],
                'rawSignature' => $rawSignature
            ]);
            return redirect()->route('user.checkout')->with('error', 'Chữ ký không hợp lệ.');
        }
    }
}

