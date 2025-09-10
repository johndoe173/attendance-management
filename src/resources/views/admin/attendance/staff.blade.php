@extends('layouts.admin')

@section('title', 'スタッフ月次勤怠')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/attendance.css') }}">
@endsection

@section('content')
<div class="admin-attendance-wrapper">
    <h2 class="admin-attendance-title">
        {{ $staff->name }} さんの勤怠一覧
    </h2>

    <table class="admin-attendance-table">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($attendance->work_date)->format('Y-m-d') }}</td>
                    <td>{{ $attendance->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') : '--:--' }}</td>
                    <td>{{ $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : '--:--' }}</td>
                    <td>{{ $attendance->break_total ?? '0:00' }}</td>
                    <td>
                        <a href="{{ route('admin.attendance.show', $attendance->id) }}" class="detail-link">詳細</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">データがありません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $attendances->links() }}
</div>
@endsection
