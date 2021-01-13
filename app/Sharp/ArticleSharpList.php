<?php

namespace App\Sharp;

use App\Article;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class ArticleSharpList extends SharpEntityList
{
    /**
    * Build list containers using ->addDataContainer()
    *
    * @return void
    */
    public function buildListDataContainers()
    {
        $this->addDataContainer(
            EntityListDataContainer::make('titre')
                ->setLabel('Titre')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('valide')
                ->setLabel('Validé')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('sport_id')
                ->setLabel('Catégorie')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('home_visible')
                ->setLabel('Visible (accueil)')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('home_priorite')
                ->setLabel('Priorité (accueil)')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('created_at')
                ->setLabel('Créé le')
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
        $this
        ->addColumn('titre', 4)
        ->addColumn('valide', 2)
        ->addColumn('home_visible', 2)
        ->addColumn('home_priorite', 2)
        // ->addColumn('sport_id', 2)
        ->addColumn('created_at', 2);
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
            ->setDefaultSort('created_at', 'desc')
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
        $articles = Article::orderBy($params->sortedBy(), $params->sortedDir());

        // Recherche sur le nom
        collect($params->searchWords())
            ->each(function ($word) use ($articles) {
                $articles->where(function ($query) use ($word) {
                    $query->orWhere('uniqid', 'like', $word)->orWhere('titre', 'like', $word);
                });
            });

        return $this->setCustomTransformer(
            "created_at",
            function ($created_at, $article) {
                return $article->created_at ? date_format($article->created_at, 'd/m/Y à h:i:s') : '';
            }
        )->setCustomTransformer(
            "valide",
            function ($valide, $article) {
                return $article->valide ? 'Oui' : 'Non';
            }
        )/* ->setCustomTransformer(
            "sport_id",
            function ($sport_id, $article) {
                return $article->sport ? $article->sport->nom : '';
            }
        ) */->setCustomTransformer(
            "home_visible",
            function ($home_visible, $article) {
                return $article->home_visible ? 'Oui': 'Non';
            }
        )->setCustomTransformer(
            "home_priorite",
            function ($home_priorite, $article) {
                $priorites = config('listes.priorites-articles');
                return $article->home_priorite ? $priorites[$article->home_priorite][1] : '';
            }
        )->transform($articles->paginate(12));
    }
}
