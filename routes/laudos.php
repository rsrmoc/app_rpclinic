<?php


use Illuminate\Support\Facades\Route;

 

Route::get('/', 'Auth\LoginController@showLoginForm')->name('laudos.login');
 

Route::get('login', 'Auth\LoginController@showLoginForm')->name('laudos.login');
Route::post('login-valida', 'Auth\LoginController@valida')->name('laudos.valida');


Route::group([
    'middleware' => [ 
        'auth:laudos', 
    ],
], function () {
    Route::get('/', 'PainelInicial@index')->name('laudos.inicial');  
    Route::get('/inicial', 'PainelInicial@index')->name('laudos.inicial');   
    Route::get('logout', 'Auth\LoginController@logout')->name('laudos.logout');
    
});