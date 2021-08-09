<?php

namespace App\Sharp;

use App\Match;
use Illuminate\Support\Facades\DB;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class MatchSharpList extends SharpEntityList
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
            EntityListDataContainer::make('equipeDom')
                ->setLabel('Domicile')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('equipeExt')
                ->setLabel('Extérieur')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('journee_numero')
                ->setLabel('Journée')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('competition')
                ->setLabel('Competition')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('annee')
                ->setLabel('Début')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('rencontre')
                ->setLabel('Rencontre')
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
        ->addColumn('competition', 3)
        ->addColumn('annee', 1)
        ->addColumn('journee_numero', 2)
        ->addColumn('equipeDom', 2)
        ->addColumn('equipeExt', 2)
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
        $matches = Match::orderBy($params->sortedBy(), $params->sortedDir())
            ->join('journees', 'journee_id', '=', 'journees.id')
            ->join('saisons', 'saison_id', '=', 'saisons.id')
            ->join('competitions', 'competition_id', '=', 'competitions.id')
            ->join('sports', 'competitions.sport_id', '=', 'sports.id')
            ->join('equipes AS equipesDom', 'equipe_id_dom', 'equipesDom.id')
            ->join('equipes AS equipesExt', 'equipe_id_ext', 'equipesExt.id')
            ->where('sports.slug', $this->sportSlug)
            ->where('saisons.finie', 0)
            ->select('matches.*', 'competitions.nom as competition', 'saisons.annee_debut as annee', 'journees.numero as journee_numero', 'equipesDom.nom as equipeDom', 'equipesExt.nom as equipeExt')
            ->distinct();

        // Recherche
        foreach ($params->searchWords() as $key => $word) {
            $matches->where(function ($query) use ($word) {
                $query->orWhere('journees.numero', 'like', $word)
                ->orWhere('matches.uniqid', 'like', $word)
                ->orWhere('saisons.annee_debut', 'like', $word)
                ->orWhere('competitions.nom', 'like', $word)
                ->orWhere('equipesDom.nom', 'like', $word)
                ->orWhere('equipesExt.nom', 'like', $word);
            });
        }

        return $this->setCustomTransformer(
            "updated_at",
            function ($updated_at, $match) {
                return $match->updated_at ? date_format($match->updated_at, 'd/m/Y à h:i:s') : '';
            }
        )->setCustomTransformer(
            "journee_numero",
            function ($journee, $match) {
                return $match->journee->nom . ' ('. $match->journee->numero .')';
            }
        )->transform($matches->paginate(12));
    }
}
