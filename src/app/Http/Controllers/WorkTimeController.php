<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\RestTime;
use Illuminate\Support\Facades\Auth;

class WorkTimeController extends Controller
{
    public function index()
    {
        // 現在のユーザーの勤務状態を取得（勤務中かどうかを判定）
        $isWorking = WorkTime::where('user_id', Auth::id())
                              ->whereNull('end_time') // end_timeがnullなら勤務中
                              ->latest()
                              ->exists();
    
        // 現在のユーザーの休憩状態を取得（休憩中かどうかを判定）
        $isResting = RestTime::whereHas('workTime', function($query) {
                                $query->where('user_id', Auth::id())
                                      ->whereNull('end_time'); // 勤務中のWorkTimeのみ
                            })
                            ->whereNull('end_time') // end_timeがnullなら休憩中
                            ->exists();
    
        // Bladeにデータを渡して表示
        return view('stamp', compact('isWorking', 'isResting'));
    }

        // 勤務開始
        public function startWork()
        {
            $today = now()->format('Y-m-d');
            $start_time = now()->format('Y-m-d') . ' ' . now()->format('H:i:s');
            $workTime = WorkTime::create([
                'user_id' => Auth::id(),
                'work_date' => $today,
                'start_time' => $start_time,
            ]);

            return redirect('/')->with('message', '勤務を開始しました');
        }

    // 勤務終了
    public function endWork()
    {
        $workTime = WorkTime::where('user_id', Auth::id())
                            ->whereNull('end_time')
                            ->latest()
                            ->first();
    
        if ($workTime) {
            // 休憩中であれば休憩終了を処理
            $restTime = RestTime::where('work_time_id', $workTime->id)
                                ->whereNull('end_time')
                                ->latest()
                                ->first();
    
            if ($restTime) {
                $restTime->update(['end_time' => now()]);
            }
    
            // 勤務終了の処理
            $workTime->update(['end_time' => now()]);
        }
    
        return redirect('/')->with('message', '勤務を終了しました');
    }

}
