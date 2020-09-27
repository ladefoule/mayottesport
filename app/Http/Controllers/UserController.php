<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Tableau de bord (profil) de l'utilisateur
     *
     */
    public function profil()
    {
        return view('profil');
    }
}
