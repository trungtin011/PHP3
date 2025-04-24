@extends('layouts.user')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="container mx-auto mt-12 px-4 lg:px-8 max-w-6xl" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Tabs -->
        <div class="flex border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100" role="tablist">
            <button id="profile-tab" class="flex-1 py-4 px-6 text-center text-base font-semibold text-gray-800 hover:text-orange-600 focus:text-orange-600 focus:bg-white transition-all tab active border-b-4 border-transparent focus:border-orange-600" role="tab" aria-controls="profile-content" aria-selected="true">
                <i class="fa-solid fa-user mr-2"></i> Hồ sơ
            </button>
            <button id="orders-tab" class="flex-1 py-4 px-6 text-center text-base font-semibold text-gray-800 hover:text-orange-600 focus:text-orange-600 focus:bg-white transition-all tab border-b-4 border-transparent focus:border-orange-600" role="tab" aria-controls="orders-content" aria-selected="false">
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
        <div id="profile-content" class="p-6 lg:p-8 tab-content" role="tabpanel">
            <div class="max-w-3xl mx-auto">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="profile-form">
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
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all bg-gray-50 shadow-sm">
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
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 fokus:ring-orange-500 focus:border-orange-500 transition-all bg-gray-50 shadow-sm" 
                                   placeholder="Nhập tên của bạn">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all bg-gray-50 shadow-sm" 
                                   placeholder="Nhập số điện thoại">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all bg-gray-50 shadow-sm" 
                               placeholder="Nhập email của bạn">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mật khẩu -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                            <input type="password" id="password" name="password" 
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all bg-gray-50 shadow-sm" 
                                   placeholder="Để trống nếu không đổi">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all bg-gray-50 shadow-sm" 
                                   placeholder="Nhập lại mật khẩu">
                        </div>
                    </div>

                    <!-- Địa chỉ -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                        <div id="addresses-container" class="space-y-4">
                            @forelse($user->addresses as $index => $address)
                                <div class="flex items-center gap-4 address-row">
                                    <input type="text" name="addresses[]" value="{{ $address->address }}" 
                                           class="flex-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all bg-gray-50 shadow-sm" 
                                           placeholder="Nhập địa chỉ" required>
                                    <input type="radio" name="default_address" value="{{ $index + 1 }}" 
                                           {{ $address->default ? 'checked' : '' }} 
                                           class="h-5 w-5 text-orange-600 focus:ring-orange-500" required>
                                    <button type="button" class="remove-address text-gray-400 hover:text-red-500 transition-colors">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="flex items-center gap-4 address-row">
                                    <input type="text" name="addresses[]" placeholder="Thêm địa chỉ mới" 
                                           class="flex-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all bg-gray-50 shadow-sm" 
                                           required>
                                    <input type="radio" name="default_address" value="1" checked 
                                           class="h-5 w-5 text-orange-600 focus:ring-orange-500" required>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" id="add-address" class="mt-4 text-orange-600 hover:text-orange-700 flex items-center gap-2 text-sm font-medium transition-colors">
                            <i class="fa-solid fa-plus"></i> Thêm địa chỉ mới
                        </button>
                        @error('addresses.*')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                        @error('default_address')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nút cập nhật -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all flex items-center gap-2 shadow-lg">
                            <i class="fa-solid fa-save"></i> Cập nhật hồ sơ
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab: Lịch sử đơn hàng -->
        <div id="orders-content" class="p-6 lg:p-8 tab-content hidden" role="tabpanel">
            <div class="max-w-4xl mx-auto">
                <!-- Filter -->
                <div class="mb-6 flex flex-col sm:flex-row gap-4">
                    <select id="status-filter" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 bg-white shadow-sm" aria-label="Lọc đơn hàng theo trạng thái">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>

                <!-- Orders List -->
                <div id="orders-list" class="space-y-6">
                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">Bạn chưa có đơn hàng nào.</p>
                        </div>
                    @else
                        @foreach($orders as $order)
                            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-shadow border border-gray-100">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">Đơn hàng #{{ $order->id }}</h3>
                                        <p class="text-sm text-gray-600">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                        <p class="text-sm font-medium text-orange-600">Tổng tiền: {{ number_format($order->total, 0, ',', '.') }} đ</p>
                                        <p class="text-sm text-gray-600">
                                            Trạng thái: 
                                            @php
                                                $statusTranslations = [
                                                    'pending' => ['label' => 'Chờ xử lý', 'color' => 'bg-yellow-100 text-yellow-800'],
                                                    'processing' => ['label' => 'Đang xử lý', 'color' => 'bg-blue-100 text-blue-800'],
                                                    'completed' => ['label' => 'Hoàn thành', 'color' => 'bg-green-100 text-green-800'],
                                                    'canceled' => ['label' => 'Đã hủy', 'color' => 'bg-red-100 text-red-800'],
                                                ];
                                            @endphp
                                            <span class="inline-block px-2 py-1 rounded-full text-xs font-medium {{ $statusTranslations[$order->status]['color'] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $statusTranslations[$order->status]['label'] ?? $order->status }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex gap-3 flex-wrap">
                                        <button class="text-orange-600 hover:text-orange-800 font-medium text-sm flex items-center gap-2 transition-colors" 
                                                onclick="toggleOrderDetails({{ $order->id }})" aria-expanded="false" aria-controls="order-details-{{ $order->id }}">
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
                                <div id="order-details-{{ $order->id }}" class="hidden mt-4" aria-hidden="true">
                                    <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
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
                                                                    <img src="{{ Storage::url($item->product_image) }}" alt="{{ $item->product_name }}" class="w-10 h-10 rounded object-cover">
                                                                @else
                                                                    <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                                                                        <i class="fa-solid fa-image text-gray-400"></i>
                                                                    </div>
                                                                @endif
                                                                <span>{{ $item->product_name }}</span>
                                                            </td>
                                                            <td class="py-3 px-4 text-center">{{ $item->quantity }}</td>
                                                            <td class="py-3 px-4 text-right">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                                            <td class="py-3 px-4 text-right font-semibold text-orange-600">{{ number_format($item->total, 0, ',', '.') }} đ</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $orders->appends(request()->only('status'))->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Thêm địa chỉ mới
    document.getElementById('add-address').addEventListener('click', function () {
        const container = document.getElementById('addresses-container');
        const addressCount = container.querySelectorAll('input[name="addresses[]"]').length + 1;
        const newAddress = document.createElement('div');
        newAddress.classList.add('flex', 'items-center', 'gap-4', 'address-row');
        newAddress.innerHTML = `
            <input type="text" name="addresses[]" placeholder="Thêm địa chỉ mới" 
                   class="flex-1 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all bg-gray-50 shadow-sm" 
                   required>
            <input type="radio" name="default_address" value="${addressCount}" 
                   class="h-5 w-5 text-orange-600 focus:ring-orange-500" required>
            <button type="button" class="remove-address text-gray-400 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        container.appendChild(newAddress);
    });

    // Xóa địa chỉ
    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-address')) {
            const container = document.getElementById('addresses-container');
            if (container.querySelectorAll('.address-row').length > 1) {
                e.target.closest('.flex').remove();
            } else {
                alert('Phải có ít nhất một địa chỉ.');
            }
        }
    });

    // Client-side validation
    document.getElementById('profile-form').addEventListener('submit', function (e) {
        const addresses = document.querySelectorAll('input[name="addresses[]"]');
        const validAddresses = Array.from(addresses).filter(input => input.value.trim() !== '');
        if (validAddresses.length === 0) {
            e.preventDefault();
            alert('Vui lòng cung cấp ít nhất một địa chỉ hợp lệ.');
        }
    });

    // Toggle chi tiết đơn hàng
    function toggleOrderDetails(orderId) {
        const details = document.getElementById(`order-details-${orderId}`);
        const button = document.querySelector(`button[aria-controls="order-details-${orderId}"]`);
        const isHidden = details.classList.toggle('hidden');
        details.setAttribute('aria-hidden', isHidden);
        button.setAttribute('aria-expanded', !isHidden);
    }

    // Xử lý tabs
    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
                t.classList.remove('active', 'text-orange-600', 'bg-white', 'border-orange-600');
                t.classList.add('border-transparent');
                t.setAttribute('aria-selected', 'false');
            });
            contents.forEach(c => c.classList.add('hidden'));

            tab.classList.add('active', 'text-orange-600', 'bg-white', 'border-orange-600');
            tab.classList.remove('border-transparent');
            tab.setAttribute('aria-selected', 'true');
            document.getElementById(`${tab.id.replace('-tab', '')}-content`).classList.remove('hidden');
        });
    });

    // Status filter
    document.getElementById('status-filter').addEventListener('change', function () {
        const status = this.value;
        window.location.href = `?status=${status}`;
    });
</script>

<!-- Font Awesome và Tailwind CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
@endsection