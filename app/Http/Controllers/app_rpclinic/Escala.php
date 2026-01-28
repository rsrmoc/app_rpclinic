<?php


namespace App\Http\Controllers\app_rpclinic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
use App\Model\rpclinica\EscalaMedica;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Model\rpclinica\Profissional;

class Escala extends Controller
{



    public function index(Request $request)
    {
  
        $userProfissional = auth()->guard('rpclinica')->user()->cd_profissional ?? null;
        $cd_profissional = $request->input('cd_profissional', $userProfissional);
        
        return view('app_rpclinic.escala.inicial', compact( 'cd_profissional'));
    }

    public function escalas(Request $request) {
        $request->validate([
            'cd_profissional' => 'required|integer',
            'data' => 'required|string|date_format:Y-m-d'
        ]);

        $agendamentos = EscalaMedica::with('localidade', 'especialidade', 'tipo_escala', 'profissional')
            ->where('cd_profissional', $request->cd_profissional)
            ->whereDate('dt_escala', $request->data)
            ->selectRaw("escala_medica.*, date_format(dt_escala, '%d/%m/%Y') data_agenda,
                date_format(hr_inicial, '%H:%i') hr_inicial,
                date_format(hr_final, '%H:%i') hr_final
            ")
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
        
        // MODO PADRÃO (AGENDAMENTOS): Busca dias com agendamentos
        $dates = EscalaMedica::where('cd_profissional', $request->cd_profissional)
            ->whereYear('dt_escala', $request->year)
            ->whereMonth('dt_escala', $request->month + 1)
            ->selectRaw('distinct date(dt_escala) as date')
            ->pluck('date');  
        return response()->json(['dates' => $dates]);
    }

    public function confirmarEscala(Request $request) {
        try {
            $request->validate([
                'cd_escala_medica' => 'required|integer'
            ]);

            $escala = EscalaMedica::findOrFail($request->cd_escala_medica);
            
            // Atualizar situação para "Confirmado"
            $escala->situacao = 'Confirmado';
            $escala->app_confirmacao_user = auth()->guard('rpclinica')->user()->cd_usuario ?? null;
            $escala->app_confirmacao_dt = now();
            $escala->save();

            return response()->json([
                'success' => true,
                'message' => 'Escala confirmada com sucesso',
                'escala' => $escala
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao confirmar escala: ' . $e->getMessage()
            ], 500);
        }
    }
}
