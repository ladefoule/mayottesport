
<nav class="navbar sticky-top navbar-light navbar-expand-lg border-bottom bg-white p-0">
    <div class="container">
        <a class="navbar-brand ml-3" href="/"><img class="img-fluid" src="/storage/img/logo-mayottesport-com.jpg" alt="Logo MayotteSport" style="height: 40px"></a>
        <button class="navbar-toggler mr-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
           aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse pr-2" id="navbarSupportedContent">
           <div class="navbar-nav mr-auto">
                @if (\Auth::user()->role->niveau >= 40) {{-- superadmin --}}
                    <a class="nav-item nav-link px-2 @if (request()->url() == route('crud-gestion.tables')) active font-weight-bold @endif" href="{{ route('crud-gestion.tables') }}">Gestion du CRUD</a>
                @endif
               <a class="nav-item nav-link px-2 @if (in_array(request()->route()->getName(), ['crud.index', 'crud.create', 'crud.update', 'crud.show'])) active font-weight-bold @endif" href="{{ route('crud') }}">CRUD de la base</a>
               <a class="nav-item nav-link px-2 @if (request()->url() == route('journees.multi.choix-saison')) active font-weight-bold @endif" href="{{ route('journees.multi.choix-saison') }}">Journées (multi)</a>
               {{-- <a class="nav-item nav-link px-2 {{ $activeSpec }}" href="{{ route('autres') }}">Actions spécifiques</a> --}}
           </div>

           @include('layouts.connexion')
       </div>
   </div>
</nav>
