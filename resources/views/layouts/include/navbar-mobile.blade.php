<!-- Modal NAVBAR MOBILE -->
<div class="modal fade" id="navbarModal" aria-labelledby="Menu" aria-describedby="Menu complet du site" tabindex="-1" aria-labelledby="navbarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header align-items-center">
          <img class="logo img-fluid" src="{{ asset('/storage/img/logo-mayottesport-com.jpg') }}" alt="Logo MayotteSport">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-0">
            <nav class="navbar navbar-light bg-light navbar-mobile">
                <div class="col-12 pr-2 align-self-stretch" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item @if(request()->route()->getName() == 'home') active text-green font-weight-bold @endif mx-0 border-bottom">
                            <a class="nav-link text-body" href="{{ asset('/') }}"><span style="font-size:1.2rem" class="ml-2 pl-1">{!! config('listes.boutons.home') !!}</span> Accueil</a>
                        </li>
                        @foreach ($sports as $sport)
                            <?php $competitionsLies = $competitions->where('sport_id', $sport->id); ?>
                            @if (count($competitionsLies->all()) > 0)
                                <li id="modal-li-{{ $sport->slug }}" class="{{ $sport->slug }} nav-item dropdown border-bottom px-3">
                                    <span class="nav-link text-body d-flex align-items-center dropdown-toggle @if (request()->sport && $sport->nom == request()->sport->nom) active text-green font-weight-bold @endif" id="navbarDropdownMenuLink{{ $sport->nom }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img class="img-fluid mr-2" src="{{ asset('/storage/img/icons/' . $sport->slug .'.png') }}" width="18" height="18">
                                        {{ $sport->nom }}
                                    </span>
                                    <div class="dropdown-menu mb-2" aria-labelledby="navbarDropdownMenuLink{{ $sport->nom }}">
                                        <a class="dropdown-item @if(request()->route()->getName() == 'sport.index' && request()->route('sport') == $sport->slug) active bg-success @endif" href="{{ route('sport.index', ['sport' => $sport->slug]) }}">Accueil {{ \Str::lower($sport->nom) }}</a>
                                        @foreach ($competitionsLies as $competition)
                                            <a class="dropdown-item @if(request()->route()->getName() == 'competition.index' && request()->route('competition') == $competition->slug_complet) active bg-success @endif" href="{{ route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug_complet]) }}">
                                                {{ $competition->nom }}
                                            </a>
                                        @endforeach
                                    </div>
                                </li>
                            @else
                                <li class="nav-item border-bottom px-3">
                                    <a class="nav-link text-body d-flex align-items-center @if (request()->sport && $sport->nom == request()->sport->nom) active text-green font-weight-bold @endif" href="{{ route('sport.index', ['sport' => $sport->slug]) }}">
                                        <img class="img-fluid mr-2" src="{{ asset('/storage/img/icons/' . $sport->slug .'.png') }}" width="18" height="18">
                                        {{ $sport->nom }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        {{-- <a class="border-bottom nav-item nav-link text-body px-3" href="{{ asset('/autres') }}">Autres</a> --}}
                        <a class="border-bottom nav-item nav-link text-body mx-0" href="{{ asset('/contact') }}">
                            <span style="font-size:1.1rem" class="ml-2 pl-1 mr-1">{!! config('listes.boutons.contact') !!}</span> Contact
                        </a>
                    </ul>
                    <?php
                        $role = Auth::check() ? Auth::user()->role->name : '';
                    ?>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav pt-3">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item px-3 border-bottom">
                                <a class="nav-link text-body" href="{{ route('login') }}"><span class="text-success">{!! config('listes.boutons.user') !!}</span> Se connecter</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item px-3">
                                    <a class="nav-link text-body" href="{{ route('register') }}"><span class="text-primary">{!! config('listes.boutons.user-add') !!}</span> S'inscrire</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown px-3 {{-- border-bottom --}}">
                                <span id="navbarDropdown" class="nav-link text-body dropdown-toggle text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img class="rounded-circle mr-2 avatar" src="{{ $urlAvatar }}" alt="Avatar">
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
                                    @endif
                                    @if($role == 'superadmin')
                                        <a class="dropdown-item" href="{{ route('cache.flush') }}">
                                            Vider le cache
                                        </a>
                                        <a class="dropdown-item" href="{{ route('cache.refresh') }}">
                                            Recharger le cache
                                        </a>
                                        <a class="dropdown-item" href="{{ route('script') }}">
                                            Exécuter un script
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