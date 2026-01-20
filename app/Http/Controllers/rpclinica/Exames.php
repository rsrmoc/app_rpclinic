<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Estoque;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\ExameFormulario;
use App\Model\rpclinica\LocalAtendimento;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento; 
use Exception;
use Illuminate\Support\Facades\Validator;

class Exames extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $exames = Exame::where('cd_exame', "'".$request->b."'") 
                ->orWhere('nm_exame', 'LIKE', "%{$request->b}%")
                ->with('procedimento')
                ->get();
        }
        else {
            $exames = Exame::with('procedimento')->get();
        } 
        return view('rpclinica.exames.lista', compact('exames'));
    }

    public function create()
    { 
        $Local = LocalAtendimento::orderBy('nm_local')->get();
        return view('rpclinica.exames.add' , compact('Local'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nm_exame' => 'required|string', 
            'tp_item' => 'required|string' ,
            'cd_local' => 'required|numeric' ,
            'cd_proc' => 'nullable|string' 
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            Exame::create([
                'nm_exame' => $request->post('nm_exame'),
                'sn_ativo' => 'S',
                'cod_proc' => $request->post('cd_proc'), 
                'tp_item' => $request->post('tp_item'), 
                'cd_local' => $request->post('cd_local'), 
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('exame.listar')->with('success', 'Exame cadastrado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o exame. '.$e->getMessage()]);
        }
    }

    public function edit(Exame $exame)
    {
        $exame->load('procedimento');
        $Local = LocalAtendimento::orderBy('nm_local')->get();
    
        return view('rpclinica.exames.edit', compact('exame','Local'));
    }

    public function formulario(Request $request, Exame $exame)
    {
 
        $exame->load('formularios.usuario');   
        if(isset($request['codigo'])){
            $form=ExameFormulario::find($request['codigo']);
            
            $formulario['codigo']= (isset($form->cd_exame_formulario)) ? $form->cd_exame_formulario : null;
            $formulario['nome']= (isset($form->nm_formulario)) ? $form->nm_formulario : null;
            $formulario['conteudo']= (isset($form->conteudo)) ? $form->conteudo : null;
        }else{
            $formulario['codigo']=null;
            $formulario['nome']=null;
            $formulario['conteudo']=null;
        }
         
        //dd($exame->toArray());
        return view('rpclinica.exames.formularios', compact('exame','formulario'));
    }

    public function formularioStore(Request $request, Exame $exame)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'conteudo_laudo' => 'required|string',
            'codigo' => 'nullable|integer|exists:exames_formularios,cd_exame_formulario',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {

            if($request['codigo']){
                ExameFormulario::where('cd_exame_formulario',$request['codigo'])
                ->update([
                    'conteudo' => $request->post('conteudo_laudo'),
                    'nm_formulario' => $request->post('descricao'), 
                    'cd_usuario' => $request->user()->cd_usuario,
                    'updated_at' => date('Y-m-d H:i') 
                ]);
            }else{
                ExameFormulario::insert([
                    'conteudo' => $request->post('conteudo_laudo'),
                    'nm_formulario' => $request->post('descricao'),
                    'cd_exame' => $exame->cd_exame,  
                    'cd_usuario' => $request->user()->cd_usuario,
                    'created_at' => date('Y-m-d H:i')
                ]);
            }


            return redirect()->route('exame.formulario',['exame'=>$exame->cd_exame])->with('success', 'Formulario cadastrado com sucesso!');

        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o estoque. '.$e->getMessage()]);
        }
 
    }
    

    public function update(Request $request, Exame $exame)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'sn_ativo' => 'required|in:S,N'
        ]);

       
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $exame->update([
                'nm_exame' => $request->post('descricao'),
                'sn_ativo' => $request->post('sn_ativo'),
                'cod_proc' => $request->post('cd_proc'), 
                'tp_item' => $request->post('tp_item'), 
                'cd_local' => $request->post('cd_local'), 
                'cd_usuario' => $request->user()->cd_usuario 
            ]);

            return redirect()->route('exame.listar')->with('success', 'Estoque atualizado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o estoque. '.$e->getMessage()]);
        }
    }

    public function delete(Exame $exame)
    {
        try {
            $exame->delete();
        }
        catch (\Exception $e) {
            abort(500);
        }
    }

    public function formularioDelete(ExameFormulario $formulario)
    {
        try {
            $formulario->delete();
        }
        catch (\Exception $e) {
            abort(500);
        }
    }
 

    public function modeloStore(Request $request, Exame $exame)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'conteudo_laudo' => 'required|string',
            'codigo' => 'nullable|integer|exists:exames_formularios,cd_exame_formulario',
            'atendimento' => 'required|integer|exists:agendamento,cd_agendamento',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            if($request['codigo']){
                ExameFormulario::where('cd_exame_formulario',$request['codigo'])
                ->update([
                    'conteudo' => $request->post('conteudo_laudo'),
                    'nm_formulario' => $request->post('descricao'), 
                    'cd_usuario' => $request->user()->cd_usuario,
                    'updated_at' => date('Y-m-d H:i') 
                ]);
            }else{
                ExameFormulario::insert([
                    'conteudo' => $request->post('conteudo_laudo'),
                    'nm_formulario' => $request->post('descricao'),
                    'cd_exame' => $exame->cd_exame,  
                    'cd_usuario' => $request->user()->cd_usuario,
                    'created_at' => date('Y-m-d H:i')
                ]);
            }
            $agendamento=Agendamento::find($request['atendimento']);
            $paciente=Paciente::find($agendamento->cd_paciente);
            $dados['texto_padrao'] = ExameFormulario::where('cd_exame',$exame->cd_exame)
            ->orderBy("nm_formulario")->get();
            foreach ($dados['texto_padrao'] as $key => $valor) {
                $dados['texto_padrao'][$key]['conteudo'] = camposInteligentes($valor->conteudo, $paciente, $agendamento);
            }

            return response()->json($dados);
           

        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o estoque. '.$e->getMessage()]);
        }
 
    }
    

}
