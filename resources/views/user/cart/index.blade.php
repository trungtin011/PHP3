@extends('layouts.user')

@section('title', 'Giỏ Hàng')

@section('content')
<div class="container mt-5" style="max-width: 1200px;">
    <h2 style="color: #ee4d2d; font-weight: bold;">Giỏ Hàng</h2>

    @if(session('success'))
        <div class="alert alert-success" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
            {{ session('error') }}
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="empty-cart" style="text-align: center; padding: 50px; background: #fff; border-radius: 4px;">
            <img src="https://deo.shopeesz.com/shopee/shopee-pcmall-live-sg/assets/9bdd8040b334d31946f4.png" alt="Empty Cart" style="width: 100px;">
            <p style="color: #757575; font-size: 16px;">Giỏ hàng của bạn đang trống.</p>
            <a href="{{ route('user.products') }}" class="btn" style="background-color: #ee4d2d; color: white; padding: 10px 20px; border-radius: 4px;">Mua sắm ngay</a>
        </div>
    @else
        <div class="cart-container" style="background: #fff; padding: 20px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
            <table class="table" style="width: 100%;">
                <thead style="background: #f5f5f5;">
                    <tr>
                        <th style="padding: 15px;">Sản phẩm</th>
                        <th style="padding: 15px;">Đơn giá</th>
                        <th style="padding: 15px;">Số lượng</th>
                        <th style="padding: 15px;">Thành tiền</th>
                        <th style="padding: 15px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="display: flex; align-items: center; padding: 15px;">
                                <img src="{{ asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}" style="width: 80px; height: 80px; object-fit: cover; margin-right: 15px; border: 1px solid #eee;">
                                <span style="font-size: 14px; color: #333;">{{ $item->product_name }}</span>
                            </td>
                            <td style="padding: 15px; color: #757575;">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                            <td style="padding: 15px;">
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control quantity-input" 
                                       data-cart-id="{{ $item->id }}" style="width: 70px; text-align: center; border-radius: 4px;">
                            </td>
                            <td class="total-price" style="padding: 15px; color: #ee4d2d; font-weight: bold;">{{ number_format($item->total, 0, ',', '.') }} đ</td>
                            <td style="padding: 15px;">
                                <form action="{{ route('user.cart.remove', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link" style="color: #ee4d2d; text-decoration: none; font-size: 14px;">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="cart-footer" style="display: flex; justify-content: flex-end; padding: 20px 0; border-top: 1px solid #eee;">
                <div>
                    <span style="font-size: 16px; color: #757575;">Tổng thanh toán: </span>
                    <span style="font-size: 20px; color: #ee4d2d; font-weight: bold;">{{ number_format($cartItems->sum('total'), 0, ',', '.') }} đ</span>
                </div>
                <button class="btn" style="background-color: #ee4d2d; color: white; padding: 10px 40px; margin-left: 20px; border-radius: 4px;">Đặt hàng</button>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quantityInputs = document.querySelectorAll('.quantity-input');

        quantityInputs.forEach(input => {
            input.addEventListener('change', function () {
                const cartId = this.dataset.cartId;
                const quantity = this.value;

                fetch(`{{ url('/cart/update-quantity') }}/${cartId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ quantity: quantity })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        const row = this.closest('tr');
                        const totalPriceElement = row.querySelector('.total-price');
                        totalPriceElement.textContent = `${data.total.toLocaleString('vi-VN')} đ`;

                        // Cập nhật tổng thanh toán
                        const totalSum = Array.from(document.querySelectorAll('.total-price'))
                            .reduce((sum, el) => sum + parseFloat(el.textContent.replace(/[^\d]/g, '')), 0);
                        document.querySelector('.cart-footer span:last-child').textContent = `${totalSum.toLocaleString('vi-VN')} đ`;
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
@endsection