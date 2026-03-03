@extends('frontend.layouts.profile')

@section('title', 'Trang cá nhân - Cửa hàng điện tử')

@section('profile')
  <div class="card w-75">
    <div class="card-header bg-white text-primary">
      <h5 class="mb-0"><i class="fas fa-user-circle me-3 text-primary"></i>Thông tin tài khoản</h5>
    </div>
    <form action="{{ route('profile.update', ['user_id' => $user->user_id]) }}" method="POST" id="myForm">
      @csrf
      @method('PUT')

      <div class="card-body">
        <!-- Tên tài khoản -->
        <div class="mb-3">
          <label for="username" class="form-label">Tên tài khoản <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username"
            value="{{ old('username', $user->username) }}" required>
          @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
            value="{{ old('email', $user->email) }}" required>
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Số điện thoại -->
        <div class="mb-3">
          <label for="phone_number" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
            name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
          @error('phone_number')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Giới tính -->
        <div class="mb-3">
          <label class="form-label">Giới tính <span class="text-danger">*</span></label>
          <div>
            <div class="form-check form-check-inline">
              <input class="form-check-input @error('sex') is-invalid @enderror" type="radio" name="sex" id="sexMale"
                value="nam" {{ old('sex', $user->sex) == 'nam' ? 'checked' : '' }}>
              <label class="form-check-label" for="sexMale">Nam</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input @error('sex') is-invalid @enderror" type="radio" name="sex" id="sexFemale"
                value="nữ" {{ old('sex', $user->sex) == 'nữ' ? 'checked' : '' }}>
              <label class="form-check-label" for="sexFemale">Nữ</label>
            </div>
          </div>
          @error('sex')
            <div class="text-danger small">{{ $message }}</div>
          @enderror
        </div>

        <!-- Ngày sinh -->
        <div class="mb-3">
          <label for="dob" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
          <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob" name="dob"
            value="{{ old('dob', $user->dob ? \Carbon\Carbon::parse($user->dob)->format('Y-m-d') : '') }}">
          @error('dob')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Nút submit -->
        <div class="mt-4 text-center">
          <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtnUser">
            <i class="fas fa-save me-2"></i>LƯU THAY ĐỔI
          </button>
          <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-lg px-5 ms-2">
            <i class="fas fa-undo me-2"></i>HỦY BỎ
          </a>
        </div>
      </div>
    </form>
  </div>
@endsection