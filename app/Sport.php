<?php
/**
 * Class Category | core/Category.class.php
 *
 * @package     MyApp XYZ
 * @subpackage  Categories
 * @author      Sandro Miguel Marques <sandromiguel@something.com>
 * @version     v.1.1 (06/12/2016)
 * @copyright   Copyright (c) 2016, Sandro
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
    protected $fillable = ['nom', 'code'];

    /**
     * Définition de l'affichage d'un sport
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nom ?? '';
    }

    /**
     * Les championnats associés à ce sport
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function championnats()
    {
        return $this->hasMany('App\Championnat');
    }

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param Sport $sport
     * @return array
     */
    public static function rules(Request $request, Sport $sport = null)
    {
        $nom = $request['nom'] ?? '';
        $unique = Rule::unique('sports')->where(function ($query) use ($nom) {
            return $query->whereNom($nom);
        });

        if($sport){
            $id = $sport->id;
            $unique = $unique->ignore($id);
        }

        $rules = [
            'code' => 'nullable|string|max:5',
            'nom' => ['required','string','max:50','min:3',$unique]
        ];
        return ['rules' => $rules];
    }
}
