<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReceiptController extends Controller
{
    /**
     * Hiển thị danh sách phiếu nhập
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $query = Receipt::with(['supplier', 'receiptDetails']);

        // Filter by search (receipt ID)
        if ($request->has('search') && $request->search) {
            $query->where('receipt_id', 'like', '%' . $request->search . '%');
        }

        // Filter by supplier
        if ($request->has('supplier_id') && $request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('order_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('order_date', '<=', $request->end_date);
        }

        $receipts = $query->orderBy('receipt_id', 'desc')->paginate(10);
        $receipts->totalPendingReceipts = Receipt::where('status', 'đang chờ')->count();
        $receipts->totalReceivedReceipts = Receipt::where('status', 'đã nhận')->count();
        $receipts->totalCancelledReceipts = Receipt::where('status', 'đã hủy')->count();
        $suppliers = Supplier::all();


        return view('admin.receipt.index', compact(
            'receipts',
            'suppliers'
        ));
    }

    /**
     * Hiển thị chi tiết phiếu nhập
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $receipt = Receipt::with(['supplier', 'receiptDetails.product'])
            ->findOrFail($id);

        // Format dates
        $receipt->order_date_formatted = \Carbon\Carbon::parse($receipt->order_date)->format('d/m/Y');
        $receipt->created_at_formatted = $receipt->created_at ? \Carbon\Carbon::parse($receipt->created_at)->format('d/m/Y H:i') : 'N/A';
        $receipt->updated_at_formatted = $receipt->updated_at ? \Carbon\Carbon::parse($receipt->updated_at)->format('d/m/Y H:i') : 'N/A';

        return view('admin.receipt.show', compact('receipt'));
    }

    /**
     * Cập nhật trạng thái phiếu nhập
     * @param \Illuminate\Http\Request $request
     * @param mixed $receiptId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $receiptId)
    {
        $receipt = Receipt::findOrFail($receiptId);

        $request->validate([
            'status' => 'required|in:đã nhận,đã hủy'
        ]);

        $receipt->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công!'
        ]);
    }

    /**
     * Hiển thị form tạo phiếu nhập
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::where('status', '!=', 'đã xóa')->get();

        return view('admin.receipt.create', compact('suppliers', 'products'));
    }

    /**
     * Xử lý tạo phiếu nhập
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        // $validated = $request->validate([
        //     'supplier_id' => 'required|exists:suppliers,supplier_id',
        //     'order_date' => 'required|date',
        //     'notes' => 'nullable|string|max:500',
        //     'products' => 'required|array|min:1',
        //     'products.*.product_id' => 'required|exists:products,product_id',
        //     'products.*.import_price' => 'required|numeric|min:0',
        //     'products.*.quantity' => 'required|integer|min:1'
        // ], [
        //     'supplier_id.required' => 'Vui lòng chọn nhà cung cấp.',
        //     'supplier_id.exists' => 'Nhà cung cấp không tồn tại.',
        //     'order_date.required' => 'Vui lòng chọn ngày đặt hàng.',
        //     'order_date.date' => 'Ngày đặt hàng không hợp lệ.',
        //     'products.required' => 'Vui lòng thêm ít nhất một sản phẩm.',
        //     'products.min' => 'Vui lòng thêm ít nhất một sản phẩm.',
        //     'products.*.product_id.required' => 'Thông tin sản phẩm không hợp lệ.',
        //     'products.*.product_id.exists' => 'Sản phẩm không tồn tại.',
        //     'products.*.import_price.required' => 'Vui lòng nhập giá nhập.',
        //     'products.*.import_price.numeric' => 'Giá nhập phải là số.',
        //     'products.*.import_price.min' => 'Giá nhập phải lớn hơn 0.',
        //     'products.*.quantity.required' => 'Vui lòng nhập số lượng.',
        //     'products.*.quantity.integer' => 'Số lượng phải là số nguyên.',
        //     'products.*.quantity.min' => 'Số lượng phải lớn hơn 0.'
        // ]);

        DB::beginTransaction();

        try {
            // Tạo mã phiếu nhập tự động
            // $receiptCode = $this->generateReceiptCode();

            // Tính tổng tiền
            $totalAmount = 0;
            foreach ($request->products as $product) {
                $totalAmount += $product['import_price'] * $product['quantity'];
            }

            // Tạo phiếu nhập
            $receipt = Receipt::create([
                'supplier_id' => $request->supplier_id,
                // 'total_amount' => $totalAmount,
                'status' => 'đang chờ'
            ]);

            // Thêm chi tiết phiếu nhập và cập nhật tồn kho
            foreach ($request->products as $productData) {
                $productId = $productData['product_id'];
                $importPrice = $productData['import_price'];
                $quantity = $productData['quantity'];

                // Thêm chi tiết phiếu nhập
                ReceiptDetail::create([
                    'receipt_id' => $receipt->receipt_id,
                    'product_id' => $productId,
                    'price' => $importPrice,
                    'quantity' => $quantity
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tạo phiếu nhập thành công!',
                'receipt_id' => $receipt->receipt_id
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Lỗi khi tạo phiếu nhập: ' . $e->getMessage(), [
                'request' => $request->all(),
                // 'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tạo phiếu nhập. Vui lòng thử lại.'
            ], 500);
        }
    }
}
