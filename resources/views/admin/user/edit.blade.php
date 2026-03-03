@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa thông tin khách hàng')
@section('user-active', 'active')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}" class="text-decoration-none">Quản lý khách hàng</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.user.show', $user->user_id) }}" class="text-decoration-none">{{ $user->username }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa thông tin</li>
        </ol>
    </nav>

    <!-- Thông báo -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Vui lòng kiểm tra lại thông tin!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin khách hàng: {{ $user->username }}
            </h5>
        </div>
        <div class="card-body">
            <form id="updateUserForm" action="{{ route('admin.user.update', $user->user_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Ảnh đại diện -->
                    <div class="col-md-3">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Ảnh đại diện</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <img id="avatar-preview" src="{{ $user->avatar_url ? asset('images/' . $user->avatar_url) : asset('images/avatar.jpg') }}" 
                                         alt="Avatar" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                {{-- <div class="mb-3">
                                    <input type="file" class="form-control @error('avatar_url') is-invalid @enderror" id="avatar_url" name="avatar_url" 
                                           accept="image/*" onchange="previewImage(this)">
                                    <div class="form-text">Chọn ảnh đại diện mới (JPG, PNG, GIF)</div>
                                    @error('avatar_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                                @if($user->avatar_url)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_avatar" id="remove_avatar" value="1">
                                    <label class="form-check-label text-danger" for="remove_avatar">
                                        Xóa ảnh đại diện
                                    </label>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin tài khoản -->
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Thông tin tài khoản</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" 
                                                   value="{{ old('username', $user->username) }}" required>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
                                                   value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                                <option value="mở" {{ old('status', $user->status) == 'mở' ? 'selected' : '' }}>Mở</option>
                                                <option value="khóa" {{ old('status', $user->status) == 'khóa' ? 'selected' : '' }}>Khóa</option>
                                                <option value="đã xóa" {{ old('status', $user->status) == 'đã xóa' ? 'selected' : '' }}>Đã xóa</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Thông tin cá nhân</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Giới tính</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input @error('sex') is-invalid @enderror" type="radio" name="sex" id="nam" 
                                                           value="nam" {{ old('sex', $user->sex) == 'nam' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="nam">Nam</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input @error('sex') is-invalid @enderror" type="radio" name="sex" id="nữ" 
                                                           value="nữ" {{ old('sex', $user->sex) == 'nữ' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="nữ">Nữ</label>
                                                </div>
                                            </div>
                                            @error('sex')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone_number" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" 
                                                   value="{{ old('phone_number', $user->phone_number) }}" required 
                                                   maxlength="10">
                                            <div class="form-text">Số điện thoại phải có đúng 10 chữ số.</div>
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="dob" class="form-label">Ngày sinh</label>
                                            <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob" name="dob" 
                                                   value="{{ old('dob', $user->dob ? \Carbon\Carbon::parse($user->dob)->format('Y-m-d') : '') }}">
                                            @error('dob')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Thông tin hệ thống (chỉ hiển thị) -->
                                        <div class="mt-4 p-3 bg-light rounded">
                                            <small class="text-muted">
                                                <div><strong>Ngày tạo:</strong> {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
                                                <div><strong>Cập nhật lần cuối:</strong> {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'N/A' }}</div>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nút submit -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.user.show', $user->user_id) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Cập nhật thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    
    .form-label {
        font-weight: 500;
    }
    
    .img-thumbnail {
        border: 3px solid #dee2e6;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .img-thumbnail:hover {
        border-color: #0d6efd;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endsection

@section('js')
<script>
    function previewImage(input) {
        const preview = document.getElementById('avatar-preview');
        const file = input.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(file);
        }
    }

    // Click vào ảnh để chọn file
    document.getElementById('avatar-preview').addEventListener('click', function() {
        document.getElementById('avatar_url').click();
    });

    // Tự động ẩn alert sau 5 giây
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endsection