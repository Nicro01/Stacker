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
    public $stacks;
    public $configs;

    public $selectedStack;
    public $selectedConfigId;
    public $selectedConfig;

    public $projectPath;
    public $projectName;
    public $projectId;
    public $createRepository = false;
    public $auth = false;
    public $port;

    public $envFile;
    public $envJson;

    public $isOpen = false;
    public $isLoading = false;

    public $gitHubToken;
    public $gitHubUsername;

    public function mount(Package $package)
    {
        $this->package = $package;
        $this->stacks = $package->stacks;
        $this->configs = Auth::user()->configs;
        $this->selectedConfigId;

        $this->gitHubToken = decrypt(Auth::user()?->github_token);
        $this->gitHubUsername = Auth::user()?->github_username;

        // Debug padrão
        $this->projectPath = Auth::user()?->projects_path;
        $this->projectName;
        $this->selectedStack = $this->stacks->first()?->id;

        $this->port = Auth::user()?->port;
    }

    #[On('update-auth')]
    public function updatedAuth(bool $auth)
    {
        $this->auth = $auth;
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

    public function render()
    {
        return view('livewire.components.package-card');
    }
}
