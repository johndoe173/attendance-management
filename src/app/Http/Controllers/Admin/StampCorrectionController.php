<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StampCorrectionRequest;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StampCorrectionController extends Controller
{
    /**
     * 修正申請一覧（管理者）
     * ?status=承認待ち|承認済み|却下（省略時は承認待ち）
     */
    public function adminList(Request $request)
    {
        $status = $request->input('status', '承認待ち');

        $requests = StampCorrectionRequest::with(['user', 'attendance'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('id')
            ->paginate(20)
            ->appends(['status' => $status]);

        return view('admin.stamp_requests.index', compact('requests', 'status'));
    }

    /**
     * 承認フォーム（管理者）
     * 申請内容（出退勤の修正値、備考）と現在の勤怠を表示
     */
    public function approveForm(StampCorrectionRequest $attendance_correct_request)
    {
        // eager load を徹底（表示漏れ対策）
        $attendance_correct_request->load(['user', 'attendance']);

        return view('admin.stamp_requests.approve', [
            'request' => $attendance_correct_request,
        ]);
    }

    /**
     * 承認処理（管理者）
     * 申請の requested_* を attendances に反映し、申請側を承認済みに更新
     */
    public function approve(Request $request, StampCorrectionRequest $attendance_correct_request)
    {
        // 備考（申請理由）は必須（空だった問題への対処）
        $request->validate([
            'approve_note' => ['nullable', 'string', 'max:255'], // 管理メモ（任意）
        ]);

        // 申請データを確実に取得
        $attendance_correct_request->load('attendance');

        // 反映対象の勤怠
        $attendance = $attendance_correct_request->attendance;
        if (!$attendance) {
            return back()->withErrors(['general' => '対象の勤怠データが見つかりません。']);
        }

        DB::beginTransaction();
        try {
            // 勤怠の出退勤を申請値で更新（片方だけの申請にも対応）
            $update = [];
            if (!empty($attendance_correct_request->requested_start_time)) {
                $update['start_time'] = $attendance_correct_request->requested_start_time;
            }
            if (!empty($attendance_correct_request->requested_end_time)) {
                $update['end_time'] = $attendance_correct_request->requested_end_time;
            }
            if ($update) {
                $attendance->update($update);
            }

            // 申請の状態を承認済みに
            $attendance_correct_request->update([
                'status'       => '承認済み',
                'approved_by'  => Auth::id(),     // 承認者（adminsテーブルなら別途取得）
                'approved_at'  => now(),
                // 'approved_note' => $request->input('approve_note'), // カラムがあれば保存
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors(['general' => '承認処理でエラーが発生しました。'])->withInput();
        }

        return redirect()
            ->route('admin.stamp_request.index');
    }
}
