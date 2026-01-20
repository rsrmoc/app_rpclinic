<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\AgendamentoSituacao;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\SituacaoItem;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class Empresas extends Controller
{
 
    public function index(Request $request)
    {
        $situacao = AgendamentoSituacao::where('sn_ativo','S')->orderBy('nm_situacao')->get();
        $situacaoItens = SituacaoItem::where('tipo','central_laudos')->orderBy('nm_situacao_itens')->get();
        $empresa = Empresa::first(); 
        if($empresa === null) 
            return view('rpclinica.empresa.add', compact('empresa','situacao','situacaoItens'));
        else 
            return view('rpclinica.empresa.edit', compact('empresa','situacao','situacaoItens'));
    }

    public function msg(Request $request, Empresa $empresa) { 
        $validator = Validator::make($request->all(), [
            'texto' => 'nullable|string', 
            'tipo' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        if($request['tipo'] == 'AG'){
            $empresa->update(['msg_agendamento'=>utf8_encode($request['texto']),'sn_agendamento'=> (($request['sn_agendamento']=='sim') ? 'sim' : 'nao') ,'situacao_agendamento'=>$request['situacao_agendamento']]);
        }
        if($request['tipo'] == 'LA'){
            $empresa->update(['msg_laudo'=>utf8_encode($request['texto']),'sn_laudo'=> (($request['sn_laudo']=='sim') ? 'sim' : 'nao') ,'situacao_laudo'=>$request['situacao_laudo']]);
        }
        if($request['tipo'] == 'PE'){
 

            $empresa->update(['pesquisa_satisfacao'=>utf8_encode($request['texto']),'sn_pesquisa'=> (($request['sn_pesquisa']=='sim') ? 'sim' : 'nao') ,'situacao_pesquisa'=>$request['situacao_pesquisa']]);
            if(isset($_FILES['img_pesquisa']['tmp_name'])){
                if($_FILES['img_pesquisa']['tmp_name']){
                    $base64 = base64_encode(file_get_contents($_FILES['img_pesquisa']['tmp_name']));
                    $array['logo_pesq_satisf'] = $base64;
                    $array['type_logo_pesq_satisf'] = $_FILES['img_pesquisa']['type'];
                    $empresa->update($array);
                }
            } 
        }
        if($request['tipo'] == 'NI'){
            $empresa->update(['msg_niver'=> utf8_encode($request['texto']) ,'sn_niver'=> (($request['sn_niver']=='sim') ? 'sim' : 'nao') ]);
        }
        if($request['tipo'] == 'AC'){
            $empresa->update(['msg_ag_confirm'=>utf8_encode($request['texto']),'sn_ag_confirm'=> (($request['sn_ag_confirm']=='sim') ? 'sim' : 'nao') ,'situacao_ag_confirm'=>$request['situacao_ag_confirm']]);
        }
        if($request['tipo'] == 'AA'){
            $empresa->update(['msg_ag_cancel'=>utf8_encode($request['texto']),'sn_ag_cancel'=> (($request['sn_ag_cancel']=='sim') ? 'sim' : 'nao') ,'situacao_ag_cancel'=>$request['situacao_ag_cancel']]);
        }
        if($request['tipo'] == 'FA'){
            $empresa->update(['msg_faltou'=>utf8_encode($request['texto']),'sn_faltou'=> (($request['sn_faltou']=='sim') ? 'sim' : 'nao') ,'situacao_faltou'=>$request['situacao_faltou']]);
        }
        return redirect()->route('empresa.listar')->with('success', 'Mensagem atualizada com sucesso!');
        
    }

    public function create(Request $request)
    {
        abort(500);
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'razao' => 'required|string',
            'regime' => 'required|in:SN,ME,LR,LP',
            'cnpj' => 'required|cnpj',
            'inscricao_est' => 'required|string',
            'inscricao_mun' => 'required|string',
            'ativo' => 'required|in:S,N',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'end' => 'nullable'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
 
            if($request->hasFile('logo')){
                  $base64 = base64_encode(file_get_contents($_FILES['logo']['tmp_name']));
            }else{
                $base64 = null;
            }
             
            Empresa::create([
                'nm_empresa' => $request->post('nome'),
                'razao_social' => $request->post('razao'),
                'regime' => $request->post('regime'),
                'cep' => $request->post('cep'),
                'cidade' => $request->post('cidade'),
                'bairro' => $request->post('bairro'),
                'numero' => $request->post('numero'),
                'uf' => $request->post('uf'),
                'email' => $request->post('email'),
                'contato' => $request->post('contato'),
                'cnes' => $request->post('cnes'),
                'insc_estadual' => $request->post('inscricao_est'),
                'insc_municipal' => $request->post('inscricao_mun'),
                'cnpj' => $request->post('cnpj'),
                'segunda' => ($request['segunda'])? '1' : null,
                'terca' => ($request['terca'])? '1' : null,
                'quarta' => ($request['quarta'])? '1' : null,
                'quinta' => ($request['quinta'])? '1' : null,
                'sexta' => ($request['sexta'])? '1' : null,
                'sabado' => ($request['sabado'])? '1' : null,
                'domingo' => ($request['domingo'])? '1' : null,
                'hr_inicial' => ($request['hora_inicial'])? $request['hora_inicial'] : null,
                'hr_final' => ($request['hora_final'])? $request['hora_final'] : null,
                'cnpj' => $request->post('cnpj'),
                'logo' =>  $base64 ,
                'type_logo' => $_FILES['logo']['type'],
                'end' => $request->post('end'),
                'sn_ativo' => $request->post('ativo'),
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('empresa.listar')->with('success', 'Empresa cadastrada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar a empresa.']);
        }
    }

    public function edit(Empresa $empresa) { 
        abort(500);
    }

    
    public function update(Request $request, Empresa $empresa)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'razao' => 'required|string',
            'regime' => 'required|in:SN,ME,LR,LP',
            'cnpj' => 'required|cnpj',
            'inscricao_est' => 'nullable|string',
            'inscricao_mun' => 'nullable|string',
            'ativo' => 'required|in:S,N',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'end' => 'nullable'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
 
            $array = array(
                'nm_empresa' => $request->post('nome'),
                'razao_social' => $request->post('razao'),
                'regime' => $request->post('regime'),
                'cep' => $request->post('cep'),
                'cidade' => $request->post('cidade'),
                'bairro' => $request->post('bairro'),
                'numero' => $request->post('numero'),
                'uf' => $request->post('uf'),
                'email' => $request->post('email'),
                'contato' => $request->post('contato'),
                'cnes' => $request->post('cnes'),
                'insc_estadual' => $request->post('inscricao_est'),
                'insc_municipal' => $request->post('inscricao_mun'),
                'cnpj' => $request->post('cnpj'),
                'segunda' => ($request['segunda'])? '1' : null,
                'terca' => ($request['terca'])? '1' : null,
                'quarta' => ($request['quarta'])? '1' : null,
                'quinta' => ($request['quinta'])? '1' : null,
                'sexta' => ($request['sexta'])? '1' : null,
                'sabado' => ($request['sabado'])? '1' : null,
                'domingo' => ($request['domingo'])? '1' : null,
                'hr_inicial' => ($request['hora_inicial'])? $request['hora_inicial'] : null,
                'hr_final' => ($request['hora_final'])? $request['hora_final'] : null,
                'sn_ativo' => $request->post('ativo'),
                'end' => $request->post('end'),
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario
            );
        
            if(isset($_FILES['logo']['tmp_name'])){
                if($_FILES['logo']['tmp_name']){
                    $base64 = base64_encode(file_get_contents($_FILES['logo']['tmp_name']));
                    $array['logo'] = $base64;
                    $array['type_logo'] = $_FILES['logo']['type'];
                }
            } 
       
            if(isset($_FILES['logo-mini']['tmp_name'])){
                if($_FILES['logo-mini']['tmp_name']){
                    $base64 = base64_encode(file_get_contents($_FILES['logo-mini']['tmp_name']));
                    $array['mini_logo'] = $base64;
                    $array['type_mini_logo'] = $_FILES['logo-mini']['type'];
                }
            } 
            
            $empresa->update($array);

            return redirect()->route('empresa.listar')->with('success', 'Empresa atualizada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar a empresa.']);
        }
    }

    public function updateConf(Request $request, Empresa $empresa)
    {
      
        try {
 
  
            unset($request['_token']);  
           
            $empresa->update($request->toArray());
            
            return redirect()->route('empresa.listar')->with('success', 'Configuração atualizada com sucesso!');

        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar a empresa.']);
        }
    }

    public function delete(Empresa $empresa)
    {
        abort(500);
    }
    public function delete_img_pesquisa(Empresa $empresa)
    {

        try {
            
            $empresa->update(['logo_pesq_satisf'=>null,'type_logo_pesq_satisf'=>null]);

        } catch (Throwable $error) {   
            return response()->json([ 'retorno'=>false,'mensagem'=>'Erro ao enviar mensagem! <br>'.$error->getMessage()]); 
        }

    }

    
}
