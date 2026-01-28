<?php

use App\Http\Middleware\AuthAppBusiness;
use App\Http\Middleware\GuestAppBusiness;
use App\Http\Middleware\VerifyAppBusiness;
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

Route::get('/', function () {
    return redirect()->route('app.inicial');
});

Route::get('login', 'Login@index')->name('app.login')
    ->middleware([VerifyAppBusiness::class, GuestAppBusiness::class]);

Route::post('login-action', 'Login@login')->name('app.login.action');


Route::middleware([VerifyAppBusiness::class, AuthAppBusiness::class])->group(function() {

    Route::post('logout', 'Login@logout')->name('app.logout');

    Route::get('inicial', 'Inicial@index')->name('app.inicial');
    
    /* Paciente */
    Route::get('paciente', 'Paciente@index')->name('app.paciente');
    Route::get('paciente-add', 'Paciente@create')->name('app.paciente.add');
    Route::get('paciente-edit/{idPaciente}', 'Paciente@edit')->name('app.paciente.edit');
    Route::get('paciente-hist/{idPaciente}', 'Paciente@hist')->name('app.paciente.hist');
    Route::get('paciente-doc/{idPaciente}', 'Paciente@doc')->name('app.paciente.doc');
    
    /* Agendamento */
    Route::get('agendamento', 'Agendamento@index')->name('app.agendamento');

    /* Escala */
    Route::get('escala', 'Escala@index')->name('app.escala');
    
    /* Consultorio */
    Route::get('consultorio', 'Consultorio@index')->name('app.consultorio');
    Route::get('consultorio-consulta/{idAgendamento}', 'Consultorio@consulta')->name('app.consultorio.consulta');
    Route::get('assinar', 'Consultorio@assinar_doc')->name('app.assinar');
    
    /* Perfil */
    Route::get('perfil', 'Perfil@index')->name('app.perfil');
 
    /* Disponibilidade */
    Route::get('disponibilidade', 'Disponibilidade@index')->name('app.disponibilidade');
    
    /* Producao */
    Route::get('producao', 'Producao@index')->name('app.producao');

    /* Permissão */
    Route::get('not-permission', 'Inicial@permission')->name('app.permission');

    /* Página não encontrada */
    Route::get('not-found', 'Inicial@notFound')->name('app.not-found');

    /* Indicadores */
    Route::get('indicadores', 'Indicadores@index')->name('app.indicadores');
    Route::get('indicadores-escalas', 'Indicadores@index')->name('app.indicadores.escalas');
    
    Route::prefix('api')->group(function() {

        /* Perfil */
        Route::post('perfil-update', 'Perfil@saveProfile')->name('app.api.perfil-update');

        /* Agendamento */
        Route::post('agendamentos', 'Agendamento@agendamentos')->name('app.api.agendamentos'); 
        Route::post('agendamentos-datas', 'Agendamento@getDatesWithEvents')->name('app.api.agendamentos-datas');

        /* Escala */
        Route::post('escalas', 'Escala@escalas')->name('app.api.escalas'); 
        Route::post('escalas-datas', 'Escala@getDatesWithEvents')->name('app.api.escalas-datas');
        Route::post('escalas/confirmar', 'Escala@confirmarEscala')->name('app.api.escalas-confirmar');

        /* Disponibilidade */
        Route::post('disponibilidade-save', 'Disponibilidade@save')->name('app.api.disponibilidade-save');
        Route::get('disponibilidade-get', 'Disponibilidade@getDates')->name('app.api.disponibilidade-get');
        Route::delete('disponibilidade-delete', 'Disponibilidade@deleteDates')->name('app.api.disponibilidade-delete');

        /* Produção */
        Route::post('producoes', 'Producao@getProducoes')->name('app.api.producoes');

        /* Documentos */
        Route::post('documentos', 'Consultorio@documentos');

        /* Pacientes */
        Route::post('paciente-add', 'Paciente@createPaciente');
        Route::get('paciente-list', 'Paciente@pacientesLista');
        Route::post('paciente-edit/{idPaciente}', 'Paciente@updatePaciente');
        Route::post('paciente-add-doc', 'Paciente@saveDocPaciente');

        /* Consultório */
        Route::post('consulta-paciente-historico', 'Consultorio@consultaPacienteHistorico')->name('app.api.consulta-paciente-historico');
        Route::post('consulta-paciente-anamnese', 'Consultorio@consultaPacienteAnamnese')->name('app.api.consulta-paciente-anamnese');
        Route::post('consulta-paciente-alertas', 'Consultorio@consultaPacienteAlertas')->name('app.api.consulta-paciente-alertas');
        Route::post('consulta-paciente-doc', 'Consultorio@consultaPacienteDoc')->name('app.api.consulta-paciente-doc');
        Route::get('consulta-docs/{cdAgendamento}', 'Consultorio@consultaDocumentosLista')->name('app.api.consulta-docs');
        Route::post('consulta-finalizar/{idAgendamento}', 'Consultorio@finalizarConsulta')->name('app.api.consulta-finalizar');
 
    });
});

Route::get('doc/download/{cdDoc}', 'Consultorio@downloadDoc');