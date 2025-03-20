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
    use WithFileUploads;

    public $projectPath;
    public $projectName;
    public $selectedStack;

    public $logs = [];
    public $projectId;
    public $isCreating = false;

    public $stacks;
    public $package;

    public $loading = false;

    public $envFile;
    public $envJson;

    public $configs;
    public $selectedConfig;

    protected $rules = [
        'envFile' => 'required|file|mimes:env,txt|max:1024',
    ];

    public function mount($id)
    {
        $this->package = Package::where('id', $id)->first();
        $this->configs = Auth::user()->configs;
        $this->stacks = $this->package->stacks;
    }

    public function createProject()
    {
        $this->loading = true;
        $this->logs = [];
        $configs = json_decode($this->selectedConfig['config']);

        $response = Http::timeout(0)->post('http://127.0.0.1:2025/api/projects', [
            'projectName' => $this->projectName,
            'projectPath' => $this->projectPath,
            'stack' => $this->selectedStack,
            'configs' => $configs,
        ]);

        $this->projectId = $response->json()['projectId'];

        $this->dispatch('start-log-polling', projectId: $this->projectId);
    }

    public function updatedEnvFile()
    {
        $this->createConfig();
    }

    public function createConfig()
    {
        $envContent = $this->envFile->get();

        $envArray = [];
        foreach (explode("\n", $envContent) as $line) {

            if (empty(trim($line))) continue;
            if (str_starts_with(trim($line), '#')) continue;

            if (str_contains($line, '=')) {
                list($key, $value) = explode('=', $line, 2);
                $envArray[trim($key)] = trim($value);
            }
        }

        $this->envJson = json_encode($envArray, JSON_PRETTY_PRINT);

        Auth::user()->configs()->create([
            'name' => $this->projectName ?? 'Env' . date('Y-m-d H:i:s'),
            'config' => $this->envJson,
        ]);

        $this->configs = Auth::user()->configs;

        $this->dispatch('close-modal');
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
