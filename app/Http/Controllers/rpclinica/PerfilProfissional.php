<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Certificado;
use App\Model\rpclinica\ConfigGeral;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\DocumentoPadro;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\Formulario;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\ProfissionalEspecialidade;
use App\Model\rpclinica\ProfissionalProcedimento;
use App\Model\rpclinica\Usuario;
use FPDF as GlobalFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;



class PerfilProfissional extends Controller
{

    public function index()
    {
        $empresa=Empresa::find(Auth::user()->cd_empresa);
        $especialidades = Especialidade::all();
        $procedimentos = Procedimento::all()->where('sn_ativo','S');
        $convenios = Convenio::all();
        $profissional = Profissional::where('cd_profissional', Auth::user()->cd_profissional)->first();
        $formularios = Formulario::with('especialidade')->where('cd_profissional', Auth::user()->cd_profissional)->get();
        foreach($formularios as $idx => $valores){
            $formularios[$idx]['conteudo']=html_entity_decode(strip_tags($valores->conteudo));
            $formularios[$idx]['exame']=html_entity_decode(strip_tags($valores->exame));
            $formularios[$idx]['hipotese']=html_entity_decode(strip_tags($valores->hipotese));
            $formularios[$idx]['conduta']=html_entity_decode(strip_tags($valores->conduta));
        }
        $procedimentosProfissional = ProfissionalProcedimento::with('procedimento', 'convenio')->where('cd_profissional', Auth::user()->cd_profissional)->get();
        $especialidadesProfissional = ProfissionalEspecialidade::with('especialidade')->where('cd_profissional', Auth::user()->cd_profissional)->get();
        $documentosPadrao = DocumentoPadro::all();
        $configGeral=ConfigGeral::find(1);
        $certificado=Certificado::find(Auth::user()->cd_profissional);
  
        if(empty($profissional)){

            return redirect()->route('inicio')->with('success', 'O usuario logado não permissão para acessar essa tela!');

        }else{
            return view(
                'rpclinica.perfilProfissional.listar',
                compact('especialidades', 'formularios', 'procedimentos', 'convenios', 'procedimentosProfissional', 'especialidadesProfissional',
                    'profissional', 'documentosPadrao','configGeral','certificado')
            );
        }

    }

    public function storeConfig(Request $request)
    {


        if (!$_FILES['assinatura']['name'] == '') {
            $arrayProf['tp_assinatura']=$_FILES['assinatura']['type'];
            $arrayProf['assinatura']=base64_encode(file_get_contents($_FILES['assinatura']['tmp_name']));
            Profissional::where('cd_profissional',$request->user()->cd_profissional)
            ->update($arrayProf);
        }

        $array['sn_triagem']=$request['sn_triagem']; 

        $array['sn_historia_pregressa']=$request['sn_historia_pregressa'];
        $array['sn_anamnese']=$request['sn_anamnese'];
        $array['sn_exame_fisico']=$request['sn_exame_fisico'];
        $array['sn_conduta']=$request['sn_conduta'];
        $array['sn_hipotese_diag']=$request['sn_hipotese_diag']; 
        $array['sn_carregar_historia_pregressa']=$request['carregar_historia_pregressa'];

        $array['sn_alerta']=$request['sn_alerta'];
        $array['sn_documento']=$request['sn_documento'];
        $array['sn_exame']=$request['sn_exame'];
        $array['sn_anexo']=$request['sn_anexo'];
        $array['sn_historico']=$request['sn_historico'];
        $array['nm_header_doc']=$request['nm_header_doc'];
        $array['espec_header_doc']=$request['espec_header_doc'];
        $array['conselho_header_doc']=$request['conselho_header_doc'];
        $array['sn_logo_header_doc']=$request['sn_logo_header_doc'];
        $array['sn_header_doc']=$request['sn_header_doc'];
        $array['sn_footer_header_doc']=$request['sn_footer_header_doc'];
        $array['sn_data_header_doc']=$request['sn_data_header_doc'];
        $array['sn_assina_header_doc']=$request['sn_assina_header_doc'];
        $array['campos_prontuario']=$request['campos'];
        $array['sn_titulo_header_doc']=$request['sn_titulo_header_doc'];

        $view['sn_historia_pregressa']=($array['sn_historia_pregressa']=='S') ? false : true;
        $view['sn_anamnese']=($array['sn_anamnese']=='S') ? false : true;
        $view['sn_exame_fisico']=($array['sn_exame_fisico']=='S') ? false : true;
        $view['sn_conduta']=($array['sn_conduta']=='S') ? false : true;
        $view['sn_hipotese_diag']=($array['sn_hipotese_diag']=='S') ? false : true;

        Usuario::find($request->user()->cd_usuario)->update($array);
        if(empty($request['tp_form'])){  
            return redirect()->route('perfil-profi.listar')->with(['success' => 'Configuração atualizada com sucesso!']);
        }else{
            $config=$request->user();  
            $config->load('profissional');
            return response()->json([
                'config' => $config->toArray(),
                'request'=>$request->toArray(),
                'view'=>$view
           ]);
        }



    }

    public function storeCertificado(Request $request) {

        $validator = $request->validate([
            'certificado' => 'required',
            'senha' => 'required',
        ]);

        try {
 
            $ERRORCONV ="";
            $file = $request->file('certificado');
            $Extensao=$file->getClientOriginalExtension();
            $path= $file->getRealPath();
            if(trim($Extensao)=='pfx'){

                $pfxCertPrivado = $path;
                $cert_password  = $request['senha'];

                if (!file_exists($pfxCertPrivado)) {
                    return redirect()->back()->withInput()->withErrors(['error' => 'Certificado Invalido!']);
                }

                $pfxContent = file_get_contents($pfxCertPrivado);

                if (!openssl_pkcs12_read($pfxContent, $x509certdata, $cert_password)) {
                    return redirect()->back()->withInput()->withErrors(['error' => "O certificado não pode ser lido!!"]);
                } else {

                    $CertPriv   = array();
                    $CertPriv   = openssl_x509_parse(openssl_x509_read($x509certdata['cert']));

                    $PrivateKey = $x509certdata['pkey'];

                    $pub_key = openssl_pkey_get_public($x509certdata['cert']);
                    $keyData = openssl_pkey_get_details($pub_key);

                    $PublicKey  = $keyData['key'];

                    $dados['pfx'] = base64_encode($pfxContent);
                    $dados['pfx_validade'] = date('Y-m-d', $CertPriv['validTo_time_t']);
                    $dados['pfx_razao'] = $CertPriv['subject']['CN'];
                    $Rz= explode(':',$dados['pfx_razao']);
                    $dados['pfx_nome'] = $Rz[0];
                    $dados['pfx_cpf'] = $Rz[1];
                    $dados['pfx_hash'] = $CertPriv['hash'];
                    $dados['pfx_emissor'] = $CertPriv['issuer']['OU'];
                    $dadosEmail = $CertPriv['extensions']['subjectAltName'];
                    $Email = explode(',',$dadosEmail);
                    $email = explode(':',$Email[0]);
                    $dados['pfx_email'] = $email[1];

                    if($dados['pfx_validade']<date('Y-m-d'))
                        $dados['pfx_situacao'] = 'VENCIDO';
                    else
                        $dados['pfx_situacao'] = 'ATIVO';

                    Certificado::updateOrCreate($dados,['cd_profissional'=>$request->user()->cd_profissional]);

                    if($request['pagina']=='prontuario'){
                        
                        $retorno['certificado']=Certificado::find(Auth::user()->cd_profissional);
                        return response()->json(['request'=>$request->toArray(),'retorno'=>$retorno]);

                    }else{
                        return redirect()->route('perfil-profi.listar')->with(['success' => 'Certificado inserido  com sucesso!']);
                    }
 
                }



            }else{
                echo "Nao Foi";
            }

        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function jsonCreateFormulario(Request $request)
    {
 
        $validator = Validator::make($request->post(), [
            'nome' => 'required|string',
            'tipo_formulario' => 'required|string|in:ATE,CON,DOC,EXA,RIP',
            'especialidade' => 'sometimes|nullable|integer|exists:especialidade,cd_especialidade',
            'conteudo' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $formulario = Formulario::create([
                'nm_formulario' => $request->post('nome'),
                'tp_formulario' => $request->post('tipo_formulario'),
                'cd_especialidade' => $request->post('especialidade'),
                'conteudo' => $request->post('conteudo'),
                'exame' => $request->post('exame'),
                'hipotese' => $request->post('hipotese'),
                'conduta' => $request->post('conduta'), 
                'cd_profissional' => $request->user()->profissional->cd_profissional,
                'sn_header' => $request->post('sn_header'),
                'cd_usuario' => $request->user()->cd_usuario,
            ]);

            
            $formularios = Formulario::with('especialidade')->where('cd_profissional', Auth::user()->cd_profissional)->get();
            foreach($formularios as $idx => $valores){
                $formularios[$idx]['conteudo']=html_entity_decode(strip_tags($valores->conteudo));
                $formularios[$idx]['exame']=html_entity_decode(strip_tags($valores->exame));
                $formularios[$idx]['hipotese']=html_entity_decode(strip_tags($valores->hipotese));
                $formularios[$idx]['conduta']=html_entity_decode(strip_tags($valores->conduta));
            }

            return response()->json([
                'message' => 'Formulario criado com sucesso!',
                'formularios' => $formularios,
                'formulario' => $formulario->load('especialidade')
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function jsonUpdateFormulario(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'cd_formulario' => 'required|integer|exists:formulario,cd_formulario',
            'nome' => 'required|string',
            'tipo_formulario' => 'required|string|in:ATE,CON,DOC,EXA,RIP',
            'especialidade' => 'sometimes|nullable|integer|exists:especialidade,cd_especialidade',
            'conteudo' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $formulario = Formulario::findOrFail($request->post('cd_formulario'));
            $formulario->update([
                'nm_formulario' => $request->post('nome'),
                'tp_formulario' => $request->post('tipo_formulario'),
                'cd_especialidade' => $request->post('especialidade'),
                'conteudo' => $request->post('conteudo'), 
                'exame' => $request->post('exame'),
                'hipotese' => $request->post('hipotese'),
                'conduta' => $request->post('conduta'), 
                'sn_header' => $request->post('sn_header'),
                'up_usuario' => $request->user()->cd_usuario,
            ]);

            $formularios = Formulario::with('especialidade')->where('cd_profissional', Auth::user()->cd_profissional)->get();
            foreach($formularios as $idx => $valores){
                $formularios[$idx]['conteudo']=html_entity_decode(strip_tags($valores->conteudo));
                $formularios[$idx]['exame']=html_entity_decode(strip_tags($valores->exame));
                $formularios[$idx]['hipotese']=html_entity_decode(strip_tags($valores->hipotese));
                $formularios[$idx]['conduta']=html_entity_decode(strip_tags($valores->conduta));
            }

            return response()->json([
                'message' => 'Formulario atualizado com sucesso!',
                'formularios' => $formularios,
                'formulario' => $formulario->load('especialidade')
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function jsonDeleteFormulario(Formulario $formulario)
    {
        try {
            $formulario->delete();
        } catch (\Exception $e) {
            abort(500);
        }
    }

    // procedimentos
    public function jsonCreateProcedimento(Request $request) {
        $validator = Validator::make($request->post(), [
            'procedimento' => 'required|integer|exists:procedimento,cd_proc',
            'convenio' => 'required|integer|exists:convenio,cd_convenio',
            'valor' => 'required|currency'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            $procedimento = ProfissionalProcedimento::create([
                'cd_profissional' => $request->user()->cd_profissional,
                'cd_proc' => $request->post('procedimento'),
                'cd_convenio' => $request->post('convenio'),
                'vl_proc' => formatCurrencyForDB($request->post('valor')),
                'sn_ativo' => 'S',
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return response()->json([
                'message' => 'Procedimento criado com sucesso!',
                'procedimento' => $procedimento->load('procedimento', 'convenio')
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function jsonUpdateProcedimento(Request $request) {
        $validator = Validator::make($request->post(), [
            'cd_proc_prof' => 'required|integer|exists:profissional_proc,cd_proc_prof',
            'procedimento' => 'required|integer|exists:procedimento,cd_proc',
            'convenio' => 'required|integer|exists:convenio,cd_convenio',
            'valor' => 'required|currency'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            $procedimento = ProfissionalProcedimento::findOrFail($request->post('cd_proc_prof'));
            $procedimento->update([
                'cd_proc' => $request->post('procedimento'),
                'cd_convenio' => $request->post('convenio'),
                'vl_proc' => formatCurrencyForDB($request->post('valor')),
                'up_usuario' => $request->user()->cd_usuario
            ]);

            return response()->json([
                'message' => 'Procedimento atualizado com sucesso!',
                'procedimento' => $procedimento->load('procedimento', 'convenio')
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    // fim procedimentos

    // especialidades
    public function jsonCreateEspecialidade(Request $request) {
        $validator = Validator::make($request->post(), [
            'especialidade' => 'required|integer|exists:especialidade,cd_especialidade',
            'compartilha' => 'nullable|string|in:S,N'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            $especialidade = ProfissionalEspecialidade::create([
                'cd_profissional' => $request->user()->cd_profissional,
                'cd_especialidade' => $request->post('especialidade'),
                'sn_compartilha' => ( empty($request->post('compartilha')) ? 'N' : $request->post('compartilha') ),
                'sn_ativo' => 'S',
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return response()->json([
                'message' => 'Especialidade criado com sucesso!',
                'especialidade' => $especialidade->load('especialidade')
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function jsonUpdateEspecialidade(Request $request) {
        $validator = Validator::make($request->post(), [
            'cd_prof_espec' => 'required|integer|exists:profissional_espec,cd_prof_espec',
            'especialidade' => 'required|integer|exists:especialidade,cd_especialidade',
            'compartilha' => 'nullable|string|in:S,N'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            $especialidade = ProfissionalEspecialidade::findOrFail($request->post('cd_prof_espec'));
            $especialidade->update([
                'cd_especialidade' => $request->post('especialidade'),
                'sn_compartilha' => ( empty($request->post('compartilha')) ? 'N' : $request->post('compartilha') ),
                'up_usuario' => $request->user()->cd_usuario
            ]);

            return response()->json([
                'message' => 'Especialidade atualizada com sucesso!',
                'especialidade' => $especialidade->load('especialidade')
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function deleteCertificado(Request $request) {

        try {
 
            Certificado::whereRaw("cd_profissional = ".$request->user()->cd_profissional)->delete();
            if($request['tipo']=='prontuario'){
                return response()->json([true]);
            }else{
                return redirect()->route('perfil-profi.listar')->with(['success' => 'Certificado deletado  com sucesso!']);
            }
                

        } catch (\Exception $e) {
            if($request['tipo']=='prontuario'){
                return response()->json(['message' => $e->getMessage()], 500);
            }else{
                return redirect()->route('perfil-profi.listar')->with(['error' => $e->getMessage()]);
            }
            
        }
    }
 
    public function relacao_texto(Request $request,$tipo) {

        try {

            return response()->json([
                'texto' => $this->modeloAnamDocumento($tipo) 
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }
     
    public function store_relacao_texto(Request $request,$tipo) {

        try {
            $validator = Validator::make($request->post(), [
                'codigo' => 'nullable|integer|exists:formulario,cd_formulario',
                'conteudo' => 'required',
                'titulo' => 'required'  
            ]);
    
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
 
            if(empty($request['codigo'])){
                Formulario::create([ 
                    'nm_formulario'=> $request['titulo'], 
                    'conteudo'=>  $request['conteudo'],
                    'sn_ativo'=> 'S',
                    'cd_profissional'=>$request->user()->cd_profissional,
                    'cd_usuario'=> $request->user()->cd_usuario,
                    'tp_formulario'=> $tipo,
                ]);
            }else{
                Formulario::where('cd_formulario',$request['codigo'])->update([
                    'conteudo'=>  $request['conteudo'],
                    'nm_formulario'=> $request['titulo'],
                ]);
            }


            return response()->json([
                'texto' => $this->modeloAnamDocumento($tipo), 
                'documentos' => $this->modeloAnamDocumento('DOC'),
                'anamnese' => $this->modeloAnamDocumento('ATE')
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    public function delete_relacao_texto(Request $request,$tipo,$codigo) {

        try {
            

            Formulario::where('cd_formulario',$codigo)->delete();

            return response()->json([
                'request'=>$request->toArray(),
                'texto' => $this->modeloAnamDocumento($tipo), 
                'documentos' => $this->modeloAnamDocumento('DOC'),
                'anamnese' => $this->modeloAnamDocumento('ATE')
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    public function modeloAnamDocumento($tipo=null) {

        if(empty($tipo)){  $tipo = "'ATE','DOC'"; }
        $empresa = Empresa::find(auth()->user()->cd_empresa);
        $tpCampo=$empresa->tp_editor_html;
 
        $dados  = Formulario::where('sn_ativo','S')->whereIn('tp_formulario',[$tipo])
        ->where('cd_profissional',auth()->user()->cd_profissional)->orderBy('nm_formulario')->get()->toArray();
        
        foreach($dados as $key => $modeloAnam){  
            $dados[$key]['exame']= nl2br($modeloAnam['exame']); 
            $dados[$key]['hipotese']=nl2br($modeloAnam['hipotese']); 
            $dados[$key]['conduta']=nl2br($modeloAnam['conduta']); 
            $dados[$key]['conteudo']=nl2br($modeloAnam['conteudo']); 
        }

        return $dados;
    }


}
