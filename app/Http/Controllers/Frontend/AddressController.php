<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    /**
     * Hiển thị danh sách địa chỉ của user
     */
    public function index()
    {
        $user = Auth::user();

        // Auth::user(): Thay thế cho $_SESSION["user"] - lấy thông tin user đã đăng nhập
        // Auth::user()->username: Truy cập thuộc tính username của user
        $addresses = Address::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('frontend.address.index', compact('addresses', 'user'));
    }

    /**
     * Thêm địa chỉ mới
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
        ], [
            'address.required' => 'Vui lòng nhập địa chỉ',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự'
        ]);

        // Tạo địa chỉ mới với user_id hiện tại
        Address::create([
            'user_id' => Auth::id(),
            'address' => $request->address
        ]);

        return redirect()->route('address')->with('success', 'Thêm địa chỉ thành công!');
    }

    /**
     * cập nhật địa chỉ
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Address $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Address $address)
    {
        // $validated là mảng
        // ['address' => 'TPHCM']
        $validated = $request->validate([
            'address' => 'required|string|max:255'
        ], [
            'address.required' => 'Vui lòng nhập địa chỉ',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự'
        ]);

        $address->update($validated);

        return redirect()->route('address')->with('success', 'Cập nhật địa chỉ thành công!');
    }

    /**
     * xóa địa chỉ
     * @param \App\Models\Address $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Address $address)
    {
        $address->delete();

        return redirect()->route('address')->with('success', 'Xóa địa chỉ thành công!');
    }
}
