<?php

namespace App\Livewire\Components;

use App\Models\Package;
use App\Models\Stack;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class PackageCards extends Component
{
    public $packages;

    public function mount()
    {
        $this->packages = Auth::user()->packages;
    }

    public function render()
    {
        return view('livewire.components.package-cards');
    }
}
