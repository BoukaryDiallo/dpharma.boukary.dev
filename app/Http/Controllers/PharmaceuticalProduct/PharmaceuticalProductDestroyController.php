<?php

namespace App\Http\Controllers\PharmaceuticalProduct;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PharmaceuticalProductDestroyController extends BaseController
{
    /**
     * Supprime un produit
     */
    public function destroy(int $id)
    {
        $this->repository->delete($id);
        
        return Redirect::route('pharmaceutical-products.index')
            ->with('success', 'Produit supprimé avec succès');
    }

    /**
     * Pour la compatibilité avec les routes invokables
     */
    public function __invoke(int $id)
    {
        return $this->destroy($id);
    }
}
