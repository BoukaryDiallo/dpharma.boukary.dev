<?php

namespace App\Http\Controllers\Sale;

use App\Http\Requests\UpdateSaleRequest;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;

class SaleEditController extends BaseSaleController
{
    public function __invoke(UpdateSaleRequest $request, Sale $sale): RedirectResponse
    {
        // $data doit contenir : client_id, products[] (product_id, quantite), date_vente (optionnel)
        $this->saleRepository->update($sale->id, $request->validated());
        return redirect()->route('sales.index');
    }
}
