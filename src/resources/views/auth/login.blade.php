@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="login__content">
  <div class="login-form__heading">
    <h2>ログイン</h2>
  </div>
  <form class="form" action="/login" method="post">
    @csrf
    <div class="form__group">
        <input class="form__input" type="mail" name="email" id="email" placeholder="メールアドレス"/>
        <p class="form__error">
            @error('email')
            {{ $message }}
            @enderror
        </p>
    </div>
    <div class="form__group">
        <input class="form__input" type="password" name="password" id="password" placeholder="パスワード"/>
        <p class="form__error">
            @error('password')
            {{ $message }}
            @enderror
        </p>
    </div>
    <div class="form__button">
      <button class="form__button-submit" type="submit">ログイン</button>
    </div>
  </form>
  <div class="register__link">
    <p class="register__button-message">アカウントをお持ちでない方はこちら</p>
    <a class="register__button-submit" href="/register">会員登録</a>
  </div>
</div>
@endsection