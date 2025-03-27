@extends('layouts.app')

<div class="container flex justify-center items-center" style="min-height: 80vh;">
    <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-lg transform transition-all ">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Đăng Nhập MagicShop</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 text-center border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-6">
                <label for="email" class="block text-lg text-gray-700 mb-2">Email</label>
                <input type="email" class="w-full p-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-transparent text-gray-800" id="email" name="email" required placeholder="Nhập email của bạn">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-lg text-gray-700 mb-2">Mật khẩu</label>
                <input type="password" class="w-full p-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-transparent text-gray-800" id="password" name="password" required placeholder="Nhập mật khẩu">
            </div>

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6 border border-red-300">
                    <ul class="list-none mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="w-full bg-yellow-600 text-white p-3 rounded-lg hover:bg-yellow-700 transition duration-300 text-lg font-semibold shadow-md">Đăng nhập</button>
        </form>

        <div class="text-center mt-6">
            <p class="text-lg text-gray-600">Chưa có tài khoản? <a href="{{ route('register') }}" class="text-yellow-600 hover:text-yellow-700 font-medium">Đăng ký ngay</a></p>
        </div>
    </div>
</div>

<style>
    .shadow-2xl {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>