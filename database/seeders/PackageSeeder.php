<?php

namespace Database\Seeders;

use App\Models\Package;
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
            'name' => 'Laravel',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.',
            'image' => 'laravel.png',
            'user_id' => 1,
        ];


        Package::create($package);
    }
}
