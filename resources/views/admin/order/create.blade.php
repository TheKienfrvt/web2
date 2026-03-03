@extends('admin.layouts.app')

@section('title', 'Tạo đơn hàng mới')
@section('order-active', 'active')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.order.index') }}" class="text-decoration-none">Đơn hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tạo đơn hàng mới</li>
        </ol>
    </nav>

    <!-- Thông báo -->
    <div id="alert-container"></div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tạo đơn hàng mới</h5>
        </div>
        <div class="card-body">
            <form id="createOrderForm" action="{{ route('admin.order.store') }}" method="POST">
                @csrf
                
                <!-- Thông tin khách hàng -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Khách hàng <span class="text-danger">*</span></label>
                            <select class="form-select" id="user_id" name="user_id" required aria-describedby="userHelp">
                                <option value="">-- Chọn khách hàng --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user['user_id'] }}">
                                        {{ $user['display_text'] }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="userHelp" class="form-text">Chọn khách hàng từ danh sách</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Phương thức thanh toán <span class="text-danger">*</span></label>
                            <select class="form-select" id="payment_method" name="payment_method" required aria-describedby="paymentHelp">
                                <option value="">-- Chọn phương thức --</option>
                                <option value="chuyển khoản">Chuyển khoản</option>
                                <option value="tiền mặt">Tiền mặt</option>
                            </select>
                            <div id="paymentHelp" class="form-text">Chọn hình thức thanh toán</div>
                        </div>
                    </div>
                </div>

                <!-- Địa chỉ giao hàng -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="2" required 
                                      placeholder="Nhập địa chỉ giao hàng đầy đủ" aria-describedby="addressHelp"></textarea>
                            <div id="addressHelp" class="form-text">Nhập địa chỉ giao hàng chi tiết</div>
                        </div>
                    </div>
                    {{-- <div class="col-md-4">
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label">Ngày giao hàng dự kiến</label>
                            <input type="date" class="form-control" id="delivery_date" name="delivery_date" 
                                   min="{{ date('Y-m-d') }}" value="{{ $defaultDeliveryDate ?? '' }}"
                                   aria-describedby="deliveryHelp">
                            <div id="deliveryHelp" class="form-text">Ngày giao hàng ước tính</div>
                        </div>
                    </div> --}}
                </div>

                <!-- Thêm sản phẩm -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Danh sách sản phẩm</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-product-btn"
                                    aria-describedby="addProductHelp">
                                <i class="fas fa-plus me-1"></i> Thêm sản phẩm
                            </button>
                            <div id="addProductHelp" class="sr-only">Mở danh sách sản phẩm để chọn</div>
                        </div>
                    </div>
                </div>

                <!-- Bảng sản phẩm -->
                <div class="table-responsive" aria-live="polite" aria-atomic="true">
                    <table class="table table-bordered" id="products-table">
                        <caption class="sr-only">Danh sách sản phẩm trong đơn hàng</caption>
                        <thead class="table-light">
                            <tr>
                                <th scope="col" width="35%">Sản phẩm</th>
                                <th scope="col" width="15%">Giá</th>
                                <th scope="col" width="15%">Số lượng</th>
                                <th scope="col" width="15%">Tồn kho</th>
                                <th scope="col" width="15%">Thành tiền</th>
                                <th scope="col" width="5%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="products-tbody">
                            <!-- Sản phẩm sẽ được thêm vào đây bằng JavaScript -->
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                                <td class="fw-bold" id="total-amount" aria-live="polite">0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Thông báo khi không có sản phẩm -->
                <div id="empty-products-message" class="alert alert-info text-center d-none">
                    <i class="fas fa-info-circle me-2"></i>Chưa có sản phẩm nào được thêm vào đơn hàng
                </div>

                <!-- Nút submit -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.order.index') }}" class="btn btn-secondary" aria-label="Quay lại danh sách đơn hàng">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary" id="submit-btn" aria-describedby="submitHelp">
                        <i class="fas fa-save me-1"></i> Tạo đơn hàng
                    </button>
                    <div id="submitHelp" class="sr-only">Tạo đơn hàng mới với thông tin đã nhập</div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal chọn sản phẩm -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Chọn sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                @if(count($products) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <caption class="sr-only">Danh sách sản phẩm có sẵn</caption>
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Giá</th>
                                <th scope="col">Tồn kho</th>
                                <th scope="col">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>
                                    @if($product->image_url)
                                        <img src="{{ asset('images/' . $product->image_url) }}" 
                                             alt="{{ $product->product_name }}" 
                                             class="img-thumbnail" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;" 
                                             aria-hidden="true">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                        <span class="sr-only">Không có hình ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                <td>
                                    <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success select-product" 
                                            data-product-id="{{ $product->product_id }}"
                                            data-product-name="{{ $product->product_name }}"
                                            data-price="{{ $product->price }}"
                                            data-stock="{{ $product->stock }}"
                                            data-image="{{ $product->image_url ?? '' }}"
                                            aria-label="Chọn sản phẩm {{ $product->product_name }}">
                                        <i class="fas fa-plus me-1"></i> Chọn
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có sản phẩm nào khả dụng</p>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Đóng cửa sổ chọn sản phẩm">
                    <i class="fas fa-times me-1"></i> Đóng
                </button>
            </div>
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
    
    .table th {
        border-top: none;
        font-weight: 600;
    }
    
    .product-row:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .remove-product {
        color: #dc3545;
        cursor: pointer;
        border: none;
        background: none;
        padding: 0.25rem;
    }
    
    .remove-product:hover {
        color: #bb2d3b;
        background-color: rgba(220, 53, 69, 0.1);
        border-radius: 0.25rem;
    }
    
    .remove-product:focus {
        outline: 2px solid #dc3545;
        outline-offset: 2px;
    }
    
    .stock-info {
        font-size: 0.85rem;
        color: #198754;
    }
    
    .stock-warning {
        color: #dc3545;
        font-weight: bold;
    }
    
    .input-group-sm .form-control {
        min-height: calc(1.5em + 0.5rem + 2px);
    }
    
    /* Accessibility improvements */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
    
    /* Focus styles for better accessibility */
    .btn:focus,
    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        border-color: #86b7fe;
    }
    
    /* Modal backdrop fix */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        let productCounter = 0;
        const selectedProducts = new Set();
        
        // Khởi tạo trạng thái ban đầu
        updateEmptyProductsMessage();
        
        // Quản lý focus và accessibility cho modal
        $('#add-product-btn').on('click', function() {
            $('#productModal').modal('show');
        });
        
        // Khi modal hiển thị, focus vào nút đóng
        $('#productModal').on('shown.bs.modal', function() {
            $(this).find('.btn-close').focus();
        });
        
        // Khi modal ẩn, trả focus về nút "Thêm sản phẩm"
        $('#productModal').on('hidden.bs.modal', function() {
            $('#add-product-btn').focus();
        });
        
        // Chọn sản phẩm từ modal
        $('.select-product').on('click', function() {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const price = $(this).data('price');
            const stock = $(this).data('stock');
            const image = $(this).data('image');
            
            // Kiểm tra nếu sản phẩm đã được chọn
            if (selectedProducts.has(productId)) {
                showAlert('Sản phẩm này đã được thêm vào danh sách!', 'warning');
                return;
            }
            
            // Kiểm tra tồn kho
            if (stock <= 0) {
                showAlert('Sản phẩm này đã hết hàng!', 'danger');
                return;
            }
            
            // Thêm sản phẩm vào bảng
            addProductRow(productId, productName, price, stock, image);
            
            // Đóng modal
            $('#productModal').modal('hide');
            
            // Thông báo cho screen reader
            announceToScreenReader(`Đã thêm sản phẩm ${productName} vào đơn hàng`);
        });
        
        // Hàm thêm sản phẩm vào bảng
        function addProductRow(productId, productName, price, stock, image) {
            productCounter++;
            const rowId = `product-${productCounter}`;
            
            const imageHtml = image ? 
                `<img src="{{ asset('images/') }}/${image}" alt="${productName}" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">` :
                `<div class="bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" aria-hidden="true">
                    <i class="fas fa-image text-muted"></i>
                </div>
                <span class="sr-only">Không có hình ảnh cho ${productName}</span>`;
            
            const rowHtml = `
                <tr id="${rowId}" class="product-row">
                    <td>
                        <div class="d-flex align-items-center">
                            ${imageHtml}
                            <div class="ms-2">
                                <div class="fw-bold">${productName}</div>
                                <input type="hidden" name="products[${productCounter}][product_id]" value="${productId}">
                                <div class="sr-only">Mã sản phẩm: ${productId}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control price" 
                                   name="products[${productCounter}][price]" 
                                   value="${price}" min="0" step="1000" required
                                   aria-label="Giá sản phẩm ${productName}">
                            <span class="input-group-text">đ</span>
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm quantity" 
                               name="products[${productCounter}][quantity]" 
                               value="1" min="1" max="${stock}" required
                               aria-label="Số lượng sản phẩm ${productName}"
                               aria-describedby="stock-info-${productCounter}">
                        <div id="stock-info-${productCounter}" class="stock-info mt-1">
                            Tồn kho: <span class="stock-value">${stock}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-${stock > 0 ? 'success' : 'danger'}">${stock}</span>
                    </td>
                    <td class="text-end product-total" aria-live="polite">${formatCurrency(price)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm remove-product" 
                                data-product-id="${productId}"
                                aria-label="Xóa sản phẩm ${productName} khỏi đơn hàng">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $('#products-tbody').append(rowHtml);
            selectedProducts.add(productId);
            updateTotalAmount();
            updateEmptyProductsMessage();
            
            // Thêm sự kiện cho các input
            $(`#${rowId} .price, #${rowId} .quantity`).on('input', function() {
                updateProductTotal(rowId);
                updateTotalAmount();
                validateStock(rowId);
            });
            
            // Sự kiện xóa sản phẩm
            $(`#${rowId} .remove-product`).on('click', function() {
                removeProduct(rowId, productId, productName);
            });
            
            // Validate stock ban đầu
            validateStock(rowId);
            
            // Focus vào số lượng của sản phẩm vừa thêm
            setTimeout(() => {
                $(`#${rowId} .quantity`).focus();
            }, 100);
        }
        
        // Hàm cập nhật tổng tiền cho một sản phẩm
        function updateProductTotal(rowId) {
            const row = $(`#${rowId}`);
            const price = parseFloat(row.find('.price').val()) || 0;
            const quantity = parseInt(row.find('.quantity').val()) || 0;
            const total = price * quantity;
            
            row.find('.product-total').text(formatCurrency(total));
        }
        
        // Hàm cập nhật tổng tiền toàn bộ đơn hàng
        function updateTotalAmount() {
            let total = 0;
            $('.product-row').each(function() {
                const price = parseFloat($(this).find('.price').val()) || 0;
                const quantity = parseInt($(this).find('.quantity').val()) || 0;
                total += price * quantity;
            });
            
            $('#total-amount').text(formatCurrency(total));
            
            // Thông báo cho screen reader khi tổng thay đổi
            announceToScreenReader(`Tổng tiền đơn hàng: ${formatCurrency(total)}`);
        }
        
        // Hàm xóa sản phẩm
        function removeProduct(rowId, productId, productName) {
            $(`#${rowId}`).remove();
            selectedProducts.delete(productId);
            updateTotalAmount();
            updateEmptyProductsMessage();
            
            // Thông báo cho screen reader
            announceToScreenReader(`Đã xóa sản phẩm ${productName} khỏi đơn hàng`);
        }
        
        // Hàm validate số lượng không vượt quá tồn kho
        function validateStock(rowId) {
            const row = $(`#${rowId}`);
            const quantity = parseInt(row.find('.quantity').val()) || 0;
            const stock = parseInt(row.find('.stock-value').text());
            const stockInfo = row.find('.stock-info');
            const quantityInput = row.find('.quantity');
            
            if (quantity > stock) {
                quantityInput.addClass('is-invalid');
                stockInfo.removeClass('stock-info').addClass('stock-warning');
                quantityInput.attr('aria-invalid', 'true');
            } else {
                quantityInput.removeClass('is-invalid');
                stockInfo.removeClass('stock-warning').addClass('stock-info');
                quantityInput.attr('aria-invalid', 'false');
            }
        }
        
        // Hàm hiển thị/thông báo trạng thái không có sản phẩm
        function updateEmptyProductsMessage() {
            const hasProducts = $('.product-row').length > 0;
            if (hasProducts) {
                $('#empty-products-message').addClass('d-none');
                $('#products-table').removeClass('d-none');
            } else {
                $('#empty-products-message').removeClass('d-none');
                $('#products-table').addClass('d-none');
            }
        }
        
        // Hàm định dạng tiền tệ
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(amount);
        }
        
        // Hàm hiển thị thông báo
        // function showAlert(message, type) {
        //     const alertId = 'alert-' + Date.now();
        //     const alert = $(`
        //         <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert" aria-live="assertive">
        //             ${message}
        //             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng thông báo"></button>
        //         </div>
        //     `);
        //     $('#alert-container').html(alert);
            
        //     // Tự động đọc thông báo cho screen reader
        //     announceToScreenReader(message);
            
        //     setTimeout(function() {
        //         $(`#${alertId}`).alert('close');
        //     }, 5000);
        // }
        
        // Hàm thông báo cho screen reader
        function announceToScreenReader(message) {
            const announcer = $('#screen-reader-announcer');
            if (announcer.length === 0) {
                $('body').append('<div id="screen-reader-announcer" class="sr-only" aria-live="polite" aria-atomic="true"></div>');
            }
            $('#screen-reader-announcer').text(message);
            
            // Clear sau 1 giây để có thể thông báo lại
            setTimeout(() => {
                $('#screen-reader-announcer').text('');
            }, 1000);
        }
        
        // Xử lý submit form
        $('#createOrderForm').on('submit', function(e) {
            e.preventDefault();
            
            // Kiểm tra xem đã chọn khách hàng chưa
            if (!$('#user_id').val()) {
                showAlert('danger', 'Vui lòng chọn khách hàng!');
                $('#user_id').focus();
                return;
            }
            
            // Kiểm tra địa chỉ giao hàng
            if (!$('#address').val().trim()) {
                showAlert('danger', 'Vui lòng nhập địa chỉ giao hàng!');
                $('#address').focus();
                return;
            }
            
            // Kiểm tra phương thức thanh toán
            if (!$('#payment_method').val()) {
                showAlert('danger', 'Vui lòng chọn phương thức thanh toán!');
                $('#payment_method').focus();
                return;
            }
            
            // Kiểm tra xem có sản phẩm nào không
            if ($('.product-row').length === 0) {
                showAlert('danger', 'Vui lòng thêm ít nhất một sản phẩm!');
                $('#add-product-btn').focus();
                return;
            }
            
            // Kiểm tra số lượng hợp lệ
            let valid = true;
            let firstInvalidRow = null;
            
            $('.product-row').each(function() {
                const quantity = parseInt($(this).find('.quantity').val());
                const stock = parseInt($(this).find('.stock-value').text());
                
                if (quantity > stock) {
                    showAlert('danger', `Số lượng đặt không được vượt quá tồn kho!`);
                    valid = false;
                    if (!firstInvalidRow) {
                        firstInvalidRow = $(this).find('.quantity');
                    }
                    return false;
                }
                
                if (quantity < 1) {
                    showAlert('danger', `Số lượng phải lớn hơn 0!`);
                    valid = false;
                    if (!firstInvalidRow) {
                        firstInvalidRow = $(this).find('.quantity');
                    }
                    return false;
                }
            });
            
            if (!valid && firstInvalidRow) {
                firstInvalidRow.focus();
                return;
            }
            
            // Hiển thị loading
            const submitBtn = $('#submit-btn');
            const originalHtml = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...');
            
            // Thông báo cho screen reader
            announceToScreenReader('Đang xử lý tạo đơn hàng, vui lòng chờ');
            
            // Gửi form
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    showAlert('success', 'Tạo đơn hàng thành công!');
                    announceToScreenReader('Tạo đơn hàng thành công! Đang chuyển hướng...');
                    
                    setTimeout(function() {
                        window.location.href = "{{ route('admin.order.index') }}";
                    }, 1500);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'Vui lòng kiểm tra lại thông tin!';
                        if (errors && Object.keys(errors).length > 0) {
                            errorMessage = Object.values(errors)[0][0];
                        }
                        showAlert('danger', errorMessage);
                    } else {
                        showAlert('danger', 'Đã xảy ra lỗi! Vui lòng thử lại.');
                    }
                    
                    // Thông báo lỗi cho screen reader
                    announceToScreenReader('Có lỗi xảy ra khi tạo đơn hàng');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalHtml);
                }
            });
        });
        
        // Keyboard navigation improvements
        $(document).on('keydown', function(e) {
            // ESC để đóng modal
            if (e.key === 'Escape' && $('#productModal').hasClass('show')) {
                $('#productModal').modal('hide');
            }
        });
        
        // Focus trap trong modal
        $('#productModal').on('keydown', function(e) {
            if (e.key === 'Tab') {
                const focusableElements = $(this).find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                const firstElement = focusableElements.first();
                const lastElement = focusableElements.last();
                
                if (e.shiftKey) {
                    if ($(document.activeElement).is(firstElement)) {
                        e.preventDefault();
                        lastElement.focus();
                    }
                } else {
                    if ($(document.activeElement).is(lastElement)) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
        });
    });
</script>
@endsection