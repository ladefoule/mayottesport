@section('section-droite')
<div class="my-3 bg-white" {{-- style="background-color:#ebeff3" --}}>
    {{-- <h2 class="alert alert-danger h4 text-center py-4">Les derniers résultats</h2> --}}
    <div class="col-12 d-flex text-center p-3">
        <span data-cible="fil-actu-section-droite"
            class="d-block col-4 p-3 border btn btn-secondary onglet active">Fil actu</span>
        <span data-cible="resultats-section-droite"
            class="d-block col-4 p-3 border btn btn-secondary onglet">Résultats</span>
        <span data-cible="prochains-section-droite"
            class="d-block col-4 p-3 border btn btn-secondary onglet">À venir</span>
    </div>
    <div id="fil-actu-section-droite" class="col-12 p-2">
        {!! $filActualites !!}
    </div>
    <div id="resultats-section-droite" class="col-12 p-2 d-none">
        @foreach ($resultats as $sport => $journees)
            <div class="col-12 text-center">
                <span class="nom-sport font-italic">
                    <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                    {{ $sport }}
                    </a>
                </span>
            </div>
            @foreach ($journees as $journee)
                <div class="col-12 text-center pb-3 justify-content-between">
                    <p class="col-12 h4 border-bottom-calendrier p2-2">
                        <a href="{{ $journee['competition_href'] }}">
                            {{ $journee['competition_nom'] }}
                        </a>
                    </p>
                    <div class="pl-0">
                            {!! $journee['journee_render'] !!}
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
    <div id="prochains-section-droite" class="col-12 p-2 d-none">
        @foreach ($prochains as $sport => $journees)
            <div class="col-12 text-center">
                <span class="nom-sport font-italic">
                    <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                    {{ $sport }}
                    </a>
                </span>
            </div>
            @foreach ($journees as $journee)
                <div class="col-12 text-center pb-3 justify-content-between">
                    <p class="col-12 h4 border-bottom-calendrier p2-2">
                        <a href="{{ $journee['competition_href'] }}">
                            {{ $journee['competition_nom'] }}
                        </a>
                    </p>
                    <div class="pl-0">
                            {!! $journee['journee_render'] !!}
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>
@endsection