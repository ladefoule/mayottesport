<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CookiesController extends Controller
{
    public function getCookies(Request $request) {
        $value = $request->cookie('name');
        echo $value;
     }
}
