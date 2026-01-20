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

class cronTab extends Controller
{

    public function __construct()
    {
        set_time_limit(600); // Applies to any method in this controller
    }
 
    public function laudos(Request $request, $key)
    { 
        try {
             
            if($key=='9a4dc32g5d6h8e'){ 
                WhastRotina::where('tipo','laudo')->update(['dt_rotina'=>date('Y-m-d H:i')]);
                set_time_limit(600);
                $codEmpresa=7;
                $comunicacao = new Comunicacoes();
                
                $query = AgendamentoItens::whereRaw("cd_status_envio in ('A') ")
                    ->whereRaw("sn_laudo='1'") 
                    ->whereRaw(" date(dt_laudo) < '".date('Y-m-d')."'")
                    ->selectRaw("agendamento_itens.cd_agendamento_item,date(dt_laudo) dt_laudo") 
                    ->orderBy("created_at","desc")->limit(25)->get(); 
                //dd($query->toSql());
                //dd(count($query->toArray()));    
                foreach($query as $item){
                         
                    sleep(20);
                    $retorno = $comunicacao->send_laudo($item->cd_agendamento_item,$codEmpresa,'S'); 
                   
                     
                     
                }    
                WhastRotina::where('tipo','laudo')->update(['dt_rotina'=>date('Y-m-d H:i')]);
                echo "<br><br><b>Rotina executada com sucesso!! ".date('d/m/Y H:i')."</b>";

            }else{
                echo "<br><b>KEY invalida!! </b>".date('d/m/Y H:i');
            }

            return true;
 
        } catch (Throwable $error) {  
            return redirect()->route('logs.rotina.whast')->with('error', 'Erro ao enviar mensagem! <br>'.$error->getMessage());
        
        }
    }

    public function whast_grupo(Request $request, $key,Empresa $empresa)
    { 
        try {
            if($key=='9a4dc32g5d6h8e'){
                if($empresa->grupo_whast){

                    $total = Agendamento::whereRaw("sn_laudo = 0")
                    ->join('agendamento_itens','agendamento_itens.cd_agendamento','agendamento.cd_agendamento')
                    ->join('exames','exames.cd_exame','agendamento_itens.cd_exame')
                    ->join('profissional','profissional.cd_profissional','agendamento.cd_profissional')  
                    ->count(); 
                    
                    $TEXTO="*".$empresa->nm_empresa."* \n📆 *Data:* ".date('d/m/Y H:i')." \n📊 *Total de Laudos Pendente:* ".$total."\n\n 📅 *Pendencia Por Competência* ‼️\n";
                    /* Exame  Pendentes Competencia */
                    $listaPizza = Agendamento::whereRaw("sn_laudo = 0")
                    ->join('agendamento_itens','agendamento_itens.cd_agendamento','agendamento.cd_agendamento')
                    ->join('exames','exames.cd_exame','agendamento_itens.cd_exame')
                    ->join('profissional','profissional.cd_profissional','agendamento.cd_profissional')
                    ->selectRaw("date_format(dt_atendimento,'%Y%m') data, date_format(dt_atendimento,'%m/%Y') dt, count(*) qtde") 
                    ->groupByRaw("date_format(dt_atendimento,'%Y%m'),date_format(dt_atendimento,'%m/%Y')") 
                    ->orderByRaw("1")
                    ->get(); 
                    foreach($listaPizza as $val){
                        $TEXTO=$TEXTO."➖  *_".$val->dt.":_* ".$val->qtde."\n";
                    }

                    $TEXTO=$TEXTO."\n 🥹 *Pendencia Por Profissional* ‼️\n";
                    $listaPizza = Agendamento::whereRaw("sn_laudo = 0")
                    ->join('agendamento_itens','agendamento_itens.cd_agendamento','agendamento.cd_agendamento')
                    ->join('exames','exames.cd_exame','agendamento_itens.cd_exame')
                    ->join('profissional','profissional.cd_profissional','agendamento.cd_profissional')
                    ->selectRaw("nm_profissional, count(*) qtde") 
                    ->groupByRaw("nm_profissional") 
                    ->orderByRaw("2 desc")
                    ->get();  
                    foreach($listaPizza as $val){
                        $TEXTO=$TEXTO."➖ *_".$val->nm_profissional.":_* ".$val->qtde."\n";
                    }
                
                    $whatsapp = new ApiWaMe();
                  
                    $retorno=$whatsapp->sendTextMessage($empresa->grupo_whast, $TEXTO,$empresa->cd_empresa);
                    return response()->json([ 'retorno'=>true,'dados'=>$retorno]); 
                }else{
                    return response()->json([ 'retorno'=>false,'mensagem'=>'Grupo não cadastrado']); 
                }
            }else{ 
                return response()->json([ 'retorno'=>false,'mensagem'=>'Key Invalida']); 
            }
  
        } catch (Throwable $error) {   
            return response()->json([ 'retorno'=>false,'mensagem'=>'Erro ao enviar mensagem! <br>'.$error->getMessage()]); 
        }
    }

    public function ajuste_agenda(Request $request)
    { 
     
        $lista=AgendaEscala::whereIn('cd_agenda',[61,62,63,64,65,66,67,68])->get();
        //dd($lista->toArray());
        echo "<pre>";
        foreach($lista as $val){
           echo $hr_inicial = substr($val->hr_inicial,0,5);
           echo "<br>1<br>";
            echo $cd_inicial = str_replace(':', '.',   substr($val->hr_inicial,0,5) ); 
            echo "<br>1<br>";
            $cd_final = str_replace(':', '.', substr($val->hr_final,0,5)); 
            $intervalo = $val->intervalo;
      
            echo $cd_inicial. "<br>";
            for ($hr = $cd_inicial; $hr <  $cd_final;) {
                 
                
                $horaNova = strtotime("$hr_inicial + ".$intervalo." minutes"); 
                $horaNovaFormatada = date("H:i",$horaNova); 
                $HoraAgedamento = str_replace('.', ':', $hr); 
                $hr = str_replace(':', '.', $horaNovaFormatada);
                $hr_inicial = $horaNovaFormatada;
                
                $Array=array(
                 'cd_escala_agenda'=>$val->cd_escala_agenda,
                 'cd_agenda'=>$val->cd_agenda,
                 'cd_horario'=>$HoraAgedamento,
                // 'cd_usuario'=>$request->user()->cd_usuario 
                );
                print_r($Array);
                
                AgendaEscalaHorario::create($Array);
                
     
            }
           
        }


    }

    public function files(Request $request, Empresa $empresa ,$key)
    { 
      
        echo $diretorio = env('URL_IMG_EXAMES', '/var/img_oftalmo');
   
        if (is_dir($diretorio)) {
            $it = new DirectoryIterator($diretorio);
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
            $this->delete_directory($diretorio.'/'.$val->cd_diretorio); 
            echo $diretorio.'/'.$val->cd_diretorio.'<br>';
        }

        $query=DB::select("select cd_diretorio,dt_diretorio,sn_sistema,agendamento.cd_agendamento,dt_agenda,dt_laudo,sn_laudo,cd_agendamento_item
        from diretorios
        inner join agendamento on agendamento.cd_agendamento=diretorios.cd_diretorio
        inner join agendamento_itens on agendamento_itens.cd_agendamento=agendamento.cd_agendamento
        where sn_laudo='1'
        order by dt_laudo ");
        foreach($query as $val){
            $imgs = Oft_formularios_imagens::whereRaw("cd_agendamento_item=".$val->cd_agendamento_item)
            ->whereRaw(" sn_storage = 'local' ")->get();
            foreach($imgs as $img){

               echo  $caminho_local=$diretorio.'/'.$img->caminho_img;
                $caminho_s3=$empresa->storage_file.'/'.$img->caminho_img;
                
                if (file_exists($caminho_local)) {

                    $stream = fopen($caminho_local, 'r');
                    $destinationPath = $caminho_s3; 
                    $retorno=Storage::disk('s3')->put($destinationPath, $stream);
                    if ($retorno) {
                          DiretoriosS3::create([
                            'cd_diretorio'=> $img->cd_agendamento_item,
                            'caminho_local'=>$caminho_local,
                            'caminho_s3'=>$caminho_s3
                          ]);
                    }
                     
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                    echo " SIM";

                }else{
                    echo " NAO";
                }
                echo '<br>';

 
            }
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
