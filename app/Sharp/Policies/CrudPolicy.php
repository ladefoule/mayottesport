<?php

namespace App\Sharp\Policies;

use App\User;

class CrudPolicy
{

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return sharp_user()->hasRole('admin');
    }
}
