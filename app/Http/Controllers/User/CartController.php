<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart; 
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        return view('user.cart.index', compact('cartItems'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $productId)
                        ->first();

        $price = $product->price; 

        if ($cartItem) {
            if ($cartItem->quantity + 1 > $product->stock) {
                return redirect()->route('user.cart.index')->with('error', 'Số lượng sản phẩm vượt quá tồn kho.');
            }
            $cartItem->increment('quantity');
            $cartItem->update([
                'total' => $cartItem->quantity * $price,
            ]);
        } else {
            if ($product->stock < 1) {
                return redirect()->route('user.cart.index')->with('error', 'Sản phẩm đã hết hàng.');
            }
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'product_name' => $product->title,
                'product_image' => $product->main_image,
                'product_sku' => $product->sku ?? null, 
                'quantity' => 1,
                'price' => $price,
                'total' => $price,
            ]);
        }

        return redirect()->route('user.cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function update(Request $request, $cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $product = $cartItem->product;

        if ($request->quantity > $product->stock) {
            return redirect()->route('user.cart.index')->with('error', 'Số lượng sản phẩm vượt quá tồn kho.');
        }

        $price = $product->price;

        $cartItem->update([
            'quantity' => $request->quantity,
            'total' => $request->quantity * $price,
        ]);

        return redirect()->route('user.cart.index')->with('success', 'Giỏ hàng đã được cập nhật.');
    }

    public function updateQuantity(Request $request, $cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $product = $cartItem->product;

        if ($request->quantity > $product->stock) {
            return response()->json(['error' => 'Số lượng sản phẩm vượt quá tồn kho.'], 400);
        }

        $price = $product->price;

        $cartItem->update([
            'quantity' => $request->quantity,
            'total' => $request->quantity * $price,
        ]);

        return response()->json([
            'success' => 'Giỏ hàng đã được cập nhật.',
            'total' => number_format($cartItem->total, 0, ',', '.') . ' đ',
        ]);
    }

    public function remove($cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $cartItem->delete();

        return redirect()->route('user.cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }


    private function getServiceId($fromDistrictId, $toDistrictId)
    {
        $response = Http::withHeaders([
            'Token' => '7dd71557-160d-11f0-95d0-0a92b8726859',
            'ShopId' => '2509766',
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services', [
            'from_district' => $fromDistrictId,
            'to_district' => $toDistrictId,
        ]);

        if ($response->successful()) {
            $services = $response->json()['data'] ?? [];
            return $services[0]['service_id'] ?? null; 
        }

        return null; 
    }

    private function getShippingFee($toDistrictId, $weight)
    {
        $fromDistrictId = 1450; 
        $serviceId = $this->getServiceId($fromDistrictId, $toDistrictId);

        if (!$serviceId) {
            return 0; 
        }

        $response = Http::withHeaders([
            'Token' => '7dd71557-160d-11f0-95d0-0a92b8726859', 
            'ShopId' => '2509766',
        ])->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', [
            'from_district_id' => $fromDistrictId,
            'to_district_id' => $toDistrictId,
            'service_id' => $serviceId,
            'weight' => $weight, 
            'length' => 20,
            'width' => 20,
            'height' => 10,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['data']['total'] ?? 0;
        }

        return 0; 
    }

    public function checkout(Request $request)
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $addresses = Auth::user()->addresses;
        $total = $cartItems->sum('total');
        $discount = 0;

        $totalWeight = $cartItems->sum(function ($item) {
            return $item->product->weight * $item->quantity;
        });

        $shippingFee = 0;
        if ($addresses->isNotEmpty()) {
            $defaultAddress = $addresses->where('default', true)->first();
            if ($defaultAddress) {
                $shippingFee = $this->getShippingFee($defaultAddress->district_id, $totalWeight);
            }
        }

        if ($request->has('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if ($coupon && $coupon->isValid()) {
                if ($coupon->discount_type === 'percentage') {
                    $discount = $total * ($coupon->discount_value / 100);
                } elseif ($coupon->discount_type === 'fixed') {
                    $discount = $coupon->discount_value;
                }
            } else {
                return redirect()->route('user.checkout')->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
            }
        }

        $totalAfterDiscount = $total + $shippingFee - $discount;

        return view('user.checkout.index', compact('cartItems', 'total', 'discount', 'totalAfterDiscount', 'shippingFee', 'addresses'));
    }

    public function placeOrder(Request $request)
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $paymentMethod = $request->input('payment_method', 'COD');

        if ($paymentMethod === 'COD') {
            $order = \App\Models\Order::createOrder(
                Auth::id(),
                $request->input('address', 'Địa chỉ mặc định'),
                $paymentMethod,
                $cartItems,
                0, 
                0, 
                $request->input('notes') 
            );

            Cart::where('user_id', Auth::id())->delete();

            Mail::send('emails.order_confirmation', ['order' => $order], function ($message) use ($order) {
                $message->to($order->user->email)
                        ->subject('Xác nhận đơn hàng #' . $order->id);
            });

            return redirect()->route('user.thankyou')->with('success', 'Đơn hàng của bạn đã được đặt thành công.');
        }

        if ($paymentMethod === 'vnpay') {
            return redirect()->route('vnpay.payment')->with('amount', $cartItems->sum('total'));
        } elseif ($paymentMethod === 'momo') {
            return redirect()->route('momo.payment')->with('amount', $cartItems->sum('total'));
        }

        return redirect()->route('user.checkout')->with('error', 'Phương thức thanh toán không hợp lệ.');
    }

    public function thankYou()
    {
        return view('user.thankyou');
    }

    public function testServiceId()
    {
        $fromDistrictId = 1450; // Example: Quận 1
        $toDistrictId = 1542; // Example: Bình Thạnh

        $serviceId = $this->getServiceId($fromDistrictId, $toDistrictId);

        return $serviceId ? "Service ID: $serviceId" : "No service ID found.";
    }

    public function testShippingFee()
    {
        $toDistrictId = 1542; // Example: Bình Thạnh District
        $weight = 1000; // Example: 1kg

        $fee = $this->getShippingFee($toDistrictId, $weight);

        return "Phí vận chuyển là: " . number_format($fee, 0, ',', '.') . ' đ';
    }
}
