@extends('layouts.admin')

@section('title', '勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/approve.css') }}">
@endsection

@section('content')
<div class="approval-container">
    <h2 class="approval-title">勤怠詳細</h2>

    <table class="approval-table">
        <tr>
            <th>名前</th>
            <td>{{ $request->user->name }}</td>
        </tr>
        <tr>
            <th>日付</th>
            <td>{{ \Carbon\Carbon::parse($request->date)->format('Y年n月j日') }}</td>
        </tr>
        <tr>
            <th>出勤・退勤</th>
            <td>{{ $request->start_time }} ～ {{ $request->end_time }}</td>
        </tr>
        <tr>
            <th>休憩</th>
            <td>{{ $request->break1_start }} ～ {{ $request->break1_end }}</td>
        </tr>
        <tr>
            <th>休憩2</th>
            <td>{{ $request->break2_start ?? '' }} ～ {{ $request->break2_end ?? '' }}</td>
        </tr>
        <tr>
            <th>備考</th>
            <td>{{ $request->note }}</td>
        </tr>
    </table>

    @if(!$request->is_approved)
        <form method="POST" action="{{ route('admin.stamp_request.approve', $request->id) }}">
            @csrf
            <button type="submit" class="approve-button">承認</button>
        </form>
    @else
        <button class="approved-button" disabled>承認済み</button>
    @endif
</div>
@endsection

