<?php

namespace App\Livewire\Panel\Inputs;

use Livewire\Component;

class Auth extends Component
{
    public $auth = false;

    public function mount($auth)
    {
        $this->auth = $auth;
    }

    public function updatedAuth($auth)
    {
        $this->dispatch('update-auth', $auth);
    }

    public function render()
    {
        return view('livewire.panel.inputs.auth');
    }
}
