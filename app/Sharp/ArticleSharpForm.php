<?php

namespace App\Sharp;

use App\Sport;
use App\Article;
use App\Competition;
use App\ArticleSport;
use App\ArticleCompetition;
use App\Jobs\ProcessCrudTable;
use Code16\Sharp\Form\SharpForm;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use App\Sharp\Formatters\TimestampSharpFormatter;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;

class ArticleSharpForm extends SharpForm
{
    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @param $id
     * @return array
     */
    public function find($id): array
    {
        $article = Article::whereUniqid($id)->firstOrFail();

        // Les sports liés
        $articleSports = ArticleSport::whereArticleId($article->id)->pluck('sport_id')->all();
        $sportsLies = [];
        foreach (Sport::all() as $sport)
            if(in_array($sport->id, $articleSports))
                $sportsLies[] = [
                    'id' => $sport->id,
                    'label' => $sport->nom,
                ];
        
        // Les compétitions liés
        $articleCompetitions = ArticleCompetition::whereArticleId($article->id)->pluck('competition_id')->all();
        $competitionsLies = [];
        foreach (Competition::all() as $competition)
            if(in_array($competition->id, $articleCompetitions))
                $competitionsLies[] = [
                    'id' => $competition->id,
                    'label' => $competition->nom,
                ];

        return $this->setCustomTransformer("user", function ($user, $article) {
            return $article->user->pseudo;
        })->setCustomTransformer("user_update", function ($user_update, $article) {
            return $article->userUpdate->pseudo ?? '';
        })->setCustomTransformer("id", function ($id, $article) {
            return $article->uniqid;
        })->setCustomTransformer("sports", function ($sports, $article) use($sportsLies) {
            return $sportsLies;
        })->setCustomTransformer("competitions", function ($competitions, $article) use($competitionsLies) {
            return $competitionsLies;
        })->transform(
            $article
        );
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $article = Article::whereUniqid($id)->firstOrFail();
        $article->update($data);
        forgetCaches('articles', $article);
        ProcessCrudTable::dispatch('articles', $article->id);

        $article->sports()->detach(); // Supprime toutes les relations sport/article concernant l'article
        foreach ($data['sports'] as $sport)
            $article->sports()->attach($sport['id']);

        $article->competitions()->detach(); // Supprime toutes les relations competition/article concernant l'article
        foreach ($data['competitions'] as $competition)
            $article->competitions()->attach($competition['id']);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $article = Article::whereUniqid($id)->firstOrFail();
        forgetCaches('articles', $article);
        $article->sports()->detach();
        $article->competitions()->detach();
        $article->delete();
        ProcessCrudTable::dispatch('articles');
    }

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    public function buildFormFields()
    {
        $timestampFormatter = new TimestampSharpFormatter;
        $this
            ->addField(
                SharpFormTextField::make("uniqid")
                    ->setLabel("Id")
                    ->setReadOnly(true)
            )->addField(
                SharpFormCheckField::make("valide", "Validé")
                    ->setLabel("Validé")
            )->addField(
                SharpFormTextField::make("titre")
                    ->setLabel("Titre")
                    ->setReadOnly(true)
            )->addField(
                SharpFormTextField::make("user")
                    ->setLabel("Auteur")
                    ->setReadOnly(true)
            )->addField(
                SharpFormTextField::make("user_update")
                    ->setLabel("Modifié par")
                    ->setReadOnly(true)
            )->addField(
                SharpFormTextField::make("created_at")
                    ->setLabel("Créé le")
                    ->setFormatter($timestampFormatter)
                    ->setReadOnly(true)
            )->addField(
                SharpFormTextField::make("updated_at")
                    ->setLabel("Modifié le")
                    ->setFormatter($timestampFormatter)
                    ->setReadOnly(true)
            )->addField(
                SharpFormSelectField::make("sports",
                    Sport::orderBy("nom")
                    ->get()->map(function($sport) {
                        return [
                            "id" => $sport->id,
                            "label" => $sport->nom
                        ];
                    })->all()
                )
                ->setLabel("Sports liés")
                ->setDisplayAsList()
                ->setMultiple(true)
                ->setClearable(true)
            )->addField(
                SharpFormSelectField::make("competitions",
                    Competition::orderBy("nom")
                    ->get()->map(function($competition) {
                        return [
                            "id" => $competition->id,
                            "label" => $competition->crud_name
                        ];
                    })->all()
                )
                ->setLabel("Compétitions liées")
                // ->setDisplayAsDropdown()
                ->setDisplayAsList()
                ->setMultiple(true)
                ->setClearable(true)
            );
    }

    /**
     * Build form layout using ->addTab() or ->addColumn()
     *
     * @return void
     */
    public function buildFormLayout()
    {
        $this->addColumn(12, function (FormLayoutColumn $column) {
            $column->withFields('uniqid|6', 'valide|6', 'titre|12', 'created_at|6', 'user|6', 'updated_at|6', 'user_update|6', 'sports|6', 'competitions|6');
        });
    }
}
