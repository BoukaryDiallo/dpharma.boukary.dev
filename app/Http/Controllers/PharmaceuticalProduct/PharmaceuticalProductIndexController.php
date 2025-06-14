<?php

namespace App\Http\Controllers\PharmaceuticalProduct;

use App\Models\Category;
use App\Models\PharmaceuticalProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PharmaceuticalProductIndexController extends BaseController
{
    /**
     * Affiche la liste des produits pharmaceutiques
     */
    public function __invoke(Request $request)
    {
        // Récupération de tous les produits avec la relation category
        $products = $this->repository->all(['category'])->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'category_id' => $product->category_id,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name
                ] : null,
                'form' => $product->form,
                'dosage' => $product->dosage,
                'price' => (float) $product->price,
                'stock_quantity' => (int) $product->stock_quantity,
                'expiration_date' => $product->expiration_date?->toDateString(),
                'manufacturer' => $product->manufacturer,
                'batch_number' => $product->batch_number,
                'requires_prescription' => (bool) $product->requires_prescription,
                'is_active' => (bool) $product->is_active,
                'created_at' => $product->created_at?->toDateTimeString(),
                'updated_at' => $product->updated_at?->toDateTimeString(),
                'deleted_at' => $product->deleted_at?->toDateTimeString(),
            ];
        });

        // Récupération des catégories
        $categories = Category::select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name
                ];
            });

        return Inertia::render('PharmaceuticalProducts', [
            'products' => $products->toArray(),
            'categories' => $categories->toArray()
        ]);
    }
}
