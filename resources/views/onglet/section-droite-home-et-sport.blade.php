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
                    <p class="col-12 nom-competition border-bottom-calendrier pt-2">
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
                    <p class="col-12 nom-competition border-bottom-calendrier pt-2">
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
    {{-- <iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-eu.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=FR&source=ac&ref=tf_til&ad_type=product_link&tracking_id=ladefoule-21&marketplace=amazon&amp;region=FR&placement=B085PSMY87&asins=B085PSMY87&linkId=a7ba40e7c566b770ff9f0f16723473f3&show_border=true&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
    </iframe> --}}
</div>
@endsection