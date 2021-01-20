<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Jobs\ProcessCacheReload;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("Accès au controller Register - Ip : " . request()->ip());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        Log::info(" -------- Controller Register : validator -------- ");
        $rules = User::rules()['rules'];
        $rules['captcha'] = 'required|captcha';

        // Les nouveaux utilisateurs auront le niveau d'accès 'membre'
        $data['role_id'] = index('roles')->firstWhere('name', 'membre')->id;

        // On génère automatiquement un pseudo à partir de l'email
        try {
            $data['pseudo'] = explode('@', $data['email'])[0] . rand(0, 1000);
        } catch (\Throwable $th) {
            abort(404);
        }
        request()['role_id'] = $data['role_id'];
        request()['pseudo'] = $data['pseudo'];

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        Log::info(" -------- Controller Register : create -------- ");
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        Log::info("Nouveau membre créé avec succès : " . $user->email);

        // On recharge tous les caches dépendants en Asynchrone (Laravel Queues)
        forgetCaches('users', $user);
        ProcessCacheReload::dispatch('users', $user->id);

        return $user;
    }
}
