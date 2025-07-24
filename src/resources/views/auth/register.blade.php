@extends('layouts.app')

@section('title', '会員登録')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-container">
    <h2 class="register-title">会員登録</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">名前</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}">
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}">
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password">
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">パスワード確認</label>
            <input id="password_confirmation" type="password" name="password_confirmation">
            @error('password_confirmation') <div class="error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="submit-btn">登録する</button>
    </form>

    <p class="link-to-login">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </p>
</div>
@endsection
