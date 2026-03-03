@extends('admin.layouts.app')

@section('title', 'Tạo phiếu nhập')
@section('receipt-active', 'active')

@section('content')
  <div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.receipt.index') }}" class="text-decoration-none">Phiếu
            nhập</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo phiếu nhập</li>
      </ol>
    </nav>

    <!-- Thông báo -->
    <div id="alert-container"></div>

    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tạo phiếu nhập mới</h5>
      </div>
      <div class="card-body">
        {{-- {{ route('admin.receipt.store') }} --}}
        <form id="createReceiptForm" action="{{ route('admin.receipt.store') }}" method="POST">
          @csrf

          <!-- Thông tin cơ bản -->
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="supplier_id" class="form-label">Nhà cung cấp <span class="text-danger">*</span></label>
                <select class="form-select" id="supplier_id" name="supplier_id" required>
                  <option value="">-- Chọn nhà cung cấp --</option>
                  @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->supplier_id }}">{{ $supplier->supplier_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            {{-- <div class="col-md-6">
              <div class="mb-3">
                <label for="order_date" class="form-label">Ngày đặt hàng <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="order_date" name="order_date" value="{{ date('Y-m-d') }}"
                  required>
              </div>
            </div> --}}
          </div>

          <!-- Thêm sản phẩm -->
          <div class="row mb-3">
            <div class="col-12">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Danh sách sản phẩm</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-product-btn">
                  <i class="fas fa-plus me-1"></i> Thêm sản phẩm
                </button>
              </div>
            </div>
          </div>

          <!-- Bảng sản phẩm -->
          <div class="table-responsive">
            <table class="table table-bordered" id="products-table">
              <thead class="table-light">
                <tr>
                  <th width="30%">Sản phẩm</th>
                  <th width="15%">Giá nhập</th>
                  <th width="15%">Giá bán</th>
                  <th width="15%">Số lượng</th>
                  {{-- <th width="15%">Tồn kho</th> --}}
                  <th width="10%">Tổng tiền</th>
                  <th width="5%"></th>
                </tr>
              </thead>
              <tbody id="products-tbody">
                <!-- Sản phẩm sẽ được thêm vào đây bằng JavaScript -->
              </tbody>
              <tfoot class="table-secondary">
                <tr>
                  <td colspan="5" class="text-end fw-bold">Tổng cộng:</td>
                  <td class="fw-bold" id="total-amount">0</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>

          <!-- Ghi chú -->
          {{-- <div class="mb-4">
            <label for="notes" class="form-label">Ghi chú</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"
              placeholder="Nhập ghi chú (nếu có)"></textarea>
          </div> --}}

          <!-- Nút submit -->
          <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.receipt.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary" id="submit-btn">
              <i class="fas fa-save me-1"></i> Tạo phiếu nhập
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal chọn sản phẩm -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Chọn sản phẩm</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead class="table-dark">
                <tr>
                  <th>Hình ảnh</th>
                  <th>Tên sản phẩm</th>
                  <th>Giá bán</th>
                  <th>Tồn kho</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach($products as $product)
                  <tr>
                    <td>
                      <img src="{{ asset('images/' . ($product->image_url ?? "no image available.jpg")) }}"
                        alt="{{ $product->product_name }}" class="img-thumbnail"
                        style="width: 50px; height: 50px; object-fit: cover;">
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                      <button type="button" class="btn btn-sm btn-success select-product"
                        data-product-id="{{ $product->product_id }}" data-product-name="{{ $product->product_name }}"
                        data-price="{{ $product->price }}" data-stock="{{ $product->stock }}"
                        data-image="{{ asset('images/' . $product->image_url ?? '')}}">
                        <i class="fas fa-plus"></i> Chọn
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
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
    }

    .remove-product:hover {
      color: #bb2d3b;
    }

    .price-info {
      font-size: 0.85rem;
      color: #6c757d;
    }

    .stock-info {
      font-size: 0.85rem;
      color: #198754;
    }
  </style>
@endsection

@section('js')
  <script>
    $(document).ready(function () {
      let productCounter = 0;
      const selectedProducts = new Set(); // Theo dõi sản phẩm đã chọn

      // Mở modal chọn sản phẩm
      $('#add-product-btn').on('click', function () {
        $('#productModal').modal('show');
      });

      // Chọn sản phẩm từ modal
      $('.select-product').on('click', function () {
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

        // Thêm sản phẩm vào bảng
        addProductRow(productId, productName, price, stock, image);

        // Đóng modal
        $('#productModal').modal('hide');
      });

      // Hàm thêm sản phẩm vào bảng
      function addProductRow(productId, productName, price, stock, image) {
        productCounter++;
        const rowId = `product-${productCounter}`;

        const imageHtml = image ?
          `<img src="${image}" alt="${productName}" class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">` :
          `<div class="bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-image text-muted"></i>
                        </div>`;

        const rowHtml = `
                        <tr id="${rowId}" class="product-row">
                            <td>
                                <div class="d-flex align-items-center">
                                    ${imageHtml}
                                    <div class="ms-2">
                                        <div class="fw-bold">${productName}</div>
                                        <input type="hidden" name="products[${productCounter}][product_id]" value="${productId}">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm import-price" 
                                       name="products[${productCounter}][import_price]" 
                                       value="${price}" min="0" step="1000" required>
                                <div class="price-info mt-1">Giá bán: ${formatCurrency(price)}</div>
                            </td>
                            <td class="text-end">${formatCurrency(price)}</td>
                            <td>
                                <input type="number" class="form-control form-control-sm quantity" 
                                       name="products[${productCounter}][quantity]" 
                                       value="1" min="1" required>
                                <div class="stock-info mt-1">Tồn kho: ${stock}</div>
                            </td>
                            <td class="text-end product-total">${formatCurrency(price)}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm remove-product" data-product-id="${productId}">
                                    <i class="fas fa-times text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    `;

        $('#products-tbody').append(rowHtml);
        selectedProducts.add(productId);
        updateTotalAmount();

        // Thêm sự kiện cho các input
        $(`#${rowId} .import-price, #${rowId} .quantity`).on('input', function () {
          updateProductTotal(rowId);
          updateTotalAmount();
        });

        // Sự kiện xóa sản phẩm
        $(`#${rowId} .remove-product`).on('click', function () {
          removeProduct(rowId, productId);
        });
      }

      // Hàm cập nhật tổng tiền cho một sản phẩm
      function updateProductTotal(rowId) {
        const row = $(`#${rowId}`);
        const importPrice = parseFloat(row.find('.import-price').val()) || 0;
        const quantity = parseInt(row.find('.quantity').val()) || 0;
        const total = importPrice * quantity;

        row.find('.product-total').text(formatCurrency(total));
      }

      // Hàm cập nhật tổng tiền toàn bộ phiếu nhập
      function updateTotalAmount() {
        let total = 0;
        $('.product-row').each(function () {
          const importPrice = parseFloat($(this).find('.import-price').val()) || 0;
          const quantity = parseInt($(this).find('.quantity').val()) || 0;
          total += importPrice * quantity;
        });

        $('#total-amount').text(formatCurrency(total));
      }

      // Hàm xóa sản phẩm
      function removeProduct(rowId, productId) {
        $(`#${rowId}`).remove();
        selectedProducts.delete(productId);
        updateTotalAmount();
      }

      // Hàm định dạng tiền tệ
      function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
          style: 'currency',
          currency: 'VND'
        }).format(amount);
      }

      // Hàm hiển thị thông báo
      function showAlert(message, type) {
        const alert = $(`
                        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
        $('#alert-container').html(alert);

        setTimeout(function () {
          alert.alert('close');
        }, 5000);
      }

      // Xử lý submit form
      $('#createReceiptForm').on('submit', function (e) {
        e.preventDefault();

        // Kiểm tra xem đã chọn nhà cung cấp chưa
        if (!$('#supplier_id').val()) {
          showAlert('Vui lòng chọn nhà cung cấp!', 'danger');
          return;
        }

        // Kiểm tra xem có sản phẩm nào không
        if ($('.product-row').length === 0) {
          showAlert('Vui lòng thêm ít nhất một sản phẩm!', 'danger');
          return;
        }

        // Kiểm tra số lượng hợp lệ
        let valid = true;
        // $('.product-row').each(function() {
        //     const quantity = parseInt($(this).find('.quantity').val());
        //     const stock = parseInt($(this).find('.stock-info').text().replace('Tồn kho: ', ''));

        //     if (quantity > stock) {
        //         showAlert(`Số lượng nhập không được vượt quá tồn kho (${stock})!`, 'danger');
        //         valid = false;
        //         return false;
        //     }
        // });

        if (!valid) return;

        // Hiển thị loading
        const submitBtn = $('#submit-btn');
        const originalHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...');

        // Gửi form
        $.ajax({
          url: $(this).attr('action'),
          method: 'POST',
          data: $(this).serialize(),
          success: function (response) {
            showAlert('Tạo phiếu nhập thành công!', 'success');
            setTimeout(function () {
              window.location.href = "{{ route('admin.receipt.index') }}";
            }, 1500);
          },
          error: function (xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              let errorMessage = 'Vui lòng kiểm tra lại thông tin!';
              if (errors && Object.keys(errors).length > 0) {
                errorMessage = Object.values(errors)[0][0];
              }
              showAlert(errorMessage, 'danger');
            } else {
              showAlert('Đã xảy ra lỗi! Vui lòng thử lại.', 'danger');
            }
          },
          complete: function () {
            submitBtn.prop('disabled', false).html(originalHtml);
          }
        });
      });
    });
  </script>
@endsection