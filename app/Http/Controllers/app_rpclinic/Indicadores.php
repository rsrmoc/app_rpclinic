<?php

namespace App\Http\Controllers\app_rpclinic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Indicadores extends Controller
{

    public function index(Request $request)
    {
        // Pega profissional logado
        $user = auth()->guard('rpclinica')->user();
        $cdProfissional = $user->cd_profissional ?? 0;

        // 1. KPI: Total de Atendimentos Hoje
        $totalHoje = Agendamento::where('cd_profissional', $cdProfissional)
            ->whereDate('dt_agenda', date('Y-m-d'))
            ->count();

        // Faturamento (Vou deixar fixo ou buscar se encontrar tabela, por enquanto foco nos graficos)
        // Se quiser implementar depois: Somar valor procedimentos realizados.

        // 2. Gráfico Pizza: Distribuição por Convênio (Ano Atual)
        // Join com tabela convenio para pegar o nome
        $pizzaQuery = Agendamento::select('convenio.nm_convenio as label', DB::raw('count(*) as value'))
            ->join('convenio', 'agendamento.cd_convenio', '=', 'convenio.cd_convenio')
            ->where('agendamento.cd_profissional', $cdProfissional)
            ->whereYear('agendamento.dt_agenda', date('Y'))
            ->groupBy('convenio.nm_convenio')
            ->orderByDesc('value')
            ->limit(6) // Top 6 convenios
            ->get();

        $pizzaLabels = $pizzaQuery->pluck('label');
        $pizzaSeries = $pizzaQuery->pluck('value');

        // Se vazia, envia flag para o front não bugar ou mostra vazio
        $hasDataPizza = $pizzaQuery->isNotEmpty();


        // 3. Gráfico Barras: Evolução Mensal (Ano Atual)
        $barQuery = Agendamento::select(
                DB::raw("DATE_FORMAT(dt_agenda, '%b') as label"),
                DB::raw("count(*) as value"),
                DB::raw("MONTH(dt_agenda) as mes_num")
            )
            ->where('cd_profissional', $cdProfissional)
            ->whereYear('dt_agenda', date('Y'))
            ->groupBy(DB::raw("MONTH(dt_agenda)"), DB::raw("DATE_FORMAT(dt_agenda, '%b')"))
            ->orderBy('mes_num')
            ->get();

        $barLabels = $barQuery->pluck('label');
        $barSeries = $barQuery->pluck('value');


        return view('app_rpclinic.indicadores.telas', compact('totalHoje', 'pizzaLabels', 'pizzaSeries', 'barLabels', 'barSeries', 'hasDataPizza'));
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
