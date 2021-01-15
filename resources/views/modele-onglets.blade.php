{{-- classique écran large --}}
@section('content')
<div class="p-3">
    <div class="d-none d-lg-flex p-0 bg-white">
        <div class="col-12 px-3 py-0">
            <?php 
                $sport = request()->sport;
                $competition = request()->competition;
            ?>
            @if(! $articles && $sport)
                <h1 class="h3 p-3 text-center">{{ $sport->nom . ($competition ? ' - ' . $competition->nom : '') }}</h1>
                <div class="col-12 d-flex px-0 pb-3">
                    {{-- Image par défaut pour les compétitions/sports sans articles liés --}}
                    <img src="{{ asset('/storage/img/as-rosador-de-passamainty-2015.jpg') }}" alt="" class="img-fluid m-auto">
                </div>
            @endif
            {!! $articles !!}
        </div>
    </div>

    {{-- avec onglets --}}
    <div class="col-12 d-lg-none d-flex text-center pb-3 p-0">
        <a href="" id="actualites"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if($articles) active @endif">Actualités</a>
        <a href="" id="resultats"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $articles && $resultats) active @endif">Résultats</a>
        <a href="" id="prochains"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $resultats && ! $articles) active @endif">À venir</a>
    </div>

    <div class="col-12 d-lg-none p-3 bg-white">
        <div id="bloc-actualites" class="@if(! $articles) d-none @endif">
            {!! $articles !!}
        </div>
        <div id="bloc-resultats" class="@if($articles || !$resultats) d-none @endif">
            @foreach ($resultats as $sport => $resultat)
                <div class="col-12 text-center my-2 px-3 @if(count($resultats) == 1) d-none @endif">
                    <span class="h2 font-italic">
                        <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                        {{ $sport }}
                        </a>
                    </span>
                </div>
                {!! $resultat !!}
            @endforeach
        </div>
        <div id="bloc-prochains" class="@if($articles || $resultats) d-none @endif">
            @foreach ($prochains as $sport => $prochain)
                <div class="col-12 text-center my-2 px-3 @if(count($prochains) == 1) d-none @endif">
                    <span class="h2 font-italic">
                        <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                        {{ $sport }}
                        </a>
                    </span>
                </div>
                {!! $prochain !!}
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('section-droite')
<div class="py-3">
    <div class="col-12 p-2 bg-resultats @if(! $resultats) d-none @endif">
        <h2 class="alert alert-danger h4 text-center py-4">Les derniers résultats</h2>
        @foreach ($resultats as $sport => $resultat)
            <div class="col-12 text-center my-2 px-3 @if(count($resultats) == 1) d-none @endif">
                <span class="h2 font-italic">
                    <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                    {{ $sport }}
                    </a>
                </span>
            </div>
            {!! $resultat !!}
        @endforeach
    </div>
    <div class="col-12 p-2 bg-resultats d-none @if(! $resultats) d-block @endif">
        <h2 class="alert alert-success h4 text-center py-4">La prochaine journée</h2>
        @foreach ($prochains as $sport => $prochain)
            <div class="col-12 text-center my-2 px-3 @if(count($prochains) == 1) d-none @endif">
                <span class="h2 font-italic">
                    <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                    {{ $sport }}
                    </a>
                </span>
            </div>
            {!! $prochain !!}
        @endforeach
    </div>
</div>
@endsection