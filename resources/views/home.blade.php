@extends('layouts.site')

@section('title', 'Accueil de notre site')

@section('content')

<div class="row bg-white justify-content-center">
    <div class="col-12 text-center p-3">
        <h1 class="h4">MayotteSport.com : l'actualité sportive de Mayotte</h1>
    </div>

    <div class="col-12 d-lg-none p-0 d-flex text-center">
         <a href="" id="actualites" data-cible="bloc-actualites" data-autre="resultats" class="d-block col-6 p-3 border btn btn-secondary onglet active">Actualités</a>
         <a href="" id="resultats" data-cible="bloc-resultats" data-autre="actualites" class="d-block col-6 p-3 border btn btn-secondary onglet">Résultats</a>
    </div>

    {{-- classique écran large --}}
    <div class="col-12 d-none d-lg-flex p-0">
      <div class="col-8 pr-lg-3 p-2">
         {!! $articles !!}
      </div>
      <div class="col-4 p-2 bg-resultats" style="font-size: 0.8rem">
         {!! $journees !!}
      </div>
    </div>

    {{-- avec onglets --}}
    <div class="col-12 d-lg-none p-0">
      <div id="bloc-actualites">
            {!! $articles !!}
      </div>
      <div id="bloc-resultats" class="d-none">
            {!! $journees !!}
      </div>
   </div>
</div>
@endsection

@section('script')
<script>
   $(document).ready(function(){
      $('.onglet').on('click', function(e){
         e.preventDefault()
         let target = e.target
         if(target.classList.contains('active'))
            return false;

         target.classList.toggle('active')

         let autreId = target.dataset.autre
         let autre = qs('#'+autreId)
         autre.classList.toggle('active')

         qs('#bloc-actualites').classList.toggle('d-none')
         qs('#bloc-resultats').classList.toggle('d-none')

      })
   })
   </script>
   @endsection
