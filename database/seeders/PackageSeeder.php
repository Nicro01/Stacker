<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $package = [
            [
                'name' => 'New Package',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.',
                'image' => 'new_package.png',
            ],
            [
                'name' => 'Laravel',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.',
                'image' => 'laravel.png',
            ],
        ];

        $users = User::all();

        foreach ($users as $user) {
            foreach ($package as $p) {
                $user->packages()->create($p);
            }
        }
    }
}
