<?php

namespace App\Livewire\Panel;

use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;

class Profile extends Component
{
    public $user;

    public $status;

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
        } catch (\Exception $e) {
            $this->status = false;
        }
    }

    public function render()
    {
        return view('livewire.panel.profile')->extends('layouts.panel');
    }
}
