<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
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
        // 🔐 ログイン時のバリデーション + 認証
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
            return $user; // ✅ Fortify が自動で Auth::login() する
            }

            return null;
        });

        // 📩 メール認証イベント
        Event::listen(Verified::class, function ($event) {
            session(['verified_redirect' => '/attendance/punch']);
        });

        // 📝 会員登録のバリデーション/作成
        Fortify::createUsersUsing(CreateNewUser::class);

        // 🖼️ ビューはビューだけ返す
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::loginView(fn () => view('auth.login'));

        // ⏱️ ログインレート制限
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by((string) $request->email . $request->ip());
        });

        // ✅ リダイレクト設定（統一）
        Fortify::redirects('login', '/attendance/punch');
        Fortify::redirects('register', '/email/verify');
        Fortify::redirects('email-verification', '/attendance/punch');
    }
}
