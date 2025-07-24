<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', '勤怠管理（管理者）')</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/base.css') }}">
    @yield('css')
</head>
<body>
    <header style="background-color: #000; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center;">
        <h1 style="color: white; font-size: 24px;">
            <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" style="height: 24px;">
        </h1>

        @auth('admin')
            <nav>
                <ul style="display: flex; gap: 20px; list-style: none; margin: 0; padding: 0; align-items: center;">
                    <li><a href="{{ route('admin.attendance.index') }}" style="color: white; text-decoration: none; line-height: 1;">勤怠一覧</a></li>
                    <li><a href="{{ route('admin.staff.index') }}" style="color: white; text-decoration: none; line-height: 1;">スタッフ一覧</a></li>
                    <li><a href="{{ route('admin.stamp_request.index') }}" style="color: white; text-decoration: none; line-height: 1;">申請一覧</a></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
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
        @endauth
    </header>

    <main class="admin-main">
        @yield('content')
    </main>
</body>
</html>
