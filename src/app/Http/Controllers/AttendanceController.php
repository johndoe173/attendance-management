<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendancPunchRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\RestRecord;
use App\Models\StampCorrectionRequest;

class AttendanceController extends Controller
{
    public function punch()
    {
        // ユーザーの打刻ステータスなどを取得
        $status = $this->determineAttendanceStatus();

        $labels = [
            'before' => '出勤前',
            'working'     => '勤務中',
            'break'     => '休憩中',
            'after'  => '退勤済',
        ];

        $statusLabel = $labels[$status] ?? '不明';

        return view('attendance.punch', [
            'status' => $status,
            'statusLabel' => $statusLabel,
        ]);
    }

    public function start(AttendancPunchRequest $request)
    {
        $user = Auth::user();

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => Carbon::today(),
            'start_time' => Carbon::now(),
            'status' => '出勤中',
        ]);

        return redirect()->route('attendance.punch');
    }

    public function end(AttendancPunchRequest $request)
    {
        $user = Auth::user();

        // 当日の出勤中レコードを取得
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', Carbon::today())
            ->where('status', '出勤中')
            ->latest()
            ->first();

        if (!$attendance) {
            return redirect()->route('attendance.punch')->with('error', '出勤記録が見つかりません。');
        }

        // 退勤時刻を記録し、ステータスを更新
        $attendance->update([
            'end_time' => Carbon::now(),
            'status'   => '退勤済',
        ]);

        return redirect()->route('attendance.punch')->with('success', '退勤しました。');
    }

    public function breakStart(AttendancPunchRequest $request)
    {
        $user = Auth::user();

        // 今日の勤務中レコードを取得
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', Carbon::today())
            ->where('status', '出勤中')
            ->latest()
            ->first();

        if (!$attendance) {
            return redirect()->route('attendance.punch')->with('error', '出勤中のレコードが見つかりません。');
        }

        // 休憩記録を作成
        RestRecord::create([
            'attendance_id' => $attendance->id,
            'break_start' => now(),
        ]);

        // 勤怠ステータスを「休憩中」に更新
        $attendance->update(['status' => '休憩中']);

        return redirect()->route('attendance.punch')->with('success', '休憩開始しました。');
    }

    public function breakEnd(AttendancPunchRequest $request)
    {
        $user = Auth::user();

        // 今日の「休憩中」勤怠レコード
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', Carbon::today())
            ->where('status', '休憩中')
            ->latest()
            ->first();

        if (!$attendance) {
            return redirect()->route('attendance.punch')->with('error', '休憩中のレコードが見つかりません。');
        }

        // 最後の未終了の休憩レコードを取得して終了時間を記録
        $rest = $attendance->restRecords()
            ->whereNull('break_end')
            ->latest()
            ->first();

        if (!$rest) {
            return redirect()->route('attendance.punch')->with('error', '未終了の休憩が見つかりません。');
        }

        $rest->update([
            'break_end' => Carbon::now(),
        ]);

        // 勤怠ステータスを「出勤中」に戻す
        $attendance->update(['status' => '出勤中']);

        return redirect()->route('attendance.punch')->with('success', '休憩終了しました。');
    }

    public function detail($id)
    {
        $attendance = Attendance::with('user', 'restRecords')->findOrFail($id);
        $user = $attendance->user;

        $breaks = $attendance->restRecords->map(function($r) {
            return [
                'start' => optional($r->break_start)->format('H:i'),
                'end' => optional($r->break_end)->format('H:i'),
            ];
        })->toArray();

        return view('attendance.show', compact('attendance', 'user', 'breaks'));
    }

    /**
     * ユーザーの打刻状態を判定する仮メソッド
     */
    private function determineAttendanceStatus()
    {
        $user = Auth::user();

        $latest = $user->attendances()->latest()->first();

        if (!$latest) {
            return 'before';
        }

        $map = [
            '勤務外' => 'before',
            '出勤中' => 'working',
            '休憩中' => 'break',
            '退勤済' => 'after',
        ];

        return $map[$latest->status] ?? 'unknown';
    }

    
    public function list(AttendancPunchRequest $request)
    {
        $user = auth()->user();
        $month = $request->input('month', now()->format('Y-m'));

        $attendances = $user->attendances()
            ->whereMonth('work_date', Carbon::parse($month)->month)
            ->whereYear('work_date', Carbon::parse($month)->year)
            ->orderBy('work_date', 'asc')
            ->get();

        return view('attendance.index', [
            'attendances' => $attendances,
            'currentMonth' => $month,
        ]);
    }

    public function update(AttendancPunchRequest $request, $id)
    {
        $attendance = Attendance::with('restRecords')->findOrFail($id);

        // 勤怠情報を比較し、変更があれば申請作成
        $hasChanges = (
            $attendance->start_time != $request->input('start_time') ||
            $attendance->end_time != $request->input('end_time') ||
            $attendance->note != $request->input('requested_note')
        );

        if ($hasChanges) {
            StampCorrectionRequest::create([
                'user_id' => auth()->id(),
                'attendance_id' => $attendance->id,
                'target_date' => $attendance->work_date,
                'requested_start_time' => $request->input('start_time'), 
                'requested_end_time'   => $request->input('end_time'),
                'reason' => $request->input('requested_note'),
                'status' => '承認待ち',
            ]);

            return redirect()->route('attendance.show', $attendance->id)
                             ->with('success', '修正申請を送信しました。');
        }

        return redirect()->route('attendance.show', $attendance->id)
                         ->with('info', '変更が検出されませんでした。');
    }


}
