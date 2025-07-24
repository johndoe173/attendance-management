@extends('layouts.admin')

@section('title', '勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/show.css') }}">
@endsection

@section('content')
<div class="attendance-detail-container">
    <h2 class="page-title">勤怠詳細</h2>

    <form class="attendance-detail-form">
        <table>
            <tr>
                <th>名前</th>
                <td>{{ $attendance->user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>
                    {{ \Carbon\Carbon::parse($attendance->work_date)->format('Y年') }}
                    &emsp;
                    {{ \Carbon\Carbon::parse($attendance->work_date)->format('n月j日') }}
                </td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    <input type="time" value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') ?? '' }}"> ～
                    <input type="time" value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') ?? '' }}">
                </td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>
                    <input type="time" value="{{ $attendance->break_start1 }}"> ～
                    <input type="time" value="{{ $attendance->break_end1 }}">
                </td>
            </tr>
            <tr>
                <th>休憩2</th>
                <td>
                    <input type="time" value="{{ $attendance->break_start2 }}"> ～
                    <input type="time" value="{{ $attendance->break_end2 }}">
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td>
                    <textarea rows="3">{{ $attendance->note }}</textarea>
                </td>
            </tr>
        </table>

        <div class="submit-btn-wrapper">
            <button type="submit" class="submit-btn">修正</button>
        </div>
    </form>
</div>
@endsection
