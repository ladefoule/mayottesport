<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['user_id', 'date', 'heure', 'lieu'];

    /**
     * Les règles de validations
     *
     * @param Presentation $presentation
     * @return array
     */
    public static function rules(Presentation $presentation = null)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'date' => 'nullable|date|max:50|min:3',
            'heure' => 'nullable|max:50|min:3',
            'lieu' => 'nullable|max:50|min:3'
        ];
        return ['rules' => $rules];
    }
}
