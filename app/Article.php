<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['img', 'titre', 'texte', 'preambule', 'uniqid', 'valide', 'sport_id'];

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
            'sport_id' => 'nullable|integer|exists:sports,id',
            'img' => 'nullable|min:3|max:100',
            'uniqid' => ['required','string','size:13',$uniqid],
            'valide' => 'boolean'
        ];
        // $messages = ['nom.unique' => "Ce nom de barème, associé à ce sport, existe déjà."];
        return ['rules' => $rules/* , 'messages' => $messages */];
    }

    /**
     * Les informations du match dont ont besoin les views match/resultat et horaire
     *
     * @return \Illuminate\Support\Collection
     */
    public function infos()
    {
        $key = 'article-'.$this->uniqid;
        if (Cache::has($key))
            return Cache::get($key);

        return Cache::rememberForever($key, function () use($key){
            Log::info('Rechargement du cache : ' . $key);

            $infos = collect();
            // On associe d'abord tous les attributs
            foreach ($this->attributes as $key => $value)
                $infos->$key = $value;

            $titreSlug = Str::slug($this->titre);
            if($this->sport_id)
                $href = route('article.sport.show', ['titre' => $titreSlug, 'uniqid' => $this->uniqid, 'sport' => $this->uniqid]);
            else
                $href = route('article.show', ['titre' => $titreSlug, 'uniqid' => $this->uniqid]);
            $infosPlus = [
                'href' => $href,
                'titreSlug' => $titreSlug,
                'publie_le' => $this->created_at->translatedFormat('d F Y'),
                'src_img' => ($this->img) ? config('app.url') . '/storage/img/' . $this->img : '' // Todo : Image par défaut !?
            ];

            // Ensuite on associe les infos supplémentaires
            foreach ($infosPlus as $key => $value)
                $infos->$key = $value;

            return $infos;
        });
    }
}
