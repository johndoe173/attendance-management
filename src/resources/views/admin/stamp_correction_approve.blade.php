@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">修正申請 承認画面</h2>

    <table class="table table-bordered">
        <tr>
            <th>名前</th>
            <td>{{ $request->user->name }}</td>
        </tr>
        <tr>
            <th>日付</th>
            <td>{{ $request->date->format('Y年n月j日') }}</td>
        </tr>
        <tr>
            <th>【修正前】出勤・退勤</th>
            <td>{{ $request->before_start }} ～ {{ $request->before_end }}</td>
        </tr>
        <tr>
            <th>【修正前】休憩</th>
            <td>{{ $request->before_break_start }} ～ {{ $request->before_break_end }}</td>
        </tr>
        <tr>
            <th>【修正後】出勤・退勤</th>
            <td>{{ $request->after_start }} ～ {{ $request->after_end }}</td>
        </tr>
        <tr>
            <th>【修正後】休憩</th>
            <td>{{ $request->after_break_start }} ～ {{ $request->after_break_end }}</td>
        </tr>
        <tr>
            <th>理由</th>
            <td>{{ $request->reason }}</td>
        </tr>
    </table>

    @if(!$request->is_approved)
    <form method="POST" action="{{ route('admin.stamp_correction_request.approve', $request->id) }}">
        @csrf
        <button type="submit" class="btn btn-primary">承認</button>
    </form>
    @else
    <div class="alert alert-success mt-3">
        この申請はすでに <strong>承認済み</strong> です。
    </div>
    @endif
</div>
@endsection
