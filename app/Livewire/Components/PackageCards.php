<?php

namespace App\Livewire\Components;

use App\Models\Package;
use App\Models\Stack;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;

class PackageCards extends Component
{

    public $projectPath;
    public $projectName;
    public $selectedStack;

    public $logs = [];
    public $projectId;
    public $isCreating = false;

    public $stacks;
    public $package;

    public $loading = false;

    public function mount($id)
    {
        $this->package = Package::where('id', $id)->first();
        $this->stacks = $this->package->stacks;
    }

    public function createProject()
    {
        $this->loading = true;
        $this->logs = [];


        $response = Http::timeout(0)->post('http://127.0.0.1:2025/api/projects', [
            'projectName' => $this->projectName,
            'projectPath' => $this->projectPath,
            'stack' => $this->selectedStack,
        ]);

        $this->projectId = $response->json()['projectId'];

        $this->dispatch('start-log-polling', projectId: $this->projectId);
    }


    #[On('stop-log-polling')]
    public function stopLogPolling()
    {
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.components.package-cards');
    }
}
