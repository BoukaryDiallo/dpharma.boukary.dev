<?php

namespace App\Http\Controllers\Sale;

use App\Models\Sale;
use Illuminate\Http\RedirectResponse;

class SaleDeleteController extends BaseSaleController
{
    public function __invoke(Sale $sale): RedirectResponse
    {
        $this->saleRepository->delete($sale->id);
        return redirect()->route('sales.index');
    }
}
