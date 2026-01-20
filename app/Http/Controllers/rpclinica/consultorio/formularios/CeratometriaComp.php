<?php


namespace App\Http\Controllers\rpclinica\consultorio\formularios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Oft_auto_refracao;
use App\Model\rpclinica\Oft_ceratometria;
use App\Model\rpclinica\Oft_formularios_imagens;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Paciente;
use App\Model\Support\Log;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CeratometriaComp extends Controller
{
    public function index(Request $request, $agendamento)
    {

        $historico = Agendamento::with(['profissional'])->where("agendamento.cd_agendamento",$agendamento)
        ->join('oft_formularios_imagens', 'oft_formularios_imagens.cd_agendamento', 'agendamento.cd_agendamento')
        ->join('usuarios', 'usuarios.cd_usuario', 'oft_formularios_imagens.cd_usuario_exame')
        ->where('oft_formularios_imagens.cd_formulario', 'CERATOSCOPIA_COMP')   
        ->orderBy('dt_agenda', 'desc')->get(); 

        $array_img = [];

        foreach ($historico as $imgs) {
            clearstatcache(); // Limpamos o cache de arquivos do PHP
            $CaminhoImg = env('URL_IMG_EXAMES') . "/" . $imgs->caminho_img;
            if (is_file($CaminhoImg)) {
                $mime_type = mime_content_type($CaminhoImg);
                $data = file_get_contents($CaminhoImg);
                $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
            } else {
                $ArrayImg['conteudo_img'] = null;
            }
            $ArrayImg['cd_img_formulario'] = $imgs->cd_img_formulario;
            $ArrayImg['usuario'] = $imgs->nm_usuario;
            $ArrayImg['data'] = $imgs->dt_exame;
            $array_img[] = $ArrayImg;
        }
        $query = Oft_formularios_imagens::where("cd_agendamento",$agendamento)
        ->where('cd_formulario', 'CERATOSCOPIA_COMP')->get();
        return response()->json(['retorno' => $array_img,'query' => $query]);

    }

    public function store(Request $request, Agendamento $agendamento)
    {

        try {

            $validator = Validator::make($request->all(), [
                'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                // 'dt_exame' => 'required|date',
                'dt_liberacao' => 'nullable|date',
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
            $dados['cd_formulario'] = "CERATOSCOPIA_COMP";


            $file = $request->file('image');

            $path = $file->store($agendamento['cd_agendamento']);

            $dados['caminho_img'] = $path;

            DB::transaction(function () use ($dados,$request){
                $tabela = Oft_formularios_imagens::create($dados); 
                $usuario_logado = $request->user(); 
                $tabela ->criarLogCadastro($usuario_logado,'agendamento','pep',$dados['cd_agendamento']); 
                return $tabela;
            });
            $retorno = Oft_formularios_imagens::where("cd_agendamento",$agendamento['cd_agendamento'])
            ->where('cd_formulario', 'CERATOSCOPIA_COMP')->get();
            return response()->json(['request' => $dados,'retorno' => $retorno]);

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
     
    public function modalCompleto(Request $request, Paciente $paciente)
    {
        $historico = Agendamento::with(['profissional'])->where('cd_paciente', $paciente->cd_paciente)
            ->join('oft_formularios_imagens', 'oft_formularios_imagens.cd_agendamento', 'agendamento.cd_agendamento')
            ->join('usuarios', 'usuarios.cd_usuario', 'oft_formularios_imagens.cd_usuario_exame')
            ->where('oft_formularios_imagens.cd_formulario', 'CERATOSCOPIA_COMP');
            if($request['cd_formulario']){
                $historico = $historico->where('cd_img_formulario',$request['cd_formulario']);
             }    
             $historico = $historico->orderBy('dt_agenda', 'desc')->get(); 

        $array_img = [];

        foreach ($historico as $imgs) {
            clearstatcache(); // Limpamos o cache de arquivos do PHP
            $CaminhoImg = env('URL_IMG_EXAMES') . "/" . $imgs->caminho_img;
            if (is_file($CaminhoImg)) {
                $mime_type = mime_content_type($CaminhoImg);
                $data = file_get_contents($CaminhoImg);
                $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
            } else {
                $ArrayImg['conteudo_img'] = null;
            }
            $ArrayImg['cd_img_formulario'] = $imgs->cd_img_formulario;
            $ArrayImg['usuario'] = $imgs->nm_usuario;
            $ArrayImg['data'] = $imgs->dt_exame;
            $array_img[] = $ArrayImg;
        }

        return  view('rpclinica.consultorio.formularios.oftalmologia.ceratometria_comp.modal', ['historico' => $historico, 'array_img' => $array_img]);
    }

    public function delete(Request $request, $cd_image_formulario)
    {
        try {

            DB::transaction(function () use ($cd_image_formulario,$request){
                $Img=Oft_formularios_imagens::find($cd_image_formulario);
                $caminho_arquivo = env('URL_IMG_EXAMES').'//'.$Img->caminho_img; 
                Oft_formularios_imagens::where('cd_img_formulario',$cd_image_formulario)->delete(); // TODO: delete file too 
    
                $usuario_logado = $request->user();    
                Log::insert([
                    'usuario_id' => $usuario_logado->cd_usuario,
                    'usuario_type' => get_class($usuario_logado),
                    'descricao' => 'Exclusao',
                    'modulo' => 'agendamento',
                    'rotina' => 'pep',
                    'registro_type' => 'App\Model\rpclinica\Oft_ceratometria',
                    'registro_id' => $cd_image_formulario,
                    'agendamento_id' => $Img->cd_agendamento,
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
}
