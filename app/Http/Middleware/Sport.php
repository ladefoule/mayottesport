<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use App\Sport as SportModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Sport
{
    /**
     * On contrôle que le nom de sport renseigné est bien présent dans la base de données.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info(" -------- Middleware Sport -------- ");
        if (Validator::make(['sport' => $request->sport], ['sport' => 'alpha_dash|min:3'])->fails())
            abort(404);

        $sport = SportModel::where('slug', $request->sport)->firstOrFail();
        $request->sport = $sport;
        return $next($request);
    }
}
