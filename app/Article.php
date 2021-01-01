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
    protected $fillable = ['img', 'titre', 'texte', 'preambule', 'uniqid', 'valide'];

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
        request()['valide'] = request()->has('valide');

        $rules = [
            'texte' => 'nullable|min:30',
            'preambule' => 'required|min:30',
            'titre' => 'required|min:0|max:100',
            'img' => 'nullable|min:3|max:100',
            'uniqid' => ['required','string','size:13',$uniqid],
            'valide' => 'boolean'
        ];
        // $messages = ['nom.unique' => "Ce nom de barème, associé à ce sport, existe déjà."];
        return ['rules' => $rules/* , 'messages' => $messages */];
    }
}
