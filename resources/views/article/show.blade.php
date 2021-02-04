@extends($article->sport_id ? 'layouts.sport' : 'layouts.site')

@section('title', $article->titre)

@section('content')
<div class="p-lg-3 h-100">
    <div class="row m-0 bg-white h-100 shadow-div">
        <div class="col-12 p-0 d-flex flex-wrap">
            <h1 class="col-12 titre-page-article py-3">
                {{-- <span class="categorie">{{ $article->categorie }}</span> --}}
                {{ $article->titre }}
            </h1>
            <div class="col-12 d-flex m-auto justify-content-center">
                <img src="{{ $article->img }}" alt="{{ $article->titre }}" title="{{ $article->titre }}" class="img-fluid">
            </div>

            <!-- Create the editor container -->
            <div class="col-12 pt-3 article">
                <span class="font-weight-bold">
                    {!! $article->preambule !!}
                </span>
                {!! $article->article !!}
            </div>
            <span class="col-12 px-3 text-secondary">
                publié le {{ $article->publie_le }}
            </span>
            <span class="col-12 px-3 text-secondary">
                modifié le {{ $article->modifie_le }}
            </span>

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

        {{-- PUB --}}
        <div class="col-12 m-auto p-3">
            @include('pub.google-display-responsive')
        </div>
    </div>
</div>
@endsection

@section('section-droite')
<div class="my-3 bg-white shadow-div">
    <!-- ONGLETS -->
    <div class="col-12 d-flex text-center p-3">
        <span data-cible="bloc-fil-actu"
            class="d-block col-4 p-3 border btn btn-secondary onglet active">Fil actu</span>
        <span data-cible="bloc-resultats"
            class="d-block col-4 p-3 border btn btn-secondary onglet">Résultats</span>
        <span data-cible="bloc-prochains"
            class="d-block col-4 p-3 border btn btn-secondary onglet">À venir</span>
    </div>

    <!-- FIL ACTU -->
    <div id="bloc-fil-actu" class="col-12 p-2">
        {!! $filActualites !!}
    </div>

    <!-- RESULTATS -->
    <div id="bloc-resultats" class="col-12 p-2 d-none">
        @foreach ($resultats as $sport => $journees)
            <div class="col-12 text-center pt-1">
                <a class="nom-sport text-secondary" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                    {{ $sport }}
                </a>
            </div>
            @foreach ($journees as $journee)
                <div class="col-12 text-center pb-3 justify-content-between">
                    <h3 class="col-12 h4 py-2">
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

    <!-- A VENIR -->
    <div id="bloc-prochains" class="col-12 p-2 d-none">
        @foreach ($prochains as $sport => $journees)
            <div class="col-12 text-center pt-1">
                <a class="nom-sport text-secondary" href="{{ route('sport.index', ['sport' => \Str::slug($sport)]) }}">
                    {{ $sport }}
                </a>
            </div>
            @foreach ($journees as $journee)
                <div class="col-12 text-center pb-3 justify-content-between">
                    <h3 class="col-12 h4 py-2">
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

    <!-- PUB -->
    <div class="col-12 pb-3">
        @include('pub.google-display-fixe-vertical')
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Gestion des onglets du bloc de droite
            cibles = qsa('#bloc-prochains,#bloc-resultats,#bloc-fil-actu')
            onglets = qsa('#section-droite .onglet') 
            ongletSwitch(cibles, onglets)
        })
    </script>
@endsection