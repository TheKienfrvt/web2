@extends('frontend.layouts.app')

@section('title', $product->product_name . ' - Cửa hàng điện tử')

@section('content')
  <div class="container">
    <div class="product__detail">
      <div class="product__detail__box1 shadow">
        <!-- phần bên trái -->
        <div class="product__detail--left">
          <!-- hình ảnh sản phẩm-->
          <img src="{{asset('images/' . ($product->image_url ?? "no image available.jpg"))}}" alt="">
        </div>

        <!-- phần bên phải -->
        <div class="product__detail--right">
          <!-- tên sản phẩm -->
          <h1 class="product__detail__name">{{$product->product_name}}</h1>

          <!-- giá sản phẩm -->
          <h2 class="product__detail__price">{{ number_format($product->price, 0, ',', '.') }}đ</h2>
          {{-- action="{{route('cart.adds')}}" --}}
          <form method="post">
            @csrf

            <input type="number" name="product_id" value='{{$product->product_id}}' class='d-none'>
            <div class="flex">
              @if($product->stock == 0)
                <p>Hết hàng</p>
              @else
                <p class="product__count">Số Lượng</p>
                <input class="form-control input-number" type="number" name='quantity' value="1" min='1'
                  max='{{$product->stock}}' style="width: 80px">
                <p>còn {{$product->stock}}</p>
              @endif
            </div>

            <!-- các nút mua, giỏ hàng -->
            <div class="product__button__buy__cart">
              <button formaction='{{ route('cart.store')}}' class="product__detail__buy" type="submit" name="buyNow" value="buyNow" @if ($product->stock == 0)
              disabled @endif>
                MUA NGAY
              </button>

              <button formaction='{{ route('cart.store')}}' class="product__detail__cart" type="submit" name="action" value="add-cart" @if ($product->stock == 0) disabled @endif>
                <i class="fa-solid fa-cart-plus"></i>
                THÊM VÀO GIỎ HÀNG
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Tab thông số kỹ thuật -->
      <div class="product__detail__list tab-pane fade show active w-100" id="specs" role="tabpanel">
        @if($product->detail)
          <div class="specs-card">
            <div class="specs-header">
              <h4 class="specs-title">
                <i class="fas fa-microchip me-2"></i>
                Thông số kỹ thuật
              </h4>
            </div>
            <div class="specs-body">
              <div class="row">
                @foreach($product->filter as $attribute => $label)
                    @if(!empty($product->detail->$attribute))
                        <div class="col-md-6 mb-3">
                            <div class="spec-item">
                                <div class="spec-label">
                                    <span class="spec-icon">
                                        @switch($attribute)
                                            @case('cpu')<i class="fas fa-microchip"></i>@break
                                            @case('gpu')<i class="fas fa-gamepad"></i>@break
                                            @case('ram')<i class="fas fa-memory"></i>@break
                                            @case('storage')<i class="fas fa-hdd"></i>@break
                                            @case('kich_thuoc_man_hinh')<i class="fas fa-desktop"></i>@break
                                            @case('do_phan_giai')<i class="fas fa-expand"></i>@break
                                            @default<i class="fas fa-cog"></i>
                                        @endswitch
                                    </span>
                                    {{ $label }}
                                </div>
                                <div class="spec-value">
                                    {{ $product->detail->$attribute }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
              </div>
            </div>
          </div>
        @else
          <div class="text-center py-5">
            <div class="no-specs-icon mb-3">
              <i class="fas fa-info-circle fa-3x text-muted"></i>
            </div>
            <h5 class="text-muted">Chưa có thông tin chi tiết</h5>
            <p class="text-muted">Thông số kỹ thuật sẽ được cập nhật sớm nhất</p>
          </div>
        @endif
      </div>

      <!-- Sản phẩm cùng loại -->
      @if($relatedProducts->count() > 0)
        <div class="product__detail__box3">
          <div class="home-page header-product-bar">
            <h2 class="home-page name-product-bar">Sản phầm cùng Loại</h2>
            <div class="home-page line"></div>
            <a href="{{route('product.indexByCategory', ['category_id' => $product->category_id])}}"
              class="home-page see-more">
              xem tất cả
              <i class="fa fa-angle-double-right"></i>
            </a>
          </div>
          <div class="product__item wrap">
            @foreach ($relatedProducts as $product)
              <div class="product__item__card">
                <a href="{{ route('product.show', ['productId' => $product->product_id])}}">
                  <div class="product__item__card__img">
                    <img src="{{ asset('images/' . ($product->image_url ?? "no image available.jpg")) }}" alt="">
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
        </div>
      @endif
    </div>
  </div>
@endsection

@section('css')
<style>
.specs-container {
    padding: 20px 0;
}

.specs-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.specs-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px 25px;
}

.specs-title {
    margin: 0;
    font-weight: 600;
    font-size: 1.25rem;
}

.specs-body {
    padding: 25px;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}

.spec-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.spec-label {
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 8px;
}

.spec-icon {
    color: #667eea;
    width: 20px;
    text-align: center;
}

.spec-value {
    font-weight: 500;
    color: #212529;
    background: white;
    padding: 4px 12px;
    border-radius: 20px;
    border: 1px solid #dee2e6;
    font-size: 0.9rem;
}

.no-specs-icon {
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 768px) {
    .specs-body {
        padding: 15px;
    }
    
    .spec-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .spec-value {
        align-self: flex-end;
    }
    
    .specs-header {
        padding: 15px 20px;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.spec-item {
    animation: fadeInUp 0.5s ease forwards;
}

.spec-item:nth-child(1) { animation-delay: 0.1s; }
.spec-item:nth-child(2) { animation-delay: 0.2s; }
.spec-item:nth-child(3) { animation-delay: 0.3s; }
.spec-item:nth-child(4) { animation-delay: 0.4s; }
.spec-item:nth-child(5) { animation-delay: 0.5s; }
.spec-item:nth-child(6) { animation-delay: 0.6s; }
</style>
@endsection

@section('js')
  <script>
    function initQuantityHandlersDetail() {
      const input = document.querySelector('.input-number');
      const min = parseInt(input.min);
      const max = parseInt(input.max);

      input.addEventListener('input', () => {
        console.log('event input-number');
        input.value = input.value.replace(/\D/g, '');
        let value = parseInt(input.value);
        if (value > max) input.value = max;
        if (value < min || isNaN(value)) input.value = min;
      });
    };

    initQuantityHandlersDetail()
    </script>
@endsection