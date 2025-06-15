<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ClientRepositoryInterface;

abstract class BaseClientController extends Controller
{
    /**
     * @var ClientRepositoryInterface
     */
    protected $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
}
