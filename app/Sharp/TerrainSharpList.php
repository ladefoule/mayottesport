<?php

namespace App\Sharp;

use App\Terrain;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class TerrainSharpList extends SharpEntityList
{
    /**
    * Build list containers using ->addDataContainer()
    *
    * @return void
    */
    public function buildListDataContainers(): void
    {
        $this->addDataContainer(
            EntityListDataContainer::make('ville_id')
                ->setLabel('Ville')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('nom')
                ->setLabel('Nom')
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
        $this->addColumn('nom', 6)
        ->addColumn('ville_id', 6);
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
            ->setDefaultSort('nom', 'asc')
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
        $terrains = Terrain::orderBy($params->sortedBy(), $params->sortedDir())
            ->join('villes', 'ville_id', 'villes.id')
            ->select('terrains.*')
            ->distinct();

        // Recherche
        foreach ($params->searchWords() as $key => $word) {
            $terrains->where(function ($query) use ($word) {
                $query->orWhere('terrains.nom', 'like', $word)
                ->orWhere('villes.nom', 'like', $word);
            });
        }

        return $this->setCustomTransformer(
            "ville_id",
            function ($ville_id, $terrain) {
                return $terrain->ville->nom;
            }
        )->transform($terrains->paginate(12));
    }
}
