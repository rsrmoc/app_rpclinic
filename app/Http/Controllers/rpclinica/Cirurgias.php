<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoItens;
use App\Model\rpclinica\AgendamentoItensHist;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\Oft_formularios_imagens;
use App\Model\rpclinica\Oft_texto_padrao;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Produto;
use App\Model\rpclinica\SituacaoItem;
use App\Model\Support\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class Cirurgias extends Controller
{
    public function index(Request $request)
    {
        $parametros['profissional'] = Profissional::where('sn_ativo', 'S')->orderBy('nm_profissional')->get();
        $parametros['opme'] = Produto::where('sn_ativo', 'S')->where('sn_opme', 'S')->orderBy('nm_produto')->get();
        $parametros['exame'] = Exame::where('tp_item', 'CI')->where('sn_ativo', 'S')->orderBy('nm_exame')->get();
        $parametros['convenio'] = Convenio::where('sn_ativo', 'S')->orderBy('nm_convenio')->get();
        $parametros['situacao'] = SituacaoItem::where('tipo','CI')->orderBy("nm_situacao_itens")->get();

        return view('rpclinica.cirurgias.painel', compact('parametros', 'request'));
    }

    public function jsonPainel(Request $request): array
    {
        $request['query'] = AgendamentoItens::PainelCirurgias($request)
            ->selectRaw("agendamento_itens.*,date_format(created_at,'%d/%m/%Y') created_data ")
            ->orderBy("created_at")->get(); 
        return $request->toArray();
    }

    public function jsonModal(Request $request)
    {
        $agendamento = Agendamento::find($request['cd_agendamento']);
        $paciente = Paciente::find($agendamento->cd_paciente);
        $dados['texto_padrao'] = Oft_texto_padrao::all();
        foreach ($dados['texto_padrao'] as $key => $valor) {
            $dados['texto_padrao'][$key]['ds_texto_padrao'] = camposInteligentes($valor->ds_texto_padrao, $paciente, $agendamento);
        }

        $dados['request'] = $request->toArray();
        $dados['tab_historico'] = AgendamentoItensHist::where('cd_agendamento_item', $request->cd_agendamento_item)
            ->selectRaw("agendamento_item_hist.*,date_format(created_at,'%d/%m/%Y') data ")
            ->with('usuario')->orderBy('created_at', 'desc')->get();

        $dados['tab_img'] = Oft_formularios_imagens::where('cd_agendamento_item', $request->cd_agendamento_item)
            ->selectRaw("oft_formularios_imagens.*,date_format(created_at,'%d/%m/%Y') data")
            ->with('usuario')->orderBy('created_at', 'desc')->get();
        
        $dados['array_img']=null;
        foreach ($dados['tab_img'] as $imgs) {
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
            $ArrayImg['data'] = $imgs->data;
            $dados['array_img'][] = $ArrayImg;
        }
        
        $dados['hist'] = AgendamentoItensHist::where('cd_agendamento_item', '=',$request['cd_agendamento_item'])
        ->join('usuarios', 'usuarios.cd_usuario', 'agendamento_item_hist.cd_usuario')
        ->selectRaw("agendamento_item_hist.*,date_format(agendamento_item_hist.created_at,'%d/%m/%Y %H:%i:%s') created_data ")
        ->selectRaw("usuarios.nm_usuario")
        ->get();
        
        return response()->json($dados);
    }

    public function addHist(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cd_agendamento_item' => 'required|integer',
                'ds_historico' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $dados = $validator->validated();
            $dados['cd_usuario'] = $request->user()->cd_usuario;

            $return = AgendamentoItensHist::create($dados);

            $retorno = AgendamentoItensHist::where('cd_agendamento_item',$request['cd_agendamento_item'])
            ->selectRaw("agendamento_item_hist.*,date_format(created_at,'%d/%m/%Y %H:%i') data ")
            ->with('usuario')
            ->orderBy('created_at','desc')->get();

            return response()->json(['request' => $return,'retorno' => $retorno]);
            
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function getHist($cd_agendamento_item)
    {
        $hist = AgendamentoItensHist::where('cd_agendamento_item', '=', $cd_agendamento_item)
            ->join('usuarios', 'usuarios.cd_usuario', 'agendamento_item_hist.cd_usuario')
            ->selectRaw("agendamento_item_hist.*,date_format(agendamento_item_hist.created_at,'%d/%m/%Y %H:%i:%s') created_data ")
            ->selectRaw("usuarios.nm_usuario")
            ->get();

        return response()->json($hist);
    }

    public function add(Request $request)
    {
        $exames = Exame::where('sn_ativo','S')->where('tp_item','EX')->orderBy("nm_exame")->get();
        $profissionais = Profissional::where('sn_ativo','S')->orderBy("nm_profissional")->get();
        return view('rpclinica.central_laudos.add',compact('exames', 'profissionais'));
    }

    public function create(Request $request)
    {

        $Campos = array(
            'atendimento' => 'required|integer',
            'dt_atend' => 'required|date_format:Y-m-d',
            'nm_paciente' => 'required|string',
            "cpf" => "required",
            "cd_profissional" => 'required|integer|exists:profissional,cd_profissional',
            "cd_exame" => 'required|integer|exists:exames,cd_exame',
            "nasc" => "nullable",
        );
        $validated = $request->validate($Campos);

        try {
                
            $CPF=preg_replace('/[^\d]/i', '', $request['cpf']);
            $Array = array(
                'nm_paciente'=>$request['nm_paciente'], 
                'cpf'=>$CPF,
                'sn_ativo'=>'S',
                'cd_usuario'=>$request->user()->cd_usuario,
                'dt_nasc'=> ($request['nasc']) ? $request['nasc'] : null
            ); 

            $Pac=Paciente::where("cpf",$CPF)->count();
            if($Pac==0){
                $retorno=Paciente::create($Array);
                $cdPac=$retorno->cd_paciente;
                $nmPac=$retorno->nm_paciente;
            }else{
                $Pac=Paciente::where("cpf",$CPF)->first();
                Paciente::where("cpf",$CPF)->update($Array);
                $cdPac=$Pac->cd_paciente;
                $nmPac=$Pac->nm_paciente;
      
            }
            $dia = date('w', strtotime($request['dt_atend']));
            $dadosAtend=Empresa::where('cd_empresa',$request->user()->cd_empresa)->first();
            $arrayAtend=array(
                'cd_agendamento'=> $request['atendimento'],
                'sn_atend_avulso'=> 'S',
                'cd_paciente'=>$cdPac,
                'cd_convenio'=>$dadosAtend['atend_convenio'],
                'cd_especialidade'=>$dadosAtend['atend_espec'],
                'situacao'=>$dadosAtend['atend_situacao'],
                'sn_atendimento'=>'S',
                'dt_atendimento'=>$request['dt_atend'],
                'usuario_atendimento'=>$request->user()->cd_usuario,
                'tipo'=>$dadosAtend['atend_tipo'],
                'data_horario'=>$request['dt_atend'].' 00:00',
                'dt_agenda'=>$request['dt_atend'],
                'hr_agenda'=>'00:00',
                'dia_semana'=>$dia,
                'cd_profissional'=>$request['cd_profissional'],
            );
             
            $Atend=Agendamento::where("cd_agendamento",$request['atendimento'])->count();
            if($Atend==0){
                $retorno=Agendamento::create($arrayAtend);
                $cdPac=$retorno->cd_paciente;
                $nmPac=$retorno->nm_paciente;
            }else{
           
                Agendamento::where("cd_agendamento",$request['atendimento'])->update($arrayAtend); 
      
            }

            dd($request->toArray(),$Pac->toArray());
 

            $array=array(
                'sn_atend_avulso'=> 'S',
                'cd_paciente'=>$request['paciente'],
                'cd_convenio'=>$request['convenio'],
                'cd_especialidade'=>$request['especialidade'],
                'situacao'=>$Situacao->cd_situacao,
                'sn_atendimento'=>'S',
                'dt_atendimento'=>date('Y-m-d H:i'),
                'usuario_atendimento'=>$request->user()->cd_profissional,
                'tipo'=>$request['tipo'],
                'data_horario'=>date('Y-m-d'),
                'dt_agenda'=>date('Y-m-d H:i'),
                'hr_agenda'=>date('H:i'),
                'dia_semana'=>$dia,
                'cd_profissional'=>$request->user()->cd_profissional
            );
            $Atend=Agendamento::create($array);

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }

    }

    public function atend(Request $request, $agendamento)
    {
        try {

            $retorno = Agendamento::find($agendamento); 
            if($retorno){
                $retorno->load('paciente');
            }
            return response()->json(['retorno' => $retorno]); 

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }


    public function saveLaudo(Request $request, $cd_agendamento_item)
    {
        try {
            $validator = Validator::make($request->all(), [
                'conteudo_laudo' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $dados = $validator->validated();

            $laudo = AgendamentoItens::find($cd_agendamento_item);

            $laudo->conteudo_laudo = $dados['conteudo_laudo'];
            $laudo->sn_laudo = '0';
            $laudo->usuario_laudo = null;
            $laudo->dt_laudo = null;
            $laudo->situacao = 'E';
            $laudo->dt_situacao = date('Y-m-d H:i');
            $laudo->save();

            return response()->json(['request' => true]);
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function liberarLaudo(Request $request, $cd_agendamento_item)
    {
        $laudo = AgendamentoItens::find($cd_agendamento_item);
        if($request['sn_laudo']==true){
            $laudo->sn_laudo = '1';
            $laudo->usuario_laudo = $request->user()->cd_usuario;
            $laudo->dt_laudo = date('Y-m-d H:i');
            $laudo->situacao = 'R';
            $laudo->dt_situacao = date('Y-m-d H:i');
        }else{
            $laudo->sn_laudo = '0';
            $laudo->usuario_laudo = null;
            $laudo->dt_laudo = null;
            $laudo->situacao = 'E';
            $laudo->dt_situacao = date('Y-m-d H:i');
        }
         
        $laudo->save();

        return response()->json(['request' => $laudo]);
    }

    public function storeImg(Request $request, $cd_agendamento_item)
    {
          
        try {
 
            $validator = Validator::make($request->all(), [ 
                'image' => 'required|file|mimes:jpg,jpeg,png|max:2048', // max:2048 is 2MB limit
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
  
            $dados['cd_agendamento'] = $request['cd_agendamento'];
            $dados['cd_agendamento_item'] = $cd_agendamento_item;
            $dados['cd_usuario_exame'] = $request->user()->cd_usuario; 
            $dados['dt_exame'] = date('Y-m-d H:i:s'); 
            $dados['cd_formulario'] = 'EXAME';
 
            $file = $request->file('image'); 
            $path = $file->store($cd_agendamento_item); 
            $dados['caminho_img'] = $path;

            
            $retorno = DB::transaction(function () use ($dados,$request){
                $tabela = Oft_formularios_imagens::create($dados); 
                $usuario_logado = $request->user(); 
                $tabela ->criarLogCadastro($usuario_logado,'agendamento','laudo',$dados['cd_agendamento']); 
                return $tabela;
            });
          
            return response()->json(['request' => $dados, 'retorno' => $retorno]);
            
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
    public function relacaoImg(Request $request, $cd_agendamento_item)
    {   

        try {
 
            $historico = Oft_formularios_imagens::where("cd_agendamento_item",$cd_agendamento_item) 
            ->join('usuarios', 'usuarios.cd_usuario', 'oft_formularios_imagens.cd_usuario_exame')
            ->where('oft_formularios_imagens.cd_formulario', 'EXAME')   
            ->orderBy('oft_formularios_imagens.created_at', 'desc')->get(); 

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
 
            return response()->json(['retorno' => $array_img ]);
 
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function deleteImg(Request $request, $cd_image_formulario)
    {
        try {

            DB::transaction(function () use ($cd_image_formulario,$request){
                $Img=Oft_formularios_imagens::find($cd_image_formulario);
                $caminho_arquivo = env('URL_IMG_EXAMES')."/".$Img->caminho_img; 
                Oft_formularios_imagens::where('cd_img_formulario',$cd_image_formulario)->delete(); // TODO: delete file too 
     
                if (file_exists($caminho_arquivo)) {
                    unlink($caminho_arquivo);
                } 
            }); 
            
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
    
    
}
