<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&family=Work+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles/navigation.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/modals.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/forms.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/table.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/app.min.css') }}">

    <!-- CDN -->
    @include('links.cdn')
</head>

<body class="font-sans text-gray-900 antialiased h-100 m-0">
    @auth
        @include('layouts.navigation')
        <main class="h-100 m-0">
            @yield('content')
        </main>
    @else
        <main class="h-100">
            @yield('login')
        </main>
    @endauth

    <script src="{{ asset('js/login.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
</body>

</html>
