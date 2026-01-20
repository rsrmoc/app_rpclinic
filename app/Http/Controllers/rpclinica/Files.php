<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoItens; 
use App\Model\rpclinica\Profissional; 
use App\Model\rpclinica\SituacaoItem; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\rpclinica\Comunicacoes;
use App\Model\rpclinica\Teste;
use App\Model\rpclinica\WhastRotina;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Bibliotecas\ApiWaMe;
use App\Model\rpclinica\AgendaEscala;
use App\Model\rpclinica\AgendaEscalaHorario;
use App\Model\rpclinica\Diretorios;
use App\Model\rpclinica\DiretoriosS3;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Oft_formularios_imagens;
use DirectoryIterator;
use Illuminate\Support\Facades\Storage;
use Throwable;

class Files extends Controller
{

    private $diretorio;

    public function __construct()
    {
        set_time_limit(600); // Applies to any method in this controller
        $this->diretorio = env('URL_IMG_EXAMES', '/var/img_oftalmo');
    }
 
    public function arquivos(Request $request, Empresa $empresa ,$key)
    { 
      
        echo $diretorio = env('URL_IMG_EXAMES', '/var/img_oftalmo');
   
        if (is_dir($this->diretorio)) {
            $it = new DirectoryIterator($this->diretorio);
            Diretorios::truncate();
            foreach ($it as $fileinfo) {  
                if (!$fileinfo->isDot()) { 
                    if ($fileinfo->isDir()) { 
                        if( (Diretorios::whereRaw("cd_diretorio = '".$fileinfo->getFilename()."'")->count()) == 0 ){
                            $Agendamento=Agendamento::whereRaw("cd_agendamento = '".$fileinfo->getFilename()."'")->count();
                            Diretorios::create([
                                'dt_diretorio'=>date("Y-m-d H:i:s", $fileinfo->getcTime()),
                                'cd_diretorio'=>$fileinfo->getFilename(),
                                'sn_sistema'=> ($Agendamento==1) ? 'S' : 'N' 
                            ]); 
                        } 
                    }   
                }
            } 
        }
        $all=Diretorios::whereRaw("sn_sistema='N'")->get();
        foreach($all as $val){
            $this->delete_directory($this->diretorio.'/'.$val->cd_diretorio); 
            echo $this->diretorio.'/'.$val->cd_diretorio.'<br>';
        }

        echo "Atualizado com Sucesso!!!";
  
    }

    public function validar_caminho(Request $request, Empresa $empresa ,$key)
    {

        $imgs = Oft_formularios_imagens::whereRaw(" sn_storage = 'local' ")->get();
        foreach($imgs as $img){
            $caminho_local=$this->diretorio.'/'.$img->caminho_img;
            if (file_exists($caminho_local)) {
                Oft_formularios_imagens::where("cd_img_formulario",$img->cd_img_formulario)
                ->update(['sn_file_valido'=>'S']);
            }else{
                Oft_formularios_imagens::where("cd_img_formulario",$img->cd_img_formulario)
                ->update(['sn_file_valido'=>'N']);
            }
        }

    }

    public function mover_file_s3(Request $request, Empresa $empresa ,$key)
    { 
      
        if($request['data']){ 
            $dt=" and date(dt_laudo) like '".$request['data']."%' "; 
        }else{
            $dt=" and date(dt_laudo) like '999-99%' "; 
        }
   
        $query=DB::select("
        select cd_diretorio,dt_diretorio,sn_sistema,agendamento.cd_agendamento,dt_agenda,dt_laudo,sn_laudo,
		agendamento_itens.cd_agendamento_item,oft_formularios_imagens.caminho_img,oft_formularios_imagens.cd_img_formulario
        from diretorios
        inner join agendamento on agendamento.cd_agendamento=diretorios.cd_diretorio
        inner join agendamento_itens on agendamento_itens.cd_agendamento=agendamento.cd_agendamento
        inner join oft_formularios_imagens on oft_formularios_imagens.cd_agendamento_item=agendamento_itens.cd_agendamento_item
        where sn_laudo='1' 
        and sn_storage = 'local' 
        and sn_file_valido = 'S'
        $dt
        order by dt_laudo");
        foreach($query as $img){

            $caminho_local=$this->diretorio.'/'.$img->caminho_img;
            $caminho_s3=$empresa->storage_file.'/'.$img->caminho_img;
            
            if (file_exists($caminho_local)) {

                $stream = fopen($caminho_local, 'r');
                $destinationPath = $caminho_s3; 
                $retorno=Storage::disk('s3')->put($destinationPath, $stream);
                if ($retorno) {
                      DiretoriosS3::create([
                        'cd_diretorio'=> $img->cd_agendamento_item,
                        'caminho_local'=>$caminho_local,
                        'caminho_s3'=>$caminho_s3,
                        'situacao'=>'importado'
                      ]);
                }else{
                    DiretoriosS3::create([
                        'cd_diretorio'=> $img->cd_agendamento_item,
                        'caminho_local'=>$caminho_local,
                        'caminho_s3'=>$caminho_s3,
                        'situacao'=>'erro'
                      ]);
                }
                 
                if (is_resource($stream)) {
                    fclose($stream);
                }
                echo " SIM";

            }else{

                DiretoriosS3::create([
                    'cd_diretorio'=> $img->cd_agendamento_item,
                    'caminho_local'=>$caminho_local,
                    'caminho_s3'=>$caminho_s3,
                    'situacao'=>'erro'
                  ]);
            }
            echo '<br>';

        }

    }


    public function delete_directory($dir) {
        if (!file_exists($dir)) {
            return true;
        }
    
        if (!is_dir($dir)) {
            return unlink($dir);
        }
    
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
    
            if (!$this->delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
    
        return rmdir($dir);
    }
 
}
