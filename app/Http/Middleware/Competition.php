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
            $journees = $derniereSaison->journees;
            $derniereJournee = $journees->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first();
            if($derniereJournee){
                $request->hrefCalendrier = route('competition.saison.calendrier-resultats', ['sport' => $sportSlug, 'competition' => $competitionSlugComplet, 'annee' => $derniereSaison->annee(), 'journee' => $derniereJournee->numero]);
                $resultats = infos('journees', $derniereJournee->id)->render_section_droite;
                $resultats_main = infos('journees', $derniereJournee->id)->render_main;
            }

            $prochaineJournee = $journees->where('date', '>=', date('Y-m-d'))->sortBy('date')->first();
            if($prochaineJournee){
                $prochains = infos('journees', $prochaineJournee->id)->render_section_droite;
                $prochains_main = infos('journees', $prochaineJournee->id)->render_main;
            }
        }

        $articles = $competition->articles()->where('valide', 1)->orderBy('created_at')->get();
        if(count($articles) > 0)
            $request->hrefActualite = route('competition.actualite', ['sport' => $sportSlug, 'competition' => $competitionSlugComplet]);

        foreach ($articles as $key => $article)
            $articles[$key] = infos('articles', $article->id);

        $request->articles = $articles;
        $request->resultats = $resultats ?? '';
        $request->prochains = $prochains ?? '';
        $request->resultats_main = $resultats_main ?? '';
        $request->prochains_main = $prochains_main ?? '';
        return $next($request);
    }
}
