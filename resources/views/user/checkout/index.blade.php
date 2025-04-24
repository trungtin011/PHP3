@extends('layouts.user')

@section('title', 'Thanh Toán')

@section('content')
<div class="container mt-5" style="max-width: 1200px;">
    <h2 style="color: #ee4d2d; font-weight: bold; border-bottom: 2px solid #ee4d2d; padding-bottom: 10px;">Thanh Toán</h2>

    <div class="checkout-container" style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-top: 20px;">
        <!-- Form for applying coupon -->
        <form action="{{ route('user.checkout') }}" method="POST" style="margin-bottom: 20px;">
            @csrf
            <h4 style="color: #ee4d2d; font-size: 18px; margin-bottom: 15px;">
                <i class="fas fa-tag" style="margin-right: 8px;"></i>Mã giảm giá
            </h4>
            <div style="display: flex; align-items: center;">
                <input type="text" name="coupon_code" class="form-control" placeholder="Nhập mã giảm giá" style="border-radius: 4px; padding: 10px; margin-right: 10px;" value="{{ request('coupon_code') }}">
                <button type="submit" class="btn" style="background-color: #ee4d2d; color: white; padding: 0px 40px; border-radius: 4px;">Áp dụng</button>
            </div>
            @if(session('error'))
                <div class="alert alert-danger" style="margin-top: 10px; padding: 10px; border-radius: 4px;">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success" style="margin-top: 10px; padding: 10px; border-radius: 4px;">
                    {{ session('success') }}
                </div>
            @endif
        </form>

        <!-- Form for placing the order -->
        <form action="{{ route('user.order.place') }}" method="POST" id="order-form">
            @csrf
            <!-- Shipping Address -->
            <div class="shipping-info" style="border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
                <h4 style="color: #ee4d2d; font-size: 18px; margin-bottom: 15px;">
                    <i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i>Địa chỉ nhận hàng
                </h4>
                <div class="form-group">
                    <select name="address_id" class="form-control" required>
                        @foreach($addresses as $address)
                            <option value="{{ $address->id }}" {{ $defaultAddress && $defaultAddress->id == $address->id ? 'selected' : '' }}>
                                {{ $address->address }} {{ $address->default ? '(Mặc định)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Order Details -->
            <div class="order-details">
                <table class="table" style="width: 100%; margin-bottom: 20px;">
                    <thead style="background: #fff5f0; color: #555;">
                        <tr>
                            <th style="padding: 12px; font-weight: 500;">Sản phẩm</th>
                            <th style="padding: 12px; font-weight: 500; text-align: center;">Đơn giá</th>
                            <th style="padding: 12px; font-weight: 500; text-align: center;">Số lượng</th>
                            <th style="padding: 12px; font-weight: 500; text-align: right;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr style="border-bottom: 1px solid #f5f5f5;">
                                <td style="padding: 15px; display: flex; align-items: center;">
                                    <img src="{{ asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}" style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #eee; margin-right: 15px; border-radius: 4px;">
                                    <span>{{ $item->product_name }}</span>
                                </td>
                                <td style="padding: 15px; text-align: center; color: #757575;">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                <td style="padding: 15px; text-align: center;">x{{ $item->quantity }}</td>
                                <td style="padding: 15px; text-align: right; color: #ee4d2d; font-weight: bold;">{{ number_format($item->total, 0, ',', '.') }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Payment Method -->
            <div class="payment-method" style="border-top: 1px solid #eee; padding-top: 20px; margin-bottom: 20px;">
                <h4 style="color: #ee4d2d; font-size: 18px; margin-bottom: 15px;">
                    <i class="fas fa-credit-card" style="margin-right: 8px;"></i>Phương thức thanh toán
                </h4>
                <div class="form-group">
                    <select name="payment_method" id="payment_method" class="form-control" style="border-radius: 4px; padding: 10px;" required>
                        <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                        <option value="vnpay">VNPay</option>
                        <option value="momo">MoMo</option>
                    </select>
                </div>
            </div>

            <!-- Notes -->
            <div class="order-notes" style="margin-bottom: 20px;">
                <h4 style="color: #ee4d2d; font-size: 18px; margin-bottom: 15px;">
                    <i class="fas fa-sticky-note" style="margin-right: 8px;"></i>Ghi chú
                </h4>
                <div class="form-group">
                    <textarea name="notes" class="form-control" rows="4" placeholder="Thêm ghi chú cho đơn hàng của bạn..." style="border-radius: 4px; padding: 10px;"></textarea>
                </div>
            </div>
            <div class="checkout-footer" style="background: #fff5f0; padding: 20px; border-radius: 0 0 8px 8px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 14px; color: #757575; margin-bottom: 5px;">
                        Tổng tiền hàng: <span style="color: #ee4d2d;">{{ number_format($total, 0, ',', '.') }} đ</span>
                    </div>
                    <div style="font-size: 14px; color: #757575; margin-bottom: 5px;">
                        Phí vận chuyển: <span style="color: #ee4d2d;">Free shipping</span>
                    </div>
                    @if($discount > 0)
                        <div style="font-size: 14px; color: #757575; margin-bottom: 5px;">
                            Giảm giá: <span style="color: #ee4d2d;">-{{ number_format($discount, 0, ',', '.') }} đ</span>
                        </div>
                    @endif
                    <div style="font-size: 18px; color: #ee4d2d; font-weight: bold; margin-top: 10px;">
                        Tổng thanh toán: <span>{{ number_format($totalAfterDiscount, 0, ',', '.') }} đ</span>
                    </div>
                </div>
                <button type="submit" class="btn" id="place-order-btn" style="background-color: #ee4d2d; color: white; padding: 12px 50px; border-radius: 4px; font-size: 16px; font-weight: bold; border: none;">Đặt hàng</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('place-order-btn').addEventListener('click', function (e) {
        const paymentMethod = document.getElementById('payment_method').value;
        const form = document.getElementById('order-form');
        
        if (paymentMethod === 'vnpay' || paymentMethod === 'momo') {
            e.preventDefault();
            form.action = paymentMethod === 'vnpay' ? '{{ route('vnpay.payment') }}' : '{{ route('momo.payment') }}';
            const amountInput = document.createElement('input');
            amountInput.type = 'hidden';
            amountInput.name = 'amount';
            amountInput.value = '{{ $totalAfterDiscount }}';
            form.appendChild(amountInput);
            form.submit();
        }
    });
</script>
@endsection