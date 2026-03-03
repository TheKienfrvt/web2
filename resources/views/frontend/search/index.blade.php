@extends('frontend.layouts.app')

@section('title', 'Kết quả tìm kiếm: ' . $textSearch . ' - Cửa hàng điện tử')

@section('content')
  <div class="container search d-flex">
    <!-- Filter Sidebar -->
    <div class="col-md-3" style="width: 350px; margin-right: 10px;">
      <form action="{{ route('search') }}" method="get" id="searchFilterForm">
        <input type="hidden" name="search" value="{{ $textSearch }}">

        <div class="search-filters card">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa-solid fa-filter"></i>Bộ lọc sản phẩm</h5>
          </div>

          <div class="card-body">
            <!-- Filter theo danh mục -->
            <div class="filter-group mb-4">
              <h6 class="filter-title mb-2"><i class="fa-solid fa-icons"></i>Danh mục</h6>
              <select name="danh_muc" class="form-select" onchange="this.form.submit()">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $category)
                  <option value="{{ $category->category_id }}" {{ request('danh_muc') == $category->category_id ? 'selected' : '' }}>
                    {{ $category->category_name }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Filter khoảng giá -->
            <div class="filter-group mb-4">
              <h6 class="filter-title mb-2"><i class="fas fa-tag me-1"></i>Khoảng giá</h6>
              <div class="price-inputs">
                <input type="number" name="giaThap" class="form-control mb-2" placeholder="Giá thấp nhất"
                  value="{{ request('giaThap') }}" min="0">
                <input type="number" name="giaCao" class="form-control" placeholder="Giá cao nhất"
                  value="{{ request('giaCao') }}" min="0">
              </div>
              <button type="submit" class="btn btn-sm btn-outline-primary mt-2 w-100">
                Áp dụng giá
              </button>
            </div>

            <!-- Sắp xếp -->
            <div class="filter-group">
              <h6 class="filter-title mb-2"><i class="fas fa-sort me-1"></i>Sắp xếp theo</h6>
              <select name="sap_xep" class="form-select" onchange="this.form.submit()">
                <option value="mac_dinh" {{ request('sap_xep') == 'mac_dinh' ? 'selected' : '' }}>Mặc định</option>
                <option value="moi_nhat" {{ request('sap_xep') == 'moi_nhat' ? 'selected' : '' }}>Mới nhất</option>
                <option value="gia_tang" {{ request('sap_xep') == 'gia_tang' ? 'selected' : '' }}>Giá tăng dần</option>
                <option value="gia_giam" {{ request('sap_xep') == 'gia_giam' ? 'selected' : '' }}>Giá giảm dần</option>
                <option value="ten_az" {{ request('sap_xep') == 'ten_az' ? 'selected' : '' }}>Tên A-Z</option>
                <option value="ten_za" {{ request('sap_xep') == 'ten_za' ? 'selected' : '' }}>Tên Z-A</option>
              </select>
            </div>

            <!-- Nút reset -->
            <div class="filter-group mt-4">
              <a href="{{ route('search', ['search' => $textSearch]) }}" class="btn btn-outline-secondary w-100">
                <i class="fa-solid fa-rotate-left"></i> Đặt lại bộ lọc
              </a>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div>
      {{-- <h2 class="search title">Nội dung tìm kiếm: "{{$textSearch}}"</h2> --}}
      @if ($products->count() > 0)
        <div class="product__item wrap">
          @foreach ($products as $product)
            <div class="product__item__card">
              <a href="{{ route('product.show', $product->product_id) }}">
                <div class="product__item__card__img">
                  <img src="{{ asset('images/' . ($product->image_url ?? "no image available.jpg")) }}" alt="">
                </div>
                <div class="product__item__card__content">
                  <h3 class="product__item__name">{{ $product->product_name }}</h3>
                  <p class="product__item_price">{{ number_format($product->price, 0, ',', '.') }}đ
                  </p>
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
        {{ $products->links('pagination::bootstrap-4') }}
      @else
        <p class="error">không có sản phẩm nào</p>
      @endif
    </div>
  </div>
@endsection