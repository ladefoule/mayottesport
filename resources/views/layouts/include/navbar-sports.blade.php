<?php
$i = 0;
    $sports = index('sports');
    $competitions = index('competitions');
?>

{{-- NAVBAR LARGE SCREEN --}}
<nav class="navbar fixed-top navbar-light navbar-expand-lg border-bottom bg-white p-0">
   <div class="container">
       <a class="navbar-brand pl-3" href="{{ route('home') }}"><img class="img-fluid" src="{{ config('app.url') }}/storage/img/logo-mayottesport-com.jpg" alt="Logo MayotteSport" style="height: 40px"></a>
       <button class="navbar-toggler mr-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
           aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
       </button>
       <div class="d-none d-lg-block collapse navbar-collapse pr-2" id="navbarSupportedContent">
           <div class="navbar-nav mr-auto bg-white">
               @foreach ($sports as $sport)
                   <a class="nav-item nav-link @if (request()->sport && $sport->nom == request()->sport->nom) active text-body font-weight-bold @endif px-2" href="{{ route('sport.index', ['sport' => strToUrl($sport->nom)]) }}">{{ $sport->nom }}</a>
                    <?php if($i++ == 5) break; ?>
                @endforeach
               <a class="nav-item nav-link px-2" href="{{ config('app.url') }}/autres">Autres</a>
               <a class="nav-item nav-link px-2" href="{{ config('app.url') }}/contact">Contact</a>
           </div>
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
                            {!! config('constant.boutons.' . strToUrl($sport->nom)) !!} {{ $sport->nom }}
                        </a>
                        <div class="dropdown-menu mb-2" aria-labelledby="navbarDropdownMenuLink{{ $sport->id }}">
                            <a class="dropdown-item" href="{{ route('sport.index', ['sport' => strToUrl($sport->nom)]) }}">Accueil {{ \Str::lower($sport->nom) }}</a>
                            @foreach ($competitions->where('sport_id', $sport->id) as $competition)
                                <a class="dropdown-item" href="{{ route('competition.index', ['sport' => strToUrl($sport->nom), 'competition' => strToUrl($competition->nom)]) }}">{{ $competition->nom }}</a>
                            @endforeach
                        </div>
                    </li>
                @else
                    <li class="nav-item border-bottom px-2">
                        <a class="nav-link @if (request()->sport && $sport->nom == request()->sport->nom) active text-body font-weight-bold @endif" href="{{ route('sport.index', ['sport' => strToUrl($sport->nom)]) }}">{{ $sport->nom }}</a>
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
