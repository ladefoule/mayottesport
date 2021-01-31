<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload'); ?> {{-- Only connect to this site via HTTPS for the two years (recommended) --}}
    @yield('head')
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/storage/img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
    <link rel="stylesheet" href="{{ asset('/css/style.css?t=' . now()) }}">{{-- A ENLEVER EN PRODUCTION --}}
    <title>@yield('title') | mayottesport.com</title>
</head>
