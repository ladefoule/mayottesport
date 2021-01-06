<?php

namespace App\Sharp\Policies;

use App\User;

class ArticlePolicy
{

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return sharp_user()->hasRole('admin') || sharp_user()->hasRole('superadmin');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return sharp_user()->hasRole('superadmin');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return sharp_user()->hasRole('superadmin');
    }
}
