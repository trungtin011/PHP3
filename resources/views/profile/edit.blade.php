@extends('layouts.user')

@section('title', 'Chỉnh sửa hồ sơ')

@section('content')
<div class="container mx-auto mt-10 px-4 lg:px-8 max-w-7xl" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Phần 1: Chỉnh sửa hồ sơ -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg p-6 lg:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">
                    Chỉnh sửa hồ sơ
                </h2>

                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Tên -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required 
                               class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                               style="border-color: #d1d5db;">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                               style="border-color: #d1d5db;">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mật khẩu -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                            <input type="password" id="password" name="password" placeholder="Để trống nếu không đổi" 
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                   style="border-color: #d1d5db;">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Để trống nếu không đổi" 
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                   style="border-color: #d1d5db;">
                        </div>
                    </div>

                    <!-- Số điện thoại -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                               class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                               style="border-color: #d1d5db;">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Địa chỉ -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                        <div id="addresses-container" class="space-y-3">
                            @foreach($user->addresses as $index => $address)
                                <div class="flex items-center gap-3">
                                    <input type="text" name="addresses[]" value="{{ $address->address }}" 
                                           class="flex-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                           style="border-color: #d1d5db;">
                                    <input type="radio" name="default_address" value="{{ $address->id }}" {{ $address->default ? 'checked' : '' }} 
                                           class="h-4 w-4 text-blue-600">
                                    <button type="button" class="remove-address text-gray-400 hover:text-red-500">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                            <div class="flex items-center gap-3">
                                <input type="text" name="addresses[]" placeholder="Thêm địa chỉ mới" 
                                       class="flex-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                       style="border-color: #d1d5db;">
                            </div>
                        </div>
                        <button type="button" id="add-address" class="mt-3 text-blue-600 hover:text-blue-700 flex items-center gap-1 text-sm">
                            <i class="fa-solid fa-plus"></i> Thêm địa chỉ
                        </button>
                    </div>

                    <!-- Ảnh đại diện -->
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" 
                               class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                               style="border-color: #d1d5db;">
                        @error('avatar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="Avatar" class="mt-3 w-16 h-16 rounded-full border shadow-sm" style="border-color: #e5e7eb;">
                        @endif
                    </div>

                    <!-- Nút cập nhật -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-save"></i> Cập nhật hồ sơ
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Phần 2: Lịch sử đơn hàng -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 lg:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">
                    Lịch sử đơn hàng
                </h2>

                @if($orders->isEmpty())
                    <p class="text-gray-500 text-sm">Bạn chưa có đơn hàng nào.</p>
                @else
                    <div class="space-y-6 max-h-[600px] overflow-y-auto pr-2">
                        @foreach($orders as $order)
                            <div class="pb-4 border-b border-gray-200 last:border-b-0">
                                <p class="text-sm font-semibold text-gray-800">
                                    Mã đơn hàng: #{{ $order->id }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}
                                </p>
                                <p class="text-sm font-semibold text-blue-600">
                                    Tổng tiền: {{ number_format($order->total, 0, ',', '.') }} đ
                                </p>
                                <p class="text-sm text-gray-600">
                                    Trạng thái: 
                                    @php
                                        $statusTranslations = [
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'completed' => 'Hoàn thành',
                                            'canceled' => 'Đã hủy',
                                        ];
                                    @endphp
                                    {{ $statusTranslations[$order->status] ?? $order->status }}
                                </p>
                                <button class="mt-2 text-blue-600 hover:text-blue-700 text-sm flex items-center gap-1" 
                                        onclick="toggleOrderDetails({{ $order->id }})">
                                    <i class="fa-solid fa-eye"></i> Xem chi tiết
                                </button>
                                <div id="order-details-{{ $order->id }}" class="hidden mt-2 text-sm text-gray-600">
                                    <p class="font-semibold text-gray-800">Chi tiết đơn hàng:</p>
                                    <ul class="list-disc pl-4 mt-1">
                                        @foreach($order->items as $item)
                                            <li>
                                                {{ $item->product_name }} - {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }} đ
                                                = <span class="font-semibold text-blue-600">{{ number_format($item->total, 0, ',', '.') }} đ</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-address').addEventListener('click', function () {
        const container = document.getElementById('addresses-container');
        const newAddress = document.createElement('div');
        newAddress.classList.add('flex', 'items-center', 'gap-3');
        newAddress.innerHTML = `
            <input type="text" name="addresses[]" placeholder="Thêm địa chỉ mới" 
                   class="flex-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                   style="border-color: #d1d5db;">
            <button type="button" class="remove-address text-gray-400 hover:text-red-500">
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

    function toggleOrderDetails(orderId) {
        const details = document.getElementById(`order-details-${orderId}`);
        details.classList.toggle('hidden');
    }
</script>
@endsection

<!-- Font Awesome và Tailwind CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>