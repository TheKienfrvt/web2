@extends('admin.layouts.app')

@section('title', 'Chi tiết khách hàng - ' . $user->username)
@section('user-active', 'active')

@section('content')
  <div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}" class="text-decoration-none">Quản lý khách
            hàng</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $user->username }}</li>
      </ol>
    </nav>

    <!-- Thông báo -->
    <div id="alert-container"></div>

    <div class="row">
      <!-- Thông tin chính -->
      <div class="col-md-4">
        <!-- Card ảnh đại diện và thông tin cơ bản -->
        <div class="card shadow-sm mb-4">
          <div class="card-body text-center">
            <div class="mb-3">
              <img src="{{ $user->avatar_url ? asset('images/' . $user->avatar_url) : asset('images/avatar.jpg') }}"
                alt="Avatar" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            </div>
            <h4 class="mb-1">{{ $user->username }}</h4>
            <p class="text-muted mb-2">{{ $user->email }}</p>

            <!-- Badge trạng thái -->
            @php
              $statusClass = [
                'mở' => 'success',
                'khóa' => 'warning',
                'đã xóa' => 'danger'
              ][$user->status] ?? 'secondary';
            @endphp
            <span class="badge bg-{{ $statusClass }} mb-3">{{ $user->status }}</span>

            <!-- Badge vai trò -->
            <span class="badge bg-primary mb-3">Khách hàng</span>

            <!-- Thao tác nhanh -->
            <div class="d-grid gap-2 mt-3">
              <a href="{{ route('admin.user.edit', $user->user_id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i> Chỉnh sửa
              </a>
              {{-- @if($user->status == 'mở')
              <button class="btn btn-outline-danger btn-sm" id="lock-user-btn">
                <i class="fas fa-lock me-1"></i> Khóa tài khoản
              </button>
              @elseif($user->status == 'khóa')
              <button class="btn btn-outline-success btn-sm" id="unlock-user-btn">
                <i class="fas fa-unlock me-1"></i> Mở khóa tài khoản
              </button>
              @endif --}}
            </div>
          </div>
        </div>

        <!-- Thông tin liên hệ -->
        <div class="card shadow-sm">
          <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-address-card me-2"></i>Thông tin liên hệ</h6>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <strong><i class="fas fa-phone me-2 text-primary"></i>Số điện thoại:</strong>
              <p class="mb-0 mt-1">{{ $user->phone_number ?? 'Chưa cập nhật' }}</p>
            </div>
            <div class="mb-3">
              <strong><i class="fas fa-venus-mars me-2 text-success"></i>Giới tính:</strong>
              <p class="mb-0 mt-1">
                @if($user->sex == 'nam')
                  <i class="fas fa-mars text-primary me-1"></i>Nam
                @elseif($user->sex == 'nữ')
                  <i class="fas fa-venus text-danger me-1"></i>Nữ
                @else
                  Chưa cập nhật
                @endif
              </p>
            </div>
            <div>
              <strong><i class="fas fa-birthday-cake me-2 text-warning"></i>Ngày sinh:</strong>
              <p class="mb-0 mt-1">
                {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d/m/Y') : 'Chưa cập nhật' }}
                @if($user->dob)
                  <br><small class="text-muted">({{ \Carbon\Carbon::parse($user->dob)->age }} tuổi)</small>
                @endif
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Thông tin chi tiết và thống kê -->
      <div class="col-md-8">
        <!-- Thông tin tài khoản -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-user-circle me-2"></i>Thông tin tài khoản</h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr>
                    <td width="40%"><strong>Mã khách hàng:</strong></td>
                    <td>
                      <span class="badge bg-secondary">#{{ $user->user_id }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Tên đăng nhập:</strong></td>
                    <td>{{ $user->username }}</td>
                  </tr>
                  <tr>
                    <td><strong>Email:</strong></td>
                    <td>
                      <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                        <i class="fas fa-envelope me-1 text-primary"></i>{{ $user->email }}
                      </a>
                    </td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr>
                    <td width="40%"><strong>Vai trò:</strong></td>
                    <td>
                      <span class="badge bg-primary">Khách hàng</span>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Trạng thái:</strong></td>
                    <td>
                      <span class="badge bg-{{ $statusClass }}">{{ $user->status }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Ngày tạo:</strong></td>
                    <td>
                      <i class="fas fa-calendar me-1 text-info"></i>
                      {{ $user->created_at->format('d/m/Y H:i') }}
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Thống kê (có thể thêm sau) -->
        <div class="row">
          <div class="col-md-4">
            <div class="card shadow-sm text-center">
              <div class="card-body">
                <h3 class="text-primary mb-1" id="order-count">0</h3>
                <p class="text-muted mb-0">Đơn hàng</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm text-center">
              <div class="card-body">
                <h3 class="text-success mb-1" id="total-spent">0</h3>
                <p class="text-muted mb-0">Tổng chi tiêu</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm text-center">
              <div class="card-body">
                <h3 class="text-warning mb-1" id="last-order">-</h3>
                <p class="text-muted mb-0">Đơn hàng gần nhất</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Lịch sử hoạt động (có thể thêm sau) -->
        <div class="card shadow-sm mt-4">
          <div class="card-header bg-secondary text-white">
            <h6 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử hoạt động gần đây</h6>
          </div>
          <div class="card-body">
            <div class="text-center py-4">
              <i class="fas fa-chart-line fa-2x text-muted mb-3"></i>
              <p class="text-muted">Chức năng thống kê đang được phát triển</p>
            </div>
            <!-- Có thể thêm timeline hoạt động ở đây -->
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

    .table-borderless td {
      border: none;
      padding: 8px 0;
    }

    .img-thumbnail {
      border: 3px solid #dee2e6;
    }

    .badge {
      font-size: 0.8em;
    }
  </style>
@endsection

@section('js')
  <script>
    $(document).ready(function () {
      // Khóa tài khoản
      $('#lock-user-btn').on('click', function () {
        $('#confirmModalTitle').text('Khóa tài khoản');
        $('#confirmModalBody').html(`
                      <p>Bạn có chắc chắn muốn khóa tài khoản của <strong>{{ $user->username }}</strong>?</p>
                      <p class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Khách hàng sẽ không thể đăng nhập khi tài khoản bị khóa.</p>
                  `);
        $('#confirmActionBtn')
          .removeClass('btn-success')
          .addClass('btn-warning')
          .text('Khóa tài khoản');

        $('#confirmModal').modal('show');

        $('#confirmActionBtn').off('click').on('click', function () {
          updateUserStatus('khóa');
        });
      });

      // Mở khóa tài khoản
      $('#unlock-user-btn').on('click', function () {
        $('#confirmModalTitle').text('Mở khóa tài khoản');
        $('#confirmModalBody').html(`
                      <p>Bạn có chắc chắn muốn mở khóa tài khoản của <strong>{{ $user->username }}</strong>?</p>
                      <p class="text-success"><i class="fas fa-info-circle me-1"></i>Khách hàng sẽ có thể đăng nhập lại bình thường.</p>
                  `);
        $('#confirmActionBtn')
          .removeClass('btn-warning')
          .addClass('btn-success')
          .text('Mở khóa tài khoản');

        $('#confirmModal').modal('show');

        $('#confirmActionBtn').off('click').on('click', function () {
          updateUserStatus('mở');
        });
      });

      // Hàm cập nhật trạng thái user
      function updateUserStatus(status) {
        const btn = $('#confirmActionBtn');
        const originalText = btn.html();

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...');

        $.ajax({
          // 
          url: "{{ route('admin.user.status', $user->user_id) }}",
          method: 'PUT',
          data: {
            _token: "{{ csrf_token() }}",
            status: status
          },
          success: function (response) {
            $('#confirmModal').modal('hide');
            showAlert(`Cập nhật trạng thái thành công! Tài khoản đã được ${status}.`, 'success');
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

      // Load thống kê (nếu có API)
      function loadUserStats() {
        // Giả sử có API endpoint để lấy thống kê
        //admin.user.stats
        $.ajax({
          url: "",
          method: 'GET',
          success: function (response) {
            if (response.success) {
              $('#order-count').text(response.data.order_count || 0);
              $('#total-spent').text(formatCurrency(response.data.total_spent || 0));
              if (response.data.last_order) {
                $('#last-order').text(response.data.last_order);
              }
            }
          },
          error: function () {
            // Nếu không có API, ẩn phần thống kê hoặc hiển thị mặc định
            $('#order-count').text('0');
            $('#total-spent').text('0đ');
          }
        });
      }

      // Hàm định dạng tiền tệ
      function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
          style: 'currency',
          currency: 'VND'
        }).format(amount);
      }

      // Gọi hàm load thống kê khi trang tải
      loadUserStats();
    });
  </script>
@endsection