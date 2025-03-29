<?php

namespace App\Livewire\Panel;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Configs extends Component
{
    public $configs;

    public function mount()
    {
        $this->configs = Auth::user()->configs;
    }

    public function render()
    {
        return view('livewire.panel.configs')->extends('layouts.panel');
    }
}
