<?php
    $sports = index('sports')->sortBy('home_position')->slice(0, 5);
    $competitions = index('competitions');
?>

{{-- NAVBAR LARGE SCREEN --}}
<nav class="navbar fixed-top navbar-light navbar-expand-lg border-bottom-defaut bg-white p-0">
   <div class="container">
       <a class="navbar-brand pl-3" href="{{ route('home') }}"><img class="img-fluid" src="{{ asset('/storage/img/logo-mayottesport-com.jpg') }}" alt="Logo MayotteSport" style="width: 200px"></a>
       <button class="navbar-toggler mr-3" type="button" {{-- data-toggle="collapse" data-target="#navbarSupportedContent" --}}
           aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" data-toggle="modal" data-target="#exampleModal">
           <span class="navbar-toggler-icon"></span>
       </button>
       <div class="d-none d-lg-block collapse navbar-collapse pr-2 align-self-stretch" id="navbarSupportedContent">
           <ul class="navbar-nav mr-auto bg-white align-self-stretch" style="font-size: 1.1rem">
               @foreach ($sports as $id => $sport)
                <li class="nav-item">
                   <a class="nav-link border-bottom-nav text-body px-2 h-100 d-flex align-items-center @if (request()->sport && $sport->nom == request()->sport->nom) active font-weight-bold text-green @endif" href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">{{ $sport->nom }}</a>
                </li>
                @endforeach
                <li class="nav-item">
                    <a class="nav-link border-bottom-nav text-body px-2 h-100 d-flex align-items-center" href="{{ asset('/contact') }}">Contact</a>
                </li>
           </ul>
           <?php
                $role = Auth::check() ? Auth::user()->role->name : '';
            ?>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item pl-1 d-flex flex-shrink-0">
                        <a class="nav-link" href="{{ route('login') }}"><span class="text-success">{!! config('listes.boutons.user') !!}</span> Se connecter</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item pl-0 d-flex flex-shrink-0">
                            <a class="nav-link" href="{{ route('register') }}"><span class="text-primary">{!! config('listes.boutons.user-add') !!}</span> S'inscrire</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown pl-2 d-flex flex-shrink-0">
                        <span id="navbarDropdown" class="nav-link dropdown-toggle text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->pseudo }} <span class="caret"></span>
                        </span>

                        <div class="dropdown-menu dropdown-menu-right mb-2" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profil') }}">
                                Mon profil
                            </a>
                            @if(in_array($role, ['admin', 'superadmin']))
                                <a class="dropdown-item" href="{{ route('code16.sharp.home') }}">
                                    Administration
                                </a>
                                {{-- <a class="dropdown-item" href="{{ route('crud') }}">
                                    Le Crud
                                </a> --}}
                            @endif
                            @if($role == 'superadmin')
                                <a class="dropdown-item" href="{{ asset('/script.html') }}">
                                    Vider le cache
                                </a>
                            @endif
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                Déconnexion
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
       </div>
   </div>
</nav>
{{-- FIN NAVBAR LARGE SCREEN --}}

@include('layouts.include.navbar-mobile')