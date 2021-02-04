<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
    </url>

    <url>
        <loc>{{ route('contact') }}</loc>
    </url>

    <url>
        <loc>{{ route('politique') }}</loc>
    </url>

    {{-- LES ARTICLES --}}
    @foreach (App\Article::all() as $article)
        @if(! $article->fil_actu)
            @if($article->sport)
                <url>
                    <loc>{{ route('article.sport.show', ['sport' => $article->sport->slug, 'titre' => $article->slug, 'uniqid' => $article->uniqid]) }}</loc>
                </url>
            @else
                <url>
                    <loc>{{ route('article.show', ['titre' => $article->slug, 'uniqid' => $article->uniqid]) }}</loc>
                </url>
            @endif
        @endif
    @endforeach

    {{-- LES SPORTS --}}
    @foreach (App\Sport::all() as $sport)
        <url>
            <loc>{{ route('sport.index', ['sport' => $sport->slug]) }}</loc>
        </url>

        {{-- LES EQUIPES --}}
        @foreach ($sport->equipes as $equipe)
            <url>
                <loc>{{ route('equipe.index', ['sport' => $sport->slug, 'equipe' => $equipe->slug_complet]) }}</loc>
            </url>
        @endforeach

        {{-- LES COMPETITIONS --}}
        @foreach ($sport->competitions as $competition)
            {{-- LA PAGE INDEX --}}
            <url>
                <loc>{{ route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug_complet]) }}</loc>
            </url>

            @if($competition->type == 1)
                {{-- LA PAGE CLASSEMENT --}}
                <url>
                    <loc>{{ route('competition.classement', ['sport' => $sport->slug, 'competition' => $competition->slug_complet]) }}</loc>
                </url>
            @endif

            {{-- LE PALMARES --}}
            <url>
                <loc>{{ route('competition.palmares', ['sport' => $sport->slug, 'competition' => $competition->slug_complet]) }}</loc>
            </url>

            <?php 
                // LES SAISONS
                $saisons = App\Saison::where('competition_id', $competition->id);
                foreach ($saisons as $saison) {
                    if(count($saison->journees) > 0){
                        // LE CLASSEMENT DE LA SAISON
                        echo '<url>';
                            echo '<loc>' . route('competition.saison.classement', ['sport' => $sport->slug, 'competition' => $competition->slug_complet, 'annee' => $saison->annee()]) . '</loc>';
                        echo '</url>';
                        
                        // CALENDRIER/RESULTATS DE LA SAISON
                        foreach($saison->journees as $journee){
                            echo '<url>';
                                echo '<loc>' . route('competition.annee.calendrier-resultats', ['sport' => $sport->slug, 'competition' => $competition->slug_complet, 'annee' => $saison->annee(), 'journee' => $journee->numero]) . '</loc>';
                            echo '</url>';
                        }

                        // LES MATCHES DE LA SAISON
                        foreach($saison->matches as $match){
                            $equipeDom = $match->equipeDom;
                            $equipeExt = $match->equipeExt;
                            echo '<url>';
                                echo '<loc>';
                                    echo route('competition.match', [
                                        'sport' => $sport->slug, 'competition' => $competition->slug_complet,
                                        'annee' => $saison->annee(), 'equipeDom' => $equipeDom->slug, 'equipeExt' => $equipeExt->slug,
                                        'uniqid' => $match->uniqid
                                    ]);
                                echo '</loc>';
                            echo '</url>';
                        }
                    }
                }
            ?>
        @endforeach
    @endforeach
</urlset>