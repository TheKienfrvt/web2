@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa sản phẩm - ' . $product->product_name)
@section('product-active', 'active')

@section('content')
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}" class="text-decoration-none">Quản
                        lý sản phẩm</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.product.show', $product->product_id) }}"
                        class="text-decoration-none">{{ $product->product_name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
            </ol>
        </nav>

        <!-- Thông báo -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Vui lòng kiểm tra lại thông tin!</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Chỉnh sửa sản phẩm: {{ $product->product_name }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.product.update', $product->product_id) }}" method="POST"
                    enctype="multipart/form-data" id="editProductForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Thông tin chung -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Thông tin chung</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Tên sản phẩm <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                            id="product_name" name="product_name"
                                            value="{{ old('product_name', $product->product_name) }}" required>
                                        @error('product_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Danh mục</label>
                                        <div>
                                            <span
                                                class="form-control bg-light">{{ $product->category->category_name ?? 'N/A' }}</span>
                                            <input type="hidden" name="category_id" value="{{ $product->category_id }}">
                                        </div>
                                        <small class="text-muted">Không thể thay đổi danh mục sau khi tạo</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="price" class="form-label">Giá bán <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                                id="price" name="price" value="{{ old('price', $product->price) }}" min="0"
                                                step="1" required>
                                            <span class="input-group-text">đ</span>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="stock" class="form-label">Tồn kho</label>
                                        <input type="number" class="form-control bg-light" id="stock"
                                            value="{{ $product->stock }}" disabled>
                                        <div class="form-text">Tồn kho được cập nhật tự động qua phiếu nhập và đơn hàng
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Trạng thái <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="hiện" {{ old('status', $product->status) == 'hiện' ? 'selected' : '' }}>Hiện</option>
                                            <option value="ẩn" {{ old('status', $product->status) == 'ẩn' ? 'selected' : '' }}>Ẩn</option>
                                            <option value="đã xóa" {{ old('status', $product->status) == 'đã xóa' ? 'selected' : '' }}>Đã xóa</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ảnh sản phẩm -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Hình ảnh sản phẩm</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 text-center">
                                        @if($product->image_url)
                                            <img id="image-preview" src="{{ asset('images/' . $product->image_url) }}"
                                                alt="Preview" class="img-thumbnail mb-3"
                                                style="max-width: 200px; max-height: 200px;">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="remove_image"
                                                    id="remove_image" value="1">
                                                <label class="form-check-label text-danger" for="remove_image">
                                                    Xóa ảnh hiện tại
                                                </label>
                                            </div>
                                        @else
                                            <img id="image-preview" src="{{ asset('images/default-product.png') }}"
                                                alt="Preview" class="img-thumbnail mb-3"
                                                style="max-width: 200px; max-height: 200px; display: none;">
                                            <div id="no-image" class="text-muted mb-3">
                                                <i class="fas fa-image fa-3x"></i>
                                                <p class="mt-2">Chưa có hình ảnh</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="image_url" class="form-label">Chọn hình ảnh mới</label>
                                        <input type="file" class="form-control @error('image_url') is-invalid @enderror"
                                            id="image_url" name="image_url" accept=".jpg,.jpeg,.png">
                                        <div class="form-text">
                                            Định dạng: JPG, PNG. Kích thước tối đa: 10MB
                                        </div>
                                        @error('image_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin hệ thống -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Thông tin hệ thống</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Mã sản phẩm:</strong>
                                        <span class="badge bg-secondary">#{{ $product->product_id }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Ngày tạo:</strong>
                                        {{ $product->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Cập nhật lần cuối:</strong>
                                        {{ $product->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin chi tiết theo danh mục - LUÔN HIỂN THỊ -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Thông tin chi tiết - {{ $product->category->category_name }}
                            </h6>
                        </div>
                        <div class="card-body">
                            @if(!empty($detailAttributes))
                                <div class="row" id="detail-fields">
                                    @foreach($detailAttributes as $attribute => $label)
                                        <div class="col-md-6 mb-3">
                                            <label for="detail_{{ $attribute }}" class="form-label">{{ $label }}</label>
                                            @php
                                                // Lấy giá trị từ productDetail nếu có, nếu không thì từ old, nếu không thì để trống
                                                $value = '';
                                                if ($productDetail && isset($productDetail->$attribute)) {
                                                    $value = $productDetail->$attribute;
                                                }
                                                $value = old("details.$attribute", $value);

                                                // Xác định kiểu input
                                                $inputType = 'text';
                                                $step = '1';
                                                if ($attribute == 'gia') {
                                                    $inputType = 'number';
                                                }
                                            @endphp
                                            <input type="{{ $inputType }}"
                                                class="form-control @error("details.$attribute") is-invalid @enderror"
                                                id="detail_{{ $attribute }}" name="details[{{ $attribute }}]" value="{{ $value }}"
                                                {{ $inputType === 'number' ? 'step="' . $step . '" min="0"' : 'maxlength="255"' }}
                                                placeholder="Nhập {{ strtolower($label) }}">
                                            @error("details.$attribute")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <p class="text-muted">Danh mục này không có thông tin chi tiết</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Nút submit -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.product.show', $product->product_id) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i> Cập nhật sản phẩm
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
            border: 2px solid #dee2e6;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('image_url');
            const imagePreview = document.getElementById('image-preview');
            const noImage = document.getElementById('no-image');
            const removeImageCheckbox = document.getElementById('remove_image');

            // Xử lý preview ảnh
            if (imageInput) {
                imageInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            if (imagePreview) {
                                imagePreview.src = e.target.result;
                                imagePreview.style.display = 'block';
                            }
                            if (noImage) {
                                noImage.style.display = 'none';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Xử lý khi chọn xóa ảnh
            if (removeImageCheckbox) {
                removeImageCheckbox.addEventListener('change', function () {
                    if (this.checked) {
                        if (imagePreview) {
                            imagePreview.style.display = 'none';
                        }
                        if (noImage) {
                            noImage.style.display = 'block';
                        }
                        if (imageInput) {
                            imageInput.value = '';
                        }
                    } else {
                        if (imagePreview) {
                            imagePreview.style.display = 'block';
                        }
                        if (noImage) {
                            noImage.style.display = 'none';
                        }
                    }
                });
            }

            // Tự động ẩn alert sau 5 giây
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
@endsection