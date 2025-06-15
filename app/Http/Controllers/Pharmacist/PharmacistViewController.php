<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PharmacistViewController extends Controller
{
    /**
     * Affiche la liste des pharmaciens
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', User::class);

        // Rediriger vers le contrôleur d'API pour la récupération des données
        return Inertia::render('Pharmacists', [
            'can' => [
                'create' => $request->user()->can('create', User::class),
                'update' => $request->user()->can('update', User::class),
                'delete' => $request->user()->can('delete', User::class),
            ]
        ]);
    }

    /**
     * Affiche le formulaire de création d'un pharmacien
     *
     * @return \Inertia\Response
     */
    public function create(): Response
    {
        $this->authorize('create', User::class);

        return Inertia::render('Pharmacists/Form', [
            'isEdit' => false,
            'pharmacist' => [
                'name' => '',
                'email' => '',
                'role' => 'pharmacist',
                'password' => '',
                'password_confirmation' => ''
            ],
            'roles' => [
                'pharmacist' => 'Pharmacien',
                'manager' => 'Gestionnaire',
                'admin' => 'Administrateur',
            ]
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'un pharmacien
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function edit(int $id): Response
    {
        $pharmacist = $this->pharmacistRepository->find($id);
        $this->authorize('update', $pharmacist);

        return Inertia::render('Pharmacists/Form', [
            'isEdit' => true,
            'pharmacist' => [
                'id' => $pharmacist->id,
                'name' => $pharmacist->name,
                'email' => $pharmacist->email,
                'role' => $pharmacist->role,
                'created_at' => $pharmacist->created_at,
                'updated_at' => $pharmacist->updated_at,
                'password' => '',
                'password_confirmation' => ''
            ],
            'roles' => [
                'pharmacist' => 'Pharmacien',
                'manager' => 'Gestionnaire',
                'admin' => 'Administrateur',
            ]
        ]);
    }
}
