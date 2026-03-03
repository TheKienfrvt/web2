@extends('frontend.layouts.app')

@section('css')
  <style>
    .form-control-plaintext {
      background: transparent;
      border: none;
    }

    .border-bottom {
      border-bottom: 1px solid #dee2e6 !important;
    }

    .card {
      border: none;
      border-radius: 15px;
    }

    .card-header {
      border-radius: 15px 15px 0 0 !important;
      border: none;
    }

    .form-check-input:checked {
      background-color: #198754;
      border-color: #198754;
    }

    .sticky-top {
      z-index: 1020;
    }

    .btn-success {
      background: linear-gradient(135deg, #198754, #157347);
      border: none;
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .btn-success:hover {
      background: linear-gradient(135deg, #157347, #13653f);
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
    }
  </style>
@endsection

@section('content')
  <div class="container py-4">
    <div class="row">
      <div class="col-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
          </ol>
        </nav>
        <h1 class="h2 mb-4"><i class="fas fa-cash-register me-2"></i>Thanh toán</h1>
      </div>
    </div>

    <div class="row">
      <!-- Thông tin giao hàng -->
      <div class="col-lg-8 mb-4">
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-truck me-2"></i>Thông Tin Giao Hàng</h4>
          </div>
          <div class="card-body">
            {{-- {{ route('order.store') }} --}}
            <form action="{{ route('order.store') }}" method="POST" id="checkout-form">
              @csrf

              <!-- Thông tin khách hàng -->
              <div class="row mb-4">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Họ và tên</label>
                    <p class="form-control-plaintext border-bottom pb-2">{{ $user->username }}</p>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Số điện thoại</label>
                    <p class="form-control-plaintext border-bottom pb-2">{{ $user->phone_number }}</p>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Email</label>
                    <p class="form-control-plaintext border-bottom pb-2">{{ $user->email }}</p>
                  </div>
                </div>
              </div>

              <!-- Địa chỉ giao hàng -->
              <div class="mb-4">
                <h5 class="mb-3"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Địa chỉ giao hàng</h5>
                @if($addresses->count() > 0)
                  <select class="form-select form-select-lg" name="address" id="address" required>
                    <option value="">Chọn địa chỉ giao hàng</option>
                    @foreach ($addresses as $address)
                      <option value="{{ $address->address }}">{{ $address->address }}</option>
                    @endforeach
                  </select>
                  <small class="text-muted mt-2 d-block">
                    <i class="fas fa-info-circle me-1"></i>Chọn địa chỉ bạn muốn nhận hàng
                  </small>
                @else
                  <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Bạn chưa có địa chỉ nào. Vui lòng thêm địa chỉ giao hàng.
                  </div>
                @endif
              </div>

              <!-- Phương thức thanh toán -->
              <div class="mb-4">
                <h5 class="mb-3"><i class="fas fa-credit-card me-2 text-info"></i>Phương thức thanh toán</h5>
                <div class="card">
                  <div class="card-body">
                    <div class="form-check mb-3">
                      <input class="form-check-input" type="radio" name="payment_method" id="cod" value="tiền mặt"
                        checked>
                      <label class="form-check-label d-flex align-items-center" for="cod">
                        <i class="fas fa-money-bill-wave fa-2x text-success me-3"></i>
                        <div>
                          <span class="fw-semibold d-block">Thanh toán khi nhận hàng (COD)</span>
                          <small class="text-muted">Bạn chỉ phải thanh toán khi nhận được hàng</small>
                        </div>
                      </label>
                    </div>
                    <div class="form-check mb-3">
                      <input class="form-check-input" type="radio" name="payment_method" id="banking"
                        value="chuyển khoản">
                      <label class="form-check-label d-flex align-items-center" for="banking">
                        <i class="fas fa-university fa-2x text-primary me-3"></i>
                        <div>
                          <span class="fw-semibold d-block">Chuyển khoản ngân hàng</span>
                          <small class="text-muted">Chuyển khoản qua ngân hàng hoặc ví điện tử</small>
                        </div>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Thông tin đơn hàng -->
      <div class="col-lg-4">
        <div class="card shadow sticky-top" style="top: 20px;">
          <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-receipt me-2"></i>Thông Tin Đơn Hàng</h4>
          </div>
          <div class="card-body">
            <!-- Danh sách sản phẩm -->
            <div class="mb-3">
              <h6 class="fw-semibold mb-3">Sản phẩm trong đơn hàng</h6>
              @foreach($cart->cartItems as $item)
                <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                  <div class="d-flex">
                    <img src="{{ asset('images/' . ($item->product->image_url ?? 'no image available.jpg')) }}"
                      alt="{{ $item->product->product_name }}" class="rounded me-3"
                      style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                      <span class="fw-medium d-block">{{ $item->product->product_name }}</span>
                      <small class="text-muted">Số lượng: {{ $item->quantity }}</small>
                      <div class="mt-1">
                        <small class="text-muted">{{ number_format($item->product->price, 0, ',', '.') }}đ x
                          {{ $item->quantity }}</small>
                      </div>
                    </div>
                  </div>
                  <span class="fw-semibold text-success p-2">
                    {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ
                  </span>
                </div>
              @endforeach
            </div>

            <!-- Tổng tiền -->
            <div class="border-top pt-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Tạm tính:</span>
                <span>{{ number_format($tongTienGioHang, 0, ',', '.') }}đ</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Phí vận chuyển:</span>
                <span>0đ</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Giảm giá:</span>
                <span class="text-danger">0đ</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <strong class="h5 mb-0">Tổng cộng:</strong>
                <strong class="h5 mb-0 text-success">{{ number_format($tongTienGioHang, 0, ',', '.') }}đ</strong>
              </div>
            </div>

            <!-- Nút xác nhận -->
            <div class="mt-4">
              <button type="submit" form="checkout-form" class="btn btn-success btn-lg w-100 py-3" @if ($addresses->count() == 0) disabled @endif>
                <i class="fas fa-check-circle me-2"></i>
                <strong>XÁC NHẬN ĐƠN HÀNG</strong>
              </button>
              <div class="text-center mt-2">
                <small class="text-muted">
                  Bằng cách xác nhận, bạn đồng ý với
                  <a href="#" class="text-decoration-none">điều khoản và điều kiện</a> của chúng tôi
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal hiển thị QR -->
  <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header">
          <h5 class="modal-title" id="qrModalLabel">Thanh toán chuyển khoản</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
          <p>Vui lòng quét mã QR để thanh toán:</p>
          <img src="{{ asset('images/qr vietcombank.jpg') }}" alt="QR Code" class="img-fluid mb-3">
          <p><strong>Số tài khoản:</strong> 1047676280 - Vietcombank</p>
          <p class="h5"><strong class="text-danger">Lưu ý:</strong> Nội dung chuyển khoản: <strong>{{ $transferContent }}</strong></p>
        </div>
        <div class="modal-footer">
          <button type="button" id="confirmPaymentBtn" class="btn btn-success">Đã chuyển khoản</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      console.log('object');
      // Xử lý khi form submit
      const form = document.getElementById('checkout-form');
      const qrModalEl = document.getElementById('qrModal');
      const qrModal = new bootstrap.Modal(qrModalEl);
      const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');

      let pendingSubmit = false; // Đánh dấu trạng thái form

      form.addEventListener('submit', function (e) {
        if (pendingSubmit) return; // tránh lặp lại sau khi xác nhận modal

        const addressSelect = document.getElementById('address');
        if (!addressSelect.value) {
          e.preventDefault();
          alert('Vui lòng chọn địa chỉ giao hàng!');
          addressSelect.focus();
          return false;
        }

        const formData = new FormData(form);
        const paymentMethodValue = formData.get('payment_method');

        if (paymentMethodValue === 'chuyển khoản') {
          e.preventDefault(); // chặn gửi form ngay lập tức
          qrModal.show(); // hiển thị modal
        }
      });

      // Khi người dùng ấn "Đã chuyển khoản"
      confirmPaymentBtn.addEventListener('click', () => {
        qrModal.hide();
        pendingSubmit = true; // cho phép gửi lại form
        form.submit(); // gửi form thật sự
      });

      // Hiệu ứng khi chọn phương thức thanh toán
      const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
      paymentMethods.forEach(method => {
        method.addEventListener('change', function () {
          const labels = document.querySelectorAll('.form-check-label');
          labels.forEach(label => {
            label.closest('.form-check').classList.remove('border', 'border-primary', 'rounded', 'p-3');
          });

          if (this.checked) {
            this.closest('.form-check').classList.add('border', 'border-primary', 'rounded', 'p-3');
          }
        });
      });

      // Kích hoạt border cho phương thức thanh toán mặc định
      const defaultPayment = document.querySelector('input[name="payment_method"]:checked');
      if (defaultPayment) {
        defaultPayment.closest('.form-check').classList.add('border', 'border-primary', 'rounded', 'p-3');
      }
    });
  </script>
@endsection