@foreach ($articles as $i => $article)
    @if ($i == 0)
        <div class="col-12 d-flex flex-wrap p-0 border-bottom mb-3">
            <h2 class="col-12 titre-premier-article p-0">
                <a href="{{ $article->href }}">
                    <span class="categorie">{{ $article->categorie }}</span>
                    {{ $article->titre }}
                </a>
            </h2>
            <div class="col-12 mx-auto my-3 p-0 text-center">
                <a href="{{ $article->href }}"><img src="{{ $article->img }}" alt="{{ $article->titre }}" title="{{ $article->titre }}" class="img-fluid"></a>
            </div>
            <div class="col-12 border-0 p-0 font-weight-bold" style="font-size: 1rem">
                {!! $article->preambule !!}
            </div>
            <p class="w-100 text-secondary text-left">Publié le {{ $article->publie_le }}</p>
        </div>
   @else
        <div class="p-0 pb-3 col-md-6 col-lg-12 col-xl-6 d-flex align-items-stretch @if($i%2 == 1) pr-md-2 @endif @if($i%2 == 0) pl-md-2 @endif">
            <div class="card w-100">
                <a href="{{ $article->href }}">
                    <img class="card-img-top object-fit-cover" height="250" src="{{ $article->img }}" alt="{{ $article->titre }}" title="{{ $article->titre }}">
                </a>
                <div class="card-body pb-0">
                    <h2 class="h4 card-title">
                        <a class="titre-article" href="{{ $article->href }}">
                            <span class="categorie">{{ $article->categorie }}</span>
                            {{ $article->titre }}
                        </a>
                    </h2>
                    <p class="text-secondary">Publié le {{ $article->publie_le }}</p>
                </div>
            </div>
        </div>
   @endif
@endforeach