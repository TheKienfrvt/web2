@extends('admin.layouts.app')
@section('receipt-active', 'active')

@section('title', 'Quản lý Phiếu nhập')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Quản lý Phiếu nhập</h1>
            <a href="{{ route('admin.receipt.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tạo phiếu nhập mới
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Tổng phiếu nhập
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $receipts->total() ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Đang chờ
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $receipts->totalPendingReceipts ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Đã nhận
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $receipts->totalReceivedReceipts ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Đã hủy
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $receipts->totalCancelledReceipts ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
                <span class="badge bg-info">Tổng: {{ $receipts->total() }} phiếu nhập</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.receipt.index') }}" method="GET" id="filterForm" class="row g-3">
                    <div class="col-md-2">
                        <label for="search" class="form-label">Mã phiếu</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Mã phiếu nhập...">
                    </div>
                    <div class="col-md-3">
                        <label for="supplier_id" class="form-label">Nhà cung cấp</label>
                        <select class="form-control select2" id="supplier_id" name="supplier_id">
                            <option value="">Tất cả nhà cung cấp</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->supplier_id }}" {{ request('supplier_id') == $supplier->supplier_id ? 'selected' : '' }}>
                                    {{ $supplier->supplier_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="đang chờ" {{ request('status') == 'đang chờ' ? 'selected' : '' }}>Đang chờ</option>
                            <option value="đã nhận" {{ request('status') == 'đã nhận' ? 'selected' : '' }}>Đã nhận</option>
                            <option value="đã hủy" {{ request('status') == 'đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="start_date" class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </form>
                <div class="mt-3">
                    <a href="{{ route('admin.receipt.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-redo"></i> Reset bộ lọc
                    </a>
                </div>
            </div>
        </div>

        <!-- Receipts Table -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Danh sách phiếu nhập</h6>
                <div class="dropdown">
                    {{-- <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-cog"></i> Tùy chọn
                    </button> --}}
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" id="bulkUpdateStatus"><i class="fas fa-sync"></i> Cập nhật
                                trạng thái hàng loạt</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#" id="exportReceipts"><i class="fas fa-file-excel"></i> Xuất
                                Excel</a></li>
                        <li><a class="dropdown-item" href="#" id="printReceipts"><i class="fas fa-print"></i> In danh
                                sách</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="receiptsTable" width="100%" cellspacing="0">
                        <thead class="table-dark">
                            <tr>
                                <th width="80">Mã phiếu</th>
                                <th>Nhà cung cấp</th>
                                <th width="120">Ngày đặt</th>
                                <th width="120">Số sản phẩm</th>
                                <th width="140">Tổng giá trị</th>
                                <th width="100">Trạng thái</th>
                                <th width="150" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receipts as $receipt)
                                <tr data-receipt-id="{{ $receipt->receipt_id }}">
                                    <td class="text-center">
                                        <strong class="text-primary">#{{ $receipt->receipt_id }}</strong>
                                        <br>
                                        {{-- <small class="text-muted">PN-{{ str_pad($receipt->receipt_id, 6, '0', STR_PAD_LEFT)
                                            }}</small> --}}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="supplier-avatar me-3">
                                                <div class="avatar-circle bg-primary text-white">
                                                    {{ strtoupper(substr($receipt->supplier->supplier_name ?? 'N', 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $receipt->supplier->supplier_name ?? 'N/A' }}
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="fas fa-phone me-1"></i>
                                                    {{ $receipt->supplier->supplier_phone ?? 'N/A' }}
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    {{ Str::limit($receipt->supplier->supplier_address ?? 'N/A', 30) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="text-dark">
                                            {{ \Carbon\Carbon::parse($receipt->order_date)->format('d/m/Y') }}</div>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($receipt->order_date)->format('H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{--
                                        <span class="badge bg-info text-dark fs-6">
                                            {{ $receipt->quantity_product ?? 0 }}
                                        </span>
                                        --}}
                                        <span class="text-dark fs-6">
                                            {{ $receipt->quantity_product ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <strong
                                            class="text-success">{{ number_format($receipt->total_amount, 0, ',', '.') }}đ</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($receipt->status == 'đang chờ')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>Đang chờ
                                            </span>
                                        @elseif($receipt->status == 'đã nhận')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Đã nhận
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Đã hủy
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{-- <div class="btn-group btn-group-sm" role="group"> --}}
                                            <a href="{{ route('admin.receipt.show', ['receiptId' => $receipt->receipt_id]) }}"
                                                class="btn btn-info" title="Xem chi tiết" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            {{-- <a href="" class="btn btn-warning" title="Sửa phiếu nhập"
                                                data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a> --}}

                                            @if($receipt->status == 'đang chờ')
                                                <button type="button" class="btn btn-success update-status" title="Xác nhận đã nhận"
                                                    data-bs-toggle="tooltip" data-receipt-id="{{ $receipt->receipt_id }}"
                                                    data-receipt-code="#{{ $receipt->receipt_id }}" data-action="received">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger update-status" title="Hủy phiếu nhập"
                                                    data-bs-toggle="tooltip" data-receipt-id="{{ $receipt->receipt_id }}"
                                                    data-receipt-code="#{{ $receipt->receipt_id }}" data-action="cancelled">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif($receipt->status == 'đã nhận')
                                                {{-- <button type="button" class="btn btn-secondary" title="Đã nhận hàng"
                                                    data-bs-toggle="tooltip" disabled>
                                                    <i class="fas fa-check-double"></i>
                                                </button> --}}
                                            @endif

                                            {{-- <button type="button" class="btn btn-outline-danger delete-receipt"
                                                title="Xóa phiếu nhập" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $receipt->receipt_id }}"
                                                data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button> --}}
                                            {{--
                                        </div> --}}

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $receipt->receipt_id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Xác nhận xóa</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Bạn có chắc chắn muốn xóa phiếu nhập
                                                            <strong>#{{ $receipt->receipt_id }}</strong>?</p>
                                                        <div class="alert alert-warning">
                                                            <small>
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                Tất cả chi tiết phiếu nhập sẽ bị xóa. Hành động này không thể
                                                                hoàn tác!
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Hủy</button>
                                                        <form action="" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Xóa phiếu nhập</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                                            <h5>Không có phiếu nhập nào</h5>
                                            <p>Hãy tạo phiếu nhập mới để bắt đầu</p>
                                            <a href="" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Tạo phiếu nhập đầu tiên
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($receipts->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị {{ $receipts->firstItem() ?? 0 }} - {{ $receipts->lastItem() ?? 0 }} của
                            {{ $receipts->total() }} phiếu nhập
                        </div>
                        <nav>
                            {{ $receipts->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật trạng thái phiếu nhập</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        <input type="hidden" id="receiptId" name="receipt_id">
                        <input type="hidden" id="actionType" name="action">
                        <div class="mb-3">
                            <label class="form-label">Trạng thái mới</label>
                            <div class="form-control" id="newStatusText"
                                style="background-color: #f8f9fa; font-weight: bold;"></div>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="statusNote" class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea class="form-control" id="statusNote" name="note" rows="3"
                                placeholder="Nhập ghi chú về việc thay đổi trạng thái..."></textarea>
                        </div> --}}
                        <div class="alert alert-info" id="statusWarning">
                            <small>
                                <i class="fas fa-info-circle me-2"></i>
                                <span id="warningText"></span>
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="saveStatus">Xác nhận</button>
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

        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }

        .border-left-danger {
            border-left: 0.25rem solid #e74a3b !important;
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
        #receiptsTable tbody tr {
            transition: all 0.3s ease;
        }

        #receiptsTable tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

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

        /* Select2 customization */
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            height: calc(1.5em + 0.75rem + 2px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + 0.75rem + 2px);
            padding-left: 0.75rem;
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

            .stats-card .card-body {
                padding: 0.75rem;
            }
        }
    </style>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('.select2').select({
                placeholder: "Chọn nhà cung cấp...",
                allowClear: true,
                width: '100%'
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Auto submit filter form when select changes
            $('#status').change(function () {
                $('#filterForm').submit();
            });

            // Date range validation
            $('#start_date, #end_date').change(function () {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                if (startDate && endDate && startDate > endDate) {
                    $('#end_date').val(startDate);
                }
            });

            // Update status functionality
            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            let currentReceiptId = null;
            let currentAction = null;

            $('.update-status').click(function () {
                currentReceiptId = $(this).data('receipt-id');
                currentAction = $(this).data('action');
                const receiptCode = $(this).data('receipt-code');

                let statusText = '';
                let warningText = '';

                if (currentAction === 'received') {
                    statusText = 'ĐÃ NHẬN';
                    warningText = 'Khi xác nhận đã nhận hàng, số lượng sản phẩm sẽ được cập nhật vào kho. Hành động này không thể hoàn tác!';
                    $('#newStatusText').removeClass('text-danger').addClass('text-success').text(statusText);
                    $('#statusWarning').removeClass('alert-warning').addClass('alert-success');
                } else {
                    statusText = 'ĐÃ HỦY';
                    warningText = 'Khi hủy phiếu nhập, tất cả thông tin sẽ được giữ nguyên nhưng không thể khôi phục trạng thái.';
                    $('#newStatusText').removeClass('text-success').addClass('text-danger').text(statusText);
                    $('#statusWarning').removeClass('alert-success').addClass('alert-warning');
                }

                $('#warningText').text(warningText);
                $('#receiptId').val(currentReceiptId);
                $('#actionType').val(currentAction);

                statusModal.show();
            });

            // Save status update
            $('#saveStatus').click(function () {
                const formData = new FormData(document.getElementById('statusForm'));
                const receiptId = formData.get('receipt_id');
                const action = formData.get('action');

                $.ajax({
                    url: `{{ route('home') }}/admin/receipt/${receiptId}/status`,
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: action === 'received' ? 'đã nhận' : 'đã hủy',
                        note: formData.get('note')
                    },
                    success: function (response) {
                        if (response.success) {
                            statusModal.hide();
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra khi cập nhật trạng thái!');
                        }
                    },
                    error: function () {
                        alert('Có lỗi xảy ra khi cập nhật trạng thái!');
                    }
                });
            });

            // Export functionality
            $('#exportReceipts').click(function (e) {
                e.preventDefault();
                const params = new URLSearchParams(window.location.search);
                window.open(`/admin/receipts/export?${params.toString()}`, '_blank');
            });

            // Print functionality
            $('#printReceipts').click(function (e) {
                e.preventDefault();
                window.print();
            });

            // Bulk update status
            $('#bulkUpdateStatus').click(function (e) {
                e.preventDefault();
                alert('Tính năng cập nhật trạng thái hàng loạt sẽ được kích hoạt!');
            });

            // Table row hover effect
            $('#receiptsTable tbody tr').hover(
                function () {
                    $(this).css('transform', 'translateY(-2px)');
                    $(this).css('box-shadow', '0 4px 12px rgba(0,0,0,0.1)');
                },
                function () {
                    $(this).css('transform', '');
                    $(this).css('box-shadow', '');
                }
            );

            // Quick search with debounce
            let searchTimeout;
            $('#search').on('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    $('#filterForm').submit();
                }, 500);
            });

            // Confirm delete with sweet alert (optional enhancement)
            $('.delete-receipt').click(function () {
                const receiptId = $(this).data('receipt-id');
                const receiptCode = $(this).data('receipt-code');

                // You can integrate SweetAlert2 here for better UX
                /*
                Swal.fire({
                    title: 'Xác nhận xóa?',
                    text: `Bạn có chắc muốn xóa phiếu nhập ${receiptCode}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(`#deleteForm${receiptId}`).submit();
                    }
                });
                */
            });

            // Real-time status updates
            function updateReceiptStatus(receiptId, status) {
                const statusBadge = $(`tr[data-receipt-id="${receiptId}"] .badge`);
                const buttons = $(`tr[data-receipt-id="${receiptId}"] .btn-group`);

                statusBadge.removeClass('bg-warning bg-success bg-danger');

                if (status === 'đã nhận') {
                    statusBadge.addClass('bg-success').html('<i class="fas fa-check-circle me-1"></i>Đã nhận');
                    buttons.find('.update-status').remove();
                    buttons.prepend('<button type="button" class="btn btn-secondary" title="Đã nhận hàng" disabled><i class="fas fa-check-double"></i></button>');
                } else if (status === 'đã hủy') {
                    statusBadge.addClass('bg-danger').html('<i class="fas fa-times-circle me-1"></i>Đã hủy');
                    buttons.find('.update-status').remove();
                }
            }
        });
    </script>
@endsection