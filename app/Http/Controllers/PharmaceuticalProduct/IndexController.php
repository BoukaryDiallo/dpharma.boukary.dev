<?php

namespace App\Http\Controllers\PharmaceuticalProduct;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\PharmaceuticalProductRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    /**
     * @var PharmaceuticalProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @param PharmaceuticalProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        PharmaceuticalProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Affiche la liste des produits pharmaceutiques
     * 
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        // Récupération des produits avec leurs relations via le repository
        $products = $this->productRepository->all(['category'])
            ->map(function($product) {
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
                ];
            });

        // Récupération des catégories via le repository
        $categories = $this->categoryRepository->all()
            ->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name
                ];
            });

        return Inertia::render('PharmaceuticalProducts', [
            'products' => $products->all(),
            'categories' => $categories->all()
        ]);
    }
}
