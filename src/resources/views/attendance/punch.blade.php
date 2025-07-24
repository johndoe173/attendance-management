@extends('layouts.app')

@section('title', '打刻処理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/punch.css') }}">
@endsection

@section('content')
<div class="punch-container">
    <div class="status-label">
        <span class="status-tag">{{ $statusLabel }}</span>
    </div>
    <div class="punch-date">
        {{ \Carbon\Carbon::now()->format('Y年n月j日（D）') }}
    </div>
    <div class="punch-time">
        {{ \Carbon\Carbon::now()->format('H:i') }}
    </div>

    <div class="punch-buttons">
        @if ($status === 'before')
            <form method="POST" action="{{ route('attendance.start') }}">
                @csrf
                <button type="submit" class="btn btn-black">出勤</button>
            </form>
        @elseif ($status === 'working')
            <form method="POST" action="{{ route('attendance.end') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-black">退勤</button>
            </form>
            <form method="POST" action="{{ route('attendance.break_start') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-white">休憩入</button>
            </form>
        @elseif ($status === 'break')
            <form method="POST" action="{{ route('attendance.break_end') }}">
                @csrf
                <button type="submit" class="btn btn-white">休憩戻</button>
            </form>
        @elseif ($status === 'after')
            <div class="thanks">お疲れ様でした。</div>
        @endif
    </div>
</div>
@endsection
