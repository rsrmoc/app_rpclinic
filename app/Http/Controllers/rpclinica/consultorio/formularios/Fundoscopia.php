<?php


namespace App\Http\Controllers\rpclinica\consultorio\formularios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Oft_anamnese;
use App\Model\rpclinica\Oft_auto_refracao;
use App\Model\rpclinica\Oft_ceratometria;
use App\Model\rpclinica\Oft_ectoscopia;
use App\Model\rpclinica\Oft_formularios_imagens;
use App\Model\rpclinica\Oft_fundoscopia;
use App\Model\rpclinica\Oft_tonometria_pneumatica;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento;
use App\Model\Support\Log;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Fundoscopia extends Controller
{

    public function store(Request $request, Agendamento $agendamento)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                'dt_exame' => 'required|date',
                'dt_liberacao' => 'nullable|date',
                'od' => 'nullable|string',
                'oe' => 'nullable|string',
                'obs' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $dados = $validator->validated();

            $dados['cd_agendamento'] = $agendamento['cd_agendamento'];
            $dados['cd_usuario_exame'] = $request->user()->cd_usuario;
            $dados['dt_cad_exame'] = date('Y-m-d H:i:s');
            $dados['dt_cad_liberacao'] = date('Y-m-d H:i:s');

            if ($request->input('midriase_od') == 'on') {
                $dados['midriase_od'] = "1";
            } else {
                $dados['midriase_od'] = "0";
            }

            if ($request->input('normal_od') == 'on') {
                $dados['normal_od'] = "1";
            } else {
                $dados['normal_od'] = "0";
            }


            if ($request->input('midriase_oe') == 'on') {
                $dados['midriase_oe'] = "1";
            } else {
                $dados['midriase_oe'] = "0";
            }

            if ($request->input('normal_oe') == 'on') {
                $dados['normal_oe'] = "1";
            } else {
                $dados['normal_oe'] = "0";
            }

            // return response()->json(['request' => $dados]);

            $query = [
                'cd_agendamento' =>  $dados['cd_agendamento']
            ];
 
            $refra = DB::transaction(function () use ($query,$dados,$request){
                $tabela = Oft_fundoscopia::updateOrCreate($query, $dados); 
                $usuario_logado = $request->user(); 
               $tabela ->criarLogCadastro($usuario_logado,'agendamento','pep',$dados['cd_agendamento']); 
                return $tabela;
            });
 
            return response()->json(['request' => $refra]);
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function storeImg(Request $request, Agendamento $agendamento)
    {
        try {
            $validator = Validator::make($request->all(), [ 
                'image' => 'required|file|mimes:jpg,jpeg,png|max:2048', // max:2048 is 2MB limit
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
            $dados = $validator->validated();

            $dados['cd_agendamento'] = $agendamento['cd_agendamento'];
            $dados['cd_usuario_exame'] = $request->user()->cd_usuario;
            // $dados['dt_cad_exame'] = date('Y-m-d H:i:s');
            $dados['dt_exame'] = date('Y-m-d H:i:s');
            $dados['cd_formulario'] = "FUNDOSCOPIA";

            $file = $request->file('image');

            $path = $file->store($agendamento['cd_agendamento']);

            $dados['caminho_img'] = $path;
 
            $refra = DB::transaction(function () use ($dados,$request){
                $tabela = Oft_formularios_imagens::create($dados); 
                $usuario_logado = $request->user(); 
               $tabela ->criarLogCadastro($usuario_logado,'agendamento','pep',$dados['cd_agendamento']); 
                return $tabela;
            });

            return response()->json(['request' => $refra]);
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }


    public function modal(Request $request,Paciente $paciente)
    {
        $historico['form'] = Agendamento::with(['profissional'])->where('cd_paciente', $paciente->cd_paciente)
            ->join('oft_fundoscopia', 'oft_fundoscopia.cd_agendamento', 'agendamento.cd_agendamento')
            ->leftJoin('usuarios', 'oft_fundoscopia.cd_usuario_exame', 'usuarios.cd_usuario');
            if($request['cd_formulario']){
                $historico['form'] = $historico['form']->where('agendamento.cd_agendamento',$request['cd_formulario']);
            }    
            $historico['form'] = $historico['form']->orderBy('dt_agenda', 'desc')->get(); 

        $historico['imagens'] = Agendamento::with(['profissional'])->where('cd_paciente', $paciente->cd_paciente)
            ->join('oft_formularios_imagens', 'oft_formularios_imagens.cd_agendamento', 'agendamento.cd_agendamento')
            ->join('usuarios', 'usuarios.cd_usuario', 'oft_formularios_imagens.cd_usuario_exame')
            ->where('oft_formularios_imagens.cd_formulario', 'FUNDOSCOPIA');
            if($request['cd_formulario']){
                $historico['imagens'] = $historico['imagens']->where('agendamento.cd_agendamento',$request['cd_formulario']);
            } 
            $historico['imagens'] = $historico['imagens']->orderBy('dt_agenda', 'desc')->get();
 
        
        $historico['array_img']=null;
        foreach ($historico['imagens'] as $imgs) {
            clearstatcache(); // Limpamos o cache de arquivos do PHP
            $CaminhoImg = env('URL_IMG_EXAMES') . "/" . $imgs->caminho_img;
            if (is_file($CaminhoImg)) {
                $mime_type = mime_content_type($CaminhoImg);
                $data = file_get_contents($CaminhoImg);
                $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
            } else {
                $ArrayImg['conteudo_img'] = null;
            }
            $ArrayImg['usuario'] = $imgs->usuario?->nm_usuario;
            $ArrayImg['cd_agendamento'] = $imgs->cd_agendamento;
            $ArrayImg['data'] = $imgs->dt_exame;
            $historico['array_img'][] = $ArrayImg;
        }

 

        return  view('rpclinica.consultorio.formularios.oftalmologia.fundoscopia.modal', ['historico' => $historico]);
    }

    public function deleteImg(Request $request, $cd_img_formulario)
    {
        try {
            
            DB::transaction(function () use ($cd_img_formulario,$request){

                $tabela = Oft_formularios_imagens::find($cd_img_formulario);
                $Codigo =$tabela->cd_agendamento;
                $caminho_arquivo = env('URL_IMG_EXAMES').'//'.$tabela->caminho_img; 
                $tabela->delete();
                $usuario_logado = $request->user();    
                Log::insert([
                    'usuario_id' => $usuario_logado->cd_usuario,
                    'usuario_type' => get_class($usuario_logado),
                    'descricao' => 'Exclusao',
                    'modulo' => 'agendamento',
                    'rotina' => 'pep',
                    'registro_type' => 'App\Model\rpclinica\Oft_formularios_imagens',
                    'registro_id' => $cd_img_formulario,
                    'agendamento_id' => $Codigo,
                    'dados' => null,
                    'created_at'=> date('Y-m-d H:i'),
                    'updated_at'=> date('Y-m-d H:i'),
                ]);
                if (file_exists($caminho_arquivo)) {
                    unlink($caminho_arquivo);
                } 
            });
  
 
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function delete(Request $request, $cd_agendamento)
    {
        try {

            DB::transaction(function () use ($cd_agendamento,$request){
                $tabela = Oft_fundoscopia::find($cd_agendamento);
                $Codigo =$tabela->cd_agendamento;
                $tabela->delete();
                $usuario_logado = $request->user();    
                Log::insert([
                    'usuario_id' => $usuario_logado->cd_usuario,
                    'usuario_type' => get_class($usuario_logado),
                    'descricao' => 'Exclusao',
                    'modulo' => 'agendamento',
                    'rotina' => 'pep',
                    'registro_type' => 'App\Model\rpclinica\Oft_fundoscopia',
                    'registro_id' => $cd_agendamento,
                    'agendamento_id' => $Codigo,
                    'dados' => null,
                    'created_at'=> date('Y-m-d H:i'),
                    'updated_at'=> date('Y-m-d H:i'),
                ]);
            });
 
        } catch (Exception $e) {
            abort(500);
        }
    }
}
