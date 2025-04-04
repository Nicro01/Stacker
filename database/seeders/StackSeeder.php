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
                'package_id' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.',
                'image' => 'laravel.png',
            ],
            [
                'name' => 'TALL',
                'package_id' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.',
                'image' => 'tall.png',
                'inputs' => json_encode([
                    'auth' => true,
                ]),
            ],
            [
                'name' => 'Vue',
                'package_id' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.',
                'image' => 'vue.png',
            ],
            [
                'name' => 'React',
                'package_id' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.',
                'image' => 'laravext.png',
            ],
            // Node Stacks
            [
                'name' => 'React',
                'package_id' => 2,
                'description' => 'A JavaScript library for building user interfaces.',
                'image' => 'react.png',
                'inputs' => json_encode([
                    'auth' => true,
                ]),
            ],
            [
                'name' => 'Next',
                'package_id' => 2,
                'description' => 'The React framework for production with server-side rendering.',
                'image' => 'nextjs.png',
            ],
            [
                'name' => 'Vue',
                'package_id' => 2,
                'description' => 'The Progressive JavaScript Framework for building user interfaces.',
                'image' => 'vue.png',
            ],
            [
                'name' => 'Nuxt',
                'package_id' => 2,
                'description' => 'The Progressive JavaScript Framework for building user interfaces.',
                'image' => 'vue.png',
            ],
            [
                'name' => 'Express',
                'package_id' => 2,
                'description' => 'Minimalist web framework for Node.js applications.',
                'image' => 'express.png',
            ],
            [
                'name' => 'Vite',
                'package_id' => 2,
                'description' => 'The Progressive JavaScript Framework for building user interfaces.',
                'image' => 'vite.png',
            ],
        ];


        foreach ($stacks as $stack) {
            Stack::create($stack);
        }
    }
}
