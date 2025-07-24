@extends('layouts.admin')

@section('title', 'スタッフ一覧')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/staff.css') }}">
@endsection

@section('content')
<div class="staff-list-container">
    <h2 class="staff-list-title">スタッフ一覧</h2>

    <table class="staff-list-table">
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staffs as $staff)
                <tr>
                    <td>{{ $staff->name }}</td>
                    <td>{{ $staff->email }}</td>
                    <td><a href="{{ route('admin.attendance.staff', ['id' => $staff->id]) }}" class="detail-link">詳細</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
