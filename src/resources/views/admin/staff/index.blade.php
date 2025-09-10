@extends('layouts.admin')

@section('title', 'スタッフ一覧')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/staff.css') }}">
@endsection

@section('content')
<div class="staff-container">
    <h2 class="staff-title">スタッフ一覧</h2>

    <form method="GET" action="{{ route('admin.staff.index') }}">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="名前やメールで検索">
        <button type="submit">検索</button>
    </form>

    <table class="staff-table">
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($staffs as $staff)
            <tr>
                <td>{{ $staff->name }}</td>
                <td>{{ $staff->email }}</td>
                <td>
                    <a href="{{ route('admin.attendance.staff', $staff->id) }}" class="detail-link">詳細</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">スタッフが見つかりません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $staffs->links() }} <!-- ページネーション -->
</div>
@endsection
