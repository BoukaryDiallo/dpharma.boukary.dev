<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PharmaceuticalProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PharmaceuticalProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les catégories
        $antibiotics = Category::where('name', 'Antibiotiques')->first();
        $painkillers = Category::where('name', 'Antidouleurs')->first();
        $antihistamines = Category::where('name', 'Antihistaminiques')->first();
        $vitamins = Category::where('name', 'Vitamines et suppléments')->first();

        $products = [
            // Antibiotiques
            [
                'name' => 'Amoxicilline 500mg',
                'form' => 'capsule',
                'dosage' => '500mg',
                'description' => 'Antibiotique à large spectre de la famille des pénicillines',
                'price' => 8.99,
                'stock_quantity' => 150,
                'manufacturer' => 'Laboratoire PharmaPlus',
                'batch_number' => 'AMX' . date('Ymd'),
                'expiration_date' => now()->addYear(2)->toDateString(),
                'requires_prescription' => true,
                'is_active' => true,
                'category_id' => $antibiotics->id,
            ],
            [
                'name' => 'Azithromycine 250mg',
                'form' => 'tablet',
                'dosage' => '250mg',
                'description' => 'Antibiotique macrolide à longue demi-vie',
                'price' => 12.50,
                'stock_quantity' => 85,
                'manufacturer' => 'BioPharma Inc.',
                'batch_number' => 'AZI' . date('Ymd'),
                'expiration_date' => now()->addYear(3)->toDateString(),
                'requires_prescription' => true,
                'is_active' => true,
                'category_id' => $antibiotics->id,
            ],

            // Antidouleurs
            [
                'name' => 'Paracétamol 1000mg',
                'form' => 'tablet',
                'dosage' => '1000mg',
                'description' => 'Antalgique et antipyrétique de niveau 1',
                'price' => 2.50,
                'stock_quantity' => 500,
                'manufacturer' => 'Doliprane',
                'batch_number' => 'PAR' . date('Ymd'),
                'expiration_date' => now()->addYear(3)->toDateString(),
                'requires_prescription' => false,
                'is_active' => true,
                'category_id' => $painkillers->id,
            ],
            [
                'name' => 'Ibuprofène 400mg',
                'form' => 'tablet',
                'dosage' => '400mg',
                'description' => 'Anti-inflammatoire non stéroïdien (AINS)',
                'price' => 3.20,
                'stock_quantity' => 320,
                'manufacturer' => 'Advil',
                'batch_number' => 'IBU' . date('Ymd'),
                'expiration_date' => now()->addYear(2)->toDateString(),
                'requires_prescription' => false,
                'is_active' => true,
                'category_id' => $painkillers->id,
            ],

            // Antihistaminiques
            [
                'name' => 'Cétirizine 10mg',
                'form' => 'tablet',
                'dosage' => '10mg',
                'description' => 'Antihistaminique H1 de deuxième génération',
                'price' => 5.75,
                'stock_quantity' => 200,
                'manufacturer' => 'Zyrtec',
                'batch_number' => 'CET' . date('Ymd'),
                'expiration_date' => now()->addYear(2)->toDateString(),
                'requires_prescription' => false,
                'is_active' => true,
                'category_id' => $antihistamines->id,
            ],

            // Vitamines
            [
                'name' => 'Vitamine D3 1000UI',
                'form' => 'capsule',
                'dosage' => '1000UI',
                'description' => 'Complément alimentaire à base de vitamine D3',
                'price' => 9.99,
                'stock_quantity' => 150,
                'manufacturer' => 'Vitabio',
                'batch_number' => 'VITD' . date('Ymd'),
                'expiration_date' => now()->addYear(3)->toDateString(),
                'requires_prescription' => false,
                'is_active' => true,
                'category_id' => $vitamins->id,
            ],
            [
                'name' => 'Vitamine C 500mg',
                'form' => 'tablet',
                'dosage' => '500mg',
                'description' => 'Comprimés effervescents à la vitamine C',
                'price' => 7.50,
                'stock_quantity' => 180,
                'manufacturer' => 'Vitabio',
                'batch_number' => 'VITC' . date('Ymd'),
                'expiration_date' => now()->addYear(2)->toDateString(),
                'requires_prescription' => false,
                'is_active' => true,
                'category_id' => $vitamins->id,
            ],
        ];

        foreach ($products as $product) {
            $product['slug'] = Str::slug($product['name']);
            PharmaceuticalProduct::create($product);
        }
    }
}
