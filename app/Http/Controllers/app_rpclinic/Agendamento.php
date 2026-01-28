<?php


namespace App\Http\Controllers\app_rpclinic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Procedimento;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Model\rpclinica\Profissional;

class Agendamento extends Controller
{



    public function index(Request $request)
    {
        $profissionais = Profissional::orderBy('nm_profissional')->get();
        $userProfissional = auth()->guard('rpclinica')->user()->cd_profissional ?? null;
        $cd_profissional = $request->input('cd_profissional', $userProfissional);
        
        return view('app_rpclinic.agendamento.inicial', compact('profissionais', 'cd_profissional'));
    }

    public function agendamentos(Request $request) {
        $request->validate([
            'cd_profissional' => 'required|integer',
            'data' => 'required|string|date_format:Y-m-d'
        ]);

        $agendamentos = RpclinicaAgendamento::with('paciente', 'especialidade', 'tipo_atend', 'profissional')
            ->where('cd_profissional', $request->cd_profissional)
            ->whereDate('dt_agenda', $request->data)
            ->selectRaw("agendamento.*, date_format(agendamento.dt_agenda, '%d/%m/%Y') data_agenda")
            ->get();

        return response()->json(['agendamentos' => $agendamentos]);
    }

    public function getDatesWithEvents(Request $request) {
        $request->validate([
            'cd_profissional' => 'required|integer',
            'month' => 'required|integer',
            'year' => 'required|integer',
            'tipo' => 'nullable|string' // Novo parâmetro opcional
        ]);

        // MODO DOCUMENTOS: Busca apenas dias com documentos
        if ($request->tipo === 'documentos') {
            $dates = \App\Model\rpclinica\AgendamentoDocumentos::query()
                ->whereYear('created_at', $request->year)
                ->whereMonth('created_at', $request->month + 1)
                ->where(function($q) use ($request) {
                    $q->where('cd_prof', $request->cd_profissional)
                      ->orWhereHas('agendamento', function($subQ) use ($request) {
                          $subQ->where('cd_profissional', $request->cd_profissional);
                      });
                })
                ->selectRaw('distinct date(created_at) as date')
                ->pluck('date');
            return response()->json(['dates' => $dates]);
        }

        // MODO PADRÃO (AGENDAMENTOS): Busca dias com agendamentos
        $dates = RpclinicaAgendamento::where('cd_profissional', $request->cd_profissional)
            ->whereYear('dt_agenda', $request->year)
            ->whereMonth('dt_agenda', $request->month + 1)
            ->selectRaw('distinct date(dt_agenda) as date')
            ->pluck('date');
 
        return response()->json(['dates' => $dates]);
    }
}
