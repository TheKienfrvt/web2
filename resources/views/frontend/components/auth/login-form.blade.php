<div class="login-register">
  <h1 class="login-register__title">ĐĂNG NHẬP</h1>
  <form action="{{route('login.submit')}}" method="post">
    {{-- Cross-Site Request Forgery (Tấn công giả mạo yêu cầu) --}}
    @csrf
    <div class="login-register__inform">
      <div class="block">
        <label for="email">Email đăng nhập:</label>
        <input type="email" name="email" placeholder="Email đăng nhập" value="{{old('email')}}">
      </div>
      <div class="block">
        <label for="password">Mật khẩu:</label>
        <input type="password" name="password" placeholder="Mật khẩu" value="{{old('password')}}">
      </div>
      <div class=" block submit">
        <button class="login-register__submit" @if($isAdmin) disabled @endif>Đăng nhập</button>
        @error('error')
          <div class="text-danger p-2">{{ $message }}</div>
        @enderror
      </div>
      <div class="block">
        <span>Chưa có tài khoản?</span>
        <a href="{{route('register')}}" class="register__link">Đăng ký</a>
      </div>
    </div>
  </form>
</div>