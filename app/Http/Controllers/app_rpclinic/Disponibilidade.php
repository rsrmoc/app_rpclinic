<?php


namespace App\Http\Controllers\app_rpclinic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\EscalaDisponibilidade;
use App\Model\rpclinica\Procedimento;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Disponibilidade extends Controller
{

    public function index(Request $request)
    {
        return view('app_rpclinic.disponibilidade.inicial');
    }

    public function save(Request $request)
    {
        $request->validate([
            'dates' => 'required|array',
            'dates.*' => 'required|date_format:Y-m-d',
            'month' => 'required|integer',
            'year' => 'required|integer',   
            'cd_profissional' => 'required|integer'
        ]);

        
        
        try {
            
            $cdProfissional = auth()->guard('rpclinica')->user()->cd_profissional ?? null;
            $month = $request->input('month', date('m') - 1); // month vem 0-based do JS
            $year = $request->input('year', date('Y'));
            $dates = $request->input('dates', []);

            DB::beginTransaction();

                // Deletar datas antigas do período
                EscalaDisponibilidade::where('cd_profissional', $cdProfissional)
                    ->whereYear('dt_disponibilidade', $year)
                    ->whereMonth('dt_disponibilidade', $month + 1)
                    ->delete();

                // Inserir novas datas
                foreach ($dates as $date) {
                    EscalaDisponibilidade::create([
                        'cd_profissional' => $cdProfissional,
                        'dt_disponibilidade' => $date,
                        'cd_usuario' => $cdProfissional,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Disponibilidade salva com sucesso']);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getDates(Request $request)
    {
        try {
            $cdProfissional = auth()->guard('rpclinica')->user()->cd_profissional ?? null;
            $month = $request->input('month', date('m') - 1); // month vem 0-based do JS
            $year = $request->input('year', date('Y'));
 

            if (!$cdProfissional) {
                return response()->json([
                    'success' => false,
                    'dates' => []
                ]);
            }

            // Buscar datas salvas no período
            $dates = EscalaDisponibilidade::where('cd_profissional', $cdProfissional)
                ->whereYear('dt_disponibilidade', $year)
                ->whereMonth('dt_disponibilidade', $month + 1) // Converter 0-based para 1-based
                ->selectRaw('distinct date(dt_disponibilidade) as date')
                ->pluck('date')
                ->toArray();

            return response()->json([
                'success' => true,
                'dates' => $dates
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'dates' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteDates(Request $request)
    {
        try {
            $cdProfissional = auth()->guard('rpclinica')->user()->cd_profissional ?? null;
            $month = $request->input('month', date('m') - 1);
            $year = $request->input('year', date('Y'));

            if (!$cdProfissional) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profissional não identificado'
                ], 400);
            }

            DB::beginTransaction();

            // Deletar todas as datas do mês
            $deleted = EscalaDisponibilidade::where('cd_profissional', $cdProfissional)
                ->whereYear('dt_disponibilidade', $year)
                ->whereMonth('dt_disponibilidade', $month + 1)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Datas limpas com sucesso',
                'deleted_count' => $deleted
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar datas: ' . $e->getMessage()
            ], 500);
        }
    }

 
}
