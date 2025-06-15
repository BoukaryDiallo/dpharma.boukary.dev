<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\PharmaceuticalProductRepositoryInterface;
use App\Repositories\Eloquent\PharmaceuticalProductRepository;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;

use App\Repositories\Contracts\PharmacistRepositoryInterface;
use App\Repositories\Eloquent\PharmacistRepository;

use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\Eloquent\ClientRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ClientRepositoryInterface::class,
            ClientRepository::class
        );

        $this->app->bind(
            PharmaceuticalProductRepositoryInterface::class,
            PharmaceuticalProductRepository::class
        );

        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            PharmacistRepositoryInterface::class,
            PharmacistRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\SaleRepositoryInterface::class,
            \App\Repositories\Eloquent\SaleRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configuration de la pagination par défaut
        \Illuminate\Pagination\Paginator::useBootstrapFive();
        
        // Définir la locale de l'application
        \Carbon\Carbon::setLocale(config('app.locale'));
        
        // Valider les numéros de téléphone
        \Illuminate\Support\Facades\Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9+\-\s]+$/', $value);
        });
    }
}
