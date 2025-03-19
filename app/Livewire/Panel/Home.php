<?php

namespace App\Livewire\Panel;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Home extends Component
{
    public $projectPath;
    public $projectName;

    public function mount()
    {
        $this->projectPath = null;
        $this->projectName = null;
    }

    public function createProject()
    {
        // $this->projectPath = $this->projectPath ?? null;
        // $this->projectName = $this->projectName ?? null;


        $response = Http::timeout(60000)->post('http://127.0.0.1:2025/api/projects', [
            'projectPath' => $this->projectPath,
            'projectName' => $this->projectName,
        ]);

        return $response->json();
    }

    public function render()
    {
        return view('livewire.panel.home')->extends('layouts.auth');
    }
}
