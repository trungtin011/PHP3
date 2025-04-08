@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3" style="background: #fff; padding: 25px; font-family: Arial, sans-serif;">
        <!-- Tiêu đề giống Shopee -->
        <h2 style="color: #ee4d2d; font-weight: bold; font-size: 28px; margin-bottom: 25px; border-bottom: 2px solid #ee4d2d; padding-bottom: 10px;">
            Chi Tiết Đơn Hàng
        </h2>

        <!-- Thông báo thành công -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" 
             style="background: #fff3e7; border: 1px solid #ee4d2d; border-radius: 5px; color: #ee4d2d; font-size: 14px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Thông tin khách hàng -->
        <div class="mb-4" style="background: #fafafa; padding: 20px; border-radius: 8px;">
            <h4 style="color: #333; font-size: 18px; font-weight: bold; margin-bottom: 15px;">Thông Tin Khách Hàng</h4>
            <p style="font-size: 14px; color: #555;"><strong>Tên:</strong> {{ $order->user->name }}</p>
            <p style="font-size: 14px; color: #555;"><strong>Email:</strong> {{ $order->user->email }}</p>
            <p style="font-size: 14px; color: #555;"><strong>Địa Chỉ:</strong> {{ $order->address }}</p>
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="mb-4">
            <h4 style="color: #333; font-size: 18px; font-weight: bold; margin-bottom: 15px;">Chi Tiết Sản Phẩm</h4>
            <table class="table table-hover align-middle" style="border: 1px solid #eee; border-radius: 8px;">
                <thead style="background: #ee4d2d; color: #fff; font-size: 14px;">
                    <tr>
                        <th style="padding: 12px;">Sản Phẩm</th>
                        <th style="padding: 12px;">Số Lượng</th>
                        <th style="padding: 12px;">Đơn Giá</th>
                        <th style="padding: 12px;">Thành Tiền</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px; color: #333;">
                    @foreach ($order->items as $item)
                    <tr>
                        <td style="padding: 12px;">{{ $item->product_name }}</td>
                        <td style="padding: 12px;">{{ $item->quantity }}</td>
                        <td style="padding: 12px;">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                        <td style="padding: 12px; color: #ee4d2d; font-weight: bold;">{{ number_format($item->total, 0, ',', '.') }} đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Trạng thái đơn hàng -->
        <div class="mb-4" style="background: #fafafa; padding: 20px; border-radius: 8px;">
            <h4 style="color: #333; font-size: 18px; font-weight: bold; margin-bottom: 15px;">Trạng Thái Đơn Hàng</h4>
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <select name="status" class="form-control" 
                            style="width: 300px; border: 1px solid #ee4d2d; border-radius: 5px; padding: 10px; font-size: 14px;">
                        @foreach ($statusTranslations as $key => $value)
                            <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn mt-3" 
                        style="background: #ee4d2d; color: #fff; font-weight: bold; padding: 10px 20px; border-radius: 5px; border: none;">
                    Cập Nhật và Gửi Email
                </button>
            </form>
        </div>
    </div>
</div>
@endsection