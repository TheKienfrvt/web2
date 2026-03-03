<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách user
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by sex
        if ($request->has('sex') && $request->sex) {
            $query->where('sex', $request->sex);
        }

        // Filter by date of birth
        if ($request->has('dob') && $request->dob) {
            $query->where('dob', $request->dob);
        }

        $users = $query->where('status', '!=', 'đã xóa')->orderBy('user_id', 'desc')->paginate(20);

        return view('admin.user.index', compact('users'));
    }

    /**
     * Hiển thị form tạo user mới
     */
    public function create()
    {
        return view('admin.user.create');
    }

    // Hiển thị chi tiết khách hàng
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Format các trường ngày tháng
        $user->dob_formatted = $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d/m/Y') : null;
        $user->age = $user->dob ? \Carbon\Carbon::parse($user->dob)->age : null;

        return view('admin.user.show', compact('user'));
    }

    // Cập nhật trạng thái (khóa/mở tài khoản)
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:mở,khóa,đã xóa'
        ]);

        try {
            $user->update([
                'status' => $validated['status'],
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xử lý tạo user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|min:3|max:50|',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6|confirmed',
            'sex' => 'required|in:nam,nữ',
            'phone_number' => 'required|string|max:10',
            'dob' => 'nullable|date'
        ], [
            'username.required' => 'Tên đăng nhập là bắt buộc.',
            'username.min' => 'Tên đăng nhập phải có ít nhất 3 ký tự.',
            'username.max' => 'Tên đăng nhập không được vượt quá 50 ký tự.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'username.regex' => 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'sex.required' => 'Giới tính là bắt buộc.',
            'sex.in' => 'Giới tính không hợp lệ.',
            'phone_number.regex' => 'Số điện thoại không hợp lệ.',
            'dob.date' => 'Ngày sinh không hợp lệ.',
            'dob.before' => 'Ngày sinh phải là ngày trong quá khứ.'
        ]);

        try {
            // Tạo user mới
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'sex' => $validated['sex'],
                'phone_number' => $validated['phone_number'] ?? null,
                'dob' => $validated['dob'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tạo khách hàng thành công!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tạo khách hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị form edit user
     */
    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        return view('admin.user.edit', compact('user'));
    }

    // Xử lý cập nhật
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|min:6|max:255',
            'email' => 'required|email|unique:user,email,' . $id . ',user_id',
            'sex' => 'nullable|in:nam,nữ',
            'phone_number' => 'required|digits:10',
            'dob' => 'nullable|date|before:today',
            'status' => 'required|in:mở,khóa,đã xóa',
            'avatar_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean'
        ], [
            'username.required' => 'Tên đăng nhập là bắt buộc.',
            'username.min' => 'Tên đăng nhập phải có ít nhất 6 ký tự.',
            'username.max' => 'Tên đăng nhập không được vượt quá 255 ký tự.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'sex.in' => 'Giới tính không hợp lệ.',
            'phone_number.required' => 'Số điện thoại là bắt buộc.',
            'phone_number.digits' => 'Số điện thoại phải có đúng 10 chữ số.',
            'dob.date' => 'Ngày sinh không hợp lệ.',
            'dob.before' => 'Ngày sinh phải là ngày trong quá khứ.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'avatar_url.image' => 'File phải là hình ảnh.',
            'avatar_url.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'avatar_url.max' => 'Kích thước hình ảnh không được vượt quá 2MB.'
        ]);

        try {
            // Xử lý ảnh đại diện
            if ($request->has('remove_avatar') && $request->remove_avatar) {
                // Xóa ảnh cũ nếu tồn tại
                if ($user->avatar_url && Storage::exists('public/' . $user->avatar_url)) {
                    Storage::delete('public/' . $user->avatar_url);
                }
                $validated['avatar_url'] = null;
            } elseif ($request->hasFile('avatar_url')) {
                // Xóa ảnh cũ nếu tồn tại
                if ($user->avatar_url && Storage::exists('public/' . $user->avatar_url)) {
                    Storage::delete('public/' . $user->avatar_url);
                }

                // Lưu ảnh mới
                $avatarPath = $request->file('avatar_url')->store('avatars', 'public');
                $validated['avatar_url'] = $avatarPath;
            } else {
                // Giữ ảnh cũ
                unset($validated['avatar_url']);
            }

            // Cập nhật thông tin user
            $user->update($validated);

            return redirect()->route('admin.user.show', $user->user_id)
                ->with('success', 'Cập nhật thông tin thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật thông tin: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Kích hoạt/vô hiệu hóa user
     */
    public function toggleStatus($userId, Request $req)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'status' => $req->status
        ]);


        return redirect()->back()
            ->with('success', "Cập nhật trang thái thành công!");
    }

    /**
     * Xóa user
     */
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        // Không cho xóa chính mình
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        $user->update([
            'status' => 'đã xóa'
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', 'User đã được xóa!');
    }
}
