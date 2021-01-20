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
        $unique = Rule::unique('users')->ignore($user);
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'pseudo' => ['nullable', 'string', 'min:3', 'max:50', $unique],
            'first_name' => ['nullable', 'string', 'min:3', 'max:50'],
            'region_id' => ['required', 'exists:regions,id'],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $data = Validator::make($request->all(), $rules)->validate();
        
        if(isset($data['avatar'])){
            $imageName = 'user-' . $user->id;  
            $request->avatar->move(storage_path('app/public/upload/avatar'), $imageName);
            
            $data['avatar'] = $imageName;
        }

        $user->update($data);

        forgetCaches('users', $user);
        ProcessCrudTable::dispatch('users', $user->id);
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
        ProcessCrudTable::dispatch('users');
        return redirect(route('home'));
    }
}
