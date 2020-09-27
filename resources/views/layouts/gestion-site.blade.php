<!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/datatables.min.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <title>@yield('title') | mayottesport.com</title>
</head>
<?php
    $routeJournees = route('champ-journees.multi.choix-saison');
    $routeFoot = route('champ-matches.foot.lister');
    $path = request()->path();
?>
<body style="background-image: url('/storage/img/fond-mayotte.jpg');background-size:1950px 1000px;background-attachment: fixed;">
    <div class="d-flex justify-content-center p-3 bg-white" style="z-index: 2">
        <img src="/storage/img/logo-mayottesport-com.jpg" alt="Logo MayotteSport.com">
    </div>
    @include('layouts.navbar-admin')
    <div class="container-lg-fluid bg-dark">
        <div class="container-lg">
            <div class="row overflow-x-auto py-2">
                <div class="d-flex justify-content-center" style="margin:0 auto">
                    <a href="{{ route('champ-journees.multi.choix-saison') }}"><button class="btn mx-2 @if(Str::endsWith($routeJournees, $path)) btn-light @else btn-outline-light @endif">Journées (multi)</button></a>
                    <a href="{{ route('champ-matches.foot.lister') }}"><button class="btn mx-2 @if(Str::endsWith($routeFoot, $path)) btn-light @else btn-outline-light @endif">Matches de foot</button></a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-lg p-3">
        @yield('content')
    </div>

    <!-- Fin de page-->
    <script src="https://kit.fontawesome.com/fa79ab8443.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" crossorigin="anonymous"></script> --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="/js/datatables.min.js"></script>
    <script src="/js/select2.min.js"></script>
    <script src="/js/outils.js"></script>
    @yield('script')
</body>

</html>
