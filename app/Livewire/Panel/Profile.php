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

    public function mount()
    {
        $this->user = auth()->user();
    }

    #[On('get-status')]
    public function getApiStatus()
    {
        try {
            $response = Http::get('http://127.0.0.1:2025/api/status');

            if ($response->successful()) {
                $this->status = true;
            } else {
                $this->status = false;
            }

            Log::info('Status: ' . $response);
        } catch (\Exception $e) {
            $this->status = false;

            Log::error("Erro ao obter status da API: {$e->getMessage()}");
        }
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
