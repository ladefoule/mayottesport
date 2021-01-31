@section('content')
{{-- <div class="p-0"> --}}
    {{-- classique écran large --}}
    <div class="d-none d-lg-block p-3">
        <div class="col-12 p-0 bg-white shadow-div">
            <div class="col-12 p-3 d-flex flex-wrap justify-content-start align-items-stretch">
                @if(! $articles && isset($sport) && $sport)
                    <div class="col-12">
                        <h1 class="h3 text-center m-auto">{{ $sport->nom }} - Résultats et actualités</h1>
                    </div>
                @endif
                {!! $articles !!}
            </div>

            {{-- PUB --}}
            <div class="col-12 m-auto p-3">
                @include('pub.google-display-responsive')
            </div>
        </div>
    </div>

    {{-- avec onglets --}}
    <div id="onglets-content" class="col-12 d-lg-none d-flex text-center py-3 px-2 bg-white">
        <span data-cible="a-la-une-content"
            class="text-decoration-none d-block col-4 p-3 border btn btn-secondary onglet @if($articles) active @endif">À la une</span>
        <span data-cible="fil-actu-content"
            class="text-decoration-none d-block col-4 p-3 border btn btn-secondary onglet @if(! $articles && $filActualites) active @endif">Fil actu</span>
        <span data-cible="resultats-content"
            class="text-decoration-none d-block col-4 p-3 border btn btn-secondary onglet @if(! $articles && ! $filActualites) active @endif">Résultats</span>
    </div>

    <div class="col-12 d-lg-none bg-white d-flex pb-3 px-0 flex-wrap justify-content-center">  
        {{-- A LA UNE --}}
        <div id="a-la-une-content" class="col-12 pb-3 @if(! $articles) d-none @endif">
            <div class="d-flex flex-wrap justify-content-start align-items-stretch">
                {!! $articles !!}
            </div>
        </div>
        
        {{-- FIL ACTU --}}
        <div id="fil-actu-content" class="col-12 px-2 @if($articles || !$filActualites) d-none @endif">
            @foreach ($filActualites as $actu)
                <div class="col-12 d-flex border-bottom p-0">
                    <div class="date col-2 d-flex flex-wrap align-items-center justify-content-center text-secondary">
                        @if($actu->date_fil_actu == date('d/m'))
                            {!! $actu->heure_fil_actu !!}
                        @else
                            {!! $actu->date_fil_actu !!}
                        @endif
                    </div>
                    <div class="col-10 p-2">
                        <div class="categorie col-12 text-danger p-0">{{ $actu->categorie }}</div>
                        <div class="col-12 p-0">{!! $actu->preambule !!}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- RESULTATS --}}
        <div id="resultats-content" class="col-12 px-2 @if($articles || $filActualites) d-none @endif">
            @foreach ($resultats as $sport => $journees)
                <div class="col-12 text-center pt-1">
                    <a class="nom-sport text-primary border-bottom border-primary" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                        {{ $sport }}
                    </a>
                </div>
                @foreach ($journees as $journee)
                    <div class="col-12 text-center pb-3">
                        <a class="d-block nom-competition py-2" href="{{ $journee['competition_href'] }}">
                            {{ $journee['competition_nom'] }}
                        </a>
                        <div class="pl-0">
                                {!! $journee['journee_render'] !!}
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        {{-- PUB --}}
        <div class="col-12 px-3">
            @include('pub.google-display-responsive')
        </div>
    </div>
{{-- </div> --}}
@endsection