<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <h1 class="header__heading">Atte</h1>
            @yield('link')
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

<footer class="footer">
    <small class="copyright">Atte, inc</small>
</footer>

</html>