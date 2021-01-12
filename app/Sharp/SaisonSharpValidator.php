<?php

use App\Saison;
use Code16\Sharp\Form\Validator\SharpFormRequest;

class SaisonSharpValidator extends SharpFormRequest
{
    public function rules()
    {
        return Saison::rules();
    }

}