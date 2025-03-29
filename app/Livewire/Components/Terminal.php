<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Terminal extends Component
{
    public $logs = [];
    public $projectId;

    public function mount($projectId)
    {
        $this->projectId = $projectId;
    }


    public function pollLogs($projectId)
    {
        $response = Http::timeout(0)->get("http://127.0.0.1:2025/api/logs?id={$projectId}");

        if ($response->successful()) {
            $newLogs = $response->json()['logs'];

            $this->logs = $newLogs;

            if (in_array("complete", $newLogs)) {
                $this->dispatch('stop-log-polling');
            }
        }
    }

    public function render()
    {
        return view('livewire.components.terminal');
    }
}
