@extends('layouts.admin')

@section('title', 'Edit Coupon')

@section('content')
<div class="form-wrapper">
    <h2 class="text-xl font-semibold text-center text-gray-800 mb-5">Chỉnh sửa mã giảm giá</h2>
    <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="code" class="form-label text-sm font-semibold text-gray-700">
                Mã giảm giá <span class="text-orange-shopee">*</span>
            </label>
            <input type="text" name="code" id="code" class="form-control" value="{{ $coupon->code }}" required>
        </div>

        <div class="mb-4">
            <label for="discount_type" class="form-label text-sm font-semibold text-gray-700">
                Loại giảm giá <span class="text-orange-shopee">*</span>
            </label>
            <select name="discount_type" id="discount_type" class="form-control" required>
                <option value="percentage" {{ $coupon->discount_type === 'percentage' ? 'selected' : '' }}>Theo phần trăm</option>
                <option value="fixed" {{ $coupon->discount_type === 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="discount_value" class="form-label text-sm font-semibold text-gray-700">
                Giá trị giảm <span class="text-orange-shopee">*</span>
            </label>
            <input type="number" name="discount_value" id="discount_value" class="form-control" value="{{ $coupon->discount_value }}" required>
        </div>

        <div class="mb-4">
            <label for="usage_limit" class="form-label text-sm font-semibold text-gray-700">
                Số lần sử dụng tối đa <span class="text-orange-shopee">*</span>
            </label>
            <input type="number" name="usage_limit" id="usage_limit" class="form-control" value="{{ $coupon->usage_limit }}" required>
        </div>

        <div class="mb-4">
            <label for="expires_at" class="form-label text-sm font-semibold text-gray-700">
                Hạn sử dụng
            </label>
            <input type="date" name="expires_at" id="expires_at" class="form-control" value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '' }}">
        </div>

        <div class="flex gap-3 justify-end">
            <button type="submit" class="btn bg-orange-shopee text-white font-semibold hover:bg-orange-shopee-hover shadow-sm">
                <i class="fa-solid fa-edit mr-2"></i> Cập nhật
            </button>
            <a href="{{ route('coupons.index') }}" class="btn bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 shadow-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection

<style>
    :root {
        --orange-shopee: #EE4D2D;
        --orange-shopee-hover: #F26A2E;
    }

    .text-orange-shopee {
        color: var(--orange-shopee);
    }

    .bg-orange-shopee {
        background-color: var(--orange-shopee) !important;
    }

    .hover\:bg-orange-shopee-hover:hover {
        background-color: var(--orange-shopee-hover) !important;
    }

    .form-wrapper {
        margin: 2rem auto;
        background: #ffffff;
        padding: 2rem;
        border-radius: 0.75rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .form-control {
        background-color: #f9fafb;
        border: none;
        border-radius: 0.5rem;
        padding: 0.625rem 1rem;
        width: 100%;
        transition: box-shadow 0.2s ease;
        font-size: 0.95rem;
    }

    .form-control:focus {
        outline: none;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(238, 77, 45, 0.2);
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
    }

    .btn {
        padding: 0.5rem 1.25rem;
        border-radius: 0.5rem;
        font-size: 0.95rem;
        border: none;
        transition: all 0.2s;
    }

    @media (max-width: 500px) {
        .form-wrapper {
            padding: 1.5rem;
        }

        .btn {
            width: 100%;
        }

        .flex.gap-3 {
            flex-direction: column;
            gap: 0.75rem;
        }

        .flex.justify-end {
            justify-content: center;
        }
    }
</style>
