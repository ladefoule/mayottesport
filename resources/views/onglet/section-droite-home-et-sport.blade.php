@section('section-droite')
<div class="my-3 pb-2 bg-white shadow-div">
    <div class="col-12 d-flex text-center p-2">
        <span data-cible="fil-actu-section-droite"
            class="d-block col-4 p-3 border btn btn-secondary onglet active">Fil actu</span>
        <span data-cible="resultats-section-droite"
            class="d-block col-4 p-3 border btn btn-secondary onglet">Résultats</span>
        <span data-cible="prochains-section-droite"
            class="d-block col-4 p-3 border btn btn-secondary onglet">À venir</span>
    </div>
    <div id="fil-actu-section-droite" class="fil-actu col-12 p-2">
        <?php $i=0; ?>
        @foreach ($filActualites as $actu)
            @if($i++ == 5)
                <div class="col-12 border-bottom m-auto py-2">
                    @include('pub.google-display-responsive')
                </div>
            @endif
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
    <div id="resultats-section-droite" class="col-12 p-2 d-none">
        @foreach ($resultats as $sport => $journees)
            <div class="col-12 text-center pt-1">
                <a class="nom-sport text-primary border-bottom border-primary" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
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
                <a class="nom-sport text-primary border-bottom border-primary" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
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
    
    {{-- PUB --}}
    <div class="col-12 m-auto py-3 px-2">
        @include('pub.google-display-responsive')
    </div>
</div>
@endsection