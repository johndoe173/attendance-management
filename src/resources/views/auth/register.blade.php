@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register__content">
    <div class="register-form__heading">
    <h2>会員登録</h2>
    </div>
    <form class="form" action="/register" method="post">
        @csrf
    <div class="form__group">
        <input class="form__input" type="text" name="name" id="name" placeholder="名前" />
        <p class="form__error">
            @error('name')
            {{ $message }}
            @enderror
        </p>
    </div>
    <div class="form__group">
        <input class="form__input" type="mail" name="email" id="email" placeholder="メールアドレス" />
        <p class="form__error">
            @error('email')
            {{ $message }}
            @enderror
        </p>
    </div>
    <div class="form__group">
        <input class="form__input" type="password" name="password" id="password" placeholder="パスワード" />
        <p class="form__error">
            @error('password')
            {{ $message }}
            @enderror
        </p>
    </div>
    <div class="form__group">
        <input class="form__input" type="password" name="password_confirmation" placeholder="確認用パスワード" />
    </div>
    <div class="form__button">
        <button class="form__button-submit" type="submit">登録</button>
    </div>
    </form>
    <div class="login__link">
    <p class="register__button-message">アカウントをお持の方はこちら</p>
    <a class="login__button-submit" href="/login">ログイン</a>
    </div>
</div>
@endsection