<!DOCTYPE html>
<<<<<<< HEAD
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
=======
<html lang="en">

<!-- Head Section -->
@include('includes.Head')

<!-- Include CSS Styles -->
@include('includes.Style')
@stack('Style')

<body>
    <div class="wrapper">
        <!-- Sidebar Navigation -->
        @include('includes.Sidebar')

        <!-- Main Panel Container -->
        <div class="main-panel" id="main-panel">
            <!-- Top Header Navbar -->
            @include('includes.Header')

            <!-- Dynamic Page Content -->
            @yield('Main_Content')

            <!-- Page Footer -->
            @include('includes.Footer')
        </div>
    </div>

    <!-- JavaScript Files and Scripts -->
    @include('includes.Script')
    @stack('Script')
</body>

>>>>>>> main
</html>
