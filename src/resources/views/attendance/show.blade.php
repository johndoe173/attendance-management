@extends('layouts.app')

@section('title', '勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/show.css') }}">
@endsection

@section('content')
<div class="detail-container">
    <h2 class="detail-title">勤怠詳細</h2>

    @php
        $hasPendingRequest = $attendance->correctionRequests()
            ->where('status', '承認待ち')
            ->exists();
    @endphp

    @if ($hasPendingRequest)
        <p class="error-message" style="color: red;">※承認待ちのため修正はできません。</p>
    @endif

    <form action="{{ route('stamp_requests.store') }}" method="POST" class="detail-form">
        @csrf

        {{-- 勤怠ID（申請対象） --}}
        <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">

        <table class="detail-table">
            <tr>
                <th>名前</th>
                <td>{{ $attendance->user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>
                    {{ optional($attendance->work_date)->format('Y年n月j日') ?? '不明' }}
                </td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    <input type="time" name="requested_start_time" value="{{ optional($attendance->start_time)->format('H:i') }}" class="input-time" {{ $hasPendingRequest ? 'disabled' : '' }}>
                    〜
                    <input type="time" name="requested_end_time" value="{{ optional($attendance->end_time)->format('H:i') }}" class="input-time" {{ $hasPendingRequest ? 'disabled' : '' }}>
                </td>
            </tr>
            {{-- 休憩欄は申請対象外のため、現在は表示だけ or 削除可能 --}}
            <tr>
                <th>休憩</th>
                <td>
                    {{ $breaks[0]['start'] ?? '--:--' }} 〜 {{ $breaks[0]['end'] ?? '--:--' }}
                </td>
            </tr>
            <tr>
                <th>休憩2</th>
                <td>
                    {{ $breaks[1]['start'] ?? '--:--' }} 〜 {{ $breaks[1]['end'] ?? '--:--' }}
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td>
                    <textarea name="requested_note" class="input-note" required>{{ $attendance->note }}</textarea>

                </td>
            </tr>
        </table>

        <div class="submit-button-area">
            <button type="submit" class="btn-black" {{ $hasPendingRequest ? 'disabled' : '' }}>修正申請</button>
        </div>
    </form>
</div>
@endsection
