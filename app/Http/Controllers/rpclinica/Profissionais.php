<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\ProfissionaisConselho;
use App\Model\rpclinica\ProfissionaisTipo;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\ProfissionalEspecialidade;
use App\Model\rpclinica\ProfissionalProcedimento;
use App\Model\rpclinica\Usuario;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Profissionais extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $profissionais  = Profissional::where('cd_profissional', $request->b)
                ->orWhere('nm_profissional', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $profissionais  = Profissional::all();
        }

        return view('rpclinica.profissional.lista', compact('profissionais'));
    }

    public function create()
    { 
        $procedimentos = Procedimento::where('sn_ativo','S')->orderBy('nm_proc')->get();
        $convenios  = Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();
        $especialidades  = Especialidade::where('sn_ativo','S')->orderBy('nm_especialidade')->get();
        $conselhos = ProfissionaisConselho::all();
        $tipos = ProfissionaisTipo::all();
        $profissional = null;
        return view('rpclinica.profissional.add', compact('procedimentos','convenios','especialidades','conselhos','tipos','profissional'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->post(), [
            "nome" => "required|string",
            "nm_guerra" => "required|string",
            "nascimento" => "required",
            "cpf" => "nullable|string",
            "rg" => "nullable",
            "rg" => "nullable",
            "conselho" => "required",
            "nr_conselho" => "required",
            "tp_profissional" => "required",
            "sexo" => "required",
            "obs_escala" => "nullable|string",
            "escala" => "nullable", 
        ]);

        if ($validator->fails()) { 
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {

            $profissional = Profissional::create([
                'nm_profissional' =>  $request['nome'], 
                'doc' => $request['cpf'], 
                'conselho' => $request['conselho'],
                'rg' => $request['rg'],
                'crm' => $request['nr_conselho'],
                'tp_profissional' => $request['tp_profissional'],
                'nm_guerra' => $request['nm_guerra'], 
                'nasc' => $request['nascimento'],
                'contato' => $request['contato'],
                'end' => $request['endereco'],
                'numero' => $request['numero'],
                'complemento' => $request['complemento'],
                'bairro' => $request['bairro'],
                'cidade' => $request['cidade'],
                'sn_ativo' => 'S',
                'local' => $request['cidade'], 
                'email' => $request->post('email'),
                'sexo' => $request->post('sexo'),
                'sms' => $request->post('celular'),
                'whatsapp' => $request->post('celular'),
                'escala' => ($request->post('escala')=='S') ? 'S' : 'N',
                'obs_escala_medica' => $request->post('obs_escala'),
                'cd_usuario' => $request->user()->cd_usuario 
            ]);

            return redirect()->route('profissional.listar')->with('success', 'Profissional cadastrado com sucesso!'); 
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(Request $request, Profissional $profissional) {
             
        $profissional->load('usuario');
        //dd($profissional->toArray());
        $procedimentos = Procedimento::where('sn_ativo','S')->orderBy('nm_proc')->get();
        $convenios  = Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();
        $especialidades  = Especialidade::where('sn_ativo','S')->orderBy('nm_especialidade')->get();
        $conselhos = ProfissionaisConselho::all();
        $tipos = ProfissionaisTipo::all();
        return view('rpclinica.profissional.edit',  compact('profissional','procedimentos','convenios','especialidades','conselhos','tipos'));
    }

    public function update(Request $request, Profissional $profissional) {

        $validator = Validator::make($request->post(), [
            "nome" => "required|string",
            "nm_guerra" => "required|string",
            "nascimento" => "required",
            "cpf" => "nullable|string",
            "rg" => "nullable",
            "obs_escala" => "nullable|string",
            "escala" => "nullable", 
            "conselho" => "required",
            "nr_conselho" => "required",
            "tp_profissional" => "required",
            "sexo" => "required",
        ]);

        if ($validator->fails()) { 
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {

              
            $Cod=$profissional->cd_profissional;
            if(isset($_FILES['assinatura'])){
                if (!$_FILES['assinatura']['name'] == '') {
                    $tp_assinatura=$_FILES['assinatura']['type'];
                    $assinatura=base64_encode(file_get_contents($_FILES['assinatura']['tmp_name'])); 
                } 
            }
            $dados=[
                'nm_profissional' =>  $request->post('nome'),
                'tp_profissional' =>  $request->post('tp_profissional'), 
                'doc' => $request['cpf'], 
                'conselho' => $request['conselho'],
                'rg' => $request['rg'],
                'crm' => $request['nr_conselho'],
                'tp_profissional' => $request['tp_profissional'],
                'nm_guerra' => $request['nm_guerra'], 
                'nasc' => $request['nascimento'],
                'contato' => $request['contato'],
                'end' => $request['endereco'],
                'numero' => $request['numero'],
                'complemento' => $request['complemento'],
                'bairro' => $request['bairro'],
                'cidade' => $request['cidade'],
                'sexo' => $request['sexo'],
                'sn_ativo' => 'S',
                'local' => $request['cidade'], 
                'email' => $request->post('email'),
                'sms' => $request->post('celular'),
                'whatsapp' => $request->post('celular'), 
                'sn_escala_medica' => ($request->post('escala')=='S') ? 'S' : 'N',
                'obs_escala_medica' => $request->post('obs_escala'),
                'cd_usuario' => $request->user()->cd_usuario 
            ];

            if(isset($assinatura)){
                $dados['assinatura']=$assinatura;    
                $dados['tp_assinatura']=$tp_assinatura;  
            }
            
            $profissional->update($dados); 

            if(isset($profissional->usuario)){
              
                $array['sn_triagem']=$request['sn_triagem'];
                $array['sn_anamnese']=$request['sn_anamnese'];
                $array['sn_exame_fisico']=$request['sn_exame_fisico'];
                $array['sn_conduta']=$request['sn_conduta'];
                $array['sn_hipotese_diag']=$request['sn_hipotese_diag'];
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
                $array['campos_prontuario']=$request['campos']; 
                Usuario::where('cd_profissional',$Cod)->update($array);

            }

            return redirect()->route('profissional.listar')->with('success', 'Profissional atualizado com sucesso!'); 
       
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
        
    }
    
    public function delete(Profissional $profissional) {
        try {
            $profissional->delete();
        }
        catch (Exception $e) {
            abort(500);
        }
    }
 
    public function storeProcedimento(Request $request) {
        $validator = Validator::make($request->post(), [
            'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
            'cd_procedimento' => 'required|integer|exists:procedimento,cd_proc',
            'cd_convenio' => 'required|integer|exists:convenio,cd_convenio',
            'valor' => 'required|currency',
            'repasse' => 'nullable|currency'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $procedimento = ProfissionalProcedimento::create([
                'cd_proc' => $request->cd_procedimento,
                'cd_convenio' => $request->cd_convenio,
                'cd_profissional' => $request->cd_profissional,
                'vl_proc' => formatCurrencyForDB($request->valor),
                'porc_repasse' => formatCurrencyForDB($request->repasse),
                'sn_ativo' => 'S',
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return response()->json(['message' => 'Procedimento cadastrado com sucesso!', 'procedimento' => $procedimento]);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'Não foi possivel cadastrar o procedimento. '.$e->getMessage()], 500);
        }
    }

    public function storeEspecialidade(Request $request) {
        $validator = Validator::make($request->post(), [
            'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
            'cd_especialidade' => 'required|integer|exists:especialidade,cd_especialidade',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $especialidade = ProfissionalEspecialidade::create([
                'cd_especialidade' => $request->cd_especialidade,
                'cd_profissional' => $request->cd_profissional,
                'sn_ativo' => 'S',
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return response()->json(['message' => 'Especialidade cadastrada com sucesso!', 'especialidade' => $especialidade]);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'Não foi possivel cadastrar a especialidade.'.$e->getMessage()], 500);
        }
    }

    public function deleteProcedimento(ProfissionalProcedimento $procedimento) {
        try {
            $procedimento->delete();
        }
        catch (Exception $e) {
            abort(500);
        }
    }

    public function deleteEspecialidade(ProfissionalEspecialidade $especialidade) {
        try {
            $especialidade->delete();
        }
        catch (Exception $e) {
            abort(500);
        }
    }

    public function deleteAssinatura(Profissional $profissional) {
        try {             
            $profissional->update(['tp_assinatura'=>null,'assinatura'=>null]);
        }
        catch (Exception $e) {
            abort(500);
        }
    }
    
}
