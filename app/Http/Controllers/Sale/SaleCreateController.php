<?php

namespace App\Http\Controllers\Sale;

use App\Http\Requests\StoreSaleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class SaleCreateController extends BaseSaleController
{
    public function __invoke(StoreSaleRequest $request): RedirectResponse
    {
        // $data doit contenir : client_id, products[] (product_id, quantite), date_vente (optionnel)
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $this->saleRepository->create($data);
        return redirect()->route('sales.index');
    }
}
