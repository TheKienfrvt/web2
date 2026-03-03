@extends('frontend.layouts.profile')

@section('title', 'Địa chỉ - Cửa hàng điện tử')

@section('profile')
  {{-- <div class="container mt-4">
    <div class="row justify-content-center">

    </div>
  </div> --}}
  <div class="card w-75">
    <!-- Danh sách địa chỉ hiện có -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white">
        <h4 class="mb-0 text-success">
          <i class="fas fa-map-marker-alt me-2"></i>Địa chỉ của tôi
        </h4>
      </div>
      <div class="card-body">
        @if ($addresses->count() > 0)
          <ul class="list-group list-group-flush">
            @foreach ($addresses as $address)
              <li class="list-group-item px-0">
                {{-- {{ route('addresses.update', $address) }} --}}
                <form action="{{ route('address.update', $address) }}" method="POST" class="row g-3 align-items-center">
                  @csrf
                  @method('PUT')

                  <div class="col-md-8">
                    <input type="text" id="address-{{ $address->address_id }}" name="address" value="{{ $address->address }}"
                      class="form-control form-control-lg" required>
                  </div>

                  <div class="col-md-4">
                    <div class="d-flex gap-2">
                      <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="fas fa-save me-1"></i>Cập nhật
                      </button>
                      <a href="{{ route('address.delete', $address) }}" class="btn btn-danger btn-sm"
                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $address->address_id }}').submit();">
                        <i class="fas fa-trash me-1"></i>Xóa
                      </a>
                    </div>
                  </div>
                </form>

                <!-- Form xóa ẩn -->
                <form id="delete-form-{{ $address->address_id }}" action="{{ route('address.delete', $address) }}"
                  method="POST" class="d-none">
                  @csrf
                  @method('DELETE')
                </form>
              </li>
            @endforeach
          </ul>
        @else
          <div class="text-center py-4">
            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Chưa có địa chỉ nào</h5>
            <p class="text-muted">Hãy thêm địa chỉ đầu tiên của bạn</p>
          </div>
        @endif
      </div>
    </div>

    <!-- Form thêm địa chỉ mới -->
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h5 class="mb-0 text-success">
          <i class="fas fa-plus-circle me-2"></i>Thêm địa chỉ mới
        </h5>
      </div>
      <div class="card-body">
        {{-- {{ route('address.store') }} --}}
        <form action="{{ route('address.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="new-address" class="form-label fw-semibold">Địa chỉ</label>
            <input type="text" id="new-address" name="address"
              class="form-control form-control-lg @error('address') is-invalid @enderror"
              placeholder="Nhập địa chỉ đầy đủ..." value="{{ old('address') }}" required>
            @error('address')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- <div class="mb-3 form-check">
            <input type="checkbox" name="is_default" id="is_default" value="1" class="form-check-input" {{
              old('is_default') ? 'checked' : '' }}>
            <label class="form-check-label" for="is_default">
              Đặt làm địa chỉ mặc định
            </label>
          </div> --}}

          <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg px-4">Thêm mới</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection