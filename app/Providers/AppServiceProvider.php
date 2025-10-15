<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar componentes manualmente si es necesario
        Blade::component('app-layout', \App\View\Components\AppLayout::class);
        Blade::component('dropdown', \App\View\Components\Dropdown::class);
        Blade::component('dropdown-link', \App\View\Components\DropdownLink::class);
    }
}