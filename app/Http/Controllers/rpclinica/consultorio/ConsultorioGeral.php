<?php

namespace App\Http\Controllers\rpclinica\consultorio;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoAnexos;
use App\Model\rpclinica\AgendamentoDocumentos;
use App\Model\rpclinica\AgendamentoLog;
use App\Model\rpclinica\ClassificacaoTriagem;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\Formulario;
use App\Model\rpclinica\FormularioHeaderFooter;
use App\Model\rpclinica\LogExamesPaciente;
use App\Model\rpclinica\LogProblemasPaciente;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\ProfissionalEspecialidade;
use App\Model\rpclinica\Usuario;
use App\Model\rpclinica\Empresa;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;
use App\Bibliotecas\PDFSign;
use App\Http\Controllers\rpclinica\AgendamentoDocumento;
use App\Model\rpclinica\AgendamentoAnotacao;
use App\Model\rpclinica\AgendamentoHistoriaPregressa;
use App\Model\rpclinica\AgendamentoHistoriaPregressaHist;
use App\Model\rpclinica\AgendamentoImg;

use App\Model\rpclinica\AgendamentoItens;
use App\Model\rpclinica\AgendamentoSituacao;
use App\Model\rpclinica\Certificado;
use Illuminate\Support\Facades\Storage;

class ConsultorioGeral extends Controller
{
    public function show(Request $request, Agendamento $agendamento)
    {
        try {

            $paciente = Paciente::find($agendamento->cd_paciente);
            $profissional = Profissional::find($request->user()->cd_profissional);
            $empresa = Empresa::find($request->user()->cd_empresa);
            $tpCampo=$empresa->tp_editor_html;
            $dados['modeloAnam'] = Formulario::where('sn_ativo','S')
            ->where('tp_formulario','ATE')
            ->where('cd_profissional',$request->user()->cd_profissional)
            ->orderBy('nm_formulario')->get()->toArray();
            
            foreach($dados['modeloAnam'] as $key => $modeloAnam){ 
                $dados['modeloAnam'][$key]['exame']=  nl2br($modeloAnam['exame']);
                $dados['modeloAnam'][$key]['hipotese']=nl2br($modeloAnam['hipotese']);
                $dados['modeloAnam'][$key]['conduta']=nl2br($modeloAnam['conduta']);
                $dados['modeloAnam'][$key]['conteudo']=nl2br($modeloAnam['conteudo']);
            } 
            
            $request['modeloAnam']=$dados['modeloAnam'];

            $dados['modeloDoc'] = Formulario::where('sn_ativo','S')->where('tp_formulario','DOC')
            ->where('cd_profissional',$request->user()->cd_profissional)->orderBy('nm_formulario')->get()->toArray();
           
            foreach($dados['modeloDoc'] as $key => $modeloDoc){ 
             
                $dados['modeloDoc'][$key]['conteudo']=nl2br(camposInteligentesPac($dados['modeloDoc'][$key]['conteudo'],$paciente,$profissional));
                
            }
            
            $request['modeloDoc']=$dados['modeloDoc'];
            
            $historico['anamnese'] = Agendamento::where('cd_paciente',$agendamento->cd_paciente)
            ->with('profissional','user_anamnese')
            ->WhereNotNull('dt_anamnese')
            ->where('cd_agendamento','<>',$agendamento->cd_agendamento)
            ->selectRaw("date_format(data_horario,'%d/%m/%Y') data, anamnese, exame_fisico,cd_paciente, 
            usuario_anamnese,cd_profissional,historia_pregressa,hipotese_diagnostica, conduta, cd_agendamento")
            ->orderByRaw("data_horario desc")->get()->toArray();
             
            foreach($historico['anamnese'] as $key => $anamnese){
                
                $histPregressa= AgendamentoHistoriaPregressaHist::where('cd_agendamento',$anamnese['cd_agendamento'])->first();
                $historico['anamnese'][$key]['anamnese']=nl2br($anamnese['anamnese']);
                $historico['anamnese'][$key]['exame_fisico']=nl2br($anamnese['exame_fisico']);
                $historico['anamnese'][$key]['historia_pregressa']=nl2br( (isset($histPregressa['conteudo'])) ? $histPregressa['conteudo'] : '' );
                $historico['anamnese'][$key]['hipotese_diagnostica']=nl2br($anamnese['hipotese_diagnostica']);
                $historico['anamnese'][$key]['conduta']=nl2br($anamnese['conduta']);

            }
           
            $historico['documento'] = AgendamentoDocumentos::where('cd_agendamento','<>',$agendamento->cd_agendamento)
            ->with(['profissional','formulario','agendamento'=> function($q) use($agendamento){ 
                $q->where('cd_paciente',$agendamento->cd_paciente);
            },'usuario']);
            $historico['documento'] = $historico['documento']->whereHas('agendamento', function($q)  use($agendamento) {
                $q->where('cd_paciente',$agendamento->cd_paciente);
            });
            $historico['documento'] = $historico['documento']->orderByRaw("created_at desc")->get()->toArray();

            foreach($historico['documento'] as $key => $documento){
                $historico['documento'][$key]['conteudo']= nl2br($documento['conteudo']); 
            }

            $historico['texto_html']= $tpCampo;

            $agendamento->load('documentos.formulario','documentos.profissional');
 
            $anotacao=$this->getAnotacao($agendamento->cd_paciente); 
        

            $historico['geral'] = Agendamento::where('cd_paciente',$agendamento->cd_paciente)
            ->selectRaw("agendamento.*,date_format(data_horario,'%d/%m/%Y') data")
            ->with('documentos.formulario','documentos.profissional','user_anamnese','profissional','agenda')
            ->orderByRaw("data_horario desc")->get();
            $Hist=$historico['geral'];
            if($tpCampo=='nao'){
                foreach($Hist as $key => $linha){ 
                    $historico['geral'][$key]['anamnese']=nl2br($linha['anamnese']);
                    $historico['geral'][$key]['exame_fisico']= nl2br($linha['exame_fisico']);
                    $historico['geral'][$key]['historia_pregressa']=nl2br($linha['historia_pregressa']);
                    $historico['geral'][$key]['hipotese_diagnostica']= nl2br($linha['hipotese_diagnostica']);
                    $historico['geral'][$key]['conduta']=nl2br($linha['conduta']); 
                    foreach($linha->documentos as $keyy => $doc){ 
                        $historico['geral'][$key]['documentos'][$keyy]['conteudo']= nl2br($doc['conteudo']); 
                    }
                }
            }

            $config=$request->user();  
            $config->load('profissional');

            $historia_pregressa=AgendamentoHistoriaPregressa::where('cd_paciente',$agendamento->cd_paciente)
            ->where('cd_profissional',$request->user()->cd_profissional)->first();
 
            if($config->sn_carregar_historia_pregressa=='S'){
                $historia_pregressa=  str_replace("\n", "<br />", ((isset($historia_pregressa->conteudo) ? $historia_pregressa->conteudo : '')) );
            }else{
                $historia_pregressa='';
            }
             
            $cdPaciente=$agendamento->cd_paciente;

            $historico['exame'] = AgendamentoItens::where('sn_laudo','1')
            ->where('cd_agendamento_item',"<>", $request->cd_agendamento_item)
            ->with(['atendimento' => function($q) use($cdPaciente){  
                $q->where('cd_paciente', $cdPaciente);
            },'exame','atendimento.profissional'])
            ->whereHas('atendimento', function($q) use($cdPaciente) {
                $q->where('cd_paciente', $cdPaciente);
             })
             ->orderBy("created_at","desc")
            ->get();
  
            $historico['arquivo']=$this->array_img_agendamentos($agendamento->cd_paciente); 



            $certificado= Certificado::find(Auth::user()->cd_profissional);
            
            return response()->json(['agendamento' => $agendamento->toArray(),
                                     'request'=>$request->toArray(),
                                     'anotacao'=>$anotacao,
                                     'historia_pregressa'=>$historia_pregressa,
                                    'historico'=>$historico,
                                    'config'=>$config,
                                    'certificado'=>$certificado
                                    ]);

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }

    }

    public function storeModelo(Request $request, $tipo)
    {
        $validator = Validator::make($request->all(), [
            'Titulo' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {   
            if($tipo=='ATE'){
                $dados=array(
                    'nm_formulario'=> $request['Titulo'],
                    'conteudo'=> $request['Anamnese'],
                    'exame'=> $request['Exame'],
                    'hipotese'=> $request['Hipotese'],
                    'conduta'=> $request['Conduta'],
                    'sn_ativo'=> 'S',
                    'cd_profissional'=>$request->user()->cd_profissional,
                    'cd_usuario'=> $request->user()->cd_usuario,
                    'tp_formulario'=> $tipo,
                );
                Formulario::create($dados);
                $request['modelo'] = Formulario::where('sn_ativo','S')->where('tp_formulario','ANA')
                ->where('cd_profissional',$request->user()->cd_profissional)
                ->orderBy('nm_formulario')->get();
                return response()->json(['request' => $request->toArray()]);
            }

            if($tipo=='DOC'){
                $dados=array(
                    'nm_formulario'=> $request['Titulo'],
                    'conteudo'=>  str_replace("<br />\n", "<br />", $request['Documento']) , 
                    'sn_ativo'=> 'S',
                    'cd_profissional'=>$request->user()->cd_profissional,
                    'cd_usuario'=> $request->user()->cd_usuario,
                    'tp_formulario'=> $tipo,
                );
                Formulario::create($dados);

                $request['modelo'] = Formulario::where('sn_ativo','S')->where('tp_formulario','DOC')
                ->where('cd_profissional',$request->user()->cd_profissional)
                ->orderBy('nm_formulario')->get();
                return response()->json(['request' => $request->toArray()]);
            }

 
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }

    }

 
    public function storeAnamnese(Request $request, Agendamento $agendamento)
    {
    
        try {
             
            //$fields = $request->only('anamnese', 'historia_pregressa', 'exame_fisico', 'hipotese_diagnostica', 'conduta');
            $fields['anamnese'] = $request['anamnese'];
            $fields['historia_pregressa'] = $request['historia_pregressa'];
            $fields['exame_fisico'] = $request['exame_fisico'];
            $fields['hipotese_diagnostica'] = $request['hipotese_diagnostica'];
            $fields['conduta'] = $request['conduta'];
            $fields['usuario_anamnese']=$request->user()->cd_usuario;
            $fields['dt_anamnese']=date('Y-m-d H:i');

            $fields['anamnese']=$fields['anamnese'];
            $fields['historia_pregressa']=$fields['historia_pregressa'] ;
            $fields['exame_fisico']=$fields['exame_fisico'];
            $fields['hipotese_diagnostica']=$fields['hipotese_diagnostica'];
            $fields['conduta']=$fields['conduta'];

            $agendamento->update($fields);

            AgendamentoHistoriaPregressa::updateOrCreate([ 
                'cd_paciente'=>$agendamento->cd_paciente,
                'cd_profissional'=>$request->user()->cd_profissional
            ],[
                'conteudo'=> str_replace("\n", "", $request['historia_pregressa']),
                'cd_agendamento'=>$agendamento->cd_agendamento,
                'cd_paciente'=>$agendamento->cd_paciente,
                'cd_profissional'=>$request->user()->cd_profissional,
                'cd_usuario'=> $request->user()->cd_usuario
            ]);
   
            AgendamentoHistoriaPregressaHist::updateOrCreate([ 
                'cd_paciente'=>$agendamento->cd_paciente,
                'cd_agendamento'=>$agendamento->cd_agendamento,
                'cd_profissional'=>$request->user()->cd_profissional
            ],[
                'conteudo'=> str_replace("\n", "", $request['historia_pregressa']),
                'cd_agendamento'=>$agendamento->cd_agendamento,
                'cd_paciente'=>$agendamento->cd_paciente,
                'cd_profissional'=>$request->user()->cd_profissional,
                'cd_usuario'=> $request->user()->cd_usuario
            ]);

            if(trim($fields['anamnese'])=='<p>undefined</p>'){ $fields['anamnese']=null; }
            if(trim($fields['exame_fisico'])=='<p>undefined</p>'){ $fields['exame_fisico']=null; }
            if(trim($fields['hipotese_diagnostica'])=='<p>undefined</p>'){ $fields['hipotese_diagnostica']=null; }
            if(trim($fields['conduta'])=='<p>undefined</p>'){ $fields['conduta']=null; }

            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO SALVOU ANAMNESE');
 
            $agendamento->load('documentos.formulario','documentos.profissional');

            return response()->json(['message' => 'Dados da anamnese salvos com sucesso!','request' => $fields,'agendamento' => $agendamento->toArray()]);

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
        
    }
 
    public function deleteAnamnese(Request $request,  $agendamento)
    {
        try { 
             
            DB::transaction(function () use ($agendamento,$request){

                Agendamento::where('cd_Agendamento',$agendamento)->update([
                    'anamnese'=> null,
                    'exame_fisico'=> null,
                    'exame_fisico'=> null,
                    'hipotese_diagnostica'=> null,
                    'conduta'=> null,
                    'usuario_anamnese'=>$request->user()->cd_usuario,
                    'dt_anamnese'=>date('Y-m-d H:i')
                ]); 
                
                funcLogsAtendimentoHelpers($agendamento,'USUARIO DELETOU ANAMNESE');

            });
            $agendamento =Agendamento::find($agendamento);
            
            $agendamento->load('documentos.formulario','documentos.profissional');
            
            return response()->json(['message' => 'Dados da anamnese salvos com sucesso!','request' => $request,'agendamento' => $agendamento->toArray()]);

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function storeArquivoAnamnese(Request $request, Agendamento $agendamento)
    {
          
        try {
            
            $empresa = Empresa::find($request->user()->cd_empresa);
            
            $rules['image'] = 'required|file|mimes:jpg,jpeg,png,psd,tiff,svg,raw,pdf,webp,bmp,png,gif|max:10224';
            $validator = Validator::make($request->all(), $rules );
 
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            } 
            if(empty($empresa->storage_file)){
                return response()->json(['message' =>'O sisetma não esta configurado para fazer upload de Arquivos!'], 500);
            }
      
            $image = $request->image;  
            $dados['cd_agendamento'] = $agendamento->cd_agendamento; 
            $dados['cd_paciente'] = $agendamento->cd_paciente; 
            $dados['cd_usuario'] = $request->user()->cd_usuario;  
            $dados['nm_img'] = time()."_".$image->getClientOriginalName(); 
            $dados['tp_file'] = $image->getMimeType(); 
            $dados['tp_store'] = 's3';  
            $path = $image->store($agendamento->cd_agendamento); 
            //dd($empresa->storage_file."/"."arquivos"."/".$agendamento->cd_agendamento,$dados['nm_img']);
            $path = $request->image->storeAs($empresa->storage_file."/"."arquivos"."/".$agendamento->cd_agendamento,$dados['nm_img'],'s3');
            $dados['caminho'] = $path; 
    
            $retorno = DB::transaction(function () use ($dados,$request){
                $tabela = AgendamentoImg::create($dados); 
                $usuario_logado = $request->user();  
                return $tabela;
            }); 

           $dados['array_img']=$this->array_img_agendamentos($agendamento->cd_paciente); 
          
            return response()->json(['request' => $dados, 'retorno' => $retorno, 'message' => 'Arquivo Salvo com Sucesso!']);
            
        } catch (Throwable $error) { 
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function deleteImgAnamnese(Request $request, $cd_image_formulario)
    {
        try {
            $Img=AgendamentoImg::find($cd_image_formulario);
            $Pac=$Img->cd_paciente; 
            DB::transaction(function () use ($cd_image_formulario,$request,$Img){ 
                $tp_storage=$Img->tp_store; 
                $caminho_arquivo_local = env('URL_IMG_EXAMES')."/".$Img->caminho; 
                $caminho_arquivo_s3 = $Img->caminho; 
                AgendamentoImg::where('cd_agendamento_img',$cd_image_formulario)->delete(); // TODO: delete file too 
                //Storage::disk('s3')->delete('pasta/teste.png');
                if($tp_storage=='local'){
                    if (file_exists($caminho_arquivo_local)) {
                        unlink($caminho_arquivo_local);
                    } 
                }
                if($tp_storage=='s3'){ 
                    Storage::disk('s3')->delete($caminho_arquivo_s3);
                }

            }); 
            $dados['array_img']=$this->array_img_agendamentos($Pac);
            return response()->json(['dados' => $dados  ]);
            
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
        
    }
 
    public function storeDocumento(Request $request, Agendamento $agendamento)
    {
    
        $validator = Validator::make($request->post(), [
            'cd_formulario' => 'nullable|integer|exists:formulario,cd_formulario',
            'documento' => 'required|string',
            'titulo' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 500);
        }

        try {
            $empresa = Empresa::find($request->user()->cd_empresa);
            $tpCampo=$empresa->tp_editor_html;
            if($request->cd_formulario){
                $formulario = Formulario::find($request->cd_formulario);
            }else{
                $formulario = Formulario::where('default','S')->first();
            }

          
            if(empty($formulario)){
                return response()->json(['message' => 'Sistema não esta configurado para essa ação! <br>[ Campo default ]'], 500);
            }
             
            if($request->cdDocumento){
                $documentoAgendamento = AgendamentoDocumentos::where("cd_documento",$request->cdDocumento)->update([
                    'nm_formulario' => (isset($request->titulo)) ? $request->titulo : $formulario->nm_formulario,
                    'conteudo' => $request->documento,
                    'titulo' => $request->titulo,
                    'cd_formulario' => (isset($formulario->cd_formulario)) ? $formulario->cd_formulario : null,
                    'cd_usuario' => $request->user()->cd_usuario,
                    'cd_pac' =>  $agendamento->cd_paciente,
                    'cd_prof' =>  $agendamento->cd_profissional,
                    'cd_agendamento' => $agendamento->cd_agendamento
                ]);
            }else{ 
                $documentoAgendamento = AgendamentoDocumentos::create([
                    'nm_formulario' => (isset($request->titulo)) ? $request->titulo : $formulario->nm_formulario,
                    'conteudo' => $request->documento,
                    'titulo' => $request->titulo,
                    'cd_formulario' => (isset($formulario->cd_formulario)) ? $formulario->cd_formulario : null,
                    'cd_usuario' => $request->user()->cd_usuario,
                    'cd_pac' =>  $agendamento->cd_paciente,
                    'cd_prof' =>  $agendamento->cd_profissional,
                    'cd_agendamento' => $agendamento->cd_agendamento
                ]); 
            }

            $agendamento->load('documentos.formulario','documentos.profissional');

            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO SALVOU DOCUMENTO ( '.$request->formulario.' )');

            return response()->json(['message' => 'Documento cadastrado!', 
                                     'request'=>$request->toArray(), 
                                     'agendamento'=>$agendamento, 
                                     'documento' => $documentoAgendamento
                                    ]);
        
        }
        catch(Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }

    }
  
    public function deleteDocumento(Request $request, $agendamento, AgendamentoDocumentos $documento)
    {
        try { 
             
            DB::transaction(function () use ($agendamento,$documento,$request){

                $documento->delete();
                funcLogsAtendimentoHelpers($agendamento,'USUARIO DELETOU DOCUMENTO');

            });
            $agendamento =Agendamento::find($agendamento);
            
            $agendamento->load('documentos.formulario','documentos.profissional');
            
            return response()->json(['message' => 'Dados da anamnese salvos com sucesso!','request' => $request,'agendamento' => $agendamento->toArray()]);

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
 
    public function imprimirDocumento(Request $request, Agendamento $agendamento, AgendamentoDocumentos $documento)
    {
       
        try { 
             
            if($documento->form_assinado==true){
                if (ob_get_length()) ob_end_clean(); // Remove lixo do buffer antes de enviar binário
                header("Content-type:application/pdf");  
                echo base64_decode($documento->form_conteudo);
                exit;
            }
           
            $documento->load('profissional','agendamento.paciente','usuario'); 
            $agendamento->load('profissional','paciente','especialidade','convenio');  
            $Empresa = Empresa::find($request->user()->cd_empresa);
            $User=Usuario::whereRaw("cd_profissional=".$agendamento->cd_profissional)->first();
            $dados['documento']=$documento->toArray();

            $relatorio['conteudo']=$documento['conteudo'];
            $relatorio['titulo']=$documento['titulo'];
            $relatorio['paciente']=$documento['agendamento']['paciente']['nm_paciente'];
            $relatorio['dt_nasc']=$documento['agendamento']['paciente']['dt_nasc'];
            $relatorio['data']=$documento['created_at'];
            $relatorio['tp_assinatura']=$dados['documento']['profissional']['tp_assinatura'];
            $relatorio['assinatura']=$dados['documento']['profissional']['assinatura'];
            $relatorio['conselho']=$dados['documento']['profissional']['conselho'];
            $relatorio['crm']=$dados['documento']['profissional']['crm'];
            $relatorio['nm_profissional']=$dados['documento']['profissional']['nm_profissional'];

            if(!$User->nm_header_doc){
                $execultante['nome']=$agendamento->profissional?->nm_profissional;
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
            $execultante['sn_assinatura']= ($request['assinatura']=='S') ? 'N' : 'S';
            $execultante['sn_ocultar_titulo']= ($request['sn_ocultar_titulo']=='S') ? 'N' : 'S';  
            $execultante['assinatura']= $agendamento->profissional->assinatura;
            $execultante['tp_assinatura']= $agendamento->profissional->tp_assinatura; 
            $execultante['end_empresa']= $Empresa->end;
 
            $TpDocumento = (isset($documento->titulo)) ? $documento->titulo : '';
            $tipo='documento';  
             

            if($request->rec_especial=='S'){ 

                    $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.documentos_espec', 
                    compact('relatorio','agendamento','execultante','tipo','TpDocumento','Empresa','documento'));
  
            }else{

                if($Empresa->tp_formulario=='logo'){
                    $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.castelo.documentos', 
                    compact('relatorio','agendamento','execultante','tipo','TpDocumento','Empresa','documento'));
                }
                if($Empresa->tp_formulario=='basico'){
                    $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.impressao', compact('relatorio','execultante'));
                }
                if(empty($Empresa->tp_formulario)){
                    $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.impressao', compact('relatorio','execultante'));
                }

            }
                
            $pdf->setPaper('A4', 'portrait');
            
            if (ob_get_length()) ob_end_clean(); // Limpa buffer antes de streamar PDF
            return $pdf->stream('Documento.pdf');
           
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
      
    }
 
    public function imprimirAnamnese(Request $request, Agendamento $agendamento)
    {
        try { 
            
             
            $agendamento->load('profissional','paciente','especialidade','convenio');  
            $Empresa = Empresa::find($request->user()->cd_empresa);
            $User=Usuario::whereRaw("cd_profissional=".$agendamento->cd_profissional)->first();
             
            $relatorio=null;
            $relatorio['titulo']='Anamnese';
            $relatorio['paciente']=$agendamento['paciente']['nm_paciente'];
            $relatorio['dt_nasc']=$agendamento['paciente']['dt_nasc'];
            $relatorio['data']=$agendamento['dt_anamnese'];
            $relatorio['tp_assinatura']=$agendamento['profissional']['tp_assinatura'];
            $relatorio['assinatura']=$agendamento['profissional']['assinatura'];
            $relatorio['conselho']=$agendamento['profissional']['conselho'];
            $relatorio['crm']=$agendamento['profissional']['crm'];
            $relatorio['nm_profissional']=$agendamento['profissional']['nm_profissional'];
           
            $relatorio['sn_historia_pregressa']=$User->sn_historia_pregressa;
            $relatorio['sn_anamnese']=$User->sn_anamnese;
            $relatorio['sn_exame_fisico']=$User->sn_exame_fisico;
            $relatorio['sn_conduta']=$User->sn_conduta;
            $relatorio['sn_hipotese_diag']=$User->sn_hipotese_diag; 
             
             
            if(!$User->nm_header_doc){
                $execultante['nome']=$agendamento->profissional?->nm_profissional;
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
            $execultante['sn_assinatura']= ($request['assinatura']=='S') ? 'N' : 'S';
            $execultante['assinatura']= $agendamento->profissional->assinatura;
            $execultante['tp_assinatura']= $agendamento->profissional->tp_assinatura; 
            $execultante['sn_ocultar_titulo']= ($request['sn_ocultar_titulo']=='S') ? 'N' : 'S';  
              
            $TpDocumento = 'Anamnese';
            $tipo='anamnese'; 
 
            if($Empresa->tp_formulario=='logo'){
                $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.castelo.documentos', 
                compact('relatorio','agendamento','execultante','tipo','TpDocumento','Empresa','User'));
            }
            if($Empresa->tp_formulario=='basico'){
                $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.impressao_anamnese', compact('relatorio','agendamento','Empresa','execultante','User'));
            }
            if(empty($Empresa->tp_formulario)){
                $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.impressao_anamnese', compact('relatorio','agendamento','Empresa','execultante','User'));
            }
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('anamnese.pdf');
           
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
        
    }

    public function assinarDocumento(Request $request, Agendamento $agendamento, AgendamentoDocumentos $documento)
    {
        
        try { 

            if ($documento->form_assinado==true) {
                return response()->json(['retorno'=>false, 'message' => 'Documento encontrasse assinado!']);
            }
           
            $validator = Validator::make($request->all(), [
                'senha' => 'required'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['retorno'=>false, 'message' => $validator->errors()->first()]);
            }
            
            if(empty($request->user()->cd_profissional)){ 
                return response()->json(['retorno'=>false,  'message' => 'Profissional não informado!']); 
            }
           
            $Cert = Certificado::find($request->user()->cd_profissional);  
            if(!isset($Cert->pfx)){ 
                if(!$Cert->pfx){ 
                    return response()->json(['retorno'=>false, 'message' => 'O certificado não esta configurado no sistema!']); 
                }
            }

            $documento->load('profissional','agendamento.paciente','usuario'); 
            $agendamento->load('profissional','paciente','especialidade','convenio');  
            $Empresa = Empresa::find($request->user()->cd_empresa);
            $User=Usuario::whereRaw("cd_profissional=".$agendamento->cd_profissional)->first();
            $dados['documento']=$documento->toArray();

            $relatorio['conteudo']=$documento['conteudo'];
            $relatorio['titulo']=$documento['titulo'];
            $relatorio['paciente']=$documento['agendamento']['paciente']['nm_paciente'];
            $relatorio['dt_nasc']=$documento['agendamento']['paciente']['dt_nasc'];
            $relatorio['data']=$documento['created_at'];
            $relatorio['tp_assinatura']=$dados['documento']['profissional']['tp_assinatura'];
            $relatorio['assinatura']=$dados['documento']['profissional']['assinatura'];
            $relatorio['conselho']=$dados['documento']['profissional']['conselho'];
            $relatorio['crm']=$dados['documento']['profissional']['crm'];
            $relatorio['nm_profissional']=$dados['documento']['profissional']['nm_profissional'];

            if(!$User->nm_header_doc){
                $execultante['nome']=$agendamento->profissional?->nm_profissional;
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
            $execultante['assinatura']= $agendamento->profissional->assinatura;
            $execultante['tp_assinatura']= $agendamento->profissional->tp_assinatura; 
            $execultante['end_empresa']= $Empresa->end;
            $certPass = $request->senha;
 
            $TpDocumento = (isset($documento->titulo)) ? $documento->titulo : '';
            $tipo='documento';  
              
            if($request->rec_especial=='S'){ 

                    $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.documentos_espec', 
                    compact('relatorio','agendamento','execultante','tipo','TpDocumento','Empresa','documento'));
  
            }else{

                if($Empresa->tp_formulario=='logo'){
                    $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.castelo.documentos', 
                    compact('relatorio','agendamento','execultante','tipo','TpDocumento','Empresa','documento'));
                }
                if($Empresa->tp_formulario=='basico'){
                    $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.impressao', compact('relatorio','execultante'));
                }
                if(empty($Empresa->tp_formulario)){
                    $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.impressao', compact('relatorio','execultante'));
                }

            }
                
            $pdf->setPaper('A4', 'portrait'); 
            $ARQ=$pdf->stream();
         
            $RetornoAssinatura=helperAssinaturaGigital($ARQ,$request->user()->cd_profissional,$certPass,$request->rec_especial);  
         
            if($RetornoAssinatura['status']==true){

                $PDF=$RetornoAssinatura['conteudo'];
                $array=array('form_assinado'=>true,'form_conteudo'=>$PDF,'dt_assinado_digital_form'=>date('Y-m-d H:i'),
                'user_assinado_digital_form'=>$request->user()->cd_usuario); 
                DB::transaction(function() use ($request,$documento,$array) {
                    $documento->update($array);
                });

                $documentos= $agendamento->load('documentos.formulario','documentos.profissional');
                return response()->json(['retorno'=>true, 'documento' => $documentos]);
            
                
            }else{
                
                return response()->json(['retorno'=>false, 'msg' => $RetornoAssinatura['conteudo']]);
            }
             
            
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
        
      
    }

    public function storeAnotacao(Request $request, Agendamento $agendamento)
    {
        $empresa = Empresa::find($request->user()->cd_empresa);
        $validator = Validator::make($request->post(), [ 
            'Anotacao' => 'required|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,psd,tiff,svg,raw,pdf,webp,bmp,png,gif|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 500);
        }
 
        try {

            if($request->hasFile('image')){ 
 
                $file = $request->file('image'); 
                $filename = time()."_".$file->getClientOriginalName();
                $extension = $file->getMimeType(); 
                if(empty($empresa->storage_file)){
                    return response()->json(['message' =>'O sisetma não esta configurado para fazer upload de Arquivos!'], 500);
                }
                $path = $request->image->storeAs($empresa->storage_file."/"."anotacoes"."/".$agendamento->cd_agendamento,$filename,'s3');
                //$path = $request->image->store->disk('s3')($agendamento->cd_agendamento,'consultorio_windows');
                $caminho_img = $path; 
                $tpStorage = 's3';
            }else{
                $caminho_img = null;
                $extension = null;
                $tpStorage = null;
            }
            $tpCampo=$empresa->tp_editor_html;
            $documentoAgendamento = AgendamentoAnotacao::create([
                'cd_paciente' => $agendamento->cd_paciente,
                'conteudo' => ($tpCampo=='nao') ? html_entity_decode(strip_tags($request->Anotacao)) : $request->Anotacao,
                'tp_file' => $extension,
                'tp_store' => $tpStorage,
                'caminho_arquivo' => $caminho_img,
                'cd_agendamento' => $agendamento->cd_agendamento,
                'cd_usuario' => $request->user()->cd_usuario, 
            ]);

            $agendamento->load('documentos.formulario','documentos.profissional');

            $anotacao=$this->getAnotacao($agendamento->cd_paciente); 
                
            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO SALVOU DOCUMENTO ( '.$request->formulario.' )');

            return response()->json(['message' => 'Documento cadastrado!', 
                                     'agendamento'=>$agendamento, 
                                     'documento' => $documentoAgendamento,
                                     'anotacao' => $anotacao,
                                     'request' => $request->toArray()
                                    ]);
        
        }
        catch(Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }

    }

    public function finalizarConsulta(Request $request, Agendamento $agendamento) {

        try {

            $situacao = AgendamentoSituacao::where('atender','S')->first();
            if(empty($situacao)){ 
                return response()->json(['message' => 'Situação não configurada para essa ação!'], 400);
            }

           $returno = Agendamento::where('cd_agendamento',$agendamento->cd_agendamento)->update(['situacao' => $situacao->cd_situacao,
                                'dt_finalizacao'=>date('Y-m-d H:i'),
                                'usuario_finalizacao'=>$request->user()->cd_usuario,
                                'sn_finalizado'=>'S']);
 

            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO FINALIZOU ATENDIMENTO');

            return redirect()->route('consultorio');

        }
        catch(Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    public function getAnotacao($paciente)
    {
        $arrayAnotacao=[]; 
        $anotacao=AgendamentoAnotacao::where('cd_paciente',$paciente)
        ->with('tab_paciente','tab_agendamento','tab_usuario')
        ->selectRaw("agendamento_anotacao.*,date_format(created_at,'%d/%m/%Y') data")
        ->orderByRaw("created_at desc")->get();
        foreach($anotacao as $linha){
            $arrayLinha['conteudo_arquivo']=null;
            $arrayLinha['tipo']=null;
            $tipo = null;
            if ($linha->caminho_arquivo) {
                $arrayLinha['conteudo_arquivo']= Storage::disk('s3')->temporaryUrl($linha->caminho_arquivo,now()->addMinutes(15));  
                if (str_contains($linha->tp_file, 'image')) {
                    $tipo = 'img';
                } 
                if (str_contains($linha->tp_file, 'pdf')) {
                    $tipo = 'pdf';
                }
            }
            $arrayLinha['dados']= $linha; 
            $arrayLinha['tipo'] = isset($tipo) ? $tipo : null; 
            $arrayAnotacao[]=$arrayLinha;
        }
        return $arrayAnotacao;
    }

    public function getAnotacaoLOCAL($paciente)
    {
        $arrayAnotacao=[];
        $CaminhoPath=(env('URL_IMG_CONSULTORIO', '/var/img_oftalmo'));
        $anotacao=AgendamentoAnotacao::where('cd_paciente',$paciente)
        ->with('tab_paciente','tab_agendamento','tab_usuario')
        ->selectRaw("agendamento_anotacao.*,date_format(created_at,'%d/%m/%Y') data")
        ->orderByRaw("created_at desc")->get();
        foreach($anotacao as $linha){
            $mime_type=null;$file_size=null;$conteudo_img=null;$tipo=null;
            $CaminhoImg = $CaminhoPath . "/" . $linha->caminho_arquivo; 
            if (is_file($CaminhoImg)) {
                $mime_type = mime_content_type($CaminhoImg);  
                $file_size = filesize($CaminhoImg); 
                $data = file_get_contents($CaminhoImg);
                $conteudo_img =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                if (str_contains($mime_type, 'image')) {
                    $tipo = 'img';
                } 
                if (str_contains($mime_type, 'pdf')) {
                    $tipo = 'pdf';
                }
            }
            $arrayLinha['dados']= $linha;
            $arrayLinha['mime_type'] = isset($mime_type) ? $mime_type : null; 
            $arrayLinha['file_size'] = isset($file_size) ? $file_size : null;
            $arrayLinha['conteudo_arquivo'] = isset($conteudo_img) ? $conteudo_img : null;
            $arrayLinha['tipo'] = isset($tipo) ? $tipo : null; 
            $arrayAnotacao[]=$arrayLinha;
        }
        return $arrayAnotacao;
    }
 
    public function teste_aws()
    { 
        
        echo ' <form class="form-horizontal"  action="/rpclinica/json/storeAnamneseArquivo/68173" enctype="multipart/form-data" 
                      id="form_ARQUIVO_ANAM" method="post">
                      <input type="hidden" name="_token" value="'.csrf_token().'">
                    <div class="form-group" style="margin-bottom: 5px;">
                      <div class="col-md-10"> 
                        <input type="file" class="form-control"  name="image">
                      </div>
                      <div class="col-md-2">
                        <button type="submit" class="btn btn-success  m-b-xs"><i class="fa fa-upload"></i></button>
                      </div>
                    </div>
                     
                </form>';
    }

    public function array_img_agendamentos($paciente)
    { 

        $dados['tab_img'] = AgendamentoImg::where('cd_paciente', $paciente)
            ->selectRaw("agendamento_img.*,date_format(created_at,'%d/%m/%Y') data")
            ->with(['agendamento','usuario'])
            ->orderBy('created_at', 'desc')->get();

        $dados['array_img']=[];
        foreach ($dados['tab_img'] as $imgs) {
         
            if ($imgs->caminho) { 
                $ArrayImg['conteudo_img'] = Storage::disk('s3')->temporaryUrl($imgs->caminho,now()->addMinutes(15));  
                if (str_contains($imgs->tp_file, 'image')) {
                    $ArrayImg['tipo'] = 'img';
                } 
                if (str_contains($imgs->tp_file, 'pdf')) {
                    $ArrayImg['tipo'] = 'pdf';
                }  
               
                $ArrayImg['codigo'] = $imgs->cd_agendamento_img; 
                $ArrayImg['descricao'] =  ($imgs->nm_img) ? $imgs->nm_img : ' ... ';  
                $ArrayImg['mime_type'] = $imgs->tp_file; 
                            
                $ArrayImg['usuario'] = $imgs->usuario?->nm_usuario;
                $ArrayImg['data'] = $imgs->data;
                $ArrayImg['cd_img_formulario'] = $imgs->cd_agendamento_img;
                $dados['array_img'][] = $ArrayImg; 
            } 

        }

        return $dados['array_img'];
    }

    public function array_img_agendamentos_LOCAL($agendamento)
    {
        $ag=Agendamento::find($agendamento);
        $dados['tab_img'] = AgendamentoImg::where('cd_agendamento', $agendamento)
            ->selectRaw("agendamento_img.*,date_format(created_at,'%d/%m/%Y') data")
            ->with(['agendamento' => function($q) use($ag){ 
                $q->where('cd_paciente',$ag->cd_paciente);
            },'usuario'])
            ->whereHas('agendamento', function($q)  use($ag) {
                $q->where('cd_paciente',$ag->cd_paciente);
            })
            ->orderBy('created_at', 'desc')->get();

        $dados['array_img']=null;
        foreach ($dados['tab_img'] as $imgs) {
            $CaminhoImg = null;
            clearstatcache(); // Limpamos o cache de arquivos do PHP
            $CaminhoPath=(env('URL_IMG_EXAMES', '/var/img_oftalmo'));
            $CaminhoImg = $CaminhoPath . "/" . $imgs->caminho;
            if (is_file($CaminhoImg)) { 

                $mime_type = mime_content_type($CaminhoImg);   
                $ArrayImg['tipo'] = null;
                $ArrayImg['codigo'] = $imgs->cd_agendamento_img; 
                $ArrayImg['descricao'] =  ($imgs->nm_img) ? $imgs->nm_img : ' ... '; 
                $ArrayImg['mime_type'] = $mime_type; 
                $ArrayImg['file_size'] = filesize($CaminhoImg); 
                
                if($ArrayImg['file_size']<=5629444){
                    $data = file_get_contents($CaminhoImg);
                    $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                    $ArrayImg['sn_visualiza'] = 'S'; 
                }else{ 
                    $ArrayImg['conteudo_img'] = null;
                    $ArrayImg['sn_visualiza'] = 'N';
                } 
 
                $ArrayImg['CaminhoImg'] =$CaminhoImg; 
                $ArrayImg['Caminho'] =$CaminhoImg; 
                if (str_contains($mime_type, 'image')) {
                    $ArrayImg['tipo'] = 'img';
                } 
                if (str_contains($mime_type, 'pdf')) {
                    $ArrayImg['tipo'] = 'pdf';
                } 

                $ArrayImg['usuario'] = $imgs->usuario?->nm_usuario;
                $ArrayImg['data'] = $imgs->data;
                $ArrayImg['cd_img_formulario'] = $imgs->cd_agendamento_img;
                $dados['array_img'][] = $ArrayImg;

            } 

        }

        return $dados['array_img'];
    }
 
    public function teste_pdf(Request $request)
    {
  
        $ARQ=file_get_contents('file:///C:/Users/administrator/Desktop/modelo.pdf');
      
        $certPass='br123456';
        $ReceitaEspecial='N';

        $PDF=helperAssinaturaGigital($ARQ,$request->user()->cd_profissional,$certPass,$ReceitaEspecial);

        header("Content-type:application/pdf");  
        echo base64_decode($PDF);

        exit;


        $Cert = Certificado::find($request->user()->cd_profissional);
        $Prof = Profissional::find($request->user()->cd_profissional);
        $DADOS['especial']='N';

        $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.teste' );
        //$arquivoPDF='data:application/pdf;base64,'.base64_encode($pdf->stream());
        $xx=file_get_contents('file:///C:/Users/administrator/Desktop/modelo.pdf');
        $arquivoPDF='data:application/pdf;base64,'.base64_encode($xx);
        
        $certContent =  base64_decode($Cert->pfx);
        $certPass = 'br123456';
        openssl_pkcs12_read($certContent, $certiciates, $certPass);


        if(empty($certiciates)){

            $array['retorno']=false;
            $array['msg']='Senha do certificado Invalida!';
            $array['hash']=null;
            $array['conteudo']=null;
      
        }else{
           
                        
            $CertPriv   = openssl_x509_parse(openssl_x509_read($certiciates['cert'])); 
            if(isset($CertPriv['subject']['CN'])){
                $nm=$CertPriv['subject']['CN'];
            }else{
                $nm=$Cert->pfx_razao; 
            }
            $Nome=explode(':',$nm);
            $NomeAssinatura=$Nome[0];

            $infoSignature = [
                'Name' => $NomeAssinatura,
                'Location' => '--',
                'Reason' => 'Assinatura do documento',
                'ContactInfo' => $Prof->email
            ];
 
            $pdf = new PDFSign($NomeAssinatura, $Prof->doc);
             
            $numPages = $pdf->setSourceFile($arquivoPDF);
        
            for($i = 0; $i < $numPages; $i++) {
        
                $pdf->AddPage();

                $tplId = $pdf->importPage($i+1);
        
                $pdf->setSignature(
                    signing_cert: $certiciates['cert'],
                    private_key: $certiciates['pkey'],
                    private_key_password: $certPass,
                    info: $infoSignature
                );
        
                $pdf->useTemplate($tplId, 0, 0);
                $pdf->setSignatureAppearance(10,10,10,10,1);
 
                if($DADOS['especial']=='S'){ 
                    $Assinatura = '
                    <div style="text-align: center; line-height: 8px;" >
                    <table width="100%" border="0" cellpadding="5" cellspacing="5">
                        <tr>
                            <td width="30%"><div align="center"></div></td>
                            <td width="26%"><div align="center"></div></td>
                            <td width="40%" style="  border: 1px solid #2b2f35;"><div align="center">
                            Documento assinado digitalmente <br> <b>'.mb_strtoupper($infoSignature['Name']).'</b> <br> Data :  '.date('d/m/Y H:i:s').' <br> Verifique em https://validar.iti.gov.br/
                            </div></td>
                            <td width="4%"><div align="center"></div></td>
                        </tr>
                    </table>
                    </div> ';

                    $pdf->setXY(10, 50);
                    $pdf->SetFont('',null,7);
                    $pdf->WriteHTML($Assinatura); 

                }else{ 
                    $Assinatura = '
                    <div style="text-align: center; line-height: 8px;" >
                    <table width="100%" border="0" cellpadding="5" cellspacing="5">
                        <tr>
                            <td width="30%"><div align="center"></div></td>
                            <td width="40%" style="  border: 1px solid #2b2f35;"><div align="center">
                            Documento assinado digitalmente <br> <b>'.mb_strtoupper($infoSignature['Name']).'</b> <br> Data :  '.date('d/m/Y H:i:s').' <br> Verifique em https://validar.iti.gov.br/
                            </div></td>
                            <td width="30%"><div align="center"></div></td>
                        </tr>
                    </table>
                    </div> '; 
                    $pdf->setXY(10, 249);
                    $pdf->SetFont('',null,7);
                    $pdf->WriteHTML($Assinatura);

                }

            }

            

          header("Content-type:application/pdf");
          //echo $pdf->Output('document.pdf','S');

          $arquivoPDF= base64_encode( $pdf->Output('document.pdf','S')   );
          echo base64_decode($arquivoPDF);
            
          $CertPriv   = openssl_x509_parse(openssl_x509_read($certiciates['cert'])); 
          $array['hash']=$CertPriv['hash'];
          //dd($Cert->toArray());

        }

    }


}
