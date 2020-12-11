<?php

namespace App\Sharp;

use App\User;
use App\Match;
use Illuminate\Support\Facades\DB;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class MatchSharpList extends SharpEntityList
{
    /**
    * Build list containers using ->addDataContainer()
    *
    * @return void
    */
    public function buildListDataContainers()
    {
        $this->addDataContainer(
            EntityListDataContainer::make('uniqid')
                ->setLabel('Uniqid')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('sport')
                ->setLabel('Sport')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('competition')
                ->setLabel('Competition')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('annee')
                ->setLabel('Année')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('rencontre')
                ->setLabel('Rencontre')
                ->setSortable()
        );
    }

    /**
    * Build list layout using ->addColumn()
    *
    * @return void
    */
    public function buildListLayout()
    {
        $this->addColumn('uniqid', 2)
        ->addColumn('sport', 2)
        ->addColumn('competition', 2)
        ->addColumn('annee', 2)
        ->addColumn('rencontre', 4);
    }

    /**
    * Build list config
    *
    * @return void
    */
    public function buildListConfig()
    {
        $this->setInstanceIdAttribute('id')
            ->setSearchable()
            ->setDefaultSort('uniqid', 'desc')
            ->setPaginated();
    }

	/**
	* Retrieve all rows data as array.
	*
	* @param EntityListQueryParams $params
	* @return array
	*/
    public function getListData(EntityListQueryParams $params)
    {
        // Jointure par ORM Eloquent
        $matches = Match::orderBy($params->sortedBy(), $params->sortedDir())
                        ->join('equipes', function ($join) {
                            $join->on('equipe_id_dom', '=', 'equipes.id')->orOn('equipe_id_ext', '=', 'equipes.id');
                        })
                        ->join('journees', 'journee_id', '=', 'journees.id')
                        ->join('saisons', 'saison_id', '=', 'saisons.id')
                        ->join('competitions', 'competition_id', '=', 'competitions.id')
                        ->join('sports', 'competitions.sport_id', '=', 'sports.id')
                        ->select('matches.*', 'sports.nom as sport', 'matches.uniqid as uniqid', 'competitions.nom as competition', 'saisons.annee_debut as annee');

        // Recherche sur le nom
        collect($params->searchWords())
            ->each(function ($word) use ($matches) {
                $matches->where(function ($query) use ($word) {
                    $query->orWhere('sports.nom', 'like', $word)
                    ->orWhere('matches.uniqid', 'like', $word)
                    ->orWhere('saisons.annee_debut', 'like', $word)
                    ->orWhere('competitions.nom', 'like', $word)
                    ->orWhere('equipes.nom', 'like', $word);
                });
            });

        return $this->setCustomTransformer(
            "saison",
            function ($label, $match) {
                return $match->journee->saison->nom;
            }
        )->setCustomTransformer(
            "sport",
            function ($label, $match) {
                return $match->journee->saison->competition->sport->nom;
            }
        )->setCustomTransformer(
            "competition",
            function ($label, $match) {
                return $match->journee->saison->competition->nom;
            }
        )->setCustomTransformer(
            "rencontre",
            function ($label, $match) {
                return $match->equipeDom->nom . ' # ' . $match->equipeExt->nom;
            }
        )->transform($matches->paginate(10));
    }
}
