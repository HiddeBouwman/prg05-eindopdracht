<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Georgia:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #f5f5dc; /* Beige background */
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text fill="%23d2b48c" font-size="20" y="50%">📖</text></svg>'); /* Subtle book icon texture */
            background-size: 50px 50px;
        }
        .cookbook-card {
            background-color: #fff8dc; /* Cream card background */
            border: 2px solid #deb887; /* Tan border */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .cookbook-header {
            color: #8b4513; /* Saddle brown */
        }
        .cookbook-text {
            color: #654321; /* Dark brown */
        }
        .user-rated {
            position: relative;
            z-index: 10;
        }
        /** Dit hier beneden is nodig om je eigen recensies te laten zien. ik ga dit niet uitleggen. */
        .user-rated::before {
            content: '\f005';
            font-family: 'Font Awesome 6 Free';
            font-weight: 600;
            color: #00429a;
            font-size: 1.2em;
            position: absolute;
            top: -0.1em;
            left: -0.0958em; /* vreemd getal ik weet het */
            z-index: -1;
        }
        @keyframes heart-bounce {
            0% { transform: scale(1); }
            25% { transform: scale(1.2); }
            50% { transform: scale(0.9); }
            75% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .heart-bounce {
            animation: heart-bounce 0.5s ease-in-out;
        }
    </style>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>
</div>
</body>
</html>
