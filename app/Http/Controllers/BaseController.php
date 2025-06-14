<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Repositories\Contracts\PharmacistRepositoryInterface;

class BaseController extends Controller
{
    protected $pharmacistRepository;

    public function __construct(PharmacistRepositoryInterface $pharmacistRepository)
    {
        $this->pharmacistRepository = $pharmacistRepository;
    }
}
