<?php

namespace App\Sharp\Policies;

use App\User;

class MatchHandPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function entity(User $user)
    {
        return sharp_user()->role->niveau >= 30;
    }
}
