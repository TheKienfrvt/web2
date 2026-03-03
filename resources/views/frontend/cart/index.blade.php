@extends('frontend.layouts.app')

@section('title', 'Giỏ Hàng - Cửa hàng điện tử')

@section('css')
  <style>
    .cart-item {
      border-bottom: 1px solid #eee;
      padding: 1rem 0;
    }

    .product-image {
      max-width: 100px;
      height: auto;
    }

    .quantity-input {
      width: 80px;
      text-align: center;
    }

    .cart-summary {
      background: #f8f9fa;
      padding: 1.5rem;
      border-radius: 0.5rem;
    }

    small.text-muted {}
  </style>
@endsection

@section('content')
  <div class="container py-4">
    @if($cart->cartItems->count() > 0)
      <!-- Danh sách sản phẩm -->
      <div class="cart py-4">
        <table class="w-100 text-center shadow" style="border-radius: 12px; overflow: hidden;">
          <thead style="background-color: #00268c;" class="text-light">
            <tr>
              {{-- style="width: 20%" --}}
              <th class='p-3' style="width: 50%" colspan="2">Sản phẩm</th>
              <th class='p-3' style="width: 20%">Số lượng</th>
              <th class='p-3' style="width: 20%">Số tiền</th>
              <th class='p-3' style="width: 10%">Xóa</th>
            </tr>
          </thead>
          <tbody>
            @php
              $warning = false;
            @endphp
            @foreach ($cart->cartItems as $item)
              @if ($item->quantity > $item->product->stock)
                @php
                  $warning = "có sản phẩm vượt quá số lượng";
                @endphp
              @endif
              <tr class="border-bottom cart-item">
                @if($item->product->status != 'hiện')
                  @php
                    $warning = "Có sản phẩm đã ẩn/xóa trong giỏ hàng";
                  @endphp
                  <td class="align-middle p-2"></td>
                  <td class="align-middle text-start p-2">
                    <p class="text-danger">đã bị ẩn/xóa</p>
                  </td>
                  <td class="align-middle p-2">
                    <p>{{ $item->quantity }}</p>
                  </td>
                @else
                  <td class="align-middle p-2">
                    <a href="{{route('product.show', ['productId' => $item->product->product_id])}}"><img
                        src="{{ asset('images/' . ($item->product->image_url ?? "no image available.jpg")) }}" alt="{{ $item->product->product_name }}"
                        class="img-fluid product-image"></a>
                  </td>
                  <td class="align-middle text-start p-2">
                    <a href="{{route('product.show', ['productId' => $item->product->product_id])}}">
                      <h5 class="mb-1">{{ $item->product->product_name }}</h5>
                      <p class="text-muted mb-0">{{ number_format($item->product->price, 0, ',', '.') }}đ</p>
                    </a>
                  </td>
                  <td class="align-middle p-2">
                    <div class="input-group input-group-sm">
                      <button class="btn btn-update-quantity" type="button" data-product-id="{{ $item->product_id }}"
                        data-action="decrease">
                        <i class="fas fa-minus"></i>
                      </button>
                      <input type="number" class="form-control quantity-input" value="{{ $item->quantity }}" min="1"
                        max="{{ $item->product->stock }}" data-product-id="{{ $item->product_id }}">
                      <button class="btn btn-update-quantity" type="button" data-product-id="{{ $item->product_id }}"
                        data-action="increase">
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                    <small class="text-muted">Còn: {{ $item->product->stock }} sản phẩm</small>
                  </td>
                @endif
                <td class="align-middle p-2">
                  <strong class="text-danger item-total" data-product-id="{{ $item->product_id }}">
                    {{ $item->thanh_tien_formatted }}
                  </strong>
                </td>
                <td class="align-middle p-2">
                  <form action="{{ route('cart.delete', $item->product_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm btn-remove" data-product-id="{{ $item->product_id }}"
                      title="Xóa sản phẩm">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Tổng kết giỏ hàng -->
      <div class="col-lg-4 shadow">
        <div class="cart-summary">
          <h5 class="mb-3">Tổng Kết Giỏ Hàng</h5>

          <div class="d-flex justify-content-between mb-2">
            <span>Tổng số lượng:</span>
            <strong id="cart-total-quantity">{{ $cart->tong_so_luong }}</strong>
          </div>

          <div class="d-flex justify-content-between mb-3">
            <span>Tổng tiền:</span>
            <strong class="text-danger h5" id="cart-total-amount">
              {{ number_format($cart->tong_tien, 0, ',', '.') }}đ
            </strong>
          </div>

          <a href="{{route('checkout.index')}}" class="btn btn-primary w-100 mb-2" id="checkout-link" @if ($warning) {{ "hidden" }} @endif>
            <i class="fas fa-credit-card me-1"></i>Tiến Hành Thanh Toán
          </a>

          <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
            <i class="fas fa-shopping-bag me-1"></i>Tiếp Tục Mua Hàng
          </a>
        </div>
      </div>
    @else
      <!-- Giỏ hàng trống -->
      <div class="text-center py-5">
        <div class="mb-4">
          <i class="fas fa-shopping-cart fa-4x text-muted"></i>
        </div>
        <h3 class="text-muted">Giỏ hàng của bạn đang trống</h3>
        <p class="text-muted mb-4">Hãy thêm sản phẩm vào giỏ hàng để bắt đầu mua sắm</p>
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
          <i class="fas fa-shopping-bag me-2"></i>Mua Sắm Ngay
        </a>
      </div>
    @endif
  </div>
@endsection

@section('js')
  <script>
    $(document).ready(function () {
      // Cập nhật khi ấn nút tăng/giảm số lượng
      $('.btn-update-quantity').on('click', function () {
        const productId = $(this).data('product-id');
        const action = $(this).data('action');
        const input = $(`.quantity-input[data-product-id="${productId}"]`);
        const maxValue = input.attr('max');
        let quantity = parseInt(input.val());

        if (action === 'increase' && quantity < maxValue) {
          quantity++;
          input.val(quantity);
          updateQuantity(productId, quantity);
        } else if (action === 'decrease' && quantity > 1) {
          quantity--;
          input.val(quantity);
          updateQuantity(productId, quantity);
        }
      });

      $('.quantity-input').on('input', function () {
        console.log('event quantity-input');

        let $input = $(this);
        let min = parseInt($input.attr('min'));
        let max = parseInt($input.attr('max'));
        let value = $input.val().replace(/\D/g, '');

        value = parseInt(value);
        if (isNaN(value) || value < min) value = min;
        if (value > max) value = max;

        $input.val(value);
      });

      // Cập nhật khi thay đổi trực tiếp trong input
      $('.quantity-input').on('change', function () {
        const productId = $(this).data('product-id');
        const quantity = parseInt($(this).val());
        const maxStock = parseInt($(this).attr('max'));

        if (quantity > maxStock) {
          $(this).val(maxStock);
          updateQuantity(productId, maxStock);
        } else if (quantity < 1) {
          $(this).val(1);
          updateQuantity(productId, 1);
        } else {
          updateQuantity(productId, quantity);
        }
      });

      // Gọi API cập nhật số lượng
      function updateQuantity(productId, quantity) {
        $.ajax({
          url: '{{ route("cart.update") }}',
          method: 'PUT',
          data: {
            product_id: productId,
            quantity: quantity,
            _token: '{{ csrf_token() }}'
          },
          success: function (response) {
            if (response.success) {
              // Cập nhật tổng tiền item
              $(`.item-total[data-product-id="${productId}"]`).text(response.item_total_formatted);

              // Cập nhật tổng giỏ hàng
              $('#cart-total-quantity').text(response.cart_total);
              $('#cart-total-amount').text(response.cart_amount_formatted ||
                new Intl.NumberFormat('vi-VN').format(response.cart_amount) + 'đ');

              showAlert('success', response.message);
            } else {
              console.log('4. Response không success');
              showAlert('error', response.message);
            }
            console.log('5. Kết thúc success');
          },
          error: function (xhr) {
            console.log('6. Vào error');
            const response = xhr.responseJSON;
            showAlert('error', response?.message || 'Có lỗi xảy ra');
          },
          complete: function () {
            console.log('7. Vào complete');
          }
        });
        console.log('8. Sau khi gọi AJAX');
      }
    });
  </script>
@endsection