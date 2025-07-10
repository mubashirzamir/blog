<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>
        @yield('title', __('Default Title'))
    </title>
</head>
<body>
    @yield('content')
</body>
</html>
