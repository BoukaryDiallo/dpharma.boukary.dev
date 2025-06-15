<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchPharmacistRequest;
use App\Http\Resources\PharmacistResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PharmacistApiController extends Controller
{
    /**
     * Affiche une liste paginée des pharmaciens
     *
     * @param  \App\Http\Requests\SearchPharmacistRequest  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(SearchPharmacistRequest $request): AnonymousResourceCollection
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');
        $role = $request->input('role', '');
        $sortField = $request->input('sortField', 'name');
        $sortDirection = $request->input('sortDirection', 'asc');
        
        $query = $this->pharmacistRepository->query()
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function($query) use ($role) {
                $query->where('role', $role);
            });
            
        // Ajout du tri
        $query->orderBy($sortField, $sortDirection);
        
        $pharmacists = $query->paginate($perPage);
        
        return PharmacistResource::collection($pharmacists);
    }

    /**
     * Affiche les détails d'un pharmacien
     *
     * @param  \App\Models\User  $pharmacist
     * @return \App\Http\Resources\PharmacistResource
     */
    public function show(User $pharmacist): PharmacistResource
    {
        $this->authorize('view', $pharmacist);
        return new PharmacistResource($pharmacist);
    }

    /**
     * Supprime un pharmacien
     *
     * @param  \App\Models\User  $pharmacist
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $pharmacist): JsonResponse
    {
        $this->authorize('delete', $pharmacist);
        
        try {
            $this->pharmacistRepository->delete($pharmacist->id);
            return response()->json(['message' => 'Pharmacien supprimé avec succès']);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du pharmacien : ' . $e->getMessage());
            return response()->json(['message' => 'Une erreur est survenue lors de la suppression'], 500);
        }
    }

    /**
     * Active ou désactive un pharmacien
     *
     * @param  \App\Models\User  $pharmacist
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(User $pharmacist): JsonResponse
    {
        $this->authorize('update', $pharmacist);
        
        try {
            $newStatus = !$pharmacist->is_active;
            $this->pharmacistRepository->update($pharmacist->id, ['is_active' => $newStatus]);
            
            $message = $newStatus ? 'Pharmacien activé avec succès' : 'Pharmacien désactivé avec succès';
            return response()->json([
                'message' => $message,
                'is_active' => $newStatus
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du changement de statut du pharmacien : ' . $e->getMessage());
            return response()->json(['message' => 'Une erreur est survenue'], 500);
        }
    }
}
