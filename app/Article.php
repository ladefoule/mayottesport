<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['img', 'titre', 'article', 'preambule', 'uniqid', 'valide', 'sport_id', 'user_id', 'user_update_id', 'slug', 'home_visible', 'home_priorite'];

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
     * @param Article|id $article
     * @return array
     */
    public static function rules($article = NULL)
    {
        $uniqid = Rule::unique('articles')->ignore($article);
        // request()['valide'] = request()->has('valide');
        // request()['slug'] = Str::slug(request()['titre']);
        // request()['user_id'] = Auth::id();
        // request()['user_update_id'] = Auth::id();

        $rules = [
            'article' => 'nullable|min:30',
            'preambule' => 'required|min:30',
            'titre' => 'required|min:10|max:150',
            'slug' => 'required|alpha_dash|min:10|max:150',
            'home_priorite' => 'nullable|integer|min:1',
            'home_visible' => 'nullable|boolean',
            'sport_id' => 'nullable|integer|exists:sports,id',
            'user_id' => 'required|integer|exists:users,id',
            'user_update_id' => 'nullable|integer|exists:users,id',
            'img' => 'nullable|min:5|max:200',
            'uniqid' => ['required','string','size:13',$uniqid],
            'valide' => 'nullable|boolean'
        ];

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

            if($this->sport_id)
                $href = route('article.sport.show', ['titre' => $this->slug, 'uniqid' => $this->uniqid, 'sport' => Str::slug($this->sport->nom)]);
            else
                $href = route('article.show', ['titre' => $this->slug, 'uniqid' => $this->uniqid]);

            $infosPlus = [
                'href' => $href,
                'publie_le' => $this->created_at->translatedFormat('d F Y'),
                'modifie_le' => $this->updated_at ? $this->updated_at->translatedFormat('d F Y') : '',
                'src_img' => ($this->img) ? asset('/storage/img/' . $this->img) : '' // Todo : Image par défaut !?
            ];

            // Ensuite on associe les infos supplémentaires
            foreach ($infosPlus as $key => $value)
                $infos->$key = $value;

            return $infos;
        });
    }

    /**
     * Undocumented function
     *
     * @param Sport|\Illuminate\Support\Collection $sport
     * @return \Illuminate\Support\Collection
     */
    public static function filActu($sport = null)
    {
        if($sport)
            return $sport->articles()
            ->where('valide', 1)
            ->where('visible', 1)
            ->where('fil_actu', 1)
            ->orderBy('priorite', 'desc')
            ->orderBy('created_at')
            ->limit(10)->get();
        
        return Article::where('valide', 1)
            ->where('fil_actu', 1)
            ->where('home_visible', '>', 0)
            ->orderBy('home_priorite', 'desc')
            ->orderBy('created_at')
            ->limit(10)
            ->get();
    }

    /**
     * Le sport lié à l'article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sport()
    {
        return $this->belongsTo('App\Sport');
    }

    /**
     * L'utilisateur qui a créé l'article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * L'utilisateur qui a modifié en dernier l'article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userUpdate()
    {
        return $this->belongsTo('App\User', 'user_update_id');
    }

    /**
     * Les sports liés à l'article
     */
    public function sports()
    {
        return $this->belongsToMany('App\Sport')->using('App\ArticleSport')->withPivot(['priorite', 'visible']);
    }

    /**
     * Les compétitions liés à l'article
     */
    public function competitions()
    {
        return $this->belongsToMany('App\Competition')->using('App\ArticleCompetition');
    }

    /**
     * Les équipes liés à l'article
     */
    public function equipes()
    {
        return $this->belongsToMany('App\Equipe')->using('App\ArticleEquipe');
    }
}
