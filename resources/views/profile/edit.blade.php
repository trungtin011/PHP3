@extends('layouts.user')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="container mx-auto mt-12 px-4 lg:px-8 max-w-6xl" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Tabs -->
        <div class="flex border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
            <button id="profile-tab" class="flex-1 py-4 px-6 text-center text-base font-semibold text-gray-800 hover:text-indigo-600 focus:text-indigo-600 focus:bg-white transition-all tab active border-b-4 border-transparent focus:border-indigo-600">
                <i class="fa-solid fa-user mr-2"></i> Hồ sơ
            </button>
            <button id="orders-tab" class="flex-1 py-4 px-6 text-center text-base font-semibold text-gray-800 hover:text-indigo-600 focus:text-indigo-600 focus:bg-white transition-all tab border-b-4 border-transparent focus:border-indigo-600">
                <i class="fa-solid fa-box mr-2"></i> Đơn hàng
            </button>
        </div>

        <!-- Thông báo -->
        <div class="px-6 pt-6">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-check-circle text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-exclamation-circle text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Tab: Chỉnh sửa hồ sơ -->
        <div id="profile-content" class="p-6 lg:p-8 tab-content">
            <div class="max-w-3xl mx-auto">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Avatar -->
                    <div class="flex items-center gap-6">
                        <div>
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="Avatar" class="w-20 h-20 rounded-full border-2 border-gray-200 shadow-sm object-cover">
                            @else
                                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 border-2 border-gray-200">
                                    <i class="fa-solid fa-user fa-2x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện</label>
                            <input type="file" id="avatar" name="avatar" accept="image/*" 
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-gray-50 shadow-sm" 
                                   style="border-color: #e5e7eb;">
                            @error('avatar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tên và Số điện thoại -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required 
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-gray-50 shadow-sm" 
                                   style="border-color: #e5e7eb;" placeholder="Nhập tên của bạn">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-gray-50 shadow-sm" 
                                   style="border-color: #e5e7eb;" placeholder="Nhập số điện thoại">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-gray-50 shadow-sm" 
                               style="border-color: #e5e7eb;" placeholder="Nhập email của bạn">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mật khẩu -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                            <input type="password" id="password" name="password" 
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-gray-50 shadow-sm" 
                                   style="border-color: #e5e7eb;" placeholder="Để trống nếu không đổi">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-7
00 mb-1">Xác nhận mật khẩu</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-gray-50 shadow-sm" 
                                   style="border-color: #e5e7eb;" placeholder="Nhập lại mật khẩu">
                        </div>
                    </div>

                    <!-- Địa chỉ -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                        <div id="addresses-container" class="space-y-4">
                            @foreach($user->addresses as $address)
                                <div class="flex items-center gap-4">
                                    <input type="text" name="addresses[]" value="{{ $address->address }}" 
                                           class="flex-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-gray-50 shadow-sm" 
                                           style="border-color: #e5e7eb;" placeholder="Nhập địa chỉ">
                                    <input type="radio" name="default_address" value="{{ $address->id }}" {{ $address->default ? 'checked' : '' }} 
                                           class="h-5 w-5 text-indigo-600 focus:ring-indigo-500">
                                    <button type="button" class="remove-address text-gray-400 hover:text-red-500 transition-colors">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                            <div class="flex items-center gap-4">
                                <input type="text" name="addresses[]" placeholder="Thêm địa chỉ mới" 
                                       class="flex-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-gray-50 shadow-sm" 
                                       style="border-color: #e5e7eb;">
                            </div>
                        </div>
                        <button type="button" id="add-address" class="mt-4 text-indigo-600 hover:text-indigo-700 flex items-center gap-2 text-sm font-medium transition-colors">
                            <i class="fa-solid fa-plus"></i> Thêm địa chỉ mới
                        </button>
                    </div>

                    <!-- Nút cập nhật -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-blue-700 transition-all flex items-center gap-2 shadow-lg">
                            <i class="fa-solid fa-save"></i> Cập nhật hồ sơ
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab: Lịch sử đơn hàng -->
        <div id="orders-content" class="p-6 lg:p-8 tab-content hidden">
            @if($orders->isEmpty())
                <div class="text-center py-12">
                    <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Bạn chưa có đơn hàng nào.</p>
                </div>
            @else
                <div class="space-y-6 max-w-4xl mx-auto">
                    @foreach($orders as $order)
                        <div class="bg-gray-50 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Đơn hàng #{{ $order->id }}</h3>
                                    <p class="text-sm text-gray-600">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    <p class="text-sm font-medium text-indigo-600">Tổng tiền: {{ number_format($order->total, 0, ',', '.') }} đ</p>
                                    <p class="text-sm text-gray-600">
                                        Trạng thái: 
                                        @php
                                            $statusTranslations = [
                                                'pending' => 'Chờ xử lý',
                                                'processing' => 'Đang xử lý',
                                                'completed' => 'Hoàn thành',
                                                'canceled' => 'Đã hủy',
                                            ];
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'canceled' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="inline-block px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusTranslations[$order->status] ?? $order->status }}
                                        </span>
                                    </p>
                                </div>
                                <div class="d-flex gap-3 align-items-center">
                                    <button class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-2 transition-colors" 
                                            onclick="toggleOrderDetails({{ $order->id }})">
                                        <i class="fa-solid fa-eye"></i> Chi tiết
                                    </button>
                                    <form action="{{ route('profile.reorder', $order->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center gap-2 transition-colors">
                                            <i class="fa-solid fa-cart-plus"></i> Mua lại
                                        </button>
                                    </form>
                                    @if(in_array($order->status, ['pending', 'processing']))
                                        <form action="{{ route('profile.cancel', $order->id) }}" method="POST" 
                                              onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');" class="m-0">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm flex items-center gap-2 transition-colors">
                                                <i class="fa-solid fa-times-circle"></i> Hủy
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div id="order-details-{{ $order->id }}" class="hidden mt-4">
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Chi tiết đơn hàng</h4>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm text-gray-600">
                                            <thead>
                                                <tr class="border-b border-gray-200">
                                                    <th class="py-2 px-4 text-left">Sản phẩm</th>
                                                    <th class="py-2 px-4 text-center">Số lượng</th>
                                                    <th class="py-2 px-4 text-right">Đơn giá</th>
                                                    <th class="py-2 px-4 text-right">Tổng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->items as $item)
                                                    <tr class="border-b border-gray-100 last:border-0">
                                                        <td class="py-3 px-4 flex items-center gap-3">
                                                            @if($item->product_image)
                                                                <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" class="w-10 h-10 rounded object-cover">
                                                            @else
                                                                <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                                                                    <i class="fa-solid fa-image text-gray-400"></i>
                                                                </div>
                                                            @endif
                                                            <span>{{ $item->product_name }}</span>
                                                        </td>
                                                        <td class="py-3 px-4 text-center">{{ $item->quantity }}</td>
                                                        <td class="py-3 px-4 text-right">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                                        <td class="py-3 px-4 text-right font-semibold text-indigo-600">{{ number_format($item->total, 0, ',', '.') }} đ</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Thêm địa chỉ mới
    document.getElementById('add-address').addEventListener('click', function () {
        const container = document.getElementById('addresses-container');
        const newAddress = document.createElement('div');
        newAddress.classList.add('flex', 'items-center', 'gap-4');
        newAddress.innerHTML = `
            <input type="text" name="addresses[]" placeholder="Thêm địa chỉ mới" 
                   class="flex-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-gray-50 shadow-sm" 
                   style="border-color: #e5e7eb;">
            <button type="button" class="remove-address text-gray-400 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        container.appendChild(newAddress);
    });

    // Xóa địa chỉ
    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-address')) {
            e.target.closest('.flex').remove();
        }
    });

    // Toggle chi tiết đơn hàng
    function toggleOrderDetails(orderId) {
        const details = document.getElementById(`order-details-${orderId}`);
        details.classList.toggle('hidden');
    }

    // Xử lý tabs
    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
                t.classList.remove('active', 'text-indigo-600', 'bg-white', 'border-indigo-600');
                t.classList.add('border-transparent');
            });
            contents.forEach(c => c.classList.add('hidden'));

            tab.classList.add('active', 'text-indigo-600', 'bg-white', 'border-indigo-600');
            tab.classList.remove('border-transparent');
            document.getElementById(`${tab.id.replace('-tab', '')}-content`).classList.remove('hidden');
        });
    });
</script>
@endsection

<!-- Font Awesome và Tailwind CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>