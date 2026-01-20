<?php

use App\Http\Controllers\rpclinica\Agendamento;
use App\Http\Controllers\rpclinica\AgendamentoDocumento;
use App\Http\Controllers\rpclinica\Agendas;
use App\Http\Controllers\rpclinica\Comunicacoes;
use App\Http\Controllers\rpclinica\Consulta;
use App\Http\Controllers\rpclinica\Consultorio; 
use App\Http\Controllers\rpclinica\Inicial;
use App\Http\Controllers\rpclinica\Motivo;
use App\Http\Controllers\rpclinica\Pacientes;
use App\Http\Controllers\rpclinica\ProdutoLote;
use App\Http\Controllers\rpclinica\Select2Controller;
use Illuminate\Support\Facades\Route;

Route::get('comunicacao-hook', 'Comunicacoes@hook')->name('comunicacao.hook');

Route::get('foo', function () {
    return 'Hello World';
});



Route::domain(config('app.url'))->group(function () {
 
    //painel de chamada
    Route::get('painel-chamada', 'painel_chamada\Painel@index')->name('painel.chamada'); 
    Route::get('painel-toten', 'painel_chamada\toten@index')->name('painel.toten'); 

    Route::group([
        'middleware' => [
            'auth:rpclinica',
            'primeiro_acesso',
            'permissoes_usuario', 
        ],
    ], function () {
    
        Route::get('foo2', function () {
            return 'Hello foo2';
        });
    
    });
    

});
