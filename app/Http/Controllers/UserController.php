<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\User;
use App\Cache;
use Illuminate\Http\Request;
use App\Jobs\ProcessCrudTable;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Tableau de bord (profil) de l'utilisateur
     *
     */
    public function profil()
    {
        Log::info(" -------- Controller User : profil -------- ");
        return view('profil.index', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Tableau de bord (profil) de l'utilisateur
     *
     */
    public function updateForm()
    {
        Log::info(" -------- Controller User : updateForm -------- ");
        return view('profil.update', [
            'user' => auth()->user(),
            'regions' => index('regions')
        ]);
    }

    /**
     * Tableau de bord (profil) de l'utilisateur
     *
     */
    public function updatePost(Request $request)
    {
        Log::info(" -------- Controller User : updatePost -------- ");
        $user = User::findOrFail(Auth::id());
        $unique = Rule::unique('users')->ignore($user);
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'pseudo' => ['nullable', 'string', 'min:3', 'max:50', $unique],
            'first_name' => ['nullable', 'string', 'min:3', 'max:50'],
            'region_id' => ['required', 'exists:regions,id'],
        ];

        $request = Validator::make($request->all(), $rules)->validate();
        $user->update($request);
        $this::refreshCaches($user->id);
        return redirect()->route('profil');
    }

    /**
     * Tableau de bord (profil) de l'utilisateur
     *
     */
    public function delete()
    {
        Log::info(" -------- Controller User : delete -------- ");
        $user = User::findOrFail(Auth::id());
        $this::refreshCaches($user->id);
        $user->delete();
    }

    /**
     * Rechargement des caches
     *
     * @param int $id
     * @return void
     */
    private static function refreshCaches(int $id)
    {
        Log::info(" -------- Controller Crud : refreshCaches -------- ");

        // On supprime les caches index directement liés à la table
        Cache::forget('index-users');
        Cache::forget('indexcrud-users');

        // On recharge tous les caches dépendants en Asynchrone (Laravel Queues)
        ProcessCrudTable::dispatch('users', $id);
    }
}
