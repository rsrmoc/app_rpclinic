<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
        config(['database.connections.mysql.database' => 'rpclinic_castelo']);

        config(['app.url' => request()->root()]);
        /*
        $database = DB::connection('master')->table('databases_clients')->select('*')->where('domain', request()->getHost())->first();

        if($database){
            config(['database.default' => 'mysql']);
            config(['database.connections.mysql.host' => $database->host]);
            config(['database.connections.mysql.username' => $database->username]);
            config(['database.connections.mysql.password' => $database->password]);
            config(['database.connections.mysql.database' => $database->database]);

            config(['app.url' => request()->root()]);


        }else{
            // echo "fdsfsdfsdfsd";
            // exit;
            //eader("Location: https://rpsys.com.br/");
        }
        */
        
        Validator::extend('currency', function($attribute, $value, $parameters) {
            return preg_match("/^[\d.,]+$/", $value);
        });
    }
}
