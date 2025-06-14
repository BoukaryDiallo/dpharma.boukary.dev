<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\PharmaceuticalProductRepositoryInterface;
use App\Repositories\Eloquent\PharmaceuticalProductRepository;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;

use App\Repositories\Contracts\PharmacistRepositoryInterface;
use App\Repositories\Eloquent\PharmacistRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
