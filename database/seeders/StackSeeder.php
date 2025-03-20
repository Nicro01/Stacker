<?php

namespace Database\Seeders;

use App\Models\Stack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stacks = [
            [
                'name' => 'Laravel',
                'user_id' => 1,
                'package_id' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.',
                'image' => 'laravel.png',
            ],
            [
                'name' => 'TALL',
                'user_id' => 1,
                'package_id' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.',
                'image' => 'tall.png',
            ],
            [
                'name' => 'VILT',
                'user_id' => 1,
                'package_id' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.',
                'image' => 'vue.png',
            ],
            [
                'name' => 'React',
                'user_id' => 1,
                'package_id' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.',
                'image' => 'laravext.png',
            ],
        ];


        foreach ($stacks as $stack) {
            Stack::create($stack);
        }
    }
}
