<?php

use Code16\Sharp\Auth\SharpAuthenticationCheckHandler;

class SharpCheckHandler implements SharpAuthenticationCheckHandler
{
    /**
     * @param $user
     * @return bool
     */
    public function check($user): bool
    {
        return $user->role->niveau > 10;//hasGroup('sharp');
    }
}
