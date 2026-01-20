<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    protected $namespace = 'App\Http\Controllers';

    public const HOME = '/home';


    public function boot()
    {
        //

        parent::boot();
    }


    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));


        Route::middleware(['web'])
            ->prefix('rpclinica')
            ->namespace('App\\Http\\Controllers\\rpclinica')
            ->group(base_path('routes/rpclinica.php'));

        Route::middleware(['web'])
        ->prefix('app_rpclinic')
        ->namespace('App\\Http\\Controllers\\app_rpclinic')
        ->group(base_path('routes/app_rpclinic.php'));

        Route::middleware(['web'])
        ->prefix('faturamento')
        ->namespace('App\\Http\\Controllers\\faturamento')
        ->group(base_path('routes/faturamento.php'));

        Route::middleware(['web'])
        ->prefix('laudos')
        ->namespace('App\\Http\\Controllers\\laudos')
        ->group(base_path('routes/laudos.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
