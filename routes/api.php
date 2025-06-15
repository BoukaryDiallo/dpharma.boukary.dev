<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PharmaceuticalProduct\PharmaceuticalProductIndexController;
use App\Http\Controllers\PharmaceuticalProduct\PharmaceuticalProductStoreController;
use App\Http\Controllers\PharmaceuticalProduct\PharmaceuticalProductShowController;
use App\Http\Controllers\PharmaceuticalProduct\PharmaceuticalProductUpdateController;
use App\Http\Controllers\PharmaceuticalProduct\PharmaceuticalProductDestroyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes API pour les pharmaciens
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Liste des pharmaciens avec pagination et recherche
    Route::get('/pharmacists', [PharmacistApiController::class, 'index']);
    
    // Détails d'un pharmacien
    Route::get('/pharmacists/{pharmacist}', [PharmacistApiController::class, 'show']);
    
    // Suppression d'un pharmacien
    Route::delete('/pharmacists/{pharmacist}', [PharmacistApiController::class, 'destroy']);
    
    // Activation/désactivation d'un pharmacien
    Route::post('/pharmacists/{pharmacist}/toggle-status', [PharmacistApiController::class, 'toggleStatus']);
});

// Routes pour les produits pharmaceutiques
Route::prefix('api/pharmaceutical-products')->group(function () {
    Route::get('/', PharmaceuticalProductIndexController::class);
    Route::post('/', PharmaceuticalProductStoreController::class);
    Route::get('/{pharmaceuticalProduct}', PharmaceuticalProductShowController::class);
    Route::put('/{pharmaceuticalProduct}', PharmaceuticalProductUpdateController::class);
    Route::delete('/{pharmaceuticalProduct}', PharmaceuticalProductDestroyController::class);
});
