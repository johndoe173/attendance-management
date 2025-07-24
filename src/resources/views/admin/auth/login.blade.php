@extends('layouts.admin')

@section('title', '管理者ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">
@endsection

@section('content')
<div class="admin-login-container">
    <div class="admin-login-box">
        <h2 class="admin-login-title">管理者ログイン</h2>

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="admin-form-group">
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}">
                @error('email') <div class="error" style="color: red;">{{ $message }}</div> @enderror
            </div>

            <div class="admin-form-group">
                <label for="password">パスワード</label>
                <input id="password" type="password" name="password">
                @error('password') <div class="error" style="color: red;">{{ $message }}</div> @enderror
            </div>

            <div class="admin-form-group">
                <button type="submit" class="admin-login-btn">
                    管理者ログインする
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
