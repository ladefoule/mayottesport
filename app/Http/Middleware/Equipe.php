<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
// use App\Equipe as EquipeModel;
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
        $rules = [
            'equipe' => 'alpha_dash|min:3',
            'uniqid' => 'alpha_dash|size:13'
        ];

        $validator = Validator::make([
            'equipe' => $request->equipe,
            'uniqid' => $request->uniqid
        ], $rules);

        if ($validator->fails())
            abort(404);

        // $equipe = EquipeModel::whereUniqid($request->uniqid)->firstOrFail();
        $equipe = index('equipes')->firstWhere('uniqid', $request->uniqid);
        if (! $equipe || Str::slug($equipe->nom) != $request->equipe)
            abort(404);

        $request->equipe = $equipe;
        return $next($request);
    }
}
