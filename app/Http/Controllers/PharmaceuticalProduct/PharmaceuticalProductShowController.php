<?php

namespace App\Http\Controllers\PharmaceuticalProduct;

use Inertia\Inertia;
use Inertia\Response;

class PharmaceuticalProductShowController extends BaseController
{
    /**
     * Affiche les détails d'un produit
     */
    public function show(int $id): Response
    {
        $product = $this->repository->find($id, ['category']);
        
        return Inertia::render('PharmaceuticalProducts/Show', [
            'product' => $product->toArray(),
            'can' => [
                'update' => true, // À remplacer par vos règles d'autorisation
                'delete' => true, // À remplacer par vos règles d'autorisation
            ]
        ]);
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(int $id): Response
    {
        $product = $this->repository->find($id);
        
        return Inertia::render('PharmaceuticalProducts/Edit', [
            'product' => $product
        ]);
    }

    /**
     * Pour la compatibilité avec les routes invokables
     */
    public function __invoke(int $id)
    {
        return $this->show($id);
    }
}
