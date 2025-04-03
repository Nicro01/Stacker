<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Config;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class PackageCard extends Component
{
    use WithFileUploads;

    public Package $package;
    public $stacks = [];
    public $configs = [];

    public $selectedStack;
    public $selectedConfigId;
    public $selectedConfig;

    public $projectPath;
    public $projectName;
    public $projectId;
    public $authTALL = false;

    public $envFile;
    public $envJson;

    public $isOpen = false;
    public $isLoading = false;

    public function mount(Package $package)
    {
        $this->package = $package;
        $this->stacks = $package->stacks;
        $this->configs = Auth::user()->configs;
        $this->selectedConfigId = $this->configs->first()?->id;

        // Debug padrão
        $this->projectPath = 'C:\laragon\www';
        $this->projectName = 'test-project';
        $this->selectedStack = $this->stacks->first()?->id;
    }

    public function updatedSelectedConfigId()
    {
        $this->selectedConfig = $this->configs->firstWhere('id', $this->selectedConfigId);
    }

    public function updatedEnvFile()
    {
        $this->createConfigFromEnv();
    }

    public function createConfigFromEnv()
    {
        $envContent = $this->envFile->get();
        $envArray = [];

        foreach (explode("\n", $envContent) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $envArray[trim($key)] = trim($value);
            }
        }

        $this->envJson = json_encode($envArray, JSON_PRETTY_PRINT);

        Auth::user()->configs()->create([
            'name' => $this->projectName ?? 'Env' . now(),
            'config' => $this->envJson,
        ]);

        $this->configs = Auth::user()->configs;
        $this->dispatch('close-modal');
    }

    // public function requestLaravelProject()
    // {
    //     $this->isLoading = true;

    //     $info = [
    //         'id' => $this->package->id,
    //         'projectPath' => $this->projectPath,
    //         'projectName' => $this->projectName,
    //         'selectedStack' => $this->selectedStack,
    //         'auth' => $this->authTALL,
    //     ];

    //     try {
    //         Http::post('http://127.0.0.1:2025/api/create-project', $info);
    //         $this->projectId = Http::get('http://127.0.0.1:2025/api/project-ids')->json()['projectIDs'][0] ?? null;
    //         $this->dispatch('start-log-polling', projectId: $this->projectId);
    //     } catch (\Throwable $e) {
    //         logger()->error('Erro ao criar projeto', ['error' => $e->getMessage()]);
    //     }

    //     $this->isLoading = false;
    // }

    public function render()
    {
        return view('livewire.components.package-card');
    }
}
