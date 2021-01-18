<?php

namespace App\Http\Controllers;

use App\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SuperadminController extends Controller
{
    /**
     * Suppression de tout le cache
     *
     * @return void
     */
    public function cacheFlush(Request $request)
    {
        if(Auth::user()->role->niveau < 40)
            abort(404);

        Cache::flush();
        Log::info('Suppression de tout le cache !');
    }
}
