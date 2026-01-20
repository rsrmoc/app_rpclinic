<?php


namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\rpclinica\AgendamentoDocumento;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoDocumentos;
use App\Model\rpclinica\ComunicacaoSend;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\Usuario;
use App\Model\rpclinica\WebhookMessage;
use App\Model\rpclinica\WhastApi;
use App\Model\rpclinica\WhastReceive;
use App\Model\rpclinica\WhastRetornoAgenda;
use App\Model\rpclinica\WhastSend;
use App\Model\rpclinica\WhastSituacao;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Bibliotecas\SimpleXLSX;
use App\Bibliotecas\SimpleXLS;
use App\Model\rpclinica\Agenda;
use App\Model\rpclinica\AgendaEscalaHorario;

class Importar_escala_horario extends Controller
{

 
    public function horarios(Request $request)
    { 
        set_time_limit(50000); 
        
        $Query=DB::table('agenda_escala')->where('sn_ativo','S')->get();
        //dd($Query->toArray());
        foreach($Query as $valor){
 
            $hr_inicial = $valor->hr_inicial;
            $cd_inicial = str_replace(':', '.', $valor->hr_inicial); 
            $cd_final = str_replace(':', '.', $valor->hr_final); 
            $intervalo = $valor->intervalo;

            for ($hr = $cd_inicial; $hr <  $cd_final;) {

                $horaNova = strtotime("$hr_inicial + ".$intervalo." minutes"); 
                $horaNovaFormatada = date("H:i",$horaNova); 
                $HoraAgedamento = str_replace('.', ':', $hr); 
                $hr = str_replace(':', '.', $horaNovaFormatada);
                $hr_inicial = $horaNovaFormatada;
     
                $Array=array(
                 'cd_escala_agenda'=>$valor->cd_escala_agenda,
                 'cd_agenda'=>$valor->cd_agenda,
                 'cd_horario'=>$HoraAgedamento,
                 'cd_usuario'=>'ROTINA'
                );

                AgendaEscalaHorario::create($Array);
      
            }
         
        }

    }

  



}
