<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse;
use App\Http\Responses\VerifyEmailViewResponse as CustomVerifyEmailViewResponse;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(VerifyEmailViewResponse::class, CustomVerifyEmailViewResponse::class);
    }

    public function boot(): void
    {
        // ✅ バリデーション追加（ログイン時）
        Fortify::authenticateUsing(function (Request $request) {
            Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required', 'string', 'min:8'],
            ], [
                'email.required' => 'メールアドレスを入力してください',
                'password.required' => 'パスワードを入力してください',
                'password.min' => 'パスワードは8文字以上で入力してください',
            ])->validate();

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });

        // ✅ バリデーション追加（会員登録時）
        Fortify::createUsersUsing(\App\Actions\Fortify\CreateNewUser::class);

        // ログイン・会員登録ビュー
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::loginView(fn () => auth()->check() ? redirect('/attendance') : view('auth.login'));

        // リダイレクト先
        Fortify::redirects('login', '/attendance');
        Fortify::redirects('register', '/email/verify');
        Fortify::redirects('email-verification', '/attendance');

        // ログインレート制限
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by((string)$request->email . $request->ip());
        });
    }
}
