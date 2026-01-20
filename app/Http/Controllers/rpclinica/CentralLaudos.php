<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoItens;
use App\Model\rpclinica\AgendamentoItensHist;
use App\Model\rpclinica\CID;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\ExameFormulario;
use App\Model\rpclinica\Oft_formularios_imagens;
use App\Model\rpclinica\Oft_texto_padrao;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Produto;
use App\Model\rpclinica\SituacaoItem;
use App\Model\rpclinica\Usuario;
use App\Model\Support\Log;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Illuminate\Support\Facades\Storage;

class CentralLaudos extends Controller
{
     
    public function index(Request $request)
    { 
 
        
        $parametros['prof_unico'] ='N';
        $parametros['dti']='2025-02-01';
        $parametros['dtf']=date('Y-m-d');
        $parametros['situacao'] = SituacaoItem::where('tipo','central_laudos')
        ->where('sn_ativo','S')->orderBy('nm_situacao_itens')->get();

        $parametros['profissional'] = Profissional::where('sn_ativo', 'S')->orderBy('nm_profissional');
        if(empty($request->user()->visualizar_exame)){
            $parametros['profissional'] = $parametros['profissional']->where('cd_profissional', ($request->user()->cd_profissional) ? $request->user()->cd_profissional : 0);
            $parametros['prof_unico'] ='S';
        }
        $parametros['profissional'] =$parametros['profissional']->get();

        $parametros['opme'] = Produto::where('sn_ativo', 'S')->where('sn_opme', 'S')->orderBy('nm_produto')->get();
        $parametros['exame'] = Exame::where('tp_item', 'EX')->where('sn_ativo', 'S')->orderBy('nm_exame')->get();
        $parametros['convenio'] = Convenio::where('sn_ativo', 'S')->orderBy('nm_convenio')->get();
        $empresa = Empresa::find($request->user()->cd_empresa);
        return view('rpclinica.central_laudos.painel', compact('parametros', 'request', 'empresa'));
        
    }
 
    public function jsonPainel(Request $request): array
    {

   
        $order = "created_at desc";
        switch($request['order']) {
            case "dt":
                $order = "created_at";
                break;
            case "at": 
                $order = "cd_agendamento";
                break; 
        }
        if(($request['orderOUT']=='created_at desc') and ($order=='created_at desc')) {
            $order='created_at';
        }
        if(($request['orderOUT']=='created_at') and ($order=='created_at')) {
            $order='created_at desc';
        }

        if(($request['orderOUT']=='cd_agendamento desc') and ($order=='cd_agendamento desc')) {
            $order='cd_agendamento';
        }
        if(($request['orderOUT']=='cd_agendamento') and ($order=='cd_agendamento')) {
            $order='cd_agendamento desc';
        }

        $request['orderBy']=$order;
        $itemsPerPage = ($request['itemsPerPage']) ? $request['itemsPerPage'] : 50;
        $request['tipo_painel']='central_laudo';
        $request['query'] = AgendamentoItens::PainelCentralLaudos($request)
            ->selectRaw("agendamento_itens.*,date_format(created_at,'%d/%m/%Y') created_data,date_format(dt_envio,'%d/%m/%Y %H:%i') data_envio ")
            ->orderByRaw($order)
            ->paginate($itemsPerPage)->appends($request->query());
      
        return $request->toArray();
    }

    public function teste(Request $request)
    { 

        $conteudoDoArquivo = Storage::disk('s3')->get('adonhiran/3316/3tNy9ahD5TMAmb7P2fVDpF547tThDLGryrLyU2DC.pdf');
         
        return view('rpclinica.central_laudos.teste', compact('conteudoDoArquivo'));
    }

    public function array_img_s3_OUT($item)
    {
        $dados['tab_img'] = Oft_formularios_imagens::where('cd_agendamento_item', $item)
            ->selectRaw("oft_formularios_imagens.*,date_format(created_at,'%d/%m/%Y') data")
            ->with('usuario')->orderBy('created_at', 'desc')->get();

        $dados['array_img']=null;
        foreach ($dados['tab_img'] as $imgs) {
            $CaminhoImg = null;
            clearstatcache(); // Limpamos o cache de arquivos do PHP
            $CaminhoImg = $imgs->caminho_img;
            if (Storage::disk('s3')->exists($CaminhoImg)) { 

                $mime_type = Storage::disk('s3')->mimeType($CaminhoImg); 
                $ArrayImg['tipo'] = null;
                $ArrayImg['codigo'] = $imgs->cd_img_formulario;
                $ArrayImg['olho'] =  ($imgs->olho) ? $imgs->olho : ' -- '; 
                $ArrayImg['descricao'] =  ($imgs->descricao) ? $imgs->descricao : ' ... '; 
                $ArrayImg['mime_type'] = $mime_type; 
                $ArrayImg['file_size'] = Storage::disk('s3')->size($CaminhoImg); 
                if($ArrayImg['file_size']<=8388608){    
                    //$data = file_get_contents($CaminhoImg); 
                    $data = Storage::disk('s3')->get($CaminhoImg);
                    $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                    $ArrayImg['sn_visualiza'] = 'S'; 
                }else{ 
                    $ArrayImg['conteudo_img'] = null;
                    $ArrayImg['sn_visualiza'] = 'N';
                }

                $ArrayImg['CaminhoImg'] =$CaminhoImg; 
                $ArrayImg['Caminho'] =$CaminhoImg; 
                if (str_contains($mime_type, 'image')) {
                    $ArrayImg['tipo'] = 'img';
                } 
                if (str_contains($mime_type, 'pdf')) {
                    $ArrayImg['tipo'] = 'pdf';
                } 

                $ArrayImg['usuario'] = $imgs->usuario?->nm_usuario;
                $ArrayImg['data'] = $imgs->data;
                $ArrayImg['cd_img_formulario'] = $imgs->cd_img_formulario;
                $dados['array_img'][] = $ArrayImg;

            }

        }
        
        return $dados['array_img'];

    }
     
    public function array_img_OUT($item)
    {
        //$ArrayImg['conteudo_img'] = Storage::disk('s3')->temporaryUrl($imgs->caminho,now()->addMinutes(15));
        
        $dados['tab_img'] = Oft_formularios_imagens::where('cd_agendamento_item', $item)
            ->selectRaw("oft_formularios_imagens.*,date_format(created_at,'%d/%m/%Y') data")
            ->with('usuario')->orderBy('created_at', 'desc')->get();

        $dados['array_img']=null;
        foreach ($dados['tab_img'] as $imgs) {
            $CaminhoImg = null;
            clearstatcache(); // Limpamos o cache de arquivos do PHP
            $CaminhoPath=(env('URL_IMG_EXAMES', '/var/img_oftalmo'));
            $CaminhoImg = $CaminhoPath . "/" . $imgs->caminho_img;
            if (is_file($CaminhoImg)) { 

                $mime_type = mime_content_type($CaminhoImg);   
                $ArrayImg['tipo'] = null;
                $ArrayImg['codigo'] = $imgs->cd_img_formulario;
                $ArrayImg['olho'] =  ($imgs->olho) ? $imgs->olho : ' -- '; 
                $ArrayImg['descricao'] =  ($imgs->descricao) ? $imgs->descricao : ' ... '; 
                $ArrayImg['mime_type'] = $mime_type; 
                $ArrayImg['file_size'] = filesize($CaminhoImg); 
                
                if($ArrayImg['file_size']<=8388608){ 
                    //if($ArrayImg['file_size']<=5629444){ 
                    $data = file_get_contents($CaminhoImg);
                    $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                    $ArrayImg['sn_visualiza'] = 'S'; 
                }else{ 
                    $ArrayImg['conteudo_img'] = null;
                    $ArrayImg['sn_visualiza'] = 'N';
                } 
 
                $ArrayImg['CaminhoImg'] =$CaminhoImg; 
                $ArrayImg['Caminho'] =$CaminhoImg; 
                if (str_contains($mime_type, 'image')) {
                    $ArrayImg['tipo'] = 'img';
                } 
                if (str_contains($mime_type, 'pdf')) {
                    $ArrayImg['tipo'] = 'pdf';
                } 

                $ArrayImg['usuario'] = $imgs->usuario?->nm_usuario;
                $ArrayImg['data'] = $imgs->data;
                $ArrayImg['cd_img_formulario'] = $imgs->cd_img_formulario;
                $dados['array_img'][] = $ArrayImg;
            } 

        }

        return $dados['array_img'];
    }

    public function jsonModal(Request $request)
    {
 
        $agendamento = Agendamento::find($request['cd_agendamento']);
        $paciente = Paciente::find($agendamento->cd_paciente);
        $empresa = Empresa::find($request->user()->cd_empresa);
 
        if(isset($request['exame']['cd_exame'])){
            $dados['texto_padrao'] = ExameFormulario::where('cd_exame',$request['exame']['cd_exame'])
            ->orderBy("nm_formulario")->get();
            foreach ($dados['texto_padrao'] as $key => $valor) {
                $dados['texto_padrao'][$key]['conteudo'] = camposInteligentes($valor->conteudo, $paciente, $agendamento);
            }
    
        }else{
            $dados['texto_padrao'] = null;
        }

        $dados['request'] = $request->toArray();
        $dados['tab_historico'] = AgendamentoItensHist::where('cd_agendamento_item', $request->cd_agendamento_item)
            ->selectRaw("agendamento_item_hist.*,date_format(created_at,'%d/%m/%Y') data ")
            ->with('usuario')->orderBy('created_at', 'desc')->get();
 
        $cdPaciente= $agendamento->cd_paciente;
        
        $dados['tab_historico_exame'] = AgendamentoItens::where('sn_laudo','1')
        ->where('cd_agendamento_item',"<>", $request->cd_agendamento_item)
        ->with(['atendimento' => function($q) use($cdPaciente){  
            $q->where('cd_paciente', $cdPaciente);
        },'exame','atendimento.profissional'])
        ->whereHas('atendimento', function($q) use($cdPaciente) {
            $q->where('cd_paciente', $cdPaciente);
         })
         ->orderBy("created_at","desc")
        ->get();

        /*
        if($empresa->storage_s3=='S'){
            $dados['array_img']=helperArray_img_s3($request->cd_agendamento_item);
        }else{
            $dados['array_img']=helperArray_img($request->cd_agendamento_item);
        }
        */
        $dados['array_img']=helperArray_img_geral($request->cd_agendamento_item);
        
       // dd($empresa->storage_s3,$dados['array_img'],$request->cd_agendamento_item);

        $dados['hist'] = AgendamentoItensHist::where('cd_agendamento_item', '=',$request['cd_agendamento_item'])
        ->join('usuarios', 'usuarios.cd_usuario', 'agendamento_item_hist.cd_usuario')
        ->selectRaw("agendamento_item_hist.*,date_format(agendamento_item_hist.created_at,'%d/%m/%Y %H:%i:%s') created_data ")
        ->selectRaw("usuarios.nm_usuario")
        ->orderBy("agendamento_item_hist.created_at","desc")
        ->get();
        
        return response()->json($dados);
    }

    public function carregaTextoPadrao(Request $request, ExameFormulario $formulario, Agendamento $agendamento)
    { 
        $paciente = Paciente::find($agendamento->cd_paciente); 
        $formulario->conteudo = camposInteligentes($formulario->conteudo, $paciente, $agendamento);

        return response()->json($formulario->toArray());

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
            ->orderBy("agendamento_item_hist.created_at","desc")
            ->get();

        return response()->json($hist);
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

    public function laudo(Request $request,AgendamentoItens $item)
    { 
        HelperDeletaLaudoTemp();
        $item->load('atendimento.paciente','atendimento.convenio',
                    'atendimento.profissional_externo',
                    'atendimento.profissional','img','exame');
        
        $Rascunho=($item->sn_laudo==1) ? false : true;

        $layout =  'P';  
        $empresa =Empresa::find($request->user()->cd_empresa);
        $usuario = $request->user();
        /*
        if($empresa->storage_s3=='S'){
            $imgs = helperArray_img_s3($item->cd_agendamento_item); 
        }else{
            $imgs = helperArray_img($item->cd_agendamento_item); 
        }
        */
        $imgs=helperArray_img_geral($item->cd_agendamento_item);
        
        $Imagens = $imgs;
        if($imgs){
            foreach ($imgs as $key => $value) {
                if($value['tipo'] == 'pdf'){ 
                   unset($Imagens[$key]);
                } 
           }
        }   
 
        $pdfMerger = PDFMerger::init();
        // $file="c:/file.pdf";
        $pdf = Pdf::loadView('rpclinica.central_laudos.documento-laudo', compact('Imagens','item','empresa','usuario','Rascunho')); 
        $pdf->save('laudo/laudo-item-'.$item->cd_agendamento_item.'.pdf');
        $pdfMerger->addPDF('laudo/laudo-item-'.$item->cd_agendamento_item.'.pdf', 'all');

        if($imgs){
            foreach ($imgs as $key => $value) {
                if($value['tipo'] == 'pdf'){ 
                    $pdfMerger->addPDF($value['Caminho'], 'all');
                } 
           }
        } 
     
        $pdfMerger->merge();
        return $pdfMerger->save('laudo/laudo-item-'.$item->cd_agendamento_item.'.pdf', "browser");
        // var_dump($pdfMerger);s
        // return $pdf->setPaper('a4', $layout)->stream('laudo-item-'.$item->cd_agendamento_item.'.pdf');
     
    }
    
    public function laudo_externo(Request $request, $exame, $key)
    {  
        
        HelperDeletaLaudoTemp();
        $item = AgendamentoItens::where('cd_agendamento_item',$exame)
        ->where('key',$key)
        ->where('sn_laudo','1')
        ->first();
        if($item){
            $item->load('atendimento.paciente','atendimento.convenio','usuario',
            'atendimento.profissional_externo','atendimento.profissional','img','exame');
           
            $layout =  'P';  
            $empresa =Empresa::find($item->usuario->cd_empresa);
            $usuario = null;

            /*
            if($empresa->storage_s3=='S'){
                $imgs = helperArray_img_s3($item->cd_agendamento_item); 
            }else{
                $imgs = helperArray_img($item->cd_agendamento_item); 
            }
            */

            $imgs=helperArray_img_geral($item->cd_agendamento_item);
             
            $Imagens = $imgs;
            if($imgs){
                foreach ($imgs as $key => $value) {
                    if($value['tipo'] == 'pdf'){ 
                       unset($Imagens[$key]);
                    } 
               }
            } 

            $pdfMerger = PDFMerger::init();
            // $file="c:/file.pdf";
            $Rascunho=null;
            $pdf = Pdf::loadView('rpclinica.central_laudos.documento-laudo', compact('Imagens','item','empresa','usuario','Rascunho')); 
            $pdf->save('laudo/laudo-item-'.$item->cd_agendamento_item.'.pdf');
            $pdfMerger->addPDF('laudo/laudo-item-'.$item->cd_agendamento_item.'.pdf', 'all');
    
            if($imgs){
                foreach ($imgs as $key => $value) {
                    if($value['tipo'] == 'pdf'){ 
                        $pdfMerger->addPDF($value['Caminho'], 'all');
                    } 
               }
            } 
         
            $pdfMerger->merge();
            return $pdfMerger->save('laudo/laudo-item-'.$item->cd_agendamento_item.'.pdf', "browser");
            // var_dump($pdfMerger);s
            // return $pdf->setPaper('a4', $layout)->stream('laudo-item-'.$item->cd_agendamento_item.'.pdf');
            
        }else{

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
            $laudo->situacao = 'A';
            $laudo->dt_situacao = date('Y-m-d H:i');
            $laudo->save();

            $hist=array(
                'cd_agendamento_item'=>$cd_agendamento_item,
                'laudo'=>$dados['conteudo_laudo'],
                'ds_historico'=>'Foi inserido informação  por '.$request->user()->nm_usuario.' ( '.$request->user()->cd_usuario.' )',
                'sn_laudo'=>'aguardando',
                'cd_usuario'=>$request->user()->cd_usuario
            );
            AgendamentoItensHist::create($hist);

            return response()->json(['request' => true]);

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function liberarLaudo(Request $request, $cd_agendamento_item)
    {

        try {

            DB::beginTransaction();

            $laudo = AgendamentoItens::find($cd_agendamento_item);
             
            $key = null;
            if($request['sn_laudo']==true){

                if(empty($laudo->conteudo_laudo)){
                
                    return response()->json(['message' =>  'Não é permitido Liberar laudo com o conteudo vazio.<br>Favor clicar no botão Salvar e depois clicar no botão liberar Laudo. '], 500);
                }

                $bytes = random_bytes(20); 
                $key = bin2hex($bytes);

                $laudo->sn_laudo = '1';
                $laudo->usuario_laudo = $request->user()->cd_usuario;
                $laudo->dt_laudo = date('Y-m-d H:i');
                $laudo->situacao = 'R';
                $laudo->key = $key;
                $laudo->dt_situacao = date('Y-m-d H:i');

                $hist=array(
                    'cd_agendamento_item'=>$cd_agendamento_item,
                    'laudo'=>$laudo->conteudo_laudo,
                    'ds_historico'=>'Laudo LIBERADO por '.$request->user()->nm_usuario.' ( '.$request->user()->cd_usuario.' )',
                    'sn_laudo'=>'liberado',
                    'cd_usuario'=>$request->user()->cd_usuario
                );
                AgendamentoItensHist::create($hist);

            }else{
                $laudo->sn_laudo = '0';
                $laudo->usuario_laudo = null;
                $laudo->dt_laudo = null;
                $laudo->situacao = 'E';
                $laudo->key = $key;
                $laudo->dt_situacao = date('Y-m-d H:i');

                $hist=array(
                    'cd_agendamento_item'=>$cd_agendamento_item,
                    'laudo'=>$laudo->conteudo_laudo,
                    'ds_historico'=>'Laudo BLOQUEADO por '.$request->user()->nm_usuario.' ( '.$request->user()->cd_usuario.' )',
                    'sn_laudo'=>'cancelado',
                    'cd_usuario'=>$request->user()->cd_usuario
                );
                AgendamentoItensHist::create($hist);
            } 
            $laudo->save(); 
             
            DB::commit();
            return response()->json(['request' => $laudo,'hist'=> $this->getHist($cd_agendamento_item), 'key'=>$key ]);

        } catch (Throwable $error) {  
            DB::rollback(); 
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
        
    }

    public function storeImg(Request $request, $cd_agendamento_item)
    {
          
        try {
            $files = count($request['image']) - 1; 
            foreach(range(0, $files) as $index) {
                $rules['image.' . $index] = 'required|file|mimes:jpg,jpeg,png,psd,tiff,svg,raw,pdf,webp,bmp,png,gif|max:204800';
            }
            $validator = Validator::make($request->all(), $rules );
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
             
            $file = $request->image;  
            foreach ($file as $image) {

                $dados['olho'] = ($request['olho']) ? $request['olho'] : null;
                $dados['cd_agendamento'] = $request['cd_agendamento'];
                $dados['cd_agendamento_item'] = $cd_agendamento_item;
                $dados['cd_usuario_exame'] = $request->user()->cd_usuario; 
                $dados['dt_exame'] = date('Y-m-d H:i:s'); 
                $dados['cd_formulario'] = 'EXAME';
                $dados['descricao'] = ($request['descricao']) ? $request['descricao'] : null; 
                $dados['caminho_nome'] = $image->getClientOriginalName();
                
                $empresa = Empresa::find($request->user()->cd_empresa);
                if($empresa->storage_s3=='S'){

                    $path = $image->store($empresa->storage_file."/".$cd_agendamento_item,'s3'); 
                    $dados['sn_storage'] = 's3';
                }else{
                    $path = $image->store($cd_agendamento_item); 
                    $dados['sn_storage'] = 'local';
                } 
                $dados['caminho_img'] = $path; 
    
                $retorno = DB::transaction(function () use ($dados,$request){
                    $tabela = Oft_formularios_imagens::create($dados); 
                    $usuario_logado = $request->user(); 
                    $tabela ->criarLogCadastro($usuario_logado,'agendamento','laudo',$dados['cd_agendamento']); 
                    return $tabela;
                });

            }
            
            /*
            if($empresa->storage_s3=='S'){    
                $dados['array_img']= helperArray_img_s3($cd_agendamento_item);
            }else{
                $dados['array_img']=helperArray_img($cd_agendamento_item);
            }
            */

            $dados['array_img']=helperArray_img_geral($cd_agendamento_item);

            return response()->json(['request' => $dados, 'retorno' => $retorno,'relacaoImg'=>$this->relacaoImg($request,$cd_agendamento_item)]);
            
        } catch (Throwable $error) { 
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
    
    public function relacaoImg(Request $request, $cd_agendamento_item)
    {   

        try {
 
            $empresa = Empresa::find($request->user()->cd_empresa);

            $historico = Oft_formularios_imagens::where("cd_agendamento_item",$cd_agendamento_item) 
            ->join('usuarios', 'usuarios.cd_usuario', 'oft_formularios_imagens.cd_usuario_exame')
            ->where('oft_formularios_imagens.cd_formulario', 'EXAME')   
            ->orderBy('oft_formularios_imagens.created_at', 'desc')->get(); 

            $array_img = [];


            foreach ($historico as $imgs) {
            
                if($imgs->sn_storage=='s3'){
 
                    clearstatcache(); // Limpamos o cache de arquivos do PHP
                    $CaminhoImg = $imgs->caminho_img; 
                    if (Storage::disk('s3')->exists($CaminhoImg)) { 
            
                        $mime_type = Storage::disk('s3')->mimeType($CaminhoImg); 
                        $ArrayImg['tipo'] = null;
                        $ArrayImg['codigo'] = $imgs->cd_img_formulario;
                        $ArrayImg['olho'] =  ($imgs->olho) ? $imgs->olho : ' -- '; 
                        $ArrayImg['descricao'] =  ($imgs->descricao) ? $imgs->descricao : ' ... '; 
                        $ArrayImg['sn_storage'] =  ($imgs->sn_storage) ? $imgs->sn_storage : ' ?? '; 
                        $ArrayImg['mime_type'] = $mime_type; 
                        $ArrayImg['file_size'] = Storage::disk('s3')->size($CaminhoImg); 
                        if($ArrayImg['file_size']<=8388608){    
                            //$data = file_get_contents($CaminhoImg); 
                            $data = Storage::disk('s3')->get($CaminhoImg);
                            $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                            $ArrayImg['sn_visualiza'] = 'S'; 
                        }else{ 
                            $ArrayImg['conteudo_img'] = null;
                            $ArrayImg['sn_visualiza'] = 'N';
                        }
        
                        $ArrayImg['CaminhoImg'] =$CaminhoImg; 
                        $ArrayImg['Caminho'] =$CaminhoImg; 
                        if (str_contains($mime_type, 'image')) {
                            $ArrayImg['tipo'] = 'img';
                        } 
                        if (str_contains($mime_type, 'pdf')) {
                            $ArrayImg['tipo'] = 'pdf';
                        } 
        
                        $ArrayImg['usuario'] = $imgs->nm_usuario;
                        $ArrayImg['data'] = $imgs->dt_exame;
                        $ArrayImg['cd_img_formulario'] = $imgs->cd_img_formulario;
                        $array_img[] = $ArrayImg;
        
                    }
                  
                }
                if($imgs->sn_storage=='local'){

                    clearstatcache(); // Limpamos o cache de arquivos do PHP
                    $CaminhoPath=(env('URL_IMG_EXAMES', '/var/img_oftalmo'));
                    $CaminhoImg = $CaminhoPath . "/" . $imgs->caminho_img; 
                  
                    if (is_file($CaminhoImg)) {
     
                        $mime_type = mime_content_type($CaminhoImg); 
                        $ArrayImg['tipo'] = null;
                        $ArrayImg['codigo'] = $imgs->cd_img_formulario;
                        $ArrayImg['olho'] = ($imgs->olho) ? $imgs->olho : ' -- '; 
                        $ArrayImg['descricao'] =  ($imgs->descricao) ? $imgs->descricao : ' ... '; 
                        $ArrayImg['sn_storage'] =  ($imgs->sn_storage) ? $imgs->sn_storage : ' ?? '; 
                        $ArrayImg['mime_type'] = $mime_type; 
                        $ArrayImg['file_size'] = filesize($CaminhoImg); 
                        if($ArrayImg['file_size']<=8388608){
                        //if($ArrayImg['file_size']<=5629444){ 
                            $data = file_get_contents($CaminhoImg);
                            $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                            $ArrayImg['sn_visualiza'] = 'S'; 
                        }else{ 
                            $ArrayImg['conteudo_img'] = null;
                            $ArrayImg['sn_visualiza'] = 'N';
                        } 
                        $ArrayImg['CaminhoImg'] =$CaminhoImg; 
                        $ArrayImg['Caminho'] =$CaminhoImg; 
                        if (str_contains($mime_type, 'image')) {
                            $ArrayImg['tipo'] = 'img';
                        } 
                        if (str_contains($mime_type, 'pdf')) {
                            $ArrayImg['tipo'] = 'pdf';
                        }
                        $ArrayImg['cd_img_formulario'] = $imgs->cd_img_formulario;
                        $ArrayImg['usuario'] = $imgs->nm_usuario;
                        $ArrayImg['data'] = $imgs->dt_exame;
                        $array_img[] = $ArrayImg;
                         
                    }  

                }
  
            }
   
            return response()->json(['retorno' => $array_img ]);
 
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function deleteImg(Request $request, $cd_image_formulario)
    {
        try {
            
            $empresa = Empresa::find($request->user()->cd_empresa);

            $Img=Oft_formularios_imagens::find($cd_image_formulario);
            $cdItem=$Img->cd_agendamento_item;
            $sn_storage=$Img->sn_storage;

            DB::transaction(function () use ($cd_image_formulario,$request,$Img,$empresa,$sn_storage){ 
                
                Oft_formularios_imagens::where('cd_img_formulario',$cd_image_formulario)->delete(); // TODO: delete file too 
     
                //if($empresa->storage_s3=='S'){
                if($sn_storage=='s3'){

                    Storage::disk('s3')->delete($Img->caminho_img);                    
                }
                if($sn_storage=='local'){
                    $caminho_arquivo = env('URL_IMG_EXAMES')."/".$Img->caminho_img; 
                    if (file_exists($caminho_arquivo)) {
                        unlink($caminho_arquivo);
                    } 

                }

            }); 
            
            /*  
            if($sn_storage=='s3'){
                $dados['array_img']=helperArray_img_s3($cdItem);
            }
            if($sn_storage=='local'){
                $dados['array_img']=helperArray_img($cdItem);
            }
            */

            $dados['array_img']=helperArray_img_geral($cdItem);

            return response()->json(['dados' => $dados  ]);
            
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
    
    public function visualizarDoc(Request $request, Oft_formularios_imagens $img)
    {
        try {
    
       
            $empresa = Empresa::find($request->user()->cd_empresa);
            //if($empresa->storage_s3=='S'){
            if($img->sn_storage='s3'){ 
 
                return Storage::disk('s3')->response($img->caminho_img);

            }

            if($img->sn_storage='local'){ 

                $CaminhoPath=(env('URL_IMG_EXAMES', '/var/img_oftalmo'));
                $CaminhoImg = $CaminhoPath . "/" . $img->caminho_img; 
                if (is_file($CaminhoImg)) {
                    $mime_type = mime_content_type($CaminhoImg); 
                    header("Content-Type: ".$mime_type); 
                    echo file_get_contents($CaminhoImg); 
                }else{
                    echo "Erro";
                }

            }

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function painelImagens(Request $request, AgendamentoItens $item)
    {
        try {
            
            $empresa = Empresa::find($request->user()->cd_empresa);
            //if($empresa->storage_s3=='S'){
            /*
            if($item->sn_storage='s3'){ 
                $dados['array_img'] = helperArray_img_s3($item->cd_agendamento_item); 
            }
            if($item->sn_storage='local'){ 
                $dados['array_img'] = helperArray_img($item->cd_agendamento_item); 
            }
            */
            
            $dados['array_img']=helperArray_img_geral($item->cd_agendamento_item);

            //dd($dados['array_img']);
            $item->load('atendimento.paciente','exame');
 
            return view('rpclinica.central_laudos.imagens', compact('item','dados'));

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
    
}
