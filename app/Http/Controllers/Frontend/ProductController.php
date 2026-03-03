<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductFilterService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Hiển thi chi tiết sản phẩm
     * @param mixed $productId
     * @return \Illuminate\Contracts\View\View
     */
    public function show($productId)
    {
        // Lấy sản phẩm với tất cả thông tin liên quan
        // ::whith sẽ eager load các quan hệ để tránh N+1 query problem
        // ví dụ nếu là Laptop thì sẽ eager load category & laptopDetail
        // sẽ có thể truy cập $product->category và $product->laptopDetail
        /*
        [
            'product_id]' => 1,
            'product_name' => 'Laptop AAA',
            'category_id' => 'Laptop',
            ...các thuộc tính khác của product
            'category' => [
                'category_id' => 'Laptop',
                'category_name' => 'Laptop',
                ...các thuộc tính khác của category
            ]
            'laptopDetail' => [
                'product_id' => 1,
                'cpu' => 'Intel',
                ..các thuộc tính khác của laptopDetail
            ]
        ]
        */
        $product = Product::with(['category'])
            ->where('product_id', $productId)
            ->active()  //chỉ lấy các sản phẩm đang hiện
            ->first();  // lấy 1 sản phẩm

        // echo '<pre>';
        // print_r($product->category->category_id);
        // echo '</pre>';
        // die;

        // Nếu không tìm thấy sản phẩm, trả về 404
        if (!$product) {
            abort(404, 'Sản phẩm không tồn tại hoặc đã bị ẩn');
        }

        

        // Lấy sản phẩm cùng loại (cùng category) để gợi ý
        $relatedProducts = Product::with(['category'])
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $productId)
            ->active()
            ->limit(8)
            ->get();

        // Tăng lượt xem sản phẩm (nếu có cột view_count)
        // $product->increment('view_count');

        return view('frontend.product.show', compact('product', 'relatedProducts'));
    }

    /**
     * Hiển thị danh sách sản phẩm theo danh mục
     * @param string $category_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function indexByCategory(String $category_id, Request $request)
    {
        $category = Category::findOrFail($category_id);

        // 1. Lấy filters config
        $filters = ProductFilterService::getFiltersForCategory($category->category_id);

        // 2. Khởi tạo query
        /**
         * ::with() eager loading tránh N+1 query problem
         */
        $products = Product::with(['category', Product::getRelationName($category_id)])
            ->where('category_id', $category->category_id)
            ->active();

        // 3. Áp dụng filters (thuộc tính detail + giá)
        $products = $this->applyCustomFilters($products, $request, $filters, $category->category_id);

        // 4. Thực thi query
        /**
         * ->paginate(12) để phân trang, mỗi trang 12 sản phẩm
         * ->appends($request->query() có tác dụng giữ lại các tham số filter khi phân trang
         * Khi KHÔNG có appends(): Khi click trang 2 sẽ mất hết filter
         * Khi CÓ appends(): Khi click trang 2 sẽ còn filter
         */
        $products = $products->paginate(12)->appends($request->query());

        return view('frontend.product.index', compact('products', 'category', 'filters'));
    }

    /**
     * Áp dụng custom filters (thuộc tính detail + khoảng giá)
     */
    private function applyCustomFilters($query, $request, $filters, $categoryId)
    {
        // A. FILTER THUỘC TÍNH CHI TIẾT (từ bảng detail)
        foreach ($filters as $attribute => $filter) {
            if ($request->filled($attribute)) {
                $selectedValues = (array) $request->input($attribute);

                if (!empty($selectedValues)) {
                    $relation = Product::getRelationName($categoryId);

                    if ($relation) {
                        $query->whereHas($relation, function ($q) use ($attribute, $selectedValues) {
                            $q->whereIn($attribute, $selectedValues);
                        });
                    }
                }
            }
        }

        // B. FILTER KHOẢNG GIÁ (từ bảng product)
        $this->applyPriceFilter($query, $request);

        // C. FILTER SẮP XẾP THEO GIÁ
        $this->applySorting($query, $request);

        return $query;
    }

    /**
     * Filter theo khoảng giá
     */
    private function applyPriceFilter($query, $request)
    {
        // Filter theo khoảng giá nhập tay
        if ($request->filled('giaThap') || $request->filled('giaCao')) {
            $minPrice = (int) $request->input('giaThap', 0);
            $maxPrice = (int) $request->input('giaCao', 0);

            // Đảm bảo minPrice <= maxPrice
            if ($minPrice > 0 && $maxPrice > 0 && $minPrice <= $maxPrice) {
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            } elseif ($minPrice > 0) {
                $query->where('price', '>=', $minPrice);
            } elseif ($maxPrice > 0) {
                $query->where('price', '<=', $maxPrice);
            }
        }

        return $query;
    }

    /**
     * Sắp xếp sản phẩm
     */
    private function applySorting($query, $request)
    {
        $sortBy = $request->input('sapXep', 'mac-dinh');

        switch ($sortBy) {
            case 'gia-thap-den-cao':
                $query->orderBy('price', 'asc');
                break;
            case 'gia-cao-den-thap':
                $query->orderBy('price', 'desc');
                break;
            case 'moi-nhat':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                // $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }
}
