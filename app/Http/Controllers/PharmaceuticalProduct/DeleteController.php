<?php

namespace App\Http\Controllers\PharmaceuticalProduct;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PharmaceuticalProductRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class DeleteController extends Controller
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
     * Supprime un produit pharmaceutique
     * 
     * @param int $id
     * @return RedirectResponse
     */
    public function __invoke(int $id): RedirectResponse
    {
        // Vérifier si le produit peut être supprimé (pas de commandes en cours, etc.)
        // if ($this->productRepository->hasPendingOrders($id)) {
        //     return back()->with('error', 'Impossible de supprimer ce produit car il est associé à des commandes en cours.');
        // }
        
        // Supprimer le produit via le repository
        $this->productRepository->delete($id);
        
        return Redirect::route('pharmaceutical-products.index')
            ->with('success', 'Le produit a été supprimé avec succès.');
    }
}
