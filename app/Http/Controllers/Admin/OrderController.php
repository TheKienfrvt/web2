<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Filter by search (order ID)
        if ($request->has('search') && $request->search) {
            $query->where('order_id', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('order_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('order_date', '<=', $request->end_date);
        }

        $orders = $query->orderBy('order_date', 'desc')->paginate(20);

        // Stats for dashboard cards
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'chờ xác nhận')->count();
        $shippingOrders = Order::where('status', 'đang giao')->count();
        $cancelledOrders = Order::where('status', 'đã hủy')->count();
        $completedOrders = Order::where('status', 'đã nhận hàng')->count();
        $monthlyRevenue = Order::where('status', 'đã nhận hàng')
            ->whereMonth('order_date', now()->month)
            ->sum('total_amount');

        return view('admin.order.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'shippingOrders',
            'cancelledOrders',
            'completedOrders',
            'monthlyRevenue'
        ));
    }

    /**
     * Xem chi tiết đơn hàng
     * @param mixed $orderId
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($orderId)
    {
        try {
            $order = Order::with([
                'user',
                'orderDetails.product'
            ])->findOrFail($orderId);

            return view('admin.order.show', compact('order'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.order.index')
                ->with('error', 'Không tìm thấy đơn hàng với ID: ' . $orderId);
        } catch (\Exception $e) {
            return redirect()->route('admin.order.index')
                ->with('error', 'Đã xảy ra lỗi khi tải thông tin đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật trạng thái đơn hàng
     * @param \Illuminate\Http\Request $request
     * @param mixed $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $orderId) {
        $order = Order::find($orderId);
        if(!$order) {
            return redirect()->route('admin.order.index')->with('error', 'Đơn hàng không tồn tại!');
        }

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    /**
     * Cập nhật trạng thánh đơn hàng thành đã hủy
     * @param \Illuminate\Http\Request $request
     * @param mixed $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelOrder(Request $request, $orderId)
    {
        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();

        try {
            // Tìm đơn hàng theo ID
            $order = Order::find($orderId);

            // Kiểm tra đơn hàng có tồn tại không
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng không tồn tại!'
                ], 404);
            }

            // Kiểm tra trạng thái hiện tại của đơn hàng
            // Chỉ cho phép hủy các đơn hàng có trạng thái 'chờ xác nhận' hoặc 'đã xác nhận'
            $allowedStatuses = ['chờ xác nhận', 'đã xác nhận', 'đang giao'];
            if (!in_array($order->status, $allowedStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể hủy đơn hàng với trạng thái hiện tại: ' . $order->status
                ], 400);
            }

            // Lưu trạng thái cũ để ghi log
            $oldStatus = $order->status;

            // Cập nhật trạng thái đơn hàng thành 'đã hủy'
            $order->update([
                'status' => 'đã hủy'
            ]);

            // Commit transaction - xác nhận tất cả thay đổi
            DB::commit();

            // Trả về response thành công
            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng #' . $order->order_id . ' đã được hủy thành công!',
                'data' => [
                    'order_id' => $order->order_id,
                    'new_status' => $order->status
                ]
            ]);
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            DB::rollBack();

            // Trả về response lỗi
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thánh đơn hàng thành đã xác nhận
     * @param \Illuminate\Http\Request $request
     * @param mixed $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmOrder(Request $request, $orderId)
    {
        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();

        try {
            // Tìm đơn hàng theo ID
            $order = Order::find($orderId);

            // Kiểm tra đơn hàng có tồn tại không
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng không tồn tại!'
                ], 404);
            }

            if (!$order->status == 'chờ xác nhận') {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xác nhận đơn hàng với trạng thái hiện tại: ' . $order->status
                ], 400);
            }

            // Lưu trạng thái cũ để ghi log
            $oldStatus = $order->status;

            // Cập nhật trạng thái đơn hàng thành 'đã xác nhận'
            $order->update([
                'status' => 'đã xác nhận'
            ]);

            // Commit transaction - xác nhận tất cả thay đổi
            DB::commit();

            // Trả về response thành công
            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng #' . $order->order_id . ' đã được xác nhận!',
                'data' => [
                    'order_id' => $order->order_id,
                    'new_status' => $order->status
                ]
            ]);
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            DB::rollBack();

            // Trả về response lỗi
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xác nhận đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thánh đơn hàng thành đang giao
     * @param \Illuminate\Http\Request $request
     * @param mixed $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deliveryOrder(Request $request, $orderId)
    {
        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();

        try {
            // Tìm đơn hàng theo ID
            $order = Order::find($orderId);

            // Kiểm tra đơn hàng có tồn tại không
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng không tồn tại!'
                ], 404);
            }

            if (!$order->status == 'đã xác nhận') {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xác nhận đơn hàng với trạng thái hiện tại: ' . $order->status
                ], 400);
            }

            // Lưu trạng thái cũ để ghi log
            $oldStatus = $order->status;

            // Cập nhật trạng thái đơn hàng thành 'đang giao'
            $order->update([
                'status' => 'đang giao'
            ]);

            // Commit transaction - xác nhận tất cả thay đổi
            DB::commit();

            // Trả về response thành công
            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng #' . $order->order_id . ' xác nhận đang giao!',
                'data' => [
                    'order_id' => $order->order_id,
                    'new_status' => $order->status
                ]
            ]);
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            DB::rollBack();

            // Trả về response lỗi
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xác nhận đang giao đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị form tạo đơn hàng
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            // Lấy danh sách khách hàng)
            $users = User::active()
                ->select('user_id', 'username', 'email', 'phone_number')
                ->orderBy('username')
                ->get()
                ->map(function ($user) {
                    return [
                        'user_id' => $user->user_id,
                        'display_text' => $user->username . ' - ' . $user->email . ($user->phone_number ? ' - ' . $user->phone_number : '')
                    ];
                });

            // Lấy danh sách sản phẩm còn hàng
            $products = Product::where('status', 'hiện')
                ->where('stock', '>', 0)
                ->select('product_id', 'product_name', 'price', 'stock', 'image_url')
                ->orderBy('product_name')
                ->get();

            // Ngày giao hàng mặc định (3 ngày sau)
            $defaultDeliveryDate = now()->addDays(3)->format('Y-m-d');

            return view('admin.order.create', compact('users', 'products', 'defaultDeliveryDate'));
        } catch (\Exception $e) {
            // \Log::error('Error loading order create form: ' . $e->getMessage());

            return redirect()->route('admin.order.index')
                ->with('error', 'Không thể tải form tạo đơn hàng. Vui lòng thử lại.');
        }
    }

    /**
     * Xử lý tạo đơn hàng
     * @param \Illuminate\Http\Request $request
     * @throws \Exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate dữ liệu
            $validated = $request->validate([
                'user_id' => 'required|exists:user,user_id', // Sửa thành user_id
                'address' => 'required|string|max:255',
                'payment_method' => 'required|in:chuyển khoản,tiền mặt',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:product,product_id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price' => 'required|integer|min:0'
            ]);

            // Bắt đầu transaction
            DB::beginTransaction();

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $validated['user_id'], // Đây là user_id từ bảng user
                'address' => $validated['address'],
                'order_date' => now(),
                'total_amount' => 0, // Sẽ tính sau
                'status' => 'chờ xác nhận',
                'payment_method' => $validated['payment_method'],
                'created_by' => 'admin'
            ]);

            // Thêm chi tiết đơn hàng và tính tổng tiền
            $totalAmount = 0;
            foreach ($validated['products'] as $productData) {
                $product = Product::find($productData['product_id']);

                if (!$product) {
                    throw new \Exception("Sản phẩm không tồn tại");
                }

                // Kiểm tra tồn kho
                if ($product->stock < $productData['quantity']) {
                    throw new \Exception("Sản phẩm {$product->product_name} không đủ tồn kho. Tồn kho hiện có: {$product->stock}");
                }

                $subTotal = $productData['quantity'] * $productData['price'];
                $totalAmount += $subTotal;

                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price']
                ]);

                // Không cần cập nhật stock vì đã có trigger
            }

            // Cập nhật tổng tiền đơn hàng
            $order->update(['total_amount' => $totalAmount]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tạo đơn hàng thành công!',
                'order_id' => $order->order_id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
