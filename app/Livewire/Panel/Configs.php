<?php

namespace App\Livewire\Panel;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Configs extends Component
{
    public $configs;

    public function mount()
    {
        $this->configs = Auth::user()->configs;
    }

    public function delete($id)
    {
        $config = Config::findOrFail($id);
        $config->delete();
        $this->configs = Auth::user()->configs()->get();
    }


    public function render()
    {
        return view('livewire.panel.configs')->extends('layouts.panel');
    }
}
