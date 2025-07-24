@extends('layouts.top')

@section('title', 'スタッフ一覧')

@section('content')
<div class="staff-container">
    <h2 class="staff-title">スタッフ一覧</h2>

    <table class="staff-table">
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staffList as $staff)
            <tr>
                <td>{{ $staff->name }}</td>
                <td>{{ $staff->email }}</td>
                <td>
                    <a href="{{ route('admin.staff.attendance', $staff->id) }}" class="detail-link">詳細</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
