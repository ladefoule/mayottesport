<?php

namespace App\Sharp;

use App\Article;
use App\Jobs\ProcessCrudTable;
use Code16\Sharp\Form\SharpForm;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use App\Sharp\Formatters\TimestampSharpFormatter;
use Code16\Sharp\Form\Fields\SharpFormCheckField;

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
        $article = Article::findOrFail($id)/* ->makeVisible('secret') */;
        return $this->setCustomTransformer("user", function ($user, $article) {
            return $article->user->pseudo;
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
        $article->update($data);
        forgetCaches('articles', $article);
        ProcessCrudTable::dispatch('articles', $id);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $article = Article::findOrFail($id);
        forgetCaches('articles', $article);
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
                SharpFormTextField::make("created_at")
                    ->setLabel("Créé le")
                    ->setFormatter($timestampFormatter)
                    ->setReadOnly(true)
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
            $column->withFields('uniqid|6', 'valide|6', 'titre|12', 'created_at|6', 'user|6');
        });
    }
}
