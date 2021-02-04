<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use App\Saison;
use Illuminate\Support\Facades\Log;
use App\Competition as CompetitionModel;
use Illuminate\Support\Facades\Validator;

class Competition
{
    /**
     * On vérifie si le nom de compétition saisi est bien dans la base.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info(" -------- Middleware Competition -------- ");
        $sport = $request->sport;
        $competition = CompetitionModel::whereSportId($sport->id)->whereSlugComplet($request->competition)->firstOrFail();
        $request->competition = $competition;

        $competitionSlugComplet = $competition->slug_complet;
        $sportSlug = $sport->slug;

        // $saison = Saison::where('competition_id', $competition->id)/* ->where('finie', '!=', 1) */->orderBy('finie')->orderBy('annee_debut')->first();
        // Recherche de la dernière saison de la compétition
        $derniereSaison = $competition->saisons()->orderBy('annee_debut', 'desc')->first();

        // Les infos requises pour toutes les pages du middleware
        $request->derniereSaison = $derniereSaison;
        $request->hrefSport = route('sport.index', ['sport' => $sportSlug]);
        $request->hrefIndex = route('competition.index', ['sport' => $sportSlug, 'competition' => $competitionSlugComplet]);
        $request->hrefPalmares = route('competition.palmares', ['sport' => $sportSlug, 'competition' => $competitionSlugComplet]);

        if($competition->type == 1) // Type Championnat
            $request->hrefClassement = route('competition.classement', ['sport' => $sportSlug, 'competition' => $competitionSlugComplet]);
        
        if($derniereSaison){
            $derniereJourneeId = $derniereSaison->derniereJourneeId();
            if($derniereJourneeId){
                $request->hrefCalendrier = route('competition.saison.calendrier-resultats', ['sport' => $sportSlug, 'competition' => $competitionSlugComplet, 'annee' => $derniereSaison->annee(), 'journee' => $derniereJournee->numero]);
                $resultats = journee($derniereJourneeId)->render_section_droite;
                $resultats_main = journee($derniereJourneeId)->render_main;
            }

            $prochaineJourneeId = $derniereSaison->prochaineJourneeId();
            if($prochaineJourneeId){
                $prochains = journee($prochaineJourneeId)->render_section_droite;
                $prochains_main = journee($prochaineJourneeId)->render_main;
            }
        }

        $articles = $competition->articles()->where('valide', 1)->orderBy('created_at')->get();
        if(count($articles) > 0)
            $request->hrefActualite = route('competition.actualite', ['sport' => $sportSlug, 'competition' => $competitionSlugComplet]);

        foreach ($articles as $key => $article)
            $articles[$key] = article($article->uniqid);

        $request->articles = $articles;
        $request->resultats = $resultats ?? '';
        $request->prochains = $prochains ?? '';
        $request->resultats_main = $resultats_main ?? '';
        $request->prochains_main = $prochains_main ?? '';
        return $next($request);
    }
}
