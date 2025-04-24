@extends('layouts.admin')

@section('title', 'Thêm Danh Mục')

@section('content')
<div class="form-wrapper">
    <h2 class="text-xl font-semibold text-center text-gray-800 mb-5">Thêm danh mục</h2>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="form-label text-sm font-semibold text-gray-700">
                Tên danh mục <span class="text-orange-shopee">*</span>
            </label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-4">
            <label for="slug" class="form-label text-sm font-semibold text-gray-700">
                Đường dẫn (slug) <span class="text-orange-shopee">*</span>
            </label>
            <input type="text" class="form-control" id="slug" name="slug" required>
        </div>

        <div class="mb-4">
            <label for="icon" class="form-label text-sm font-semibold text-gray-700">
                Chọn biểu tượng
            </label>
            <input type="hidden" id="icon" name="icon">
            <div class="icon-scroll-container">
                @php
                    $icons = ['mobile-alt', 'laptop', 'headphones-alt', 'camera', 'blender', 'plug', 'desktop', 'tv', 'sync-alt', 'box-open', 'tags', 'newspaper'];
                @endphp
                @foreach ($icons as $icon)
                    <div class="icon-box">
                        <i class="fas fa-{{ $icon }} text-blue-500 text-2xl icon-option" data-icon="fas fa-{{ $icon }}"></i>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-4">
            <label for="parent_id" class="form-label text-sm font-semibold text-gray-700">
                Danh mục cha (nếu có)
            </label>
            <select class="form-control" id="parent_id" name="parent_id">
                <option value="">-- Không có --</option>
                @foreach ($categories as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3 justify-end">
            <button type="submit" class="btn bg-orange-shopee text-white font-semibold hover:bg-orange-shopee-hover shadow-sm">
                <i class="fa-solid fa-plus mr-2"></i> Thêm danh mục
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 shadow-sm">
                <i class="fa-solid fa-times mr-2"></i> Hủy
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

    .icon-option {
        cursor: pointer;
        transition: transform 0.2s ease, color 0.2s ease;
    }

    .icon-option:hover {
        transform: scale(1.2);
    }

    .icon-option.selected {
        transform: scale(1.5);
        color: var(--orange-shopee) !important;
    }

    .icon-scroll-container {
        display: flex;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        gap: 1rem;
        scroll-behavior: smooth;
    }

    .icon-box {
        flex: 0 0 auto;
        padding: 0.5rem;
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.icon-option').forEach(icon => {
            icon.addEventListener('click', function () {
                document.getElementById('icon').value = this.getAttribute('data-icon');
                document.querySelectorAll('.icon-option').forEach(i => i.classList.remove('selected', 'text-orange-shopee'));
                this.classList.add('selected', 'text-orange-shopee');
            });
        });
    });
</script>
