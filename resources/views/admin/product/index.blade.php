@extends('admin.layouts.app')

@section('title', 'Quản lý Sản phẩm')
@section('product-active', 'active')
@section("content")
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Quản lý Sản phẩm</h1>
            <a href="{{ route('admin.product.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Thêm sản phẩm mới
            </a>
        </div>

        <!-- Filter Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Bộ lọc & Tìm kiếm</h6>
                <span class="badge bg-info">Tổng: {{ $products->total() }} người dùng</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.product.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Tên sản phẩm...">
                    </div>
                    <div class="col-md-2">
                        <label for="category" class="form-label">Danh mục</label>
                        <select class="form-control" id="category" name="category_id">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}" {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Tất cả</option>
                            <option value="hiện" {{ request('status') == 'hiện' ? 'selected' : '' }}>Hiện</option>
                            <option value="ẩn" {{ request('status') == 'ẩn' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="stock" class="form-label">Tồn kho</label>
                        <select class="form-control" id="stock" name="stock">
                            <option value="">Tất cả</option>
                            <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i> Lọc
                        </button>
                        <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Danh sách sản phẩm</h6>
                {{-- <div class="">Tổng: {{ $products->total() }} sản phẩm</div> --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="productsTable" width="100%" cellspacing="0">
                        <thead class="table-dark">
                            <tr>
                                <th width="60">ID</th>
                                <th width="80">Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th width="120">Danh mục</th>
                                <th width="100">Giá</th>
                                <th width="80">Tồn kho</th>
                                <th width="80">Trạng thái</th>
                                <th width="150" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td class="text-center text-primary"><strong>#{{ $product->product_id }}</strong></td>
                                    <td class="text-center">
                                        <img src="{{ asset('/images/' . ($product->image_url ?? "no image available.jpg"))}}"
                                            alt="{{ $product->product_name }}" class="img-thumbnail"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="">{{ $product->product_name }}</div>
                                        {{-- <small class="text-muted">SKU: PROD-{{ str_pad($product->product_id, 6, '0',
                                            STR_PAD_LEFT) }}</small> --}}
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-info text-dark">{{ $product->category->category_name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-end">
                                        <strong class="">{{ number_format($product->price, 0, ',', '.') }}đ</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($product->stock > 0)
                                            <span class="badge bg-success">{{ $product->stock }}</span>
                                        @else
                                            <span class="badge bg-danger">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($product->status == 'hiện')
                                            <span class="badge bg-success">Hiện</span>
                                        @else
                                            <span class="badge bg-secondary">Ẩn</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.product.show', $product->product_id) }}" class="btn btn-info" title="Xem chi tiết" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.product.edit', $product->product_id) }}" class="btn btn-warning" title="Sửa" data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" title="Xóa sản phẩm"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->product_id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $product->product_id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Xác nhận xóa</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Bạn có chắc chắn muốn xóa sản phẩm
                                                            <strong>"{{ $product->product_name }}"</strong>?
                                                        </p>
                                                        <p class="text-danger"><small>Hành động này không thể hoàn tác!</small>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Hủy</button>
                                                        <form action="{{ route('admin.product.delete', $product->product_id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Xóa</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-box-open fa-3x mb-3"></i>
                                            <h5>Không có sản phẩm nào</h5>
                                            <p>Hãy thêm sản phẩm mới để bắt đầu</p>
                                            <a href="{{ route('admin.product.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} của {{
                    $products->total() }} sản phẩm
                            </div>
                            <nav>
                                {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                @endif
                {{-- {{ $products->links('pagination::bootstrap-4') }} --}}
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

        .img-thumbnail {
            border-radius: 8px;
            border: 2px solid #e3e6f0;
        }

        .btn-group .btn {
            border-radius: 4px !important;
            margin: 0 2px;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .card-header h6 {
            margin: 0;
        }

        /* Hover effects */
        #productsTable tbody tr {
            transition: all 0.3s ease;
        }

        #productsTable tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

        /* Responsive */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }

            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            .card-body {
                padding: 1rem;
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
            $('#category, #status, #stock').on('change', function () {
                $(this).closest('form').trigger('submit');
            });

            // Search debounce
            let searchTimeout;
            $('#search').on('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    $(this).closest('form').trigger('submit');
                }, 500);
            });

            // Bulk actions
            $('#selectAll').on('change', function () {
                $('.product-checkbox').prop('checked', this.checked);
            });

            // Status change confirmation
            $('.status-btn').on('click', function (e) {
                const productName = $(this).data('product-name');
                const newStatus = $(this).data('status') === 'hiện' ? 'ẩn' : 'hiện';
                const statusText = newStatus === 'hiện' ? 'hiển thị' : 'ẩn';

                if (!confirm(`Bạn có chắc muốn ${statusText} sản phẩm "${productName}"?`)) {
                    e.preventDefault();
                }
            });

            // Table row hover effects
            $('#productsTable tbody tr').hover(
                function () {
                    $(this).css({
                        'transform': 'translateY(-2px)',
                        'box-shadow': '0 4px 12px rgba(0,0,0,0.1)',
                        'background-color': '#f8f9fa'
                    });
                },
                function () {
                    $(this).css({
                        'transform': '',
                        'box-shadow': '',
                        'background-color': ''
                    });
                }
            );
        });
    </script>
@endsection