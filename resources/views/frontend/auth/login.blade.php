@extends('frontend.layouts.app')

@section('title', 'Đăng nhập - Cửa hàng điện tử')

@section('content')
  <div class="container">
    @if(session('message'))
      <div class="alert alert-dismissible fade show" role="alert">
        <strong>{{ session('message') }}</strong>
      </div>
    @endif
    @include('frontend.components.auth.login-form')
  </div>
@endsection