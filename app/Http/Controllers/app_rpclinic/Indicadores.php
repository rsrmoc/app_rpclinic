<?php

namespace App\Http\Controllers\app_rpclinic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\EscalaMedica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Indicadores extends Controller
{

    public function index(Request $request)
    {
        // Recebe mês e ano do filtro ou usa o mês/ano atual
        $mes = $request->get('mes', date('m'));
        $ano = $request->get('ano', date('Y'));
        $Data = $ano . '-' . $mes;
        
        // Pega profissional logado
        $user = auth()->guard('rpclinica')->user();
        $cdProfissional = $user->cd_profissional ?? 0;

   
 
        $pizzaQuery = EscalaMedica::selectRaw('escala_localidade.nm_localidade as label,count(*) as value' )
            ->join('escala_localidade', 'escala_localidade.cd_escala_localidade', '=', 'escala_medica.cd_escala_localidade')
            ->where('escala_medica.cd_profissional', $cdProfissional)
            ->whereRaw("escala_medica.dt_escala like '".$Data."%'")  
            ->groupBy('escala_localidade.nm_localidade')
            ->orderByRaw('2 desc')
            ->limit(10) // Top 10 convenios
            ->get(); 
        $pizzaLabels = $pizzaQuery->pluck('label');
        $pizzaSeries = $pizzaQuery->pluck('value'); 
        // Se vazia, envia flag para o front não bugar ou mostra vazio
        $hasDataPizza = $pizzaQuery->isNotEmpty();

        $pizzaQueryPessoa = EscalaMedica::selectRaw('escala_localidade.nm_localidade as label,sum(qtde_final) as value' )
            ->join('escala_localidade', 'escala_localidade.cd_escala_localidade', '=', 'escala_medica.cd_escala_localidade')
            ->where('escala_medica.cd_profissional', $cdProfissional)
            ->whereRaw("escala_medica.dt_escala like '".$Data."%'")  
            ->groupBy('escala_localidade.nm_localidade')
            ->orderByRaw('2 desc')
            ->limit(10) // Top 10 convenios
            ->get(); 
        $pizzaLabelsPessoa = $pizzaQueryPessoa->pluck('label');
        $pizzaSeriesPessoa = $pizzaQueryPessoa->pluck('value'); 
        // Se vazia, envia flag para o front não bugar ou mostra vazio
        $hasDataPizzaPessoa = $pizzaQueryPessoa->isNotEmpty();
  
        $pizzaSituacao = EscalaMedica::selectRaw('situacao as label,count(*) as value' ) 
            ->where('escala_medica.cd_profissional', $cdProfissional)
            ->whereRaw("escala_medica.dt_escala like '".$Data."%'")  
            ->groupBy('escala_medica.situacao')
            ->orderByRaw('2 desc')
            ->limit(10) // Top 10 convenios
            ->get(); 
        $pizzaSituacaoLabels = $pizzaSituacao->pluck('label');
        $pizzaSituacaoSeries = $pizzaSituacao->pluck('value');
        // Se vazia, envia flag para o front não bugar ou mostra vazio
        $hasDataPizzaSituacao = $pizzaSituacao->isNotEmpty();

 


        return view('app_rpclinic.indicadores.telas', compact( 'Data', 'pizzaLabels', 'pizzaSeries', 'pizzaLabelsPessoa', 'pizzaSeriesPessoa', 'pizzaSituacaoLabels', 'pizzaSituacaoSeries', 'hasDataPizza', 'hasDataPizzaPessoa', 'hasDataPizzaSituacao'));
    }

    public function agendamentos(Request $request) {
        $request->validate([
            'cd_profissional' => 'required|integer',
            'data' => 'required|string|date_format:Y-m-d'
        ]);

        $agendamentos = Agendamento::with('paciente', 'especialidade', 'tipo_atend', 'profissional')
            ->where('cd_profissional', $request->cd_profissional)
            ->whereDate('dt_agenda', $request->data)
            ->selectRaw("agendamento.*, date_format(agendamento.dt_agenda, '%d/%m/%Y') data_agenda")
            ->get();

        return response()->json(['agendamentos' => $agendamentos]);
    }
}
