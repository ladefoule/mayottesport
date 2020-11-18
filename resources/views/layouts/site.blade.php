<!doctype html>
<html lang="fr">

<?php
    request()->sports = App\Sport::all();
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/datatables.min.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <title>@yield('title') | mayottesport.com</title>
</head>

<body>
    @include('layouts.include.navbar-sports')
    <div class="container-lg">
        @yield('content')
    </div>

    {{-- Footer --}}
    @include('layouts.include.footer')
</body>

</html>
