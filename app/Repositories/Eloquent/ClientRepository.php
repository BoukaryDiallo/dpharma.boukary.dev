<?php

namespace App\Repositories\Eloquent;

use App\Models\Client;
use App\Repositories\Contracts\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    public function all()
    {
        return Client::all();
    }

    public function find($id)
    {
        return Client::findOrFail($id);
    }

    public function create(array $data)
    {
        return Client::create($data);
    }

    public function update($id, array $data)
    {
        $client = Client::findOrFail($id);
        $client->update($data);
        return $client;
    }

    public function delete($id)
    {
        $client = Client::findOrFail($id);
        return $client->delete();
    }
}
