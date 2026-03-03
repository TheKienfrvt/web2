@extends('admin.layouts.app')

@section('title', 'Chi tiết nhà cung cấp')

@section('supplier-active', 'active')

@section('content')
<div class="container-fluid py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.supplier.index') }}" class="text-decoration-none">Nhà cung cấp</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ $supplier->supplier_name }}</li>
    </ol>
  </nav>

  <!-- Thông báo -->
  <div id="alert-container"></div>

  <div class="row">
    <!-- Thông tin nhà cung cấp -->
    <div class="col-md-6 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <h6 class="mb-0">Thông tin nhà cung cấp</h6>
          <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#updateModal">
            <i class="fas fa-edit me-1"></i> Cập nhật
          </button>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-sm-4 fw-bold">Mã nhà cung cấp:</div>
            <div class="col-sm-8">
              <span class="badge bg-secondary">{{ $supplier->supplier_id }}</span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4 fw-bold">Tên nhà cung cấp:</div>
            <div class="col-sm-8">{{ $supplier->supplier_name }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4 fw-bold">Số điện thoại:</div>
            <div class="col-sm-8">
              <a href="tel:{{ $supplier->supplier_phone }}" class="text-decoration-none">
                <i class="fas fa-phone me-1"></i> {{ $supplier->supplier_phone }}
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4 fw-bold">Địa chỉ:</div>
            <div class="col-sm-8">
              <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $supplier->supplier_address }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Thống kê nhanh -->
    <div class="col-md-6 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
          <h6 class="mb-0">Thống kê</h6>
          <a href="{{ route('admin.receipt.create') }}" class="btn btn-sm btn-light">
            <i class="fas fa-edit me-1"></i> Tạo phiếu nhập
          </a>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-6 mb-3">
              <div class="border rounded p-3 bg-light">
                <h3 class="text-primary">{{ count($supplier->receipts) }}</h3>
                <p class="mb-0">Tổng phiếu nhập</p>
              </div>
            </div>
            <div class="col-6 mb-3">
              <div class="border rounded p-3 bg-light">
                <h3 class="text-success" id="completed-receipts">0</h3>
                <p class="mb-0">Đã hoàn thành</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Danh sách phiếu nhập -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Danh sách phiếu nhập đã hợp tác</h6>
      <span class="badge bg-light text-dark">{{ count($supplier->receipts) }} phiếu</span>
    </div>
    <div class="card-body">
      @if(count($supplier->receipts) > 0)
      <div class="table-responsive">
        <table class="table table-striped table-hover" id="receipts-table">
          <thead class="table-dark">
            <tr>
              <th>Mã phiếu</th>
              <th>Ngày đặt</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($supplier->receipts as $receipt)
            <tr>
              <td>
                <span class="fw-bold">{{ $receipt->receipt_id }}</span>
              </td>
              <td>{{ \Carbon\Carbon::parse($receipt->order_date)->format('d/m/Y') }}</td>
              <td>
                @php
                  $statusClass = 'secondary';
                  if($receipt->status == 'đã nhận') $statusClass = 'success';
                  elseif($receipt->status == 'đang chờ') $statusClass = 'warning';
                  elseif($receipt->status == 'đã hủy') $statusClass = 'danger';
                @endphp
                <span class="badge bg-{{ $statusClass }} text-capitalize">
                  {{ $receipt->status }}
                </span>
              </td>
              <td>
                {{-- {{ route('admin.receipt.show', $receipt->receipt_id) }} --}}
                <a href="{{ route('admin.receipt.show', $receipt->receipt_id) }}" 
                   class="btn btn-sm btn-info" 
                   title="Xem chi tiết" 
                   data-bs-toggle="tooltip">
                  <i class="fas fa-eye"></i>
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @else
      <div class="text-center py-4">
        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
        <p class="text-muted">Chưa có phiếu nhập nào từ nhà cung cấp này.</p>
      </div>
      @endif
    </div>
  </div>
</div>

<!-- Modal cập nhật -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Cập nhật thông tin nhà cung cấp</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form id="updateForm" action="{{ route('admin.supplier.update', ['supplierId' => $supplier->supplier_id]) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="supplier_id" value="{{ $supplier->supplier_id }}" hidden>
        <div class="modal-body">
          <div class="mb-3">
            <label for="supplier_name" class="form-label">Tên nhà cung cấp <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="supplier_name" id="supplier_name"
              value="{{ $supplier->supplier_name }}" required>
            <div class="invalid-feedback" id="name-error"></div>
          </div>
          <div class="mb-3">
            <label for="supplier_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="supplier_phone" id="supplier_phone"
              value="{{ $supplier->supplier_phone }}" required>
            <div class="invalid-feedback" id="phone-error"></div>
          </div>
          <div class="mb-3">
            <label for="supplier_address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
            <textarea class="form-control" name="supplier_address" id="supplier_address" rows="3" required>{{ $supplier->supplier_address }}</textarea>
            <div class="invalid-feedback" id="address-error"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary" id="submit-btn">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            Cập nhật
          </button>
        </div>
      </form>
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
  
  .table th {
    border-top: none;
  }
  
  .badge {
    font-size: 0.8em;
  }
  
  #receipts-table tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.03);
  }
</style>
@endsection

@section('js')
<script>
  $(document).ready(function() {
    // Đếm số phiếu nhập đã hoàn thành
    let completedCount = 0;
    $('#receipts-table tbody tr').each(function() {
      if ($(this).find('.badge').text().trim() === 'đã nhận') {
        completedCount++;
      }
    });
    $('#completed-receipts').text(completedCount);
    
    // Khởi tạo tooltip
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Xử lý form cập nhật
    // $('#updateForm').on('submit', function(e) {
    //   e.preventDefault();
      
    //   const form = $(this);
    //   const submitBtn = $('#submit-btn');
    //   const spinner = submitBtn.find('.spinner-border');
    //   const originalText = submitBtn.html();
      
    //   // Hiển thị loading
    //   submitBtn.prop('disabled', true);
    //   spinner.removeClass('d-none');
    //   submitBtn.html('Đang xử lý...');
      
    //   // Xóa thông báo lỗi trước đó
    //   $('.is-invalid').removeClass('is-invalid');
    //   $('.invalid-feedback').text('');
      
    //   // Gửi yêu cầu AJAX
    //   $.ajax({
    //     url: form.attr('action'),
    //     method: form.attr('method'),
    //     data: form.serialize(),
    //     success: function(response) {
    //       // Hiển thị thông báo thành công
    //       showAlert('Cập nhật thông tin thành công!', 'success');
          
    //       // Đóng modal
    //       $('#updateModal').modal('hide');
          
    //       // Cập nhật thông tin trên trang
    //       setTimeout(function() {
    //         location.reload();
    //       }, 1500);
    //     },
    //     error: function(xhr) {
    //       // Xử lý lỗi
    //       if (xhr.status === 422) {
    //         const errors = xhr.responseJSON.errors;
    //         for (const field in errors) {
    //           $(`#${field}`).addClass('is-invalid');
    //           $(`#${field}-error`).text(errors[field][0]);
    //         }
    //         showAlert('Vui lòng kiểm tra lại thông tin!', 'danger');
    //       } else {
    //         showAlert('Đã xảy ra lỗi! Vui lòng thử lại.', 'danger');
    //       }
    //     },
    //     complete: function() {
    //       // Khôi phục trạng thái nút
    //       submitBtn.prop('disabled', false);
    //       spinner.addClass('d-none');
    //       submitBtn.html(originalText);
    //     }
    //   });
    // });
    
    // Hàm hiển thị thông báo
    function showAlert(message, type) {
      const alert = $(`
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
          ${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `);
      $('#alert-container').html(alert);
      
      // Tự động ẩn thông báo sau 5 giây
      setTimeout(function() {
        alert.alert('close');
      }, 5000);
    }
    
    // Xóa thông báo lỗi khi người dùng bắt đầu nhập
    $('input, textarea').on('input', function() {
      if ($(this).hasClass('is-invalid')) {
        $(this).removeClass('is-invalid');
        $(`#${this.id}-error`).text('');
      }
    });
    
    // Đặt lại form khi modal đóng
    $('#updateModal').on('hidden.bs.modal', function() {
      $('#updateForm')[0].reset();
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').text('');
    });
  });
</script>
@endsection