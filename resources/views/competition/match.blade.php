@extends('layouts.competition')

@section('title', $match->title)

@section('content')
<div class="p-lg-3 h-100">
    <div class="row m-0 text-white bloc-match bloc-match-{{ $sport->slug }} py-4" style="background-image: url('{{ asset('storage/img/sport/'.$sport->slug.'.jpg') }}')">
        <div class="row mx-0 col-5 d-flex justify-content-between align-items-center bloc-equipe-dom p-1 mb-5">
            <div class="col-md-4 d-md-inline py-2 px-0">
                <a href="{{ $match->href_equipe_dom }}"><img src="{{ $match->fanion_equipe_dom }}" alt="{{ $match->equipe_dom->nom }}" class="fanion-match"></a>
            </div>
            <div class="equipe-domicile col-md-8 d-md-inline py-2 px-0">
                <a href="{{ $match->href_equipe_dom }}" class="text-white">{{ $match->equipe_dom->nom }}</a>
            </div>
        </div>
        <div class="col-2 bloc-score d-flex align-items-center justify-content-around p-0 mb-5">
            <span class="w-100 text-center">{!! $match->score !!}</span>
        </div>
        <div class="row mx-0 col-5 d-flex justify-content-between align-items-center bloc-equipe-ext p-1 mb-5">
            <div class="equipe-exterieur col-md-8 d-md-inline order-2 order-md-1 py-2 px-0">
                <a href="{{ $match->href_equipe_ext }}" class="text-white">{{ $match->equipe_ext->nom }}</a>
            </div>
            <div class="col-md-4 d-md-inline order-1 order-md-2 py-2 px-0">
                <a href="{{ $match->href_equipe_ext }}"><img src="{{ $match->fanion_equipe_ext }}" alt="{{ $match->equipe_ext->nom }}" class="fanion-match"></a>
            </div>
        </div>

        @if ($accesModifResultat)
            <div class="col-12 d-flex align-items-center justify-content-center p-3">
                <a href="{{ $match->href_resultat }}"><button class="btn btn-success px-3">{{ $match->score_eq_dom ? 'Modifier' : 'Saisir' }} le résultat</button></a>
            </div>
        @endif
        @if ($accesModifHoraire)
            <div class="col-12 text-center">
                <a href="{{ $match->href_horaire }}"><button class="btn btn-primary px-3">Modifier l'horaire</button></a>
            </div>
        @endif

        <div class="row col-12 d-flex justify-content-center align-items-center mx-0 p-3">
            @if($match->lieu)
                <div class="col-12 text-center">
                    {!! config('listes.boutons.position') !!} {{ $match->lieu }}
                </div>
            @endif
            @if($match->date_format)
                <div class="col-12 text-center">
                    {!! config('listes.boutons.calendrier') !!} {{ $match->date_format }} @if($match->heure) {!! config('listes.boutons.horloge') !!} {{ $match->heure }} @endif
                </div>
            @endif
            <div class="col-12 text-center">
                {{ $match->competition . ' : ' . $match->journee }}
            </div>
        </div>
    </div>

    <div class="row m-0 bg-white pt-2">
        <section class="col-12 pt-1">
            <div id="disqus_thread"></div>
            <script>
                /**
                *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
                /*
                var disqus_config = function () {
                this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                };
                */
                (function() { // DON'T EDIT BELOW THIS LINE
                var d = document, s = d.createElement('script');
                s.src = 'https://mayottesport-v2.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
                })();
            </script>
            <noscript>Veuillez activer JavaScript pour voir les commentaires alimentés par Disqus.</noscript>
        </section>

        <div class="col-12 m-auto py-3 px-2">
            @include('pub.google-display-responsive')
        </div>
    </div>
</div>
@endsection
