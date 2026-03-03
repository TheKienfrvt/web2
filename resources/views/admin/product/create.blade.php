@extends('admin.layouts.app')

@section('title', 'Thêm sản phẩm mới')
@section('product-active', 'active')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}" class="text-decoration-none">Quản lý sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm sản phẩm mới</li>
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
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle me-2"></i>Thêm sản phẩm mới
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
                @csrf
                
                <div class="row">
                    <!-- Thông tin chung -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Thông tin chung</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="product_name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('product_name') is-invalid @enderror" 
                                           id="product_name" name="product_name" 
                                           value="{{ old('product_name') }}" required>
                                    @error('product_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->category_id }}" 
                                                {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá bán <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" 
                                               value="{{ old('price') }}" 
                                               min="0" step="1" required>
                                        <span class="input-group-text">đ</span>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="hiện" {{ old('status') == 'hiện' ? 'selected' : 'selected' }}>Hiện</option>
                                        <option value="ẩn" {{ old('status') == 'ẩn' ? 'selected' : '' }}>Ẩn</option>
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
                                    <img id="image-preview" src="" 
                                         alt="Preview" class="img-thumbnail mb-3" 
                                         style="max-width: 200px; max-height: 200px; display: none;">
                                    <div id="no-image" class="text-muted mb-3">
                                        <i class="fas fa-image fa-3x"></i>
                                        <p class="mt-2">Chưa có hình ảnh</p>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image_url" class="form-label">Chọn hình ảnh</label>
                                    <input type="file" class="form-control @error('image_url') is-invalid @enderror" 
                                           id="image_url" name="image_url" 
                                           accept=".jpg,.jpeg,.png">
                                    <div class="form-text">
                                        Định dạng: JPG, PNG. Kích thước tối đa: 10MB
                                    </div>
                                    @error('image_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin chi tiết theo danh mục -->
                <div class="card mb-4" id="detail-section" style="display: none;">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Thông tin chi tiết - <span id="category-name"></span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="detail-fields">
                            <!-- Các trường detail sẽ được thêm vào đây bằng JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Nút submit -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Tạo sản phẩm
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
    
    .detail-field {
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const detailSection = document.getElementById('detail-section');
        const detailFields = document.getElementById('detail-fields');
        const categoryName = document.getElementById('category-name');
        const imageInput = document.getElementById('image_url');
        const imagePreview = document.getElementById('image-preview');
        const noImage = document.getElementById('no-image');

        // Mapping các thuộc tính detail theo category (sẽ được cập nhật từ server)
        const categoryDetails = @json($categoryDetails);

        // Xử lý thay đổi category
        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;
            
            if (selectedCategory) {
                // Hiển thị section detail
                detailSection.style.display = 'block';
                
                // Cập nhật tên category
                const selectedOption = this.options[this.selectedIndex];
                categoryName.textContent = selectedOption.textContent;
                
                // Hiển thị các trường detail
                displayDetailFields(selectedCategory);
            } else {
                // Ẩn section detail nếu không chọn category
                detailSection.style.display = 'none';
                detailFields.innerHTML = '';
            }
        });

        // Hiển thị các trường detail theo category
        function displayDetailFields(categoryId) {
            detailFields.innerHTML = '';
            
            if (categoryDetails[categoryId]) {
                const attributes = categoryDetails[categoryId];
                
                Object.keys(attributes).forEach(attribute => {
                    const label = attributes[attribute];
                    const fieldId = `detail_${attribute}`;
                    
                    const fieldDiv = document.createElement('div');
                    fieldDiv.className = 'detail-field';
                    
                    // Xác định kiểu input dựa trên tên thuộc tính
                    let inputType = 'text';
                    let step = '1';
                    
                    if (attribute == 'gia') {
                        inputType = 'number';
                    }
                    
                    fieldDiv.innerHTML = `
                        <label for="${fieldId}" class="form-label">${label}</label>
                        <input type="${inputType}" 
                               class="form-control" 
                               id="${fieldId}" 
                               name="details[${attribute}]" 
                               ${inputType === 'number' ? 'step="' + step + '" min="0"' : 'maxlength="255"'}
                               placeholder="Nhập ${label.toLowerCase()}">
                    `;
                    
                    detailFields.appendChild(fieldDiv);
                });
            }
        }

        // Xử lý preview ảnh
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    noImage.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
                noImage.style.display = 'block';
            }
        });

        // Kiểm tra nếu đã chọn category từ trước (khi có lỗi validation)
        if (categorySelect.value) {
            categorySelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection