@extends('admin.layouts.app')

@section('title', $product->product_name . ' - Chi tiết sản phẩm')
@section('product-active', 'active')

@section('content')
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}" class="text-decoration-none">Quản
                        lý sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->product_name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Thông tin chính & Ảnh -->
            <div class="col-md-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($product->image_url)
                                <img src="{{ asset('images/' . $product->image_url) }}" alt="{{ $product->product_name }}"
                                    class="img-fluid rounded" style="max-height: 400px; object-fit: contain;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                    style="height: 300px;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-image fa-3x mb-3"></i>
                                        <p>Chưa có hình ảnh</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <h3 class="mb-2">{{ $product->product_name }}</h3>
                        <div class="mb-3">
                            <span class="badge bg-primary fs-6">{{ $product->category->category_name ?? 'N/A' }}</span>
                            <span class="badge bg-{{ $product->status == 'hiện' ? 'success' : 'secondary' }} fs-6 ms-2">
                                {{ $product->status }}
                            </span>
                        </div>

                        <h4 class="text-success mb-3">{{ number_format($product->price, 0, ',', '.') }} đ</h4>

                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border rounded p-3 bg-light">
                                    <h5 class="text-primary mb-1">{{ $product->stock }}</h5>
                                    <small class="text-muted">Tồn kho</small>
                                </div>
                            </div>
                            {{-- <div class="col-6">
                                <div class="border rounded p-3 bg-light">
                                    <h5 class="text-info mb-1" id="sold-count">0</h5>
                                    <small class="text-muted">Đã bán</small>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin chi tiết -->
            <div class="col-md-7">
                <!-- Thông tin cơ bản -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin cơ bản</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%"><strong>Mã sản phẩm:</strong></td>
                                        <td>
                                            <span class="badge bg-secondary">#{{ $product->product_id }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tên sản phẩm:</strong></td>
                                        <td>{{ $product->product_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Danh mục:</strong></td>
                                        <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%"><strong>Giá bán:</strong></td>
                                        <td class="text-success fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tồn kho:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'warning' }} fs-6">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Trạng thái:</strong></td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $product->status == 'hiện' ? 'success' : 'secondary' }}">
                                                {{ $product->status }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Thông tin hệ thống -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <small class="text-muted">
                                <div><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</div>
                                <div><strong>Cập nhật lần cuối:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Thông tin chi tiết theo danh mục -->
                @if($productDetail)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>
                                Thông số kỹ thuật - {{ $product->category->category_name }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($detailAttributes as $attribute => $label)
                                    @if(!empty($productDetail->$attribute))
                                        <div class="col-md-6 mb-3">
                                            <strong>{{ $label }}:</strong>
                                            <div class="mt-1">{{ $productDetail->$attribute }}</div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Đơn hàng đã bán (nếu có) -->
                {{-- <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Đơn hàng đã bán</h6>
                    </div>
                    <div class="card-body">
                        @if(isset($orders) && $orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Ngày đặt</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="#" class="text-decoration-none">#{{ $order->order_id }}</a>
                                        </td>
                                        <td>{{ $order->order_date->format('d/m/Y') }}</td>
                                        <td>{{ $order->pivot->quantity }}</td>
                                        <td>{{ number_format($order->pivot->quantity * $order->pivot->price, 0, ',', '.') }}
                                            đ</td>
                                        <td>
                                            @php
                                            $statusClass = [
                                            'pending' => 'warning',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                            ][$order->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có đơn hàng nào bán sản phẩm này</p>
                        </div>
                        @endif
                    </div>
                </div> --}}
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

        .badge {
            font-size: 0.8em;
        }

        .img-fluid {
            border-radius: 8px;
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Có thể thêm các tính năng JavaScript ở đây nếu cần
            // Ví dụ: tính tổng số lượng đã bán
            updateSoldCount();

            function updateSoldCount() {
                // Giả sử có API endpoint để lấy số lượng đã bán
                // Đây chỉ là ví dụ, bạn cần thay thế bằng API thực tế
                const soldCount = {{ $orders->sum('pivot.quantity') ?? 0 }};
                document.getElementById('sold-count').textContent = soldCount;
            }
        });
    </script>
@endsection