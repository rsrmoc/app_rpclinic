<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\laudos\UsuarioLaudo;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoItens;
use App\Model\rpclinica\AgendamentoItensHist;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\Oft_formularios_imagens;
use App\Model\rpclinica\Oft_texto_padrao;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Produto;
use App\Model\rpclinica\WhastSend;
use App\Model\Support\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Storage;
use Throwable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;

class Atendimentos extends Controller
{
 

    public function index(Request $request)
    { 
        

        /*
        $stream = fopen('C:\img_oftalmo\3316\3tNy9ahD5TMAmb7P2fVDpF547tThDLGryrLyU2DC.pdf', 'r');
        $destinationPath = 'adonhiran/8888/Rqvplfq0PODiM5flVcRgj3N9EfQh5Iy4VhFRKpx9.pdf';

        $retorno=Storage::disk('s3')->put($destinationPath, $stream);
        if ($retorno) {
              
        }
        
        // If using a stream, make sure to close it
        if (is_resource($stream)) {
            fclose($stream);
        }
        */
        
        //$ArrayImg  = Storage::disk('s3')->temporaryUrl('adonhiran/6257/Rqvplfq0PODiM5flVcRgj3N9EfQh5Iy4VhFRKpx9.pdf',now()->addMinutes(15));
        /*
        return Storage::disk('s3')->response('adonhiran/6257/Rqvplfq0PODiM5flVcRgj3N9EfQh5Iy4VhFRKpx9.pdf', null, [
            'Content-Disposition' => 'inline; filename="foto_perfil.pdf"',
        ]);
        */
 
        /*
        $xx=Storage::disk('s3')->response('adonhiran/6257/Rqvplfq0PODiM5flVcRgj3N9EfQh5Iy4VhFRKpx9.pdf');
        dd($ArrayImg,$primeiros_tres = substr('adonhiran/6257/Rqvplfq0PODiM5flVcRgj3N9EfQh5Iy4VhFRKpx9.pdf', -3),
        Storage::disk('s3')->download('adonhiran/6257/Rqvplfq0PODiM5flVcRgj3N9EfQh5Iy4VhFRKpx9.pdf'),
        Storage::disk('s3')->mimeType('adonhiran/6257/Rqvplfq0PODiM5flVcRgj3N9EfQh5Iy4VhFRKpx9.pdf'), 
        Storage::disk('s3')->size('adonhiran/6257/Rqvplfq0PODiM5flVcRgj3N9EfQh5Iy4VhFRKpx9.pdf')
        );
        */
        
        $request['dti']= date("Y-m-d",strtotime('-3 day', strtotime(date('Y-m-d')))); 
        $request['dtf']=date('Y-m-d'); 
        $parametros['profissional'] = Profissional::where('sn_ativo', 'S')->orderBy('nm_profissional')->get();
        $parametros['opme'] = Produto::where('sn_ativo', 'S')->where('sn_opme', 'S')->orderBy('nm_produto')->get();
        $parametros['exame'] = Exame::where('tp_item', 'EX')->where('sn_ativo', 'S')->orderBy('nm_exame')->get();
        $parametros['convenio'] = Convenio::where('sn_ativo', 'S')->orderBy('nm_convenio')->get();


        return view('rpclinica.atendimentos.painel', compact('parametros', 'request'));
    }

    public function jsonPainel(Request $request): array
    {
        $itemsPerPage = ($request['itemsPerPage']) ? $request['itemsPerPage'] : 50;
        $request['query'] = Agendamento::Agendamentos($request)
        ->SelectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data_agenda") 
        ->orderBy("dt_agenda","desc") 
        ->paginate($itemsPerPage)->appends($request->query());  
        return $request->toArray();
    }
 
    public function add(Request $request)
    {
 
        $convenio = Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();
        $empresa = Empresa::find($request->user()->cd_empresa); 
        $exames = Exame::where('sn_ativo','S')->where('tp_item','EX')->orderBy("nm_exame")->get();
        $profissionais = Profissional::where('sn_ativo','S')->orderBy("nm_profissional")->get(); 
        return view('rpclinica.atendimentos.add_simplificado',compact('convenio', 'exames', 'profissionais','empresa' ));
    }


    public function create(Request $request)
    {
        $Campos = array( 
            'dt_atend' => 'required|date_format:Y-m-d',
            'cd_paciente' => 'required',
            'convenio' => 'required|exists:convenio,cd_convenio',
            "cpf" => "required",
            "cd_profissional" => 'required|integer|exists:profissional,cd_profissional', 
            "cd_exame" => 'required',
            "nasc" => "nullable",
            "celular" => "nullable",
            "cd_exame" => "required|array",
            "olho" => "required|array"
        ); 
  
        $empresa = Empresa::find($request->user()->cd_empresa); 
        if($empresa->atend_externo=='S'){
            $Campos['atendimento']='required|integer|unique:agendamento,cd_agendamento';
        } 
        $validated = $request->validate($Campos);
  
        try {
                
            $paciente =Paciente::where('cd_paciente',$request->cd_paciente)->first();
            if($request->SituacaoWhast){
                $SituacaoWhast = ($request->SituacaoWhast==true) ? 'S' : 'N';
                $DtWhast = date('Y-m-d H:i');
            }else{
                $SituacaoWhast = null;
                $DtWhast = null;
                if( $paciente ){
                    if($paciente->sn_whatsapp=='S'){ $SituacaoWhast = true; $DtWhast = $paciente->dt_whatsapp; }
                    if($paciente->sn_whatsapp=='N'){ $SituacaoWhast = false; $DtWhast = $paciente->dt_whatsapp; } 
                }

            }
            
            DB::beginTransaction(); 
 
            if( $paciente ){

                $paciente->update(
                    [  
                        'cpf' => $request->cpf,
                        'celular' => ($request->celular) ? $request->celular : null,
                        'sn_whatsapp' => $SituacaoWhast,
                        'dt_whatsapp' => $DtWhast,
                        'dt_nasc' => ( $request->nasc ) ? $request->nasc : null 
                    ] 
                );
            }else{
                $paciente = Paciente::create(
                    [ 
                        'nm_paciente' => $request->cd_paciente,
                        'cpf' => $request->cpf,
                        'sn_ativo' => 'S',
                        'dt_nasc' => ( $request->nasc ) ? $request->nasc : null,
                        'celular' => ($request->celular) ? $request->celular : null,
                        'sn_whatsapp' => $SituacaoWhast,
                        'dt_whatsapp' => $DtWhast,
                        'cd_usuario' => $request->user()->cd_usuario
                    ] 
                );
            }
              
            $dia = date('w', strtotime($request['dt_atend']));
            $dadosAtend=Empresa::where('cd_empresa',$request->user()->cd_empresa)->first();
   
            $agendamento=Agendamento::create([
                'cd_agendamento'=> $request['atendimento'],
                'sn_atend_avulso'=> 'S',
                'cd_paciente'=>$paciente->cd_paciente,
                'celular' => ($request->celular) ? $request->celular : null, 
                'whast' => $SituacaoWhast,
                'dt_whast' => $DtWhast,
                'cd_convenio'=>$request->convenio,
                'cd_especialidade'=>$dadosAtend['atend_espec'],
                'situacao'=>$dadosAtend['atend_situacao'],
                'sn_atendimento'=>'S', 
                'dt_atendimento'=>$request['dt_atend'],
                'usuario_atendimento'=>$request->user()->cd_usuario,
                'tipo'=>$dadosAtend['atend_tipo'],
                'data_horario'=>$request['dt_atend'].' 00:00',
                'dt_agenda'=>$request['dt_atend'],
                'hr_agenda'=>'00:00',
                'dia_semana'=>$dia,
                'cd_profissional'=>$request['cd_profissional'],
            ]);

            
            $qtde = $request['qtde'];
            $obs = $request['obs'];
            $olho = $request['olho'];
            foreach($request['cd_exame'] as $id => $exa){
                $qtde=1;
                if(isset($olho[$id]))
                    if($olho[$id]=='ambos') 
                        $qtde=2;

                $dadosExame=Exame::find($exa);
                $Valor=helperValorItem($exa,$request->convenio); 
                AgendamentoItens::create([
                    'cd_agendamento'=>$agendamento->cd_agendamento,
                    'cd_exame'=>$exa,
                    'cd_procedimento'=>($dadosExame->cod_proc) ? $dadosExame->cod_proc : null,
                    'vl_item'=> ($Valor) ? $Valor : null,
                    'dt_valor'=> ($Valor) ? date('Y-m-d H:i') : null,
                    'qtde'=> $qtde,
                    'olho'=>(isset($olho[$id])) ? $olho[$id] : null,
                    'obs_exame'=>(isset($obs[$id])) ? $obs[$id] : null,
                    'cd_usuario'=>$request->user()->cd_usuario,
                    'sn_laudo'=>0,
                    'situacao'=>'A',
                ]);
            }

            $senha = helperGerarSenha(); 
            UsuarioLaudo::create([
                'cd_usuario'=>$agendamento->cd_agendamento,
                'cd_agendamento'=>$agendamento->cd_agendamento,
                'cd_paciente'=>$agendamento->cd_paciente,
                'sn_ativo'=>'S',
                'password'=>bcrypt($senha),
                'senha_pura'=>$senha,
            ]);
 
            DB::commit();
            $dadosPaciente= Paciente::find($paciente->cd_paciente);
            $Mgs='<a style="color: #3c763d;" target="_blank" href="/rpclinica/recepcao-ficha/'.$agendamento->cd_agendamento.'"> <i class="fa fa-print"></i> </a> </span> <br> <span style="font-size: 11px;"> [&nbsp&nbsp <strong>ATENDIMENTO :</strong> ' . $agendamento->cd_agendamento .' &nbsp&nbsp] &nbsp -&nbsp ' . $dadosPaciente->nm_paciente . '  &nbsp&nbsp&nbsp  </span>';
            return redirect()->route('atendimento.add')->with([
                'success'=>'Atendimento cadastrado com sucesso!',
                'msg'=>$Mgs
            ]);
         
        } catch (Throwable $error) { 
            DB::rollback();
            return redirect()->route('atendimento.add')->with('error', 'Erro ao cadastrar Atendimento! <br>'.$error->getMessage());
        
        }

    }

    public function edit(Request $request, Agendamento $atendimento)
    {
  
        $atendimento->load('paciente','itens');
        $listaExame=null;
        foreach($atendimento->itens as $key){
            $listaExame[]=$key->cd_exame;
        } 

        $convenio = Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();
        $exames = Exame::where('sn_ativo','S')->where('tp_item','EX')->orderBy("nm_exame")->get();
        $profissionais = Profissional::where('sn_ativo','S')->orderBy("nm_profissional")->get();

        
        return view('rpclinica.atendimentos.edit_simplificado',
            compact('exames', 'profissionais','atendimento','listaExame','convenio'));
    }

    public function update(Request $request, Agendamento $atendimento)
    {
 
        $Campos = array(
            'atendimento' => 'required|integer|exists:agendamento,cd_agendamento',
            'dt_atend' => 'required|date_format:Y-m-d',
            'cd_paciente' => 'required',
            'convenio' => 'required|exists:convenio,cd_convenio',
            "cpf" => "required",
            "cd_profissional" => 'required|integer|exists:profissional,cd_profissional', 
            "nasc" => "nullable",
            "celular" => "nullable",
            "cd_exame" => "required|array",
            "olho" => "required|array"
        ); 
        $validated = $request->validate($Campos);

        try {
 
            $paciente =Paciente::where('cd_paciente',$request->cd_paciente)->first();
            if($request->SituacaoWhast){
                $SituacaoWhast = ($request->SituacaoWhast==true) ? 'S' : 'N';
                $DtWhast = date('Y-m-d H:i');
            }else{
                $SituacaoWhast = null;
                $DtWhast = null;
                if( $paciente ){
                    if($paciente->sn_whatsapp=='S'){ $SituacaoWhast = true; $DtWhast = $paciente->dt_whatsapp; }
                    if($paciente->sn_whatsapp=='N'){ $SituacaoWhast = false; $DtWhast = $paciente->dt_whatsapp; } 
                }

            }

            DB::beginTransaction(); 
              
            if( $paciente ){
                $paciente->update(
                    [  
                        'cpf' => $request->cpf,
                        'celular' => ($request->celular) ? $request->celular : null,
                        'sn_whatsapp' => $SituacaoWhast,
                        'dt_whatsapp' => $DtWhast,
                        'dt_nasc' => ( $request->nasc ) ? $request->nasc : null 
                    ] 
                );
            }else{
                $paciente = Paciente::create(
                    [ 
                        'nm_paciente' => $request->cd_paciente,
                        'cpf' => $request->cpf,
                        'sn_ativo' => 'S',
                        'dt_nasc' => ( $request->nasc ) ? $request->nasc : null,
                        'celular' => ($request->celular) ? $request->celular : null,
                        'sn_whatsapp' => $SituacaoWhast,
                        'dt_whatsapp' => $DtWhast,
                        'cd_usuario' => $request->user()->cd_usuario
                    ] 
                );
            }
            
            $dia = date('w', strtotime($request['dt_atend']));
            $dadosAtend=Empresa::where('cd_empresa',$request->user()->cd_empresa)->first();

            //Se não existir Usuario criado no portal ele cria
            if(UsuarioLaudo::find($atendimento->cd_agendamento)->count() == 0){
                
                $senha = helperGerarSenha(); 
                UsuarioLaudo::create([
                    'cd_usuario'=>$atendimento->cd_agendamento,
                    'cd_agendamento'=>$atendimento->cd_agendamento,
                    'cd_paciente'=>$atendimento->cd_paciente,
                    'sn_ativo'=>'S',
                    'password'=>bcrypt($senha),
                    'senha_pura'=>$senha,
                ]);
            }
             
  
            $atendimento->update([  
                'cd_paciente'=>$paciente->cd_paciente, 
                'cd_especialidade'=>$dadosAtend['atend_espec'],
                'situacao'=>$dadosAtend['atend_situacao'], 
                'celular' => ($request->celular) ? $request->celular : null,
                'whast' => $SituacaoWhast,
                'dt_whast' => $DtWhast,
                'cd_convenio'=>$request->convenio,
                'dt_atendimento'=>$request['dt_atend'], 
                'tipo'=>$dadosAtend['atend_tipo'],
                'data_horario'=>$request['dt_atend'].' 00:00',
                'dt_agenda'=>$request['dt_atend'],  
                'dia_semana'=>$dia,
                'cd_profissional'=>$request['cd_profissional'],
            ]);

            $cd_item = $request['cd_item'];   
            $exames = $request['cd_exame'];  
            $olho = $request['olho'];   
            $obs = $request['obs']; 
            $atendimento->load('paciente','itens'); 

            foreach($cd_item as $IDX => $item){

                if(empty($item)){

                    $qtde=1;
                    if(isset($olho[$IDX])){ if($olho[$IDX]=='ambos'){ $qtde=2; } }
                         
                    $exa=$exames[$IDX];
                    $dadosExame=Exame::find($exa);
                    $Valor=helperValorItem($exa,$request->convenio); 
                    AgendamentoItens::create([
                        'cd_agendamento'=>$atendimento->cd_agendamento,
                        'cd_exame'=>$exa,
                        'cd_procedimento'=>($dadosExame->cod_proc) ? $dadosExame->cod_proc : null,
                        'vl_item'=> ($Valor) ? $Valor : null,
                        'dt_valor'=> ($Valor) ? date('Y-m-d H:i') : null,
                        'qtde'=> $qtde,
                        'olho'=>(isset($olho[$IDX])) ? $olho[$IDX] : null,
                        'obs_exame'=>(isset($obs[$IDX])) ? $obs[$IDX] : null,
                        'cd_usuario'=>$request->user()->cd_usuario,
                        'sn_laudo'=>0,
                        'situacao'=>'A',
                    ]);

                }
            }
 
            foreach($atendimento->itens as $key){
                $listaExame[$key->cd_agendamento_item]=$key->cd_exame;
                $listaItens[]=$key->cd_agendamento_item;
            } 

            if(isset($listaItens)){
                foreach(array_diff($listaItens, $cd_item) as $Codigo){  
                    AgendamentoItens::find($Codigo)->delete();
                }
            }
  
            DB::commit();
            return redirect()->route('atendimento.add')->with('success', 'Atendimento cadastrado com sucesso!');
         
        } catch (Throwable $error) { 
            DB::rollback();
            return redirect()->route('atendimento.add')->with('error', 'Erro ao editar Atendimento! <br>'.$error->getMessage());
        
        }
    }

    public function atend(Request $request, $agendamento)
    {
        try {

            $retorno = Agendamento::find($agendamento); 
            if($retorno){
                $retorno->load('paciente');
            }
            return response()->json(['retorno' => $retorno]); 

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
 
    public function jsonDestroy(Request $request, Agendamento $atendimento)
    {
        try {

            if(empty(env('URL_IMG_EXAMES'))){
                return response()->json(['message' => 'Caminho filesystems não configurado!'], 500);
            }

            $result = AgendamentoItens::where('cd_agendamento',$atendimento->cd_agendamento)
            ->where('situacao','R')->count();
          
            if($result > 0 ){
                return response()->json(['message' => 'Existe exame(s) laudado(s) laudados para atendimento!'], 500);
            }else{
                
                $result = DB::transaction(function () use ($atendimento,$request){
                     
                    $imagens = Oft_formularios_imagens::where('cd_agendamento',$atendimento->cd_agendamento)->get(); 
                    foreach($imagens as $img){
                        $caminho_arquivo = env('URL_IMG_EXAMES')."/".$img->caminho_img; 
                        if (file_exists($caminho_arquivo)) {
                            unlink($caminho_arquivo);
                        } 
                    }
                    Oft_formularios_imagens::where('cd_agendamento',$atendimento->cd_agendamento)->delete();
                    $itens=AgendamentoItens::where('cd_agendamento',$atendimento->cd_agendamento)
                    ->get();
                    foreach($itens as $item){
                        AgendamentoItensHist::where('cd_agendamento_item',$item->cd_agendamento_item)
                        ->delete();

                        WhastSend::where('cd_agendamento_item',$item->cd_agendamento_item)
                        ->delete(); 
                    }
                    


                    AgendamentoItens::where('cd_agendamento',$atendimento->cd_agendamento)
                    ->delete();
                    $atendimento->delete();

                    //$tabela->criarLogExclusao($usuario_logado,'agendamento','atendimento',$dados['cd_agendamento']); 
                    $usuario_logado = $request->user(); 
                    return $atendimento->criarLogExclusao($usuario_logado,'agendamento','atendimento',$atendimento->cd_agendamento); 
                }); 

            } 
            return response()->json(['retorno' => $result]); 
            
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

 

    
    
  
}
