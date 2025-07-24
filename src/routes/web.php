<?php

use Illuminate\Support\Facades\Route;
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
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // 打刻画面（punch）
    Route::get('/attendance', [AttendanceController::class, 'punch'])->name('attendance.punch');

    // 勤怠打刻系
    Route::post('/attendance/start', [AttendanceController::class, 'start'])->name('attendance.start');
    Route::post('/attendance/end', [AttendanceController::class, 'end'])->name('attendance.end');
    Route::post('/attendance/break_start', [AttendanceController::class, 'breakStart'])->name('attendance.break_start');
    Route::post('/attendance/break_end', [AttendanceController::class, 'breakEnd'])->name('attendance.break_end');

    // 勤怠一覧（list + 月切替）
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.index');

    // 修正申請保存用
    Route::post('/stamp_correction_request', [StampCorrectionController::class, 'store'])->name('stamp_requests.store');

    // 詳細・修正
    Route::get('/attendance/{id}', [AttendanceController::class, 'detail'])->name('attendance.show');
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');

    // 修正申請一覧
    Route::get('/stamp_correction_request/list', [StampCorrectionController::class, 'userList'])->name('stamp_requests.index');

    // ログアウト
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});


/*
|--------------------------------------------------------------------------
| 管理者専用ルート（admin prefix）
|--------------------------------------------------------------------------
*/

// 管理者ログイン画面・ログイン処理（ミドルウェアなし）
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
});

// ログイン後に入れる管理者専用ページ
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // 勤怠管理
    Route::get('/attendance/list', [AdminAttendanceController::class, 'list'])->name('attendance.index');
    Route::get('/attendance/staff/{id}', [AdminAttendanceController::class, 'staffAttendance'])->name('attendance.staff');
    Route::get('/attendance/{id}', [AdminAttendanceController::class, 'detail'])->name('attendance.show');

    // スタッフ一覧
    Route::get('/staff/list', [StaffController::class, 'index'])->name('staff.index');

    // 修正申請
    Route::get('/stamp_correction_request/list', [AdminStampCorrectionController::class, 'adminList'])->name('stamp_request.index');
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}', [AdminStampCorrectionController::class, 'approveForm'])->name('stamp_request.approve.form');
    Route::post('/stamp_correction_request/approve/{attendance_correct_request}', [AdminStampCorrectionController::class, 'approve'])->name('stamp_request.approve');
});
