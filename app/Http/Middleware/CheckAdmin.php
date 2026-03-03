<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Kiểm tra đã đăng nhập chưa
        if (!Auth::guard('employee')->check()) {
            return redirect()->route('admin.login')  // Đúng route
                ->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // 2. Kiểm tra có phải Ban Quản lý không
        $employee = Auth::guard('employee')->user();

        if ($employee->department !== 'Ban Quản lý') {
            // Đăng xuất hoặc redirect về trang không có quyền
            return back()->with('error', 'Bạn không có quyền truy cập khu vực quản lý.');
        }

        return $next($request);
    }
}
