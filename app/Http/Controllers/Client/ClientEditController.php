<?php

namespace App\Http\Controllers\Client;

use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;

class ClientEditController extends BaseClientController
{
    public function __invoke(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $this->clientRepository->update($client->id, $request->validated());
        return redirect()->route('clients.index');
    }
}
