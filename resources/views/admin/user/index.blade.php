@extends('admin.layouts.app')

@section('title', 'Quản lý Người dùng')
@section('user-active', 'active')

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Quản lý Người dùng</h1>
      <a href="{{ route('admin.user.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-user-plus fa-sm text-white-50"></i> Thêm người dùng mới
      </a>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Bộ lọc & Tìm kiếm</h6>
        <span class="badge bg-info">Tổng: {{ $users->total() }} người dùng</span>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.user.index') }}" method="GET" class="row g-3">
          <div class="col-md-3">
            <label for="search" class="form-label">Tìm kiếm</label>
            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}"
              placeholder="Tên, email, số điện thoại...">
          </div>
          <div class="col-md-2">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-control" id="status" name="status">
              <option value="">Tất cả</option>
              <option value="mở" {{ request('status') == 'mở' ? 'selected' : '' }}>Mở</option>
              <option value="khóa" {{ request('status') == 'khóa' ? 'selected' : '' }}>Khóa</option>
              <option value="đã xóa" {{ request('status') == 'đã xóa' ? 'selected' : '' }}>Đã xóa</option>
            </select>
          </div>
          <div class="col-md-2">
            <label for="sex" class="form-label">Giới tính</label>
            <select class="form-control" id="sex" name="sex">
              <option value="">Tất cả</option>
              <option value="nam" {{ request('sex') == 'nam' ? 'selected' : '' }}>Nam</option>
              <option value="nữ" {{ request('sex') == 'nữ' ? 'selected' : '' }}>Nữ</option>
              <option value="khác" {{ request('sex') == 'khác' ? 'selected' : '' }}>Khác</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="date_range" class="form-label">Ngày sinh</label>
            <input type="date" class="form-control" id="dob" name="dob" value="{{ request('dob') }}">
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2 w-100">
              <i class="fas fa-filter"></i> Lọc
            </button>
          </div>
        </form>
        <div class="mt-3">
          <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-redo"></i> Reset bộ lọc
          </a>
        </div>
      </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Danh sách người dùng</h6>
        <div class="dropdown">
          <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fas fa-cog"></i> Tùy chọn
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" id="exportUsers"><i class="fas fa-download"></i> Xuất Excel</a></li>
            <li><a class="dropdown-item" href="#" id="printUsers"><i class="fas fa-print"></i> In danh sách</a></li>
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="usersTable" width="100%" cellspacing="0">
            <thead class="table-dark">
              <tr>
                <th width="60">ID</th>
                <th>Thông tin</th>
                <th width="120">Giới tính</th>
                <th width="120">Ngày sinh</th>
                <th width="150">Số điện thoại</th>
                <th width="100">Trạng thái</th>
                <th width="150" class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
                <tr>
                  <td class="text-center text-primary">
                    <strong>#{{ $user->user_id }}</strong>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="user-avatar me-3">
                        <div class="avatar-circle bg-primary text-white">
                          {{ strtoupper(substr($user->username, 0, 1)) }}
                        </div>
                      </div>
                      <div>
                        <div class="fw-bold text-dark">{{ $user->username }}</div>
                        <div class="text-muted small">{{ $user->email }}</div>
                        <div class="text-muted small">
                          <i class="fas fa-calendar me-1"></i>
                          {{ $user->created_at->format('d/m/Y') }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="text-center">
                    @if($user->sex == 'nam')
                      <span class="badge bg-info"><i class="fas fa-mars me-1"></i>Nam</span>
                    @elseif($user->sex == 'nữ')
                      <span class="badge bg-pink"><i class="fas fa-venus me-1"></i>Nữ</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($user->dob)
                      <span class="text-dark">{{ \Carbon\Carbon::parse($user->dob)->format('d/m/Y') }}</span>
                      <br>
                      <small class="text-muted">({{ \Carbon\Carbon::parse($user->dob)->age }} tuổi)</small>
                    @else
                      <span class="text-muted">Chưa cập nhật</span>
                    @endif
                  </td>
                  <td>
                    @if($user->phone_number)
                      <div class="text-dark">
                        <i class="fas fa-phone me-1 text-success"></i>{{ $user->phone_number }}
                      </div>
                    @else
                      <span class="text-muted">Chưa cập nhật</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($user->status == 'mở')
                      <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i>Mở
                      </span>
                    @elseif($user->status == 'khóa')
                      <span class="badge bg-warning text-dark">
                        <i class="fas fa-lock me-1"></i>Khóa
                      </span>
                    @else
                      <span class="badge bg-danger">
                        <i class="fas fa-trash me-1"></i>Đã xóa
                      </span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="{{ route('admin.user.show', ['userId' => $user->user_id]) }}" class="btn btn-info" title="Xem chi tiết" data-bs-toggle="tooltip">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('admin.user.edit', ['userId' => $user->user_id]) }}" class="btn btn-warning" title="Sửa thông tin" data-bs-toggle="tooltip">
                        <i class="fas fa-edit"></i>
                      </a>
                      @if($user->status == 'mở')
                        <button type="button" class="btn btn-secondary lock-user" title="Khóa tài khoản"
                          data-bs-toggle="tooltip" data-user-id="{{ $user->user_id }}" data-user-name="{{ $user->username }}">
                          <i class="fas fa-lock"></i>
                        </button>
                      @elseif($user->status == 'khóa')
                        <button type="button" class="btn btn-success unlock-user" title="Mở khóa tài khoản"
                          data-bs-toggle="tooltip" data-user-id="{{ $user->user_id }}" data-user-name="{{ $user->username }}">
                          <i class="fas fa-unlock"></i>
                        </button>
                      @endif

                      @if($user->status != 'đã xóa')
                        <button type="button" class="btn btn-danger" title="Xóa tài khoản" data-bs-toggle="modal"
                          data-bs-target="#deleteModal{{ $user->user_id }}" data-bs-toggle="tooltip">
                          <i class="fas fa-trash"></i>
                        </button>
                      @else
                        {{-- <button type="button" class="btn btn-success restore-user" title="Khôi phục tài khoản"
                          data-bs-toggle="tooltip" data-user-id="{{ $user->user_id }}" data-user-name="{{ $user->username }}">
                          <i class="fas fa-undo"></i>
                        </button> --}}
                      @endif
                    </div>

                  </td>
                </tr>
                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal{{ $user->user_id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Xác nhận xóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa tài khoản <strong>"{{ $user->username }}"</strong>?</p>
                        <div class="alert alert-warning">
                          <small>
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Tài khoản sẽ được đánh dấu là "đã xóa" và không thể đăng nhập.
                          </small>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form action="{{ route('admin.user.delete', ['userId' => $user->user_id]) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger">Xóa tài khoản</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
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
        @if($users->hasPages())
          <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
              Hiển thị {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} của {{ $users->total() }} người dùng
            </div>
            <nav>
              {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
          </div>
        @endif
        {{-- {{ $users->links('pagination::bootstrap-4') }} --}}
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

@section('js')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Initialize tooltips
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });

      // Auto submit filter form when select changes
      const filterSelects = document.querySelectorAll('#status, #sex');
      filterSelects.forEach(select => {
        select.addEventListener('change', function () {
          this.form.submit();
        });
      });

      // Search debounce
      let searchTimeout;
      const searchInput = document.getElementById('search');
      if (searchInput) {
        searchInput.addEventListener('input', function () {
          clearTimeout(searchTimeout);
          searchTimeout = setTimeout(() => {
            this.form.submit();
          }, 500);
        });
      }

      // Lock/Unlock user functionality
      const lockButtons = document.querySelectorAll('.lock-user, .unlock-user, .restore-user');
      lockButtons.forEach(button => {
        button.addEventListener('click', function () {
          const userId = this.dataset.userId;
          const userName = this.dataset.userName;
          const action = this.classList.contains('lock-user') ? 'khóa' :
            this.classList.contains('unlock-user') ? 'mở khóa' : 'khôi phục';

          if (confirm(`Bạn có chắc muốn ${action} tài khoản "${userName}"?`)) {
            // In real implementation, you would make an AJAX request here
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('home') }}/admin/user/${userId}/status`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PATCH';

            const statusField = document.createElement('input');
            statusField.type = 'hidden';
            statusField.name = 'status';
            statusField.value = action === 'khóa' ? 'khóa' :
              action === 'mở khóa' ? 'mở' : 'mở';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            form.appendChild(statusField);
            document.body.appendChild(form);
            form.submit();
          }
        });
      });

      // Export functionality
      document.getElementById('exportUsers')?.addEventListener('click', function (e) {
        e.preventDefault();
        // In real implementation, this would trigger an export
        alert('Tính năng xuất Excel sẽ được kích hoạt!');
      });

      // Print functionality
      document.getElementById('printUsers')?.addEventListener('click', function (e) {
        e.preventDefault();
        window.print();
      });

      // Table row hover effect
      const tableRows = document.querySelectorAll('#usersTable tbody tr');
      tableRows.forEach(row => {
        row.addEventListener('mouseenter', function () {
          this.style.transform = 'translateY(-2px)';
          this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
        });
        row.addEventListener('mouseleave', function () {
          this.style.transform = '';
          this.style.boxShadow = '';
        });
      });
    });
  </script>
@endsection