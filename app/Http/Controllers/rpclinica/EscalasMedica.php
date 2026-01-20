<?php

namespace App\Http\Controllers\rpclinica;
 
use App\Http\Controllers\Controller;
 
use App\Model\rpclinica\AgendamentoSituacao; 
use App\Model\rpclinica\Convenio; 
use App\Model\rpclinica\LocalAtendimento; 
use App\Model\rpclinica\Profissional; 
use App\Model\rpclinica\TipoAtendimento;   
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\EscalaDisponibilidade;
use App\Model\rpclinica\EscalaLocalidade;
use App\Model\rpclinica\EscalaMedica;
use App\Model\rpclinica\EscalaTipo; 

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;  
use Illuminate\Support\Facades\DB; 

class EscalasMedica extends Controller
{

    public function index(Request $request)
    { 
         
            $parametros['agenda'] = [];

            $parametros['profissionais'] = Profissional::where('sn_ativo','S')->where('sn_escala_medica','S')->orderBy('nm_profissional')->get(); 
            $parametros['localidade'] = EscalaLocalidade::where('sn_ativo','S')->orderBy('nm_localidade')->get();
            $parametros['tipo_escala'] = EscalaTipo::where('sn_ativo','S')->orderBy('nm_tipo_escala')->get();

            $parametros['local'] = LocalAtendimento::where('sn_ativo','S')->orderBy('nm_local')->get();
            $parametros['tipo'] = TipoAtendimento::where('sn_ativo','S')->orderBy('nm_tipo_atendimento')->get();
            $parametros['convenio'] = Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();
            $parametros['situacao'] = AgendamentoSituacao::orderBy('nm_situacao')->get();
            $parametros['confirmar'] = (AgendamentoSituacao::where('sn_ativo','S')->where('confirmar','S')->first())->cd_situacao;
            $parametros['cancelar'] = (AgendamentoSituacao::where('sn_ativo','S')->where('cancelar','S')->first())->cd_situacao;
            $parametros['situacao'] = AgendamentoSituacao::orderBy('nm_situacao')->get();
            $parametros['situacao-agend'] = AgendamentoSituacao::orderBy('nm_situacao')->where('agendamento','S')->get();
            $livre = (AgendamentoSituacao::where('sn_ativo','S')->where('livre','S')->first());
            $parametros['livre'] = $livre->icone.' '.$livre->nm_situacao;
            $parametros['class_livre'] = $livre->class;
            $convenios = Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();
            $empresa = Empresa::find($request->user()->cd_empresa);
            $request['obriga_cpf']=$empresa->obriga_cpf;
             
            return view('rpclinica.escalas_medica.painel', 
                   compact('parametros','request','convenios'));
         
 
    }


    public function json(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'data' => 'required|date_format:Y-m-d', 
            'local' => 'nullable|integer|exists:escala_localidade,cd_escala_localidade', 
            'profissional' => 'nullable|integer|exists:profissional,cd_profissional', 
            'tipo_escala' => 'nullable|integer|exists:escala_tipo,cd_escala_tipo',   
            'situacao' => 'nullable', 
        ] );
        if ($validator->fails()) { 
            return response()->json(['message' => [$validator->errors()->first()]], 500);
        }

        try { 
            
            $request['query'] = EscalaMedica::GetEscalasMedicas($request)
            ->selectRaw("escala_medica.*, date_format(dt_escala,'%d/%m/%Y') data, date_format(hr_inicial,'%H:%i') hri, date_format(hr_final,'%H:%i') hrf")
            ->orderByRaw("dt_escala,cd_escala_localidade,hr_inicial")->get();
            $header = EscalaMedica::Header($request)->first();
 
            

            $request['header'] = array(
                'agendado'=> ( isset($header->agendado) ) ? $header->agendado : 0,
                'confirmado'=> ( isset($header->confirmado) ) ? $header->confirmado : 0,
                'finalizado'=> ( isset($header->finalizado) ) ? $header->finalizado : 0,
                'pago'=> ( isset($header->pago) ) ? $header->pago : 0,

            ); 
            
            return response()->json(['request'=>$request->toArray()]);

        }
        catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }


    public function storeEscala(Request $request)
    {
          
        try { 
            
            $validator = Validator::make($request->all(),[  
                    'cd_escala' => 'nullable|integer|exists:escala_medica,cd_escala_medica',
                    'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                    'cd_localidade' => 'required|integer|exists:escala_localidade,cd_escala_localidade',  
                    'cd_tipo_escala' => 'nullable|integer|exists:escala_tipo,cd_escala_tipo',   
                    'dt_agenda' => 'required|date_format:Y-m-d',
                    'hr_inicio' => 'required|date_format:H:i',
                    'hr_fim' => 'required|date_format:H:i',  
                    'obs' => 'nullable|string',
                    'informativo' => 'nullable|string',
                    "situacao" => 'required|in:Agendado,Confirmado,Finalizado,Pago',
                   
            ] , [
             
                    'dt_agenda.required' => 'Data da Agenda não informada.',
                    'dt_agenda.date_format' => 'Data da Agenda não informada.',
                    'hr_inicio.date_format' => 'Data da Agenda não informada.',
                    'hr_inicio.required' => 'Data da Agenda não informada.',
                    'hr_fim.date_format' => 'Data da Agenda não informada.',
                    'hr_fim.required' => 'Data da Agenda não informada.',
                    'cd_localidade.required' => 'Localidade não informada.',
                    'cd_profissional.required' => 'Profissional não informada.', 
                    'situacao.required' => 'Situação não informada.', 
            ] 
            );
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);
            }
            $cd_dia = strtotime($request['dt_agenda']);
            $cd_dia = date('N', $cd_dia);  
            
            //Verifica Escala
            $retornoCheck = $this->checkEscala($request);
            if(!$retornoCheck['retorno']){
                return response()->json(['errors' => ['Esse profissional já está escalado em outra escala.'],'dados'=> [$retornoCheck]], 500);
            }

            DB::beginTransaction();
             
            EscalaMedica::create([
                'dt_escala'=>$request['dt_agenda'],
                'cd_dia'=>$cd_dia,
                'hr_inicial'=>$request['hr_inicio'],
                'hr_final'=>$request['hr_fim'],
                'cd_profissional'=>$request['cd_profissional'],
                'cd_escala_localidade'=>$request['cd_localidade'],
                'cd_escala_tipo'=>$request['cd_tipo_escala'],
                'qtde_escala'=>$request['qtde_escala'],
                'situacao'=>$request['situacao'],
                'obs'=>$request['obs'],
                'informativo'=>$request['informativo'],
                'cd_usuario_agenda'=>$request->user()->cd_usuario 
            ]);

            DB::commit();
             
            return $request->toArray();
           

        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['errors' => [$error->getMessage()]], 500);
        }
        
         
    }

    
    public function getProfissional(Request $request, Profissional $profissional)
    {
        
        $validator = Validator::make($request->all(),[ 'data' => 'nullable|date_format:Y-m-d' ], [ 'data.date_format' => 'Data da Agenda não informada.' ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }
        $request['disponibilidade']=null;
        $request['escalas']=null;
        if($request['data']){
            $dt = strtotime($request['data']);
            $dt = date('Y-m', $dt);  
            $request['escalas']=EscalaMedica::where('cd_profissional',$profissional->cd_profissional)->with('localidade')
            ->selectRaw("escala_medica.*, date_format(dt_escala,'%d/%m/%Y') data, date_format(hr_inicial,'%H:%i') hri, date_format(hr_final,'%H:%i') hrf")
            ->whereRaw("dt_escala like '".$dt."%'")->orderByRaw("dt_escala")->get(); 
            $disponibilidade=EscalaDisponibilidade::where('cd_profissional',$profissional->cd_profissional)
            ->selectRaw(" date_format(dt_disponibilidade,'%d/%m/%Y') data, dt_disponibilidade ")
            ->whereRaw("dt_disponibilidade like '".$dt."%'")->orderByRaw("dt_disponibilidade")->get();
            $relacaoDisp=null;
            foreach($disponibilidade as $val){
                $cd_dia = strtotime($val->dt_disponibilidade);
                $cd_dia = date('N', $cd_dia);  
                $relacaoDisp[]=array(
                    'data'=>$val->data,
                    'dia'=>helperDiaSemana($cd_dia)
                );
            }
            $request['disponibilidade']=$relacaoDisp;
 
        }

        return response()->json(['request'=>$request->toArray(),'profissional'=>$profissional->toArray() ]);
          
    }
 
    public function updateEscala(Request $request, EscalaMedica $escala)
    {
          
        try { 
             
            $validator = Validator::make($request->all(),[  
                    'cd_escala' => 'nullable|integer|exists:escala_medica,cd_escala_medica',
                    'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                    'cd_localidade' => 'required|integer|exists:escala_localidade,cd_escala_localidade',  
                    'cd_tipo_escala' => 'nullable|integer|exists:escala_tipo,cd_escala_tipo',   
                    'dt_agenda' => 'required|date_format:Y-m-d',
                    'hr_inicio' => 'required|date_format:H:i',
                    'hr_fim' => 'required|date_format:H:i',  
                    'obs' => 'nullable|string',
                    "situacao" => 'required|in:Agendado,Confirmado,Finalizado,Pago',
                   
            ] , [
             
                    'dt_agenda.required' => 'Data da Agenda não informada.',
                    'dt_agenda.date_format' => 'Data da Agenda não informada.',
                    'hr_inicio.date_format' => 'Hora inicial não informada.',
                    'hr_inicio.required' => 'Hora inicial não informada.',
                    'hr_fim.date_format' => 'Hora final não informada.',
                    'hr_fim.required' => 'Hora inicial não informada.',
                    'cd_localidade.required' => 'Localidade não informada.',
                    'cd_profissional.required' => 'Profissional não informada.', 
                    'situacao.required' => 'Situação não informada.', 
            ] 
            );
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);
            }

            //Verifica Escala
            $retornoCheck = $this->checkEscala($request);
            if(!$retornoCheck['retorno']){
                return response()->json(['errors' => ['Esse profissional já está escalado em outra escala.'],'dados'=> [$retornoCheck]], 500);
            }

            $cd_dia = strtotime($request['dt_agenda']);
            $cd_dia = date('N', $cd_dia);  
            
            DB::beginTransaction();
             
            $escala->update([
                'dt_escala'=>$request['dt_agenda'],
                'cd_dia'=>$cd_dia,
                'hr_inicial'=>$request['hr_inicio'],
                'hr_final'=>$request['hr_fim'],
                'cd_profissional'=>$request['cd_profissional'],
                'cd_escala_localidade'=>$request['cd_localidade'],
                'cd_escala_tipo'=>$request['cd_tipo_escala'],
                'qtde_escala'=>$request['qtde_escala'],
                'situacao'=>$request['situacao'],
                'obs'=>$request['obs'],
                'cd_usuario_agenda'=>$request->user()->cd_usuario 
            ]);

            DB::commit();
             
            return $request->toArray();
           

        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['errors' => [$error->getMessage()]], 500);
        }
          
    }
 
    public function checkEscala(Request $request)
    {

        try { 

            $validator = Validator::make($request->all(), [
                'dt_agenda' => 'required|date_format:Y-m-d',
                'hr_inicio' => 'required|date_format:H:i',
                'hr_fim' => 'required|date_format:H:i',
                'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                'cd_escala' => 'nullable|integer|exists:escala_medica,cd_escala_medica' 
            ]);

            if ($validator->fails()) {
                return ['retorno'=>false,'errors' => $validator->errors()->all()];
            }
            
            $horaI=EscalaMedica::whereRaw("dt_escala='".$request['dt_agenda']."'")->where("cd_profissional",$request['cd_profissional'])
            ->with('localidade')->selectRaw("escala_medica.*,date_format(dt_escala,'%d/%m/%Y') data");
            if($request['cd_escala']){
                $horaI=$horaI->where("cd_escala_medica","<>",$request['cd_escala']);
            }
            $horaI=$horaI->whereRaw("'".$request['hr_inicio']."' between  hr_inicial and hr_final ")->get();
            if(isset($horaI[0])){
                return ['retorno'=>false,'dados'=>$horaI->toArray()];
            }

            $horaF=EscalaMedica::whereRaw("dt_escala='".$request['dt_agenda']."'")->where("cd_profissional",$request['cd_profissional'])
            ->with('localidade')->selectRaw("escala_medica.*,date_format(dt_escala,'%d/%m/%Y') data");
            if($request['cd_escala']){
                $horaF=$horaF->where("cd_escala_medica","<>",$request['cd_escala']);
            }
            $horaF=$horaF->whereRaw("'".$request['hr_fim']."' between  hr_inicial and hr_final ")->get();
            if(isset($horaF[0])){
                return  ['retorno'=>false,'dados'=>$horaF->toArray()];
            }

            return ['retorno'=>true,'dados'=>null] ;

        }
        catch (Throwable $error) {
            DB::rollback();
            return  ['retorno'=>false,'errors' => [$error->getMessage()]];
        }
      

    }
 
}
