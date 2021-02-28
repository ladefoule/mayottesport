@extends($article->sport_id ? 'layouts.sport' : 'layouts.site')

@section('title', $article->titre)

@section('head')
	<meta name="description" content="{{ $article->preambule }}">
@endsection

@section('content')
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v9.0" nonce="4xNA2MjG"></script>

<div class="p-lg-3 h-100">
    <div class="row m-0 bg-white h-100 shadow-div">
        <div class="col-12 p-0 d-flex flex-wrap">
            <h1 class="col-12 titre-page-article py-3">
                {{ $article->titre }}
            </h1>
            <div class="col-12 px-0 px-lg-3 text-center">
                <img src="{{ $article->img }}" alt="{{ $article->img_description ?? $article->titre }}" title="{{ $article->img_description ?? $article->titre }}" class="img-fluid">
            </div>
            <div class="col-12 text-secondary pt-2">{{ $article->img_description }}</div>

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

            {{-- Réseaux sociaux --}}
            <div class="col-12 p-0 mt-2 px-3 d-flex">
                <div class="col-12 border-bottom border-top d-flex px-0 flex-wrap py-2">
                    {{-- Twitter --}}
                    <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large" data-via="mayottesport" data-hashtags="mayottesport" data-lang="fr" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                    
                    {{-- Facebook --}}
                    <div class="ml-3 fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button" data-size="large">
                        <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Partager</a>
                    </div>
                </div>
            </div>

            @if($articleSuivant || $articlePrecedent)
                <div class="col-12 p-0 d-flex mt-2">
                    <div class="col-6 d-flex justify-content-start flex-wrap h-100 align-items-start pr-2 border-right">
                        @if($articlePrecedent)
                            <div class="col-12 p-0 d-flex flex-wrap">
                                <span class="col-12 p-0 font-weight-bold">{!! config('listes.boutons.left') !!} Précédent</span>
                                <a class="col-12 p-0 mb-auto" href="{{ $articlePrecedent->href }}">{{ $articlePrecedent->titre }}</a>
                            </div>
                        @endif
                    </div>
                    <div class="col-6 d-flex justify-content-end flex-wrap text-right align-items-start h-100 pl-2">
                        @if($articleSuivant)
                            <div class="col-12 p-0 d-flex flex-wrap">
                                <span class="col-12 p-0 font-weight-bold">Suivant {!! config('listes.boutons.right') !!}</span>
                                <a class="col-12 p-0 mb-auto" href="{{ $articleSuivant->href }}">{{ $articleSuivant->titre }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

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
            @include('pub.google-thematique-responsive')
        </div>
    </div>
</div>
@endsection

@section('section-droite')
<div class="my-3 bg-white shadow-div">
    <!-- ONGLETS -->
    <div class="col-12 d-flex text-center p-2">
        <span data-cible="bloc-fil-actu"
            class="d-block col-4 p-3 border btn btn-secondary onglet active">Fil actu</span>
        <span data-cible="bloc-resultats"
            class="d-block col-4 p-3 border btn btn-secondary onglet">Résultats</span>
        <span data-cible="bloc-prochains"
            class="d-block col-4 p-3 border btn btn-secondary onglet">À venir</span>
    </div>

    <!-- FIL ACTU -->
    <div id="bloc-fil-actu" class="fil-actu col-12 p-2">
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
                <div class="font-size-1-rem col-10 p-2">
                    <a href="{!! $actu->href_fil_actu !!}">
                        <div class="col-12 text-primary p-0">{{ $actu->categorie }}</div>
                        <div class="col-12 p-0 text-body">{!! $actu->preambule !!}</div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- RESULTATS -->
    <div id="bloc-resultats" class="col-12 p-2 d-none">
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

    <!-- A VENIR -->
    <div id="bloc-prochains" class="col-12 p-2 d-none">
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