<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    /**
     * Hiển thị trang cấu hình
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.config.index', compact('categories'));
    }

    /**
     * Xử lý cấu hình hiển thị danh mục
     * @param mixed $categotyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeCategoryStatus($categotyId)
    {
        $category = Category::find($categotyId);
        if ($category) {
            if ($category->status == 'hiện') {
                $category->update(['status' => 'ẩn']);
            } elseif ($category->status == 'ẩn') {
                $category->update(['status' => 'hiện']);
            }
        }
        return redirect()->route('admin.config.index')->with('success', 'Cập nhật trạng thái danh mục thành công.');
    }
}
