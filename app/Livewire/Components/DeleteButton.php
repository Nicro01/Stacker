<?php

namespace App\Livewire\Components;

use App\Traits\CommonTrait;
use Livewire\Component;

class DeleteButton extends Component
{
    use CommonTrait;

    public $id;
    public $model;
    public $redirect;

    public function mount($id, $model, $redirect = null)
    {
        $this->id = $id;
        $this->model = $model;
        $this->redirect = $redirect;
    }

    public function render()
    {
        return view('livewire.components.delete-button');
    }
}
