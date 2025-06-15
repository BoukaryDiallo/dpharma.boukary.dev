<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Antibiotiques',
                'description' => 'Médicaments utilisés pour traiter les infections bactériennes',
            ],
            [
                'name' => 'Antidouleurs',
                'description' => 'Médicaments pour soulager la douleur',
            ],
            [
                'name' => 'Antihistaminiques',
                'description' => 'Traitement des allergies et réactions allergiques',
            ],
            [
                'name' => 'Vitamines et suppléments',
                'description' => 'Compléments alimentaires et vitamines',
            ],
            [
                'name' => 'Soins de la peau',
                'description' => 'Produits dermatologiques et soins cutanés',
            ],
            [
                'name' => 'Médicaments cardiovasculaires',
                'description' => 'Traitement des affections cardiaques et circulatoires',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
            ]);
        }
    }
}
