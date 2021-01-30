<?php

namespace App\Sharp;

use App\Sport;
use App\Equipe;
use App\Article;
use App\Competition;
use App\ArticleSport;
use App\Jobs\ProcessCacheReload;
use Code16\Sharp\Form\SharpForm;
use Illuminate\Support\Facades\Validator;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormDateField;
use Code16\Sharp\Form\Fields\SharpFormListField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use App\Sharp\Formatters\TimestampSharpFormatter;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormNumberField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class ArticleSharpForm extends SharpForm
{
    use WithSharpFormEloquentUpdater;
    
    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @param $id
     * @return array
     */
    public function find($id): array
    {
        $article = Article::findOrFail($id);

        // Les sports liés
        $sports = $article->sports->all();
        foreach ($sports as $key => $sport)
            $sports[$key] = [
                'id' => $sport->id,
                'sport_id' => $sport->id,
                'article_id' => $article->id,
                'visible' => $sport->pivot->visible,
                'priorite' => $sport->pivot->priorite,
            ];
        
        // Les compétitions liés
        $competitions = $article->competitions->all();
        foreach ($competitions as $competition)
            $competition = [
                'id' => $competition->id,
                'label' => $competition->nom,
            ];

        // Les équipes liés
        $equipes = $article->equipes->all();
        foreach ($equipes as $equipe)
            $equipe = [
                'id' => $equipe->id,
                'label' => $equipe->nom,
            ];

        return $this->setCustomTransformer("user", function ($user, $article) {
            return $article->user->pseudo;
        })->setCustomTransformer("user_update", function ($user_update, $article) {
            return $article->userUpdate->pseudo ?? '';
        })->setCustomTransformer("id", function ($id, $article) {
            return $article->uniqid;
        })->setCustomTransformer("sports", function ($sports_, $article) use($sports) {
            return $sports;
        })->setCustomTransformer("competitions", function ($competitions_, $article) use($competitions) {
            return $competitions;
        })->setCustomTransformer("equipes", function ($equipes_, $article) use($equipes) {
            return $equipes;
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
        $article = Article::findOrFail($id);

        // On valide la requète
        $rules = Article::rules($article);
        $messages = $rules['messages'];
        $rules = $rules['rules'];
        // $ignore = ['titre', 'preambule', 'uniqid', 'slug', 'user_id', 'uniqid'];
        // $rules = array_diff_key($rules, array_flip($ignore));

        $pluck = ['home_visible', 'home_priorite', 'valide', 'fil_actu', 'sport_id', 'competition_id'];
        $rules = array_intersect_key($rules, array_flip($pluck));

        $dataUpdate = Validator::make($data, $rules, $messages)->validate();

        $article->update($dataUpdate);
        forgetCaches('articles', $article);
        ProcessCacheReload::dispatch('articles', $article->id);

        $article->sports()->detach(); // Supprime toutes les relations sport/article concernant l'article
        foreach ($data['sports'] as $sport){
            if($sport){
                // On valide la requète des pivots
                $rules = ArticleSport::rules();
                $messages = $rules['messages'];
                $rules = $rules['rules'];

                $sport['article_id'] = $article->id;
                Validator::make($sport, $rules, $messages)->validate();

                $article->sports()->attach($sport['sport_id'], [
                    'visible' => $sport['visible'],
                    'priorite' => $sport['priorite'],
                ]);
            }
        }

        $article->competitions()->detach(); // Supprime toutes les relations competition/article concernant l'article
        foreach ($data['competitions'] as $competition)
            $article->competitions()->attach($competition['id']);

        $article->equipes()->detach(); // Supprime toutes les relations equipe/article concernant l'article
        foreach ($data['equipes'] as $equipe)
            $article->equipes()->attach($equipe['id']);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $article = Article::findOrFail($id);
        forgetCaches('articles', $article);
        $article->sports()->detach();
        $article->equipes()->detach();
        $article->competitions()->detach();
        $article->delete();
        ProcessCacheReload::dispatch('articles');
    }

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    public function buildFormFields()
    {
        $timestampFormatter = new TimestampSharpFormatter;

        $prioritesConfig = config('listes.priorites-articles');
        foreach ($prioritesConfig as $id => $priorite) {
            $priorites[] = [
                "id" => $id,
                "label" => $priorite[1]
            ];
        }

        $sports = Sport::orderBy('sports.nom')
                        ->get()->map(function($sport) {
                            return [
                                "id" => $sport->id,
                                "label" => $sport->nom
                            ];
                        })->all();

        $competitions = Competition::join('sports', 'sport_id', 'sports.id')
                                    ->orderBy('sports.nom')->orderBy("competitions.nom")
                                    ->select('competitions.*')
                                    ->get()->map(function($competition) {
                                        return [
                                            "id" => $competition->id,
                                            "label" => $competition->sport->nom . ' - ' . $competition->nom
                                        ];
                                    })->all();

        $this->addField(
                SharpFormTextField::make("uniqid")
                    ->setLabel("Id")
                    ->setReadOnly(true)
            )->addField(
                SharpFormCheckField::make("valide", "Validé")
                    ->setLabel("Validé")
            )->addField(
                SharpFormCheckField::make("fil_actu", "Fil actu")
                    ->setLabel("Fil actu")
            )->addField(
                SharpFormTextField::make("titre")
                    ->setLabel("Titre")
                    ->setReadOnly(true)
            )->addField(
                SharpFormTextField::make("user")
                    ->setLabel("Auteur")
                    ->setReadOnly(true)
            )->addField(
                SharpFormCheckField::make("home_visible", "Afficher")
                    ->setLabel("Afficher (page d'accueil)")
            )->addField(
                SharpFormSelectField::make("home_priorite",
                    $priorites ?? []
                )->setLabel("Priorité (page d'accueil)")
                ->setDisplayAsDropdown()
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
                SharpFormListField::make("sports")
                    ->setLabel("Sports liés")
                    ->setAddable()
                    ->setMaxItemCount(count(Sport::all()))
                    ->setRemovable()
                    ->addItemField(
                        SharpFormSelectField::make("sport_id",
                            $sports
                        )->setDisplayAsDropdown()
                        ->setLabel("Sport")
                    )->addItemField(
                        SharpFormCheckField::make("visible", "Visible")
                            ->setLabel("Visible")
                    )->addItemField(
                        SharpFormSelectField::make("priorite",
                            $priorites ?? []
                        )->setLabel("Priorité")
                        ->setDisplayAsDropdown()
                    )
            )->addField(
                SharpFormSelectField::make("competitions",
                    $competitions
                )
                ->setLabel("Compétitions liées")
                ->setDisplayAsDropdown()
                // ->setDisplayAsList()
                ->setMultiple(true)
                ->setClearable(true)
            )->addField(
                SharpFormSelectField::make("equipes",
                    Equipe::join('sports', 'sport_id', 'sports.id')
                    ->select('equipes.*')
                    ->orderBy('sports.nom')
                    ->orderBy('equipes.nom')->get()->map(function($equipe) {
                        return [
                            "id" => $equipe->id,
                            "label" => $equipe->sport->nom . ' - ' . $equipe->nom
                        ];
                    })->all()
                )
                ->setLabel("Equipes liées")
                ->setDisplayAsDropdown()
                // ->setDisplayAsList()
                ->setMultiple(true)
                ->setClearable(true)
            )->addField(
                SharpFormSelectField::make("sport_id",
                    $sports
                )->setLabel("Catégorie")
                ->setDisplayAsDropdown()
                ->setClearable()
            )->addField(
                SharpFormSelectField::make("competition_id",
                    $competitions
                )->setLabel("Sous-catégorie")
                ->setDisplayAsDropdown()
                ->setClearable()
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
            $column->withFields('uniqid|6', 'valide|3', 'fil_actu|3', 'titre|12', 'sport_id|6', 'competition_id|6', 'home_visible|6', 'home_priorite|6');
        });

        $this->addColumn(12, function(FormLayoutColumn $column) {
            $column->withSingleField("sports", function(FormLayoutColumn $listItem) {
                 $listItem->withFields("sport_id|4", "visible|4", "priorite|4");
            });
        });

        $this->addColumn(12, function (FormLayoutColumn $column) {
            $column->withFields('competitions|6', 'equipes|6');
        });

        $this->addColumn(12, function (FormLayoutColumn $column) {
            $column->withFields('created_at|6', 'user|6', 'updated_at|6', 'user_update|6');
        });
    }
}
