<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet" />
    <style>
        body { font-family: 'Nunito', sans-serif; margin: 0; }
        .flex-center { align-items: center; display: flex; justify-content: center; height: 100vh; }
        .content { text-align: center; }
        .title { font-size: 84px; }
        .links > a { color: #636b6f; padding: 0 25px; font-size: 13px; font-weight: 600; letter-spacing: .1rem; text-decoration: none; text-transform: uppercase; }
        .m-b-md { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="flex-center">
        <div class="content">
            <div class="title m-b-md">
                Laravel
            </div>
            @if (Route::has('login'))
                <div class="links">
                    @auth
                        <a href="{{ route('dashboard') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
            @endif
                </div>
            <div class="links">
                <a href="https://laravel.com/docs">Docs</a>
                <a href="https://laracasts.com">Laracasts</a>
                <a href="https://laravel-news.com">News</a>
                <a href="https://blog.laravel.com">Blog</a>
                <a href="https://nova.laravel.com">Nova</a>
                <a href="https://forge.laravel.com">Forge</a>
                <a href="https://vapor.laravel.com">Vapor</a>
                <a href="https://github.com/laravel/laravel">GitHub</a>
            </div>
        </div>
    </div>
</body>
</html>