@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/date.css') }}">
@endsection

@section('link')
    <!-- ヘッダー -->
    <header class="header-link">
        <nav class="header-nav">
            <a href="{{ url('/') }}" class="nav-link">ホーム</a>
            <a href="{{ url('/userpage') }}" class="nav-link">ユーザー一覧</a>
            <a href="{{ url('/attendance') }}" class="nav-link">日付一覧</a>
            <a href="{{ url('/logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </header>
@endsection

@section('content')
<h2 class="date-nav">
    <a href="{{ url('/attendance?date=' . \Carbon\Carbon::parse($date)->subDay()->toDateString()) }}">＜</a>
    {{ $date }}
    <a href="{{ url('/attendance?date=' . \Carbon\Carbon::parse($date)->addDay()->toDateString()) }}">＞</a>
</h2>

    {{-- 本日の勤怠記録 --}}
    @if ($workTimes->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>勤務開始</th>
                    <th>勤務終了</th>
                    <th>休憩時間</th>
                    <th>勤務時間</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($workTimes as $workTime)
                    <tr>
                        <td>{{ Auth::user()->name }}</td>
                        <td>{{ $workTime->start_time }}</td>
                        <td>{{ $workTime->end_time ?? '勤務中' }}</td>


                        {{-- 休憩時間の計算（時・分・秒に変換） --}}
                        <td>
                            @php
                                $totalRestTime = 0;
                                foreach ($workTime->restTimes as $restTime) {
                                    if ($restTime->end_time) {
                                        $totalRestTime += \Carbon\Carbon::parse($restTime->end_time)->diffInSeconds($restTime->start_time);
                                    }
                                }
                                $hours = floor($totalRestTime / 3600);
                                $minutes = floor(($totalRestTime % 3600) / 60);
                                $seconds = $totalRestTime % 60;
                                echo sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                            @endphp
                        </td>

                        {{-- 勤務時間の計算（休憩時間を引いて時・分・秒で表示） --}}
                        <td>
                            @if ($workTime->end_time)
                                @php
                                    $workDuration = \Carbon\Carbon::parse($workTime->end_time)->diffInSeconds($workTime->start_time);
                                    $actualWorkTime = $workDuration - $totalRestTime;
                                    $workHours = floor($actualWorkTime / 3600);
                                    $workMinutes = floor(($actualWorkTime % 3600) / 60);
                                    $workSeconds = $actualWorkTime % 60;
                                    echo sprintf('%02d:%02d:%02d', $workHours, $workMinutes, $workSeconds);
                                @endphp
                            @else
                                勤務中
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $workTimes->appends(['date' => $date])->links('vendor.pagination.custom-pagination') }}
        </div>
    @else
        <p>記録がありません。</p>
    @endif
</div>
@endsection