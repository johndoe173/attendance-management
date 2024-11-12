@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user_list.css') }}">
@endsection


@section('link')
    <!-- ヘッダー -->
    <header class="header-link">
        <nav class="header-nav">
            <a href="{{ url('/') }}" class="nav-link">ホーム</a>
            <a href="{{ url('/userpage') }}" class="nav-link">ユーザー一覧</a>
            <a href="{{ url('/attendance') }}" class="nav-link">日付一覧</a>
            <a href="{{ url('/logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </header>
@endsection

@section('content')
<h1 class="user-list-title">ユーザー一覧</h1>
<ul class="user-list">
    @foreach($users as $user)
    <li class="user-list-item">
        <a href="{{ route('user.show', $user->id) }}" class="user-list-link">{{ $user->name }}</a>
    </li>
    @endforeach
</ul>

<div class="pagination-container">
    {{ $users->links('vendor.pagination.custom-pagination') }}
</div>
@endsection