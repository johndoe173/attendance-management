<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ログイン')</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/common.css') }}">
    @yield('css')
</head>
<body>
    <header style="background-color: #000; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center;">
        <h1 style="color: white; font-size: 24px;">
            <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" style="height: 24px;">
        </h1>

        {{-- ログイン中ユーザーのみナビゲーション表示 --}}
        @auth
            @if (!Request::is('email/verify*'))
                <nav>
                    <ul style="display: flex; gap: 20px; list-style: none; margin: 0; padding: 0; align-items: center;">
                        <li><a href="{{ route('attendance.punch') }}" style="color: white; text-decoration: none; line-height: 1;">勤怠</a></li>
                        <li><a href="{{ route('attendance.index') }}" style="color: white; text-decoration: none; line-height: 1;">勤怠一覧</a></li>
                        <li><a href="{{ route('stamp_requests.index') }}" style="color: white; text-decoration: none; line-height: 1;">申請</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" style="
                                    color: white;
                                    background: none;
                                    border: none;
                                    padding: 0;
                                    font: inherit;
                                    cursor: pointer;
                                    text-decoration: none;
                                    line-height: 1;
                                ">ログアウト</button>
                            </form>
                        </li>
                    </ul>
                </nav>
            @endif
        @endauth
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>
