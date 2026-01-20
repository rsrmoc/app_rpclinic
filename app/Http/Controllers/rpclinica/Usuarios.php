<?php


namespace App\Http\Controllers\rpclinica;

use App\Bibliotecas\EnvioEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\EmpresaEmail;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Perfil;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\ProfissionalEspecialidade;
use App\Model\rpclinica\ProfissionalProcedimento;
use App\Model\rpclinica\Usuario;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Usuarios extends Controller
{

    public function index(Request $request)
    {

        if ($request->query('b')) {
            $usuarios = Usuario::where('cd_usuario', 'LIKE', "%{$request->b}%")
                ->orWhere('nm_usuario', 'LIKE', "%{$request->b}%")
                ->orderBy('created_at', 'desc')->get();
        } else {
            $usuarios = Usuario::orderBy('created_at', 'desc')->get();
        }
        $usuarios->load('profissional');
        return view('rpclinica.usuario.lista', \compact('usuarios'));
    }

    public function create(Request $request)
    {
        $empresas = Empresa::all();
        $perfis = Perfil::all();
        $procedimentos = Procedimento::all();
        $convenios = Convenio::all();
        $especialidades = Especialidade::all();
        $profissionais = Profissional::orderBy("nm_profissional")->get();
        $codEmpresa = Empresa::find($request->user()->cd_empresa);

        return view('rpclinica.usuario.add', compact('perfis', 'procedimentos', 'convenios', 'especialidades','empresas','profissionais','codEmpresa'));
    }

    public function jsonStore(Request $request)
    {
        $Campos =array( 
            "nome" => "required|string",
            "perfil" => "required|integer|exists:perfil,cd_perfil",
            "empresa" => "required|integer|exists:empresa,cd_empresa",
            "profissional" => "nullable|integer|exists:profissional,cd_profissional",
            "celular" => "required|celular_com_ddd",
            "admin" => "required|in:S,N",
            "ativo" => "required|in:S,N",
            "resetar_senha" => "required|boolean",
            "enviar_email" => "required|boolean", 
            "todos_agendamentos" => "nullable|boolean",
            "senha" => "required",
            "visualizar_exame" => "nullable",
            "laudar_exame" => "nullable",
         );
        
        $codEmpresa = Empresa::find($request->user()->cd_empresa);
        if($codEmpresa->sn_login_email=='S'){
            $Campos['email']="required|string|email|unique:usuarios";
        }else{
            $Campos['email']="required|string|unique:usuarios";
            $Campos['email_contato']="nullable";
        }
         
        $validator = Validator::make($request->post(), $Campos,['email.required'=> 'Campo Usuario obrigatório','email.unique'=>'O usuario informado ja existe no sistema'] );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        if($request->post('senha')){
            $senha = $request->post('senha');
        }else{
            $senha = rand(111111, 999999);
        }
 
        $usuario =  Usuario::create([
            'cd_usuario' => Str::uuid(),
            'nm_usuario' => $request->post('nome'),
            'visualizar_exame' => ($request->post('visualizar_exame')=='S') ? 'S' : null,
            'laudar_exame' => ($request->post('laudar_exame')=='S') ? 'S' : null,
            'password' => Hash::make($senha),
            'cd_perfil' => $request->post('perfil'),
            'email' => $request->post('email'),
            'email_contato' => ($request->post('email_contato')) ? $request->post('email_contato') : null,
            'nm_celular' => $request->post('celular'),
            'cd_empresa' => $request->post('empresa'),
            'admin' => (empty($request->post('admin'))) ? 'N' : $request->post('admin'),
            'sn_ativo' =>  'S',
            'resetar_senha' => $request->post('resetar_senha') ? 'S' : 'N',
            'cd_profissional' => $request->post('profissional') ? $request->post('profissional') : null,
            'sn_profissional' => $request->post('profissional') ? 'S' : 'N', 
            'sn_todos_agendamentos' => $request->post('todos_agendamentos') ? 'S' : 'N',
        ]);

        try {

            $emailEnviado = true;
 
            return response()->json(['request' =>$request,'message' => 'Usuário cadastrado com sucesso!'.( !$emailEnviado ? " Mas houve um erro ao enviar o email." : "")]);
        } catch (Exception $e) {
            $usuario->delete();

            return response()->json(['message' => 'Não foi possivel cadastrar o usuário. ' . $e->getMessage()], 500);
        }
    }

    public function edit(Request $request, Usuario $usuario)
    {
        $usuario->load('profissional');
        if ($usuario->profissional) {
            $usuario->profissional->load('procedimentos', 'especialidades');
        }
        $perfis = Perfil::all();
        $procedimentos = Procedimento::all();
        $convenios = Convenio::all();
        $especialidades = Especialidade::all();
        $empresas = Empresa::all();
        $profissionais = Profissional::orderBy("nm_profissional")->get();
        $codEmpresa = Empresa::find($request->user()->cd_empresa);
        return view('rpclinica.usuario.edit', compact('perfis', 'procedimentos', 'convenios', 'especialidades', 'usuario','empresas', 'profissionais','codEmpresa'));
    }


    public function update(Request $request, Usuario $usuario)
    { 
        $Campos =array( 
            "nome" => "required|string",
            "perfil" => "required|integer|exists:perfil,cd_perfil",
            "empresa" => "required|integer|exists:empresa,cd_empresa",
            "profissional" => "nullable|integer|exists:profissional,cd_profissional",
            "celular" => "required|celular_com_ddd", 
            "sn_todos_agendamentos" => "nullable|boolean",
            "senha" => "nullable",
            "visualizar_exame" => "nullable",
            "laudar_exame" => "nullable",
            "ativo-usuario" => "nullable|in:S",
         );
         $codEmpresa = Empresa::find($request->user()->cd_empresa);
         if($codEmpresa->sn_login_email=='S'){ 
            $Campos['email']="required|string|email";
         }else{
            $Campos['email']="required|string";
            $Campos['email_contato']="nullable";
         }
        $validator = Validator::make($request->post(), $Campos);
 

        if ($validator->fails()) {
            return redirect()
            ->back()
            ->withInput()
            ->withErrors(['message' => $validator->errors()->first()]); 
        }

        
        if($request['senha']){
            $senha = $request['senha'];
        } else{
            $senha=null;
        }
 
        $DadosEmail = array(
            'nm_usuario' => $request->post('nome'),
            'visualizar_exame' => ($request->post('visualizar_exame')=='S') ? 'S' : null,
            'laudar_exame' => ($request->post('laudar_exame')=='S') ? 'S' : null,
            'cd_perfil' => $request->post('perfil'), 
            'email' => $request->post('email'),
            'email_contato' => ($request->post('email_contato')) ? $request->post('email_contato') : null,
            'nm_celular' => $request->post('celular'),
            'cd_empresa' => $request->post('empresa'),
            'admin' => (empty($request->post('admin'))) ? 'N' : $request->post('admin'),
            'sn_ativo' => ($request->post('ativo-usuario')=='S') ? 'S' : 'N',
            'resetar_senha' => $request->post('resetar_senha') ? 'S' : 'N',
            'sn_profissional' => $request->post('profissional') ? 'S' : 'N',
            'cd_profissional' => $request->post('profissional'), 
            'sn_todos_agendamentos' => $request->post('todos_agendamentos') ? 'S' : 'N',
        );
        if(trim($senha)){
            $DadosEmail['password'] = Hash::make($senha);
        } 
        $retorno = $usuario->update($DadosEmail);
 
        HelperSessionUsusario($request->user()->email);
            
        return redirect()->route('usuario.listar')->with('success', 'Usuário atualizado com sucesso!'); 
 
    }

    public function jsonUpdate(Request $request, Usuario $usuario)
    {

        $Campos =array( 
            "nome" => "required|string",
            "perfil" => "required|integer|exists:perfil,cd_perfil",
            "empresa" => "required|integer|exists:empresa,cd_empresa",
            "profissional" => "required|integer|exists:profissional,cd_profissional",
            "celular" => "required|celular_com_ddd",
            "admin" => "required|in:S,N",
            "ativo" => "nullable|in:S,N",
            "resetar_senha" => "required|boolean",
            "enviar_email" => "required|boolean",
            "sn_profissional" => "required|boolean",
            "sn_todos_agendamentos" => "required|boolean",
            "senha" => "nullable",
            "visualizar_exame" => "nullable",
            "laudar_exame" => "nullable",
         );

         $codEmpresa = Empresa::find($request->user()->cd_empresa);
         if($codEmpresa->sn_login_email=='S'){ 
            $Campos['email']="required|string|email";
         }else{
            $Campos['email']="required|string";
            $Campos['email_contato']="nullable";
         }

         $validator = Validator::make($request->post(), $Campos);


        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        if($request['senha']){
            $senha = $request['senha'];
        } else{
            $senha=null;
        }

        if (Usuario::where('email', $request->email)->where('cd_usuario', '<>', $usuario->cd_usuario)->first()) {
            return response()->json(['message' => 'O email já está sendo utilizado.'], 400);
        }

        try {
            $emailEnviado = false;

            $DadosEmail = array(
                'nm_usuario' => $request->post('nome'),
                'visualizar_exame' => ($request->post('visualizar_exame')=='S') ? 'S' : null,
                'laudar_exame' => ($request->post('laudar_exame')=='S') ? 'S' : null,
                'cd_perfil' => $request->post('perfil'),
                'email' => $request->post('email'),
                'email_contato' => ($request->post('email_contato')) ? $request->post('email_contato') : null,
                'nm_celular' => $request->post('celular'),
                'cd_empresa' => $request->post('empresa'),
                'admin' => (empty($request->post('admin'))) ? 'N' : $request->post('admin'),
                'sn_ativo' => ($request->post('ativo')=='S') ? 'S' : 'N',
                'resetar_senha' => $request->post('resetar_senha') ? 'S' : 'N',
                'sn_profissional' => $request->post('sn_profissional') ? 'S' : 'N',
                'sn_todos_agendamentos' => $request->post('sn_todos_agendamentos') ? 'S' : 'N',
            );
            if(trim($senha)){
                $DadosEmail['password'] = Hash::make($senha);
            }


            $retorno = $usuario->update($DadosEmail);
        
            if ($request->has('profissional')) {
                if (!$usuario->cd_profissional) {
                    $profissional = Profissional::create([
                        'nm_profissional' =>  $request->post('nome'),
                        'doc' => $request->profissional['documento'],
                        'nasc' => $request->profissional['nascimento'],
                        'contato' => $request->profissional['contato'],
                        'end' => $request->profissional['endereco'],
                        'numero' => $request->profissional['numero'],
                        'complemento' => $request->profissional['complemento'],
                        'bairro' => $request->profissional['bairro'],
                        'cidade' => $request->profissional['cidade'],
                        'sn_ativo' => $request->profissional['ativo'],
                        'local' => $request->profissional['cidade'],
                        'crm' => $request->profissional['crm'],
                        'email' => $request->post('email'),
                        'sms' => $request->post('celular'),
                        'whatsapp' => $request->post('celular'),
                        'cd_usuario' => $request->user()->cd_usuario
                    ]);

                    foreach ($request->profissional['procedimentos'] as $procedimento) {
                        ProfissionalProcedimento::create([
                            'cd_proc' => $procedimento['cd_procedimento'],
                            'cd_convenio' => $procedimento['cd_convenio'],
                            'cd_profissional' => $profissional->cd_profissional,
                            'vl_proc' => formatCurrencyForDB($procedimento['valor']),
                            'porc_repasse' => formatCurrencyForDB($procedimento['repasse']),
                            'sn_ativo' => 'S',
                            'cd_usuario' => $request->user()->cd_usuario
                        ]);
                    }

                    foreach ($request->profissional['especialidades'] as $especialidade) {
                        ProfissionalEspecialidade::create([
                            'cd_especialidade' => $especialidade['cd_especialidade'],
                            'cd_profissional' => $profissional->cd_profissional,
                            'sn_ativo' => 'S',
                            'cd_usuario' => $request->user()->cd_usuario
                        ]);
                    }

                    $usuario->cd_profissional = $profissional->cd_profissional;
                    $usuario->save();
                } else {
                    $usuario->profissional->update([
                        'nm_profissional' =>  $request->post('nome'),
                        'doc' => $request->profissional['documento'],
                        'nasc' => $request->profissional['nascimento'],
                        'contato' => $request->profissional['contato'],
                        'end' => $request->profissional['endereco'],
                        'numero' => $request->profissional['numero'],
                        'complemento' => $request->profissional['complemento'],
                        'bairro' => $request->profissional['bairro'],
                        'cidade' => $request->profissional['cidade'],
                        'sn_ativo' => $request->profissional['ativo'],
                        'local' => $request->profissional['cidade'],
                        'crm' => $request->profissional['crm'],
                        'email' => $request->post('email'),
                        'sms' => $request->post('celular'),
                        'whatsapp' => $request->post('celular'),
                        'up_usuario' => $request->user()->cd_usuario
                    ]);
                }
            }
            $string = implode(",", $DadosEmail);

            HelperSessionUsusario($request->user()->email);
            
            return response()->json(['message' => 'Usuário atualizado com sucesso! '  . ( !$emailEnviado ? " Mas houve um erro ao enviar o email." : "")]);
        
        } catch (Exception $e) {
            return response()->json(['message' => 'Não foi possivel atualizar o usuário. ' . $e->getMessage()], 500);
        }
    }

    public function delete(Usuario $usuario)
    {
        try {
            $usuario->delete();
        } catch (Exception $e) {
            abort(500);
        }
    }
}
