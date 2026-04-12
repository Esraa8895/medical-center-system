<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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

    protected function mapModuleRoutes(): void
    {
        $routeFiles = array_merge(
            glob(base_path('app/Modules/routes.php')) ?: [],
            glob(base_path('app/Modules/*/routes.php')) ?: []
        );

        foreach ($routeFiles as $routeFile) {
            Route::prefix('api')
                ->middleware(['api'])
                ->group($routeFile);
        }
    }

    public function boot(): void
    {
        $this->mapModuleRoutes();
    }
}
