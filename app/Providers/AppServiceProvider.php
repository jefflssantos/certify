<?php

namespace App\Providers;

use App\Contracts\Credentials\MakerInterface;
use App\Services\CredentialMakers\BrowserMakerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(MakerInterface::class, BrowserMakerService::class);
    }
}
