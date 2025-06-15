<?php

namespace App\Http\Controllers\Client;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;

class ClientDeleteController extends BaseClientController
{
    public function __invoke(Client $client): RedirectResponse
    {
        $this->clientRepository->delete($client->id);
        return redirect()->route('clients.index');
    }
}
