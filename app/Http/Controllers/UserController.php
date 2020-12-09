<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Tableau de bord (profil) de l'utilisateur
     *
     */
    public function profil()
    {
        Log::info(" -------- Controller User : profil -------- ");
        return view('profil');
    }
}
