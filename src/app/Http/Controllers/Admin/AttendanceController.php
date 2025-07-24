<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;


class AttendanceController extends Controller
{
    public function list(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : now()->startOfDay();

        $records = Attendance::with('user') // userリレーションを読み込む
        ->whereDate('work_date', $date->toDateString())
        ->get();

        return view('admin.attendance.index', compact('date', 'records'));
    }

    public function detail($id) 
    { 
        $attendance = Attendance::with('user')->findOrFail($id);
        return view('admin.attendance.show', compact('attendance')); 
    }
}

