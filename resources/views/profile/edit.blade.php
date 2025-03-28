@extends('layouts.user')

@section('title', 'Chỉnh sửa hồ sơ')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-2xl">
    <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Chỉnh sửa hồ sơ</h2>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" class="bg-white p-6 rounded-xl shadow-lg">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên</label>
            <input type="text" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $user->name) }}" 
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                   id="email" 
                   name="email" 
                   value="{{ old('email', $user->email) }}" 
                   required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
            <input type="password" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                   id="password" 
                   name="password" 
                   placeholder="Để trống nếu không muốn thay đổi">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu</label>
            <input type="password" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                   id="password_confirmation" 
                   name="password_confirmation" 
                   placeholder="Để trống nếu không muốn thay đổi">
        </div>

        <div class="mb-5">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
            <input type="text" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                   id="phone" 
                   name="phone" 
                   value="{{ old('phone', $user->phone) }}">
            @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-y" 
                      id="address" 
                      name="address" 
                      rows="4">{{ old('address', $user->address) }}</textarea>
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="text-center">
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg">
                <i class="fa-solid fa-save"></i> Cập nhật hồ sơ
            </button>
        </div>
    </form>
</div>
@endsection

<!-- Font Awesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>