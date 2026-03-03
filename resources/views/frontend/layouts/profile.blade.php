@extends('frontend.layouts.app')

@section('content')
  {{-- <p>Tên: {{$user->username}}</p>
  <p>Mật khẩu: {{$user->password}}</p>
  <p>Email: {{$user->email}}</p>
  <p>Số điện thoại: {{$user->phone_number}}</p>
  <p>Giới tính: {{$user->sex}}</p>
  <p>Ngày sinh: {{$user->dob}}</p>
  <p>Trạng thái tài khoản: {{$user->status}}</p>
  <p>Đường dẫn ảnh đại diện: {{$user->avatar_url}}</p> --}}

  <div class='container' style='margin-top: 40px;'>
    {{-- @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session('success') }}</strong>
      </div>
    @endif --}}

    <div class="d-flex">
      <div class="card shadow-sm border-0 w-25">
        <div class="text-center p-4 bg-light">
          <img src="{{ $user->avatar_url ? asset('images/' . $user->avatar_url) : asset('images/avatar.jpg') }}"
            alt="avatar" class="rounded-circle mb-3 border border-3 border-white shadow" width="80" height="80">
          <h5 class="card-title mb-0 fw-bold text-primary">{{ $user->username }}</h5>
          <small class="text-muted">Thành viên</small>
        </div>

        <ul class="list-group list-group-flush">
          <a href="{{ route('profile.show') }}"
            class="text-decoration-none text-dark d-flex align-items-center hover-effect">
            <li class="list-group-item py-3 w-100">
              <i class="fas fa-user-circle me-3 text-primary"></i>
              <span>Thông tin tài khoản</span>
            </li>
          </a>
          <a href="{{route('address')}}" class="text-decoration-none text-dark d-flex align-items-center hover-effect">
            <li class="list-group-item py-3 w-100">
              <i class="fas fa-map-marker-alt me-2 text-success"></i>
              <span>Sổ địa chỉ</span>
            </li>
          </a>
          <a href="{{route('order.index')}}"
            class="text-decoration-none text-dark d-flex align-items-center hover-effect">
            <li class="list-group-item py-3 w-100">
              <i class="fas fa-shopping-bag me-3 text-warning"></i>
              <span>Lịch sử đơn hàng</span>
            </li>
          </a>
          <form method="POST" action="{{ route('logout') }}" class="mb-0 w-100 hover-effect">
            <li class="list-group-item py-3">
              @csrf
              <button type="submit"
                class="btn btn-link text-decoration-none text-dark p-0 border-0 w-100 text-start d-flex align-items-center hover-effect">
                <i class="fas fa-sign-out-alt me-3 text-danger"></i>
                <span>Đăng xuất</span>
              </button>
            </li>
          </form>
        </ul>
      </div>

      <style>
        .hover-effect:hover {
          border-radius: 5px;
          padding-left: 10px;
        }

        .hover-effect {
          transition: all 0.3s ease;
        }

        .btn-link:hover {
          background-color: transparent !important;
        }
      </style>

      @yield('profile')
    </div>

  </div>
@endsection