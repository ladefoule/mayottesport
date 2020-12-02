<?php

namespace App\Http\Middleware;

use Closure;
use App\Sport as SportModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Sport
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info(" ---- Middleware Sport ---- ");
        if (Validator::make(['sport' => $request->sport], ['sport' => 'alpha_dash|min:3'])->fails())
            abort(404);

        // On remplace la chaine de caractère par la collection
        // $sport = SportModel::where('nom', 'like', $request->sport)->firstOrFail();
        // $sports = index('sports');//->whereStrict('nom', $request->sport)->first();

        $find = false;
        foreach(index('sports') as $sport)
            if(strToUrl($sport->nom) == ($request->sport)){
                $request->sport = $sport;
                $find = true;
                break;
            }

        if(! $find)
            abort(404);

        // $sports->each(function ($sport, $key) use($sportKebabCase, $request) {
        //     // strcasecmp(strToUrl($sport->nom), $request->sport) == 0
        //     if (strToUrl($sport->nom) == $sportKebabCase) {
        //         $request->sport = $sport;
        //         return false;
        //     }
        // });

        return $next($request);
    }
}
