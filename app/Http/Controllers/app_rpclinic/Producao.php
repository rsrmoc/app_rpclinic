<?php


namespace App\Http\Controllers\app_rpclinic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Producao extends Controller
{

    public function index(Request $request)
    {
        return view('app_rpclinic.producao.inicial');
    }

    public function getProducoes(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cd_profissional' => 'required|integer',
                'data' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cd_profissional = $request->cd_profissional;
            $data = $request->data;
            
            // Extrai o mês e ano da data
            $month = date('m', strtotime($data));
            $year = date('Y', strtotime($data));

            // Busca as produções do profissional no mês/ano selecionado
            $producoes = DB::table('escala_medica')
                ->whereYear('dt_escala', $year)
                ->whereMonth('dt_escala', $month)
                ->where('cd_profissional', $cd_profissional)
                ->where('situacao', 'Finalizado')
                ->orderBy('dt_escala', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'producoes' => $producoes 
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar produções: ' . $e->getMessage()
            ], 500);
        }
    }

 
}
