@section('section-droite')
<div class="my-3 pb-2 bg-white shadow-div" {{-- style="background-color:#ebeff3" --}}>
    {{-- <h2 class="alert alert-danger h4 text-center py-4">Les derniers résultats</h2> --}}
    <div class="col-12 d-flex text-center p-2">
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
            <div class="col-12 text-center pt-1">
                <a class="nom-sport text-secondary" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                    {{ $sport }}
                </a>
            </div>
            @foreach ($journees as $journee)
                <div class="col-12 text-center pb-3 justify-content-between">
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
    <div id="prochains-section-droite" class="col-12 p-2 d-none">
        @foreach ($prochains as $sport => $journees)
            <div class="col-12 text-center pt-1">
                <a class="nom-sport text-secondary" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                    {{ $sport }}
                </a>
            </div>
            @foreach ($journees as $journee)
                <div class="col-12 text-center pb-3 justify-content-between">
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
    
    @include('pub.google-display-fixe-vertical')
</div>
@endsection