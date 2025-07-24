<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StampCorrectionRequest;

class StampCorrectionController extends Controller
{
    public function adminList()
    {
        $requests = StampCorrectionRequest::with('user')
            ->where('status', '承認待ち') // 適宜調整
            ->get();

        return view('admin.stamp_requests.index', compact('requests'));
    }

    public function approveForm(StampCorrectionRequest $attendance_correct_request)
    {
        return view('admin.stamp_requests.approve', [
        'request' =>   $attendance_correct_request
        ]);
    }

    public function approve(Request $request, StampCorrectionRequest $attendance_correct_request)
    {
        $attendance_correct_request->update([
            'status' => '承認済み',
        ]);

        return redirect()->route('admin.stamp_request.index')->with('success', '承認しました');
    }
}
