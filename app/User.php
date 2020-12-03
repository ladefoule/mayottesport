<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'pseudo', 'first_name', 'role_id', 'region_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Définition de l'attribut nom pour un objet de la class User
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return $this->pseudo;
    }

    /**
     * Les règles de validations
     *
     * @param User $user
     * @return array
     */
    public static function rules(User $user = null)
    {
        $unique = Rule::unique('users')->ignore($user);
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'pseudo' => ['nullable', 'string', 'min:3', 'max:50', $unique],
            'first_name' => ['nullable', 'string', 'min:3', 'max:50'],
            'role_id' => ['required', 'exists:roles,id'],
            'region_id' => ['required', 'exists:regions,id'],
            'email' => ['required', 'string', 'email', 'max:80', $unique],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        return ['rules' => $rules];
    }

    /**
     * Le role de l'utilisateur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }
}
