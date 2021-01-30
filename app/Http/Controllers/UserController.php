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
use App\Rules\MatchOldPassword;
use Illuminate\Validation\Rule;
use App\Jobs\ProcessCacheReload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("Accès au controller User - Ip : " . request()->ip());
    }

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
     * Mise à jour d'un utilisateur
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
     * Mise à jour d'un utilisateur (Post)
     *
     */
    public function updatePost(Request $request)
    {
        Log::info(" -------- Controller User : updatePost -------- ");
        $user = User::findOrFail(Auth::id());

        $rules = User::rules($user)['rules'];
        $rules['avatar'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        unset($rules['password'], $rules['email'], $rules['role_id']);

        $data = Validator::make($request->all(), $rules)->validate();
        
        // Si l'utilisateur souhaite changer son avatar
        if(isset($data['avatar'])){
            $imageName = 'user-' . $user->id . '-' . time() . '.' . $request->avatar->extension();  // Ex : user-1-1589553652.jpg
            $request->avatar->move(storage_path('app/public/upload/avatar'), $imageName);
            
            $data['avatar'] = $imageName;
        }

        $user->update($data);

        forgetCaches('users', $user);
        ProcessCacheReload::dispatch('users', $user->id);
        return redirect()->route('profil');
    }

    /**
     * Mise à jour du mot de passe d'un utilisateur
     *
     */
    public function updatePasswordForm()
    {
        Log::info(" -------- Controller User : updatePasswordForm -------- ");
        return view('profil.update-password');
    }

    /**
     * Mise à jour du mpd d'un utilisateur (Post)
     *
     */
    public function updatePasswordPost(Request $request)
    {
        Log::info(" -------- Controller User : updatePasswordPost -------- ");
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'min:8', 'confirmed']
        ]);

        $user->update(['password'=> Hash::make($request->new_password)]);

        forgetCaches('users', $user);
        ProcessCacheReload::dispatch('users', $user->id);
        return redirect()->route('profil');
    }

    /**
     * Suppression d'un utilisateur
     *
     */
    public function delete()
    {
        Log::info(" -------- Controller User : delete -------- ");
        $user = User::findOrFail(Auth::id());
        forgetCaches('users', $user);
        Auth::logout();
        $user->delete();
        ProcessCacheReload::dispatch('users');
        return redirect(route('home'));
    }
}
