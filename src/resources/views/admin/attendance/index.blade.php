@extends('layouts.admin')

@section('title', '勤怠一覧（管理者）')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/attendance.css') }}">
@endsection

@section('content')
<div class="admin-attendance-wrapper">
    <div class="admin-attendance-container">
        <h2 class="admin-attendance-title">{{ $date->format('Y年n月j日') }}の勤怠</h2>

        <div class="date-selector">
            <div class="date-nav">
                <a href="{{ route('admin.attendance.index', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}">← 前日</a>
            </div>

            <form method="GET" action="{{ route('admin.attendance.index') }}">
                <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="calendar-input" onchange="this.form.submit()">
            </form>

            <div class="date-nav">
                <a href="{{ route('admin.attendance.index', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}">翌日 →</a>
            </div>
        </div>

        <table class="admin-attendance-table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <td>{{ $record->user->name }}</td>
                        <td>{{ $record->start_time ? \Carbon\Carbon::parse($record->start_time)->format('H:i') : '--:--' }}</td>
                        <td>{{ $record->end_time ? \Carbon\Carbon::parse($record->end_time)->format('H:i') : '--:--' }}</td>
                        <td>{{ $record->break_total ?? '0:00' }}</td>
                        <td>{{ $record->work_duration ?? '0:00' }}</td>
                        <td><a href="{{ route('admin.attendance.show', ['id' => $record->id]) }}" class="detail-link">詳細</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">データが存在しません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

