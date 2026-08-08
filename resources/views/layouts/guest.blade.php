<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CoreTask') }}</title>

    <!-- Fonts and Styles -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<!--
      BODY BACKGROUND:
      Using a professional gradient background suited for a software company management system,
      blending modern dark tones with a subtle professional depth.
    -->

<body
    class="font-sans text-gray-900 antialiased bg-gradient-to-br from-slate-900 via-zinc-900 to-stone-900 min-h-screen flex items-center justify-center m-0">

    <!-- Main Wrapper Container -->
    <div class="w-full flex flex-col items-center justify-center p-4">
        {{ $slot }}
    </div>

</body>

</html>
