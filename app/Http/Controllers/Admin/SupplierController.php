<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        // $query = Supplier::withCount(['receipts as receipts_count'])
        //     ->withSum('receipts as total_import_value', 'total_amount');
        $query = Supplier::withCount(['receipts as receipts_count']);
        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('supplier_name', 'like', '%' . $request->search . '%')
                  ->orWhere('supplier_phone', 'like', '%' . $request->search . '%')
                  ->orWhere('supplier_address', 'like', '%' . $request->search . '%');
            });
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'supplier_id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $suppliers = $query->paginate(10);
        
        // Stats for dashboard cards
        $totalSuppliers = Supplier::count();
        $activeSuppliers = Supplier::has('receipts')->count();
        $monthlyReceipts = Receipt::whereMonth('order_date', now()->month)->count();
        // $totalImportValue = Receipt::where('status', 'đã nhận')->sum('total_amount');
        $totalImportValue = 0;
        return view('admin.supplier.index', compact(
            'suppliers',
            'totalSuppliers',
            'activeSuppliers',
            'monthlyReceipts',
            'totalImportValue'
        ));
    }

    public function show($supplierId) {
        $supplier = Supplier::with(['receipts'])->where('supplier_id', $supplierId)->firstOrFail();

        return view('admin.supplier.show', compact('supplier'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'required|string|size:10|unique:supplier,supplier_phone',
            'supplier_address' => 'required|string|max:500',
            'email' => 'nullable|email|max:255',
            'tax_code' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
        ]);
        
        Supplier::create($request->all());
        
        return redirect()->route('admin.supplier.index')
            ->with('success', 'Thêm nhà cung cấp thành công!');
    }
    
    public function update(Request $request, $supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'required|string|size:10|unique:supplier,supplier_phone,' . $supplierId . ',supplier_id',
            'supplier_address' => 'required|string|max:500',
            'email' => 'nullable|email|max:255',
            'tax_code' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
        ]);
        
        $supplier->update($request->all());
        
        return back()->with('success', 'Cập nhật nhà cung cấp thành công!');
    }
    
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Check if supplier has receipts
        if ($supplier->receipts()->exists()) {
            return redirect()->back()
                ->with('error', 'Không thể xóa nhà cung cấp đã có phiếu nhập!');
        }
        
        $supplier->delete();
        
        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Xóa nhà cung cấp thành công!');
    }
}
