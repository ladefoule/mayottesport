<?php

namespace App\Sharp;

use App\Match;
use Illuminate\Support\Facades\Log;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class MatchFootSharpList extends SharpEntityList
{
    /**
    * Build list containers using ->addDataContainer()
    *
    * @return void
    */
    public function buildListDataContainers()
    {
        $this->addDataContainer(
            EntityListDataContainer::make('id')
                ->setLabel('id')
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
        );
    }

    /**
    * Build list layout using ->addColumn()
    *
    * @return void
    */
    public function buildListLayout()
    {
        $this->addColumn('id', 2)
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
            ->setDefaultSort('updated_at', 'desc') // On affiche en premier les matches modifiés récemment
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
                        ->join('journees', 'journee_id', '=', 'journees.id')
                        ->join('saisons', 'saison_id', '=', 'saisons.id')
                        ->join('competitions', 'competition_id', '=', 'competitions.id')
                        ->join('sports', 'competitions.sport_id', '=', 'sports.id')
                        ->where('sports.nom', 'like', 'football')
                        ->select('matches.*', 'sports.nom as sport', 'competitions.nom as competition', 'saisons.annee_debut as annee')
                        ->distinct();

        // Recherche
        collect($params->searchWords())
            ->each(function ($word) use ($matches) {
                $matches
                ->join('equipes', function ($join) {
                    // Pour pouvoir faire la recherche sur les noms des équipes
                    $join->on('equipe_id_dom', '=', 'equipes.id')->orOn('equipe_id_ext', '=', 'equipes.id');
                })
                ->where(function ($query) use ($word) {
                    $query->orWhere('sports.nom', 'like', $word)
                    ->orWhere('matches.uniqid', 'like', $word)
                    ->orWhere('saisons.annee_debut', 'like', $word)
                    ->orWhere('competitions.nom', 'like', $word)
                    ->orWhere('equipes.nom', 'like', $word);
                });
            });

        return $this->setCustomTransformer(
            "id",
            function ($id, $match) {
                return $match->uniqid;
            }
        )->setCustomTransformer(
            "rencontre",
            function ($rencontre, $match) {
                return $match->equipeDom->nom . ' # ' . $match->equipeExt->nom;
            }
        )->transform($matches->paginate(10));
    }
}
