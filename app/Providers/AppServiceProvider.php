<?php

namespace App\Providers;

use App\Domain\Factories\IRazaFactory;
use App\Domain\Factories\RazaFactory;
use App\Domain\Repositories\IAnimalRepository;
use App\Infrastructure\Persistence\EloquentAnimalRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(IRazaFactory::class, RazaFactory::class);
        $this->app->bind(IAnimalRepository::class, EloquentAnimalRepository::class);

        // Para usar DoctrineAnimalRepository en lugar de EloquentAnimalRepository,
        // solo se cambia la implementación en esta línea de binding.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
