@section('content')
{{-- <div class="p-0"> --}}
    {{-- classique écran large --}}
    <div class="d-none d-lg-block p-3 {{-- h-100 --}}">
        <div class="col-12 p-0 {{-- h-100 --}} bg-white shadow-div">
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
    <div id="onglets-content" class="col-12 d-lg-none d-flex text-center p-3 bg-white">
        <span data-cible="actualites-content"
            class="text-decoration-none d-block col-4 p-3 border btn btn-secondary onglet @if($articles) active @endif">Actualités</span>
        <span data-cible="resultats-content"
            class="text-decoration-none d-block col-4 p-3 border btn btn-secondary onglet @if(! $articles && $resultats) active @endif">Résultats</span>
        <span data-cible="prochains-content"
            class="text-decoration-none d-block col-4 p-3 border btn btn-secondary onglet @if(! $resultats && ! $articles) active @endif">À venir</span>
    </div>

    <div class="col-12 d-lg-none bg-white d-flex pb-3 px-0 flex-wrap justify-content-center">        
        <div id="actualites-content" class="col-12 pb-3 @if(! $articles) d-none @endif">
            <div class="d-flex flex-wrap justify-content-start align-items-stretch">
                {!! $articles !!}
            </div>
        </div>
        <div id="resultats-content" class="col-12 @if($articles || !$resultats) d-none @endif">
            @foreach ($resultats as $sport => $journees)
                <div class="col-12 text-center pt-1">
                    <a class="nom-sport text-secondary" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
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
        <div id="prochains-content" class="col-12 @if($articles || $resultats) d-none @endif">
            @foreach ($prochains as $sport => $journees)
                <div class="col-12 text-center pt-1">
                    <a class="nom-sport text-secondary" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
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

        <div class="col-12 px-3">
            @include('pub.google-display-responsive')
        </div>
    </div>
{{-- </div> --}}
@endsection