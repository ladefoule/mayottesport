<?php

namespace App\Sharp;

use App\Match;
use Illuminate\Support\Facades\DB;
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
        ->addColumn('competition', 2)
        ->addColumn('annee', 2)
        ->addColumn('journee_numero', 2)
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
        $matches = Match::orderBy($params->sortedBy(), $params->sortedDir())
            ->join('journees', 'journee_id', '=', 'journees.id')
            ->join('saisons', 'saison_id', '=', 'saisons.id')
            ->join('competitions', 'competition_id', '=', 'competitions.id')
            ->join('sports', 'competitions.sport_id', '=', 'sports.id')
            ->join('equipes AS equipesDom', 'equipe_id_dom', 'equipesDom.id')
            ->join('equipes AS equipesExt', 'equipe_id_ext', 'equipesExt.id')
            ->where('sports.nom', 'like', 'football')
            ->where('saisons.finie', 0)
            ->select('matches.*', 'competitions.nom as competition', 'saisons.annee_debut as annee', 'journees.numero as journee_numero')
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

        \Log::info(count($matches->get()));

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
        )->setCustomTransformer(
            "journee_numero",
            function ($journee, $match) {
                return $match->journee->nom . ' ('. $match->journee->numero .')';
            }
        )->transform($matches->paginate(12));
    }
}
