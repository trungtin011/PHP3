@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="form-wrapper">
    <h2 class="text-xl font-semibold text-center text-gray-800 mb-5">Sửa người dùng</h2>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="form-label text-sm font-semibold text-gray-700">
                Họ tên <span class="text-orange-shopee">*</span>
            </label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="mb-4">
            <label for="email" class="form-label text-sm font-semibold text-gray-700">
                Email <span class="text-orange-shopee">*</span>
            </label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label text-sm font-semibold text-gray-700">
                Mật khẩu (Để trống nếu không thay đổi)
            </label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label text-sm font-semibold text-gray-700">
                Xác nhận mật khẩu
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <div class="mb-4">
            <label for="role" class="form-label text-sm font-semibold text-gray-700">
                Vai trò <span class="text-orange-shopee">*</span>
            </label>
            <select name="role" id="role" class="form-control" required>
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Người dùng</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
            </select>
        </div>

        <div class="flex gap-3 justify-end">
            <button type="submit" class="btn bg-orange-shopee text-white font-semibold hover:bg-orange-shopee-hover shadow-sm">
                <i class="fa-solid fa-user-edit mr-2"></i> Cập nhật người dùng
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 shadow-sm">
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

    .flex.gap-3 {
        display: flex;
        gap: 1rem;
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
