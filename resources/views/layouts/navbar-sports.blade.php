<nav class="navbar sticky-top navbar-light navbar-expand-lg border-bottom bg-white p-0">
   <div class="container">
       <a class="navbar-brand ml-2" href="/"><img class="img-fluid" src="/storage/img/logo-mayottesport-com.jpg" alt="Logo MayotteSport" style="height: 40px"></a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
           aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse pr-2" id="navbarSupportedContent">
           <div class="navbar-nav mr-auto bg-white{{--  d-flex align-items-center --}}">
               {{-- <a class="nav-item nav-link px-3" href="/"><img class="img-fluid mx-auto" src="/storage/img/logo-mayottesport-com.jpg" alt="Logo MayotteSport" style="height: 30px"></a> --}}
               @foreach (request()->sports as $sport)
                   <a class="nav-item nav-link @if (strToUrl($sport->nom) == request()->sport) active text-body font-weight-bold @endif px-2" href="/{{ strToUrl($sport->nom) }}">{{ $sport->nom }}</a>
               @endforeach
               <a class="nav-item nav-link px-2" href="/autres">Autres</a>
               <a class="nav-item nav-link px-2" href="/contact">Contact</a>
           </div>
           @include('layouts.connexion')
       </div>
   </div>
</nav>
