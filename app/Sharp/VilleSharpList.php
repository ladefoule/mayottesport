<?php

namespace App\Sharp;

use App\Ville;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class VilleSharpList extends SharpEntityList
{
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
            EntityListDataContainer::make('nb_equipes')
                ->setLabel('Nombre d\'équipes')
                ->setSortable(false)
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
        ->addColumn('nom', 6)
        ->addColumn('nb_equipes', 6);
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
        $villes = Ville::orderBy($params->sortedBy(), $params->sortedDir());

        foreach ($params->searchWords() as $key => $word) {
            $villes->where(function ($query) use ($word) {
                $query->orWhere('villes.nom', 'like', $word);
            });
        }

        return $this->setCustomTransformer(
            "nb_equipes",
            function ($nb_equipes, $ville) {
                return count($ville->equipes);
            }
        )->transform($villes->paginate(12));
    }
}
