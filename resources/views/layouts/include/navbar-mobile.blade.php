<!-- Modal NAVBAR MOBILE -->
<div class="modal fade {{-- d-lg-none --}}" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">LE MENU</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <nav class="navbar {{-- navbar-expand-lg --}} navbar-light bg-light">
                {{-- <a class="navbar-brand" href="#">MENU</a> --}}
                <div class="col-12 pr-2 align-self-stretch" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item @if(request()->route()->getName() == 'home') active text-green font-weight-bold @endif px-3 border-bottom">
                            <a class="nav-link" href="{{ asset('/') }}">Accueil</a>
                        </li>
                        @foreach ($sports as $sport)
                            @if (count($competitions->where('sport_id', $sport->id)->all()) > 0)
                                <li class="nav-item dropdown border-bottom px-3">
                                    <span class="nav-link dropdown-toggle @if (request()->sport && $sport->nom == request()->sport->nom) active text-green font-weight-bold @endif" href="#" id="navbarDropdownMenuLink{{ $sport->id }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {!! config('listes.boutons.' . \Str::slug($sport->nom)) !!} {{ $sport->nom }}
                                    </span>
                                    <div class="dropdown-menu mb-2" aria-labelledby="navbarDropdownMenuLink{{ $sport->id }}">
                                        <a class="dropdown-item" href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">Accueil {{ \Str::lower($sport->nom) }}</a>
                                        @foreach ($competitions->where('sport_id', $sport->id) as $competition)
                                            <a class="dropdown-item" href="{{ route('competition.index', ['sport' => \Str::slug($sport->nom), 'competition' => \Str::slug($competition->nom)]) }}">{{ $competition->nom }}</a>
                                        @endforeach
                                    </div>
                                </li>
                            @else
                                <li class="nav-item border-bottom px-3">
                                    <a class="nav-link @if (request()->sport && $sport->nom == request()->sport->nom) active text-green font-weight-bold @endif" href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">{{ $sport->nom }}</a>
                                </li>
                            @endif
                        @endforeach
                        <a class="border-bottom nav-item nav-link px-3" href="{{ asset('/autres') }}">Autres</a>
                        <a class="border-bottom nav-item nav-link px-3" href="{{ asset('/contact') }}">Contact</a>
                    </ul>
                    <?php
                        $role = Auth::check() ? Auth::user()->role->name : '';
                    ?>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav pt-3">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item px-3 border-bottom">
                                <a class="nav-link" href="{{ route('login') }}">Se connecter</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item px-3 border-bottom">
                                    <a class="nav-link" href="{{ route('register') }}">S'inscrire</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown px-3 border-bottom">
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
            </nav>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
</div>
<!-- Fin Modal NAVBAR MOBILE -->