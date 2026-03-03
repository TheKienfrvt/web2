<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Đăng nhập quản trị viên</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  {{-- jquery --}}
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  {{-- Bootstrap JavaScript Bundle --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  {{-- limk css --}}
  <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
  <link rel="icon" type="image/png" href="{{asset('images/logo.png')}}">
  {{-- link js --}}
  <script src=" {{ asset('js/admin/global-functions.js') }}"></script>

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      width: 350px;
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 25px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #555;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #45a049;
    }

    .error {
      color: red;
      margin-bottom: 15px;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <h1>Đăng Nhập Hệ Thống</h1>

    <form method="post" action="{{ route('admin.login') }}">
      @csrf
      <div class="form-group">
        <label for="email">Email đăng nhập</label>
        <input type="email" id="email" name="email" required>
      </div>
      @error('email')
        <div class="text-danger p-2">{{ $message }}</div>
      @enderror

      <div class="form-group">
        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" required>
      </div>
      @error('password')
        <div class="text-danger p-2">{{ $message }}</div>
      @enderror

      <button type="submit">Đăng Nhập</button>
    </form>
  </div>
  <script>
    // Hiển thị thông báo từ session
    @if(session('success'))
      showAlert('success', '{{ session('success') }}');
    @endif

    @if(session('error'))
      showAlert('error', '{{ session('error') }}');
    @endif

    @if($errors->any())
      @foreach($errors->all() as $error)
        showAlert('error', '{{ $error }}');
      @endforeach
    @endif
  </script>
</body>

</html>