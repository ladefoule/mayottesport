<?php

namespace App\Sharp;

use App\Competition;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class CompetitionSharpList extends SharpEntityList
{
    protected $sportSlug;

    /**
    * Build list containers using ->addDataContainer()
    *
    * @return void
    */
    public function buildListDataContainers(): void
    {
        $this->addDataContainer(
            EntityListDataContainer::make('nom')
                ->setLabel('Nom')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('sport')
                ->setLabel('Sport')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('nom_complet')
                ->setLabel('Nom complet')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('updated_at')
                ->setLabel('Modifié le')
                ->setSortable()
        );
    }

    /**
    * Build list layout using ->addColumn()
    *
    * @return void
    */
    public function buildListLayout(): void
    {
        $this
        ->addColumn('sport', 3)
        ->addColumn('nom', 4)
        ->addColumn('nom_complet', 3)
        ->addColumn('updated_at', 2);
    }

    /**
    * Build list config
    *
    * @return void
    */
    public function buildListConfig(): void
    {
        $this->setInstanceIdAttribute('id')
            ->setSearchable()
            ->setDefaultSort('sport', 'desc')
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
        $competitions = Competition::orderBy($params->sortedBy(), $params->sortedDir())
            ->join('sports', 'sport_id', '=', 'sports.id')
            ->select('competitions.*', 'sports.nom as sport');

        // Recherche
        foreach ($params->searchWords() as $key => $word) {
            $competitions->where(function ($query) use ($word) {
                $query->orWhere('sports.nom', 'like', $word)
                ->orWhere('competitions.nom_complet', 'like', $word)
                ->orWhere('competitions.nom', 'like', $word)
                /* ->orWhere('sports.nom', 'like', $word) */;
            });
        }

        return $this->setCustomTransformer(
            "updated_at",
            function ($updated_at, $competition) {
                return $competition->updated_at ? date_format($competition->updated_at, 'd/m/Y à h:i:s') : '';
            }
        )->transform($competitions->paginate(12));
    }
}
