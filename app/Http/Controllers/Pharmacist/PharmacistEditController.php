<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePharmacistRequest;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Http\Controllers\BaseController;

class PharmacistEditController extends BaseController
{
    public function edit(User $pharmacist)
    {
        return Inertia::render('Pharmacists/Edit', [
            'pharmacist' => $pharmacist
        ]);
    }

    public function update(UpdatePharmacistRequest $request, User $pharmacist)
    {
        $validated = $request->validated();

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        // Ajout explicite du champ 'role' s'il est présent
        if (isset($validated['role'])) {
            $updateData['role'] = $validated['role'];
        }

        // Mettre à jour le mot de passe uniquement s'il est fourni
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Mettre à jour le rôle uniquement si l'utilisateur est admin
        if (isset($validated['role']) && auth()->user()->can('updateRole', $pharmacist)) {
            $updateData['role'] = $validated['role'];
        }

        $this->pharmacistRepository->update($pharmacist->id, $updateData);

        return redirect()
            ->route('pharmacists.index')
            ->with('success', 'Pharmacien mis à jour avec succès.');
    }
}
