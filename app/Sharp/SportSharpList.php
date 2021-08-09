<?php

namespace App\Sharp;

use App\Sport;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class SportSharpList extends SharpEntityList
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
            EntityListDataContainer::make('home_position')
                ->setLabel('Position (accueil)')
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
        ->addColumn('nom', 4)
        ->addColumn('home_position', 4)
        ->addColumn('updated_at', 4);
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
            ->setDefaultSort('home_position', 'asc')
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
        $sports = Sport::orderBy($params->sortedBy(), $params->sortedDir());

        foreach ($params->searchWords() as $key => $word) {
            $sports->where(function ($query) use ($word) {
                $query->orWhere('sports.nom', 'like', $word);
            });
        }

        return $this->setCustomTransformer(
            "updated_at",
            function ($updated_at, $sport) {
                return $sport->updated_at ? date_format($sport->updated_at, 'd/m/Y à h:i:s') : '';
            }
        )->transform($sports->paginate(12));
    }
}
