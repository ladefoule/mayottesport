<!doctype html>
<html lang="fr">

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
    @include('layouts.navbar-sports')
    <div class="container-lg-fluid bg-white border-bottom">
        <div class="container-lg">
            <div class="row overflow-x-auto py-2" id="navbar-scroll-x">
                <div class="d-flex justify-content-start px-3">
                    @foreach (request()->competitions as $competition)
                        <a href="{{ route('competition.index', ['sport' => strToUrl(request()->sport->nom), 'competition' => strToUrl($competition->nom)]) }}">
                            <button class="btn btn-sm mx-2 px-3 btn-dark">
                                {{ $competition->nom }}
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
