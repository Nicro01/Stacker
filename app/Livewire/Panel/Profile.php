<?php

namespace App\Livewire\Panel;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    use WithFileUploads;

    public $user;

    public $status;

    public $rootPath;

    public $apiPort;

    public $port;

    public $background;

    public $profileImage;

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

    public function updatedProfileImage()
    {
        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

        if ($this->user->profile_image) {
            $cloudinary->adminApi()->deleteAssetsByPrefix($this->user->profile_image_public_id);
        }

        $tempPath = sys_get_temp_dir() . '/profile_' . time() . '.png';

        file_put_contents($tempPath, $this->profileImage->get());

        $uploadResponse = $cloudinary->uploadApi()->upload($tempPath, [
            'folder' => $this->user->name,
            'public_id' => 'profile_user_' . $this->user->id . '_' .  time(),
            'overwrite' => true,
        ]);

        $this->user->update([
            'profile_image' => $uploadResponse['secure_url'],
            'profile_image_public_id' => $uploadResponse['public_id'],
        ]);
    }

    public function updatedBackground()
    {
        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

        if ($this->user->background) {
            $cloudinary->adminApi()->deleteAssetsByPrefix($this->user->background_public_id);
        }

        $tempPath = sys_get_temp_dir() . '/background_' . time() . '.png';

        file_put_contents($tempPath, $this->background->get());

        $uploadResponse = $cloudinary->uploadApi()->upload($tempPath, [
            'folder' => $this->user->name,
            'public_id' => 'background_user_' . $this->user->id . '_' .  time(),
            'overwrite' => true,
        ]);

        $this->user->update([
            'background' => $uploadResponse['secure_url'],
            'background_public_id' => $uploadResponse['public_id'],
        ]);
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
