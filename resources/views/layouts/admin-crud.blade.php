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
    // $path = request()->path();
    // $routeTables = route('crud-gestion.tables');
    // $routeAttributs = route('crud-gestion.attributs');
    // $routeParametres = route('crud-gestion.parametres');
?>
<body>
    @include('layouts.navbar-admin')
    {{-- <div class="container-fluid">
        <div class="row overflow-x-auto p-3">
            <div class="d-flex justify-content-center" style="margin:0 auto">
                <a href="{{ $routeTables }}">
                    <button class="btn mx-2 @if(Str::endsWith($routeTables, $path)) btn-dark @else btn-outline-dark @endif">Les tables</button>
                </a>
                <a href="{{ $routeAttributs }}">
                    <button class="btn mx-2 @if(Str::endsWith($routeAttributs, $path)) btn-dark @else btn-outline-dark @endif">Les attributs</button>
                </a>
                <a href="{{ $routeParametres }}">
                    <button class="btn mx-2 @if(Str::endsWith($routeParametres, $path)) btn-dark @else btn-outline-dark @endif">Les paramètres</button>
                </a>
            </div>
        </div>
    </div> --}}

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
