<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') - Ayat Isyarat</title>
    <meta name="description" content="@yield('meta-description', 'AyatIsyarat — Panel admin untuk pengelolaan sistem pembelajaran Iqra.')">
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="pattern-bg">
    <!-- Flash Messages -->
    @if(session('success'))
        <x-alert type="success" class="fixed top-4 right-4 z-50">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error" class="fixed top-4 right-4 z-50">
            {{ session('error') }}
        </x-alert>
    @endif

    <!-- Include Sidebar Layout -->
    @include('layouts.admin-sidebar')
</body>
</html>
