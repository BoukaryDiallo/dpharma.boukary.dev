<?php

namespace App\Http\Controllers\Sale;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SaleIndexController extends BaseSaleController
{
    public function __invoke(Request $request)
    {
        $sales = $this->saleRepository->all();
        $products = \App\Models\PharmaceuticalProduct::all(['id', 'name', 'price']);
        $clients = \App\Models\Client::all(['id', 'nom']);
        return Inertia::render('Sales', [
            'sales' => [ 'data' => $sales ],
            'products' => [ 'data' => $products ],
            'clients' => [ 'data' => $clients ],
        ]);
    }
}
