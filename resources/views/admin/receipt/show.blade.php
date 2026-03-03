@extends('admin.layouts.app')

@section('title', 'Chi tiết phiếu nhập')

@section('receipt-active', 'active')

@section('content')
  <div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.receipt.index') }}" class="text-decoration-none">Phiếu
            nhập</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chi tiết phiếu nhập #{{ $receipt->receipt_id }}</li>
      </ol>
    </nav>

    <!-- Thông báo -->
    <div id="alert-container"></div>

    <div class="row">
      <!-- Thông tin phiếu nhập -->
      <div class="col-md-8">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="fas fa-file-invoice me-2"></i>Thông tin phiếu nhập
            </h5>
            <div class="d-flex gap-2">
              @if($receipt->status == 'đang chờ')
                <button class="btn btn-sm btn-success" id="complete-receipt-btn">
                  <i class="fas fa-check me-1"></i> Xác nhận nhập
                </button>
                <button class="btn btn-sm btn-danger" id="cancel-receipt-btn">
                  <i class="fas fa-times me-1"></i> Hủy phiếu
                </button>
              @endif
              <a href="{{ route('admin.receipt.index') }}" class="btn btn-sm btn-light">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
              </a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold">Mã phiếu nhập:</label>
                  <p class="mb-0">
                    <span class="badge bg-secondary fs-6">#{{ $receipt->receipt_id }}</span>
                  </p>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">Nhà cung cấp:</label>
                  <p class="mb-0">
                    <a href="{{ route('admin.supplier.show', $receipt->supplier_id) }}" class="text-decoration-none">
                      <i class="fas fa-building me-1 text-primary"></i>
                      {{ $receipt->supplier->supplier_name ?? 'N/A' }}
                    </a>
                  </p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold">Ngày đặt hàng:</label>
                  <p class="mb-0">
                    <i class="fas fa-calendar me-1 text-info"></i>
                    {{ \Carbon\Carbon::parse($receipt->order_date)->format('d/m/Y') }}
                  </p>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">Trạng thái:</label>
                  <p class="mb-0">
                    @php
                      $statusClass = [
                        'đang chờ' => 'warning',
                        'đã nhận' => 'success',
                        'đã hủy' => 'danger'
                      ][$receipt->status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $statusClass }} fs-6 text-capitalize">
                      {{ $receipt->status }}
                    </span>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="card shadow-sm">
          <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">
              <i class="fas fa-boxes me-2"></i>Chi tiết sản phẩm
            </h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th width="5%">#</th>
                    <th width="35%">Sản phẩm</th>
                    <th width="15%" class="text-center">Số lượng</th>
                    <th width="15%" class="text-end">Đơn giá</th>
                    <th width="15%" class="text-end">Thành tiền</th>
                    <th width="15%" class="text-center">Tồn kho hiện tại</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($receipt->receiptDetails as $index => $detail)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>
                        <div class="d-flex align-items-center">
                          <img src="{{ asset('images/' . ($detail->product->image_url ?? "no image available.jpg")) }}"
                            alt="{{ $detail->product->product_name }}" class="img-thumbnail me-3"
                            style="width: 50px; height: 50px; object-fit: cover;">
                          <div>
                            <div class="fw-bold">{{ $detail->product->product_name ?? 'N/A' }}</div>
                            <small class="text-muted">Mã: {{ $detail->product_id }}</small>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <span class="badge bg-primary fs-6">{{ $detail->quantity }}</span>
                      </td>
                      <td class="text-end">{{ number_format($detail->price, 0, ',', '.') . "đ"}}</td>
                      <td class="text-end fw-bold text-success">
                        {{ number_format($detail->total_amount, 0, ',', '.') . "đ" }}
                      </td>
                      <td class="text-center">
                        <span class="badge bg-{{ ($detail->product->stock ?? 0) > 0 ? 'success' : 'warning' }} fs-6">
                          {{ $detail->product->stock ?? 0 }}
                        </span>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot class="table-secondary">
                  <tr>
                    <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                    <td class="text-end fw-bold fs-5 text-success">
                      {{ number_format($receipt->receiptDetails->sum('total_amount'), 0, ',', '.') . "đ" }}
                    </td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Thông tin bổ sung và thao tác -->
      <div class="col-md-4">
        <!-- Thống kê nhanh -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Thống kê</h6>
          </div>
          <div class="card-body">
            <div class="row text-center">
              <div class="col-6 mb-3">
                <div class="border rounded p-3 bg-light">
                  <h4 class="text-primary mb-1">{{ $receipt->receiptDetails->count() }}</h4>
                  <small class="text-muted">Số sản phẩm</small>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div class="border rounded p-3 bg-light">
                  <h4 class="text-success mb-1">{{ $receipt->quantity_product }}</h4>
                  <small class="text-muted">Tổng số lượng</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Lịch sử trạng thái -->
        {{-- <div class="card shadow-sm mb-4">
          <div class="card-header bg-warning text-dark">
            <h6 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử trạng thái</h6>
          </div>
          <div class="card-body">
            <div class="timeline">
              <div class="timeline-item {{ $receipt->status == 'đã hủy' ? 'text-danger' : 'text-success' }}">
                <div class="timeline-marker bg-success"></div>
                <div class="timeline-content">
                  <small class="text-muted">{{ \Carbon\Carbon::parse($receipt->order_date)->format('d/m/Y
                    H:i') }}</small>
                  <p class="mb-0">Tạo phiếu nhập</p>
                </div>
              </div>

              @if($receipt->status == 'đã nhận')
              <div class="timeline-item text-success">
                <div class="timeline-marker bg-success"></div>
                <div class="timeline-content">
                  <small class="text-muted">{{ \Carbon\Carbon::parse($receipt->updated_at)->format('d/m/Y
                    H:i') }}</small>
                  <p class="mb-0">Đã nhận hàng</p>
                </div>
              </div>
              @endif

              @if($receipt->status == 'đã hủy')
              <div class="timeline-item text-danger">
                <div class="timeline-marker bg-danger"></div>
                <div class="timeline-content">
                  <small class="text-muted">{{ \Carbon\Carbon::parse($receipt->updated_at)->format('d/m/Y
                    H:i') }}</small>
                  <p class="mb-0">Đã hủy phiếu</p>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div> --}}

        <!-- Thao tác nhanh -->
        <div class="card shadow-sm">
          <div class="card-header bg-dark text-white">
            <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Thao tác</h6>
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              {{-- {{ route('admin.receipt.edit', $receipt->receipt_id) }} --}}
              <a href="" class="btn btn-outline-primary btn-sm {{ $receipt->status != 'đang chờ' ? 'disabled' : '' }}">
                <i class="fas fa-edit me-1"></i> Chỉnh sửa
              </a>
              <button class="btn btn-outline-info btn-sm" id="print-receipt-btn">
                <i class="fas fa-print me-1"></i> In phiếu nhập
              </button>
              <button class="btn btn-outline-secondary btn-sm" id="export-pdf-btn">
                <i class="fas fa-file-pdf me-1"></i> Xuất PDF
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal xác nhận -->
  <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmModalTitle">Xác nhận</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="confirmModalBody">
          <!-- Nội dung sẽ được thay đổi bằng JavaScript -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-primary" id="confirmActionBtn">Xác nhận</button>
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

    .badge {
      font-size: 0.8em;
    }

    .timeline {
      position: relative;
      padding-left: 20px;
    }

    .timeline-item {
      position: relative;
      margin-bottom: 15px;
    }

    .timeline-marker {
      position: absolute;
      left: -20px;
      top: 5px;
      width: 12px;
      height: 12px;
      border-radius: 50%;
    }

    .timeline-content {
      margin-left: 10px;
    }

    .img-thumbnail {
      border-radius: 8px;
    }
  </style>
@endsection

@section('js')
  <script>
    $(document).ready(function () {
      // Xác nhận nhập hàng
      $('#complete-receipt-btn').on('click', function () {
        $('#confirmModalTitle').text('Xác nhận nhập hàng');
        $('#confirmModalBody').html(`
                      <p>Bạn có chắc chắn muốn xác nhận đã nhận hàng cho phiếu nhập này?</p>
                      <p class="text-success"><i class="fas fa-info-circle me-1"></i>Số lượng hàng sẽ được cập nhật vào tồn kho.</p>
                  `);
        $('#confirmActionBtn')
          .removeClass('btn-danger')
          .addClass('btn-success')
          .text('Xác nhận nhập hàng');

        $('#confirmModal').modal('show');

        // Xử lý xác nhận
        $('#confirmActionBtn').off('click').on('click', function () {
          updateReceiptStatus('đã nhận');
        });
      });

      // Hủy phiếu nhập
      $('#cancel-receipt-btn').on('click', function () {
        $('#confirmModalTitle').text('Hủy phiếu nhập');
        $('#confirmModalBody').html(`
                      <p>Bạn có chắc chắn muốn hủy phiếu nhập này?</p>
                      <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Hành động này không thể hoàn tác.</p>
                  `);
        $('#confirmActionBtn')
          .removeClass('btn-success')
          .addClass('btn-danger')
          .text('Hủy phiếu nhập');

        $('#confirmModal').modal('show');

        // Xử lý xác nhận
        $('#confirmActionBtn').off('click').on('click', function () {
          updateReceiptStatus('đã hủy');
        });
      });

      // In phiếu nhập
      $('#print-receipt-btn').on('click', function () {
        window.print();
      });

      // Xuất PDF
      $('#export-pdf-btn').on('click', function () {
        showAlert('Tính năng xuất PDF đang được phát triển!', 'info');
      });

      // Hàm cập nhật trạng thái phiếu nhập
      function updateReceiptStatus(status) {
        const btn = $('#confirmActionBtn');
        const originalText = btn.html();

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...');

        $.ajax({
          url: "{{ route('admin.receipt.update-status', $receipt->receipt_id) }}",
          method: 'PATCH',
          data: {
            _token: "{{ csrf_token() }}",
            status: status
          },
          success: function (response) {
            $('#confirmModal').modal('hide');
            showAlert(`Cập nhật trạng thái thành công! Phiếu nhập đã được ${status}.`, 'success');
            setTimeout(function () {
              location.reload();
            }, 1500);
          },
          error: function (xhr) {
            $('#confirmModal').modal('hide');
            if (xhr.status === 422) {
              showAlert('Dữ liệu không hợp lệ!', 'danger');
            } else {
              showAlert('Đã xảy ra lỗi! Vui lòng thử lại.', 'danger');
            }
          },
          complete: function () {
            btn.prop('disabled', false).html(originalText);
          }
        });
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

      // Ẩn các nút thao tác khi in
      window.addEventListener('beforeprint', function () {
        $('.btn, .breadcrumb, .card-header .d-flex').hide();
      });

      window.addEventListener('afterprint', function () {
        $('.btn, .breadcrumb, .card-header .d-flex').show();
      });
    });
  </script>
@endsection