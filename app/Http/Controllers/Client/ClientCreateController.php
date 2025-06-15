<?php

namespace App\Http\Controllers\Client;

use App\Http\Requests\StoreClientRequest;
use Illuminate\Http\RedirectResponse;

class ClientCreateController extends BaseClientController
{
    public function __invoke(StoreClientRequest $request): RedirectResponse
    {
        $this->clientRepository->create($request->validated());
        return redirect()->route('clients.index');
    }
}
