@extends('layouts.admin')

@section('title', 'Quản Lý Người Dùng')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3" style="background: #fff; padding: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: #ee4d2d; font-weight: bold; font-size: 24px; margin: 0;">Quản Lý Người Dùng</h2>
            <a href="{{ route('admin.users.create') }}" class="btn"
                style="background: #ee4d2d; color: white; font-weight: 600; padding: 10px 20px; border-radius: 5px;">
                <i class="bi bi-plus-lg me-2"></i>Thêm Người Dùng
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="background: #d4edda; border: none; border-radius: 5px; color: #155724;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Bảng người dùng -->
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="border-radius: 5px; overflow: hidden;">
                <thead style="background: #f5f5f5; color: #333;">
                    <tr>
                        <th style="padding: 15px;">ID</th>
                        <th style="padding: 15px;">Tên</th>
                        <th style="padding: 15px;">Email</th>
                        <th style="padding: 15px;">Avatar</th>
                        <th style="padding: 15px;">Vai trò</th>
                        <th style="padding: 15px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;">{{ $user->id }}</td>
                        <td style="padding: 15px; font-weight: 500;">{{ Str::limit($user->name, 30, '...') }}</td>
                        <td style="padding: 15px;">{{ $user->email }}</td>
                        <td style="padding: 15px;">
                            @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #eee;"> 
            
                            @else
                            <span class="text-muted">Chưa có ảnh</span>
                            @endif
                        </td>
                        <td style="padding: 15px;">
                            @if($user->role == 'admin')
                            <span class="badge" style="background: #28a745; color: white;">Admin</span>
                            @elseif($user->role == 'user')
                            <span class="badge" style="background: #007bff; color: white;">User</span>
                            @else
                            <span class="badge" style="background: #6c757d; color: white;">{{ ucfirst($user->role) }}</span>
                            @endif
                        </td>
                        <td style="padding: 15px;">
                            <!-- Nút Edit -->
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                class="btn btn-sm me-2" 
                                style="background: #ffd700; color: #333; border-radius: 5px;" 
                                title="Sửa">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </a>
                            <!-- Nút Delete -->
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" 
                                    style="background: #dc3545; color: white; border-radius: 5px;" 
                                    title="Xóa">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
        background: #fafafa;
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .pagination .page-link {
        border-radius: 5px;
        color: #ee4d2d;
        margin: 0 5px;
    }

    .pagination .page-item.active .page-link {
        background: #ee4d2d;
        border-color: #ee4d2d;
        color: white;
    }

    .pagination .page-link:hover {
        background: #ee4d2d;
        color: white;
    }
</style>

<script>
    // Xác nhận xóa người dùng
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc muốn xóa người dùng này không?')) {
                this.submit();
            }
        });
    });
</script>
@endsection