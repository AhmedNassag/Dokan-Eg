<?php

namespace App\Providers;

use App\Repositories\Country\CountryInterface;
use App\Repositories\Country\CountryRepository;
use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CountryInterface::class,
            CountryRepository::class
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
