<?php

namespace App\Http\Controllers\PharmaceuticalProduct;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PharmaceuticalProductUpdateController extends BaseController
{
    public function execute(Request $request, int $id)
    {
        $product = $this->repository->find($id);

        // Formater la date d'expiration si elle est présente
        if ($request->has('expiration_date')) {
            try {
                $expirationDate = \Carbon\Carbon::parse($request->expiration_date)->format('Y-m-d');
                $request->merge(['expiration_date' => $expirationDate]);
            } catch (\Exception $e) {


            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'form' => 'required|string|in:tablet,capsule,syrup,injection,cream,drops,inhaler,other',
            'dosage' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'expiration_date' => ['required', 'date', 'date_format:Y-m-d', 'after:today'],
            'manufacturer' => 'required|string|max:255',
            'batch_number' => 'required|string|max:100|unique:pharmaceutical_products,batch_number,' . $id,
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // Mise à jour du slug si le nom a changé
        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);

            // Vérification de l'unicité du slug
            $count = 1;
            $originalSlug = $validated['slug'];
            $tempSlug = $validated['slug'];

            // Vérifier si le slug existe déjà pour un autre produit
            while ($this->repository->slugExists($tempSlug, $id)) {
                $tempSlug = $originalSlug . '-' . $count++;
            }
            $validated['slug'] = $tempSlug;
        }

        $this->repository->update($id, $validated);

        return to_route('pharmaceutical-products.index');
    }

    /**
     * Pour la compatibilité avec les routes invokables
     */
    public function __invoke(Request $request, int $id)
    {
        return $this->update($request, $id);
    }
}
