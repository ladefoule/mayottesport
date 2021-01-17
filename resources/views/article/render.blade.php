@php $i = 0; @endphp
@foreach ($articles as $article)
<div class="d-flex flex-wrap justify-content-start border-bottom p-0 pb-3">
   @if ($i == 0)
      <h1 class="col-12 titre-premier-article p-0"><a href="{{ $article->href }}">{{ $article->titre }}</a></h1>
      <div class="col-12 my-3 p-0 text-center">
         <a href="{{ $article->href }}"><img src="{{ $article->img }}" alt="{{ $article->titre }}" title="{{ $article->titre }}" class="img-fluid"></a>
      </div>
      <div class="col-12 border-0 p-0">
         {!! $article->preambule !!}
      </div>
      <p class="w-100 text-secondary text-left">Publié le {{ $article->publie_le }}</p>
   @else
      {{-- <div class="col-6 pt-3 px-0 pr-1">
         <h1 class="col-12 titre-article p-0"><a class="" href="{{ $article->href }}">{{ $article->titre }}</a></h1>
         <div class="col-12 border-0 p-0 d-none d-sm-block resume">
               {!! $article->preambule !!}
         </div>
         <p class="text-secondary">Publié le {{ $article->publie_le }}</p>
      </div> --}}
      <div class="col-sm-6 pt-3 px-0">
         <a  style="height: 150px" href="{{ $article->href }}"><img src="{{ $article->img }}" alt="{{ $article->titre }}" title="{{ $article->titre }}" class="img-fluid"></a>
         <h1 class="col-12 titre-article p-0"><a class="" href="{{ $article->href }}">{{ $article->titre }}</a></h1>
      </div>
   @endif
</div>
@php $i++; @endphp
@endforeach