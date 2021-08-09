<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category - active record
 *
 * Recipe categories
 */
class Sport extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'home_position'];

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param Sport $sport
     * @return array
     */
    public static function rules(Sport $sport = null)
    {
        $rules = [
            'nom' => ['required','string','max:50','min:3',Rule::unique('sports')->ignore($sport)],
            'home_position' => 'nullable|integer|min:0',
        ];
        return ['rules' => $rules];
    }

    /**
     * Les competitions associés à ce sport
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function competitions()
    {
        return $this->hasMany('App\Competition');
    }

    /**
     * Les équipes liées
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipes()
    {
        return $this->hasMany('App\Equipe');
    }

    /**
     * Les articles liés au sport
     */
    public function articles()
    {
        return $this->belongsToMany('App\Article')->using('App\ArticleSport')->withPivot(['priorite', 'visible']);
    }

    /**
     * Les compétitions dans la navbar
     */
    public function competitionsNavbar()
    {
        return $this->belongsToMany('App\Competition')->using('App\CompetitionSport')->withPivot(['position']);
    }
}
