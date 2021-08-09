<?php

namespace App\Sharp;

use App\Sport;
use App\Bareme;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class BaremeVolleySharpList extends SharpEntityList
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
            EntityListDataContainer::make('victoire')
                ->setLabel('Victoire')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('nul')
                ->setLabel('Nul')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('defaite')
                ->setLabel('Défaite')
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
        ->addColumn('nom', 4)
        /* ->addColumn('victoire', 3)
        ->addColumn('nul', 2)
        ->addColumn('defaite', 3) */;
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
            ->setDefaultSort('nom', 'desc')
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
        $volleyballId = Sport::firstWhere('slug', 'volleyball')->id;
        $baremes = Bareme::whereSportId($volleyballId)->orderBy($params->sortedBy(), $params->sortedDir())
            ->join('sports', 'sport_id', '=', 'sports.id')
            ->select('baremes.*', 'sports.nom as sport');

        // Recherche
        foreach ($params->searchWords() as $key => $word) {
            $baremes->where(function ($query) use ($word) {
                $query->orWhere('baremes.nom', 'like', $word);
            });
        }

        return $this->transform($baremes->paginate(12));
    }
}
