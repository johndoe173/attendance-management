<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
// use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
/**
* 確認メール送信画面
*/
    public function index(Request $request)
        {
            return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(RouteServiceProvider::HOME)
            : view('auth.verify-email-massage');
        }

/**
* 確認メール送信
*
* @param  Request  $request
* @return RedirectResponse|View
*/
public function notification(Request $request)
    {

    }

    /**
* メールリンクの検証
*
* @param  Request  $request
* @return RedirectResponse
*/

public function verification(EmailVerificationRequest $request): RedirectResponse
{
/** @var User $user */
$user = $request->user();
if ($user->hasVerifiedEmail()) {
return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
}
// email_verified_atカラムの更新
if ($user->markEmailAsVerified()) {
event(new Verified($user));
}
return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
}

/**
* 確認メール再送信画面
*/

public function resendVerifyEmail(Request $request)
{
session()->forget('status');
return view('auth.verify-email-massage');
}
public function resetSession(){
session()->flush();
return redirect()->route('login');
}
}