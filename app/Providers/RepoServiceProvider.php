<?php

namespace App\Providers;

use App\Repositories\Area\AreaInterface;
use App\Repositories\Area\AreaRepository;
use App\Repositories\Branch\BranchInterface;
use App\Repositories\Branch\BranchRepository;
use App\Repositories\City\CityInterface;
use App\Repositories\City\CityRepository;
use App\Repositories\Country\CountryInterface;
use App\Repositories\Country\CountryRepository;
use App\Repositories\Language\LanguageInterface;
use App\Repositories\Language\LanguageRepository;
use App\Repositories\ShippingCompany\ShippingCompanyInterface;
use App\Repositories\Translation\TranslationInterface;
use App\Repositories\Translation\TranslationRepository;
use App\Repositories\ShippingCompany\ShippingCompanyRepository;
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

        $this->app->bind(
            CityInterface::class,
            CityRepository::class
        );

        $this->app->bind(
            AreaInterface::class,
            AreaRepository::class
        );

        $this->app->bind(
            BranchInterface::class,
            BranchRepository::class
        );

        $this->app->bind(
            ShippingCompanyInterface::class,
            ShippingCompanyRepository::class
        );

        $this->app->bind(
            LanguageInterface::class,
            LanguageRepository::class
        );

        $this->app->bind(
            TranslationInterface::class,
            TranslationRepository::class
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
