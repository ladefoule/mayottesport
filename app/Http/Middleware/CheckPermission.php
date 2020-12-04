<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        Log::info(" -------- Middleware CheckPermission -------- ");
        $permission = explode('|', $permission);
        if(checkPermission($permission)){
            return $next($request);
        }

        abort(404);
    }
}
