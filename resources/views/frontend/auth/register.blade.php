@extends('frontend.layouts.app')

@section('title', 'Đăng ký - Cửa hàng điện tử')

@section('content')
  <div class="container">
    <div class="login-register">
      <h1 class="login-register__title">ĐĂNG KÝ</h1>
      <form action="{{ route('register.submit') }}" method="post" class="form-register">
        {{-- Cross-Site Request Forgery (Tấn công giả mạo yêu cầu) --}}
        @csrf
        <div class="login-register__inform">
          <div class="block">
            <label for="username">Tên tài khoản:</label>
            <input type="text" name="username" id="username" placeholder="Tên tài khoản" value="{{ old('username') }}">
            @error('username')
              <div class="text-danger p-2">{{ $message }}</div>
            @enderror
          </div>

          <div class="block">
            <label for="password">Mật khẩu:</label>
            <input type="password" name="password" id="password" placeholder="Mật khẩu">
            @error('password')
              <div class="text-danger p-2">{{ $message }}</div>
            @enderror
          </div>

          <div class="block">
            <label for="repassword">Nhập lại mật khẩu:</label>
            <input type="password" name="repassword" id="repassword" placeholder="Nhập lại mật khẩu">
            @error('repassword')
              <div class="text-danger p-2">{{ $message }}</div>
            @enderror
          </div>

          <div class="block">
            <label for="email">Địa chỉ Email</label>
            <input type="email" name="email" id="email" placeholder="Nhập email" value="{{ old('email') }}">
            @error('email')
              <div class="text-danger p-2">{{ $message }}</div>
            @enderror
          </div>

          <div class="block">
            <label for="phone_number">Số điện thoại:</label>
            <input type="text" name="phone_number" id="phone_number" placeholder="Số điện thoại"
              value="{{ old('phone_number') }}">
            @error('phone_number')
              <div class="text-danger p-2">{{ $message }}</div>
            @enderror
          </div>

          <div class=" block">
            <label for="sex">Giới tính:</label>
            <select name="sex" id="sex">
              @if (old('sex') == 'Nam')
                <option value="Nam" selected>Nam</option>
                <option value="Nữ">Nữ</option>
              @elseif (old('sex') == 'Nữ')
                <option value="Nữ" selected>Nữ</option>
                <option value="Nam">Nam</option>
              @else
                <option value="">-- Chọn giới tính --</option>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
              @endif
              {{-- <option value="Khác">Khác</option> --}}
            </select>
          </div>

          <div class="block">
            <label for="dob">Ngày sinh:</label>
            <input type="date" name="dob" id="dob" value="{{ old('dob') }}">
          </div>

          <div class="block submit">
            <button type="submit" class="login-register__submit" @if($isAdmin) disabled @endif>Đăng ký</button>
          </div>

          <div class="block">
            <span>Đã có tài khoản?</span>
            <a href="{{route('login')}}" class="login__link">Đăng nhập</a>
          </div>

        </div>
      </form>
    </div>
  </div>
@endsection