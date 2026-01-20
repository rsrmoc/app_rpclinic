<?php


namespace App\Http\Controllers\laudos;
 
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;  
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
use App\Model\rpclinica\Paciente;

class PainelInicial extends Controller
{
    public function __construct()
    {
         
    }
    public function index(Request $request)
    { 
  
        $Atendimento=RpclinicaAgendamento::find($request->user('laudos')->cd_usuario);
 
        $Atendimento->load('itens.exame','convenio','profissional');
        $Paciente= Paciente::find($request->user('laudos')->cd_paciente);
        $Historico=RpclinicaAgendamento::where('cd_paciente',$request->user('laudos')->cd_paciente)
        ->whereRaw("cd_agendamento <> ".$request->user('laudos')->cd_agendamento)
        
        ->with('itens.exame','convenio','profissional')
        ->orderByRaw("dt_agenda desc")->get();
        //dd($Historico->toArray());
         
        return view('laudos.painel.lista', compact( 'Paciente', 'Atendimento', 'Historico' ));
    }

  
  
}
