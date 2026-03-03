@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_id)
@section('order-active', 'active')

@section('content')
  <div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.order.index') }}" class="text-decoration-none">Quản lý đơn
            hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page">Đơn hàng #{{ $order->order_id }}</li>
      </ol>
    </nav>

    <!-- Thông báo -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="row">
      <!-- Thông tin chính -->
      <div class="col-md-8">
        <!-- Thông tin đơn hàng -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
              <i class="fas fa-shopping-cart me-2"></i>Thông tin đơn hàng #{{ $order->order_id }}
            </h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr>
                    <td width="40%"><strong>Mã đơn hàng:</strong></td>
                    <td>
                      <span class="badge bg-secondary">#{{ $order->order_id }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Ngày đặt hàng:</strong></td>
                    <td>
                      <i class="fas fa-calendar me-1 text-info"></i>
                      {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Ngày giao hàng:</strong></td>
                    <td>
                      @if($order->delivery_date)
                        <i class="fas fa-truck me-1 text-success"></i>
                        {{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}
                      @else
                        <span class="text-muted">Chưa xác định</span>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Phương thức thanh toán:</strong></td>
                    <td>
                      @if($order->payment_method == 'chuyển khoản')
                        <i class="fas fa-university me-1 text-primary"></i>
                      @else
                        <i class="fas fa-money-bill-wave me-1 text-success"></i>
                      @endif
                      {{ $order->payment_method }}
                    </td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr>
                    <td width="40%"><strong>Trạng thái:</strong></td>
                    <td>
                      @php
                        $statusClass = [
                          'chờ xác nhận' => 'warning',
                          'đã xác nhận' => 'info',
                          'đang giao' => 'primary',
                          'đã nhận hàng' => 'success',
                          'đã hủy' => 'danger'
                        ][$order->status] ?? 'secondary';
                      @endphp
                      <span class="badge bg-{{ $statusClass }} fs-6">{{ $order->status }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Tổng tiền:</strong></td>
                    <td class="text-success fw-bold fs-5">
                      {{ number_format($order->total_amount, 0, ',', '.') . 'đ' }}
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Người tạo:</strong></td>
                    <td>
                      <span class="badge bg-{{ $order->created_by == 'admin' ? 'danger' : 'primary' }}">
                        {{ $order->created_by == 'admin' ? 'Nhân viên' : 'Khách hàng' }}
                      </span>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="card shadow-sm mb-4">
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
                    <th width="45%">Sản phẩm</th>
                    <th width="15%" class="text-center">Số lượng</th>
                    <th width="15%" class="text-end">Đơn giá</th>
                    <th width="20%" class="text-end">Thành tiền</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->orderDetails as $index => $detail)
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
                      <td class="text-end">{{ number_format($detail->price, 0, ',', '.') . 'đ' }}</td>
                      <td class="text-end fw-bold text-success">
                        {{ number_format($detail->quantity * $detail->price, 0, ',', '.') . 'đ' }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot class="table-secondary">
                  <tr>
                    <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                    <td class="text-end fw-bold fs-5 text-success">
                      {{ number_format($order->total_amount, 0, ',', '.') . "đ" }}
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Thông tin bổ sung và thao tác -->
      <div class="col-md-4">
        <!-- Thông tin khách hàng -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin khách hàng</h6>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <strong>Tên khách hàng:</strong>
              <p class="mb-0 mt-1">{{ $order->user->username ?? 'N/A' }}</p>
            </div>
            <div class="mb-3">
              <strong>Email:</strong>
              <p class="mb-0 mt-1">
                <a href="mailto:{{ $order->user->email ?? '' }}" class="text-decoration-none">
                  <i class="fas fa-envelope me-1 text-primary"></i>
                  {{ $order->user->email ?? 'N/A' }}
                </a>
              </p>
            </div>
            <div class="mb-3">
              <strong>Số điện thoại:</strong>
              <p class="mb-0 mt-1">
                @if($order->user->phone_number ?? false)
                  <a href="tel:{{ $order->user->phone_number }}" class="text-decoration-none">
                    <i class="fas fa-phone me-1 text-success"></i>
                    {{ $order->user->phone_number }}
                  </a>
                @else
                  <span class="text-muted">Chưa cập nhật</span>
                @endif
              </p>
            </div>
            <div>
              <strong>Địa chỉ giao hàng:</strong>
              <p class="mb-0 mt-1">
                <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                {{ $order->address }}
              </p>
            </div>
          </div>
        </div>

        <!-- Thao tác đơn hàng -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-warning text-dark">
            <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Thao tác đơn hàng</h6>
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              @if($order->status == 'chờ xác nhận')
                <form action="{{ route('admin.order.update-status', $order->order_id) }}" method="POST" class="d-grid">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="đã xác nhận">
                  <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-check me-1"></i> Xác nhận đơn hàng
                  </button>
                </form>

                <form action="{{ route('admin.order.update-status', $order->order_id) }}" method="POST" class="d-grid">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="đã hủy">
                  <button type="submit" class="btn btn-danger btn-sm"
                    onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                    <i class="fas fa-times me-1"></i> Hủy đơn hàng
                  </button>
                </form>
              @endif

              @if($order->status == 'đã xác nhận')
                <form action="{{ route('admin.order.update-status', $order->order_id) }}" method="POST" class="d-grid">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="đang giao">
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-truck me-1"></i> Bắt đầu giao hàng
                  </button>
                </form>

                <form action="{{ route('admin.order.update-status', $order->order_id) }}" method="POST" class="d-grid">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="đã hủy">
                  <button type="submit" class="btn btn-danger btn-sm"
                    onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                    <i class="fas fa-times me-1"></i> Hủy đơn hàng
                  </button>
                </form>
              @endif

              @if($order->status == 'đang giao')
                @if ($order->created_by == 'admin')
                  <form action="{{ route('admin.order.update-status', $order->order_id) }}" method="POST" class="d-grid">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="đã nhận hàng">
                    <button type="submit" class="btn btn-success btn-sm">
                      <i class="fas fa-box me-1"></i> Xác nhận đã giao
                    </button>
                  </form>
                @endif

                <form action="{{ route('admin.order.update-status', $order->order_id) }}" method="POST" class="d-grid">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="đã hủy">
                  <button type="submit" class="btn btn-danger btn-sm"
                    onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                    <i class="fas fa-times me-1"></i> Hủy đơn hàng
                  </button>
                </form>
              @endif

              @if(in_array($order->status, ['đã nhận hàng', 'đã hủy']))
                <button class="btn btn-secondary btn-sm" disabled>
                  <i class="fas fa-lock me-1"></i> Đơn hàng đã kết thúc
                </button>
              @endif
            </div>
          </div>
        </div>

        <!-- Thông tin hệ thống -->
        <div class="card shadow-sm">
          <div class="card-header bg-dark text-white">
            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin hệ thống</h6>
          </div>
          <div class="card-body">
            {{-- <small class="text-muted">
              <div><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
              <div><strong>Cập nhật lần cuối:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</div>
            </small> --}}
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

    .table-borderless td {
      border: none;
      padding: 8px 0;
    }

    .badge {
      font-size: 0.8em;
    }

    .img-thumbnail {
      border-radius: 8px;
    }
  </style>
@endsection

@section('js')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
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