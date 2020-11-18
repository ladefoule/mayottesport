<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/datatables.min.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link rel="stylesheet" href="/css/style.css?t={{ now() }}">{{-- A ENLEVER EN PRODUCTION --}}
    <title>@yield('title') | mayottesport.com</title>
</head>

<body>
    @include('layouts.include.navbar-sports')
    <div class="container-lg-fluid bg-white border-bottom">
        <div class="container-lg">
            <div class="row overflow-x-auto py-3" id="navbar-scroll-x">
                <div class="d-flex justify-content-start px-3 flex-shrink-0">
                    @foreach (request()->competitions as $competition)
                        <a href="{{ route('competition.index', ['sport' => strToUrl(request()->sport->nom), 'competition' => strToUrl($competition->nom)]) }}">
                            <button class="btn btn-sm mx-2 px-3 btn-outline-dark">
                                {{ $competition->nom }}
                            </button>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <section class="container-lg p-3">
        @yield('content')
    </section>

    {{-- Footer --}}
    @include('layouts.include.footer')
</body>

</html>
