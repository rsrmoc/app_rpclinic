<?php

namespace App\Http\Controllers\rpclinica;

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
use App\Model\rpclinica\Certificado;
use App\Model\rpclinica\FormulariosOftalmo;

class Consulta extends Controller
{

    public function show(Request $request, Agendamento $agendamento)
    {

        $prontuario =  DB::select("
        select * from (

            select anamnese conteudo, 'Anamnese' as nm_formulario,agendamento.dt_anamnese,
            date_format(agendamento.dt_anamnese,'%d/%m/%Y %H:%i') data,
            profissional.nm_profissional,profissional.crm,usuarios.nm_usuario,
            datediff(curdate(), date(agendamento.dt_anamnese)) diferenca
            from agendamento
            left join usuarios on usuarios.cd_usuario=agendamento.usuario_anamnese
            left join profissional on profissional.cd_profissional=agendamento.cd_profissional
            where cd_paciente=".$agendamento->cd_paciente." and ifnull(anamnese,'') <> ''
            and ifnull(agendamento.deleted_at,'')=''


            union all

            select agendamento_documentos.conteudo, nm_formulario,agendamento.dt_anamnese,
            date_format(agendamento_documentos.created_at,'%d/%m/%Y %H:%i') data,
            profissional.nm_profissional,profissional.crm,usuarios.nm_usuario,
            datediff(curdate(), date(agendamento_documentos.created_at)) diferenca
            from  agendamento_documentos
            left join agendamento on agendamento_documentos.cd_agendamento=agendamento.cd_agendamento
            left join usuarios on usuarios.cd_usuario=agendamento_documentos.cd_usuario
            left join profissional on profissional.cd_profissional=agendamento.cd_profissional
            where ifnull(agendamento_documentos.cd_pac,agendamento.cd_paciente)=".$agendamento->cd_paciente."

        ) xx
        order by dt_anamnese desc
        ");


        if(empty($agendamento->dt_inicio)){
            $agendamento->update(array('dt_inicio'=>date('Y-m-d H:i'),
            'usuario_inicio'=>$request->user()->cd_usuario,
            'situacao'=>'atendimento'));

            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO INICIOU ATENDIMENTO');
        }

        if($agendamento->cd_profissional<>$request->user()->cd_profissional){
            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'PROFISSIONAL [ '.$request->user()->cd_profissional.' ] usuario: [ '.$request->user()->cd_usuario.' ] ASSUMIU O PACIENTE');

            $agendamento->update(array('dt_inicio'=>date('Y-m-d H:i'),
            'usuario_inicio'=>$request->user()->cd_usuario,
            'situacao'=>'atendimento','cd_profissional'=>$request->user()->cd_profissional));
        }

        $historicoAgentamentos = Agendamento::with('agenda', 'profissional', 'paciente', 'especialidade', 'procedimento', 'documentos')
            ->where('cd_paciente', $agendamento->cd_paciente)
            ->orderBy('data_horario', 'desc')
            ->get();

        foreach($historicoAgentamentos as $index => $agendamentoItem) {
            $agendamentoItem->agenda?->load('profissional', 'especialidade', 'local');

            $compartilha = $agendamentoItem->profissional?->especialidades
                ->where('cd_especialidade', $agendamentoItem->cd_especialidade)
                ->where('sn_compartilha', 'N')
                ->first();

            if ($compartilha) {
                unset($historicoAgentamentos[$index]);
            }
        }

        $logsAgedamento=AgendamentoLog::whereRaw(" chave =".$agendamento->cd_agendamento)
        ->join('usuarios','usuarios.cd_usuario','agendamento_log.cd_usuario')
        ->orderBy("agendamento_log.created_at")->get();

        $formularios = Formulario::where('cd_profissional', $request->user()->cd_profissional)->orWhere('cd_profissional', '=','0')->where('sn_ativo','S')->orderBy('nm_formulario')->get();

        foreach($formularios as $formulario) {
            $CONTEUDO = ($formulario->conteudo) ? $formulario->conteudo : ' ';
            $formulario->conteudo = camposInteligentes($CONTEUDO, $agendamento->paciente, $agendamento);

        }
        $agendamento = Agendamento::find($agendamento->cd_agendamento);
        $SnCertificado=false;
        $Certificado = Certificado::find($request->user()->cd_profissional);
        if(isset($Certificado->pfx_situacao)){
            if($Certificado->pfx_situacao=='ATIVO'){
                $SnCertificado=true;
            }
        }
        
        $historico = Agendamento::with(['profissional'])->where('cd_paciente',$agendamento->cd_paciente)
                   ->join('oft_auto_refracao','oft_auto_refracao.cd_agendamento','agendamento.cd_agendamento') 
                   ->orderBy('dt_agenda','desc')->get();

        $formulario = FormulariosOftalmo::where('sn_ativo', 'S')->orderBy('ordem')->get();
        return view('rpclinica.consultorio.prontuario-eletronico', [
            'agendamento' => $agendamento->load('paciente', 'documentos','profissional','convenio','tab_situacao'),
            'historicoAgentamentos' => $historicoAgentamentos,
            'classificacaoTriagem' => ClassificacaoTriagem::all(),
            'formularios' => $formularios, 'dadosProntuario' => $prontuario,
            'logsAgedamento'=>$logsAgedamento, 'SnCertificado' => $SnCertificado,
            'formulario' => $formulario
        ]);

    }

    public function jsonSaveTriagem(Request $request, Agendamento $agendamento)
    {
        try {
            $fields = $request->only(
                "queixa_principal",
                "dt_inicio_sintoma",
                "peso",
                "altura",
                "imc",
                "temperatura",
                "cd_cid",
                "cd_classificacao",
                "arterial_sistotica",
                "arterial_diastolica",
                "frequencia_respiratoria",
                "frequencia_cardiaca",
                "informacoes_adicionais"
            );
            $fields['usuario_triagem']=$request->user()->cd_usuario;
            $fields['dt_atual_triagem']=date('Y-m-d H:i');
            $agendamento->update($fields);

            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO SALVOU TRIAGEM');

            return response()->json(['message' => 'Dados da triagem salvos com sucesso!','fields' => $fields]);
        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao salvar os dados da triagem.'], 500);
        }
    }

    public function jsonSaveAnamnese(Request $request, Agendamento $agendamento) {
        try {
            $fields = $request->only('anamnese', 'exame_fisico', 'hipotese_diagnostica', 'conduta');
            $fields['usuario_anamnese']=$request->user()->cd_usuario;
            $fields['dt_anamnese']=date('Y-m-d H:i');
            $agendamento->update($fields);

            if(trim($fields['anamnese'])=='<p>undefined</p>'){ $fields['anamnese']=null; }
            if(trim($fields['exame_fisico'])=='<p>undefined</p>'){ $fields['exame_fisico']=null; }
            if(trim($fields['hipotese_diagnostica'])=='<p>undefined</p>'){ $fields['hipotese_diagnostica']=null; }
            if(trim($fields['conduta'])=='<p>undefined</p>'){ $fields['conduta']=null; }

            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO SALVOU ANAMNESE');

            return response()->json(['message' => 'Dados da anamnese salvos com sucesso!','request' => $fields]);
        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao salvar os dados da anamnese.'], 500);
        }
    }

    public function jsonSaveListaProblemas(Request $request, Agendamento $agendamento)
    {
        try {
            $paciente = Paciente::find($agendamento->cd_paciente);

            $paciente->historico_problemas = $request->problemas;
            $paciente->save();

            LogProblemasPaciente::create([
                'cd_usuario' => $request->user()->cd_usuario,
                'cd_agendamento' => $agendamento->cd_agendamento,
                'problemas' => $paciente->historico_problemas
            ]);

            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO ATUALIZOU ETAPA DE ALERTA');

            return  response()->json(['message' => 'Historico de problemas atualizado!']);
        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao salvar os problemas.'], 500);
        }
    }

    public function jsonSaveHistoricoExames(Request $request, Agendamento $agendamento)
    {
        try {
            $paciente = Paciente::find($agendamento->cd_paciente);

            $paciente->historico_exames = $request->exames;
            $paciente->save();

            LogExamesPaciente::create([
                'cd_usuario' => $request->user()->cd_usuario,
                'cd_agendamento' => $agendamento->cd_agendamento,
                'exames' => $paciente->historico_problemas
            ]);


            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO ATUALIZOU LISTA DE EXAMES');

            return response()->json(['message' => 'Exames atualizado!']);
        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao atualizar os exames.'], 500);
        }
    }

    public function downloadPDF(Request $request, Agendamento $agendamento) {

       

        if($request['tipo']=='anamnese'){
            if($agendamento->doc_assinado==true){
                header('Content-type: application/pdf;');
                header('Content-Disposition: inline; filename="dasdad.pdf";');
                echo base64_decode($agendamento->doc_conteudo);
                exit;
            }
        }

        if($request['tipo']=='documento'){
          
            $documento = AgendamentoDocumentos::find($request->cdDocumento);
            
            if($documento->form_assinado==true){
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

        $Prof = $agendamento->cd_profissional;
        if($agendamento->cd_paciente){
            $Paciente = Paciente::find($agendamento->cd_paciente);
        }else{
            $Paciente =null;
        }
        if($agendamento->cd_especialidade){
            $Especialidade = Especialidade::find($agendamento->cd_especialidade);
        }else{
            $Especialidade =null;
        }
        if($agendamento->cd_convenio){
            $Convenio = Convenio::find($agendamento->cd_convenio);
        }else{
            $Convenio = null;
        }
        $Profissional = Profissional::find($agendamento->cd_profissional);

        

        $Empresa = Empresa::find($request->user()->cd_empresa);
        $User=Usuario::whereRaw("cd_profissional=".$agendamento->cd_profissional)->first();

        $Espec = ProfissionalEspecialidade::where('profissional_espec.cd_profissional',$agendamento->cd_profissional)
        ->join('especialidade','especialidade.cd_especialidade','profissional_espec.cd_especialidade')
        ->where('profissional_espec.sn_ativo','S')->selectRaw('nm_especialidade')->orderBy('nm_especialidade')->get();
        $Cont = count($Espec);
        $ESPECIALIDADE = NULL;
        foreach ($Espec as $key => $espec) {
            if(($Cont-1)==$key){ $ESPECIALIDADE = $ESPECIALIDADE . ' e '; }else { $ESPECIALIDADE = $ESPECIALIDADE . ' '; }
            $ESPECIALIDADE = $ESPECIALIDADE . ucwords( mb_strtolower($espec->nm_especialidade) );
        }

        $HeaderFooter = FormularioHeaderFooter::find('DOC');

        

        if(!$User->nm_header_doc){
            $execultante['nome']=$Profissional->nm_profissional;
        }else{
            $execultante['nome']=$User->nm_header_doc;
        }

        if(!$User->espec_header_doc){
            $execultante['espec']=$ESPECIALIDADE;
        }else{
            $execultante['espec']=$User->espec_header_doc;
        }

        if(!$User->conselho_header_doc){
            $execultante['conselho']=$Profissional->crm;
        }else{
            $execultante['conselho']=$User->conselho_header_doc;
        }
        $execultante['logo']=$Empresa->logo;
        $execultante['header']=(!$request['header']) ? 'N' : $request['header'];
        $execultante['footer']=(!$request['footer']) ? 'N' : $request['footer'];
        $execultante['data']=(!$request['data']) ? 'N' : $request['data'];
        $execultante['sn_logo']=(!$request['logo']) ? 'N' : $request['logo'];
        $execultante['sn_assinatura']=(!$sn_assinatura) ? 'N' : $sn_assinatura;
        $execultante['assinatura']= $Profissional->assinatura;
        $execultante['tp_assinatura']= $Profissional->tp_assinatura;
        $execultante['end_empresa']= $Empresa->end;

        

        if(empty($tipo)){ $tipo='anamnese';  }
        $TpDocumento = 'Anamnese';
        if ($tipo == 'documento' && $request->has('cdDocumento')) {
            $documento = AgendamentoDocumentos::find($request->cdDocumento);

            if($documento['cd_formulario']>=0){
                $Ret=Formulario::where('cd_formulario',$documento['cd_formulario'])->first();

                $sn_header = $Ret['sn_header'];
                $TpDocumento = $Ret['tp_documento'];
            }
        }


        if( ($TpDocumento=='Receituário de Controle Especial') || ($sn_especial=='S') ){

            $pdf = FacadePdf::loadView('rpclinica.consultorio.documentos_espec', compact('agendamento', 'usuario', 'tipo', 'documento','sn_header','Profissional','ESPECIALIDADE','TpDocumento','HeaderFooter','Paciente','Especialidade','Convenio','execultante'));

        }else{

            $pdf = FacadePdf::loadView('rpclinica.consultorio.documentos', compact('agendamento', 'usuario', 'tipo', 'documento','sn_header','Profissional','ESPECIALIDADE','TpDocumento','HeaderFooter','Paciente','Especialidade','Convenio','execultante'));

        }


        if(empty($request['assinar_digital'])){

            return $pdf->stream('Documento.pdf');

        }else{

            if($request['assinar_digital']=='S'){

                if(empty($request->user()->cd_profissional)){
                    return response()->json(['message' => 'O usuario não Profissional!'], 400);
                }

                $Cert = Certificado::find($request->user()->cd_profissional);
                $Prof = Profissional::find($request->user()->cd_profissional);

                if(empty($Cert)){
                    return response()->json(['message' => 'O Profissional não tem certificado habilitado!'], 400);
                }

                if($Cert->pfx_validade < date('Y-m-d')){
                    return response()->json(['message' => 'O certificado encontrasse vencido [ '.$Cert->pfx_validade.' ]!'], 400);
                }

                if(empty($request['senha'])){
                    return response()->json(['message' => 'A senha não foi infornado!'], 400);
                }
                $nm=rand();
                $NmPdfAss=$nm."_assinado.pdf";
                $NmPdf=$nm.".pdf";

                $pdf->render();
                $pdf = $pdf->output();
                file_put_contents('certificado/'.$NmPdf, $pdf);

                $DADOS['RecEsp']=$request['especial'];
                $DADOS['certificado']=$Cert;
                $DADOS['profissional']=$Prof;
                $DADOS['senha']=$request['senha'];
                $DADOS['doc']=$NmPdf;
                $DADOS['doc_ass']=$NmPdfAss;
                $DADOS['especial']=$request['especial'];
                
                //FuncHelper
                $dados=certidicadoDigital($DADOS);

                

                $sn_assinado=$dados['retorno'];
                $msg=$dados['msg'];
                $hash=$dados['hash'];
                $conteudo=$dados['conteudo'];
                $retorno=null;
                $Docs=null;
                if($sn_assinado==true){
                   
                    if($request['tipo']=='anamnese'){

                        $dadosAssinatura['doc_assinado']=$sn_assinado;
                        $dadosAssinatura['doc_conteudo']=$conteudo;
                        $dadosAssinatura['dt_assinado_digital']=date('Y-m-d H:i:s');
                        $dadosAssinatura['user_assinado_digital']= $request->user()->cd_usuario;
                        $dadosAssinatura['hash_assinado_digital']=$hash;
                        $retorno=Agendamento::find($agendamento->cd_agendamento)->update($dadosAssinatura);
                        if($retorno == true){
                            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO ASSINOU DOCUMENTO  DIGITALMENTE (anamnese)');
                            $msg="Certificado assinado com sucesso!";
                        }

                    }

                    if($request['tipo']=='documento'){

                        $dadosAssinatura['form_assinado']=$sn_assinado;
                        $dadosAssinatura['form_conteudo']=$conteudo;
                        $dadosAssinatura['dt_assinado_digital_form']=date('Y-m-d H:i:s');
                        $dadosAssinatura['user_assinado_digital_form']= $request->user()->cd_usuario;
                        $dadosAssinatura['hash_assinado_digital_form']=$hash;
                         $retorno=AgendamentoDocumentos::find($request['cdDocumento'])->update($dadosAssinatura);

                        if($retorno == true){
                            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO ASSINOU DOCUMENTO  DIGITALMENTE ( formulário [ cod: '.$request['cdDocumento'].' ] )');
                            $msg="Certificado assinado com sucesso!";
                        }

                        $Docs = AgendamentoDocumentos::where('cd_agendamento',$agendamento->cd_agendamento)->orderByRaw('created_at desc')->get();

                    }

                }else{
                    $dados['retorno'] = false;
                    $dados['conteudo'] = null;
                }

                $retornoProcesso['retorno'] = $retorno;
                //$retornoProcesso['conteudo'] = $conteudo;
                $retornoProcesso['sn_assinado'] = $sn_assinado;
                $retornoProcesso['msg'] = $msg;
                $retornoProcesso['documentos'] = $Docs;

                return response()->json($retornoProcesso);

            }

        }

    }



    public function finalizarConsulta(Request $request, Agendamento $agendamento) {
        $agendamento->update(['situacao' => 'atendido',
                              'dt_finalizacao'=>date('Y-m-d H:i'),
                              'usuario_finalizacao'=>$request->user()->cd_usuario,
                              'sn_finalizado'=>'S']);

                              funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO FINALIZOU ATENDIMENTO');

        return redirect()->route('consultorio');
    }

    public function indexAnexos(Agendamento $agendamento) {
        return response()->json(['anexos'=>$agendamento->anexos,'doc_assinado' =>$agendamento->doc_assinado,'doc_conteudo'=>$agendamento->doc_conteudo ]);
    }

    public function storeAnexos(Request $request, $cd_agendamento) {
        $request->validate([
            "files" => "required|array",
            "files.*" => "required|file|mimes:webp,jpeg,jpg,bmp,png,pdf|max:1024",
        ]);

        try {
            DB::transaction(function() use($request, $cd_agendamento) {
                foreach($request->file("files") as $file) {
                    $data = [
                        "cd_agendamento" => $cd_agendamento,
                        "nome" => $file->getClientOriginalName(),
                        "tipo" => $file->extension(),
                        "tamanho" => $file->getSize(),
                        "cd_usuario" => $request->user()->cd_usuario,
                        "arquivo" => utf8_encode( file_get_contents($file->getPathName()) ),
                        "created_at" => Carbon::now()
                    ];

                    funcLogsAtendimentoHelpers($cd_agendamento,'SUARIO ANEXOU ARQUIVO');


                    DB::table('agendamento_anexos')->insert($data);
                }
            });

            return response()->json([
                "message" => "Arquivos salvos com sucesso!",
                // "anexos" => AgendamentoAnexos::where('cd_agendamento', $cd_agendamento)->get()
            ]);
        }
        catch (Throwable $th) {
            return response()->json(["message" => $th->getMessage()], 500);
        }
    }

    public function destroyAnexos(Request $request,$cd_anexo) {
        $agendamento=AgendamentoAnexos::find($cd_anexo)->delete();

        funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO DELETOU ANEXOS');

        return response()->json(['message' => 'Excluído com sucesso!']);
    }

    public function teste(Request $request) {


        //dd($documento->toArray());

        //return view('rpclinica.consultorio.documento_teste');
        $pdf = FacadePdf::loadView('rpclinica.consultorio.documento_teste',compact('documento'));
        return $pdf->stream();
    }

    public function doc_padrao(Request $request) {


        $validator = Validator::make($request->all(), [
            'conteudo' => 'required',
            'tipo' => 'required',
            'titulo' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            $array['conteudo']= $request['conteudo'];
            $array['nm_formulario']= $request['titulo'];
            $array['tp_formulario']= $request['tipo'];
            $array['sn_ativo']= 'S';
            $array['cd_usuario']=  $request->user()->cd_usuario;
            $array['cd_profissional']=  $request->user()->cd_profissional;
            $formulario = Formulario::create($array);

            return response()->json(['message' => 'Documento Salvo com sucesso!']);

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao processar informação.'], 200);
        }
    }

    public function assinaturaDigital(Request $request ) {

        if($request['tipo']=='anamnese'){

            $validator = Validator::make($request->all(), [
                'agendamento' => 'required|integer|exists:agendamento,cd_agendamento',
                'senha' => 'required',
                'tipo' => 'required',
            ]);

        }

        if($request['tipo']=='documento'){

            $validator = Validator::make($request->all(), [
                'agendamento' => 'required|integer|exists:agendamento,cd_agendamento',
                'senha' => 'required',
                'codigo' => 'required|integer|exists:agendamento_documentos,cd_documento',
                'tipo' => 'required',
            ]);

        }

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            $Profissional = Profissional::find($request->user()->cd_profissional);
            $DADOS['Profissional']=$Profissional;
            $DADOS['RecEsp']=$request['RecEsp'];
            $dados=certidicadoDigital($DADOS);

            $sn_assinado=$dados->retorno;
            $msg=$dados->msg;
            $hash=$dados->hash;
            $conteudo=$dados->conteudo;
            $retorno=null;
            $Docs=null;
            if($sn_assinado==true){

                if($request['tipo']=='anamnese'){

                    $dadosAssinatura['doc_assinado']=$sn_assinado;
                    $dadosAssinatura['doc_conteudo']=$conteudo;
                    $dadosAssinatura['dt_assinado_digital']=date('Y-m-d H:i:s');
                    $dadosAssinatura['user_assinado_digital']= $request->user()->cd_usuario;
                    $dadosAssinatura['hash_assinado_digital']=$hash;
                    $retorno=Agendamento::find($request['agendamento'])->update($dadosAssinatura);
                    if($retorno == true){
                        funcLogsAtendimentoHelpers($request['agendamento'],'USUARIO ASSINOU DOCUMENTO  DIGITALMENTE (anamnese)');
                        $msg="Certificado assinado com sucesso!";
                    }

                }

                if($request['tipo']=='documento'){

                    $dadosAssinatura['form_assinado']=$sn_assinado;
                    $dadosAssinatura['form_conteudo']=$conteudo;
                    $dadosAssinatura['dt_assinado_digital_form']=date('Y-m-d H:i:s');
                    $dadosAssinatura['user_assinado_digital_form']= $request->user()->cd_usuario;
                    $dadosAssinatura['hash_assinado_digital_form']=$hash;
                    $retorno=AgendamentoDocumentos::find($request['codigo'])->update($dadosAssinatura);
                    if($retorno == true){
                        funcLogsAtendimentoHelpers($request['agendamento'],'USUARIO ASSINOU DOCUMENTO  DIGITALMENTE ( formulário [ cod: '.$request['codigo'].' ] )');
                        $msg="Certificado assinado com sucesso!";
                    }

                    $Docs = AgendamentoDocumentos::where('cd_agendamento',$request['agendamento'])
                    ->orderByRaw('created_at desc')->get();

                }

            }else{
                $dados['retorno'] = false;
                $dados['conteudo'] = null;
            }

            $retornoProcesso['retorno'] = $retorno;
            $retornoProcesso['conteudo'] = $conteudo;
            $retornoProcesso['sn_assinado'] = $sn_assinado;
            $retornoProcesso['msg'] = $msg;
            $retornoProcesso['documentos'] = $Docs;

            return response()->json($retornoProcesso);

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao processar informação.'], 200);
        }

    }

    public function deleteAssinatura(Request $request,$agendamento,$tipo,$codigo ) {

        try {

            if($tipo=='anamnese'){
                $dadosAssinatura['doc_assinado']=null;
                $dadosAssinatura['doc_conteudo']=null;
                $dadosAssinatura['dt_assinado_digital']=null;
                $dadosAssinatura['user_assinado_digital']= null;
                $dadosAssinatura['hash_assinado_digital']=null;
                $retorno=Agendamento::find($request['agendamento'])->update($dadosAssinatura);
                if($retorno == true){
                    funcLogsAtendimentoHelpers($agendamento,'USUARIO DELETOU DOCUMENTO ASSINADO DIGITALMENTE  (anamnese)');
                }
                return response()->json(['retorno'=>$retorno]);
            }

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao processar informação.'], 200);
        }

    }

    public function historicoDocumento(Request $request ) {

        $validator = Validator::make($request->all(), [
            'agendamento' => 'required|integer|exists:agendamento,cd_agendamento',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            $queryAgendamento = Agendamento::find($request['agendamento']);

            $retorno = Agendamento::where("cd_paciente",$queryAgendamento['cd_paciente'])
            ->where("cd_profissional",$queryAgendamento['cd_profissional'])
            ->join('agendamento_documentos','agendamento_documentos.cd_agendamento',
            'agendamento.cd_agendamento')
            ->selectRaw("agendamento_documentos.cd_documento,agendamento_documentos.conteudo,
            DATE_FORMAT(agendamento_documentos.created_at, '%d/%m/%Y') dt_doc,nm_formulario,
            agendamento_documentos.cd_formulario")
            ->orderByRaw("agendamento_documentos.created_at desc")
            ->limit(15)
            ->get();

            return response()->json(['retorno'=>$retorno]);

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao processar informação.'], 200);
        }

    }



}
