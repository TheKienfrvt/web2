@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="login-register">
      <h1 class="login-register__title">ĐĂNG KÝ</h1>
      <form action="{{ route('user.store') }}" method="post" class="form-register">
        <div class="login-register__inform">
          {{-- Cross-Site Request Forgery (Tấn công giả mạo yêu cầu) --}}
          @csrf
          <div class="block">
            <label for="username">Tên tài khoản:</label>
            <input type="text" name="username" id="username" placeholder="Tên tài khoản">
            @error('username')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="block">
            <label for="password">Mật khẩu:</label>
            <input type="password" name="password" id="password" placeholder="Mật khẩu">
            @error('password')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="block">
            <label for="repassword">Nhập lại mật khẩu:</label>
            <input type="password" name="repassword" id="repassword" placeholder="Nhập lại mật khẩu">
          </div>

          <div class="block">
            <label for="email">Địa chỉ Email</label>
            <input type="email" name="email" id="email" placeholder="Nhập email">
            @error('email')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="block">
            <label for="phone_number">Số điện thoại:</label>
            <input type="text" name="phone_number" id="phone_number" placeholder="Số điện thoại">
          </div>

          <div class="block">
            <label for="sex">Giới tính:</label>
            <select name="sex" id="sex">
              <option value="">-- Chọn giới tính --</option>
              <option value="Nam">Nam</option>
              <option value="Nữ">Nữ</option>
              {{-- <option value="Khác">Khác</option> --}}
            </select>
          </div>

          <div class="block">
            <label for="dob">Ngày sinh:</label>
            <input type="date" name="dob" id="dob">
            {{-- value="{{ old('dob') }} --}}
          </div>

          <div class="block submit">
            <button type="submit" class="login-register__submit">Đăng ký</button>
          </div>

          <div class="block">
            <span>Đã có tài khoản?</span>
            <a href="./index.php?controller=user&action=login" class="login__link">Đăng nhập</a>
          </div>

        </div>
      </form>
    </div>
  </div>
@endsection