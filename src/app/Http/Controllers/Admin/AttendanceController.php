<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    /**
     * 日次勤怠一覧（管理者）
     * ?date=YYYY-MM-DD を受け取り。未指定は本日。
     */
    public function list(Request $request)
    {
        // 入力バリデーション（任意の日付／未指定可）
        $validated = $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $date = isset($validated['date'])
            ? Carbon::parse($validated['date'])->startOfDay()
            : now()->startOfDay();

        // user と restRecords を読み込み、当日分を取得。並び順は氏名→開始時刻
        $records = Attendance::with(['user', 'restRecords'])
            ->whereDate('work_date', $date->toDateString())
            ->orderBy(User::select('name')->whereColumn('users.id', 'attendances.user_id')) // サブクエリでname順
            ->orderBy('start_time')
            ->get();

        return view('admin.attendance.index', compact('date', 'records'));
    }

    /**
     * 勤怠詳細（管理者）
     */
    public function detail(int $id)
    {
        $attendance = Attendance::with(['user', 'restRecords'])
            ->findOrFail($id);

        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * スタッフ別の勤怠一覧（管理者）
     */
    public function staffAttendance(int $staffId)
    {
        // 該当ユーザーの存在確認
        $staff = User::findOrFail($staffId);

        // 直近30日を例に取得（必要に応じて期間は調整）
        $attendances = Attendance::with('restRecords')
            ->where('user_id', $staffId)
            ->orderByDesc('work_date')
            ->orderBy('start_time')
            ->paginate(20); // ページネーション推奨

        // ビュー名を admin/attendance/staff.blade.php に統一
        return view('admin.attendance.staff', compact('staff', 'attendances'));
    }
}
