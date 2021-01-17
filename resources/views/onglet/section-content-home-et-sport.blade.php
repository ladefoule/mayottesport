@section('content')
<div class="p-0 h-100">
    {{-- classique écran large --}}
    <div class="d-none d-lg-block h-100 p-3">
        <div class="col-12 p-3 bg-white d-flex flex-wrap justify-content-start p-0 pb-3 h-100 align-items-start">
            @if(! $articles && isset($sport) && $sport)
                <div class="row">
                    {{-- Image pour les sports sans articles liés --}}
                    <img src="{{ asset('/storage/img/sport/'. $sport->slug .'.jpg') }}" alt="" class="img-fluid m-auto">
                </div>
            @endif
            {!! $articles !!}
        </div>
    </div>

    {{-- avec onglets --}}
    <div id="onglets-content" class="col-12 d-lg-none d-flex text-center p-3 bg-white">
        <a href="" data-cible="actualites-content"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if($articles) active @endif">Actualités</a>
        <a href="" data-cible="resultats-content"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $articles && $resultats) active @endif">Résultats</a>
        <a href="" data-cible="prochains-content"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $resultats && ! $articles) active @endif">À venir</a>
    </div>

    <div class="col-12 d-lg-none bg-white">
        <div id="actualites-content" class="pb-3 @if(! $articles) d-none @endif">
            <div class="d-flex flex-wrap justify-content-start">
                {!! $articles !!}
            </div>
        </div>
        <div id="resultats-content" class="@if($articles || $resultats) d-none @endif">
            @foreach ($resultats as $sport => $journees)
                <div class="col-12 text-center pb-2 px-3">
                    <span class="h2 font-italic">
                        <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                        {{ $sport }}
                        </a>
                    </span>
                </div>
                @foreach ($journees as $journee)
                    <div class="col-12 text-center pb-3 justify-content-between">
                        <h3 class="col-12 h4 border-bottom-calendrier py-2">
                            <a class="text-green-light" href="{{ $journee['competition_href'] }}">
                                {{ $journee['competition_nom'] }}
                            </a>
                        </h3>
                        <div class="pl-0">
                                {!! $journee['journee_render'] !!}
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
        <div id="prochains-content" class="@if($articles || $resultats) d-none @endif">
            @foreach ($prochains as $sport => $journees)
                <div class="col-12 text-center pb-2 px-3">
                    <span class="h2 font-italic">
                        <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                        {{ $sport }}
                        </a>
                    </span>
                </div>
                @foreach ($journees as $journee)
                    <div class="col-12 text-center pb-3 justify-content-between">
                        <h3 class="col-12 h4 border-bottom-calendrier py-2">
                            <a class="text-green-light" href="{{ $journee['competition_href'] }}">
                                {{ $journee['competition_nom'] }}
                            </a>
                        </h3>
                        <div class="pl-0">
                                {!! $journee['journee_render'] !!}
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>
@endsection