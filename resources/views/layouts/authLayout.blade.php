<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Word On The Go</title>
    <link rel="icon" href="{{ asset('images/wotg-icon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/auth.css?v=1.9') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="{{ Request::is('profile/edit') || Request::is('signup') ? 'full-height' : 'full-viewport' }}">
    <div class="auth-container">
        @yield('content')
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
