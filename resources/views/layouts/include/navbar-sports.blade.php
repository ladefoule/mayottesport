<?php
    $sports = index('sports')->sortBy('home_position')->slice(0, 5);
    $competitions = index('competitions');
?>

{{-- NAVBAR LARGE SCREEN --}}
<nav class="navbar fixed-top navbar-light navbar-expand-lg border-bottom bg-white p-0">
   <div class="container">
       <a class="navbar-brand pl-3" href="{{ route('home') }}"><img class="img-fluid" src="{{ config('app.url') }}/storage/img/logo-mayottesport-com.jpg" alt="Logo MayotteSport" style="width: 200px"></a>
       <button class="navbar-toggler mr-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
           aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
       </button>
       <div class="d-none d-lg-block collapse navbar-collapse pr-2 align-self-stretch" id="navbarSupportedContent">
           <ul class="navbar-nav mr-auto bg-white align-self-stretch" style="font-size: 1.1rem">
               @foreach ($sports as $sport)
                <li class="nav-item">
                   <a class="nav-link border-bottom-nav text-body px-2 h-100 d-flex align-items-center @if (request()->sport && $sport->nom == request()->sport->nom) active font-weight-bold @endif" href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">{{ $sport->nom }}</a>
                </li>
                @endforeach
                <li class="nav-item">
                    <a class="nav-link border-bottom-nav text-body px-2 h-100 d-flex align-items-center" href="{{ config('app.url') }}/autres">Autres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link border-bottom-nav text-body px-2 h-100 d-flex align-items-center" href="{{ config('app.url') }}/contact">Contact</a>
                </li>
           </ul>
           @include('layouts.include.connexion')
       </div>
   </div>
</nav>
{{-- FIN NAVBAR LARGE SCREEN --}}

{{-- NAVBAR MOBILE --}}
<nav class="navbar-mobile border bg-light d-lg-none h-100 position-fixed overflow-y-auto" data-state="hidden">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">MENU</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="text-danger h3">X</span>
        </button>
          <ul class="navbar-nav w-100 border-bottom">
            <li class="nav-item active px-2 border-bottom">
                <a class="nav-link" href="{{ config('app.url') }}">Accueil</a>
            </li>
            @foreach ($sports as $sport)
                @if ($competitions->where('sport_id', $sport->id)->all() > 0)
                    <li class="nav-item dropdown border-bottom px-2">
                        <a class="nav-link dropdown-toggle @if (request()->sport && $sport->nom == request()->sport->nom) active text-body font-weight-bold @endif" href="#" id="navbarDropdownMenuLink{{ $sport->id }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {!! config('listes.boutons.' . \Str::slug($sport->nom)) !!} {{ $sport->nom }}
                        </a>
                        <div class="dropdown-menu mb-2" aria-labelledby="navbarDropdownMenuLink{{ $sport->id }}">
                            <a class="dropdown-item" href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">Accueil {{ \Str::lower($sport->nom) }}</a>
                            @foreach ($competitions->where('sport_id', $sport->id) as $competition)
                                <a class="dropdown-item" href="{{ route('competition.index', ['sport' => \Str::slug($sport->nom), 'competition' => \Str::slug($competition->nom)]) }}">{{ $competition->nom }}</a>
                            @endforeach
                        </div>
                    </li>
                @else
                    <li class="nav-item border-bottom px-2">
                        <a class="nav-link @if (request()->sport && $sport->nom == request()->sport->nom) active text-body font-weight-bold @endif" href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">{{ $sport->nom }}</a>
                    </li>
                @endif
            @endforeach
            <a class="border-bottom nav-item nav-link px-2" href="{{ config('app.url') }}/autres">Autres</a>
            <a class="border-bottom nav-item nav-link px-2" href="{{ config('app.url') }}/contact">Contact</a>
            @include('layouts.include.connexion')
          </ul>
      </nav>
</nav>
{{-- FIN NAVBAR MOBILE --}}
