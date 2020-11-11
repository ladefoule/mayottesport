<!doctype html>
<html lang="fr">

<?php
$sports = App\Sport::all();
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
    <nav class="navbar sticky-top navbar-light navbar-expand-lg border-bottom bg-white p-0">
        <div class="container">
            <a class="navbar-brand ml-3" href="/"><img class="img-fluid" src="/storage/img/logo-mayottesport-com.jpg" alt="Logo MayotteSport" style="height: 40px"></a>
            <button class="navbar-toggler mr-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse pr-2" id="navbarSupportedContent">
                <div class="navbar-nav mr-auto bg-white">
                    @foreach ($sports as $sport)
                        <a class="nav-item nav-link px-2" href="/{{ strToUrl($sport->nom) }}">{{ $sport->nom }}</a>
                    @endforeach
                    <a class="nav-item nav-link px-2" href="/autres">Autres</a>
                    <a class="nav-item nav-link px-2" href="/contact">Contact</a>
                </div>
                @include('layouts.connexion')
            </div>
        </div>
     </nav>
    <div class="container-lg">
        @yield('content')
    </div>
    <!-- Fin de page-->

    <!-- Fin de page-->
    <script src="https://kit.fontawesome.com/fa79ab8443.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="/js/datatables.min.js"></script>
    <script src="/js/select2.min.js"></script>
    <script src="/js/outils.js"></script>
    @yield('script')
</body>

</html>
