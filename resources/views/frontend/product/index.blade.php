@extends('frontend.layouts.app')

@section('title', $category->category_name . ' - Cửa hàng điện tử')

@section('content')
  <div class="main">
    <div class="container category">
      <div class="category__left">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa-solid fa-filter"></i>Bộ lọc sản phẩm</h5>
          </div>

          <div class="card-body">
            @if (!empty($filters))
              <form action="{{ route('product.indexByCategory', ['category_id' => $category->category_id]) }}" method="get"
                id="filterForm">
                <!-- FILTER SẮP XẾP -->
                <div class="mb-4">
                  <label class="form-label fw-semibold text-dark mb-2">
                    <i class="fas fa-sort me-1"></i>Sắp xếp theo
                  </label>
                  <select name="sapXep" class="form-select border-2" onchange="this.form.submit()">
                    <option value="mac-dinh" {{ request('sapXep') == 'mac-dinh' ? 'selected' : '' }}>Mặc định</option>
                    <option value="gia-thap-den-cao" {{ request('sapXep') == 'gia-thap-den-cao' ? 'selected' : '' }}>Giá: Thấp
                      đến cao</option>
                    <option value="gia-cao-den-thap" {{ request('sapXep') == 'gia-cao-den-thap' ? 'selected' : '' }}>Giá: Cao
                      đến thấp</option>
                  </select>
                </div>

                <!-- FILTER KHOẢNG GIÁ -->
                <div class="mb-4">
                  <label class="form-label fw-semibold text-dark mb-2">
                    <i class="fas fa-tag me-1"></i>Khoảng giá
                  </label>
                  <div class="row g-2">
                    <div class="col-6">
                      <input type="number" name="giaThap" class="form-control border-2" placeholder="Từ"
                        value="{{ request('giaThap') }}" min="0">
                    </div>
                    <div class="col-6">
                      <input type="number" name="giaCao" class="form-control border-2" placeholder="Đến"
                        value="{{ request('giaCao') }}" min="0">
                    </div>
                  </div>
                  <button type="submit" class="btn btn-outline-primary btn-sm w-100 mt-2">
                    <i class="fas fa-check me-1"></i>Áp dụng giá
                  </button>
                </div>

                <!-- FILTER THUỘC TÍNH -->
                @foreach ($filters as $attribute => $filter)
                  <div class="mb-4">
                    <label class="form-label fw-semibold text-dark mb-2">
                      <i class="fas fa-list me-1"></i>{{ $filter['label'] }}
                    </label>
                    <div class="filter-options p-2" style="max-height: 200px; overflow-y: auto;">
                      @foreach ($filter['values'] as $value)
                        @php
                          $safeId = $attribute . '_' . strtolower(str_replace(' ', '_', $value));
                          $isChecked = in_array($value, (array) request($attribute, []));
                        @endphp

                        <div class="form-check">
                          <input class="form-check-input filter-checkbox" type="checkbox" id="{{ $safeId }}"
                            name="{{ $attribute }}[]" value="{{ $value }}" {{ $isChecked ? 'checked' : '' }}
                            onchange="this.form.submit()">
                          <label class="form-check-label w-100" for="{{ $safeId }}">
                            {{ $value }}
                            @if($isChecked)
                              <span class="badge bg-primary float-end">✓</span>
                            @endif
                          </label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                @endforeach

                <input type="hidden" name="category_id" value="{{ $category->category_id }}">

                <!-- BUTTONS -->
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary" name="filter" value="filter">
                    <i class="fas fa-search me-2"></i>Áp dụng bộ lọc
                  </button>
                  <a href="{{ route('product.indexByCategory', ['category_id' => $category->category_id]) }}"
                    class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Xóa bộ lọc
                  </a>
                </div>
              </form>
            @else
              <div class="text-center py-3">
                <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                <p class="text-muted mb-0">Chưa có bộ lọc cho danh mục này</p>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="category__right">
        @if ($products->count() > 0)
          <div class="product__item wrap">
            @foreach ($products as $product)
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

          <!-- Pagination -->
          @if($products->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
              <div class="text-muted">
                Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} của {{
            $products->total() }} sản phẩm
              </div>
              <nav>
                {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
              </nav>
            </div>
          @endif
        @else
          <p class="error">không có sản phẩm nào</p>
        @endif
      </div>
    </div>
  </div>
@endsection