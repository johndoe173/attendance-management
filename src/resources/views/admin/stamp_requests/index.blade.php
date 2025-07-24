@extends('layouts.admin')

@section('title', '申請一覧')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/stamp_requests_list.css') }}">
@endsection

@section('content')
<div class="request-list-container">
    <h2 class="request-list-title">申請一覧</h2>

    <div class="tab-menu">
        <a href="#" class="tab active">承認待ち</a>
        <a href="#" class="tab">承認済み</a>
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
            @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->status }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->target_date)->format('Y/m/d') }}</td>
                    <td>{{ $request->reason }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->created_at)->format('Y/m/d') }}</td>
                    <td><a href="{{ route('admin.stamp_request.approve.form', $request->id) }}" class="detail-link">詳細</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

