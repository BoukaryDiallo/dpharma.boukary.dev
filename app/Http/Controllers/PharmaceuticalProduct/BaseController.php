<?php

namespace App\Http\Controllers\PharmaceuticalProduct;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PharmaceuticalProductRepositoryInterface;

class BaseController extends Controller
{
    protected $repository;

    public function __construct(PharmaceuticalProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}
