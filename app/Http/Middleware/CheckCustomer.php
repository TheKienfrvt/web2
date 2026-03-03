<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra không cho truy cập tính năng khách hàng nếu là nhân viên
        if (Auth::guard('employee')->check()) {
            return back()->with('error', 'Admin không thể truy cập trang khách hàng');
        }

        // Kiểm tra không cho truy cập nếu chưa đăng nhập
        if(!Auth::check()) {
            return redirect('/login')->with('error', 'Bạn cần đăng nhập để tiếp tục');
        }

        return $next($request);
    }
}
