@extends('frontend.layouts.app')

@section('title', 'Lịch Sử Đơn Hàng')

@section('css')
  <style>
    .order-card {
      border-radius: 15px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      overflow: hidden;
    }

    .order-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .order-card .card-header {
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }

    .product-item {
      transition: background-color 0.2s ease;
    }

    .product-item:hover {
      background-color: #f8f9fa;
      border-radius: 10px;
      margin-left: -10px;
      margin-right: -10px;
      padding-left: 10px !important;
      padding-right: 10px !important;
    }

    .badge {
      font-size: 0.75em;
    }

    .empty-state-icon {
      opacity: 0.5;
    }

    .product-image {
      border: 2px solid #f8f9fa;
      transition: border-color 0.2s ease;
    }

    .product-item:hover .product-image {
      border-color: #007bff;
    }

    /* Status badge animations */
    .badge {
      position: relative;
      overflow: hidden;
    }

    .badge::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s;
    }

    .badge:hover::before {
      left: 100%;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .order-badge {
        margin-bottom: 10px;
      }

      .product-info .row>div {
        margin-bottom: 5px;
      }

      .card-body {
        padding: 1rem;
      }
    }
  </style>
@endsection

@section('content')
  <div class="container px-0 mt-4">
    {{-- breadcrumb/đường dẫn điều hướng--}}
    <div class="">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('home')}}">Trang chủ</a></li>
          <li class="breadcrumb-item"><a href="{{route('profile.show')}}">Tài khoản</a></li>
          <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
        </ol>
      </nav>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="h4 mb-0"><i class="fas fa-clipboard-list me-2 text-primary"></i>Lịch Sử Đơn Hàng</h2>
      <span class="badge bg-primary">{{ $orders->total() }} đơn hàng</span>
    </div>

    @if($orders->count() > 0)
      {{-- Danh sách đơn hàng --}}
      @foreach ($orders as $order)
        <div class="card order-card mb-5 shadow-sm border-0">
          <div class="card-header bg-light py-3">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="d-flex align-items-center">
                  <div class="order-badge me-3">
                    @if($order->status === 'chờ xác nhận')
                      <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                        <i class="fas fa-clock me-1"></i>Chờ xác nhận
                      </span>
                    @elseif($order->status === 'đã xác nhận')
                      <span class="badge bg-info fs-6 px-3 py-2">
                        <i class="fas fa-check-circle me-1"></i>Đã xác nhận
                      </span>
                    @elseif($order->status === 'đang giao')
                      <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-shipping-fast me-1"></i>Đang giao
                      </span>
                    @elseif($order->status === 'đã nhận hàng')
                      <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-box-open me-1"></i>Đã nhận hàng
                      </span>
                    @elseif($order->status === 'đã hủy')
                      <span class="badge bg-danger fs-6 px-3 py-2">
                        <i class="fas fa-times-circle me-1"></i>Đã hủy
                      </span>
                    @else
                      <span class="badge bg-secondary fs-6 px-3 py-2">{{ $order->status }}</span>
                    @endif
                  </div>
                  <small class="text-muted">Mã đơn: #{{ $order->order_id }}</small>
                </div>
              </div>
              @if ($order->status === 'đã nhận hàng')
                <div class="col-md-6 text-md-end">
                  <small class="text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    {{ $order->order_date->format('H:i d/m/Y') . ' - ' . $order->delivery_date->format('H:i d/m/Y ')}}
                  </small>
                </div>
              @else
                <div class="col-md-6 text-md-end">
                  <small class="text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    {{ $order->order_date->format('H:i d/m/Y') }}
                  </small>
                </div>
              @endif
            </div>
          </div>

          <div class="card-body">
            <!-- Địa chỉ giao hàng -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="d-flex align-items-start">
                  <i class="fas fa-map-marker-alt text-danger mt-1 me-3"></i>
                  <div>
                    <small class="text-muted d-block">Địa chỉ giao hàng</small>
                    <span class="fw-medium">{{ $order->address }}</span>
                  </div>
                </div>
              </div>
            </div>

            {{-- Phương thức thanh toán --}}
            <div class="row mb-3">
              <div class="col-12">
                <div class="d-flex align-items-start">
                  <i class="fa-solid fa-money-bill text-success mt-1 me-2"></i>
                  <div>
                    <small class="text-muted d-block">Phương thức thanh toán</small>
                    <span class="fw-medium">{{ $order->payment_method }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="products-section">
              <h6 class="fw-semibold mb-3 text-muted">Sản phẩm đã đặt</h6>
              @foreach ($order->orderDetails as $detail)
                <div class="product-item d-flex align-items-center mb-3 pb-3 border-bottom">
                  <div class="product-image me-3">
                    <img src="{{ asset('images/' . ($detail->product->image_url ?? "no image available.jpg")) }}" alt="{{ $detail->product->product_name }}"
                      class="rounded-3" width="70" height="70" style="object-fit: cover;">
                  </div>
                  <div class="product-info flex-grow-1">
                    <h6 class="mb-1 fw-semibold">{{ $detail->product->product_name }}</h6>
                    <div class="row text-muted">
                      <div class="col-sm-4">
                        <small>Số lượng: {{ $detail->quantity }}</small>
                      </div>
                      <div class="col-sm-4">
                        <small>Đơn giá: {{ number_format($detail->price, 0, ',', '.') }}đ</small>
                      </div>
                      <div class="col-sm-4">
                        <small>Thành tiền:
                          <span class="fw-semibold text-dark">
                            {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}đ
                          </span>
                        </small>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Tổng tiền và hành động -->
            <div class="row align-items-center mt-4">
              <div class="col-md-6 mb-2 mb-md-0">
                <div class="d-flex align-items-center">
                  <i class="fas fa-info-circle text-primary me-2"></i>
                  <small class="text-muted">
                    @if($order->status === 'chờ xác nhận')
                      Đơn hàng đang chờ xác nhận từ cửa hàng
                    @elseif($order->status === 'đã xác nhận')
                      Đơn hàng đã được xác nhận, đang chuẩn bị giao
                    @elseif($order->status === 'đang giao')
                      Đơn hàng đang trên đường giao đến bạn
                    @elseif($order->status === 'đã nhận hàng')
                      Đơn hàng đã được giao thành công
                    @elseif($order->status === 'đã hủy')
                      Đơn hàng đã bị hủy
                    @endif
                  </small>
                </div>
              </div>
              <div class="col-md-6 text-md-end">
                <div class="d-flex align-items-center justify-content-md-end">
                  <span class="text-muted me-3">Tổng cộng:</span>
                  <h4 class="fw-bold text-success mb-0">
                    {{ number_format($order->total_amount, 0, ',', '.') }}đ
                  </h4>
                </div>

                <!-- Nút hành động -->
                <div class="mt-3">
                  @if($order->status === 'chờ xác nhận' || $order->status === 'đã xác nhận')
                    <a href="{{route('order.cancel', ['order' => $order])}}" class="btn btn-outline-danger btn-sm me-2">
                      <i class="fas fa-times me-1"></i>Hủy đơn
                    </a>
                  @endif

                  @if($order->status === 'đang giao')
                    <a class="btn btn-outline-success btn-sm me-2" href='{{ route('order.delivered', ['order' => $order]) }}'>
                      <i class="fas fa-times me-1"></i>Xác nhận đã nhận được hàng
                    </a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach

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
    @else
      <!-- Empty state -->
      <div class="text-center py-5">
        <div class="empty-state-icon mb-4">
          <i class="fas fa-clipboard-list fa-4x text-muted"></i>
        </div>
        <h4 class="text-muted mb-3">Chưa có đơn hàng nào</h4>
        <p class="text-muted mb-4">Bạn chưa có đơn hàng nào trong lịch sử mua hàng.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">
          <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
        </a>
      </div>
    @endif
  </div>
@endsection

@section('js')
  <script>
    function cancelOrder(orderId) {
      if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
        // Gọi API hủy đơn hàng
        fetch(`/orders/${orderId}/cancel`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
          }
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              location.reload();
            } else {
              alert('Có lỗi xảy ra khi hủy đơn hàng');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi hủy đơn hàng');
          });
      }
    }

    // Hiệu ứng loading khi click nút
    document.addEventListener('DOMContentLoaded', function () {
      const buttons = document.querySelectorAll('.btn');
      buttons.forEach(button => {
        button.addEventListener('click', function (e) {
          if (this.classList.contains('btn-outline-danger') ||
            this.classList.contains('btn-outline-primary')) {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';
            this.disabled = true;

            setTimeout(() => {
              this.innerHTML = originalText;
              this.disabled = false;
            }, 2000);
          }
        });
      });
    });
  </script>
@endsection