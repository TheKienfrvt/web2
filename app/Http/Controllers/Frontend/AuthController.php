<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng ký
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showRegisterForm()
    {
        $isAdmin = false;
        $alert = '';
        $message = '';

        if (Auth::guard('employee')->check()) {
            $isAdmin = true;
            $alert = 'warning';
            $message = 'Bạn đang đăng nhập với tư cách quản trị viện!';
        } elseif (Auth::check()) {
            return back()->with('warning', 'Bạn đã đăng nhập rồi!');
        }

        return view('frontend.auth.register', compact('isAdmin'))->with($alert, $message);
    }

    /**
     * Xử lý đăng ký
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        if (Auth::check() || Auth::guard('employee')->check()) {
            return redirect('/')->with('warning', 'Bạn đã đăng nhập rồi!');
        }

        // 1. Validate dữ liệu
        $request->validate([
            'username'      => 'required|string|min:6|max:255',
            'password'      => 'required|string|min:6|max:35',
            'repassword'    => 'required|string|same:password',
            'email'         => 'required|email|unique:user,email',
            'sex'           => 'nullable|in:Nam,Nữ',
            'dob'           => 'nullable|date',
            'phone_number'  => 'required|string|max:10',
            'status'        => 'in:mở,khóa,đã xóa'
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.min'      => 'Tên đăng nhập phải ít nhất 6 ký tự.',
            'username.max'      => 'Tên đăng nhập không quá 255 ký tự.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu phải ít nhất 6 ký tự.',
            'password.max'      => 'Mật khẩu không quá 35 ký tự.',
            'repassword.same'   => 'Mật khẩu nhập lại không khớp với mật khẩu đã nhập.',
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không đúng định dạng.',
            'email.unique'      => 'Email này đã được sử dụng, vui lòng chọn email khác.',
            'phone_number'      => 'Số điện thoại phải 10 chữ số.'
        ]);

        // 2. Tạo user
        User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'email' => $request->email,
            'sex' => $request->sex,
            'dob' => $request->dob,
            'phone_number' => $request->phone_number,
            'status' => $request->status ?? 'mở',
        ]);

        // 3. Redirect sau khi thành công
        return redirect('/login')->with('success', 'Đăng ký thành công!');
    }

    /**
     * Hiển thị form đăng nhập
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        $isAdmin = false;
        $alert = '';
        $message = '';

        if (Auth::guard('employee')->check()) {
            $isAdmin = true;
            $alert = 'warning';
            $message = 'Bạn đang đăng nhập với tư cách quản trị viện!';
        } elseif (Auth::check()) {
            return back()->with('warning', 'Bạn đã đăng nhập rồi!');
        }

        return view('frontend.auth.login', compact('isAdmin'))->with($alert, $message);
    }

    /**
     * Xử lý đăng nhập
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        if (Auth::check() || Auth::guard('employee')->check()) {
            return redirect('/')->with('warning', 'Bạn đã đăng nhập rồi!');
        }

        // Validate dữ liệu
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu'
        ]);

        // Tìm user theo email và kiểm tra trạng thái trước
        $user = User::where('email', $credentials['email'])->first();

        if ($user && in_array($user->status, ['khóa', 'đã xóa'])) {
            return back()
                ->withInput($request->only('email', 'password'))
                ->withErrors([
                    'error' => 'Tài khoản này đã bị vô hiệu hóa.'
                ]);
        }

        // Kiểm tra đăng nhập
        if (Auth::attempt($credentials)) {
            // ĐĂNG NHẬP THÀNH CÔNG
            $request->session()->regenerate();

            // "Chuyển hướng đến URL mà user muốn truy cập ban đầu, nếu không có thì chuyển hướng đến trang chủ ('/')"
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công!');
        }

        // Đăng nhập thất bại - GIỮ LẠI DỮ LIỆU CŨ
        return back()
            ->withInput($request->only('email', 'password'))
            ->withErrors([
                'error' => 'Thông tin đăng nhập không chính xác.'
            ]);
    }

    /**
     * Xử lý đăng xuất
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Đăng xuất thành công!');
    }

    /**
     * Xem thông tin cá nhân
     * @return \Illuminate\Contracts\View\View
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('frontend.auth.profile', compact('user'));
    }

    /**
     * Cập nhật thông tin cá nhân
     * @param \Illuminate\Http\Request $request
     * @param mixed $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $user_id)
    {
        // validate thông tin cập nhật
        $request->validate([
            'username'      => 'required|string|min:6|max:255',
            'email'         => 'required|email|unique:user,email,' . $user_id . ',user_id',
            'sex'           => 'nullable|in:nam,nữ',
            'dob'           => 'nullable|date',
            'phone_number'  => 'required|string|min:8|max:10',
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.min'      => 'Tên đăng nhập phải ít nhất 6 ký tự.',
            'username.max'      => 'Tên đăng nhập không quá 255 ký tự.',
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không đúng định dạng.',
            'email.unique'      => 'Email này đã được sử dụng, vui lòng chọn email khác.',
            'phone_number'      => 'Số điện thoại không hợp lệ.'
        ]);

        $user = User::findOrFail($user_id);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'sex' => $request->sex,
            'dob' => $request->dob,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('profile.show')->with('success', 'Cập nhật user thành công');
    }
}
