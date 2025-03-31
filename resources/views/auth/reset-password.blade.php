@extends('layouts.app')

<div class="container flex justify-center items-center" style="min-height: 80vh;">
    <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-lg transform transition-all">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Đặt Lại Mật Khẩu</h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') ?? '' }}">

            <div class="mb-6">
                <label for="email" class="block text-lg text-gray-700 mb-2">Email</label>
                <input type="email" class="w-full p-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-transparent text-gray-800" id="email" name="email" required value="{{ old('email', $request->email ?? '') }}" placeholder="Nhập email của bạn">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-lg text-gray-700 mb-2">Mật khẩu mới</label>
                <input type="password" class="w-full p-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-transparent text-gray-800" id="password" name="password" required placeholder="Nhập mật khẩu mới">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-lg text-gray-700 mb-2">Xác nhận mật khẩu</label>
                <input type="password" class="w-full p-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-transparent text-gray-800" id="password_confirmation" name="password_confirmation" required placeholder="Xác nhận lại mật khẩu">
            </div>

            <button type="submit" class="w-full bg-yellow-600 text-white p-3 rounded-lg hover:bg-yellow-700 transition duration-300 text-lg font-semibold shadow-md">Đặt lại mật khẩu</button>
        </form>
    </div>
</div>
