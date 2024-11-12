<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\RestTime;
use Carbon\Carbon;
use Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(5); // 全ユーザーを取得
        return view('user.index', compact('users'));
    }

    public function show($id,Request $request)
    {
        $user = User::findOrFail($id); // 指定したIDのユーザーを取得

        // リクエストから日付を取得し、指定がない場合は当日の日付を使用
        $date = $request->input('date', Carbon::today()->toDateString());
    
        // 指定した日付の勤務時間データを取得
        $workTimes = WorkTime::where('user_id', $id) // user_idで絞り込み
            ->whereDate('work_date', $date) // 日付で絞り込み
            ->paginate(5);

        return view('user.show', compact('user', 'workTimes', 'date'));
    }
}
