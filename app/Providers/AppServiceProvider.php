<?php

namespace App\Providers;

use App\Model\rpclinica\Usuario;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (env('REDIRECT_HTTPS')) {
            $url->forceScheme('https');
        }
         
        config(['database.default' => 'mysql']);
        config(['database.connections.mysql.host' => 'localhost']);
        config(['database.connections.mysql.username' => 'root']);
        config(['database.connections.mysql.password' => '']);
        config(['database.connections.mysql.database' => 'castelo']);
        config(['app.url' => request()->root()]);
 
        
        Validator::extend('currency', function($attribute, $value, $parameters) {
            return preg_match("/^[\d.,]+$/", $value);
        });
    }
}
