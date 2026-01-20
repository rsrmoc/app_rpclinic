<?php


namespace App\Http\Controllers\rpclinica;

use App\Bibliotecas\ApiWaMe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoDocumentos;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\Formulario;
use App\Model\rpclinica\FormularioHeaderFooter;
use App\Model\rpclinica\LocalAtendimento;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\PacienteDocumento;
use App\Model\rpclinica\PacienteEnvio;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\ProfissionalEspecialidade;
use App\Model\rpclinica\ProfissionalProcedimento;
use App\Model\rpclinica\Usuario;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Route;
use Throwable;

class Pacientes extends Controller
{

    public function index(Request $request)
    {
 
        $docProfissional=[];$convenios=[];$procedimentos=[];$especialidades=[];$locaisAtendimento=[];
        if ($request->query('b')) {
            $CPF='';
            if(trim( preg_replace('/[^0-9]/', '', $request->query('b')))){
                $CPF="or REGEXP_REPLACE(cpf, '[^0-9]', '') = '" . trim( preg_replace('/[^0-9]/', '', $request->query('b'))) . "'";
            }
            $pacientes = Paciente::with('convenio', 'agendamentos')
                               
                ->whereRaw(" ( upper(nm_paciente) like '". mb_strtoupper($request->query('b')) ."%' ".$CPF." ) ")
                //->where(db::raw("upper(nm_paciente)"),'like',mb_strtoupper($request->query('b')) . "%")
                //->whereRaw("( (  LIKE '%" .  ) or ( cd_paciente = '" . trim($request->query('b')) . "' ) or (   REGEXP_REPLACE(cpf, '[^0-9]', '') = '" . trim( preg_replace('/[^0-9]/', '', $request->query('b'))) . "') )")
                ->orderBy('created_at', 'desc')
                ->selectRaw("paciente.*, date_format(`dt_nasc`,'%d/%m/%Y') data_nasc")
                ->paginate(20);
                //dd($pacientes->toSql(),$request->query('b'),trim( preg_replace('/[^0-9]/', '', $request->query('b'))) );
        } else {
            $pacientes = Paciente::with('convenio', 'agendamentos')
                ->selectRaw("paciente.*, date_format(dt_nasc,'%d/%m/%Y') data_nasc")
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }
         
        $docProfissional=[];
        if ($request->user()->cd_profissional) {
            $convenios = Convenio::all();
            $procedimentosProfissional = ProfissionalProcedimento::with('procedimento', 'convenio')->where('cd_profissional', $request->user()->cd_profissional)->get();
            $especialidadesProfissional = ProfissionalEspecialidade::with('especialidade')->where('cd_profissional', $request->user()->cd_profissional)->get();

            $procedimentos = [];
            foreach ($procedimentosProfissional as $procedimento) {
                $procedimentos[] = $procedimento->procedimento;
            }

            $especialidades = [];
            foreach ($especialidadesProfissional as $especialidade) {
                $especialidades[] = $especialidade->especialidade;
            }

            $docProfissional= Formulario::where('cd_profissional',$request->user()->cd_profissional)
            ->where('tp_formulario','DOC')->orderBy('nm_formulario')->where('sn_ativo','S')
            ->orderBy('nm_formulario')->get();
            
            $locaisAtendimento = LocalAtendimento::all();

            return view('rpclinica.paciente.lista', compact('pacientes', 'convenios', 'procedimentos', 'especialidades', 
                                                            'locaisAtendimento','docProfissional'));
        }
        
        
        return view('rpclinica.paciente.lista', compact('pacientes', 'convenios', 'procedimentos', 'especialidades', 
        'locaisAtendimento','docProfissional'));
    }

    public function pacienteDoc(Request $request, Formulario $documento, Paciente $paciente)
    {
        try {
            if($request->user()->cd_profissional){
                $profissional=Profissional::find($request->user()->cd_profissional);
                $emp=Empresa::find($request->user()->cd_empresa);
                //$conteudo=($emp->tp_editor_html=='sim') ? $documento->conteudo : $documento->conteudo_text;
                $conteudo= $documento->conteudo;
                $retorno=camposInteligentesPac($conteudo,$paciente,$profissional);
                $documento['retorno'] = $retorno;
                return $documento->toArray();
            }else{
                return response()->json(['message' => 'Esse usuario não é permitido para essa ação!'], 500);
            }
            
        } catch (Exception $e) { 
            return response()->json(['message' => 'Erro no Documento. ' . $e->getMessage()], 500);
        }

    }

    public function getDocumento(Request $request, Paciente $paciente)
    {
        try {
            if($request->user()->cd_profissional){
                $documentos=PacienteDocumento::where('cd_paciente',$paciente->cd_paciente)
                ->selectRaw("paciente_documentos.*, date_format(created_at,'%d/%m/%Y %H:%i') data_hora") 
                ->with('profissional')
                ->orderBy('created_at','desc')->get();

                $envios=PacienteEnvio::where('cd_paciente',$paciente->cd_paciente)
                ->selectRaw("paciente_envios.*, date_format(created_at,'%d/%m/%Y %H:%i') data_hora") 
                ->with('usuario','paciente_documento')
                ->orderByRaw('created_at desc')->get();
                 
                return response()->json(['documento' => $documentos, 'envio' => $envios ]);
            }else{
                return response()->json(['message' => 'Usuario não tem permissão para essa ação!'], 500);
            }


        }
        catch(Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    
    public function storeDocumento(Request $request, Paciente $paciente)
    {
    
        $validator = Validator::make($request->post(), [
            'formulario' => 'sometimes|integer|exists:formulario,cd_formulario',
            'documento' => 'required|string',
            'titulo' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 500);
        }

        try {

            $empresa = Empresa::find($request->user()->cd_empresa); 
            if($request->cd_formulario){
                $formulario = Formulario::find($request->cd_formulario);
            }else{
                $formulario = Formulario::where('default','S')->first();
            }

            if(empty($formulario)){
                return response()->json(['message' => 'Sistema não esta configurado para essa ação! <br>[ Campo default ]'], 500);
            }
             
            if($request->cdDocumento){
                $documentoAgendamento = PacienteDocumento::where("cd_documento_paciente",$request->cdDocumento)->update([ 
                    'conteudo' => $request->documento,
                    'titulo' => $request->titulo,
                    'cd_formulario' => (isset($formulario->cd_formulario)) ? $formulario->cd_formulario : null,
                    'cd_usuario' => $request->user()->cd_usuario,
                    'cd_paciente' =>  $paciente->cd_paciente,
                    'cd_profissional' =>  $request->user()->cd_profissional, 
                ]);
                $codigo=$request->cdDocumento;
            }else{ 
                $documentoAgendamento = PacienteDocumento::create([ 
                    'conteudo' => $request->documento,
                    'titulo' => $request->titulo,
                    'cd_formulario' => (isset($formulario->cd_formulario)) ? $formulario->cd_formulario : null,
                    'cd_usuario' => $request->user()->cd_usuario,
                    'cd_paciente' =>  $paciente->cd_paciente,
                    'cd_profissional' =>  $request->user()->cd_profissional, 
                ]); 
                $codigo=$documentoAgendamento->cd_documento_paciente;
            }

            $documentos=PacienteDocumento::where('cd_paciente',$paciente->cd_paciente)
            ->selectRaw("paciente_documentos.*, date_format(created_at,'%d/%m/%Y %H:%i') data_hora")
            ->where('cd_profissional',$request->user()->cd_profissional)
            ->with('profissional')
            ->orderBy('created_at','desc')->get();
 
            return response()->json(['message' => 'Documento cadastrado!', 
                                     'request'=>$request->toArray(),  
                                     'documento' => $documentos,
                                     'codigo'=>$codigo
                                    ]);
        
        }
        catch(Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }

    }


    public function storeMsg(Request $request, Paciente $paciente)
    {
    
        $validator = Validator::make($request->post(), [
            'celular' => 'required',
            'msg' => 'required',
            'cdDocumento' => 'required|integer|exists:paciente_documentos,cd_documento_paciente',
            'titulo' => 'required|string'
        ]);


        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 500);
        }
        $request['msg']='S';
        $this->imprimirDocumento($request,$request['cdDocumento']);
        return $request->toArray();

        try {

            $empresa = Empresa::find($request->user()->cd_empresa); 
            if($request->cd_formulario){
                $formulario = Formulario::find($request->cd_formulario);
            }else{
                $formulario = Formulario::where('default','S')->first();
            }

            if(empty($formulario)){
                return response()->json(['message' => 'Sistema não esta configurado para essa ação! <br>[ Campo default ]'], 500);
            }
             
            if($request->cdDocumento){
                $documentoAgendamento = PacienteDocumento::where("cd_documento_paciente",$request->cdDocumento)->update([ 
                    'conteudo' => $request->documento,
                    'titulo' => $request->titulo,
                    'cd_formulario' => (isset($formulario->cd_formulario)) ? $formulario->cd_formulario : null,
                    'cd_usuario' => $request->user()->cd_usuario,
                    'cd_paciente' =>  $paciente->cd_paciente,
                    'cd_profissional' =>  $request->user()->cd_profissional, 
                ]);
                $codigo=$request->cdDocumento;
            }else{ 
                $documentoAgendamento = PacienteDocumento::create([ 
                    'conteudo' => $request->documento,
                    'titulo' => $request->titulo,
                    'cd_formulario' => (isset($formulario->cd_formulario)) ? $formulario->cd_formulario : null,
                    'cd_usuario' => $request->user()->cd_usuario,
                    'cd_paciente' =>  $paciente->cd_paciente,
                    'cd_profissional' =>  $request->user()->cd_profissional, 
                ]); 
                $codigo=$documentoAgendamento->cd_documento_paciente;
            }

            $documentos=PacienteDocumento::where('cd_paciente',$paciente->cd_paciente)
            ->selectRaw("paciente_documentos.*, date_format(created_at,'%d/%m/%Y %H:%i') data_hora")
            ->where('cd_profissional',$request->user()->cd_profissional)
            ->with('profissional')
            ->orderBy('created_at','desc')->get();
 
            return response()->json(['message' => 'Documento cadastrado!', 
                                     'request'=>$request->toArray(),  
                                     'documento' => $documentos,
                                     'codigo'=>$codigo
                                    ]);
        
        }
        catch(Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }

    }

    public function deleteDocumento(Request $request, Paciente $paciente, PacienteDocumento $documento)
    {
        try { 
             
            DB::transaction(function () use ($documento,$request){

                $documento->delete(); 

            });
 
            $documentos=PacienteDocumento::where('cd_paciente',$paciente->cd_paciente)
            ->selectRaw("paciente_documentos.*, date_format(created_at,'%d/%m/%Y %H:%i') data_hora")
            ->where('cd_profissional',$request->user()->cd_profissional)
            ->with('profissional')
            ->orderBy('created_at','desc')->get();
            
            return response()->json(['message' => 'Documento excluido com sucesso!','request' => $request,'documento' => $documentos]);

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function imprimirDocumento(Request $request,   PacienteDocumento $documento)
    {
        try { 
            $Empresa = Empresa::find($request->user()->cd_empresa);

            $documento->load('profissional','paciente','usuario'); 
            $dados['documento']=$documento->toArray();

            $relatorio['conteudo']=$documento['conteudo'];
            $relatorio['titulo']=$Empresa->laudo_titulo; //$documento['titulo'];
            $relatorio['paciente']=$documento['paciente']['nm_paciente'];
            $relatorio['dt_nasc']=$documento['paciente']['dt_nasc'];
            $relatorio['data']=$documento['created_at'];
            $relatorio['tp_assinatura']=$dados['documento']['profissional']['tp_assinatura'];
            $relatorio['assinatura']=$dados['documento']['profissional']['assinatura'];
            $relatorio['conselho']=$dados['documento']['profissional']['conselho'];
            $relatorio['crm']=$dados['documento']['profissional']['crm'];
            $relatorio['nm_profissional']=$dados['documento']['profissional']['nm_profissional'];
 
            $User=Usuario::whereRaw("cd_profissional=".$request->user()->cd_profissional)->first();
            if(!$User->nm_header_doc){
                $execultante['nome']=$documento->profissional?->nm_profissional;
            }else{
                $execultante['nome']=$User->nm_header_doc;
            }

            if(!$User->espec_header_doc){
                $execultante['espec']=null;
            }else{
                $execultante['espec']=$User->espec_header_doc;
            }

            if(!$User->conselho_header_doc){
                $execultante['conselho']=null;
            }else{
                $execultante['conselho']=$User->conselho_header_doc;
            }
            $execultante['logo']=$Empresa->logo;
            $execultante['header']=(!$request['header']) ? 'N' : $request['header'];
            $execultante['footer']=(!$request['footer']) ? 'N' : $request['footer'];
            $execultante['data']=(!$request['data']) ? 'N' : $request['data'];
            $execultante['sn_logo']=(!$request['logo']) ? 'N' : $request['logo'];
            $execultante['sn_assinatura'] = 'N';
            $execultante['sn_ocultar_titulo']= ($request['sn_ocultar_titulo']=='S') ? 'N' : 'S';  
            $execultante['assinatura']= $documento->profissional->assinatura;
            $execultante['tp_assinatura']= $documento->profissional->tp_assinatura; 
            $execultante['end_empresa']= $Empresa->end;
            $certPass = $request->senha;
            $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.impressao', compact('relatorio','execultante' ));
            if(empty($request['compartilhar'])){
                
                return $pdf->stream('Documento.pdf');  
                  
            }

            if($request['compartilhar']=='ZAP'){

                $validator = Validator::make($request->post(), [
                    'celular' => 'required',
                    'msg' => 'required' 
                ]);
         
                if ($validator->fails()) {
                    return response()->json(['message' => $validator->errors()->first()], 500);
                }

                $celular=HELPERformatPhone($request['celular']); 
                if(!preg_match("/\(?\d{2}\)?\s?\d{5}\-?\d{4}/", $celular)) {
                    return response()->json(['message' => 'Numero de celular informado inválido! '], 500);
                }

                $celular='55'.$celular;

                $pdf->setPaper('A4', 'portrait'); 
                $ARQ=$pdf->stream();  
                $api = new ApiWaMe(); 
                $retorno = $api->sendDocumentoBase64( $celular,$request['msg'],base64_encode($ARQ),'application/pdf');  
                $dados = json_decode($retorno['dados']);
                if($retorno['retorno']==true){  
                    if(isset($dados->status)){
                        if($dados->status==200){
                            PacienteEnvio::create([
                                'cd_paciente'=>$documento['cd_paciente'],
                                'cd_documento_paciente'=>$documento['cd_documento_paciente'],
                                'msg'=>$request['msg'],
                                'cd_usuario'=>$request->user()->cd_usuario,
                                'celular'=>$celular,
                                'id_msg'=>(isset($dados->data->key->id)) ? $dados->data->key->id : null  
                            ]);

                            $envios=PacienteEnvio::where('cd_paciente',$documento['cd_paciente'])
                            ->selectRaw("paciente_envios.*, date_format(created_at,'%d/%m/%Y %H:%i') data_hora") 
                            ->with('usuario','paciente_documento')
                            ->orderByRaw('created_at desc')->get();
                        }
                    } 
                    return ['retorno'=>true,'dados'=>$dados, 'envio'=>$envios];
                }else{
                    return ['retorno'=>false,'dados'=>$retorno];
                }
                 
            }
            
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function create(Request $request)
    {
        $convenios = Convenio::all();

        return view('rpclinica.paciente.add', compact('convenios'));
    }

    
    public function store(Request $request)
    {
        $empresa=Empresa::find($request->user()->cd_empresa);
        if($empresa->obriga_cpf=='sim'){ $Obriga="required|cpf"; }else{ $Obriga="nullable|cpf"; }

        $request->validate([
            "nome" => "required|string|max:255",
            "nome_social" => "nullable|string|max:255",
            "data_de_nascimento" => "required|date",
            "sexo" => 'nullable|in:H,M',
            "estado_civil" => "nullable|in:S,C,D,V",
            "rg" => "nullable",
            "cpf" => $Obriga,
            "cartao" => "nullable",
            "cartao_sus" => "nullable|string|max:100",
            "nome_da_mae" => "nullable|string|max:255",
            "nome_do_pai" => "nullable|string|max:255",
            "convenio" => "nullable|integer|exists:convenio,cd_convenio",
            "logradouro" => "nullable|string|max:255",
            "numero" => "nullable",
            "complemento" => "nullable|string|max:50",
            "bairro" => "nullable|string|max:50",
            "cidade" => "nullable|string|max:50",
            "uf" => "nullable|string|uf",
            "cep" => "nullable",
            "vip" => "nullable",
            "profissao" => "nullable",
            "telefone" => "nullable",
            "celular" => "nullable",
            "nm_responsavel" => "nullable",
            "cpf_responsavel" => "nullable|cpf",
        ]);

        try {

            if($empresa->valida_cpf=='sim'){

                $retorno = Paciente::whereRaw(" REGEXP_REPLACE(cpf, '[^0-9]', '') = '" . trim( preg_replace('/[^0-9]/', '', $request->cpf)) . "'")
                ->count();
                if($retorno>0){
                    return redirect()->back()->withInput()->withErrors(['error' => 'Esse CPF ( '.$request->cpf.' ) já esta cadastro na base de dados ']);
                }

            } 


            $paciente = Paciente::create([
                'nm_paciente' => $request->nome,
                'nome_social' => $request->nome_social,
                'cd_categoria' => $request->convenio,
                'cartao' => $request->cartao,
                'cartao_sus' => $request->cartao_sus,
                'dt_nasc' => $request->data_de_nascimento,
                'sexo' => $request->sexo,
                'estado_civil' => $request->estado_civil,
                'rg' => $request->rg,
                'profissao'=> $request->profissao,
                'cpf' => $request->cpf,
                'nm_mae' => $request->nome_da_mae, 
                'dt_nasc_mae' => $request->dt_nasc_mae,
                'celular_mae' => $request->celular_mae, 
                'nm_pai' => $request->nome_do_pai, 
                'dt_nasc_pai' => $request->dt_nasc_pai,
                'celular_pai' => $request->celular_pai,  
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'nm_bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'uf' => $request->uf,
                'vip' => $request->vip,
                'cep' => $request->cep,
                'fone' => $request->telefone,
                'celular' => $request->celular,
                'nm_responsavel' => $request->nm_responsavel,
                'cpf_responsavel' => $request->cpf_responsavel,
                'sn_ativo' => 'S',
                'cd_usuario' => $request->user()->cd_usuario,
            ]);


            if ($request->has('fotoCopy')) {
                $extension = substr($request->fotoCopy, strpos($request->fotoCopy, '/') + 1);
                $extension = substr($extension, 0, strpos($extension, ';'));
                $paciente->foto_tipo = $extension;
                $paciente->foto = base64_decode(substr($request->fotoCopy, strpos($request->fotoCopy, ',') + 1));
                $paciente->save();
            } else if ($request->hasFile('foto')) {
                $paciente->foto_tipo = $request->foto->extension();
                $paciente->foto = file_get_contents($request->foto->path());
                $paciente->save();
            }

            return redirect()->route('paciente.listar')->with('success', 'Paciente cadastrado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro ao cadastrar o usuário. ' . $e->getMessage()]);
        }
    }

    public function edit(Request $request, Paciente $paciente)
    {

        $prontuario =  DB::select("
        select * from (

            select anamnese conteudo, 'Anamnese' as nm_formulario,agendamento.dt_anamnese,
            date_format(agendamento.dt_anamnese,'%d/%m/%Y %H:%i') data,
            profissional.nm_profissional,profissional.crm,usuarios.nm_usuario,
            datediff(curdate(), date(agendamento.dt_anamnese)) diferenca,
            agendamento.cd_agendamento codigo,'anamnese' as tipo,agendamento.cd_agendamento
            from agendamento
            left join usuarios on usuarios.cd_usuario=agendamento.usuario_anamnese
            left join profissional on profissional.cd_profissional=agendamento.cd_profissional
            where cd_paciente=" . $paciente->cd_paciente . " and ifnull(anamnese,'') <> ''
            and ifnull(agendamento.deleted_at,'')=''


            union all

            select agendamento_documentos.conteudo, nm_formulario,agendamento.dt_anamnese,
            date_format(agendamento_documentos.created_at,'%d/%m/%Y %H:%i') data,
            profissional.nm_profissional,profissional.crm,usuarios.nm_usuario,
            datediff(curdate(), date(agendamento_documentos.created_at)) diferenca,
            agendamento_documentos.cd_documento codigo,'documento' as tipo,agendamento.cd_agendamento
            from  agendamento_documentos
            left join agendamento on agendamento_documentos.cd_agendamento=agendamento.cd_agendamento
            left join usuarios on usuarios.cd_usuario=agendamento_documentos.cd_usuario
            left join profissional on profissional.cd_profissional=agendamento.cd_profissional
            where ifnull(agendamento_documentos.cd_pac,agendamento.cd_paciente)=" . $paciente->cd_paciente . "
 
        ) xx
        order by dt_anamnese desc
        ");

        $convenios = Convenio::all();

        return view('rpclinica.paciente.edit', compact('convenios', 'paciente', 'prontuario'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $empresa=Empresa::find($request->user()->cd_empresa);
        if($empresa->obriga_cpf=='sim'){ $Obriga="required|cpf"; }else{ $Obriga="nullable|cpf"; }

        $request->validate([
            "nome" => "required|string|max:255",
            "nome_social" => "nullable|string|max:255",
            "data_de_nascimento" => "required",
            "sexo" => 'nullable|in:H,M',
            "estado_civil" => "nullable|in:S,C,D,V",
            "rg" => "nullable",
            "cpf" => $Obriga,
            "profissao" => "nullable",
            "cartao_sus" => "nullable|string|max:100",
            "cartao" => "nullable|numeric",
            "nome_da_mae" => "nullable|string|max:255",
            "nome_do_pai" => "nullable|string|max:255",
            "convenio" => "nullable|integer|exists:convenio,cd_convenio",
            "cartao" => "nullable",
            "logradouro" => "nullable|string|max:255",
            "numero" => "nullable|string",
            "complemento" => "nullable|string|max:50",
            "bairro" => "nullable|string|max:50",
            "cidade" => "nullable|string|max:50",
            "uf" => "nullable|string|uf",
            "cep" => "nullable",
            "vip" => "nullable",
            "telefone" => "nullable",
            "celular" => "nullable",
            "nm_responsavel" => "nullable",
            "cpf_responsavel" => "nullable",
        ]);

        try {

            if($empresa->valida_cpf=='sim'){

                $retorno = Paciente::whereRaw(" REGEXP_REPLACE(cpf, '[^0-9]', '') = '" . trim( preg_replace('/[^0-9]/', '', $request->cpf)) . "'")
                ->whereNotIn([$request['paciente']])->count();
                if($retorno>0){
                    
                    return response()->json([
                        'message' => 'Esse CPF ( '.$request->cpf.' ) já esta cadastro na base de dados ',
                        'error' => 'Esse CPF ( '.$request->cpf.' ) já esta cadastro na base de dados '
                    ], 500);

                }

            } 

            $paciente->update([
                'nm_paciente' => $request->nome,
                'nome_social' => $request->nome_social,
                'cd_categoria' => $request->convenio,
                'cartao' => $request->cartao,
                'cartao_sus' => $request->cartao_sus,
                'dt_nasc' => $request->data_de_nascimento,
                'profissao'=> $request->profissao,
                'sexo' => $request->sexo,
                'estado_civil' => $request->estado_civil,
                'rg' => $request->rg,
                'cpf' => $request->cpf,
                'nm_mae' => $request->nome_da_mae, 
                'dt_nasc_mae' => $request->dt_nasc_mae,
                'celular_mae' => $request->celular_mae, 
                'nm_pai' => $request->nome_do_pai, 
                'dt_nasc_pai' => $request->dt_nasc_pai,
                'celular_pai' => $request->celular_pai, 
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'nm_bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'vip' => $request->vip,
                'uf' => $request->uf,
                'cep' => $request->cep,
                'fone' => $request->telefone,
                'celular' => $request->celular,
                'nm_responsavel' => $request->nm_responsavel,
                'cpf_responsavel' => $request->cpf_responsavel,
                'up_usuario' => $request->user()->cd_usuario,
            ]);

            if ($request->has('fotoCopy') && !empty($request->fotoCopy)) {
                $extension = substr($request->fotoCopy, strpos($request->fotoCopy, '/') + 1);
                $extension = substr($extension, 0, strpos($extension, ';'));
                $paciente->foto_tipo = $extension;
                $paciente->foto = base64_decode(substr($request->fotoCopy, strpos($request->fotoCopy, ',') + 1));
                $paciente->save();
            } else if ($request->hasFile('foto')) {
                $paciente->foto_tipo = $request->foto->extension();
                $paciente->foto = file_get_contents($request->foto->path());
                $paciente->save();
            }

            return redirect()->route('paciente.listar')->with('success', 'Paciente atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro ao atualizar o usuário. ' . $e->getMessage()]);
        }
    }

    public function updateJson(Request $request)
    {

        $empresa=Empresa::find($request->user()->cd_empresa);
        if($empresa->obriga_cpf=='sim'){ $Obriga="required|cpf"; }else{ $Obriga="nullable|cpf"; }

        $validator = Validator::make($request->post(), [
            "cd_agendamento" => "nullable|integer|exists:agendamento,cd_agendamento",
            "paciente" => "nullable|integer|exists:paciente,cd_paciente",
            "nome" => "required|string|max:255",
            "nome_social" => "nullable|string|max:255",
            "data_de_nascimento" => "required|date_format:Y-m-d",
            "sexo" => 'nullable|in:H,M',
            "estado_civil" => "nullable|in:S,C,D,V",
            "rg" => "nullable",
            "cpf" => $Obriga,
            "cartao_sus" => "nullable|string|max:100",
            "cartao" => "nullable|numeric",
            "nome_da_mae" => "nullable|string|max:255",
            "nome_do_pai" => "nullable|string|max:255",
            "convenio" => "nullable|integer|exists:convenio,cd_convenio",
            "cartao" => "nullable",
            "logradouro" => "nullable|string|max:255",
            "numero" => "nullable|string",
            "complemento" => "nullable|string|max:50",
            "bairro" => "nullable|string|max:50",
            "cidade" => "nullable|string|max:50",
            "uf" => "nullable|string|uf",
            "cep" => "nullable",
            "vip" => "nullable",
            "telefone" => "nullable",
            "celular" => "nullable",
            "nm_responsavel" => "nullable",
            "cpf_responsavel" => "nullable|cpf",
            "profissao" => "nullable",
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {


            DB::beginTransaction();

            $ArrayPac = array(
                'nm_paciente' => $request->nome,
                'nome_social' => $request->nome_social,
                'cd_categoria' => $request->convenio,
                'cartao' => $request->cartao,
                'cartao_sus' => $request->cartao_sus,
                'dt_nasc' => $request->data_de_nascimento,
                'sexo' => $request->sexo,
                'estado_civil' => $request->estado_civil,
                'rg' => $request->rg,
                'cpf' => $request->cpf,
                'nm_mae' => $request->nome_da_mae,
                'dt_nasc_mae' => $request->dt_nasc_mae,
                'celular_mae' => $request->celular_mae, 
                'nm_pai' => $request->nome_do_pai, 
                'dt_nasc_pai' => $request->dt_nasc_pai,
                'celular_pai' => $request->celular_pai, 
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'profissao' => $request->profissao,
                'nm_bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'vip' => $request->vip,
                'uf' => $request->uf,
                'cep' => $request->cep,
                'fone' => $request->telefone,
                'celular' => $request->celular,
                'nm_responsavel' => $request->nm_responsavel,
                'cpf_responsavel' => $request->cpf_responsavel,
                'up_usuario' => $request->user()->cd_usuario,
            );
            if ($request['paciente']) {
                
                if($empresa->valida_cpf=='sim'){

                    $retorno = Paciente::whereRaw(" REGEXP_REPLACE(cpf, '[^0-9]', '') = '" . trim( preg_replace('/[^0-9]/', '', $request->cpf)) . "'")
                    ->whereRaw("cd_paciente <> '".$request['paciente']."'")->count();
                     

                    if($retorno>0){
                        
                        return response()->json([
                            'message' => 'Esse CPF ( '.$request->cpf.' ) já esta cadastro na base de dados ',
                            'error' => 'Esse CPF ( '.$request->cpf.' ) já esta cadastro na base de dados '
                        ], 500);
                    }
                } 
                
                $paciente = Paciente::find($request['paciente']);
                $paciente->update($ArrayPac);

            } else {
                $ArrayPac['sn_ativo'] = 'S';
                $ArrayPac['cd_usuario'] = $request->user()->cd_usuario;
            
                if($empresa->valida_cpf=='sim'){

                    $retorno = Paciente::whereRaw(" REGEXP_REPLACE(cpf, '[^0-9]', '') = '" . trim( preg_replace('/[^0-9]/', '', $request->cpf)) . "'")
                    ->count();
                    if($retorno>0){
                        
                        return response()->json([
                            'message' => 'Esse CPF ( '.$request->cpf.' ) já esta cadastro na base de dados ',
                            'error' => 'Esse CPF ( '.$request->cpf.' ) já esta cadastro na base de dados '
                        ], 500);
    
                    }
    
                } 
               
                $paciente = Paciente::create($ArrayPac);
            }

            if ($request['cd_agendamento']) {

                $Tem = 'N';
                if ($request->convenio) {
                    $AgendamentoUp['cd_convenio'] = $request->convenio;
                    $Tem = 'S';
                }
                if ($request->cartao) {
                    $AgendamentoUp['cartao'] = $request->cartao;
                    $Tem = 'S';
                }
                if ($Tem == 'S') {
                    $AgendamentoUp['cartao'] = $request->cartao;
                    $query = Agendamento::where('cd_agendamento', $request['cd_agendamento'])
                        ->update($AgendamentoUp);
                }
            }

            if ($request->has('fotoCopy') && !empty($request->fotoCopy)) {
                $extension = substr($request->fotoCopy, strpos($request->fotoCopy, '/') + 1);
                $extension = substr($extension, 0, strpos($extension, ';'));
                $paciente->foto_tipo = $extension;
                $paciente->foto = base64_decode(substr($request->fotoCopy, strpos($request->fotoCopy, ',') + 1));
                $paciente->save();
            } else if ($request->hasFile('foto')) {
                $paciente->foto_tipo = $request->foto->extension();
                $paciente->foto = file_get_contents($request->foto->path());
                $paciente->save();
            }

            DB::commit();

            return response()->json($paciente);
             
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Houve um erro ao iniciar a consulta.'. $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

    public function destroy(Paciente $paciente)
    {
        try {
            $paciente->delete();
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function jsonIndexPacientes(Request $request)
    {
        if ($request->q) {

            $CPF='';
            if(trim( preg_replace('/[^0-9]/', '', $request->q))){
                $CPF="or REGEXP_REPLACE(cpf, '[^0-9]', '') like '" . trim( preg_replace('/[^0-9]/', '', $request->q)) . "%'";
            }
         
            $pacientes = Paciente::selectRaw("cd_paciente as id,concat(nm_paciente,  case when cpf is not null then concat(' ( ',cpf,' ) ') else ''   end ) as text")
                ->whereRaw(" ( upper(nm_paciente) LIKE '%". mb_strtoupper($request->q) . "%' ".$CPF." ) ")
                ->orderBy("nm_paciente")
                ->paginate(20);

        } else {
            $pacientes = Paciente::select('cd_paciente as id', 'nm_paciente as text')
                ->paginate(20);
        }
       
        return $pacientes->items();
    }

    public function jsonIniciarConsulta(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'paciente' => 'required|integer|exists:paciente,cd_paciente',
            'procedimento' => 'required|integer|exists:procedimento,cd_proc',
            'convenio' => 'required|integer|exists:convenio,cd_convenio',
            'especialidade' => 'required|integer|exists:especialidade,cd_especialidade',
            'local' => 'required|integer|exists:local_atendimento,cd_local',
            'tipo' => 'required|string|in:consulta,retorno,encaixe,exame,terapia',
            'cartao' => 'sometimes|nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $horario = date('Y-m-d H:i:s');
            $paciente = Paciente::find($request->post('paciente'));

            if ($paciente->cd_categoria != $request->cd_convenio) {
                $paciente->cd_categoria = $request->cd_convenio;
                $paciente->save();
            }

            if ($paciente->cartao != $request->cartao) {
                $paciente->cartao = $request->cartao;
                $paciente->save();
            }

            $agendamento = Agendamento::create([
                'cd_paciente' => $request->post('paciente'),
                'cd_profissional' => $request->user()->cd_profissional,
                'cd_procedimento' => $request->post('procedimento'),
                'cd_convenio' => $request->post('convenio'),
                'cd_especialidade' => $request->post('especialidade'),
                'cd_local_atendimento' => $request->post('local'),
                'nome_na_agenda' => $paciente->nm_paciente,
                'situacao' => 'atendido',
                'tipo' => 'consulta',
                'data_horario' => $horario,
                'celular' => $paciente->celular,
            ]);

            return response()->json([
                'agendamento' => $agendamento,
                'consulta' => route('consulta.show', ['agendamento' => $agendamento->cd_agendamento])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Houve um erro ao iniciar a consulta.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function jsonShowPaciente(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cd_paciente' => 'required|integer|exists:paciente,cd_paciente',
        ]);

      
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $Paciente=Paciente::find($request->cd_paciente);
        $Paciente['idade']=idadeAluno($Paciente->dt_nasc);
        return response()->json($Paciente);
    }

    public function storeVip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        if ($request['status'] == 'S') {
            $Status = 'S';
        } else {
            $Status = null;
        }

        $pac = Paciente::where('cd_paciente', $request['paciente'])->update(['vip' => $Status]);
        $paciente = Paciente::find($request['paciente']);
        return response()->json($paciente);
    }

    public function downloadPDFDocumento(Request $request)
    {

        if ($request['tipo'] == 'anamnese') {
            $agendamento = Agendamento::find($request->codigo);
            if ($agendamento->doc_assinado == true) {
                header('Content-type: application/pdf;');
                header('Content-Disposition: inline; filename="dasdad.pdf";');
                echo base64_decode($agendamento->doc_conteudo);
                exit;
            }
        }

        if ($request['tipo'] == 'documento') {
            $documento = AgendamentoDocumentos::find($request->codigo);

            $usuario = Usuario::find($documento->cd_usuario);
            $agendamento['cd_profissional'] = $usuario->cd_profissinal;
            if ($documento->cd_agendamento) {
                $agendamento = Agendamento::find($documento->cd_agendamento);
            } else {
                $agendamento['cd_profissional'] = $usuario->cd_profissinal;
                $agendamento['cd_paciente'] = $usuario->cd_pac;
                $agendamento['cd_convenio'] = null;
                $agendamento['cd_especialidade'] = null;
            }



            if ($documento->form_assinado == true) {
                header('Content-type: application/pdf;');
                header('Content-Disposition: inline; filename="dasdad.pdf";');
                echo base64_decode($documento->form_conteudo);
                exit;
            }
        }

        $usuario = Auth::user();
        $tipo = $request->tipo;
        $documento = null;
        $sn_header = $request['header'];
        $sn_footer = $request['footer'];
        $sn_logo = $request['logo'];
        $sn_data = $request['data'];
        $sn_especial = $request['especial'];
        $sn_assinatura = $request['assinatura'];

        $Prof = $agendamento['cd_profissional'];
        if ($agendamento['cd_paciente']) {
            $Paciente = Paciente::find($agendamento['cd_paciente']);
        } else {
            $Paciente = null;
        }
        if ($agendamento['cd_especialidade']) {
            $Especialidade = Especialidade::find($agendamento['cd_especialidade']);
        } else {
            $Especialidade = null;
        }
        if ($agendamento['cd_convenio']) {
            $Convenio = Convenio::find($agendamento['cd_convenio']);
        } else {
            $Convenio = null;
        }
        $Profissional = Profissional::find($agendamento['cd_profissional']);

        $Empresa = Empresa::find($request->user()->cd_empresa);
        $User = Usuario::whereRaw("cd_profissional=" . $agendamento['cd_profissional'])->first();

        $Espec = ProfissionalEspecialidade::where('profissional_espec.cd_profissional', $agendamento['cd_profissional'])
            ->join('especialidade', 'especialidade.cd_especialidade', 'profissional_espec.cd_especialidade')
            ->where('profissional_espec.sn_ativo', 'S')->selectRaw('nm_especialidade')->orderBy('nm_especialidade')->get();
        $Cont = count($Espec);
        $ESPECIALIDADE = NULL;
        foreach ($Espec as $key => $espec) {
            if (($Cont - 1) == $key) {
                $ESPECIALIDADE = $ESPECIALIDADE . ' e ';
            } else {
                $ESPECIALIDADE = $ESPECIALIDADE . ' ';
            }
            $ESPECIALIDADE = $ESPECIALIDADE . ucwords(mb_strtolower($espec->nm_especialidade));
        }

        $HeaderFooter = FormularioHeaderFooter::find('DOC');

        if (!$User->nm_header_doc) {
            $execultante['nome'] = $Profissional->nm_profissional;
        } else {
            $execultante['nome'] = $User->nm_header_doc;
        }

        if (!$User->espec_header_doc) {
            $execultante['espec'] = $ESPECIALIDADE;
        } else {
            $execultante['espec'] = $User->espec_header_doc;
        }

        if (!$User->conselho_header_doc) {
            $execultante['conselho'] = $Profissional->crm;
        } else {
            $execultante['conselho'] = $User->conselho_header_doc;
        }
        $execultante['logo'] = $Empresa->logo;
        $execultante['header'] = (!$request['header']) ? 'N' : $request['header'];
        $execultante['footer'] = (!$request['footer']) ? 'N' : $request['footer'];
        $execultante['data'] = (!$request['data']) ? 'N' : $request['data'];
        $execultante['sn_logo'] = (!$request['logo']) ? 'N' : $request['logo'];
        $execultante['sn_assinatura'] = (!$sn_assinatura) ? 'N' : $sn_assinatura;
        $execultante['assinatura'] = $Profissional->assinatura;
        $execultante['tp_assinatura'] = $Profissional->tp_assinatura;
        $execultante['end_empresa'] = $Empresa->end;

        if (empty($tipo)) {
            $tipo = 'anamnese';
        }
        $TpDocumento = 'Anamnese';
        if ($tipo == 'documento') {
            $documento = AgendamentoDocumentos::find($request->codigo);

            if ($documento['cd_formulario'] >= 0) {
                $Ret = Formulario::where('cd_formulario', $documento['cd_formulario'])->first();
                $sn_header = $Ret['sn_header'];
                $TpDocumento = $Ret['tp_documento'];
            }
        }


        if (($TpDocumento == 'Receituário de Controle Especial') || ($sn_especial == 'S')) {

            $pdf = FacadePdf::loadView('rpclinica.consultorio.documentos_espec', compact('agendamento', 'usuario', 'tipo', 'documento', 'sn_header', 'Profissional', 'ESPECIALIDADE', 'TpDocumento', 'HeaderFooter', 'Paciente', 'Especialidade', 'Convenio', 'execultante'));
        } else {

            $pdf = FacadePdf::loadView('rpclinica.consultorio.documentos', compact('agendamento', 'usuario', 'tipo', 'documento', 'sn_header', 'Profissional', 'ESPECIALIDADE', 'TpDocumento', 'HeaderFooter', 'Paciente', 'Especialidade', 'Convenio', 'execultante'));
        }
        return $pdf->stream('Documento.pdf');
    }

    public function updateComentario(Request $request, $id)
    {
        try {
            $paciente = Paciente::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'comentario' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $paciente->comentario = $request->input('comentario');
            $paciente->save();

            return response()->json(['success' => true, 'message' => 'Comentário atualizado com sucesso.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Não foi possível atualizar o comentário. ' . $e->getMessage()], 500);
        }
    }
}
