<?php

namespace App\Sharp;

use App\Saison;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class SaisonSharpList extends SharpEntityList
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
            EntityListDataContainer::make('nb_journees')
                ->setLabel('Nb journées')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('competition')
                ->setLabel('Compétition')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('annee_debut')
                ->setLabel('Début')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('annee_fin')
                ->setLabel('Fin')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('nb_journees')
                ->setLabel('Nb. Journées')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('finie')
                ->setLabel('Finie')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('vainqueur')
                ->setLabel('Vainqueur')
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
        ->addColumn('competition', 2)
        ->addColumn('annee_debut', 2)
        ->addColumn('annee_fin', 2)
        ->addColumn('nb_journees', 2)
        ->addColumn('finie', 2)
        ->addColumn('vainqueur', 2);
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
            ->setDefaultSort('annee_debut', 'desc')
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
        $saisons = Saison::orderBy($params->sortedBy(), $params->sortedDir())
            ->join('competitions', 'competition_id', '=', 'competitions.id')
            ->join('sports', 'competitions.sport_id', '=', 'sports.id')
            ->leftJoin('equipes', 'saisons.equipe_id', '=', 'equipes.id')
            // ->where('saisons.finie', 0)
            ->where('sports.slug', $this->sportSlug)
            ->select('saisons.*', 'competitions.nom as competition', 'sports.nom as sport', 'equipes.nom as vainqueur')
            ->distinct();

        // Recherche
        foreach ($params->searchWords() as $key => $word) {
            $saisons->where(function ($query) use ($word) {
                $query->orWhere('saisons.annee_debut', 'like', $word)
                ->orWhere('saisons.annee_fin', 'like', $word)
                ->orWhere('competitions.nom', 'like', $word)
                ->orWhere('equipes.nom', 'like', $word);
            });
        }

        return $this->setCustomTransformer(
            "finie",
            function ($finie, $saison) {
                return $saison->finie ? 'Oui' : 'Non';
            }
        )->setCustomTransformer(
            "vainqueur",
            function ($vainqueur, $saison) {
                return $saison->equipe ? $saison->equipe->nom : '';
            }
        )->transform($saisons->paginate(12));
    }
}
