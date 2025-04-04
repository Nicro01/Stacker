<?php

namespace App\Livewire\Panel;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class Profile extends Component
{
    public $user;

    public $status;

    public $rootPath;

    public $apiPort;

    public $port;

    public function mount()
    {
        $this->user = auth()->user();

        $this->port = $this->user->port;
    }

    #[On('update-status')]
    public function updateStatus(bool $status)
    {
        $this->status = $status;
    }

    public function changeRootPath()
    {
        $this->user->projects_path = $this->rootPath;
        $this->user->save();
    }

    public function changeApiPort()
    {
        $this->user->port = $this->apiPort;
        $this->user->save();
    }

    public function render()
    {
        return view('livewire.panel.profile')->extends('layouts.panel');
    }
}
