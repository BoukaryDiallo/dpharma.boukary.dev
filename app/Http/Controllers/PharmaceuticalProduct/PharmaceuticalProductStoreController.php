<?php

namespace App\Http\Controllers\PharmaceuticalProduct;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class PharmaceuticalProductStoreController extends BaseController
{

    public function store(Request $request)
    {
        // Formater la date d'expiration si elle est présente
        if ($request->has('expiration_date')) {
            try {
                $expirationDate = \Carbon\Carbon::parse($request->expiration_date)->format('Y-m-d');
                $request->merge(['expiration_date' => $expirationDate]);
            } catch (\Exception $e) {
                // La validation échouera avec un message d'erreur approprié
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
            'batch_number' => 'required|string|max:100|unique:pharmaceutical_products,batch_number',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // Génération du slug à partir du nom
        $validated['slug'] = Str::slug($validated['name']);

        // Vérification de l'unicité du slug
        $count = 1;
        $originalSlug = $validated['slug'];
        while ($this->repository->slugExists($validated['slug'])) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        $product = $this->repository->create($validated);

        return to_route('pharmaceutical-products.index');
    }

    public function __invoke(Request $request)
    {
        return $this->store($request);
    }
}
