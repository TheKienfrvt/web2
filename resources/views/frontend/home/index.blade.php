@extends('frontend.layouts.app')

@section('title', 'Trang chủ - Cửa hàng điện tử')

@section('content')
  <div class="container product">
    @foreach ($categories as $category)
      <div class="home-page product-container">
        <div class="home-page header-product-bar">
          <h2 class="home-page name-product-bar">{{ $category->category_name }} bán chạy</h2>
          <div class="home-page line"></div>
          <a href="{{ route('product.indexByCategory', ['category_id' => $category->category_id]) }}"
            class="home-page see-more">
            xem tất cả <i class="fa fa-angle-double-right"></i>
          </a>
        </div>
        <button class="home-page arrow left-arrow" onclick="scrollLeftt(this)">
          <i class="fa-solid fa-arrow-left"></i>
        </button>
        <div class="home-page product__bar productWrapper">
          @foreach ($category->products as $product)
            @php
              if ($product->status == 'đã xóa') {
                continue;
              }
            @endphp
            <div class="product__item__card">
              <a href="{{ route('product.show', ['productId' => $product->product_id]) }}">
                <div class="product__item__card__img">
                  <img src="{{ asset("images/" . ($product->image_url ?? "no image available.jpg")) }}" alt="{{ $product->product_name }}">
                </div>
                <div class="product__item__card__content">
                  <h3 class="product__item__name">{{ $product->product_name }}</h3>
                  <p class="product__item_price">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                </div>
                <div class="flex product-item__quantity">
                  <p class="da-ban-text">Số lượng: {{ $product->stock }}</p>
                </div>
              </a>
              <div class="button__addcart__box">
                <form action="{{ route("cart.store") }}" method="POST">
                  @csrf
                  <input type="number" name="product_id" value={{ $product->product_id }} hidden>
                  <input type="number" name="quantity" value=1 hidden>
                  <button class="button button__addcart" type="submit" @if ($product->stock == 0){{ "disabled" }}@endif>
                    Mua ngay
                  </button>
                </form>
              </div>
            </div>
          @endforeach
        </div>
        <button class="home-page arrow right-arrow" onclick="scrollRight(this)">
          <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
    @endforeach
  </div>

  <script>
    function scrollRight(button) {
      const wrapper = button.parentElement.querySelector(".productWrapper");
      wrapper.scrollBy({
        left: 690,
        behavior: 'smooth'
      });
    }

    function scrollLeftt(button) {
      const wrapper = button.parentElement.querySelector(".productWrapper");
      wrapper.scrollBy({
        left: -690,
        behavior: 'smooth'
      });
    }
  </script>
@endsection