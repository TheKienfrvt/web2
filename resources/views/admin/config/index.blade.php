@extends('admin.layouts.app')

@section('title', 'Cài đặt')
@section('config-active', 'active')

@section('content')
  <div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Cài đặt</h1>
      <div class="d-flex">
        <!-- Có thể thêm nút hành động ở đây nếu cần -->
      </div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
        <h6 class="m-0 font-weight-bold">Hiển thị danh mục</h6>
        <span class="badge bg-info">Tổng: {{ count($categories) }} danh mục</span>
      </div>
      <div class="card-body">
        <div class="alert alert-info">
          <i class="fas fa-info-circle"></i>
          <strong>Thông tin:</strong> Thiết lập các danh mục nào được phép hiển thị trên navbar và trang chính của khách
          hàng.
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover" width="100%" cellspacing="0">
            <thead class="table-primary">
              <tr>
                <th width="80" class="text-center">Mã danh mục</th>
                <th>Tên danh mục</th>
                <th width="100" class="text-center">Trạng thái</th>
                <th width="120" class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($categories as $category)
                <tr>
                  <td class="fw-bold">{{ $category->category_id }}</td>
                  <td class="fw-semibold">{{ $category->category_name }}</td>
                  <td class="text-center">
                    @if ($category->status == 'hiện')
                      <span class="badge bg-success p-2">Hiển thị</span>
                    @elseif ($category->status == 'ẩn')
                      <span class="badge bg-warning p-2">Đang ẩn</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if ($category->status == 'hiện')
                      <a href="{{ route('admin.config.category-status', $category->category_id) }}"
                        class="btn btn-sm btn-outline-warning" title="Ẩn danh mục">
                        <i class="fas fa-eye-slash"></i> Ẩn
                      </a>
                    @elseif ($category->status == 'ẩn')
                      <a href="{{ route('admin.config.category-status', $category->category_id) }}"
                        class="btn btn-sm btn-outline-success" title="Hiện danh mục">
                        <i class="fas fa-eye"></i> Hiện
                      </a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @if(count($categories) == 0)
          <div class="text-center py-4">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">Không có danh mục nào để hiển thị</p>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('css')
  <style>
    /* Status badges */
    .badge.bg-warning {
      background-color: #f6c23e !important;
      color: #212529 !important;
    }

    .badge.bg-success {
      background-color: #1cc88a !important;
    }

    .badge.bg-danger {
      background-color: #e74a3b !important;
    }

    .badge.bg-info {
      background-color: #36b9cc !important;
      color: #fff !important;
    }

    .card-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .table th {
      font-weight: 600;
    }

    .badge {
      font-size: 0.8rem;
    }

    .btn-sm {
      padding: 0.25rem 0.5rem;
      font-size: 0.8rem;
    }
  </style>
@endsection

@section('js')
  <script>
    // Có thể thêm các hiệu ứng JavaScript nếu cần
    $(document).ready(function () {
      // Thêm hiệu ứng hover cho các hàng trong bảng
      $('table tbody tr').hover(
        function () {
          $(this).addClass('table-active');
        },
        function () {
          $(this).removeClass('table-active');
        }
      );
    });
  </script>
@endsection