<?php

namespace App\Sharp;

use App\Sport;
use App\Bareme;
use App\Jobs\ProcessCacheReload;
use Code16\Sharp\Form\SharpForm;
use Illuminate\Support\Facades\Validator;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Fields\SharpFormNumberField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class BaremeSharpForm extends SharpForm
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
        $bareme = Bareme::findOrFail($id);

        return $this->transform(
            $bareme
        );
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $bareme = $id ? Bareme::findOrFail($id) : new Bareme;

        // On valide la requète
        $rules = Bareme::rules($bareme);
        $messages = $rules['messages'] ?? [];
        $rules = $rules['rules'];

        Validator::make($data, $rules, $messages)->validate();

        $bareme = $this->save($bareme, $data);

        forgetCaches('baremes', $bareme);
        ProcessCacheReload::dispatch('baremes', $bareme->id);
    }

    /**
     * @param $id
     */
    public function delete($id): void
    {
        $bareme = Bareme::findOrFail($id);

        forgetCaches('baremes', $bareme);
        $bareme->delete();
        ProcessCacheReload::dispatch('baremes');
    }

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    public function buildFormFields(): void
    {
        $sports = Sport::orderBy('sports.nom')->get()->map(function($sport) {
                return [
                    "id" => $sport->id,
                    "label" => $sport->nom
                ];
            })->all();

        $this
            ->addField(
                SharpFormTextField::make("nom")
                    ->setLabel("Nom")
            )->addField(
                SharpFormNumberField::make("victoire")
                    ->setLabel("Victoire")
                    ->setShowControls()
                    ->setMin(0)
            )->addField(
                SharpFormNumberField::make("nul")
                    ->setLabel("Nul")
                    ->setShowControls()
                    ->setMin(0)
            )->addField(
                SharpFormNumberField::make("defaite")
                    ->setLabel("Défaite")
                    ->setShowControls()
                    ->setMin(0)
            )->addField(
                SharpFormNumberField::make("forfait")
                    ->setLabel("Forfait")
                    ->setShowControls()
                    ->setMax(0)
            )->addField(
                SharpFormSelectField::make("sport_id", $sports)
                ->setLabel("Sport")
                ->setDisplayAsDropdown()
            );
    }

    /**
     * Build form layout using ->addTab() or ->addColumn()
     *
     * @return void
     */
    public function buildFormLayout(): void
    {
        $this->addColumn(12, function (FormLayoutColumn $column) {
            $column->withFields('nom|6', 'sport_id|6', 'victoire|6', 'nul|6', 'defaite|6', 'forfait|6');
        });
    }
}
