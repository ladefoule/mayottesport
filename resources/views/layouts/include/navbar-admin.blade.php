<?php
    $layout = request()->layout;
    $url = request()->url();
?>
<nav class="navbar sticky-top navbar-light navbar-expand-lg border-bottom bg-white p-0">
    <div class="container">
        <a class="navbar-brand ml-3" href="/"><img class="img-fluid" src="/storage/img/logo-mayottesport-com.jpg" alt="Logo MayotteSport" style="height: 40px"></a>
        <button class="navbar-toggler mr-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
           aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
       </button>
       <div class="d-none d-lg-block collapse navbar-collapse pr-2" id="navbarSupportedContent">
           <div class="navbar-nav mr-auto">
                @if (\Auth::user()->role->niveau >= 40) {{-- superadmin --}}
                    <a class="nav-item nav-link px-2 @if ($layout == 'crud-superadmin') active font-weight-bold @endif" href="{{ route('crud-gestion.tables') }}">Superadmin</a>
                @endif
               <a class="nav-item nav-link px-2 @if ($layout == 'crud') active font-weight-bold @endif" href="{{ route('crud') }}">CRUD de la base</a>
               <a class="nav-item nav-link px-2 @if ($url == route('journees.multi.select')) active font-weight-bold @endif" href="{{ route('journees.multi.select') }}">Journées (multi)</a>
               {{-- <a class="nav-item nav-link px-2 {{ $activeSpec }}" href="{{ route('autres') }}">Actions spécifiques</a> --}}
           </div>

           @include('layouts.include.connexion')
       </div>
   </div>
</nav>

{{-- NAVBAR MOBILE --}}
<nav class="navbar-mobile border bg-light d-lg-none h-100 position-fixed overflow-y-auto" data-state="hidden">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">MENU</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="text-danger h3">X</span>
        </button>
          <ul class="navbar-nav w-100 border-bottom">
            <li class="nav-item active px-2 border-bottom">
                <a class="nav-link" href="/">Accueil du site</a>
            </li>
            @if (\Auth::user()->role->niveau >= 40) {{-- superadmin --}}
                <a class="border-bottom nav-item nav-link px-2 @if ($layout == 'crud-superadmin') active font-weight-bold @endif" href="{{ route('crud-gestion.tables') }}">Gestion du CRUD</a>
            @endif
            <a class="border-bottom nav-item nav-link px-2 @if ($layout == 'crud') active font-weight-bold @endif" href="{{ route('crud') }}">CRUD de la base</a>
            <a class="border-bottom nav-item nav-link px-2 @if ($url == route('journees.multi.select')) active font-weight-bold @endif" href="{{ route('journees.multi.select') }}">Journées (multi)</a>
            @include('layouts.include.connexion')
          </ul>
      </nav>
</nav>
{{-- FIN NAVBAR MOBILE --}}
