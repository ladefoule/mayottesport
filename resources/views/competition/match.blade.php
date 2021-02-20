@extends('layouts.competition')

@section('title', $match->title)

@section('content')
<div class="p-lg-3 h-100">
    <div class="row m-0 text-white bloc-match bloc-match-{{ $sport->slug }} py-4" style="background-image: url('{{ asset('storage/img/sport/'.$sport->slug.'.webp') }}')">
        <div class="col-12 p-0 d-flex align-items-center mb-5">
            <div class="row m-auto w-40 d-flex justify-content-between align-items-center bloc-equipe-dom p-1 mb-5">
                <div class="col-md-4 col-lg-12 col-xl-4 py-2 px-0 d-flex justify-content-center align-items-center">
                    <a href="{{ $match->href_equipe_dom }}">
                        <div class="d-flex justify-content-center p-0 fanion-match">
                            <img src="{{ $match->fanion_equipe_dom }}" alt="{{ $match->equipe_dom->nom }}" class="img-fluid">
                        </div>
                    </a>
                </div>
                <div class="equipe-domicile col-md-8 col-lg-12 col-xl-8 py-2 px-0">
                    <a href="{{ $match->href_equipe_dom }}" class="text-white">{{ $match->equipe_dom->nom }}</a>
                </div>
            </div>
            <div class="w-20 bloc-score d-flex flex-wrap align-items-center m-auto p-0 mb-5">
                <span class="col-12 text-center p-0">{!! $match->score !!}</span>
                @if (isset($match->avec_tirs_au_but))
                    <span class="col-12 p-0 text-center tirs-au-but">tab. {{ $match->tab_eq_dom . '-' . $match->tab_eq_ext }}</span>
                @endif
            </div>
            <div class="row m-auto w-40 d-flex justify-content-between align-items-center bloc-equipe-ext p-1 mb-5">
                <div class="equipe-exterieur col-md-8 col-lg-12 col-xl-8 order-2 order-md-1 order-lg-2 order-xl-1 py-2 px-0">
                    <a href="{{ $match->href_equipe_ext }}" class="text-white">{{ $match->equipe_ext->nom }}</a>
                </div>
                <div class="col-md-4 col-lg-12 col-xl-4 order-1 order-md-2 order-lg-1 order-xl-2 py-2 px-0 d-flex justify-content-center align-items-center">
                    <a href="{{ $match->href_equipe_ext }}">
                        <div class="d-flex justify-content-center p-0 fanion-match">
                            <img src="{{ $match->fanion_equipe_ext }}" alt="{{ $match->equipe_ext->nom }}" class="img-fluid">
                        </div>
                    </a>
                </div>
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

        <div class="infos-match row col-12 d-flex justify-content-center align-items-center mx-0 p-3 mt-5">
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
                {{ $match->competition . ' ' . $match->annee . ' - ' }} <a class="text-primary" href="{{ $journee->href }}">{{ $journee->nom }}</a>
            </div>
        </div>
    </div>

    <div class="row m-0 bg-white pt-2">
        <section class="col-12 pt-1">
            <div id="disqus_thread"></div>
            <script defer>
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
