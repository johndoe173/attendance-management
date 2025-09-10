<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // ← 追加（logoutで使用）
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StampCorrectionController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StampCorrectionController as AdminStampCorrectionController;

/*
|--------------------------------------------------------------------------
| 一般ユーザー
|--------------------------------------------------------------------------
|
| ※ 重複していた GET /attendance を整理。
|   - 打刻画面は /attendance/punch に統一
|   - 一覧は /attendance/list に統一
|   - 詳細は /attendance/{attendance}（idでも可）
| ルート名の重複（attendance.index）も解消。
|
*/

Route::middleware(['auth'])->group(function () {
    // 打刻画面（punch）: ここをログイン後のデフォルトにするのが分かりやすい
    Route::get('/attendance/punch', [AttendanceController::class, 'punch'])
        ->name('attendance.punch');

    // 勤怠打刻アクション
    Route::post('/attendance/start', [AttendanceController::class, 'start'])->name('attendance.start');
    Route::post('/attendance/end', [AttendanceController::class, 'end'])->name('attendance.end');
    Route::post('/attendance/break_start', [AttendanceController::class, 'breakStart'])->name('attendance.break_start');
    Route::post('/attendance/break_end', [AttendanceController::class, 'breakEnd'])->name('attendance.break_end');

    // 勤怠一覧
    Route::get('/attendance/list', [AttendanceController::class, 'index'])
        ->name('attendance.list'); 

    // 修正申請保存
    Route::post('/stamp_correction_request', [StampCorrectionController::class, 'store'])
        ->name('stamp_requests.store');

    // 詳細・修正
    Route::get('/attendance/{attendance}', [AttendanceController::class, 'detail'])
        ->whereNumber('attendance')
        ->name('attendance.show');
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])
        ->whereNumber('attendance')
        ->name('attendance.update');

    // 修正申請一覧（ユーザー）
    Route::get('/stamp_correction_request/list', [StampCorrectionController::class, 'userList'])
        ->name('stamp_requests.index');

    // ログアウト
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});

// 「ログイン直後 / デフォルトの到達先」を必要なら整備（任意）
// Fortifyのredirects(['login' => route('attendance.punch')]) と揃えるなら↓を使う
Route::redirect('/attendance', '/attendance/punch')->name('attendance.redirect'); // 旧トップをpunchへ集約

// ルートページ（ExampleTest 対応：200 を返す）
Route::view('/', 'welcome')->name('home.welcome');


/*
|--------------------------------------------------------------------------
| 管理者専用ルート（admin prefix）
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    // 管理者ログイン（ミドルウェアなし）
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
});

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // 勤怠管理（管理者）
    Route::get('/attendance/list', [AdminAttendanceController::class, 'list'])->name('attendance.index');
    Route::get('/attendance/staff/{id}', [AdminAttendanceController::class, 'staffAttendance'])
        ->whereNumber('id')
        ->name('attendance.staff');
    Route::get('/attendance/{attendance}', [AdminAttendanceController::class, 'detail'])
        ->whereNumber('attendance')
        ->name('attendance.show');

    // スタッフ一覧
    Route::get('/staff/list', [StaffController::class, 'index'])->name('staff.index');

    // 修正申請（管理者）
    Route::get('/stamp_correction_request/list', [AdminStampCorrectionController::class, 'adminList'])
        ->name('stamp_request.index');
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}', [AdminStampCorrectionController::class, 'approveForm'])
        ->whereNumber('attendance_correct_request')
        ->name('stamp_request.approve.form');
    Route::post('/stamp_correction_request/approve/{attendance_correct_request}', [AdminStampCorrectionController::class, 'approve'])
        ->whereNumber('attendance_correct_request')
        ->name('stamp_request.approve');
});
