@extends('layouts.user')

@section('title', 'Cảm ơn bạn')

@section('content')
<div class="container mt-5" style="max-width: 800px; text-align: center;">
    <h2 style="color: #ee4d2d; font-weight: bold;">Cảm ơn bạn đã đặt hàng!</h2>
    <p style="font-size: 16px; color: #757575; margin-top: 20px;">
        Đơn hàng của bạn đã được đặt thành công. Chúng tôi sẽ xử lý đơn hàng và giao hàng sớm nhất có thể.
    </p>
    <a href="{{ route('home') }}" class="btn" style="background-color: #ee4d2d; color: white; padding: 10px 30px; border-radius: 4px; font-weight: bold; margin-top: 20px;">Tiếp tục mua sắm</a>
</div>
@endsection
