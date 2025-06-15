<?php

namespace App\Http\Controllers\Pharmacist;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\PharmacistRepositoryInterface;

class PharmacistDeleteController extends Controller
{
    protected $pharmacistRepository;

    public function __construct(PharmacistRepositoryInterface $pharmacistRepository)
    {
        $this->pharmacistRepository = $pharmacistRepository;
    }
    /**
     * Supprime un pharmacien de la base de données
     *
     * @param  \App\Models\User  $pharmacist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(User $pharmacist)
    {
        // Vérifier si l'utilisateur peut supprimer ce pharmacien
        if (!auth()->user()->can('delete', $pharmacist)) {
            return redirect()
                ->route('pharmacists.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer ce pharmacien.');
        }

        // Vérifier si l'utilisateur essaie de se supprimer lui-même
        if (auth()->id() === $pharmacist->id) {
            return redirect()
                ->route('pharmacists.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        try {
            DB::beginTransaction();
            
            // Supprimer le pharmacien via le repository
            $this->pharmacistRepository->delete($pharmacist->id);
            
            DB::commit();
            
            return redirect()
                ->route('pharmacists.index')
                ->with('success', 'Le pharmacien a été supprimé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du pharmacien : ' . $e->getMessage());
            
            return redirect()
                ->route('pharmacists.index')
                ->with('error', 'Une erreur est survenue lors de la suppression du pharmacien.');
        }
    }
}
