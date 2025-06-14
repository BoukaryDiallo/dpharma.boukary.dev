<?php

namespace App\Http\Controllers\PharmaceuticalProduct;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PharmaceuticalProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class CreateController extends Controller
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
     * Afficher le formulaire de création
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('PharmaceuticalProducts/Create');
    }

    /**
     * Enregistrer un nouveau produit pharmaceutique
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'form' => 'required|string|in:tablet,capsule,syrup,injection,cream,drops,inhaler,other',
            'dosage' => 'required|string|max:100',
            'manufacturer' => 'required|string|max:255',
            'expiration_date' => 'required|date|after:today',
            'batch_number' => 'required|string|max:100|unique:pharmaceutical_products,batch_number',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Générer un slug unique
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $count = 1;

        // Vérifier si le slug existe déjà
        while ($this->productRepository->slugExists($slug)) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Ajouter le slug aux données validées
        $validated['slug'] = $slug;

        // Créer le produit via le repository
        $this->productRepository->create($validated);

        return redirect()
            ->route('pharmaceutical-products.index')
            ->with('success', 'Produit pharmaceutique créé avec succès.');
    }
}
