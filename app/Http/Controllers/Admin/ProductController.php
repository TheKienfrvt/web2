<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use FFI\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->where('status', '!=', 'đã xóa');

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by stock
        if ($request->has('stock')) {
            if ($request->stock === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            }
        }

        $products = $query->orderBy('product_id', 'desc')->paginate(34);
        $categories = Category::all();

        return view('admin.product.index', compact('products', 'categories'));
    }

    /**
     * Xử lý xóa sản phẩm
     * @param mixed $productId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($productId)
    {
        $product = Product::findOrFail($productId);

        $product->update(['status' => 'đã xóa']);

        return redirect()->route('admin.product.index')->with('success', 'xóa sản phẩm thành công');
    }

    /**
     * Hiển thị form tạo sản phẩm
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = Category::all();

        // Lấy thông tin detail attributes cho từng category
        $categoryDetails = [];
        foreach ($categories as $category) {
            $relationName = Product::getRelationName($category->category_id);
            if ($relationName) {
                $modelClass = Product::$categoryMapping[$category->category_id]['model'] ?? null;
                if ($modelClass && method_exists($modelClass, 'getDetailAttributes')) {
                    $categoryDetails[$category->category_id] = $modelClass::getDetailAttributes();
                }
            }
        }

        return view('admin.product.create', compact('categories', 'categoryDetails'));
    }

    /**
     * Xử lý tạo sản phẩm
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate thông tin chung
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:category,category_id',
            'price' => 'required|integer|min:0',
            'status' => 'required|in:hiện,ẩn',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // 10MB
        ], [
            'product_name.required' => 'Tên sản phẩm là bắt buộc.',
            'product_name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'price.required' => 'Giá bán là bắt buộc.',
            'price.integer' => 'Giá bán phải là số nguyên.',
            'price.min' => 'Giá bán không được âm.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'image_url.image' => 'File phải là hình ảnh.',
            'image_url.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png.',
            'image_url.max' => 'Kích thước hình ảnh không được vượt quá 10MB.',
        ]);

        // Validate các trường detail (tùy chọn, max 255 ký tự)
        $detailRules = [];
        if ($request->has('details')) {
            foreach ($request->input('details') as $key => $value) {
                $detailRules["details.{$key}"] = 'nullable|string|max:255';
            }
        }

        $validatedDetails = $request->validate($detailRules);

        DB::beginTransaction();
        try {
            // Xử lý upload ảnh
            if ($request->hasFile('image_url')) {
                $image = $request->file('image_url');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                $validated['image_url'] = $imageName;
            }

            // Tạo sản phẩm
            $product = Product::create($validated);

            // Tạo detail record nếu có thông tin detail
            if (!empty($validatedDetails['details'])) {
                $relationName = Product::getRelationName($validated['category_id']);
                if ($relationName) {
                    $detailData = [];
                    foreach ($validatedDetails['details'] as $key => $value) {
                        if (!empty($value)) {
                            if (in_array($key, ['do_phan_giai'])) {
                                $value = str_replace(' ', '', $value);
                            }

                            $detailData[$key] = $value;
                        }
                    }

                    if (!empty($detailData)) {
                        $product->$relationName()->create($detailData);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.product.index')
                ->with('success', 'Tạo sản phẩm thành công!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi tạo sản phẩm: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Xem chi tiết sản phẩm
     * @param mixed $productId
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($productId)
    {
        try {
            $product = Product::with(['category'])->findOrFail($productId);

            // Lấy thông tin chi tiết theo danh mục
            $productDetail = null;
            $detailAttributes = [];

            $relationName = Product::getRelationName($product->category_id);
            if ($relationName) {
                $product->load($relationName);
                $productDetail = $product->$relationName;

                // Lấy attributes chi tiết
                $modelClass = Product::$categoryMapping[$product->category_id]['model'] ?? null;
                if ($modelClass && method_exists($modelClass, 'getDetailAttributes')) {
                    $detailAttributes = $modelClass::getDetailAttributes();
                }
            }

            // Lấy thông tin đơn hàng (giả sử có quan hệ orders)
            // Thay thế bằng quan hệ thực tế của bạn
            $orders = collect(); // Mặc định empty collection
            // $orders = $product->orders()->withPivot('quantity', 'price')->get();

            return view('admin.product.show', compact(
                'product',
                'productDetail',
                'detailAttributes',
                'orders'
            ));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.product.index')
                ->with('error', 'Không tìm thấy sản phẩm với ID: ' . $productId);
        } catch (\Exception $e) {
            return redirect()->route('admin.product.index')
                ->with('error', 'Đã xảy ra lỗi khi tải thông tin sản phẩm: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị form cập nhật sản phẩm
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $product = Product::with(['category'])->findOrFail($id);

        // Lấy thông tin chi tiết theo danh mục
        $productDetail = null;
        $detailAttributes = [];

        $relationName = Product::getRelationName($product->category_id);
        if ($relationName) {
            $product->load($relationName);
            $productDetail = $product->$relationName;

            // Lấy attributes chi tiết
            $modelClass = Product::$categoryMapping[$product->category_id]['model'] ?? null;
            if ($modelClass && method_exists($modelClass, 'getDetailAttributes')) {
                $detailAttributes = $modelClass::getDetailAttributes();
            }
        }

        return view('admin.product.edit', compact(
            'product',
            'productDetail',
            'detailAttributes'
        ));
    }

    /**
     * Xử lý cập nhật sản phẩm
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate thông tin chung
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'status' => 'required|in:hiện,ẩn,đã xóa',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // 10MB
            'remove_image' => 'nullable|boolean',
        ], [
            'product_name.required' => 'Tên sản phẩm là bắt buộc.',
            'product_name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'price.required' => 'Giá bán là bắt buộc.',
            'price.integer' => 'Giá bán phải là số nguyên.',
            'price.min' => 'Giá bán không được âm.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'image_url.image' => 'File phải là hình ảnh.',
            'image_url.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png.',
            'image_url.max' => 'Kích thước hình ảnh không được vượt quá 10MB.',
        ]);

        // Validate các trường detail (tùy chọn, max 255 ký tự)
        $detailRules = [];
        if ($request->has('details')) {
            foreach ($request->input('details') as $key => $value) {
                $detailRules["details.{$key}"] = 'nullable|string|max:255';
            }
        }

        $validatedDetails = $request->validate($detailRules);

        DB::beginTransaction();
        try {
            // Xử lý upload ảnh
            if ($request->has('remove_image') && $request->remove_image) {
                // Xóa ảnh cũ nếu tồn tại
                if ($product->image_url && file_exists(public_path('images/' . $product->image_url))) {
                    unlink(public_path('images/' . $product->image_url));
                }
                $validated['image_url'] = null;
            } elseif ($request->hasFile('image_url')) {
                // Xóa ảnh cũ nếu tồn tại
                if ($product->image_url && file_exists(public_path('images/' . $product->image_url))) {
                    unlink(public_path('images/' . $product->image_url));
                }

                // Lưu ảnh mới
                $image = $request->file('image_url');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                $validated['image_url'] = $imageName;
            } else {
                // Giữ ảnh cũ
                unset($validated['image_url']);
            }

            // Cập nhật thông tin sản phẩm
            $product->update($validated);

            // Cập nhật detail record nếu có thông tin detail
            if (!empty($validatedDetails['details'])) {
                $relationName = Product::getRelationName($product->category_id);
                if ($relationName) {
                    $detailData = [];
                    foreach ($validatedDetails['details'] as $key => $value) {
                        // Chỉ cập nhật nếu có giá trị, nếu không thì giữ nguyên
                        if (!is_null($value)) {
                            if (in_array($key, ['do_phan_giai'])) {
                                $value = str_replace(' ', '', $value);
                            }

                            $detailData[$key] = $value;
                        }
                    }

                    if (!empty($detailData)) {
                        // Kiểm tra xem detail đã tồn tại chưa
                        if ($product->$relationName) {
                            $product->$relationName()->update($detailData);
                        } else {
                            $product->$relationName()->create($detailData);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.product.show', $product->product_id)
                ->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật sản phẩm: ' . $e->getMessage())
                ->withInput();
        }
    }
}
