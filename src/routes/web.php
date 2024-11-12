<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\WorkTimeController;
use App\Http\Controllers\RestTimeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\EmailVerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/', [WorkTimeController::class, 'index']);
    Route::post('/work/start', [WorkTimeController::class, 'startWork'])->name('work.start');
    Route::post('/work/end', [WorkTimeController::class, 'endWork'])->name('work.end');

    Route::post('/rest/start', [RestTimeController::class, 'startRest'])->name('rest.start');
    Route::post('/rest/end', [RestTimeController::class, 'endRest'])->name('rest.end');

    Route::get('/userpage', [UserController::class, 'index'])->name('user.index');
    Route::get('/userpage/{id}', [UserController::class, 'show'])->name('user.show');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
});

Route::controller(EmailVerificationController::class)
->prefix('email')->name('verification.')->group(function () {
// 確認メール送信
Route::post('verification-notification', 'notification');
// 確認メールリンクの検証
Route::get('verification/{id}/{hash}', 'verification');
// 確認メール再送
Route::get('resend_verify_email', 'resendVerifyEmail');
// セッションリセット（確認メールが届かないなどイレギュラー時の対応）
Route::get('reset', 'resetSession');
});

