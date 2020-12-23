@extends('layouts.competition')

@section('title', $match['title'])

@section('content')
<div class="row text-white bloc-match bloc-match-football py-4 rounded mx-0">
    <div class="row mx-0 col-5 d-flex justify-content-between align-items-center bloc-equipe-dom p-1">
        <div class="col-lg-4 d-lg-inline py-2 px-0">
            <a href="{{ $match['href_equipe_dom'] }}"><img src="{{ $match['fanion_equipe_dom'] }}" alt="{{ $match['equipe_dom_nom'] }}" class="fanion-match"></a>
        </div>
        <div class="equipe-domicile col-lg-8 d-lg-inline py-2 px-0">
            <a href="{{ $match['href_equipe_dom'] }}" class="text-white">{{ $match['equipe_dom_nom'] }}</a>
        </div>
    </div>
    <div class="col-2 bloc-score d-flex align-items-center justify-content-around p-0">
        <span class="w-100 text-center font-weight-bold">{!! $match['score'] !!}</span>
    </div>
    <div class="row mx-0 col-5 d-flex justify-content-between align-items-center bloc-equipe-ext p-1">
        <div class="equipe-exterieur col-lg-8 d-lg-inline order-2 order-lg-1 py-2 px-0">
            <a href="{{ $match['href_equipe_ext'] }}" class="text-white">{{ $match['equipe_ext_nom'] }}</a>
        </div>
        <div class="col-lg-4 d-lg-inline order-1 order-lg-2 py-2 px-0">
            <a href="{{ $match['href_equipe_ext'] }}"><img src="{{ $match['fanion_equipe_ext'] }}" alt="{{ $match['equipe_ext_nom'] }}" class="fanion-match"></a>
        </div>
    </div>

    @if (! $match['acces_bloque'])
        {{-- Accès à la saisie de résultat que pour les matches déjà joués (aujourd'hui compris) --}}
        @if ($match['date'] <= date('Y-m-d'))
            <div class="col-12 d-flex align-items-center justify-content-center p-3">
                <a href="{{ $match['href_resultat'] }}"><button class="btn btn-success">{{ $match['score_eq_dom'] ? 'Modifier' : 'Saisir' }} le résultat</button></a>
            </div>
        @endif

        {{-- Accès à la modification de l'horaire qu'aux membres premium/admin/superadmin --}}
        @if (Auth::check() && Auth::user()->role->niveau > 10)
            <div class="col-12 text-center">
                <a href="{{ $match['href_horaire'] }}"><button class="btn btn-primary">Modifier l'horaire</button></a>
            </div>
        @endif
    @endif

    <div class="row col-12 d-flex justify-content-center align-items-center mx-0 p-3">
        <div class="col-12 text-center">
            Le {{ $match['date_format'] }}
        </div>
        <div class="col-12 text-center">
            {{ $match['competition'] . ' : ' . $match['journee'] }}
        </div>
    </div>
</div>

<div class="row bg-white border mt-2 mx-0 rounded">
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
        <noscript>Veuillez activer JavaScript pour voir les <a href="https://disqus.com/?ref_noscript">commentaires alimentés par Disqus.</a></noscript>
    </section>
</div>
@endsection
