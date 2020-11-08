<!doctype html>
<html lang="fr">

<?php
$sportId = App\Sport::where('nom', 'like', 'football')->first()->id ?? 0;
$championnats = App\Competition::whereSportId($sportId)->get();
?>

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

<body>
    <!-- Debut de page-->
    <div class="d-flex justify-content-center bg-white p-2">
        <a href="/" class="col-10 col-sm-12 d-flex justify-content-center"><img class="img-fluid mx-auto" src="/storage/img/logo-mayottesport-com.jpg" alt="Logo MayotteSport"></a>
    </div>
    <nav class="navbar sticky-top navbar-dark navbar-expand-lg bg-dark" style="background-color: #000 !important">
        <div class="container">
            <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="navbar-nav mr-auto">
                    <a class="nav-item nav-link px-3" href="/">Accueil</a>
                    <a class="nav-item nav-link active px-3" href="/football">Football</a>
                    <a class="nav-item nav-link px-3" href="/handball">Handball</a>
                    <a class="nav-item nav-link px-3" href="/basketball">Basketball</a>
                    <a class="nav-item nav-link px-3" href="/volleyball">Volleyball</a>
                    <a class="nav-item nav-link px-3" href="/autres">Autres</a>
                    <a class="nav-item nav-link px-3" href="/contact">Contact</a>
                </div>
                @include('layouts.connexion')
            </div>
        </div>
    </nav>
    <div class="container-lg-fluid" style="background-color: rgba(141, 174, 182, 0.7) !important">
        <div class="container-lg">
            <div class="row overflow-x-auto py-2" id="navbar-scroll-x">
                <div class="d-flex justify-content-center" style="margin:0 auto">
                    @foreach ($championnats as $championnat)
                        <a href="{{ $championnat->url }}">
                            <button class="btn btn-sm mx-2 px-3 btn-light">
                                {{ $championnat->nom }}
                            </button>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
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
