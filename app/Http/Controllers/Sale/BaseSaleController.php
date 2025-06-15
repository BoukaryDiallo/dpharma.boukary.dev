<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\SaleRepositoryInterface;

abstract class BaseSaleController extends Controller
{
    /**
     * @var SaleRepositoryInterface
     */
    protected $saleRepository;

    public function __construct(SaleRepositoryInterface $saleRepository)
    {
        $this->saleRepository = $saleRepository;
    }
}
