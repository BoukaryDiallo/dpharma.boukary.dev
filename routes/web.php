<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Contrôleurs pour les produits pharmaceutiques
use App\Http\Controllers\PharmaceuticalProduct\IndexController as PharmaceuticalProductIndexController;
use App\Http\Controllers\PharmaceuticalProduct\ShowController as PharmaceuticalProductShowController;
use App\Http\Controllers\PharmaceuticalProduct\CreateController as PharmaceuticalProductCreateController;
use App\Http\Controllers\PharmaceuticalProduct\UpdateController as PharmaceuticalProductUpdateController;
use App\Http\Controllers\PharmaceuticalProduct\DeleteController as PharmaceuticalProductDeleteController;
use App\Http\Controllers\PharmaceuticalProduct\ToggleStatusController as PharmaceuticalProductToggleStatusController;
use App\Http\Controllers\PharmaceuticalProduct\PharmaceuticalProductDestroyController;

// Contrôleurs pour la gestion des pharmaciens
use App\Http\Controllers\Pharmacist\PharmacistIndexController;
use App\Http\Controllers\Pharmacist\PharmacistCreateController;
use App\Http\Controllers\Pharmacist\PharmacistShowController;
use App\Http\Controllers\Pharmacist\PharmacistEditController;
use App\Http\Controllers\Pharmacist\PharmacistDeleteController;
use App\Http\Controllers\Pharmacist\PharmacistToggleStatusController;

// Contrôleurs pour les clients
use App\Http\Controllers\Client\IndexController as ClientIndexController;
use App\Http\Controllers\Client\ShowController as ClientShowController;
use App\Http\Controllers\Client\CreateController as ClientCreateController;
use App\Http\Controllers\Client\EditController as ClientEditController;
use App\Http\Controllers\Client\DeleteController as ClientDeleteController;
use App\Http\Controllers\Client\ToggleStatusController as ClientToggleStatusController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Tableau de bord
    Route::get('dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');

    // Routes pour les produits pharmaceutiques
    Route::prefix('pharmaceutical-products')
        ->name('pharmaceutical-products.')
        ->group(function () {
            // Liste des produits
            Route::get('/', [PharmaceuticalProductIndexController::class, '__invoke'])
                ->name('index');

            // Formulaire de création
            Route::get('/create', [PharmaceuticalProductCreateController::class, 'create'])
                ->name('create');

            // Enregistrement d'un nouveau produit
            Route::post('/', [PharmaceuticalProductCreateController::class, 'store'])
                ->name('store');


            // Formulaire d'édition d'un produit
            Route::get('/{pharmaceuticalProduct}/edit', [PharmaceuticalProductUpdateController::class, 'edit'])
                ->name('edit');

            // Mise à jour d'un produit
            Route::put('/{pharmaceuticalProduct}', [PharmaceuticalProductUpdateController::class, 'update'])
                ->name('update');

            // Suppression d'un produit
            Route::delete('/{pharmaceuticalProduct}', [PharmaceuticalProductDeleteController::class, '__invoke'])
                ->name('destroy');

            // Activation/Désactivation d'un produit
            Route::get('/{pharmaceuticalProduct}/toggle-status', [PharmaceuticalProductToggleStatusController::class, '__invoke'])
                ->name('toggle-status');
        });

    // Routes pour la gestion des pharmaciens
    Route::prefix('pharmacists')
        ->name('pharmacists.')
        ->middleware('can:viewAny,App\\Models\\User')
        ->group(function () {
            // Liste des pharmaciens (Vue Inertia)
            Route::get('/', [PharmacistIndexController::class, '__invoke'])
                ->name('index');

            // Formulaire de création (Vue Inertia)
            Route::get('/create', [PharmacistIndexController::class, 'create'])
                ->name('create');

            // Enregistrement d'un nouveau pharmacien (API)
            Route::post('/', [PharmacistCreateController::class, 'store'])
                ->name('store');


            // Mise à jour d'un pharmacien (API)
            Route::put('/{pharmacist}', [PharmacistEditController::class, 'update'])
                ->name('update');

            // Suppression d'un pharmacien (API)
            Route::delete('/{pharmacist}', [PharmacistDeleteController::class, '__invoke'])
                ->name('destroy');
        });

    // Routes pour les clients
    Route::prefix('clients')
        ->name('clients.')
        ->group(function () {
            // Dedicated controllers for each action (all extend BaseController)
            Route::get('/', [\App\Http\Controllers\Client\ClientIndexController::class, '__invoke'])->name('index');
            Route::post('/', [\App\Http\Controllers\Client\ClientCreateController::class, '__invoke'])->name('store');
            Route::put('/{client}', [\App\Http\Controllers\Client\ClientEditController::class, '__invoke'])->name('update');
            Route::delete('/{client}', [\App\Http\Controllers\Client\ClientDeleteController::class, '__invoke'])->name('destroy');
        });

    // Sales CRUD
    Route::prefix('sales')
        ->name('sales.')
        ->group(function () {
            Route::get('/', [\App\Http\Controllers\Sale\SaleIndexController::class, '__invoke'])->name('index');
            Route::post('/', [\App\Http\Controllers\Sale\SaleCreateController::class, '__invoke'])->name('store');
            Route::put('/{sale}', [\App\Http\Controllers\Sale\SaleEditController::class, '__invoke'])->name('update');
            Route::delete('/{sale}', [\App\Http\Controllers\Sale\SaleDeleteController::class, '__invoke'])->name('destroy');
        });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
