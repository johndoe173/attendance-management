<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\RestTime;
use Carbon\Carbon;
use Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $workTimes = WorkTime::with('user')
            ->where('work_date', $date)
            ->paginate(5);

        return view('attendance', compact('workTimes', 'date'));
    }

}
