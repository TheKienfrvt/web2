<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $categories = Category::with(['products' => function ($query) {
            $query->where('status', 'hiện')
                ->orderBy('product_id', 'asc')
                ->limit(30); // giới hạn 30 sản phẩm mỗi danh mục
        }])
            ->where('status', 'hiện')
            ->get();

        return view('frontend.home.index', compact("categories"));
    }

    /**
     * Trang kết quả tìm kiếm với filter
     */
    public function search(Request $request)
    {
        $textSearch = $request->input('search', '');

        if (empty($textSearch)) {
            return redirect()->route('home');
        }

        // Khởi tạo query
        $products = Product::with(['category', 'laptopDetail', 'screenDetail', 'gpuDetail', 'headsetDetail', 'mouseDetail', 'keyboardDetail'])
            ->active();

        // Áp dụng tìm kiếm theo từ khóa
        $products->where(function ($query) use ($textSearch) {
            $query->where('product_name', 'LIKE', "%{$textSearch}%")
                ->orWhereHas('category', function ($q) use ($textSearch) {
                    $q->where('category_name', 'LIKE', "%{$textSearch}%");
                })
                ->orWhereHas('laptopDetail', function ($q) use ($textSearch) {
                    $q->where('thuong_hieu', 'LIKE', "%{$textSearch}%")
                        ->orWhere('cpu', 'LIKE', "%{$textSearch}%")
                        ->orWhere('ram', 'LIKE', "%{$textSearch}%");
                })
                ->orWhereHas('screenDetail', function ($q) use ($textSearch) {
                    $q->where('thuong_hieu', 'LIKE', "%{$textSearch}%")
                        ->orWhere('kich_thuoc_man_hinh', 'LIKE', "%{$textSearch}%");
                })
                ->orWhereHas('gpuDetail', function ($q) use ($textSearch) {
                    $q->where('thuong_hieu', 'LIKE', "%{$textSearch}%")
                        ->orWhere('gpu', 'LIKE', "%{$textSearch}%");
                })
                ->orWhereHas('headsetDetail', function ($q) use ($textSearch) {
                    $q->where('thuong_hieu', 'LIKE', "%{$textSearch}%")
                        ->orWhere('ket_noi', 'LIKE', "%{$textSearch}%");
                })
                ->orWhereHas('mouseDetail', function ($q) use ($textSearch) {
                    $q->where('thuong_hieu', 'LIKE', "%{$textSearch}%")
                        ->orWhere('ket_noi', 'LIKE', "%{$textSearch}%");
                })
                ->orWhereHas('keyboardDetail', function ($q) use ($textSearch) {
                    $q->where('thuong_hieu', 'LIKE', "%{$textSearch}%")
                        ->orWhere('ket_noi', 'LIKE', "%{$textSearch}%");
                });
        });

        // FILTER THEO DANH MỤC
        if ($request->filled('danh_muc')) {
            $products->where('category_id', $request->input('danh_muc'));
        }

        // FILTER THEO KHOẢNG GIÁ
        $this->applyPriceFilter($products, $request);

        // SẮP XẾP
        $this->applySorting($products, $request);

        // Thực thi query
        $products = $products->paginate(20)
            ->appends($request->query());

        // Lấy danh sách categories cho dropdown
        $categories = Category::active()->get();

        return view('frontend.search.index', compact('products', 'textSearch', 'categories'));
    }

    /**
     * Áp dụng filter khoảng giá
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
     * Áp dụng sắp xếp
     */
    private function applySorting($query, $request)
    {
        $sortBy = $request->input('sap_xep', 'mac_dinh');

        switch ($sortBy) {
            case 'gia_tang':
                $query->orderBy('price', 'asc');
                break;
            case 'gia_giam':
                $query->orderBy('price', 'desc');
                break;
            case 'moi_nhat':
                $query->orderBy('created_at', 'desc');
                break;
            case 'ten_az':
                $query->orderBy('product_name', 'asc');
                break;
            case 'ten_za':
                $query->orderBy('product_name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }
}
