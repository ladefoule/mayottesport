<!-- Right Side Of Navbar -->
<ul class="navbar-nav ml-auto">
    <!-- Authentication Links -->
    @guest
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
        </li>
        @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">S'inscrire</a>
            </li>
        @endif
    @else
        <li class="nav-item dropdown pl-2">
            <a id="navbarDropdown" class="nav-link dropdown-toggle text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->pseudo }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right mb-2" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('profil') }}">
                    Mon profil
                </a>
                @if(in_array(index('roles')[Auth::user()->role_id]->nom, ['admin', 'superadmin']))
                    <a class="dropdown-item" href="{{ route('code16.sharp.home') }}">
                        Admin{{-- istration --}} (sharp)
                    </a>
                @endif
                @if(in_array(index('roles')[Auth::user()->role_id]->nom, ['admin', 'superadmin']))
                    <a class="dropdown-item" href="{{ route('crud') }}">
                        Le Crud
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
