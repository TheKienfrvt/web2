@extends('admin.layouts.app')

@section('title', 'Admin Dashboard - Hệ thống quản trị')
@section('dashboard-active', 'active')
@section('content')
  <!-- Top Bar -->
  <div class="top-bar">
    <div class="search-box">
      {{-- <i class="fas fa-search"></i>
      <input type="text" placeholder="Tìm kiếm..."> --}}
    </div>
    <div class="user-menu">
      {{-- <div class="notification-bell">
        <i class="fas fa-bell"></i>
        <span class="notification-badge">3</span>
      </div> --}}
      <div class="user-info">
        <div class="user-avatar">AD</div>
        <div>
          <div class="fw-bold">Admin User</div>
          <div class="small text-muted">Quản trị viên</div>
        </div>
        <div class="ms-2">
          <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-right-from-bracket"></i>Đăng xuất</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row">
    <div class="col-md-3">
      <div class="stats-card">
        <div class="stats-icon primary">
          <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stats-number">{{ $dashboard['totalOrders'] ?? 0 }}</div>
        <div class="stats-label">Tổng đơn hàng</div>
        {{-- <div class="stats-change positive">
          <i class="fas fa-arrow-up"></i> 100% so với tháng trước
        </div> --}}
      </div>
    </div>
    <div class="col-md-3">
      <div class="stats-card">
        <div class="stats-icon success">
          <i class="fas fa-users"></i>
        </div>
        <div class="stats-number">{{ $dashboard['totalUsers'] ?? 0 }}</div>
        <div class="stats-label">Người dùng</div>
        {{-- <div class="stats-change positive">
          <i class="fas fa-arrow-up"></i> 100% so với tháng trước
        </div> --}}
      </div>
    </div>
    <div class="col-md-3">
      <div class="stats-card">
        <div class="stats-icon warning">
          <i class="fas fa-box"></i>
        </div>
        <div class="stats-number">{{ $dashboard['totalProducts'] ?? 0 }}</div>
        <div class="stats-label">Sản phẩm</div>
        {{-- <div class="stats-change positive">
          <i class="fas fa-arrow-up"></i> 100% so với tháng trước
        </div> --}}
      </div>
    </div>
    <div class="col-md-3">
      <div class="stats-card">
        <div class="stats-icon info">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stats-number">{{ number_format($dashboard['totalRevenue']) ?? 0 }}đ</div>
        <div class="stats-label">Doanh thu</div>
        <div class="stats-change positive">
          <i class="fas fa-arrow-up"></i> {{ (int)$monthlyComparison['growth'] . '%'}} so với tháng trước
        </div>
      </div>
    </div>
  </div>

  <!-- Charts and Tables -->
  <div class="col-md-8">
    <div class="chart-container">
      <div class="chart-header">
        <div class="chart-title">Doanh thu theo tháng</div>
        <div class="btn-group">
          {{-- <button class="btn btn-sm btn-outline-primary active">Tháng</button> --}}
          {{-- <button class="btn btn-sm btn-outline-primary">Quý</button>
          <button class="btn btn-sm btn-outline-primary">Năm</button> --}}
        </div>
      </div>
      <div class="chart-body">
        @if($monthlyRevenue->count() > 0)
          <div class="revenue-chart">
            <div class="chart-bars">
              @php
                $maxRevenue = $monthlyRevenue->max('revenue');
              @endphp
              @foreach($monthlyRevenue->reverse() as $revenue)
                @php
                  $height = $maxRevenue > 0 ? ($revenue->revenue / $maxRevenue) * 100 : 0;
                  $monthName = DateTime::createFromFormat('!m', $revenue->month)->format('M');
                @endphp
                <div class="bar-container justify-content-end">
                  <div class="bar" style="height: {{ $height }}%">
                    <span class="bar-value">{{ number_format($revenue->revenue / 1000000, 1) }}tr</span>
                  </div>
                  <div class="bar-label">{{ $monthName }}/{{ $revenue->year }}</div>
                </div>
              @endforeach
            </div>
          </div>
        @else
          <div class="no-data-placeholder">
            <i class="fas fa-chart-line fa-3x mb-3"></i>
            <p>Chưa có dữ liệu doanh thu</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Recent Orders -->
  <div class="recent-orders">
    <div class="chart-header">
      <div class="chart-title">Đơn hàng gần đây</div>
      <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
    </div>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Mã đơn hàng</th>
            <th>Khách hàng</th>
            <th>Ngày đặt</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($dashboard['orders'] as $order)
            <tr>
              <td>{{ $order->order_id }}</td>
              <td>{{ $order->user->username }}</td>
              <td>{{ $order->order_date }}</td>
              <td>{{ number_format($order->total_amount) . 'đ' }}</td>
              <td class="text-center">
                    @if($order->status == 'chờ xác nhận')
                      <span class="badge bg-warning text-dark">
                        <i class="fas fa-clock me-1"></i>Chờ xác nhận
                      </span>
                    @elseif($order->status == 'đã xác nhận')
                      <span class="badge bg-info">
                        <i class="fas fa-check me-1"></i>Đã xác nhận
                      </span>
                    @elseif($order->status == 'đang giao')
                      <span class="badge bg-primary">
                        <i class="fas fa-shipping-fast me-1"></i>Đang giao
                      </span>
                    @elseif($order->status == 'đã nhận hàng')
                      <span class="badge bg-success">
                        <i class="fas fa-box me-1"></i>Đã nhận hàng
                      </span>
                    @else
                      <span class="badge bg-danger">
                        <i class="fas fa-times me-1"></i>Đã hủy
                      </span>
                    @endif
                  </td>
              <td>
                <a href="{{ route('admin.order.show', $order->order_id) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('css')
  <style>
    /* Chart Containers */
    .chart-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      margin-bottom: 20px;
    }

    .chart-header {
      padding: 20px;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .chart-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: #374151;
    }

    .chart-body {
      padding: 20px;
      min-height: 300px;
    }

    /* Revenue Chart */
    .revenue-chart {
      height: 300px;
      display: flex;
      align-items: flex-end;
      gap: 15px;
    }

    .chart-bars {
      display: flex;
      align-items: flex-end;
      gap: 12px;
      height: 100%;
      width: 100%;
    }

    .bar-container {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      height: 100%;
    }

    .bar {
      background: linear-gradient(180deg, #4f46e5, #7c73e6);
      width: 100%;
      border-radius: 4px 4px 0 0;
      position: relative;
      transition: all 0.3s ease;
      min-height: 20px;
    }

    .bar:hover {
      opacity: 0.8;
      transform: scale(1.05);
    }

    .bar-value {
      position: absolute;
      top: -25px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 0.75rem;
      font-weight: 600;
      color: #374151;
      white-space: nowrap;
    }

    .bar-label {
      margin-top: 10px;
      font-size: 0.8rem;
      color: #6b7280;
      text-align: center;
    }
  </style>
@endsection