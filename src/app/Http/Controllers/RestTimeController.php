<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RestTime;
use App\Models\WorkTime;
use Illuminate\Support\Facades\Auth;

class RestTimeController extends Controller
{
    public function startRest(WorkTime $workTime)
    {
        $workTime = WorkTime::where('user_id', auth()->id())
                            ->whereNull('end_time') // 終了していない勤務のみ
                            ->latest()
                            ->first();

        if (!$workTime) {
            return redirect('/')->with('error', '勤務時間が見つかりません');
        }

        $restTime = $workTime->restTimes()->create([
            'start_time' => now(),
        ]);

        return redirect('/')->with('message', '休憩を開始しました');
    }

    public function endRest()
    {
        $workTime = WorkTime::where('user_id', Auth::id())
                            ->whereNull('end_time') // 勤務が終了していない
                            ->latest()
                            ->first();
    
        if (!$workTime) {
            return redirect('/')->with('error', '勤務が開始されていません');
        }
    
        $restTime = RestTime::where('work_time_id', $workTime->id)
                            ->whereNull('end_time') // 終了していない休憩のみ
                            ->latest()
                            ->first();
    
        if ($restTime) {
            $restTime->update([
                'end_time' => now(),
            ]);
    
            return redirect('/')->with('message', '休憩を終了しました');
        }
    
        return redirect('/')->with('error', '進行中の休憩が見つかりません');
    }
}
