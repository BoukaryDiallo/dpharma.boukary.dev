<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\PharmaceuticalProduct\PharmaceuticalProductIndexController;
use App\Http\Controllers\PharmaceuticalProduct\PharmaceuticalProductStoreController;
use App\Http\Controllers\PharmaceuticalProduct\PharmaceuticalProductShowController;
use App\Http\Controllers\PharmaceuticalProduct\PharmaceuticalProductUpdateController;
use App\Http\Controllers\PharmaceuticalProduct\PharmaceuticalProductDestroyController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])
    ->prefix('pharmaceutical-products')
    ->name('pharmaceutical-products.')
    ->group(function () {
        Route::get('/', [PharmaceuticalProductIndexController::class, '__invoke'])
            ->name('index');

        Route::post('/', [PharmaceuticalProductStoreController::class, 'store'])
            ->name('store');

        Route::put('/{pharmaceuticalProduct}', [PharmaceuticalProductUpdateController::class, 'execute'])
            ->name('update');

        Route::delete('/{pharmaceuticalProduct}', [PharmaceuticalProductDestroyController::class, 'destroy'])
            ->name('destroy');
    });

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
// require __DIR__.'/api.php';
