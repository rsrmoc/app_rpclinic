<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\FaturamentoConta;
use App\Model\rpclinica\LocalAtendimento;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\ProfissionalEspecialidade;
use App\Model\rpclinica\ProfissionalProcedimento;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Bibliotecas\SimpleXLSX;
use App\Model\rpclinica\Agenda;
use App\Model\rpclinica\AgendamentoItens;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\GuiaSituacao;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\SituacaoItem;
use Illuminate\Support\Facades\Route;

class Faturamento extends Controller
{

    public function contas(Request $request)
    {


 
        $parametros['dti']= date('Y-m-d',strtotime('-20 day', strtotime(date('Y-m-d'))));
        $parametros['dtf']= date('Y-m-d');
        $parametros['convenio']= Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();
        $parametros['profissional']= Profissional::where('sn_ativo','S')->orderBy('nm_profissional')->get();
        $parametros['agenda']= Agenda::where('sn_ativo','S')->orderBy('nm_agenda')->get();
        $parametros['itens']= Exame::where('sn_ativo','S')->orderBy('nm_exame')->get();
        $parametros['situacao_guia']= GuiaSituacao::orderBy('nm_situacao')->get();
        $parametros['situacao_conta']= SituacaoItem::orderBy('nm_situacao_itens')->get();
 
        return view('rpclinica.faturamento.contas', compact('parametros'));
    }

    public function jsonContas(Request $request)
    {

        try {

            $itemsPerPage = ($request['itemsPerPage']) ? $request['itemsPerPage'] : 50;
            $query=AgendamentoItens::PainelFaturamento($request)
            ->selectRaw("agendamento_itens.*, 
                        format(vl_recebido, 2, 'pt_BR') valor_recebido, 
                        format(vl_glosado, 2, 'pt_BR') valor_glosado,
                        format(vl_total, 2, 'pt_BR') valor_total, 
                        format(vl_item, 2, 'pt_BR') valor_item")
            ->orderby("cd_agendamento")
            ->paginate($itemsPerPage)->appends($request->query());
            return response()->json(['request'=>$request->toArray(),'query'=>$query]);

        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro ao cadastrar o usuário. ' . $e->getMessage()]);
        }

    }

    public function storeFaturamento(Request $request, AgendamentoItens $item)
    {
        $validator = Validator::make($request->all(), [
            'situacao' => 'required|exists:situacao_itens,cd_situacao_itens',  
        ]);
        
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
        try {
             
            $item->update([
                'cd_status_faturamento'=> $request['situacao'],
                'dt_lacnc_fat'=> date('Y-m-d H:i'),
                'fat_user'=> $request->user()->cd_usuario,
                'vl_recebido'=> str_replace(',', '.', str_replace('.', '', $request['vl_recebido'])),
                'vl_glosado'=> str_replace(',', '.', str_replace('.', '', $request['vl_glosado'])) 
            ]);

            $dados['codigo']=$item->cd_agendamento_item; 
            $query=AgendamentoItens::PainelFaturamento($dados)
            ->selectRaw("agendamento_itens.*, 
                        format(vl_recebido, 2, 'pt_BR') valor_recebido, 
                        format(vl_glosado, 2, 'pt_BR') valor_glosado,
                        format(vl_total, 2, 'pt_BR') valor_total, 
                        format(vl_item, 2, 'pt_BR') valor_item")
            ->first(); 

            return response()->json(['message' => 'Registro atualizado com sucesso!',
                                     'request'=>$request->toArray(),
                                     'query'=>$query]);

        } catch (Exception $e) {
             return response()->json(['message' => 'Erro ao atualizar registro. ' . $e->getMessage()],500);
        }

    }
     
    public function jsonItensConta(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cd_atendimento' => 'required|integer|exists:faturamento_conta,cd_atendimento',
            'cd_conta' => 'required|integer|exists:faturamento_conta,cd_conta',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            $dados['retorno']=FaturamentoConta::whereRaw("cd_conta=".$request['cd_conta'])
            ->selectRaw("faturamento_conta.*,FORMAT(vl_total, 2, 'de_DE') valor_total,
            FORMAT(vl_unitario, 2, 'de_DE') valor_unitario
            ")
            ->whereRaw("cd_atendimento=".$request['cd_atendimento'])->get();
            return response()->json($dados);

        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro ao cadastrar o usuário. ' . $e->getMessage()]);
        }

    }


    public function create(Request $request)
    {
        $convenios = Convenio::all();

        return view('rpclinica.paciente.add', compact('convenios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "nome" => "required|string|max:255",
            "nome_social" => "nullable|string|max:255",
            "data_de_nascimento" => "required|date",
            "sexo" => 'nullable|in:H,M',
            "estado_civil" => "nullable|in:S,C,D,V",
            "rg" => "nullable",
            "cpf" => "nullable",
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
            "telefone" => "nullable",
            "celular" => "nullable",
        ]);

        try {
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
                'cpf' => $request->cpf,
                'nm_mae' => $request->nome_da_mae,
                'nm_pai' => $request->nome_do_pai,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'nm_bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'uf' => $request->uf,
                'cep' => $request->cep,
                'fone' => $request->telefone,
                'celular' => $request->celular,
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
            datediff(curdate(), date(agendamento.dt_anamnese)) diferenca
            from agendamento
            left join usuarios on usuarios.cd_usuario=agendamento.usuario_anamnese
            left join profissional on profissional.cd_profissional=agendamento.cd_profissional
            where cd_paciente=61483775 and ifnull(anamnese,'') <> ''

            union all

            select agendamento_documentos.conteudo, nm_formulario,agendamento.dt_anamnese,
            date_format(agendamento_documentos.created_at,'%d/%m/%Y %H:%i') data,
            profissional.nm_profissional,profissional.crm,usuarios.nm_usuario,
            datediff(curdate(), date(agendamento_documentos.created_at)) diferenca
            from agendamento
            inner join agendamento_documentos on agendamento_documentos.cd_agendamento=agendamento.cd_agendamento
            left join usuarios on usuarios.cd_usuario=agendamento_documentos.cd_usuario
            left join profissional on profissional.cd_profissional=agendamento.cd_profissional
            where cd_paciente=61483775

        ) xx
        order by dt_anamnese
        ");

        $convenios = Convenio::all();

        return view('rpclinica.paciente.edit', compact('convenios', 'paciente', 'prontuario'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $request->validate([
            "nome" => "required|string|max:255",
            "nome_social" => "nullable|string|max:255",
            "data_de_nascimento" => "required",
            "sexo" => 'nullable|in:H,M',
            "estado_civil" => "nullable|in:S,C,D,V",
            "rg" => "nullable",
            "cpf" => "nullable",
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
            "telefone" => "nullable",
            "celular" => "nullable",
        ]);

        try {
            $paciente->update([
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
                'nm_pai' => $request->nome_do_pai,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'nm_bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'uf' => $request->uf,
                'cep' => $request->cep,
                'fone' => $request->telefone,
                'celular' => $request->celular,
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
            $pacientes = Paciente::select('cd_paciente as id', 'nm_paciente as text')
                ->where('nm_paciente', 'LIKE', '%' . $request->q . '%')
                ->paginate(20);
        } else {
            $pacientes = Paciente::select('cd_paciente as id', 'nm_paciente as text')
                ->paginate(20);
        }

        return $pacientes->items();
    }

    public function jsonIniciarConsulta(Request $request) {
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
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Houve um erro ao iniciar a consulta.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function jsonShowPaciente(Request $request) {
        $validator = Validator::make($request->all(), [
            'cd_paciente' => 'required|integer|exists:paciente,cd_paciente',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        return response()->json(Paciente::find($request->cd_paciente));
    }


    public function import(Request $request ) {

        $validated = $request->validate([
            'xls' => 'required|mimes:xlsx'
        ]);

        try {
            $ERRORCONV ="";
            $file = $request->file('xls');
            $Extensao=$file->getClientOriginalExtension();
            $path= $file->getRealPath();
            if(trim($Extensao)=='xlsx'){
                $xlsx = new SimpleXLSX( $path );
                list($cols,) = $xlsx->dimension();
                $ERRO=FALSE;
                foreach( $xlsx->rows() as $key => $coluna) {

                    if($key>0){
                        $dado['Prof'] =$coluna[0];
                        $dado['Exec'] =$coluna[1];
                        $dado['Conv'] =$coluna[2];
                        $dado['Data'] =$coluna[3];
                        $dado['Pac'] =$coluna[4];
                        $dado['CodProc'] =$coluna[5];
                        $dado['DsProc'] =$coluna[6];
                        $dado['Qtde'] =$coluna[7];
                        $dado['VlUnit'] =$coluna[8];
                        $dado['VlTotal'] =$coluna[9];
                        $dado['Atend'] =$coluna[10];
                        $dado['Conta'] =$coluna[11];
                        $Prof=Profissional::whereRaw(" upper(nm_profissional) = '".mb_strtoupper($dado['Prof'])."'")->first();
                        if(isset($Prof)){ $cdProf = $Prof->cd_profissional; }else{ $ErroProf[] = $dado['Prof'];  $ERRO=TRUE; }

                        $Exec=Profissional::whereRaw(" upper(nm_profissional) = '".mb_strtoupper($dado['Exec'])."'")->first();
                        if(isset($Exec)){ $cdExec = $Exec->cd_profissional; }else{  $ErroExec[]=$dado['Exec'];  $ERRO=TRUE;  }

                        $conv=DB::table("convenio")->whereRaw(" upper(nm_convenio) = '".mb_strtoupper($dado['Conv'])."'")->first();
                        if(isset($conv)){ $cdconv = $conv->cd_convenio; }else{  $ErroConv[]=$dado['Conv'];  $ERRO=TRUE;  }

                    }

                }
                $ERROR='';
                if($ERRO==TRUE){
                    $ErroConv=array_unique($ErroConv);
                    $ErroProf=array_unique($ErroProf);
                    $ErroExec=array_unique($ErroExec);
                    $ERRORCONV = "CONVÊNIO - Codigo não encontrado<br>";
                    $ERRORCONV = $ERRORCONV. "<ul>";
                    foreach($ErroConv as $Ccnv){
                        $ERRORCONV = $ERRORCONV. "<li>".$Ccnv."</li>";
                    }
                    $ERRORCONV = $ERRORCONV. "</ul>";

                    $ERRORCONV = $ERRORCONV.  "<br>PROFISSIONAL -  Codigo não encontrado<br>";
                    $ERRORCONV = $ERRORCONV. "<ul>";
                    foreach($ErroProf as $Ccnv){
                        if(trim($Ccnv)){
                            $ERRORCONV = $ERRORCONV. "<li>".$Ccnv."</li>";
                        }
                    }
                    $ERRORCONV = $ERRORCONV. "</ul>";

                    $ERRORCONV = $ERRORCONV."<br>EXECUTANTE - Codigo não encontrado<br>";
                    $ERRORCONV = $ERRORCONV. "<ul>";
                    foreach($ErroExec as $Ccnv){
                        if(trim($Ccnv)){
                            $ERRORCONV = $ERRORCONV. "<li>".$Ccnv."</li>";
                        }
                    }
                    $ERRORCONV = $ERRORCONV. "</ul>";
                }

                if($ERRO==FALSE){
                    foreach( $xlsx->rows() as $key => $coluna) {

                        if($key>0){

                            $dados['nm_profissional'] =$coluna[0];
                            $dados['nm_prof_exec'] =$coluna[1];
                            $dados['nm_convenio'] =$coluna[2];
                            $dados['dt_conta'] =$coluna[3];
                            $dados['nm_paciente'] =$coluna[4];
                            $dados['cod_proc'] =$coluna[5];
                            $dados['ds_proc'] =$coluna[6];
                            $dados['qtde'] =$coluna[7];
                            $dados['vl_unitario'] =$coluna[8];
                            $dados['vl_total'] =$coluna[9];
                            $dados['cd_atendimento'] =$coluna[10];
                            $dados['cd_conta'] =$coluna[11];
                            $dados['created_at'] =date('Y-m-d H:i');

                            $select = DB::table("faturamento_conta")->whereRaw("cd_conta=".$coluna[11])->whereRaw("cd_atendimento=".$coluna[10])->first();
                            if(empty($select->cd_atendimento)){
                                $XX=DB::table("faturamento_conta")->insert($dados);
                            }else{
                                $XX=DB::table("faturamento_conta")->whereRaw("cd_atendimento=".$coluna[10])
                                ->whereRaw("cod_proc='".$coluna[6]."'")->whereRaw("cd_conta=".$coluna[11])->update($dados);

                            }

                        }
                    }
                }
                if($ERRO==FALSE){
                    return redirect()->route('faturamento.listar')->with('success', 'Importado com sucesso!');
                }else{

                    return redirect()->back()->withInput()->withErrors(['error' => $ERRORCONV]);
                }

            }else{
                return redirect()->back()->withInput()->withErrors(['error' => 'Tipo de Arquivo não permitido!']);
            }


        }
        catch(Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
