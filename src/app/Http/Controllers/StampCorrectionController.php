<?php

namespace App\Http\Controllers;

use App\Http\Requests\StampCorrectionRequest as StampCorrectionForm;
use App\Models\StampCorrectionRequest;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StampCorrectionController extends Controller
{
    /**
     * ユーザー自身の修正申請一覧
     * ?status=approved|pending（既定: pending=承認待ち）
     */
    public function userList(Request $request)
    {
        $statusParam = $request->input('status', 'pending');
        $status = $statusParam === 'approved' ? '承認済み' : '承認待ち';

        $requests = StampCorrectionRequest::with(['user', 'attendance'])
            ->where('user_id', auth()->id())
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->paginate(20)                   // ← 大量データでも安定
            ->appends(['status' => $statusParam]);

        return view('stamp_requests.index', compact('requests', 'statusParam'));
    }

    /**
     * 修正申請の新規作成
     */
    public function store(StampCorrectionForm $request)
    {
        // 1) 勤怠の存在 & 所有者チェック
        $attendance = Attendance::where('id', $request->attendance_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$attendance) {
            return back()->withErrors(['attendance_id' => '対象の勤怠が見つからないか、権限がありません。'])->withInput();
        }

        // 2) 同一勤怠の承認待ちが既にあるか（重複申請の抑止）
        $existsPending = StampCorrectionRequest::where('user_id', auth()->id())
            ->where('attendance_id', $attendance->id)
            ->where('status', '承認待ち')
            ->exists();

        if ($existsPending) {
            return back()->withErrors(['general' => 'この勤怠には承認待ちの申請が既に存在します。結果をお待ちください。'])->withInput();
        }

        // 3) 申請登録（必要に応じてトランザクション）
        DB::transaction(function () use ($request) {
            StampCorrectionRequest::create([
                'user_id'               => auth()->id(),
                'attendance_id'         => $request->attendance_id,
                'requested_start_time'  => $request->requested_start_time,
                'requested_end_time'    => $request->requested_end_time,
                'requested_note'        => $request->requested_note,
                'status'                => '承認待ち',
            ]);
        });

        return redirect()
            ->route('attendance.show', $request->attendance_id)
            ->with('success', '修正申請を送信しました。');
    }
}
