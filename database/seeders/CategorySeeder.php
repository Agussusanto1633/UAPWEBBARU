<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cuci Mobil',
                'description' => 'Jasa pencucian mobil, detailing, dan perawatan kendaraan'
            ],
            [
                'name' => 'Service AC',
                'description' => 'Jasa perbaikan, maintenance, dan instalasi AC mobil dan ruangan'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
