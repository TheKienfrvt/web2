@extends('admin.layouts.app')
@section('inventory-active', 'active')

@section('title', 'Quản lý Kho hàng')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Quản lý Kho hàng</h1>
            <div class="d-flex">
                <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#adjustModal">
                    <i class="fas fa-sliders-h fa-sm me-1"></i> Điều chỉnh kho hàng
                </button>
                <a href="{{ route('admin.receipt.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tạo phiếu nhập mới
                </a>
                {{-- <button class="btn btn-info btn-sm" id="exportInventory">
                    <i class="fas fa-file-excel fa-sm me-1"></i> Xuất Excel
                </button> --}}
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Tổng nhập hàng
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalImport ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
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
                                    Tổng xuất hàng
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalExport ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Điều chỉnh
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAdjustment ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
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
                                    Tổng biến động
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTransactions ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                <span class="badge bg-info">Tổng: {{ $inventorys->total() }} bản ghi</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.inventory.index') }}" method="GET" id="filterForm" class="row g-3">
                    <div class="col-md-3">
                        <label for="product_id" class="form-label">Sản phẩm</label>
                        <select class="form-control select2" id="product_id" name="product_id">
                            <option value="">Tất cả sản phẩm</option>
                            @foreach($products as $product)
                                <option value="{{ $product->product_id }}" {{ request('product_id') == $product->product_id ? 'selected' : '' }}>
                                    {{ $product->product_name }} (ID: {{ $product->product_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="type" class="form-label">Loại biến động</label>
                        <select class="form-control" id="type" name="type">
                            <option value="">Tất cả loại</option>
                            <option value="nhập hàng" {{ request('type') == 'nhập hàng' ? 'selected' : '' }}>Nhập hàng
                            </option>
                            <option value="xuất hàng" {{ request('type') == 'xuất hàng' ? 'selected' : '' }}>Xuất hàng
                            </option>
                            <option value="điều chỉnh" {{ request('type') == 'điều chỉnh' ? 'selected' : '' }}>Điều chỉnh
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="quantity_type" class="form-label">Loại số lượng</label>
                        <select class="form-control" id="quantity_type" name="quantity_type">
                            <option value="">Tất cả</option>
                            <option value="positive" {{ request('quantity_type') == 'positive' ? 'selected' : '' }}>Tăng
                                (Dương)</option>
                            <option value="negative" {{ request('quantity_type') == 'negative' ? 'selected' : '' }}>Giảm (Âm)
                            </option>
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
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-redo"></i> Reset bộ lọc
                    </a>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Lịch sử biến động kho hàng</h6>
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-cog"></i> Tùy chọn
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" id="bulkActions"><i class="fas fa-tasks"></i> Hành động hàng
                                loạt</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#" id="printReport"><i class="fas fa-print"></i> In báo cáo</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="inventoryTable" width="100%" cellspacing="0">
                        <thead class="table-dark">
                            <tr>
                                <th width="80">Mã biến động</th>
                                <th>Sản phẩm</th>
                                <th width="100">Số lượng</th>
                                <th width="120">Loại biến động</th>
                                <th width="120">Tham chiếu</th>
                                <th width="150">Thời gian</th>
                                {{-- <th width="120" class="text-center">Thao tác</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventorys as $inventory)
                                <tr data-inventory-id="{{ $inventory->inventory_id }}">
                                    <td class="text-center">
                                        <strong class="text-primary">#{{ $inventory->inventory_id }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/' . ($inventory->product->image_url ?? "no image available.jpg")) }}"
                                                alt="{{ $inventory->product->product_name ?? 'N/A' }}"
                                                class="product-thumb me-2" onerror="this.src='/images/default-product.jpg'">
                                            <div>
                                                <div class="text-dark">{{ $inventory->product->product_name ?? 'N/A' }}</div>
                                                {{-- <small class="text-muted">ID: {{ $inventory->product_id }}</small> --}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($inventory->quantity > 0)
                                            <span class="badge bg-success quantity-badge">
                                                +{{ $inventory->quantity }}
                                            </span>
                                        @elseif($inventory->quantity < 0)
                                            <span class="badge bg-danger quantity-badge">
                                                {{ $inventory->quantity }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary quantity-badge">
                                                {{ $inventory->quantity }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($inventory->type == 'nhập hàng')
                                            <span class="badge bg-success">
                                                <i class="fas fa-arrow-down me-1"></i>Nhập hàng
                                            </span>
                                        @elseif($inventory->type == 'xuất hàng')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-arrow-up me-1"></i>Xuất hàng
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-adjust me-1"></i>Điều chỉnh
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($inventory->reference)
                                            <span class="text-primary">{{ $inventory->reference }}</span>
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="text-dark">{{ $inventory->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $inventory->created_at->format('H:i:s') }}</small>
                                    </td>
                                    {{-- <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-info view-details" title="Xem chi tiết"
                                                data-bs-toggle="tooltip" data-inventory-id="{{ $inventory->inventory_id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($inventory->type == 'điều chỉnh')
                                            <button type="button" class="btn btn-warning edit-adjustment" title="Sửa điều chỉnh"
                                                data-bs-toggle="tooltip" data-inventory-id="{{ $inventory->inventory_id }}"
                                                data-product-name="{{ $inventory->product->product_name ?? 'N/A' }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif
                                            <button type="button" class="btn btn-danger delete-record" title="Xóa bản ghi"
                                                data-bs-toggle="tooltip" data-inventory-id="{{ $inventory->inventory_id }}"
                                                data-product-name="{{ $inventory->product->product_name ?? 'N/A' }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-warehouse fa-3x mb-3"></i>
                                            <h5>Không có biến động kho hàng</h5>
                                            <p>Chưa có biến động nào được ghi nhận</p>
                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#importModal">
                                                <i class="fas fa-plus me-2"></i>Thêm biến động đầu tiên
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($inventorys->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị {{ $inventorys->firstItem() ?? 0 }} - {{ $inventorys->lastItem() ?? 0 }} của
                            {{ $inventorys->total() }} bản ghi
                        </div>
                        <nav>
                            {{ $inventorys->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Adjust Modal -->
    <div class="modal fade" id="adjustModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Điều chỉnh tồn kho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="adjustForm" action="{{ route('admin.inventory.adjust') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="adjust_product_id" class="form-label">Sản phẩm <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="adjust_product_id" name="product_id" required>
                                <option value="">Chọn sản phẩm</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->product_id }}" data-current-stock="{{ $product->stock ?? 0 }}">
                                        {{ $product->product_name }} (Hiện có: {{ $product->stock ?? 0 }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="adjust_type" class="form-label">Loại điều chỉnh <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="adjust_type" name="adjust_type" required>
                                <option value="increase">Tăng tồn kho</option>
                                <option value="decrease">Giảm tồn kho</option>
                                <option value="set">Đặt lại số lượng</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="adjust_quantity" class="form-label">Số lượng <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="adjust_quantity" name="quantity" min="0" required
                                placeholder="Nhập số lượng">
                        </div>
                        <div class="mb-3">
                            <label for="adjust_reason" class="form-label">Lý do điều chỉnh</label>
                            <textarea class="form-control" id="adjust_reason" name="reason" rows="3"
                                placeholder="Lý do điều chỉnh tồn kho..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning">Điều chỉnh</button>
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

        .product-thumb {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }

        .quantity-badge {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.5em 0.8em;
            min-width: 70px;
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
        #inventoryTable tbody tr {
            transition: all 0.3s ease;
        }

        #inventoryTable tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Status badges */
        .badge.bg-success {
            background-color: #1cc88a !important;
        }

        .badge.bg-danger {
            background-color: #e74a3b !important;
        }

        .badge.bg-warning {
            background-color: #f6c23e !important;
            color: #212529 !important;
        }

        .badge.bg-secondary {
            background-color: #858796 !important;
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

            .card-body {
                padding: 1rem;
            }

            .stats-card .card-body {
                padding: 0.75rem;
            }

            .modal-dialog {
                margin: 0.5rem;
            }
        }
    </style>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('.select2').select({
                placeholder: "Chọn sản phẩm...",
                allowClear: true,
                width: '100%'
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Auto submit filter form when select changes
            $('#type, #quantity_type').change(function () {
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

            // View details
            $('.view-details').click(function () {
                const inventoryId = $(this).data('inventory-id');

                // In real implementation, you would fetch details via AJAX
                alert(`Xem chi tiết biến động #${inventoryId}`);
            });

            // Edit adjustment
            $('.edit-adjustment').click(function () {
                const inventoryId = $(this).data('inventory-id');
                const productName = $(this).data('product-name');

                if (confirm(`Bạn có muốn sửa điều chỉnh cho sản phẩm "${productName}"?`)) {
                    // In real implementation, you would open edit modal
                    alert(`Sửa điều chỉnh #${inventoryId}`);
                }
            });

            // Delete record
            // $('.delete-record').click(function() {
            //     const inventoryId = $(this).data('inventory-id');
            //     const productName = $(this).data('product-name');

            //     if (confirm(`Bạn có chắc muốn xóa bản ghi biến động cho sản phẩm "${productName}"?\n\nHành động này không thể hoàn tác!`)) {
            //         // In real implementation, you would make an AJAX request
            //         $.ajax({
            //             url: `/admin/inventory/${inventoryId}`,
            //             type: 'DELETE',
            //             data: {
            //                 _token: '{{ csrf_token() }}'
            //             },
            //             success: function(response) {
            //                 if (response.success) {
            //                     location.reload();
            //                 } else {
            //                     alert('Có lỗi xảy ra khi xóa bản ghi!');
            //                 }
            //             },
            //             error: function() {
            //                 alert('Có lỗi xảy ra khi xóa bản ghi!');
            //             }
            //         });
            //     }
            // });

            // Export functionality
            // $('#exportInventory').click(function(e) {
            //     e.preventDefault();
            //     const params = new URLSearchParams(window.location.search);
            //     window.open(`/admin/inventory/export?${params.toString()}`, '_blank');
            // });

            // Print report
            // $('#printReport').click(function(e) {
            //     e.preventDefault();
            //     window.print();
            // });

            // Bulk actions
            // $('#bulkActions').click(function(e) {
            //     e.preventDefault();
            //     alert('Tính năng hành động hàng loạt sẽ được kích hoạt!');
            // });

            // Adjust modal logic
            $('#adjust_type').change(function () {
                const type = $(this).val();
                const currentStock = $('#adjust_product_id option:selected').data('current-stock') || 0;

                if (type === 'set') {
                    $('#adjust_quantity').attr('min', '0');
                    $('#adjust_quantity').attr('placeholder', `Số lượng mới (hiện có: ${currentStock})`);
                } else if (type === 'increase') {
                    $('#adjust_quantity').attr('min', '1');
                    $('#adjust_quantity').attr('placeholder', 'Số lượng tăng thêm');
                } else {
                    $('#adjust_quantity').attr('min', '1');
                    $('#adjust_quantity').attr('max', currentStock);
                    $('#adjust_quantity').attr('placeholder', `Số lượng giảm (tối đa: ${currentStock})`);
                }
            });

            $('#adjust_product_id').change(function () {
                $('#adjust_type').trigger('change');
            });


            $('#adjustForm').submit(function (e) {
                const quantity = $('#adjust_quantity').val();
                const type = $('#adjust_type').val();
                const currentStock = $('#adjust_product_id option:selected').data('current-stock') || 0;

                if (quantity <= 0) {
                    e.preventDefault();
                    alert('Số lượng phải lớn hơn 0!');
                    $('#adjust_quantity').focus();
                    return;
                }

                if (type === 'decrease' && quantity > currentStock) {
                    e.preventDefault();
                    alert(`Số lượng giảm không được vượt quá tồn kho hiện tại (${currentStock})!`);
                    $('#adjust_quantity').focus();
                }
            });

            // Table row hover effect
            $('#inventoryTable tbody tr').hover(
                function () {
                    $(this).css('transform', 'translateY(-2px)');
                    $(this).css('box-shadow', '0 4px 12px rgba(0,0,0,0.1)');
                },
                function () {
                    $(this).css('transform', '');
                    $(this).css('box-shadow', '');
                }
            );

            // Real-time stock update preview
            $('#adjust_quantity, #adjust_type').on('input change', function () {
                const quantity = parseInt($('#adjust_quantity').val()) || 0;
                const type = $('#adjust_type').val();
                const currentStock = $('#adjust_product_id option:selected').data('current-stock') || 0;
                let newStock = currentStock;

                if (type === 'increase') {
                    newStock = currentStock + quantity;
                } else if (type === 'decrease') {
                    newStock = currentStock - quantity;
                } else if (type === 'set') {
                    newStock = quantity;
                }

                $('#stockPreview').remove();
                if ($('#adjust_product_id').val()) {
                    $('#adjust_quantity').after(`<div id="stockPreview" class="form-text">Tồn kho sau điều chỉnh: <strong>${newStock}</strong></div>`);
                }
            });
        });
    </script>
@endsection