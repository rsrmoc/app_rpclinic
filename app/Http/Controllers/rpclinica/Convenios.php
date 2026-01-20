<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Categoria;
use App\Model\rpclinica\ContaBancaria;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\FormaPagamento;
use App\Model\rpclinica\Fornecedor;
use App\Model\rpclinica\Marca;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\ProcedimentoConvenio;
use App\Model\rpclinica\ProcedimentoRepasse;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\Setores;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
//use App\Bibliotecas\SimpleXLSX;
use Shuchkin\SimpleXLSX;

class Convenios extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $convenios = Convenio::where('cd_convenio', $request->b)
                ->orWhere('nm_convenio', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $convenios = Convenio::all();
        }
 
        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }

        return view('rpclinica.convenio.lista', compact('convenios'));
    }

    public function create()
    {
        $procedimentos = Procedimento::where('sn_ativo','S')->orderBy('nm_proc')->get();  
        $fornecedor = Fornecedor::orderBy('nm_fornecedor')->get(); 
        $empresa = Empresa::orderBy('nm_empresa')->get(); 
        $categoria = Categoria::orderBy('nm_categoria')->get(); 
        $conta = ContaBancaria::orderBy('nm_conta')->get(); 
        $setor = Setores::orderBy('nm_setor')->get(); 
        $forma = FormaPagamento::orderBy('nm_forma_pag')->get(); 
        $marca = Marca::orderBy('nm_marca')->get(); 
        return view('rpclinica.convenio.add', compact('procedimentos','fornecedor','empresa','categoria',
        'conta','setor','forma','marca'));
    }

    public function store(Request $request)
    {
        $Campos = array(
            'nome' => 'required|string',
            'convenio' => 'required|in:CO,SUS,PA',
            'faturasancoop' => 'required|in:S,N',
            "cnpj" => "sometimes|nullable|formato_cnpj|cnpj",
            "ans" => "sometimes|nullable|string",
            "prazo_retorno" => "integer|nullable",
            "endereco" => "sometimes|nullable|string",
            "link_autorizacao" => "sometimes|nullable|string",
            "user_autorizacao" => "sometimes|nullable|string",
            "senha_autorizacao" => "sometimes|nullable|string",
            "obs" => "sometimes|nullable|string",
            "email" => "sometimes|nullable|string|email",
            "data_contrato" => "sometimes|nullable|date_format:Y-m-d",
            "telefone" => "sometimes|nullable",
            'procedimentos' => 'sometimes|array|min:1',
            'procedimentos.*.cd_procedimento' => 'required|integer|exists:procedimento,cd_proc',
            'procedimentos.*.dt_vigencia' => 'nullable|date_format:Y-m-d',
            'procedimentos.*.valor' => 'nullable|currency',
            'prazo_guia' => 'nullable|currency'
        );

        if($request['sn_financeiro']=='S'){
            $Campos['cliente']=['required'];
            $Campos['empresa']=['required'];
            $Campos['categoria']=['required'];
            $Campos['conta']=['required'];
            $Campos['setor']=['required'];
            $Campos['forma']=['required'];
            $Campos['marca']=['required'];
        }

        $validated = $request->validate($Campos);
        try {
            DB::transaction(function() use ($request, $validated) {
                $convenio = Convenio::create([
                    'nm_convenio' => $validated['nome'],
                    'tp_convenio' => $validated['convenio'],
                    'sn_sancoop' => $validated['faturasancoop'],
                    'prazo_retorno' => $validated['prazo_retorno'],
                    'sn_ativo' => 'S',
                    'cd_usuario' => $request->user()->cd_usuario,
                    'link_autorizacao' => $validated['link_autorizacao'],
                    'user_autorizacao' => $validated['user_autorizacao'],
                    'senha_autorizacao' => $validated['senha_autorizacao'],
                    'obs' => $validated['obs'],
                    'cnpj' => $validated['cnpj'],
                    'registro_ans' => $validated['ans'],
                    'endereco' => $validated['endereco'],
                    'email' => $validated['email'],
                    'dt_contrato' => $validated['data_contrato'],
                    'telefone' => $validated['telefone'],
                    'sn_financeiro' => $request['sn_financeiro'],
                    'cd_fornecedor' => $request['cliente'],
                    'cd_empresa' => $request->user()->cd_empresa,
                    'cd_categoria' => $request['categoria'],
                    'cd_conta' => $request['conta'],
                    'cd_setor' => $request['setor'],
                    'cd_forma' => $request['forma'],
                    'cd_marca' => $request['marca'],
                    'prazo_guia' => $request['prazo_guia'],
                ]);

                if (!empty($request->procedimentos)) {
                    foreach ($request->procedimentos as $procedimento) {
                        $proc = Procedimento::find($procedimento['cd_procedimento']);
                        $procedimento['cd_convenio'] = $convenio->cd_convenio;
                        $procedimento['cd_procedimento'] = $proc->cod_proc;
                        $procedimento['cd_usuario'] = $request->user()->cd_usuario;
                        $procedimento['valor'] = formatCurrencyForDB($procedimento['valor']); 
                        $procedimento['sn_ativo'] ='S';
                        ProcedimentoConvenio::create($procedimento);
                    }
                }
            });

            return redirect()->route('convenio.listar')->with('success', 'Convenio cadastrado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o convenio.']);
        }
    }

    public function edit(Request $request, Convenio $convenio) {
        $procedimentos = Procedimento::all();
        $fornecedor = Fornecedor::orderBy('nm_fornecedor')->get(); 
        $empresa = Empresa::orderBy('nm_empresa')->get(); 
        $categoria = Categoria::orderBy('nm_categoria')->get(); 
        $conta = ContaBancaria::orderBy('nm_conta')->get(); 
        $setor = Setores::orderBy('nm_setor')->get(); 
        $forma = FormaPagamento::orderBy('nm_forma_pag')->get(); 
        $marca = Marca::orderBy('nm_marca')->get(); 
        $profissional = Profissional::where('sn_ativo','S')->orderBy('nm_profissional')->get();
        $tab=($request['tab']) ? $request['tab'] : null;
        $repasse= ProcedimentoRepasse::with('procedimento','convenio','profissional')
        ->where('sn_ativo','S')
        ->orderBy('cd_profissional')->get();
        
        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        } 
        return view('rpclinica.convenio.edit', compact('convenio', 'procedimentos','fornecedor','empresa','categoria', 
               'conta','setor','forma','marca','profissional','tab','repasse'));
    }

    public function update(Request $request, Convenio $convenio) {

        $Campos = array(
            'nome' => 'required|string',
            'convenio' => 'required|in:CO,SUS,PA',
            'faturasancoop' => 'required|in:S,N',
            "cnpj" => "sometimes|nullable|formato_cnpj|cnpj",
            "ans" => "sometimes|nullable|string",
            "prazo_retorno" => "nullable|integer",
            "endereco" => "sometimes|nullable|string",
            "link_autorizacao" => "sometimes|nullable|string",
            "user_autorizacao" => "sometimes|nullable|string",
            "senha_autorizacao" => "sometimes|nullable|string",
            "obs" => "sometimes|nullable|string",
            "email" => "sometimes|nullable|string|email",
            "data_contrato" => "sometimes|nullable|date_format:Y-m-d",
            "telefone" => "sometimes|nullable|telefone_com_ddd",
            'procedimentos' => 'sometimes|array|min:1',
            'procedimentos.*.cd_procedimento' => 'required|integer|exists:procedimento,cd_proc',
            'procedimentos.*.dt_vigencia' => 'nullable|date_format:Y-m-d',
            'procedimentos.*.valor' => 'nullable|currency',
            'prazo_guia' => 'nullable|currency'
        );

 
        if($request['sn_financeiro']=='S'){
            $Campos['cliente']='required';
            $Campos['empresa']='required';
            $Campos['categoria']='required';
            $Campos['conta']='required';
            $Campos['setor']='required';
            $Campos['forma']='required';
            $Campos['marca']='required';
        }

        $validated = $request->validate($Campos);
        
        try {
          $retorno =  DB::transaction(function() use ($request, $convenio, $validated) {

                $convenio->update([
                    'nm_convenio' => $validated['nome'],
                    'tp_convenio' => $validated['convenio'],
                    'sn_sancoop' => $validated['faturasancoop'],
                    'prazo_retorno' => $validated['prazo_retorno'],
                    'up_cadastro' => date('Y-m-d H:i:s'),
                    'cd_usuario' => $request->user()->cd_usuario,
                    'link_autorizacao' => $validated['link_autorizacao'],
                    'user_autorizacao' => $validated['user_autorizacao'],
                    'senha_autorizacao' => $validated['senha_autorizacao'],
                    'obs' => $validated['obs'], 
                    'cnpj' => $validated['cnpj'],
                    'registro_ans' => $validated['ans'],
                    'endereco' => $validated['endereco'],
                    'email' => $validated['email'],
                    'dt_contrato' => $validated['data_contrato'],
                    'telefone' => $validated['telefone'],
                    'sn_financeiro' => $request['sn_financeiro'],
                    'cd_fornecedor' => $request['cliente'],
                    'cd_empresa' => $request->user()->cd_empresa,
                    'cd_categoria' => $request['categoria'],
                    'cd_conta' => $request['conta'],
                    'cd_setor' => $request['setor'],
                    'cd_forma' => $request['forma'],
                    'cd_marca' => $request['marca'],
                    'prazo_guia' => $request['prazo_guia'],
                ]);

                if (!empty($request->procedimentos)) {

                    foreach ($request->procedimentos as $procedimento) {
                        $proc = Procedimento::find($procedimento['cd_procedimento']);
                        $procedimento['cd_convenio'] = $convenio->cd_convenio;
                        $procedimento['cd_usuario'] = $request->user()->cd_usuario;
                        $procedimento['cd_procedimento'] = $proc->cod_proc;
                        $procedimento['valor'] = formatCurrencyForDB($procedimento['valor']);
                        $procedimento['sn_ativo'] = 'S';

                        ProcedimentoConvenio::where('cd_procedimento',$procedimento['cd_procedimento'])
                        ->where('cd_convenio',$procedimento['cd_convenio'])
                        ->update(['sn_ativo'=>'N']);
                        
                        ProcedimentoConvenio::create($procedimento);
                    }

                }
            });
 
          return redirect()->route('convenio.listar')->with('success', 'Convenio atualizado com sucesso!');
         
        }
        catch (Exception $e) {
          
          return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o convenio.']);
        }
         
    }

    public function delete(Convenio $convenio) {
        try {
            $convenio->delete();
        }
        catch(Exception $e) {
            return redirect()->back()->withInput()->with('error', $e);  
        }
    }

    public function import(Request $request ) {
  
        $validated = $request->validate([
            'xls' => 'required|mimes:xlsx', 
            'convenio' => 'required', 
        ]);
        
       
        try {

            $file = $request->file('xls'); 
            $Extensao=$file->getClientOriginalExtension(); 
            $path= $file->getRealPath();  
            if(trim($Extensao)=='xlsx'){ 
                $xlsx = SimpleXLSX::parse($path);
                list($cols,) = $xlsx->dimension();
                $ERRO=FALSE; $CONVENIO=$request['convenio'];

                $conv=DB::table("convenio")->whereRaw(" cd_convenio =  ".$CONVENIO)->first();
                if(empty($conv)){ 
                    $ErroGEral[]=$CONVENIO. ' não existe.';   $ERRO=TRUE;  
                }

                foreach( $xlsx->rows() as $key => $coluna) { 
                    if($key>0){
                        
                        $dado['cod_proc'] = preg_replace('/[^0-9]/', '', $coluna[0]);
                        $dado['dt_vigencia'] =$coluna[1]; 
                        $dado['valor'] =$coluna[2];

                        $conv=DB::table("procedimento")->whereRaw(" cod_proc = '".$dado['cod_proc']."'")->first(); 
                        if(empty($conv)){ 
                            $ErroGEral[]=$dado['cod_proc']. '  não existe.';  $ERRO=TRUE;   
                        } 

                        if(empty($dado['valor'])){ 
                            $ErroGEral[]='Erro na linha '.($key+1)." - O campo VALOR esta vazio.";  $ERRO=TRUE; 
                        }
                        if(empty($dado['dt_vigencia'])){ 
                            $ErroGEral[]='Erro na linha '.($key+1)." - O campo DATA DE VIGÊNCIA esta vazio.";  $ERRO=TRUE; 
                        }
                    }
                }
                
                if($ERRO==TRUE){
                    $ERRORCONVPROC =   "<ul>";
                    foreach($ErroGEral as $Ccnv){
                        $ERRORCONVPROC = $ERRORCONVPROC. "<li>".$Ccnv."</li>";
                    }
                    $ERRORCONVPROC = $ERRORCONVPROC. "</ul>";
                }

               
                if($ERRO==FALSE){
                    foreach( $xlsx->rows() as $key => $coluna) { 
                        if($key>0){ 
                            $cod_proc = preg_replace('/[^0-9]/', '', $coluna[0]);
                            $procedimento=DB::table("procedimento")->whereRaw(" cod_proc = '".$cod_proc."'")->first();
                            $dados['cd_procedimento'] =$procedimento->cod_proc; 
                            $dados['dt_vigencia'] =$coluna[1]; 
                            $dados['valor'] =$coluna[2];
                            $dados['cd_convenio'] =$CONVENIO; 
 
                            $select = DB::table("procedimento_convenio")->whereRaw("cd_procedimento=".$dados['cd_procedimento'])->whereRaw("cd_convenio=".$CONVENIO)->first();
                           
                            if(empty($select->cd_procedimento)){ 
                                $dados['cd_usuario']=$request->user()->cd_usuario;
                                $dados['created_at'] =date('Y-m-d H:i'); 
                                $dados['sn_ativo']='S';   
                                $XX=DB::table("procedimento_convenio")->insert($dados);
                            } 
                        }
                    }
                    
                }
                 
                if($ERRO==FALSE){
                    return redirect()->route('convenio.listar')->with('success', 'Importado com sucesso!');
                }else{
                    return redirect()->back()->withInput()->withErrors(['error' => $ERRORCONVPROC]);
                }
                 
            }else{
                return redirect()->back()->withInput()->withErrors(['error' => 'Tipo de Arquivo não permitido!']);
            }
 
        }
        catch(Exception $e) { 
             return redirect()->back()->withInput()->with('error', $e);  
        }
        
         
    }

    public function repasse(Request $request, Convenio $convenio)
    {
        $Campos = array(
            'profissional' => 'required|integer|exists:profissional,cd_profissional',
            'procedimento' => 'required|integer|exists:procedimento,cod_proc', 
            'tipo' => 'required|in:%,=', 
            'valor' => 'nullable|currency'
        ); 
        $validated = $request->validate($Campos);
        try {

            DB::transaction(function() use ($request, $validated, $convenio) {
                $convenio = ProcedimentoRepasse::create([
                    'cd_convenio' => $convenio->cd_convenio,
                    'cd_profissional' => $validated['profissional'],
                    'cd_procedimento' => $validated['procedimento'],
                    'tipo' => $validated['tipo'],
                    'sn_ativo' => 'S',
                    'cd_usuario' => $request->user()->cd_usuario,
                    'valor' => str_replace(',', '.',  str_replace('.', '', $validated['valor']) ), 
                ]); 
            });

            return redirect()->route('convenio.edit',[ 'convenio'=> $convenio->cd_convenio,'tab'=>'RE' ])
                             ->with('success', 'Repasse cadastrado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o Repasse.']);
        }
    }

    public function repasseDelete(Request $request, ProcedimentoRepasse $cd_procedimento_repasse)
    {
        try {
            $Convenio = $cd_procedimento_repasse->cd_convenio;
            $cd_procedimento_repasse->upadte(['sn_ativo'=>'N','dt_desativacao'=>date('Y-m-d H:i')]);
            return redirect()->route('convenio.edit',[ 'convenio'=> $Convenio,'tab'=>'RE' ])
            ->with('success', 'Repasse deletado com sucesso!');
        }
        catch(Exception $e) { 
            return redirect()->back()->withInput()->with('error', $e); 
        } 

    }
    
    
}
