
<nav class="navbar sticky-top navbar-dark navbar-expand-md bg-dark" style="background-color: #000 !important">
   <div class="container">
       <a class="navbar-brand" href="#"></a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
           aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
           <div class="navbar-nav mr-auto">
                @if (\Auth::user()->role->niveau >= 40) {{-- superadmin --}}
                    <a class="nav-item nav-link px-3 @if (request()->url() == route('crud-gestion.tables')) active @endif" href="{{ route('crud-gestion.tables') }}">Gestion du CRUD</a>
                @endif
               <a class="nav-item nav-link px-3 @if (request()->route()->getName() == 'crud.index') active @endif" href="{{ route('crud') }}">CRUD de la base</a>
               <a class="nav-item nav-link px-3 @if (request()->url() == route('journees.multi.choix-saison')) active @endif" href="{{ route('journees.multi.choix-saison') }}">Journées (multi)</a>
               {{-- <a class="nav-item nav-link px-3 {{ $activeSpec }}" href="{{ route('autres') }}">Actions spécifiques</a> --}}
           </div>

           @include('layouts.connexion')
       </div>
   </div>
</nav>
