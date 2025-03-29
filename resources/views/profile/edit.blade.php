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

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-lg">
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
            <label for="addresses" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
            <div id="addresses-container">
                @foreach($user->addresses as $index => $address)
                    <div class="flex items-center gap-2 mb-2">
                        <input type="text" 
                               name="addresses[]" 
                               value="{{ $address->address }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <button type="button" class="remove-address text-red-500 hover:text-red-700">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                @endforeach
                <div class="flex items-center gap-2 mb-2">
                    <input type="text" 
                           name="addresses[]" 
                           placeholder="Thêm địa chỉ mới" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>
            </div>
            <button type="button" id="add-address" class="mt-2 text-indigo-500 hover:text-indigo-700">
                <i class="fa-solid fa-plus"></i> Thêm địa chỉ
            </button>
        </div>

        <div class="mb-5">
            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện</label>
            <input type="file" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                   id="avatar" 
                   name="avatar" 
                   accept="image/*">
            @error('avatar')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            @if($user->avatar)
                <img src="{{ $user->avatar }}" alt="Avatar" class="mt-4 w-20 h-20 rounded-full">
            @endif
        </div>

        <div class="text-center">
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg">
                <i class="fa-solid fa-save"></i> Cập nhật hồ sơ
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('add-address').addEventListener('click', function () {
        const container = document.getElementById('addresses-container');
        const newAddress = document.createElement('div');
        newAddress.classList.add('flex', 'items-center', 'gap-2', 'mb-2');
        newAddress.innerHTML = `
            <input type="text" name="addresses[]" placeholder="Thêm địa chỉ mới" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
            <button type="button" class="remove-address text-red-500 hover:text-red-700">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        container.appendChild(newAddress);
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-address')) {
            e.target.closest('.flex').remove();
        }
    });
</script>
@endsection

<!-- Font Awesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>