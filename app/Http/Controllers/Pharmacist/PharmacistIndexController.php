<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Repositories\Contracts\PharmacistRepositoryInterface;

class PharmacistIndexController extends Controller
{
    /**
     * Affiche la liste paginée des pharmaciens (recherche locale côté front)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    protected $pharmacistRepository;

    public function __construct(PharmacistRepositoryInterface $pharmacistRepository)
    {
        $this->pharmacistRepository = $pharmacistRepository;
    }

    public function __invoke(Request $request)
    {
        $pharmacists = $this->pharmacistRepository->all();
        return Inertia::render('Pharmacists', [
            'pharmacists' => [
                'data' => $pharmacists,
            ],
            'roles' => [
                'pharmacist' => 'Pharmacien',
                'manager' => 'Gestionnaire',
                'admin' => 'Administrateur',
            ]
        ]);
    }
}
