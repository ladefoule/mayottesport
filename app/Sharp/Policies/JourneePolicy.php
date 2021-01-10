<?php

namespace App\Sharp\Policies;

use App\User;

class JourneePolicy
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

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return sharp_user()->hasRole('view journees') || sharp_user()->role->name == 'superadmin';
    }

    /**
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return sharp_user()->hasRole('update journees') || sharp_user()->role->name == 'superadmin';
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return sharp_user()->hasRole('delete journees') || sharp_user()->role->name == 'superadmin';
    }
}
