<!doctype html>
<html lang="fr">

<?php
use App\Sport;
use App\Competition;

$sportActuel = Sport::where('nom', 'like', $sport)->firstOrFail();
$sportId = $sportActuel->id;
$sportNom = strToUrl($sportActuel->nom);
// $competitions = Competition::whereSportId($sportId)->get();
$sports = Sport::all();

$competitionKebab = strToUrl($competition);
$hrefIndex = route('competition.index', ['sport' => $sport, 'competition' => $competitionKebab]);
$hrefClassement = route('competition.classement', ['sport' => $sport, 'competition' => $competitionKebab]);
$hrefResultats = route('competition.resultats', ['sport' => $sport, 'competition' => $competitionKebab]);
$hrefASuivre = route('competition.asuivre', ['sport' => $sport, 'competition' => $competitionKebab]);
$hrefPalmares = route('competition.palmares', ['sport' => $sport, 'competition' => $competitionKebab]);
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
    {{-- <div class="d-flex justify-content-center bg-white p-2">
        <a href="/" class="col-10 col-sm-12 d-flex justify-content-center"></a>
    </div> --}}
    <nav class="navbar sticky-top navbar-light navbar-expand-lg border-bottom bg-white p-0">
        <div class="container">
            <a class="navbar-brand ml-2" href="/"><img class="img-fluid" src="/storage/img/logo-mayottesport-com.jpg" alt="Logo MayotteSport" style="height: 40px"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse pr-2" id="navbarSupportedContent">
                <div class="navbar-nav mr-auto bg-white{{--  d-flex align-items-center --}}">
                    {{-- <a class="nav-item nav-link px-3" href="/"><img class="img-fluid mx-auto" src="/storage/img/logo-mayottesport-com.jpg" alt="Logo MayotteSport" style="height: 30px"></a> --}}
                    @foreach ($sports as $sport)
                        <a class="nav-item nav-link @if (strToUrl($sport->nom) == $sportNom) active text-body font-weight-bold @endif px-2" href="/{{ strToUrl($sport->nom) }}">{{ $sport->nom }}</a>
                    @endforeach
                    <a class="nav-item nav-link px-2" href="/autres">Autres</a>
                    <a class="nav-item nav-link px-2" href="/contact">Contact</a>
                </div>
                @include('layouts.connexion')
            </div>
        </div>
    </nav>
    <div class="container-lg-fluid border-bottom" style="background-color: rgba(255, 255, 255, 0.7) !important">
        <div class="container-lg">
            <div class="row overflow-x-auto py-3 mx-0" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center pl-3" style="margin:0;font-size:0.9rem">
                    <a href="{{ $hrefIndex }}" class="flex-shrink-0 text-body font-weight-bold pr-3 float-left">
                        {{ Str::upper($competition) }}
                    </a>
                    <span class="pr-3">
                        <i class="fas fa-angle-right"></i>
                    </span>
                    <a href="{{ $hrefClassement }}" class="flex-shrink-0 @if(request()->route()->getName() == 'competition.classement') text-info @else text-secondary @endif font-weight-bold pr-3">
                        Le classement
                    </a>
                    <a href="{{ $hrefResultats }}" class="d-flex flex-shrink-0 @if(request()->route()->getName() == 'competition.resultats') text-danger @else text-secondary @endif font-weight-bold pr-3">
                        Les résultats
                    </a>
                    <a href="{{ $hrefASuivre }}" class="flex-shrink-0 @if(request()->route()->getName() == 'competition.asuivre') text-success @else text-secondary @endif font-weight-bold pr-3">
                        À suivre
                    </a>
                    <a href="{{ $hrefPalmares }}" class="flex-shrink-0 @if(request()->route()->getName() == 'competition.palmares') text-body @else text-secondary @endif font-weight-bold">
                        Le palmarès
                    </a>
                </div>

                {{-- <a href="">
                    <button class="btn btn-sm mx-2 px-3 btn-light">Classement</button>
                </a> --}}
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
