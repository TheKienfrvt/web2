<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // kiểm tra đã đăng nhập chưa
        if (!Auth::guard('employee')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Bạn cần đăng nhập với tư cách quản trị viên');
        }

        return $next($request);
    }
}
