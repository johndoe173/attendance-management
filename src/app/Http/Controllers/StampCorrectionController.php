<?php

namespace App\Http\Controllers;

use App\Http\Requests\StampCorrectionRequest as StampCorrectionForm;
use App\Models\StampCorrectionRequest;
use Illuminate\Http\Request;

class StampCorrectionController extends Controller
{
    public function userList(Request $request)
    {
        $status = $request->input('status', 'pending'); // デフォルトは承認待ち

        $query = StampCorrectionRequest::with('user')
            ->where('user_id', auth()->id());

        if ($status === 'approved') {
            $query->where('status', '承認済み');
        } else {
            $query->where('status', '承認待ち');
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        return view('stamp_requests.index', compact('requests'));
    }

    public function store(StampCorrectionForm $request)
    {
        StampCorrectionRequest::create([
            'user_id' => auth()->id(),
            'attendance_id' => $request->attendance_id,
            'requested_start_time' => $request->requested_start_time,
            'requested_end_time' => $request->requested_end_time,
            'requested_note' => $request->requested_note,
            'status' => '承認待ち',
        ]);

        return redirect()->route('attendance.show', $request->attendance_id)
                         ->with('success', '修正申請を送信しました。');
    }
}
