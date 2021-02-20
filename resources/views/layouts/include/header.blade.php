<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="language" content="fr">
	<meta http-equiv="Content-Language" content="fr">
	<meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
        // Only connect to this site via HTTPS for the two years (recommended)
        header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload'); 

        # Block site from being framed with X-Frame-Options and CSP
        header("Content-Security-Policy: frame-ancestors 'none'");
        header("X-Frame-Options: DENY");

        # Block pages from loading when they detect reflected XSS attacks
        header('X-XSS-Protection: 1; mode=block');

        # Prevent browsers from incorrectly detecting non-scripts as scripts
        header("X-Content-Type-Options: nosniff");

        # Compression avec gzip
        header("Accept-Encoding: *");
    ?> 

    @yield('head')
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/storage/img/icons/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
    <link rel="stylesheet" href="{{ asset('/css/style.css?id=1') }}">
    <title>@yield('title')</title>
</head>
