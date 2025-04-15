@extends('layouts.user')

@section('title', 'Lịch sử đơn hàng')

@section('content')
<div class="container mx-auto mt-10 px-4 lg:px-8 max-w-7xl" style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div class="bg-white rounded-2xl shadow-lg p-6 lg:p-8 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">
            Lịch sử đơn hàng
        </h2>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 flex items-center gap-2">
                <i class="fa-solid fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 flex items-center gap-2">
                <i class="fa-solid fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($orders->isEmpty())
            <p class="text-gray-500 text-sm">Bạn chưa có đơn hàng nào.</p>
        @else
            <div class="space-y-6 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
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
                                $statusClasses = [
                                    'pending' => 'text-yellow-600',
                                    'processing' => 'text-blue-600',
                                    'completed' => 'text-green-600',
                                    'canceled' => 'text-red-600',
                                ];
                            @endphp
                            <span class="{{ $statusClasses[$order->status] ?? 'text-gray-600' }}">
                                {{ $statusTranslations[$order->status] ?? $order->status }}
                            </span>
                        </p>
                        <div class="mt-4 flex gap-2">
                            <button class="action-btn btn-view" 
                                    onclick="toggleOrderDetails({{ $order->id }})">
                                <i class="fa-solid fa-eye"></i> Xem chi tiết
                            </button>
                            <form action="{{ route('profile.reorder', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="action-btn btn-reorder">
                                    <i class="fa-solid fa-cart-plus"></i> Mua lại
                                </button>
                            </form>
                            @if(in_array($order->status, ['pending', 'processing']))
                                <form action="{{ route('profile.cancel', $order->id) }}" method="POST" 
                                      onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                                    @csrf
                                    <button type="submit" class="action-btn btn-cancel">
                                        <i class="fa-solid fa-times-circle"></i> Hủy đơn
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div id="order-details-{{ $order->id }}" class="hidden mt-3 text-sm text-gray-600 transition-all duration-300">
                            <p class="font-semibold text-gray-800">Chi tiết đơn hàng:</p>
                            <ul class="list-disc pl-4 mt-1">
                                @foreach($order->items as $item)
                                    <li class="py-1">
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

<style>
    .action-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.375rem;
        border: 1px solid transparent;
        background: transparent;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .action-btn i {
        font-size: 1rem;
    }

    .btn-view {
        color: #2563eb;
        border-color: #bfdbfe;
        background: #eff6ff;
    }

    .btn-view:hover {
        background: #dbeafe;
        border-color: #93c5fd;
        color: #1e40af;
    }

    .btn-reorder {
        color: #15803d;
        border-color: #bef264;
        background: #f0fdf4;
    }

    .btn-reorder:hover {
        background: #dcfce7;
        border-color: #84cc16;
        color: #14532d;
    }

    .btn-cancel {
        color: #b91c1c;
        border-color: #fecaca;
        background: #fef2f2;
    }

    .btn-cancel:hover {
        background: #fee2e2;
        border-color: #f87171;
        color: #7f1d1d;
    }

    .action-btn:active {
        transform: scale(0.95);
    }

    form {
        display: contents;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>

<script>
    function toggleOrderDetails(orderId) {
        const details = document.getElementById(`order-details-${orderId}`);
        details.classList.toggle('hidden');
    }
</script>
@endsection

<!-- Font Awesome và Tailwind CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>