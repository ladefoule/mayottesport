@extends($article->sport_id ? 'layouts.sport' : 'layouts.site')

@section('title', $article->titre)

@section('content')
<div class="p-lg-3">
    <div class="row m-0 bg-white">
        <div class="col-12 p-0">
            <h1 class="col-12 titre-page-article py-3">
                <span class="categorie">{{ $article->categorie }}</span>
                {{ $article->titre }}
            </h1>
            <div class="col-12 d-flex m-auto justify-content-center">
                <img src="{{ $article->img }}" alt="{{ $article->titre }}" title="{{ $article->titre }}" class="img-fluid">
            </div>

            <!-- Create the editor container -->
            <div class="col-12 pt-3">
            <span class="font-weight-bold">
                {!! $article->preambule !!}
            </span>
                {!! $article->article !!}
            </div>

            <div class="col-12 bg-white pt-2">
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
            </div>
        </div>
    </div>
</div>
@endsection

@section('section-droite')
<div class="my-3 bg-white" {{-- style="background-color:#ebeff3" --}}>
    <div class="col-12 d-flex text-center p-3">
        <a href="" data-cible="fil-actu"
            class="d-block col-4 p-3 border btn btn-secondary onglet active">Fil actu</a>
        <a href="" data-cible="resultats"
            class="d-block col-4 p-3 border btn btn-secondary onglet">Résultats</a>
        <a href="" data-cible="prochains"
            class="d-block col-4 p-3 border btn btn-secondary onglet">À venir</a>
    </div>
    <div class="bloc-fil-actu col-12 p-2">
        {!! $filActualites !!}
    </div>
    <div class="bloc-resultats col-12 p-2 d-none">
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
    <div class="bloc-prochains col-12 p-2 d-none">
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

@section('script')
    <script>
        $(document).ready(function() {
            // Gestion des onglets dans le main
            var cibles = qsa('main .bloc-prochains,main .bloc-resultats,main .bloc-actualites')
            var onglets = qsa('main .onglet') 
            ongletSwitch(cibles, onglets)

            // Gestion des onglets du bloc de droite
            cibles = qsa('#section-droite .bloc-prochains,#section-droite .bloc-resultats,#section-droite .bloc-fil-actu')
            onglets = qsa('#section-droite .onglet') 
            ongletSwitch(cibles, onglets)
        })
    </script>
@endsection