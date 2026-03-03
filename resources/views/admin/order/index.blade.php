@extends('admin.layouts.app')

@section('title', 'Quản lý Đơn hàng')
@section('order-active', 'active')

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Quản lý Đơn hàng</h1>
      <div class="d-flex">
        <a href="{{ route('admin.order.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
          <i class="fas fa-plus fa-sm text-white-50"></i> Tạo đơn hàng mới
        </a>
        {{-- <button class="btn btn-secondary btn-sm" id="printOrders">
          <i class="fas fa-print fa-sm me-1"></i> In
        </button> --}}
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Tổng đơn hàng
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders ?? 0 }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Chờ xác nhận
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrders ?? 0 }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clock fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  Đang giao
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $shippingOrders ?? 0 }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-shipping-fast fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  Đã hủy
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelledOrders ?? 0 }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                  Doanh thu tháng
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}đ
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                  Hoàn thành
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedOrders ?? 0 }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Bộ lọc & Tìm kiếm</h6>
        <span class="badge bg-info">Tổng: {{ $orders->total() }} đơn hàng</span>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.order.index') }}" method="GET" class="row g-3">
          <div class="col-md-2">
            <label for="search" class="form-label">Mã đơn hàng</label>
            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}"
              placeholder="Mã đơn hàng...">
          </div>
          <div class="col-md-2">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-control" id="status" name="status">
              <option value="">Tất cả trạng thái</option>
              <option value="chờ xác nhận" {{ request('status') == 'chờ xác nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
              <option value="đã xác nhận" {{ request('status') == 'đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
              <option value="đang giao" {{ request('status') == 'đang giao' ? 'selected' : '' }}>Đang giao</option>
              <option value="đã nhận hàng" {{ request('status') == 'đã nhận hàng' ? 'selected' : '' }}>Đã nhận hàng</option>
              <option value="đã hủy" {{ request('status') == 'đã hủy' ? 'selected' : '' }}>Đã hủy</option>
            </select>
          </div>
          <div class="col-md-2">
            <label for="payment_method" class="form-label">Phương thức TT</label>
            <select class="form-control" id="payment_method" name="payment_method">
              <option value="">Tất cả</option>
              <option value="chuyển khoản" {{ request('payment_method') == 'chuyển khoản' ? 'selected' : '' }}>Chuyển khoản
              </option>
              <option value="tiền mặt" {{ request('payment_method') == 'tiền mặt' ? 'selected' : '' }}>Tiền mặt</option>
            </select>
          </div>
          <div class="col-md-2">
            <label for="start_date" class="form-label">Từ ngày</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
          </div>
          <div class="col-md-2">
            <label for="end_date" class="form-label">Đến ngày</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2 w-100">
              <i class="fas fa-filter"></i> Lọc
            </button>
          </div>
        </form>
        <div class="mt-3">
          <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-redo"></i> Reset bộ lọc
          </a>
        </div>
      </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Danh sách đơn hàng</h6>
        <div class="dropdown">
          <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fas fa-cog"></i> Tùy chọn
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" id="bulkUpdate"><i class="fas fa-sync"></i> Cập nhật hàng loạt</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf"></i> Xuất PDF</a></li>
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="ordersTable" width="100%" cellspacing="0">
            <thead class="table-dark">
              <tr>
                <th width="50">Mã đơn</th>
                <th width="130">Khách hàng</th>
                <th>Địa chỉ giao</th>
                {{-- <th width="100">Ngày đặt</th>
                <th width="100">Ngày giao</th> --}}
                <th width="120">Tổng tiền</th>
                <th width="100">Trạng thái</th>
                <th width="100">Thanh toán</th>
                <th width="160" class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $order)
                <tr data-order-id="{{ $order->order_id }}">
                  <td class="text-center">
                    <strong class="text-primary">#{{ $order->order_id }}</strong>
                    <br>
                  </td>
                  <td>
                    <div class="fw-bold text-dark">{{ $order->user->username ?? 'N/A' }}</div>
                    <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                  </td>
                  <td>
                    <div class="text-truncate" style="max-width: 360px;" title="{{ $order->address }}">
                      {{ $order->address }}
                    </div>
                  </td>
                  {{-- <td class="text-center">
                    <div class="text-dark">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</div>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($order->order_date)->format('H:i') }}</small>
                  </td> --}}
                  {{-- <td class="text-center">
                    @if($order->delivery_date)
                    <div class="text-dark">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</div>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($order->delivery_date)->format('H:i') }}</small>
                    @else
                    <span class="text-muted">Chưa giao</span>
                    @endif
                  </td> --}}
                  <td class="text-end">
                    <strong class="text-success">{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong>
                  </td>
                  <td class="text-center">
                    @if($order->status == 'chờ xác nhận')
                      <span class="badge bg-warning text-dark">
                        <i class="fas fa-clock me-1"></i>Chờ xác nhận
                      </span>
                    @elseif($order->status == 'đã xác nhận')
                      <span class="badge bg-info">
                        <i class="fas fa-check me-1"></i>Đã xác nhận
                      </span>
                    @elseif($order->status == 'đang giao')
                      <span class="badge bg-primary">
                        <i class="fas fa-shipping-fast me-1"></i>Đang giao
                      </span>
                    @elseif($order->status == 'đã nhận hàng')
                      <span class="badge bg-success">
                        <i class="fas fa-box me-1"></i>Đã nhận hàng
                      </span>
                    @else
                      <span class="badge bg-danger">
                        <i class="fas fa-times me-1"></i>Đã hủy
                      </span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($order->payment_method == 'chuyển khoản')
                      <span class="badge bg-success">
                        <i class="fas fa-university me-1"></i>Chuyển khoản
                      </span>
                    @else
                      <span class="badge bg-secondary">
                        <i class="fas fa-money-bill me-1"></i>Tiền mặt
                      </span>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="{{ route('admin.order.show', $order->order_id) }}" class="btn btn-info" title="Xem chi tiết"
                        data-bs-toggle="tooltip">
                        <i class="fas fa-eye"></i>
                      </a>
                    </div>
                    @if($order->status != 'đã hủy')
                      <button type="button" class="btn btn-danger cancel-order" title="Hủy đơn hàng" data-bs-toggle="tooltip"
                        data-order-id="{{ $order->order_id }}" data-order-code="#{{ $order->order_id }}">
                        <i class="fas fa-times"></i>
                      </button>
                    @endif
                    @if($order->status == 'đã xác nhận')
                      <button type="button" class="btn btn-success delivery-order" title="Giao đơn hàng"
                        data-bs-toggle="tooltip" data-order-id="{{ $order->order_id }}"
                        data-order-code="#{{ $order->order_id }}">
                        <i class="fa-solid fa-check"></i>
                      </button>
                    @endif
                    @if($order->status == 'chờ xác nhận')
                      <button type="button" class="btn btn-warning confirm-order" title="Xác nhận đơn"
                        data-bs-toggle="tooltip" data-order-id="{{ $order->order_id }}"
                        data-order-code="#{{ $order->order_id }}">
                        <i class="fa-solid fa-check"></i>
                      </button>
                    @endif
                    @if($order->status == 'đang giao' && $order->created_by == 'admin')
                      <form action="{{ route('admin.order.update-status', $order->order_id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="text" name='status' value="đã nhận hàng" hidden>
                        <button type="submit" class="btn btn-success" title="Đã giao" data-bs-toggle="tooltip">
                          <i class="fa-solid fa-cart-flatbed"></i>
                        </button>
                      </form>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center py-5">
                    <div class="text-muted">
                      <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                      <h5>Không có đơn hàng nào</h5>
                      <p>Chưa có đơn hàng nào được tạo</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
          <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
              Hiển thị {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} của {{ $orders->total() }} đơn hàng
            </div>
            <nav>
              {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
          </div>
        @endif
        {{-- {{ $orders->links('pagination::bootstrap-4') }} --}}
      </div>
    </div>
  </div>

  <!-- Quick Status Update Modal -->
  <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Cập nhật trạng thái đơn hàng</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="statusForm">
            <input type="hidden" id="orderId" name="order_id">
            <div class="mb-3">
              <label for="newStatus" class="form-label">Trạng thái mới</label>
              <select class="form-control" id="newStatus" name="status" required>
                <option value="chờ xác nhận">Chờ xác nhận</option>
                <option value="đã xác nhận">Đã xác nhận</option>
                <option value="đang giao">Đang giao</option>
                <option value="đã nhận hàng">Đã nhận hàng</option>
                <option value="đã hủy">Đã hủy</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="statusNote" class="form-label">Ghi chú (tùy chọn)</label>
              <textarea class="form-control" id="statusNote" name="note" rows="3"
                placeholder="Nhập ghi chú về trạng thái đơn hàng..."></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-primary" id="saveStatus">Cập nhật</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('css')
  <style>
    .table th {
      font-weight: 600;
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .table td {
      vertical-align: middle;
    }

    .btn-group .btn {
      border-radius: 4px !important;
      margin: 0 1px;
      padding: 0.375rem 0.5rem;
    }

    .badge {
      font-size: 0.75rem;
      font-weight: 500;
      padding: 0.4em 0.6em;
    }

    .card-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .card-header h6 {
      margin: 0;
    }

    /* Border colors for stats cards */
    .border-left-primary {
      border-left: 0.25rem solid #4e73df !important;
    }

    .border-left-success {
      border-left: 0.25rem solid #1cc88a !important;
    }

    .border-left-info {
      border-left: 0.25rem solid #36b9cc !important;
    }

    .border-left-warning {
      border-left: 0.25rem solid #f6c23e !important;
    }

    .border-left-danger {
      border-left: 0.25rem solid #e74a3b !important;
    }

    .border-left-dark {
      border-left: 0.25rem solid #5a5c69 !important;
    }

    /* Pagination styling */
    .pagination .page-link {
      color: #6c757d;
      border: 1px solid #dee2e6;
    }

    .pagination .page-item.active .page-link {
      background-color: #4e73df;
      border-color: #4e73df;
    }

    .pagination .page-link:hover {
      color: #4e73df;
      background-color: #eaecf4;
    }

    /* Hover effects */
    #ordersTable tbody tr {
      transition: all 0.3s ease;
    }

    #ordersTable tbody tr:hover {
      background-color: #f8f9fa;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Status badges */
    .badge.bg-warning {
      background-color: #f6c23e !important;
      color: #212529 !important;
    }

    .badge.bg-info {
      background-color: #36b9cc !important;
    }

    .badge.bg-primary {
      background-color: #4e73df !important;
    }

    .badge.bg-success {
      background-color: #1cc88a !important;
    }

    .badge.bg-danger {
      background-color: #e74a3b !important;
    }

    .badge.bg-secondary {
      background-color: #858796 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .table-responsive {
        font-size: 0.875rem;
      }

      .btn-group .btn {
        padding: 0.25rem 0.4rem;
        font-size: 0.7rem;
      }

      .card-body {
        padding: 1rem;
      }

      .stats-card .card-body {
        padding: 0.75rem;
      }

      .stats-card .h5 {
        font-size: 1.1rem;
      }
    }

    /* Alert styles */
    .alert {
      border: none;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .alert-success {
      background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
      color: #155724;
      border-left: 4px solid #28a745;
    }

    .alert-danger {
      background: linear-gradient(135deg, #f8d7da 0%, #f1b0b7 100%);
      color: #721c24;
      border-left: 4px solid #dc3545;
    }

    .alert i {
      font-size: 1.1em;
    }

    /* Loading states */
    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    /* Print styles */
    @media print {

      .card-header,
      .btn,
      .dropdown,
      .d-sm-flex .btn {
        display: none !important;
      }

      .table {
        font-size: 12px;
      }

      .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
        background: none !important;
      }
    }
  </style>
@endsection

@section('js')
  <script>
    $(document).ready(function () {
      // Hàm hủy đơn hàng với jQuery
      $('.cancel-order').on('click', function () {
        console.log('cancel-order');
        const orderId = $(this).data('order-id');
        const orderCode = $(this).data('order-code');

        // Hiển thị confirm dialog với lý do hủy
        if (!confirm(`Bạn có chắc muốn hủy đơn hàng ${orderCode}`)) { return; }

        // Hiển thị loading indicator
        const $button = $(this);
        const originalHtml = $button.html();
        $button.html('<i class="fas fa-spinner fa-spin"></i>');
        $button.prop('disabled', true);

        // Gọi API hủy đơn hàng
        $.ajax({
          url: '{{ route("admin.order.cancel", ["orderId" => ":orderId"]) }}'.replace(':orderId', orderId),
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
          },
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              // Hiển thị thông báo thành công
              showAlert('success', response.message);

              // Cập nhật giao diện sau 1.5 giây
              setTimeout(function () {
                location.reload();
              }, 1500);

            } else {
              // Hiển thị thông báo lỗi
              showAlert('error', response.message);
              $button.html(originalHtml);
              $button.prop('disabled', false);
            }
          },
          error: function (xhr, status, error) {
            // Hiển thị thông báo lỗi
            let errorMessage = 'Có lỗi xảy ra khi hủy đơn hàng!';

            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }

            showAlert('error', errorMessage);
            $button.html(originalHtml);
            $button.prop('disabled', false);
          }
        });
      });

      // Hàm xác nhận đơn hàng
      $('.confirm-order').on('click', function () {
        console.log('confirm-order');
        const orderId = $(this).data('order-id');
        const orderCode = $(this).data('order-code');

        // Hiển thị confirm dialog với lý do hủy
        if (!confirm(`Xác nhận đơn hàng ${orderCode}`)) { return; }

        // Hiển thị loading indicator
        const $button = $(this);
        const originalHtml = $button.html();
        $button.html('<i class="fas fa-spinner fa-spin"></i>');
        $button.prop('disabled', true);

        $.ajax({
          url: '{{ route("admin.order.confirm", ["orderId" => ":orderId"]) }}'.replace(':orderId', orderId),
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
          },
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              // Hiển thị thông báo thành công
              showAlert('success', response.message);

              // Cập nhật giao diện sau 1.5 giây
              setTimeout(function () {
                location.reload();
              }, 1500);

            } else {
              // Hiển thị thông báo lỗi
              showAlert('error', response.message);
              $button.html(originalHtml);
              $button.prop('disabled', false);
            }
          },
          error: function (xhr, status, error) {
            // Hiển thị thông báo lỗi
            let errorMessage = 'Có lỗi xảy ra khi xác nhận đơn hàng!';

            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }

            showAlert('error', errorMessage);
            $button.html(originalHtml);
            $button.prop('disabled', false);
          }
        });
      })

      // Hàm xác nhận đang giao đơn hàng
      $('.delivery-order').on('click', function () {
        const orderId = $(this).data('order-id');
        const orderCode = $(this).data('order-code');

        // Hiển thị confirm dialog với lý do hủy
        if (!confirm(`Xác nhận đang giao đơn hàng ${orderCode}`)) { return; }

        // Hiển thị loading indicator
        const $button = $(this);
        const originalHtml = $button.html();
        $button.html('<i class="fas fa-spinner fa-spin"></i>');
        $button.prop('disabled', true);

        $.ajax({
          url: '{{ route("admin.order.delivery", ["orderId" => ":orderId"]) }}'.replace(':orderId', orderId),
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
          },
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              // Hiển thị thông báo thành công
              showAlert('success', response.message);

              // Cập nhật giao diện sau 1.5 giây
              setTimeout(function () {
                location.reload();
              }, 1500);

            } else {
              // Hiển thị thông báo lỗi
              showAlert('error', response.message);
              $button.html(originalHtml);
              $button.prop('disabled', false);
            }
          },
          error: function (xhr, status, error) {
            // Hiển thị thông báo lỗi
            let errorMessage = 'Có lỗi xảy ra khi xác nhận đang giao đơn hàng!';

            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }

            showAlert('error', errorMessage);
            $button.html(originalHtml);
            $button.prop('disabled', false);
          }
        });
      })

      // Hàm cập nhật trạng thái nhanh với modal
      $('.update-status').on('click', function () {
        const orderId = $(this).data('order-id');
        const currentStatus = $(this).data('current-status');

        // Điền thông tin vào modal
        $('#orderId').val(orderId);
        $('#newStatus').val(currentStatus);
        $('#statusNote').val('');

        // Hiển thị modal
        $('#statusModal').modal('show');
      });

      // Lưu trạng thái mới
      $('#saveStatus').on('click', function () {
        const $button = $(this);
        const originalHtml = $button.html();

        // Hiển thị loading
        $button.html('<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...');
        $button.prop('disabled', true);

        const formData = {
          order_id: $('#orderId').val(),
          status: $('#newStatus').val(),
          note: $('#statusNote').val(),
          _token: '{{ csrf_token() }}',
          _method: 'PATCH'
        };

        $.ajax({
          url: `/admin/orders/${formData.order_id}/status`,
          method: 'POST', // Sử dụng POST với _method PATCH
          data: formData,
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              // Đóng modal
              $('#statusModal').modal('hide');

              // Hiển thị thông báo thành công
              showAlert('success', response.message);

              // Cập nhật giao diện sau 1.5 giây
              setTimeout(function () {
                location.reload();
              }, 1500);

            } else {
              showAlert('error', response.message);
              $button.html(originalHtml);
              $button.prop('disabled', false);
            }
          },
          error: function (xhr, status, error) {
            let errorMessage = 'Có lỗi xảy ra khi cập nhật trạng thái!';

            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }

            showAlert('error', errorMessage);
            $button.html(originalHtml);
            $button.prop('disabled', false);
          }
        });
      });

      // Auto submit filter form khi select thay đổi
      $('#status, #payment_method').on('change', function () {
        $(this).closest('form').submit();
      });

      // Search với debounce
      let searchTimeout;
      $('#search').on('input', function () {
        clearTimeout(searchTimeout);
        const $form = $(this).closest('form');

        searchTimeout = setTimeout(function () {
          $form.submit();
        }, 500);
      });

      // Export orders
      $('#exportOrders').on('click', function (e) {
        e.preventDefault();

        // Hiển thị loading
        const $button = $(this);
        const originalHtml = $button.html();
        $button.html('<i class="fas fa-spinner fa-spin"></i> Đang xuất...');

        // Lấy params hiện tại
        const params = new URLSearchParams(window.location.search);

        // Tạo iframe để download file
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = `/admin/orders/export?${params.toString()}`;
        document.body.appendChild(iframe);

        // Khôi phục button sau 2 giây
        setTimeout(function () {
          $button.html(originalHtml);
          document.body.removeChild(iframe);
        }, 2000);
      });

      // Print orders
      $('#printOrders').on('click', function (e) {
        e.preventDefault();
        window.print();
      });

      // Validate date range
      $('#start_date, #end_date').on('change', function () {
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();

        if (startDate && endDate && startDate > endDate) {
          if ($(this).attr('id') === 'start_date') {
            $('#end_date').val(startDate);
          } else {
            $('#start_date').val(endDate);
          }
        }
      });

      // Tooltip initialization
      $('[data-bs-toggle="tooltip"]').tooltip();

      // Table row hover effects
      $('#ordersTable tbody tr').hover(
        function () {
          $(this).css({
            'transform': 'translateY(-2px)',
            'box-shadow': '0 4px 12px rgba(0,0,0,0.1)',
            'background-color': '#f8f9fa'
          });
        },
        function () {
          $(this).css({
            'transform': '',
            'box-shadow': '',
            'background-color': ''
          });
        }
      );
    });
  </script>
@endsection