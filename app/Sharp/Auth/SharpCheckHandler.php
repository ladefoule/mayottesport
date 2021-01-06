<?php

namespace App\Sharp\Auth;
use Code16\Sharp\Auth\SharpAuthenticationCheckHandler;

class SharpCheckHandler implements SharpAuthenticationCheckHandler
{
    /**
     * @param $user
     * @return bool
     */
    public function check($user): bool
    {
        // L'email doit être vérifié pour accéder au panel Sharp
        // Et le role de l'utilisateur doit etre admin ou superadmin
        return in_array($user->role->name, ['admin', 'superadmin']) && $user->email_verified_at != null;
    }
}
