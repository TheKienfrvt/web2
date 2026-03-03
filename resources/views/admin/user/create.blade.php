@extends('admin.layouts.app')

@section('title', 'Thêm khách hàng mới')
@section('user-active', 'active')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}" class="text-decoration-none">Quản lý khách hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm khách hàng mới</li>
        </ol>
    </nav>

    <!-- Thông báo -->
    <div id="alert-container"></div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-user-plus me-2"></i>Thêm khách hàng mới
            </h5>
        </div>
        <div class="card-body">
            <form id="createUserForm" action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <!-- Thông tin đăng nhập -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Thông tin đăng nhập</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="{{ old('username') }}" required 
                                           placeholder="Nhập tên đăng nhập">
                                    <div class="form-text">Tên đăng nhập phải từ 3-50 ký tự, chỉ chứa chữ cái, số và dấu gạch dưới.</div>
                                    <div class="invalid-feedback" id="username-error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email') }}" required 
                                           placeholder="Nhập địa chỉ email">
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" 
                                               required placeholder="Nhập mật khẩu">
                                        <button type="button" class="btn btn-outline-secondary toggle-password" 
                                                data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự.</div>
                                    <div class="invalid-feedback" id="password-error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirmation" 
                                               name="password_confirmation" required placeholder="Nhập lại mật khẩu">
                                        <button type="button" class="btn btn-outline-secondary toggle-password" 
                                                data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback" id="password_confirmation-error"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin cá nhân -->
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
                                            <input class="form-check-input" type="radio" name="sex" id="male" 
                                                   value="nam" {{ old('sex') == 'nam' ? 'checked' : 'checked' }}>
                                            <label class="form-check-label" for="male">Nam</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sex" id="female" 
                                                   value="nữ" {{ old('sex') == 'nữa' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="female">Nữ</label>
                                        </div>
                                        {{-- <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sex" id="other" 
                                                   value="other" {{ old('sex') == 'other' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="other">Khác</label>
                                        </div> --}}
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                           value="{{ old('phone_number') }}" 
                                           placeholder="Nhập số điện thoại">
                                    <div class="invalid-feedback" id="phone_number-error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="dob" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" id="dob" name="dob" 
                                           value="{{ old('dob') }}">
                                    <div class="invalid-feedback" id="dob-error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nút submit -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i> Đặt lại
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="fas fa-save me-1"></i> Tạo khách hàng
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
    
    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .toggle-password {
        border-left: none;
    }
    
    .toggle-password:hover {
        background-color: #e9ecef;
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Toggle hiển thị mật khẩu
        $('.toggle-password').on('click', function() {
            const target = $(this).data('target');
            const input = $('#' + target);
            const icon = $(this).find('i');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
        
        // Validate form real-time
        $('#username').on('input', function() {
            validateUsername($(this).val());
        });
        
        $('#email').on('input', function() {
            validateEmail($(this).val());
        });
        
        $('#password').on('input', function() {
            validatePassword($(this).val());
        });
        
        $('#password_confirmation').on('input', function() {
            validatePasswordConfirmation();
        });
        
        $('#phone_number').on('input', function() {
            validatePhoneNumber($(this).val());
        });
        
        // Hàm validate username
        function validateUsername(username) {
            const usernameRegex = /^[a-zA-Z0-9_]{3,50}$/;
            const field = $('#username');
            const error = $('#username-error');
            
            if (!username) {
                field.removeClass('is-valid is-invalid');
                error.text('');
                return false;
            }
            
            if (!usernameRegex.test(username)) {
                field.removeClass('is-valid').addClass('is-invalid');
                error.text('Tên đăng nhập phải từ 3-50 ký tự và chỉ chứa chữ cái, số, dấu gạch dưới.');
                return false;
            } else {
                field.removeClass('is-invalid').addClass('is-valid');
                error.text('');
                return true;
            }
        }
        
        // Hàm validate email
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const field = $('#email');
            const error = $('#email-error');
            
            if (!email) {
                field.removeClass('is-valid is-invalid');
                error.text('');
                return false;
            }
            
            if (!emailRegex.test(email)) {
                field.removeClass('is-valid').addClass('is-invalid');
                error.text('Địa chỉ email không hợp lệ.');
                return false;
            } else {
                field.removeClass('is-invalid').addClass('is-valid');
                error.text('');
                return true;
            }
        }
        
        // Hàm validate password
        function validatePassword(password) {
            const field = $('#password');
            const error = $('#password-error');
            
            if (!password) {
                field.removeClass('is-valid is-invalid');
                error.text('');
                return false;
            }
            
            if (password.length < 6) {
                field.removeClass('is-valid').addClass('is-invalid');
                error.text('Mật khẩu phải có ít nhất 6 ký tự.');
                return false;
            } else {
                field.removeClass('is-invalid').addClass('is-valid');
                error.text('');
                validatePasswordConfirmation();
                return true;
            }
        }
        
        // Hàm validate password confirmation
        function validatePasswordConfirmation() {
            const password = $('#password').val();
            const confirmation = $('#password_confirmation').val();
            const field = $('#password_confirmation');
            const error = $('#password_confirmation-error');
            
            if (!confirmation) {
                field.removeClass('is-valid is-invalid');
                error.text('');
                return false;
            }
            
            if (password !== confirmation) {
                field.removeClass('is-valid').addClass('is-invalid');
                error.text('Mật khẩu xác nhận không khớp.');
                return false;
            } else {
                field.removeClass('is-invalid').addClass('is-valid');
                error.text('');
                return true;
            }
        }
        
        // Hàm validate phone number
        function validatePhoneNumber(phone) {
            const phoneRegex = /^(0|\+84)(\d{9,10})$/;
            const field = $('#phone_number');
            const error = $('#phone_number-error');
            
            if (!phone) {
                field.removeClass('is-valid is-invalid');
                error.text('');
                return true; // Phone không bắt buộc
            }
            
            if (!phoneRegex.test(phone)) {
                field.removeClass('is-valid').addClass('is-invalid');
                error.text('Số điện thoại không hợp lệ. Định dạng: 0xxxxxxxxx hoặc +84xxxxxxxxx');
                return false;
            } else {
                field.removeClass('is-invalid').addClass('is-valid');
                error.text('');
                return true;
            }
        }
        
        // Xử lý submit form
        $('#createUserForm').on('submit', function(e) {
            e.preventDefault();
            
            // Validate tất cả trường
            const isUsernameValid = validateUsername($('#username').val());
            const isEmailValid = validateEmail($('#email').val());
            const isPasswordValid = validatePassword($('#password').val());
            const isPasswordConfirmationValid = validatePasswordConfirmation();
            const isPhoneValid = validatePhoneNumber($('#phone_number').val());
            
            if (!isUsernameValid || !isEmailValid || !isPasswordValid || !isPasswordConfirmationValid || !isPhoneValid) {
                showAlert('danger', 'Vui lòng kiểm tra lại thông tin!');
                return;
            }
            
            // Hiển thị loading
            const submitBtn = $('#submit-btn');
            const originalHtml = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...');
            
            // Gửi form
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    showAlert('success', 'Tạo khách hàng thành công!');
                    setTimeout(function() {
                        window.location.href = "{{ route('admin.user.index') }}";
                    }, 1500);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        // Hiển thị lỗi cho từng trường
                        for (const field in errors) {
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}-error`).text(errors[field][0]);
                        }
                        showAlert('danger', 'Vui lòng kiểm tra lại thông tin!');
                    } else {
                        showAlert('danger', 'Đã xảy ra lỗi! Vui lòng thử lại.');
                    }
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalHtml);
                }
            });
        });
        
        // Xóa thông báo lỗi khi người dùng bắt đầu nhập
        $('input').on('input', function() {
            if ($(this).hasClass('is-invalid')) {
                $(this).removeClass('is-invalid');
                $(`#${this.id}-error`).text('');
            }
        });
        
        // Đặt focus vào trường đầu tiên
        $('#username').focus();
    });
</script>
@endsection