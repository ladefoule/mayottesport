@section('content')
<div class="p-0 h-100">
    {{-- classique écran large --}}
    <div class="d-none d-lg-flex h-100 p-3">
        <div class="col-12 p-3 bg-white">
            <?php 
                $sport = request()->sport;
                $competition = request()->competition;
            ?>
            @if(! $articles && $sport)
                {{-- <h1 class="h3 p-3 text-center">{{ $sport->nom . ($competition ? ' - ' . $competition->nom : '') }}</h1> --}}
                <div class="col-12 d-flex p-0">
                    {{-- Image par défaut pour les compétitions/sports sans articles liés --}}
                    <img src="{{ asset('/storage/img/as-rosador-de-passamainty-2015.jpg') }}" alt="" class="img-fluid m-auto">
                </div>
            @endif
            {!! $articles !!}
        </div>
    </div>

    {{-- avec onglets --}}
    <div class="col-12 d-lg-none d-flex text-center p-3 bg-white">
        <a href="" data-cible="actualites"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if($articles) active @endif">Actualités</a>
        <a href="" data-cible="resultats"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $articles && $resultats) active @endif">Résultats</a>
        <a href="" data-cible="prochains"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $resultats && ! $articles) active @endif">À venir</a>
    </div>

    <div class="col-12 d-lg-none bg-white">
        <div class="bloc-actualites @if(! $articles) d-none @endif">
            {!! $articles !!}
        </div>
        <div class="bloc-resultats @if($articles || $resultats) d-none @endif">
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
        <div class="bloc-prochains @if($articles || $resultats) d-none @endif">
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