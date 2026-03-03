@extends('admin.layouts.app')
@section('title', 'Quản lý nhân sự')
@section('employee-active', 'active')

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Quản lý nhân sự</h1>
      <a href="" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-user-plus fa-sm text-white-50"></i> Thêm nhân sự mới
      </a>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Bộ lọc & Tìm kiếm</h6>
        <span class="badge bg-info">Tổng: {{ $employees->total() }} nhân sự</span>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.employee.index') }}" method="GET" class="row g-3">
          <div class="col-md-3">
            <label for="search" class="form-label">Tìm kiếm</label>
            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}"
              placeholder="Tên, email, số điện thoại...">
          </div>
          <div class="col-md-3">
            <label for="status" class="form-label">Chức vụ</label>
            <select class="form-control" id="status" name="position">
              <option value="">Tất cả</option>
              <option value="Quản lý" {{ request('position') == 'Quản lý' ? 'selected' : '' }}>Quản lý</option>
              <option value="Nhân viên Kinh doanh" {{ request('position') == 'Nhân viên Kinh doanh' ? 'selected' : '' }}>Nhân viên Kinh doanh</option>
            </select>
          </div>
          <div class="col-md-2">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-control" id="status" name="status">
              <option value="">Tất cả</option>
              <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Đang làm việc</option>
              <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Không còn làm việc</option>
              <option value="On Leave" {{ request('status') == 'On Leave' ? 'selected' : '' }}>Đang nghỉ phép</option>
            </select>
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2 w-100">
              <i class="fas fa-filter"></i> Lọc
            </button>
          </div>
        </form>
        <div class="mt-3">
          <a href="{{ route('admin.employee.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-redo"></i> Reset bộ lọc
          </a>
        </div>
      </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Danh sách nhân sự</h6>
        <div class="dropdown">
          {{-- <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fas fa-cog"></i> Tùy chọn
          </button> --}}
          {{-- <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" id="exportUsers"><i class="fas fa-download"></i> Xuất Excel</a></li>
            <li><a class="dropdown-item" href="#" id="printUsers"><i class="fas fa-print"></i> In danh sách</a></li>
          </ul> --}}
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="usersTable" width="100%" cellspacing="0">
            <thead class="table-dark">
              <tr>
                <th width="60">ID</th>
                <th>Họ và Tên</th>
                <th width="190">Email</th>
                <th width="200">Chức vụ</th>
                <th width="140">Số điện thoại</th>
                <th width="130">Trạng thái</th>
                <th width="110" class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($employees as $employee)
                <tr>
                  <td class="text-center text-primary">
                    <strong>#{{ $employee->employee_id }}</strong>
                  </td>
                  <td>
                    {{-- class="fw-bold text-dark" --}}
                    <div>{{ $employee->full_name }}</div>
                  </td>
                  <td>
                    <div>{{ $employee->email }}</div>
                  </td>
                  <td>
                    <div>{{ $employee->position }}</div>
                  </td>
                  <td>
                    @if($employee->phone_number)
                      <div class="text-dark">
                        <i class="fas fa-phone me-1 text-success"></i>{{ $employee->phone_number }}
                      </div>
                    @else
                      <span class="text-muted">Chưa cập nhật</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($employee->status == 'Active')
                      <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i>Đang làm việc
                      </span>
                    @elseif($employee->status == 'Inactive')
                      <span class="badge bg-warning text-dark">
                        <i class="fas fa-lock me-1"></i>Không còn làm việc
                      </span>
                    @else
                      <span class="badge bg-danger">
                        <i class="fas fa-trash me-1"></i>Đang nghỉ phép
                      </span>
                    @endif
                  </td>
                  <td class="text-center">
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5">
                    <div class="text-muted">
                      <i class="fas fa-users fa-3x mb-3"></i>
                      <h5>Không có người dùng nào</h5>
                      <p>Hãy thêm người dùng mới để bắt đầu</p>
                      <a href="" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Thêm người dùng đầu tiên
                      </a>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if($employees->hasPages())
          <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
              Hiển thị {{ $employees->firstItem() ?? 0 }} - {{ $employees->lastItem() ?? 0 }} của {{ $employees->total() }}
              người dùng
            </div>
            <nav>
              {{ $employees->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
          </div>
        @endif
        {{-- {{ $employees->links('pagination::bootstrap-4') }} --}}
      </div>
    </div>
  </div>
@endsection

@section('css')
  <style>
    :root {
      --pink-color: #e83e8c;
    }

    .table th {
      font-weight: 600;
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .table td {
      vertical-align: middle;
    }

    .avatar-circle {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 1.1rem;
    }

    .bg-pink {
      background-color: var(--pink-color) !important;
      color: white;
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
    #usersTable tbody tr {
      transition: all 0.3s ease;
    }

    #usersTable tbody tr:hover {
      background-color: #f8f9fa;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Status badges */
    .badge.bg-success {
      background-color: #28a745 !important;
    }

    .badge.bg-warning {
      background-color: #ffc107 !important;
      color: #212529 !important;
    }

    .badge.bg-danger {
      background-color: #dc3545 !important;
    }

    .badge.bg-info {
      background-color: #17a2b8 !important;
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

      .avatar-circle {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
      }

      .card-body {
        padding: 1rem;
      }

      .d-sm-flex {
        flex-direction: column;
        align-items: flex-start !important;
      }

      .d-sm-flex .btn {
        margin-top: 10px;
      }
    }
  </style>
@endsection