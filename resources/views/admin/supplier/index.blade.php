@extends('admin.layouts.app')
@section('supplier-active', 'active')

@section('title', 'Quản lý Nhà cung cấp')

@section('content')
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Quản lý Nhà cung cấp</h1>
      <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal"
        data-bs-target="#createSupplierModal">
        <i class="fas fa-plus fa-sm text-white-50"></i> Thêm nhà cung cấp
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Tổng nhà cung cấp
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSuppliers ?? 0 }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-truck fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Đang hợp tác
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeSuppliers ?? 0 }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-handshake fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div> --}}

      {{-- <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  Phiếu nhập tháng
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $monthlyReceipts ?? 0 }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div> --}}

      {{-- <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  Tổng giá trị nhập
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalImportValue ?? 0, 0, ',', '.')
                  }}đ</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Bộ lọc & Tìm kiếm</h6>
        <span class="badge bg-info">Tổng: {{ $suppliers->total() }} nhà cung cấp</span>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.supplier.index') }}" method="GET" id="filterForm" class="row g-3">
          <div class="col-md-4">
            <label for="search" class="form-label">Tìm kiếm</label>
            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}"
              placeholder="Tên, số điện thoại, địa chỉ...">
          </div>
          <div class="col-md-3">
            <label for="sort_by" class="form-label">Sắp xếp theo</label>
            <select class="form-control" id="sort_by" name="sort_by">
              <option value="supplier_id" {{ request('sort_by') == 'supplier_id' ? 'selected' : '' }}>Mã NCC</option>
              <option value="supplier_name" {{ request('sort_by') == 'supplier_name' ? 'selected' : '' }}>Tên A-Z</option>
              {{-- <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Mới nhất</option> --}}
            </select>
          </div>
          <div class="col-md-3">
            <label for="sort_order" class="form-label">Thứ tự</label>
            <select class="form-control" id="sort_order" name="sort_order">
              <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
              <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
            </select>
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
              <i class="fas fa-filter"></i> Lọc
            </button>
          </div>
        </form>
        <div class="mt-3">
          <a href="{{ route('admin.supplier.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-redo"></i> Reset bộ lọc
          </a>
        </div>
      </div>
    </div>

    <!-- Suppliers Table -->
    <div class="card shadow">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Danh sách nhà cung cấp</h6>
        <div class="dropdown">
          <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fas fa-cog"></i> Tùy chọn
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" id="exportSuppliers"><i class="fas fa-file-excel"></i> Xuất Excel</a>
            </li>
            <li><a class="dropdown-item" href="#" id="printSuppliers"><i class="fas fa-print"></i> In danh sách</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#" id="bulkActions"><i class="fas fa-tasks"></i> Hành động hàng loạt</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="suppliersTable" width="100%" cellspacing="0">
            <thead class="table-dark">
              <tr>
                <th width="80">Mã NCC</th>
                <th>Thông tin nhà cung cấp</th>
                <th width="150">Liên hệ</th>
                <th width="120">Tổng phiếu nhập</th>
                <th width="120" class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($suppliers as $supplier)
                <tr data-supplier-id="{{ $supplier->supplier_id }}">
                  <td class="text-center">
                    <strong class="text-primary">#{{ $supplier->supplier_id }}</strong>
                    <br>
                    <small class="text-muted">NCC-{{ str_pad($supplier->supplier_id, 4, '0', STR_PAD_LEFT) }}</small>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="supplier-avatar me-3">
                        <div class="avatar-circle bg-primary text-white">
                          {{ strtoupper(substr($supplier->supplier_name, 0, 1)) }}
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <div class="fw-bold text-dark mb-1">{{ $supplier->supplier_name }}</div>
                        <div class="text-muted small">
                          <i class="fas fa-map-marker-alt me-1"></i>
                          {{ Str::limit($supplier->supplier_address, 50) }}
                        </div>
                        <div class="mt-1">
                          <span class="badge bg-success">
                            <i class="fas fa-star me-1"></i>Đang hợp tác
                          </span>
                          <span class="badge bg-info ms-1">
                            {{ $supplier->receipts_count ?? 0 }} phiếu
                          </span>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="contact-info">
                      <div class="mb-1">
                        <i class="fas fa-phone text-success me-2"></i>
                        <span class="text-dark">{{ $supplier->supplier_phone }}</span>
                      </div>
                      {{-- <div class="small text-muted">
                        <i class="fas fa-clock me-2"></i>
                        {{ $supplier->order_date->format('d/m/Y') }}
                      </div> --}}
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="receipt-stats">
                      <div class="fw-bold text-primary">{{ $supplier->receipts_count ?? 0 }}</div>
                      <small class="text-muted">phiếu nhập</small>
                      @if($supplier->total_import_value)
                        <div class="text-success small">
                          {{ number_format($supplier->total_import_value, 0, ',', '.') }}đ
                        </div>
                      @endif
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                      <a  href="{{ route('admin.supplier.show' , [ 'supplierId' => $supplier->supplier_id]) }}" type="button" class="btn btn-info view-supplier" title="Xem chi tiết" data-bs-toggle="tooltip"
                        data-supplier-id="{{ $supplier->supplier_id }}" data-supplier-name="{{ $supplier->supplier_name }}">
                        <i class="fas fa-eye"></i>
                      </a>
                      {{-- <button type="button" class="btn btn-warning edit-supplier" title="Sửa thông tin"
                        data-bs-toggle="tooltip" data-supplier-id="{{ $supplier->supplier_id }}"
                        data-supplier-data='@json($supplier)'>
                        <i class="fas fa-edit"></i>
                      </button> --}}
                      {{-- <button type="button" class="btn btn-success quick-action" title="Tạo phiếu nhập"
                        data-bs-toggle="tooltip" data-supplier-id="{{ $supplier->supplier_id }}"
                        data-supplier-name="{{ $supplier->supplier_name }}">
                        <i class="fas fa-plus-circle"></i>
                      </button> --}}
                      {{-- <button type="button" class="btn btn-danger delete-supplier" title="Xóa nhà cung cấp"
                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $supplier->supplier_id }}"
                        data-bs-toggle="tooltip">
                        <i class="fas fa-trash"></i>
                      </button> --}}
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $supplier->supplier_id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Xác nhận xóa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p>Bạn có chắc chắn muốn xóa nhà cung cấp <strong>"{{ $supplier->supplier_name }}"</strong>?</p>
                            <div class="alert alert-warning">
                              <small>
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                @if($supplier->receipts_count > 0)
                                  Nhà cung cấp này có {{ $supplier->receipts_count }} phiếu nhập. Việc xóa có thể ảnh hưởng
                                  đến thống kê!
                                @else
                                  Hành động này không thể hoàn tác!
                                @endif
                              </small>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <form action="" method="POST">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger">Xóa nhà cung cấp</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-5">
                    <div class="text-muted">
                      <i class="fas fa-truck fa-3x mb-3"></i>
                      <h5>Không có nhà cung cấp nào</h5>
                      <p>Hãy thêm nhà cung cấp mới để bắt đầu</p>
                      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
                        <i class="fas fa-plus me-2"></i>Thêm nhà cung cấp đầu tiên
                      </button>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if($suppliers->hasPages())
          <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
              Hiển thị {{ $suppliers->firstItem() ?? 0 }} - {{ $suppliers->lastItem() ?? 0 }} của {{ $suppliers->total() }}
              nhà cung cấp
            </div>
            <nav>
              {{ $suppliers->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Create Supplier Modal -->
  <div class="modal fade" id="createSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Thêm nhà cung cấp mới</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="createSupplierForm" action="{{ route('admin.supplier.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="supplier_name" class="form-label">Tên nhà cung cấp <span
                      class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="supplier_name" name="supplier_name" required
                    placeholder="Nhập tên nhà cung cấp">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="supplier_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                  <input type="tel" class="form-control" id="supplier_phone" name="supplier_phone" required
                    placeholder="Nhập số điện thoại" pattern="[0-9]{10}">
                  <div class="form-text">Số điện thoại 10 chữ số</div>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="supplier_address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
              <textarea class="form-control" id="supplier_address" name="supplier_address" rows="3" required
                placeholder="Nhập địa chỉ đầy đủ"></textarea>
            </div>
            {{-- <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="tax_code" class="form-label">Mã số thuế</label>
                  <input type="text" class="form-control" id="tax_code" name="tax_code" placeholder="Mã số thuế (nếu có)">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Mô tả thêm</label>
              <textarea class="form-control" id="description" name="description" rows="2"
                placeholder="Thông tin thêm về nhà cung cấp..."></textarea>
            </div> --}}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Thêm nhà cung cấp</button>
          </div>
        </form>
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

    .avatar-circle {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 1.2rem;
      border: 3px solid #e3e6f0;
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
    #suppliersTable tbody tr {
      transition: all 0.3s ease;
    }

    #suppliersTable tbody tr:hover {
      background-color: #f8f9fa;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Contact info styling */
    .contact-info i {
      width: 16px;
      text-align: center;
    }

    /* Receipt stats */
    .receipt-stats {
      text-align: center;
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
        width: 40px;
        height: 40px;
        font-size: 1rem;
      }

      .card-body {
        padding: 1rem;
      }

      .contact-info .badge {
        font-size: 0.7rem;
      }
    }
  </style>
@endsection

@section('js')
  <script>
    $(document).ready(function () {
      // Initialize tooltips
      $('[data-bs-toggle="tooltip"]').tooltip();

      // Auto submit filter form when select changes
      $('#sort_by, #sort_order').change(function () {
        $('#filterForm').submit();
      });

      // Search with debounce
      let searchTimeout;
      $('#search').on('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
          $('#filterForm').submit();
        }, 1500);
      });

      // View supplier details
      // $('.view-supplier').click(function () {
      //   const supplierId = $(this).data('supplier-id');
      //   const supplierName = $(this).data('supplier-name');

      //   // In real implementation, you would fetch details via AJAX
      //   window.location.href = `/admin/suppliers/${supplierId}`;
      // });

      // Edit supplier
      const editModal = new bootstrap.Modal(document.getElementById('editSupplierModal'));

      $('.edit-supplier').click(function () {
        const supplierData = $(this).data('supplier-data');

        // Populate edit form
        $('#editSupplierForm').attr('action', `/admin/suppliers/${supplierData.supplier_id}`);
        $('#editFormContent').html(`
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_supplier_name" class="form-label">Tên nhà cung cấp</label>
                            <input type="text" class="form-control" id="edit_supplier_name" name="supplier_name" 
                                   value="${supplierData.supplier_name}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_supplier_phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="edit_supplier_phone" name="supplier_phone" 
                                   value="${supplierData.supplier_phone}" required pattern="[0-9]{10}">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="edit_supplier_address" class="form-label">Địa chỉ</label>
                    <textarea class="form-control" id="edit_supplier_address" name="supplier_address" 
                              rows="3" required>${supplierData.supplier_address}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" 
                                   value="${supplierData.email || ''}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_tax_code" class="form-label">Mã số thuế</label>
                            <input type="text" class="form-control" id="edit_tax_code" name="tax_code" 
                                   value="${supplierData.tax_code || ''}">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="edit_description" class="form-label">Mô tả thêm</label>
                    <textarea class="form-control" id="edit_description" name="description" 
                              rows="2">${supplierData.description || ''}</textarea>
                </div>
            `);

        editModal.show();
      });

      // Quick action - create receipt
      $('.quick-action').click(function () {
        const supplierId = $(this).data('supplier-id');
        const supplierName = $(this).data('supplier-name');

        // window.location.href = `/admin/receipts/create?supplier_id=${supplierId}`;
      });

      // Export functionality
      $('#exportSuppliers').click(function (e) {
        e.preventDefault();
        const params = new URLSearchParams(window.location.search);
        window.open(`/admin/suppliers/export?${params.toString()}`, '_blank');
      });

      // Print functionality
      $('#printSuppliers').click(function (e) {
        e.preventDefault();
        window.print();
      });

      // Bulk actions
      $('#bulkActions').click(function (e) {
        e.preventDefault();
        alert('Tính năng hành động hàng loạt sẽ được kích hoạt!');
      });

      // Form validation
      $('#createSupplierForm').submit(function (e) {
        const phone = $('#supplier_phone').val();
        if (!/^[0-9]{10}$/.test(phone)) {
          e.preventDefault();
          alert('Số điện thoại phải có đúng 10 chữ số!');
          $('#supplier_phone').focus();
        }
      });

      $('#editSupplierForm').submit(function (e) {
        const phone = $('#edit_supplier_phone').val();
        if (!/^[0-9]{10}$/.test(phone)) {
          e.preventDefault();
          alert('Số điện thoại phải có đúng 10 chữ số!');
          $('#edit_supplier_phone').focus();
        }
      });

      // Table row hover effect
      $('#suppliersTable tbody tr').hover(
        function () {
          $(this).css('transform', 'translateY(-2px)');
          $(this).css('box-shadow', '0 4px 12px rgba(0,0,0,0.1)');
        },
        function () {
          $(this).css('transform', '');
          $(this).css('box-shadow', '');
        }
      );

      // Phone number formatting
      $('#supplier_phone, #edit_supplier_phone').on('input', function () {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 10) {
          value = value.substring(0, 10);
        }
        $(this).val(value);
      });

      // Quick stats update on successful form submission
      $(document).ajaxSuccess(function (event, xhr, settings) {
        if (settings.url.includes('/admin/suppliers') && settings.type === 'POST') {
          // Refresh the page or update stats dynamically
          setTimeout(() => {
            location.reload();
          }, 1000);
        }
      });
    });
  </script>
@endsection