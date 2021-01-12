<?php

namespace App\Sharp;

use App\Journee;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class JourneeSharpList extends SharpEntityList
{
    protected $sportSlug;

    /**
    * Build list containers using ->addDataContainer()
    *
    * @return void
    */
    public function buildListDataContainers()
    {
        $this->addDataContainer(
            EntityListDataContainer::make('sport')
                ->setLabel('Sport')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('competition')
                ->setLabel('Compétition')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('saison')
                ->setLabel('Début')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('numero')
                ->setLabel('Journée')
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
        $this->addColumn('sport', 3)
        ->addColumn('competition', 3)
        ->addColumn('saison', 3)
        ->addColumn('numero', 3);
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
            ->setDefaultSort('numero', 'desc')
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
        $journees = Journee::orderBy($params->sortedBy(), $params->sortedDir())
            ->join('saisons', 'saison_id', '=', 'saisons.id')
            ->join('competitions', 'competition_id', '=', 'competitions.id')
            ->join('sports', 'competitions.sport_id', '=', 'sports.id')
            ->where('sports.slug', $this->sportSlug)
            ->where('saisons.finie', 0)
            ->select('journees.*', 'competitions.nom as competition', 'saisons.annee_debut as saison', 'sports.nom as sport')
            ->distinct();

        // Recherche
        foreach ($params->searchWords() as $key => $word) {
            $journees->where(function ($query) use ($word) {
                $query->orWhere('journees.numero', 'like', $word)
                ->orWhere('saisons.annee_debut', 'like', $word)
                ->orWhere('competitions.nom', 'like', $word)
                ->orWhere('sports.nom', 'like', $word);
            });
        }

        return $this->setCustomTransformer(
            "numero",
            function ($numero, $journee) {
                return $journee->nom . ' (' . $journee->numero . ')';
            }
        )->transform($journees->paginate(12));
    }
}
