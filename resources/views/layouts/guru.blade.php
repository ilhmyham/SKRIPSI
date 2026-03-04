<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Guru') - Ayat Isyarat</title>
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
  
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) }}">
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>
<body class="pattern-bg">

    @if(session('success'))
        <x-alert type="success" class="fixed top-2 right-4 md:right-4" style="z-index: 9999;">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error" class="fixed top-2 right-4 md:right-4" style="z-index: 9999;">
            {{ session('error') }}
        </x-alert>
    @endif

    @include('layouts.guru-sidebar')        

    @stack('scripts')
</body>
</html>
