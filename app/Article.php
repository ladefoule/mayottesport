<?php

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['img', 'titre', 'texte', 'uniqid'];

    /**
     * Définition de l'affichage dans le CRUD
     *
     * @return string
     */
    public function getNomAttribute()
    {
        return "Article n° : " . $this->uniqid . ' - ' . $this->titre;
    }

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param Article $article
     * @return array
     */
    public static function rules(Article $article = null)
    {
        $uniqid = Rule::unique('articles')->ignore($article);

        $rules = [
            'texte' => 'required|min:30',
            'titre' => 'required|min:0|max:100',
            'img' => 'nullable|min:3|max:100',
            'uniqid' => ['required','string','max:50','min:3',$uniqid],
        ];
        // $messages = ['nom.unique' => "Ce nom de barème, associé à ce sport, existe déjà."];
        return ['rules' => $rules/* , 'messages' => $messages */];
    }
}
