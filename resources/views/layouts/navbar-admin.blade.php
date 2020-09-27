<?php
    $path = request()->path();
    $activeGestion = $activeCrud = $activeSpec = 0;
    if(Str::contains($path, 'admin/crud-gestion')) $activeGestion = 'active';
    if(Str::contains($path, 'admin/crud/')) $activeCrud = 'active';
    if(Str::contains($path, 'admin/autres')) $activeSpec = 'active';
?>
<nav class="navbar sticky-top navbar-dark navbar-expand-md bg-dark" style="background-color: #000 !important">
   <div class="container">
       <a class="navbar-brand" href="#"></a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
           aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
           <div class="navbar-nav mr-auto">
                @if (\Auth::user()->role->niveau >= 40)
                    <a class="nav-item nav-link px-3 {{ $activeGestion }}" href="{{ route('crud-gestion.tables') }}">Gestion du CRUD</a>
                @endif

               <a class="nav-item nav-link px-3 {{ $activeCrud }}" href="{{ route('crud') }}">CRUD de la base</a>
               <a class="nav-item nav-link px-3 {{ $activeSpec }}" href="{{ route('autres') }}">Actions spécifiques</a>
           </div>

           @include('layouts.connexion')
       </div>
   </div>
</nav>
