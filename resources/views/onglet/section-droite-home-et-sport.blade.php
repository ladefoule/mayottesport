@section('section-droite')
<div class="my-3 bg-white" {{-- style="background-color:#ebeff3" --}}>
    {{-- <h2 class="alert alert-danger h4 text-center py-4">Les derniers résultats</h2> --}}
    <div class="col-12 d-flex text-center p-3">
        <a href="" data-cible="fil-actu"
            class="d-block col-4 p-3 border btn btn-secondary onglet active">Fil actu</a>
        <a href="" data-cible="resultats"
            class="d-block col-4 p-3 border btn btn-secondary onglet">Résultats</a>
        <a href="" data-cible="prochains"
            class="d-block col-4 p-3 border btn btn-secondary onglet">À venir</a>
    </div>
    <div id="fil-actu-section-droite" class="col-12 p-2">
        {!! $filActualites !!}
    </div>
    <div id="resultats-section-droite" class="col-12 p-2 d-none">
        @foreach ($resultats as $sport => $journees)
            <div class="col-12 text-center my-2 px-3">
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
    <div id="prochains-section-droite" class="col-12 p-2 d-none">
        @foreach ($prochains as $sport => $journees)
            <div class="col-12 text-center my-2 px-3">
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
@endsection