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

class Disponibilidade extends Controller
{

    public function index(Request $request)
    {
        return view('app_rpclinic.disponibilidade.inicial');
    }

    public function save(Request $request)
    {
        // $dates = $request->input('dates');
        // $cd_profissional = $request->input('cd_profissional');
        
        // TODO: Implement actual saving logic to database
        
        return response()->json(['success' => true]);
    }

 
}
