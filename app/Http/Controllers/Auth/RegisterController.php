<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Cache;
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
        //
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        Log::info("Validation des données du nouvel utilisateur.");
        $rules = User::rules()['rules'];
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
        Log::info("Création d'un nouvel utilisateur.");
        Log::info("L'email : "  . $data['email']);
        // On génère automatiquement un pseudo à partir de l'email
        $pseudo = explode('@', $data['email'])[0] . rand(0, 1000);

        Cache::forget('index-users');
        Cache::forget('indexcrud-users');
        return User::create([
            'name' => $data['name'],
            'first_name' => $data['first_name'],
            'email' => $data['email'],
            'pseudo' => $pseudo,
            'region_id' => $data['region_id'],
            'role_id' => $data['role_id'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
