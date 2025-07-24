@extends('layouts.app')

@section('title', 'メール認証')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">
@endsection

@section('content')
<div class="verify-container">
    <p class="verify-text">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="verify-btn">
            認証はこちらから
        </button>
    </form>

    @if (session('status') == 'verification-link-sent')
        <p class="sent-message">認証リンクを再送信しました。</p>
    @endif

    <div class="resend-link">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="link-style">認証メールを再送する</button>
        </form>
    </div>
</div>
@endsection
