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
        $perPage = $request->input('per_page', 10);
        
        // Utilisation du repository avec chargement des relations
        $products = $this->repository->paginate($perPage, ['category']);
            
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
            'products' => $products,
            'categories' => $categories->toArray(),
            'filters' => $request->only(['search', 'per_page'])
        ]);
    }
}
