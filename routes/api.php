<?php

use App\Http\Controllers\rpclinica\Agendamento;
use App\Http\Controllers\rpclinica\AgendamentoFormulario;
use App\Http\Controllers\rpclinica\Consulta;
use App\Http\Controllers\rpclinica\Motivo;
use App\Http\Controllers\rpclinica\Pacientes;
use App\Http\Controllers\rpclinica\ProdutoLote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* WebHook */
Route::any('whast-webhookMessage', 'api\Whast@webhookMessage')->name('whast.webhookMessage');
Route::any('whast-webhookGroup', 'api\Whast@webhookGroup')->name('whast.webhookGroup');
Route::any('whast-webhookConnection', 'api\Whast@webhookConnection')->name('whast.webhookConnection');
Route::any('whast-webhookQrCode', 'api\Whast@webhookQrCode')->name('whast.webhookQrCode');
Route::any('whast-webhookMessageFromMe', 'api\Whast@webhookMessageFromMe')->name('whast.webhookMessageFromMe');

Route::any('whast-confirmaAgenda', 'api\Whast@confirmaAgenda')->name('whast.confirmaAgenda');
Route::any('whast-send-agenda', 'api\Whast@sendAgenda')->name('whast.send.agenda');

/* AMigo */
Route::any('amigo-paciente', 'api\Amigo@paciente')->name('amigo.paciente');
Route::any('amigo-anamnese', 'api\Amigo@anamnese')->name('amigo.anamnese');
Route::any('amigo-obs', 'api\Amigo@obs')->name('amigo.obs');
Route::any('amigo-receita', 'api\Amigo@receita')->name('amigo.receita');
Route::any('amigo-outros', 'api\Amigo@outros')->name('amigo.outros');
Route::any('amigo-import', 'api\Amigo@import')->name('amigo.import');
Route::any('amigo-atend', 'api\Amigo@atend')->name('amigo.atend');


/* Kentro WebHook */
Route::any('kentro-confirmacao-agenda/{tipo}', 'api\WhastKentro@confirAgendaRPclinic')->name('kentro.confirmacao.agenda');

/* Kentro OFICIAL WebHook */
Route::any('kentro-oficial-confirmacao-agenda', 'api\WhastKentro@confirAgendaKentroOficial')->name('kentro.oficial.confirmacao.agenda');

/* ApiMe WebHook */
Route::any('api-me-confirmacao-agenda/{key}', 'api\WebhookApiMe@webhookMessage')->name('apime.confirmacao.agenda');
Route::get('api-me-retorno-atendimento/{key}', 'api\WebhookApiMe@retornoAtendimento')->name('apime.retorno.atendimento');

/* Correção Agenda */
Route::get('importar_escala_horario', 'api\Importar_escala_horario@horarios')->name('importar._escala.horario');
