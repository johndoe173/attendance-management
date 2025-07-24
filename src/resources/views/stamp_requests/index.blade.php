@extends('layouts.app')

@section('title', '申請一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stamp_requests/index.css') }}">
@endsection

@section('content')
<div class="request-list-container">
    <h2 class="page-title">申請一覧</h2>

    <div class="tabs">
        <a href="{{ route('stamp_requests.index', ['status' => 'pending']) }}" 
           class="{{ request('status', 'pending') === 'pending' ? 'active' : '' }}">承認待ち</a>
        <a href="{{ route('stamp_requests.index', ['status' => 'approved']) }}" 
           class="{{ request('status') === 'approved' ? 'active' : '' }}">承認済み</a>
    </div>

    <table class="request-table">
        <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $request)
                <tr>
                    <td>{{ $request->status }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->target_date)->format('Y/m/d') }}</td>
                    <td>{{ $request->reason }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->created_at)->format('Y/m/d') }}</td>
                    <td><a href="{{ route('attendance.show', $request->attendance_id) }}">詳細</a></td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty">申請はありません</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
