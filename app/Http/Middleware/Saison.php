<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class Saison
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
        Log::info(" -------- Middleware Saison -------- ");
        $sport = $request->sport;
        $competition = $request->competition;
        $derniereSaison = $request->derniereSaison;

        foreach ($competition->saisons as $saison)
            if($saison->annee() == $request->annee){
                // $journees = $saison->journees;
                $journees = index('journees')->where('saison_id', $saison->id);
                if(count($journees) > 0){
                    $derniereJournee = $journees->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first();
                    $request->hrefCalendrier = route('competition.saison.calendrier-resultats', ['sport' => $sport->slug, 'competition' => $competition->slug_complet, 'annee' => $saison->annee(), 'journee' => $derniereJournee->numero]);
                    
                    // Type Championnat et saison différente de la dernière
                    if($competition->type == 1 && $saison->id != $derniereSaison->id)
                        $request->hrefClassement = route('competition.saison.classement', ['sport' => $sport->slug, 'competition' => $competition->slug_complet, 'annee' => $saison->annee()]);
                }else {
                    $request->hrefCalendrier = '';
                    $request->hrefClassement = '';
                }

                $request->saison = $saison;
                return $next($request);
            }
        
        abort(404);
    }
}
