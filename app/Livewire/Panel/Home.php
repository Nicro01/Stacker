<?php

namespace App\Livewire\Panel;

use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Home extends Component
{
    public $packages;

    public function mount()
    {
        $this->packages = Package::all();
    }

    public function render()
    {
        return view('livewire.panel.home')->extends('layouts.panel');
    }
}
