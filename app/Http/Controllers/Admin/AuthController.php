<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        if (Auth::guard('employee')->check()) {
            return redirect()->route('admin.dashboard')->with('success', 'bạn đã đăng nhập rồi!');
        } elseif (Auth::check()) {
            return back()->with('error', 'Bạn đang đăng nhập với tài khoản khách hàng.');
        }

        return view('admin.auth.login');
    }

    /**
     * Xử lý đăng nhập
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate dữ liệu
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu'
        ]);

        // Sử dụng guard 'employee' đã cấu hình
        if (Auth::guard('employee')->attempt($credentials)) {
            // Đăng nhập thành công, lấy thông tin user
            $employee = Auth::guard('employee')->user();

            // Kiểm tra trạng thái tài khoản
            if ($employee->status !== 'Active') {
                // Nếu tài khoản không active, logout và thông báo
                Auth::guard('employee')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Xác định thông báo dựa trên status
                $message = $this->getStatusMessage($employee->status);

                return back()->withErrors([
                    'email' => $message,
                ])->withInput($request->only('email'));
            }

            // Tài khoản active, tiếp tục đăng nhập
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Đăng nhập thành công!');
        }

        // Đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác hoặc tài khoản không có quyền admin.',
        ])->withInput($request->only('email'));
    }

    /**
     * Lấy thông báo dựa trên trạng thái tài khoản
     * @param string $status
     * @return string
     */
    private function getStatusMessage($status)
    {
        $messages = [
            'Inactive' => 'Tài khoản của bạn đã bị vô hiệu hóa.',
            'Pending' => 'Tài khoản của bạn đang chờ xét duyệt.',
            'Suspended' => 'Tài khoản của bạn đã bị tạm ngưng.',
            'Blocked' => 'Tài khoản của bạn đã bị khóa.',
            'Deleted' => 'Tài khoản không tồn tại.',
        ];

        return $messages[$status] ?? 'Tài khoản của bạn không thể đăng nhập.';
    }

    /**
     * Xử lý đăng xuất
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Đã đăng xuất thành công!');
    }
}
