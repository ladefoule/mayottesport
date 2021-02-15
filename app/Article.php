<?php

namespace App;

use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements Feedable
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['img', 'img_description', 'titre', 'article', 'preambule', 'uniqid', 'valide', 'fil_actu', 'href_fil_actu', 'sport_id', 'competition_id', 'user_id', 'user_update_id', 'slug', 'home_visible', 'home_priorite'];

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
        $sportId = request()->input('sport_id');
        $uniqueWithSport = Rule::unique('articles')->where(function ($query) use ($sportId) {
            return $query->whereSportId($sportId);
        })->ignore($article);

        $uniqid = Rule::unique('articles')->ignore($article);

        $rules = [
            'article' => 'nullable|min:30',
            'preambule' => 'required|min:30',
            'titre' => ['required','min:5',$uniqueWithSport],
            'slug' => ['required','alpha_dash','min:5',$uniqueWithSport],
            'home_priorite' => 'nullable|integer|min:1',
            'home_visible' => 'nullable|boolean',
            'sport_id' => 'nullable|integer|required_with:competition_id|exists:sports,id',
            'competition_id' => 'nullable|integer|exists:competitions,id',
            'user_id' => 'required|integer|exists:users,id',
            'user_update_id' => 'nullable|integer|exists:users,id',
            'img' => 'nullable|min:5|max:200',
            'img_description' => 'nullable|max:200',
            'uniqid' => ['required','string','size:13',$uniqid],
            'valide' => 'nullable|boolean',
            'fil_actu' => 'nullable|boolean',
            'href_fil_actu' => 'nullable'
        ];

        $messages = [
            'required_with' => "La catégorie est obligatoire si la sous-catégorie est renseignée."
        ];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * Les informations du match dont ont besoin les views match/resultat et horaire
     *
     * @return \Illuminate\Support\Collection
     */
    public function infos()
    {
        $key = 'articles-'.$this->id;
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

            $categorie = '';
            if($this->sport)
                $categorie .= $this->sport->nom;
            if($this->competition)
                $categorie .= ' - ' . $this->competition->nom;  

            $infosPlus = [
                'href' => $href,
                'publie_le' => $this->created_at->translatedFormat('d F Y à H:i'),
                'modifie_le' => $this->updated_at ? $this->updated_at->translatedFormat('d F Y à H:i') : '',
                'date_fil_actu' => $this->created_at->format('d/m'),
                'heure_fil_actu' => $this->created_at->format('H:i'),
                'categorie' => $categorie,
                // 'auteur' => $this->user->name . ' ' . Str::ucfirst($this->user->first_name)[0] ?? '',
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
            $articles = index('articles')->where('sport_id', $sport->id);
        else
            $articles = index('articles');

        $articles = $articles->where('valide', 1)
            ->where('fil_actu', 1)
            ->sortByDesc('created_at')
            ->splice(0,10);

        foreach ($articles as $key => $article)
            $articles[$key] = infos('articles', $article->id);
        
        return $articles;
    }

    /**
     * Transformation de l'article en objet FeedItem pour les
     *
     * @return FeedItem
     */
    public function toFeedItem(): FeedItem
    {
        $article = infos('articles', $this->id);
        return FeedItem::create()
            ->id($this->id)
            ->title($this->titre)
            ->summary($this->preambule)
            ->updated($this->updated_at)
            ->link($article->href)
            ->category($article->categorie)
            ->author("M. ALI MOUSSA");
    }

    /**
     * Liste des articles visibles dans le flux RSS
     *
     * @return Collection
     */
    public static function getFeedItems()
    {
        return Article::where('fil_actu', '!=', 1)->orderBy('updated_at', 'desc')->get();
    }

    /**
     * La catégorie de l'article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sport()
    {
        return $this->belongsTo('App\Sport');
    }

    /**
     * La sous-catégorie de l'article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competition()
    {
        return $this->belongsTo('App\Competition');
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
