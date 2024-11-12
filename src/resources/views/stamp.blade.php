@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stamp.css') }}">
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
<div class="container stamp-page">
    <!-- ログインユーザーの名前表示 -->
    <div class="user-greeting text-center">
        <h2 class="greeting-message">{{ auth()->user()->name }}さんお疲れ様です！</h2>
    </div>

    <!-- 勤務ボタンと休憩ボタンの表示 -->
    <div class="row stamp-buttons">
        <div class="col-6">
            <form action="{{ route('work.start') }}" method="POST">
                @csrf
                <button class="btn btn-lg btn-block btn-work-start" @if($isWorking) disabled @endif type="submit">
                    勤務開始
                </button>
            </form>
        </div>

        <div class="col-6">
            <form action="{{ route('work.end') }}" method="POST">
                @csrf
                <button class="btn btn-lg btn-block btn-work-end" @if(!$isWorking || $isResting) disabled @endif type="submit">
                    勤務終了
                </button>
            </form>
        </div>

        <div class="col-6">
            <form action="{{ route('rest.start') }}" method="POST">
                @csrf
                <button class="btn btn-lg btn-block btn-rest-start" @if(!$isWorking || $isResting) disabled @endif type="submit">
                    休憩開始
                </button>
            </form>
        </div>

        <div class="col-6">
            <form action="{{ route('rest.end') }}" method="POST">
                @csrf
                <button class="btn btn-lg btn-block btn-rest-end" @if(!$isWorking || !$isResting) disabled @endif type="submit">
                    休憩終了
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
