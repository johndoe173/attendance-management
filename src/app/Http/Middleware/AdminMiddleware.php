<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // ログイン済みでない場合はログインページにリダイレクト
        return redirect()->route('admin.login')->with('error', '管理者としてログインしてください');
    }
}
