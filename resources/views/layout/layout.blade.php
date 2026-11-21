<!DOCTYPE html>
<html lang="en">
<head>
    @vite('resources/css/app.css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Layout Blade')</title>
</head>
<body>
    <h1 className="text-3xl font-bold underline">Wilbert</h1>
    <nav>
        <a href ='/'>Home</a> | <a href="/user">Users</a>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        2026 Copyright
    </footer>
</body>
</html>
