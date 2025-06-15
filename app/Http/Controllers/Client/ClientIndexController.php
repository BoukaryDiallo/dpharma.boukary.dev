<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientIndexController extends BaseClientController
{
    public function __invoke(Request $request)
    {
        $clients = $this->clientRepository->all();
        return Inertia::render('Clients', [
            'clients' => [ 'data' => $clients ]
        ]);
    }
}
