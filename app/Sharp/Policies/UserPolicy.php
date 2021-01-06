<?php

namespace App\Sharp\Policies;

use App\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user, $id)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param int $id
     * @return mixed
     */
    public function view(User $user, $id)
    {
        return sharp_user()->id == $id || sharp_user()->role->name == 'superadmin';
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param int $id
     * @return mixed
     */
    public function update(User $user, $id)
    {
        return sharp_user()->id == $id || sharp_user()->role->name == 'superadmin';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param int $id
     * @return mixed
     */
    public function delete(User $user, $id)
    {
        return sharp_user()->id == $id || sharp_user()->role->name == 'superadmin';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  @param int $id
     * @return mixed
     */
    public function restore(User $user, $id)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  @param int $id
     * @return mixed
     */
    public function forceDelete(User $user, $id)
    {
        //
    }
}
