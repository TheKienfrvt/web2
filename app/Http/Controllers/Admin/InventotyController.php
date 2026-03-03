<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventotyController extends Controller
{
    /**
     * Hiển thị lịch sử kho hàng
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $query = Inventory::with('product');
        
        // Filter by product
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        
        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Filter by quantity type
        if ($request->has('quantity_type') && $request->quantity_type) {
            if ($request->quantity_type === 'positive') {
                $query->where('quantity', '>', 0);
            } elseif ($request->quantity_type === 'negative') {
                $query->where('quantity', '<', 0);
            }
        }
        
        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $inventorys = $query->orderBy('created_at', 'desc')->paginate(40);
        $products = Product::active()->get();
        
        // Stats for dashboard cards
        $totalImport = Inventory::where('type', 'nhập hàng')->count();
        $totalExport = Inventory::where('type', 'xuất hàng')->count();
        $totalAdjustment = Inventory::where('type', 'điều chỉnh')->count();
        $totalTransactions = Inventory::count();
        
        return view('admin.inventory.index', compact(
            'inventorys',
            'products',
            'totalImport',
            'totalExport', 
            'totalAdjustment',
            'totalTransactions'
        ));
    }

    /**
     * Xử lý điều chỉnh kho
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'adjust_type' => 'required|in:increase,decrease,set',
            'quantity' => 'required|integer|min:0',
        ]);
        
        $product = Product::find($request->product_id);
        $currentStock = $product->stock;
        $adjustmentQuantity = 0;
        
        switch ($request->adjust_type) {
            case 'increase':
                $adjustmentQuantity = $request->quantity;
                $product->increment('stock', $request->quantity);
                break;
                
            case 'decrease':
                if ($request->quantity > $currentStock) {
                    return redirect()->back()->with('error', 'Số lượng giảm vượt quá tồn kho hiện tại!');
                }
                $adjustmentQuantity = -$request->quantity;
                $product->decrement('stock', $request->quantity);
                break;
                
            case 'set':
                $adjustmentQuantity = $request->quantity - $currentStock;
                $product->update(['stock' => $request->quantity]);
                break;
        }
        
        Inventory::create([
            'product_id' => $request->product_id,
            'quantity' => $adjustmentQuantity,
            'type' => 'điều chỉnh',
            'reference' => $request->reason,
        ]);
        
        return redirect()->back()->with('success', 'Điều chỉnh tồn kho thành công!');
    }
}
