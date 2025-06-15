<?php

namespace App\Http\Controllers\PharmaceuticalProduct;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PharmaceuticalProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class UpdateController extends Controller
{
    /**
     * @var PharmaceuticalProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param PharmaceuticalProductRepositoryInterface $productRepository
     */
    public function __construct(PharmaceuticalProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Afficher le formulaire d'édition
     * 
     * @param int $id
     * @return Response
     */
    public function edit(int $id): Response
    {
        $product = $this->productRepository->find($id);
        
        return Inertia::render('PharmaceuticalProducts/Edit', [
            'product' => $product
        ]);
    }

    /**
     * Mettre à jour un produit pharmaceutique
     * 
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // Formater la date d'expiration si elle est présente
        if ($request->has('expiration_date')) {
            try {
                $expirationDate = \Carbon\Carbon::parse($request->expiration_date)->format('Y-m-d');
                $request->merge(['expiration_date' => $expirationDate]);
            } catch (\Exception $e) {
                // Gérer l'erreur si nécessaire
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'form' => 'required|string|in:tablet,capsule,syrup,injection,cream,drops,inhaler,other',
            'dosage' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'manufacturer' => 'required|string|max:255',
            'expiration_date' => 'required|date|after:today',
            'batch_number' => 'required|string|max:100|unique:pharmaceutical_products,batch_number,' . $id,
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Mettre à jour le slug si le nom a changé
        $product = $this->productRepository->find($id);
        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);

            // Vérification de l'unicité du slug
            $originalSlug = $validated['slug'];
            $count = 1;
            $tempSlug = $validated['slug'];

            // Vérifier si le slug existe déjà pour un autre produit
            while ($this->productRepository->slugExists($tempSlug, $id)) {
                $tempSlug = $originalSlug . '-' . $count++;
            }
            $validated['slug'] = $tempSlug;
        }

        // Mettre à jour le produit via le repository
        $this->productRepository->update($id, $validated);

        return redirect()
            ->route('pharmaceutical-products.index')
            ->with('success', 'Produit pharmaceutique mis à jour avec succès.');
    }

    /**
     * Pour la compatibilité avec les routes invokables
     */
    public function __invoke(Request $request, int $id)
    {
        return $this->update($request, $id);
    }
}
