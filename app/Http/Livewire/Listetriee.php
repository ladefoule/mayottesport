<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;

class Listetriee extends Component
{
    public $equipes = [];
    public $orderBy = [];
    public $count = 0;

    public function render()
    {
        // var_dump(get_class_methods('Illuminate\Support\Collection'));
        // if(isset($this->orderBy['equipe_lib']) && $this->orderBy['equipe_lib'] == 'asc')
        //     $this->orderBy = ['equipe_lib' => 'desc'];
        // else
        //     $this->orderBy = ['equipe_lib' => 'asc'];

        // if($this->equipes == [])
        //     $this->equipes = listeTriee('equipes', $this->orderBy)->all();

        return view('livewire.listetriee', ['equipes' => $this->equipes]);
    }

    public function equipe()
    {
        // if(isset($this->orderBy['equipe_lib']) && $this->orderBy['equipe_lib'] == 'asc')
        //     $this->orderBy = ['equipe_lib' => 'desc'];
        // else
        //     $this->orderBy = ['equipe_lib' => 'asc'];

        $this->equipes = listeTriee('sports')->all();
    }

    public function increment()
    {
        $this->count++;
        $this->equipes = listeTriee('equipes')->all();
    }

    public function decrement()
    {
        $this->count--;
    }
}
