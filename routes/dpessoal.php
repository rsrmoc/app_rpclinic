<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', 'Auth\LoginController@showLoginForm')->name('dpessoal.login');
Route::post('login-valida', 'Auth\LoginController@valida')->name('dpessoal.valida');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('dpessoal.logout');
 
 
Route::group([
    'middleware' => [
        'auth:dpessoal'
    ],
], function () {

    Route::get('/', 'Inicial@index')->name('inicio');
    Route::get('inicio', 'Inicial@index')->name('inicio');
    Route::get('usuario-alterar', 'Usuario@alterar')->name('dpessoal.usuario.alterar');
    Route::get('usuario-lista', 'Usuario@lista')->name('usuario.lista');
    Route::get('usuario-criar', 'Usuario@criar')->name('usuario.criar');
    Route::post('usuario-store', 'Usuario@store')->name('usuario.store');
    Route::get('usuario-alterar-usuario', 'Usuario@alterar_usuario')->name('usuario.alterar.usuario');

    

    
});