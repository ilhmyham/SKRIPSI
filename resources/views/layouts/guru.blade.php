<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Guru') - LMS Iqra</title>
    
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
    @include('layouts.guru-sidebar')
</body>
</html>
