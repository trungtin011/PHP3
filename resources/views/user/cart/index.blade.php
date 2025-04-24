@extends('layouts.user')

@section('title', 'Giỏ Hàng')

@section('content')
<div class="container mt-5" style="max-width: 1200px;">
    <h2 style="color: #ee4d2d; font-weight: bold; border-bottom: 2px solid #ee4d2d; padding-bottom: 10px;">Giỏ Hàng</h2>

    @if(session('success'))
        <div class="alert alert-success" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724; border-radius: 4px; padding: 10px; margin-top: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; border-radius: 4px; padding: 10px; margin-top: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="empty-cart" style="text-align: center; padding: 50px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-top: 20px;">
            <img src="https://deo.shopeesz.com/shopee/shopee-pcmall-live-sg/assets/9bdd8040b334d31946f4.png" alt="Empty Cart" style="width: 100px;">
            <p style="color: #757575; font-size: 16px; margin: 20px 0;">Giỏ hàng của bạn đang trống.</p>
            <a href="{{ route('user.products') }}" class="btn" style="background-color: #ee4d2d; color: white; padding: 10px 30px; border-radius: 4px; font-weight: bold;">Mua sắm ngay</a>
        </div>
    @else
        <div class="cart-container" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-top: 20px;">
            <form id="cart-form" action="{{ route('user.checkout') }}" method="POST">
                @csrf
                <table class="table" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                    <thead style="background: #fff5f0; color: #555;">
                        <tr>
                            <th style="padding: 12px; width: 5%;">
                                <input type="checkbox" id="select-all" style="cursor: pointer;">
                            </th>
                            <th style="padding: 12px; width: 40%;">Sản phẩm</th>
                            <th style="padding: 12px; text-align: center; width: 15%;">Đơn giá</th>
                            <th style="padding: 12px; text-align: center; width: 15%;">Số lượng</th>
                            <th style="padding: 12px; text-align: right; width: 15%;">Thành tiền</th>
                            <th style="padding: 12px; text-align: center; width: 10%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr style="border-bottom: 1px solid #f5f5f5;" data-cart-id="{{ $item->id }}">
                                <td style="padding: 15px; vertical-align: middle;">
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="select-item" style="cursor: pointer;">
                                </td>
                                <td style="padding: 15px; display: flex; align-items: center;">
                                    <img src="{{ asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}" style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #eee; margin-right: 15px; border-radius: 4px;">
                                    <span style="font-size: 14px; color: #333;">{{ $item->product_name }}</span>
                                </td>
                                <td style="padding: 15px; text-align: center; color: #757575;">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                <td style="padding: 15px; text-align: center;">
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control quantity-input" 
                                           data-cart-id="{{ $item->id }}" style="width: 70px; text-align: center; border-radius: 4px; display: inline-block;">
                                </td>
                                <td class="total-price" style="padding: 15px; text-align: right; color: #ee4d2d; font-weight: bold;" data-total="{{ $item->total }}">{{ number_format($item->total, 0, ',', '.') }} đ</td>
                                <td style="padding: 15px; text-align: center;">
                                    <button type="button" class="btn btn-link remove-item" data-cart-id="{{ $item->id }}" style="color: #ee4d2d; text-decoration: none; font-size: 14px;">Xóa</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="cart-footer" style="background: #fff5f0; padding: 20px; border-radius: 0 0 8px 8px; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center;">
                        <input type="checkbox" id="select-all-footer" style="margin-right: 10px; cursor: pointer;">
                        <span style="font-size: 14px; color: #757575;">Chọn tất cả</span>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 16px; color: #757575;">Tổng thanh toán (<span id="selected-count">0</span> sản phẩm): </span>
                        <span id="total-sum" style="font-size: 20px; color: #ee4d2d; font-weight: bold;">0 đ</span>
                        <button type="submit" class="btn" style="background-color: #ee4d2d; color: white; padding: 12px 50px; border-radius: 4px; font-size: 16px; font-weight: bold; margin-left: 20px; border: none;">Đặt hàng</button>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        const selectAll = document.getElementById('select-all');
        const selectAllFooter = document.getElementById('select-all-footer');
        const selectItems = document.querySelectorAll('.select-item');
        const removeButtons = document.querySelectorAll('.remove-item');
        const totalSumElement = document.getElementById('total-sum');
        const selectedCountElement = document.getElementById('selected-count');

        // Cập nhật số lượng
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
                        totalPriceElement.dataset.total = data.total;
                        updateTotal();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        // Xóa sản phẩm
        removeButtons.forEach(button => {
            button.addEventListener('click', function () {
                const cartId = this.dataset.cartId;

                if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                    fetch(`{{ url('/cart/remove') }}/${cartId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`tr[data-cart-id="${cartId}"]`).remove();
                            updateTotal();
                            if (document.querySelectorAll('.select-item').length === 0) {
                                window.location.reload(); // Tải lại trang nếu giỏ hàng trống
                            }
                        } else {
                            alert(data.error || 'Xóa sản phẩm thất bại.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });

        // Chọn tất cả
        function toggleSelectAll() {
            const isChecked = this.checked;
            selectItems.forEach(item => item.checked = isChecked);
            updateTotal();
        }
        selectAll.addEventListener('change', toggleSelectAll);
        selectAllFooter.addEventListener('change', toggleSelectAll);

        // Cập nhật tổng tiền và số sản phẩm được chọn
        function updateTotal() {
            let total = 0;
            let selectedCount = 0;
            selectItems.forEach(item => {
                if (item.checked) {
                    const row = item.closest('tr');
                    const totalPrice = parseFloat(row.querySelector('.total-price').dataset.total);
                    total += totalPrice;
                    selectedCount++;
                }
            });
            totalSumElement.textContent = `${total.toLocaleString('vi-VN')} đ`;
            selectedCountElement.textContent = selectedCount;
        }

        selectItems.forEach(item => item.addEventListener('change', updateTotal));

        // Cập nhật tổng ban đầu
        updateTotal();
    });
</script>
@endsection