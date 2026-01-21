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
    
    Route::get('paciente', 'Paciente@index')->name('app.paciente');
    Route::get('paciente-add', 'Paciente@create')->name('app.paciente.add');
    Route::get('paciente-edit/{idPaciente}', 'Paciente@edit')->name('app.paciente.edit');
    Route::get('paciente-hist/{idPaciente}', 'Paciente@hist')->name('app.paciente.hist');
    Route::get('paciente-doc/{idPaciente}', 'Paciente@doc')->name('app.paciente.doc');
    
    
    Route::get('agendamento', 'Agendamento@index')->name('app.agendamento');
    
    Route::get('consultorio', 'Consultorio@index')->name('app.consultorio');
    Route::get('consultorio-consulta/{idAgendamento}', 'Consultorio@consulta')->name('app.consultorio.consulta');
    Route::get('assinar', 'Consultorio@assinar_doc')->name('app.assinar');
    
    Route::get('perfil', 'Perfil@index')->name('app.perfil');

    
    Route::get('disponibilidade', 'Disponibilidade@index')->name('app.disponibilidade');
    
    Route::get('producao', 'Producao@index')->name('app.producao');

    Route::get('indicadores', 'Indicadores@index')->name('app.indicadores');
    
    Route::prefix('api')->group(function() {

        Route::post('perfil-update', 'Perfil@saveProfile');

        Route::post('agendamentos', 'Agendamento@agendamentos')->name('app.api.agendamentos');
        Route::post('agendamentos-datas', 'Agendamento@getDatesWithEvents')->name('app.api.agendamentos-datas');

        Route::post('documentos', 'Consultorio@documentos');

        Route::post('paciente-add', 'Paciente@createPaciente');
        Route::get('paciente-list', 'Paciente@pacientesLista');
        Route::post('paciente-edit/{idPaciente}', 'Paciente@updatePaciente');
        Route::post('paciente-add-doc', 'Paciente@saveDocPaciente');

        Route::post('consulta-paciente-historico', 'Consultorio@consultaPacienteHistorico')->name('app.api.consulta-paciente-historico');
        Route::post('consulta-paciente-anamnese', 'Consultorio@consultaPacienteAnamnese')->name('app.api.consulta-paciente-anamnese');
        Route::post('consulta-paciente-alertas', 'Consultorio@consultaPacienteAlertas')->name('app.api.consulta-paciente-alertas');
        Route::post('consulta-paciente-doc', 'Consultorio@consultaPacienteDoc')->name('app.api.consulta-paciente-doc');
        Route::get('consulta-docs/{cdAgendamento}', 'Consultorio@consultaDocumentosLista')->name('app.api.consulta-docs');

        Route::post('consulta-finalizar/{idAgendamento}', 'Consultorio@finalizarConsulta')->name('app.api.consulta-finalizar');
    });
});

Route::get('doc/download/{cdDoc}', 'Consultorio@downloadDoc');