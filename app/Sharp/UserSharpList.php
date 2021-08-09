<?php

namespace App\Sharp;

use App\User;
use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class UserSharpList extends SharpEntityList
{
    /**
    * Build list containers using ->addDataContainer()
    *
    * @return void
    */
    public function buildListDataContainers(): void
    {
        $this->addDataContainer(
            EntityListDataContainer::make('name')
                ->setLabel('Nom')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('pseudo')
                ->setLabel('Pseudo')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('role_id')
                ->setLabel('Rôle')
                ->setSortable()
        )->addDataContainer(
            EntityListDataContainer::make('created_at')
                ->setLabel('Inscrit le')
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
        $this->addColumn('name', 3)
        ->addColumn('pseudo', 3)
        ->addColumn('role_id', 3)
        ->addColumn('created_at', 3);
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
        $users = User::orderBy($params->sortedBy(), $params->sortedDir());

        // Recherche sur le nom
        collect($params->searchWords())
            ->each(function ($word) use ($users) {
                $users->where(function ($query) use ($word) {
                    $query->orWhere('name', 'like', $word)->orWhere('pseudo', 'like', $word);
                });
            });

        return $this->setCustomTransformer(
            "created_at",
            function ($created_at, $user) {
                return $user->created_at ? date_format($user->created_at, 'd/m/Y à H:i:s') : '';
            }
        )->setCustomTransformer(
            "role_id",
            function ($role_id, $user) {
                return $user->role->name;
            }
        )->transform($users->paginate(12));
    }
}
