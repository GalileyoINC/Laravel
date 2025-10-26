<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vapid-public-key" content="{{ config('services.push.vapid_public_key') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="Galileyo unites communities with real-time emergency alerts, social safety tools, and satellite-ready connectivity. Join and stay informed.">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="author" content="Zoran Shefot Bogoevski">
    <link rel="author" href="https://zorandev.info">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name', 'Galileyo') }} | Speak Freely — Unleash Your Voice">
    <meta property="og:description" content="Real-time emergency alerts and social tools to protect what matters most.">
    <meta property="og:image" content="{{ asset('galileyo_new_logo.png') }}">
    <meta property="og:site_name" content="{{ config('app.name', 'Galileyo') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('app.name', 'Galileyo') }} | Speak Freely — Unleash Your Voice">
    <meta name="twitter:description" content="Real-time emergency alerts and social tools to protect what matters most.">
    <meta name="twitter:image" content="{{ asset('galileyo_new_logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div id="app"></div>
</body>
</html>
