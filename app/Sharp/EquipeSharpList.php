<?php

namespace App\Sharp;

use App\Equipe;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class EquipeSharpList extends SharpEntityList
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
            EntityListDataContainer::make('sport_id')
                ->setLabel('Sport')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('nom')
                ->setLabel('Nom')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('ville_id')
                ->setLabel('Ville')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('nom_complet')
                ->setLabel('Nom complet')
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
        $this->addColumn('sport_id', 3)
        ->addColumn('nom', 3)
        ->addColumn('ville_id', 3)
        ->addColumn('nom_complet', 3);
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
        $equipes = Equipe::orderBy($params->sortedBy(), $params->sortedDir())
            ->join('sports', 'sport_id', 'sports.id')
            ->join('villes', 'ville_id', 'villes.id')
            // ->where('sports.slug', $this->sportSlug)
            ->select('equipes.*')
            ->distinct();

        // Recherche
        foreach ($params->searchWords() as $key => $word) {
            $equipes->where(function ($query) use ($word) {
                $query->orWhere('equipes.nom', 'like', $word)
                ->orWhere('equipes.nom_complet', 'like', $word)
                ->orWhere('villes.nom', 'like', $word)
                ->orWhere('sports.nom', 'like', $word);
            });
        }

        return $this->setCustomTransformer(
            "sport_id",
            function ($sport_id, $equipe) {
                return $equipe->sport->nom;
            }
        )->setCustomTransformer(
            "ville_id",
            function ($ville_id, $equipe) {
                return $equipe->ville->nom;
            }
        )->transform($equipes->paginate(12));
    }
}
