<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use App\Equipe as EquipeModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Equipe
{
    /**
     * Vérification du nom de l'équipe et de son uniqid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info(" -------- Middleware Equipe -------- ");
        $equipe = EquipeModel::whereSlugComplet($request->equipe)->firstOrFail();

        $request->equipe = $equipe;
        return $next($request);
    }
}
