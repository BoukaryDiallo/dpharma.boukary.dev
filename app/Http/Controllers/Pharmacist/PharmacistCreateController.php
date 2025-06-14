<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Http\Requests\StorePharmacistRequest;
use App\Repositories\Contracts\PharmacistRepositoryInterface;

class PharmacistCreateController extends Controller
{
    protected $pharmacistRepository;

    public function __construct(PharmacistRepositoryInterface $pharmacistRepository)
    {
        $this->pharmacistRepository = $pharmacistRepository;
    }
    public function create()
    {
        return Inertia::render('Pharmacists/Create');
    }

    public function store(StorePharmacistRequest $request)
    {
        $validated = $request->validated();

        $pharmacistData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'pharmacist',
        ];

        $this->pharmacistRepository->create($pharmacistData);

        return redirect()
            ->route('pharmacists.index')
            ->with('success', 'Pharmacien créé avec succès.');
    }
}
