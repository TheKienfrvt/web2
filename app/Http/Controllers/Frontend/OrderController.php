<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Xem lịch sử đơn hàng
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();

        $orders = Order::with(['orderDetails.product'])->where('user_id', $user->user_id)->orderBy('order_date','desc')->paginate(10);

        return view('frontend.order.index', compact('user', 'orders'));
    }

    /**
     * Thêm đơn hàng mới
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'payment_method' => 'required|string|in:tiền mặt,chuyển khoản'
        ], [
            'address.required' => 'Vui lòng chọn địa chỉ giao hàng',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ'
        ]);
        $address = $request->input('address');
        $paymentMethod = $request->input('payment_method');

        $cart = Cart::with(['cartItems.product'])->where('user_id', Auth::id())->first();
        $totalAmount = $cart->getTongTienAttribute();


        Order::create([
            'user_id' => Auth::id(),
            'address' => $address,
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'created_by' => 'customer'
        ]);

        return redirect()->route('order.index')->with('success', 'Đặt hàng thành công!');
    }

    /**
     * Cập nhật trang thái đơn hàng thành đã hủy
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Order $order)
    {
        $order->capNhatTrangThai('đã hủy');
        return redirect()->route('order.index')->with('success', 'Hủy đơn hàng thành công!');
    }

    /**
     * Cập nhật trạng thái đơn hành thành đã nhận hàng
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delivered(Order $order)
    {
        $order->capNhatTrangThai('đã nhận hàng');
        return redirect()->route('order.index')->with('success', 'đơn hàng của bạn đã hoàn thành!');
    }
}
