@extends('layouts.admin')

@section('title', 'Chỉnh sửa Thương Hiệu')

@section('content')
<div class="form-wrapper">
    <h2 class="text-xl font-semibold text-center text-gray-800 mb-5">Chỉnh sửa thương hiệu</h2>
    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="form-label text-sm font-semibold text-gray-700">
                Tên thương hiệu <span class="text-orange-shopee">*</span>
            </label>
            <input
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                name="name"
                value="{{ old('name', $brand->name) }}"
                required
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 flex gap-3 justify-end">
            <button type="submit" class="btn bg-orange-shopee text-white font-semibold hover:bg-orange-shopee-hover shadow-sm">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Cập nhật
            </button>
            <a href="{{ route('admin.brands.index') }}" class="btn bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 shadow-sm">
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
        transition: box-shadow 0.2s ease, background-color 0.2s ease;
        font-size: 0.95rem;
    }

    .form-control:focus {
        outline: none;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(238, 77, 45, 0.2);
    }

    .form-control.is-invalid {
        background-color: #fff5f5;
        box-shadow: 0 0 0 2px #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-label {
        margin-bottom: 0.5rem;
        display: block;
    }

    .btn {
        padding: 0.5rem 1.25rem;
        border-radius: 0.5rem;
        transition: all 0.2s;
        font-size: 0.95rem;
        border: none;
    }

    @media (max-width: 500px) {
        .form-wrapper {
            padding: 1.5rem;
            max-width: 90%;
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
