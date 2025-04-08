@extends('layouts.user')

@section('title', 'Lịch Sử Thanh Toán')

@section('content')
<div class="container mt-5">
    <h2 style="color: #ee4d2d; font-weight: bold;">Lịch Sử Thanh Toán</h2>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Mã Đơn Hàng</th>
                <th>Phương Thức</th>
                <th>Tổng Tiền</th>
                <th>Trạng Thái</th>
                <th>Ngày Tạo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ ucfirst($order->payment_method) }}</td>
                    <td>{{ number_format($order->total, 0, ',', '.') }} đ</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
