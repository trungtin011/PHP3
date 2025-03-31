@extends('layouts.app')

<div class="container flex justify-center items-center" style="min-height: 80vh;">
    <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-lg transform transition-all">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Quên Mật Khẩu</h2>

        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 text-center border border-green-300">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-6">
                <label for="email" class="block text-lg text-gray-700 mb-2">Email</label>
                <input type="email" class="w-full p-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:border-transparent text-gray-800" id="email" name="email" required placeholder="Nhập email của bạn">
            </div>

            <button type="submit" class="w-full bg-yellow-600 text-white p-3 rounded-lg hover:bg-yellow-700 transition duration-300 text-lg font-semibold shadow-md">Gửi liên kết đặt lại mật khẩu</button>
        </form>
    </div>
</div>
