<?php

namespace App\Http\Controllers\rpclinica;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\EmpresaEmail;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\WhastApi;
use App\Model\rpclinica\WhastSend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use App\Bibliotecas\PDFSign;
use App\Model\rpclinica\Menu;
use App\Model\rpclinica\ProcedimentoConvenio;
use App\Model\rpclinica\Usuario;
use Illuminate\Contracts\Session\Session as SessionSession;
use Spatie\PdfToImage\Pdf as PdfToImagePdf;
use App\Bibliotecas\WhatsApp;
use App\Bibliotecas\ApiWaMe;
use App\Model\rpclinica\Formulario;
use finfo;
use Illuminate\Support\Facades\Storage;

 
class Inicial extends Controller
{

   
 
    /*
    UPLOAD AWS
    public function store_aws(Request $request)
    {
        $file = $request->file('image');
        $filename = $file->getClientOriginalName();
      
        $path = $file->storeAs('pasta',$filename,'s3');

      // upload arquivo
      $url = Storage::disk('s3')->temporaryUrl('pasta/'.$filename,now()->addMinutes(15));
      echo '<img src="'.$url.'" >';
      dd($url);
      // upload arquivo
     //return Storage::disk('s3')->response('pasta/'.$filename);


      // captura o arquivo
      //  return Storage::disk('s3')->get('pasta/teste.png');
      
      // deleta o arquivo
      // return Storage::disk('s3')->delete('pasta/teste.png');

    }

    public function aws(Request $request)
    { 
    
      echo '
      <form role="form"   action="'.route('aws2').'" enctype="multipart/form-data" method="post" >
       
      <input type="file" name="image">
      <input type="hidden" name="_token" value="'.csrf_token().'">
      <input type="submit" class="btn btn-success"  value="Salvar" />
      </form>
      '; 

    }
    */

    public function index(Request $request)
    {     
      
      /*
      $relatorio['titulo']='';
      $relatorio['paciente']='';
      $relatorio['dt_nasc']='';
      $relatorio['data']='2025-08-02';
      $relatorio['dt_nasc']='';
      $relatorio['tp_assinatura']='';
      $relatorio['nm_profissional']='';
      $relatorio['conselho']='';
      $relatorio['crm']='';
      $relatorio['conteudo']='';

      
      $execultante['logo']='N';
      $execultante['header']='N';
      $execultante['footer']='N';
      $execultante['data']='N';
      $execultante['sn_logo']='N';
      $execultante['sn_assinatura'] = 'N';
      $execultante['sn_ocultar_titulo']= 'N';
      $execultante['assinatura']= null;
      $execultante['tp_assinatura']= null; 
      $execultante['end_empresa']= '';
      $pdf = FacadePdf::loadView('rpclinica.consultorio.formularios.consultorio.impressao.rpclinic.impressao', compact('relatorio','execultante' ));

      //return $pdf->stream('Documento.pdf');

      
      $pdf->setPaper('A4', 'portrait'); 
      $ARQ=$pdf->stream();
      //header("Content-type:application/pdf");   
      //echo base64_decode($ARQ);
      //exit;
      

      $api = new ApiWaMe(); 
      $retorno=$api->sendDocumentoBase64('553888281639','adasdas dsadsadasdas', 
            base64_encode($ARQ),
            'application/pdf' ); 
      dd($retorno);
      
      
      echo $xx = (file_get_contents(asset("rpclinica/json/imprimirDocumentoPaciente/16")));

      header("Content-type:application/pdf");  
      echo ($xx);
      exit;

      //dd( mime_content_type(file_get_contents(asset('http://rp.oftalmo/rpclinica/json/imprimirDocumentoPaciente/16'))) , base64_encode(file_get_contents(asset('http://rp.oftalmo/rpclinica/json/imprimirDocumentoPaciente/16'))));
     
      $emp = Empresa::find($request->user()->cd_empresa);
      $api = new ApiWaMe(); 
      $retorno=$api->sendDocumentoBase64('553888281639','adasdas dsadsadasdas', 
            base64_encode(file_get_contents("c:\same.pdf")),
            'application/pdf' ); 
      dd($retorno);
      
      */

      /*
      $emp = Empresa::find($request->user()->cd_empresa);
      $api = new ApiWaMe(); 
      $retorno=$api->listMessage('553888281639',$emp); 
      $Instance = json_decode($retorno['dados'], true);
      //echo date( "d/m/Y H:i", 1748038525);
      echo strtotime('2025-05-23 19:15:25');
      dd($Instance);
      */

 
 
      //dd(Session::all());

      $request['data']=date('Y-m');
      $profissionais=$request->user()->sn_profissional;
      $emp = Empresa::find($request->user()->cd_empresa);
      $user=Usuario::find($request->user()->cd_usuario);
      $user->load('perfil');    
      if($request->user()->sn_notificacao=='sim'){
        $msgZap=helperInforInstance($emp); 
      }else{
        $msgZap=null;
      }
       
      return view('rpclinica.inicial.inicial',compact('profissionais','emp','request','user','msgZap'));

    }

    public function menu(Request $request)
    {

      if(empty(Session::get('menuCompact'))){
        Session::put('menuCompact', 'small-sidebar');
      }else{
        Session::put('menuCompact', null);
      }

      return true;

    }

    public function dias($quant){
      
      $Sub=($quant*(-1));
      for ($i = $Sub; $i <= 0; $i++) {
        $Data = date('Y-m-d', strtotime('+'. $i.' days', strtotime(date('Y-m-d'))));
        $Datas[$Data] = array('data'=>$Data,'qtde'=>0);
      }
      for ($i = 1; $i <= $quant; $i++) {
        $Data = date('Y-m-d', strtotime('+'. $i.' days', strtotime(date('Y-m-d'))));
        $Datas[$Data] = array('data'=>$Data,'qtde'=>0);
      }

      return ($Datas); ;
    }

    public function semPermissao() {
        return view('rpclinica.layout.erro', ['dados' => 'Você não tem permissão de acesso!']);
    }

    public function jsonPanelComp(Request $request) {

      if($request->user()->cd_profissional){

        $historico = Agendamento::with(['paciente' => function($q) use($request){
          $q->selectRaw("paciente.*,
          TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) ano,
          FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30) mes,
         ( ( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) - ( FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30)*30) ) dia,

         case when TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) = 1 then '1 ano'
         when  TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) >1 then concat(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()),' anos')
         else '' end anoss,

         case when FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30) = 1 then ' 1 mês'
         when  FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30) >1 then concat(FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30),' meses')
         else '' end mess ,

         case when ( ( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) - ( FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30)*30) ) = 1 then ' 1 dia'
         when  ( ( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) - ( FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30)*30) ) > 1 then concat( ( ( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) - ( FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30)*30) ),' dias')
         else '' end dias

         ");
        },'convenio' => function($q) use($request){
          $q->selectRaw('convenio.*');
        },'especialidade' => function($q) use($request){
          $q->selectRaw('especialidade.*');
        }])
        ->selectRaw("agendamento.*,data_horario,situacao,
        concat(DATE_FORMAT(data_horario, '%d'),' ',
        (CASE month(data_horario)
                               when 1 then 'Janeiro'
                               when 2 then 'Fevereiro'
                               when 3 then 'Março'
                               when 4 then 'Abril'
                               when 5 then 'Maio'
                               when 6 then 'Junho'
                               when 7 then 'Julho'
                               when 8 then 'Agosto'
                               when 9 then 'Setembro'
                               when 10 then 'Outubro'
                               when 11 then 'Novembro'
                               when 12 then 'Dezembro'
                               END)
        ,' ',DATE_FORMAT(data_horario, '%Y')) dia_data")
        ->whereRaw("situacao<>'livre'")->whereRaw("situacao<>'bloqueado'")
        ->whereRaw("cd_profissional=".$request->user()->cd_profissional)->whereRaw("date(data_horario)<=curdate()")
        ->whereRaw("date(data_horario) >= date_sub(curdate(), INTERVAL 5 DAY)")
        ->orderByRaw("1")->get();

        $retorno= Agendamento::selectRaw("
        distinct(date(data_horario))data_agendamento,situacao,

        concat(DATE_FORMAT(data_horario, '%d'),' ',
        (CASE month(data_horario)
                               when 1 then 'Janeiro'
                               when 2 then 'Fevereiro'
                               when 3 then 'Março'
                               when 4 then 'Abril'
                               when 5 then 'Maio'
                               when 6 then 'Junho'
                               when 7 then 'Julho'
                               when 8 then 'Agosto'
                               when 9 then 'Setembro'
                               when 10 then 'Outubro'
                               when 11 then 'Novembro'
                               when 12 then 'Dezembro'
                               END)
        ,' ',DATE_FORMAT(data_horario, '%Y')) dia_data,

        case when date(data_horario)=curdate() then 'Hoje'
        when date(data_horario)=(curdate()+1) then 'Amanhã'  else
        (CASE WEEKDAY(data_horario)
                          when 0 then 'Segunda-feira'
                          when 1 then 'Terça-feira'
                          when 2 then 'Quarta-feira'
                          when 3 then 'Quinta-feira'
                          when 4 then 'Sexta-feira'
                          when 5 then 'Sábado'
                          when 6 then 'Domingo'
                          END)  END dia_semana
        ")
        ->whereRaw("situacao<>'livre'")->whereRaw("situacao<>'bloqueado'")
        ->whereRaw("cd_profissional=".$request->user()->cd_profissional)
        ->whereRaw("date(data_horario)>=curdate()")->orderByRaw("1")->get();
        $re=null;
        foreach($retorno as  $val){

          $atendimento=Agendamento::with(['paciente' => function($q) use($request){
            $q->selectRaw("paciente.*,
            TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) ano,
            FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30) mes,
           ( ( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) - ( FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30)*30) ) dia,

           case when TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) = 1 then '1 ano'
           when  TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) >1 then concat(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()),' anos')
           else '' end anoss,

           case when FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30) = 1 then ' 1 mês'
           when  FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30) >1 then concat(FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30),' meses')
           else '' end mess ,

           case when ( ( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) - ( FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30)*30) ) = 1 then ' 1 dia'
           when  ( ( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) - ( FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30)*30) ) > 1 then concat( ( ( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) - ( FLOOR(( TIMESTAMPDIFF(DAY,dt_nasc,curdate()) -(TIMESTAMPDIFF(YEAR,dt_nasc,curdate()) * 365) ) /30)*30) ),' dias')
           else '' end dias

           ");
          },'convenio' => function($q) use($request){
            $q->selectRaw('convenio.*');
          },'especialidade' => function($q) use($request){
            $q->selectRaw('especialidade.*');
          }])
        ->whereRaw("date(data_horario) = '".$val->data_agendamento."'")
          ->whereRaw("situacao<>'livre'")->whereRaw("situacao<>'bloqueado'")
          ->whereRaw("situacao<>'atendido'")->whereRaw("cd_profissional=".$request->user()->cd_profissional)
          ->orderBy("data_horario")->get();
          $val['atend']=$atendimento;
          $re[]=$val;
        }
        $dados['historico']=$historico;
        $dados['retorno']=$re;
        $dados['dsadas']=$request->user()->cd_profissional;
        return response()->json($dados);
      }else{
        $dados['historico']=null;
        $dados['retorno']=null;
        $dados['dsadas']=null;
        return response()->json($dados);
      }
    }

    public function jsonPanelConsultorio(Request $request, $data) {

      $data = $data.'-01';  
      list($newAno, $newMes, $newDia) = explode("-", $data);
      $request['dt'] = $newMes.'/'.$newAno;
      $primeiroDia = $data;
      $ultimoDia = date("Y-m-d", mktime(0, 0, 0, $newMes+1, 0, $newAno)); 
      $ultimo=date("d", mktime(0, 0, 0, $newMes+1, 0, $newAno));
      $request['dt_extenso'] = date("m/Y", mktime(0, 0, 0, $newMes+1, 0, $newAno)); 
      $Mes = date("Ym", mktime(0, 0, 0, $newMes+1, 0, $newAno)); 
      $diaAtual = date("Ymd"); 
      if($request->user()->cd_profissional){
        $Prof= Profissional::find($request->user()->cd_profissional);
        $request['profissional'] = $Prof->nm_profissional;
      }else{
        $request['profissional'] = '';
      }
  
      $header['atendimento']=0; $header['exame']=0; $header['pendente']=0; $header['laudado']=0;

      $atendimentosHeader= Agendamento::whereRaw("date(dt_atendimento) >= '$primeiroDia'")
      ->join('agendamento_situacao','agendamento_situacao.cd_situacao','agendamento.situacao')
      ->whereRaw("date(dt_atendimento) <= '$ultimoDia'")
      ->selectRaw("count(*) atendimentos,
      sum(case when  atender='S' then 1 else 0 end) final,
      sum(case when  cancelar='S' then 1 else 0 end) cancelado,
      sum(case when  confirmar='S' then 1 when  agendar='S' then 1 when  atendimento='S' then 1 when  em_atend='S' then 1 else 0 end) agendado ");
      if($request->user()->cd_profissional){
         $atendimentosHeader=$atendimentosHeader->whereRaw("cd_profissional=".$request->user()->cd_profissional);
      }
      $atendimentosHeader=$atendimentosHeader->get();

      foreach($atendimentosHeader as $key => $atendimento){
          $header['atendimento']=$atendimento->atendimentos; 
          $header['exame']=$atendimento->agendado; 
          $header['pendente']=$atendimento->cancelado; 
          $header['laudado']=$atendimento->final; 
      }


      $atendimentos= Agendamento::whereRaw("date(dt_atendimento) >= '$primeiroDia'")
      ->whereRaw("date(dt_atendimento) <= '$ultimoDia'")
      ->selectRaw(" date_format(dt_atendimento,'%d') dt_atendimento,count(*) qtde");
      if($request->user()->cd_profissional){
         $atendimentos=$atendimentos->whereRaw("cd_profissional=".$request->user()->cd_profissional);
      }
      $atendimentos=$atendimentos->groupByRaw("date_format(dt_atendimento,'%d')")
      ->get();  
      foreach($atendimentos as $key => $atendimento){
 
        $atend[$atendimento->dt_atendimento]['atend']=$atendimento->qtde;
        $atend[$atendimento->dt_atendimento]['dt_atend']=[$key , $atendimento->dt_atendimento];

      }
  
      for ($i = 1; $i <= $ultimo; $i++) {
        $Array[str_pad($i , 2 , '0' , STR_PAD_LEFT)]=0;
        $key=($i-1);
        if(isset($atend[str_pad($i , 2 , '0' , STR_PAD_LEFT)]['atend'])){ 
          $grafico['atend'][]=[$key , $atend[str_pad($i , 2 , '0' , STR_PAD_LEFT)]['atend']];
          $grafico['dt_atend'][]=[$key , str_pad($i , 2 , '0' , STR_PAD_LEFT)]; 
        }else{ 
          $grafico['atend'][]=[$key , 0];
          $grafico['dt_atend'][]=[$key , str_pad($i , 2 , '0' , STR_PAD_LEFT)]; 
        }  
      } 

      $atendimentos= Agendamento::whereRaw("date(dt_agenda) >= '$primeiroDia'") 
      ->join('paciente','paciente.cd_paciente','agendamento.cd_paciente')
      ->join('agendamento_situacao','agendamento_situacao.cd_situacao','agendamento.situacao')
      ->whereRaw("date(dt_agenda) <= '$ultimoDia'")
      ->whereRaw(" ( agendar='S' or confirmar='S' or atendimento='S') ") 
      ->selectRaw(" date_format(dt_agenda,'%d/%m/%y') dt_atendimento,agendamento_situacao.nm_situacao,nm_paciente,atendimento,confirmar");
      if($request->user()->cd_profissional){
         $atendimentos=$atendimentos->whereRaw("cd_profissional=".$request->user()->cd_profissional);
      }
      $atendimentos=$atendimentos->get();  
    
      $examePendente=[]; 
      $exameLaudado=[]; 
      foreach($atendimentos as $atend){
        if($atend->atendimento=='S'){
          $exameLaudado[]=array('paciente'=>$atend->nm_paciente,'data'=>$atend->dt_atendimento,'nm_situacao'=>$atend->nm_situacao);
        }else{ 
          if($atend->confirmar=='S'){$icone='<i class="fa fa-check"></i>';}else{$icone='<i class="fa fa-calendar"></i>';}
          $examePendente[]=array('paciente'=>$atend->nm_paciente,'data'=>$icone.' '.$atend->dt_atendimento,'nm_situacao'=>$atend->nm_situacao);
        }
      }

    
      $header['atendimento']= ($header['atendimento']) ? $header['atendimento'] : 0; 
      $header['exame']=($header['exame']) ? $header['exame'] : 0; 
      $header['pendente']=($header['pendente']) ? $header['pendente'] : 0; 
      $header['laudado']=($header['laudado']) ? $header['laudado'] : 0; 

      return response()->json(['grafico'=>$grafico,
      'header'=>$header,
      'examePendente'=>$examePendente,
      'exameLaudado'=>$exameLaudado,
      'request'=> $request->toArray()
     ]);

    }
 
    public function jsonPanel(Request $request, $data) {

        $data = $data.'-01'; 
        date_default_timezone_set('America/Sao_Paulo');
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');   
        $request['dt_extenso'] = utf8_encode(strftime('%B de %Y', strtotime($data))); 
        $request['dt_extenso'] = utf8_encode(strftime('%m de %Y', strtotime($data))); 
        list($newAno, $newMes, $newDia) = explode("-", $data);
        $request['dt'] = $newMes.'/'.$newAno;
        $primeiroDia = $data;
        $ultimoDia = date("Y-m-d", mktime(0, 0, 0, $newMes+1, 0, $newAno));
        $ultimo=date("d", mktime(0, 0, 0, $newMes+1, 0, $newAno));
        $header['atendimento']=0; $header['exame']=0; $header['pendente']=0; $header['laudado']=0;
        $grafico['atend']=null; $grafico['dt_atend']=null; $grafico['exame']=0;
        if($request->user()->cd_profissional){
          $Prof= Profissional::find($request->user()->cd_profissional);
          $request['profissional'] = $Prof->nm_profissional;
        }else{
          $request['profissional'] = '';
        }
          
        $atendimentos= Agendamento::whereRaw("date(dt_atendimento) >= '$primeiroDia'")
        ->whereRaw("date(dt_atendimento) <= '$ultimoDia'")
        ->selectRaw(" date_format(dt_atendimento,'%d') dt_atendimento,count(*) qtde");
        if($request->user()->cd_profissional){
          $atendimentos=$atendimentos->whereRaw("cd_profissional=".$request->user()->cd_profissional);
        }
        $atendimentos=$atendimentos->groupByRaw("date_format(dt_atendimento,'%d')")
        ->get();  
        foreach($atendimentos as $key => $atendimento){
  
          $atend[$atendimento->dt_atendimento]['atend']=$atendimento->qtde;
          $atend[$atendimento->dt_atendimento]['dt_atend']=[$key , $atendimento->dt_atendimento];

        }
    
        for ($i = 1; $i <= $ultimo; $i++) {
          $Array[str_pad($i , 2 , '0' , STR_PAD_LEFT)]=0;
          $key=($i-1);
          if(isset($atend[str_pad($i , 2 , '0' , STR_PAD_LEFT)]['atend'])){ 
            $grafico['atend'][]=[$key , $atend[str_pad($i , 2 , '0' , STR_PAD_LEFT)]['atend']];
            $grafico['dt_atend'][]=[$key , str_pad($i , 2 , '0' , STR_PAD_LEFT)]; 
          }else{ 
            $grafico['atend'][]=[$key , 0];
            $grafico['dt_atend'][]=[$key , str_pad($i , 2 , '0' , STR_PAD_LEFT)]; 
          }  
        } 


        if($request->user()->cd_profissional){
          $group="agendamento_itens.cd_exame,nm_exame";
        } else{
          $group="nm_profissional";
        }  
        /* Exame Laudados */ 
        $listaExame = Agendamento::whereRaw("date(dt_laudo) >= '$primeiroDia'")
        ->whereRaw("sn_laudo = 1")
        ->join('agendamento_itens','agendamento_itens.cd_agendamento','agendamento.cd_agendamento')
        ->join('exames','exames.cd_exame','agendamento_itens.cd_exame')
        ->join('profissional','profissional.cd_profissional','agendamento.cd_profissional')
        ->selectRaw( $group." nome, count(*) qtde")
        ->whereRaw("date(dt_laudo) <= '$ultimoDia'");
        if($request->user()->cd_profissional){
              $listaExame=$listaExame->whereRaw("profissional.cd_profissional=".$request->user()->cd_profissional);
              
        }
        $listaExame=$listaExame->groupByRaw($group)
        ->get();   
        $examePendente=null; $exameLaudado=null;
        foreach($listaExame as $lista){
            $exameLaudado[]=array('nome'=>$lista->nome,'qtde'=>$lista->qtde); 
            $header['laudado']=($header['laudado']+$lista->qtde); 
        }

        /* Exame  Pendentes */
        $listaExame = Agendamento::whereRaw("date(dt_atendimento) >= '$primeiroDia'")
        ->whereRaw("date(dt_atendimento) <= '$ultimoDia'")
        ->join('agendamento_itens','agendamento_itens.cd_agendamento','agendamento.cd_agendamento')
        ->join('exames','exames.cd_exame','agendamento_itens.cd_exame')
        ->join('profissional','profissional.cd_profissional','agendamento.cd_profissional')
        ->selectRaw( $group." nome, sum(case when sn_laudo = 0 then 1 else 0 end) qtde, sum(case when sn_laudo = 1 then 1 else 0 end) laudado");
        if($request->user()->cd_profissional){
              $listaExame=$listaExame->whereRaw("profissional.cd_profissional=".$request->user()->cd_profissional);
        }
        $listaExame=$listaExame->groupByRaw($group)
        ->get();   
        
        foreach($listaExame as $lista){
            $examePendente[]=array('nome'=>$lista->nome,'qtde'=>$lista->qtde,'laudado'=>$lista->laudado); 
        }
 
        $exames= Agendamento::whereRaw("date(dt_atendimento) >= '$primeiroDia'")
        ->whereRaw("date(dt_atendimento) <= '$ultimoDia'");
        if($request->user()->cd_profissional){
          $exames=$exames->whereRaw("cd_profissional=".$request->user()->cd_profissional);
        } 
        $exames=$exames->with('itens','profissional')->get(); 

        foreach($exames as $atendimentos){
           
          $header['atendimento']=($header['atendimento']+1);
          if(isset($atendimentos->itens)){
            foreach($atendimentos->itens as $idx => $exames){
              $header['exame']=($header['exame']+1);
              if($exames->sn_laudo==0){
                $header['pendente']=($header['pendente']+1); 
              }  
            }
          }  

        } 
        

        /* Exame  Pendentes Pizza */
        $pizza=null;
        $listaPizza = Agendamento::whereRaw("sn_laudo = 0")
        ->join('agendamento_itens','agendamento_itens.cd_agendamento','agendamento.cd_agendamento')
        ->join('exames','exames.cd_exame','agendamento_itens.cd_exame')
        ->join('profissional','profissional.cd_profissional','agendamento.cd_profissional')
        ->selectRaw("date_format(dt_atendimento,'%Y%m') data, date_format(dt_atendimento,'%m/%Y') dt, count(*) qtde");
        if($request->user()->cd_profissional){
              $listaPizza=$listaPizza->whereRaw("profissional.cd_profissional=".$request->user()->cd_profissional);
        }
        $listaPizza=$listaPizza->groupByRaw("date_format(dt_atendimento,'%Y%m'),date_format(dt_atendimento,'%m/%Y')") 
        ->orderByRaw("1")
        ->get();   
        $cores=array("#FF6347","#FF7F50","#FF8C00","#FFA500","#FFD700","#FF69B4","#F08080","#DA70D6","#EE82EE","#DEB887","#F4A460","#CD853F","#DAA520","#BDB76B" );
        foreach($listaPizza as $lista){ 

          $pizza[]= array('label'=>$lista->dt,'data'=>$lista->qtde,'color'=>$cores[rand(0,13)]);
        }
        $header['atendimento']= ($header['atendimento']) ? $header['atendimento'] : 0; 
        $header['exame']=($header['exame']) ? $header['exame'] : 0; 
        $header['pendente']=($header['pendente']) ? $header['pendente'] : 0; 
        $header['laudado']=($header['laudado']) ? $header['laudado'] : 0; 
 
        return response()->json(['grafico'=>$grafico,
                                 'header'=>$header,
                                 'examePendente'=>$examePendente,
                                 'exameLaudado'=>$exameLaudado,
                                 'request'=> $request->toArray(),
                                 'pizza' => $pizza
                                ]);

      
    }
 
    public function xls_laudo(Request $request, $data,$tipo) {
 
      $data = $data.'-01';   
      list($newAno, $newMes, $newDia) = explode("-", $data); 
      $primeiroDia = $data;
      $ultimoDia = date("Y-m-d", mktime(0, 0, 0, $newMes+1, 0, $newAno));

      if($tipo==1){

        $query = Agendamento::whereRaw("date(dt_laudo) >= '$primeiroDia'")
        ->whereRaw("sn_laudo = 1")
        ->join('agendamento_itens','agendamento_itens.cd_agendamento','agendamento.cd_agendamento')
        ->join('exames','exames.cd_exame','agendamento_itens.cd_exame')
        ->join('profissional','profissional.cd_profissional','agendamento.cd_profissional')
        ->selectRaw("agendamento.*, agendamento_itens.cd_exame,nm_exame,date_format(dt_atendimento,'%d/%m/%Y') dt_atend,
        date_format(dt_laudo,'%d/%m/%Y') data_laudo,agendamento_itens.cd_agendamento_item")
        ->whereRaw("date(dt_laudo) <= '$ultimoDia'");
        if($request->user()->cd_profissional){
              $query=$query->whereRaw("profissional.cd_profissional=".$request->user()->cd_profissional);
        }
        $query=$query->get();  

      }

      if($tipo==0){
        $query = Agendamento::whereRaw("date(dt_atendimento) >= '$primeiroDia'")
        ->whereRaw("date(dt_atendimento) <= '$ultimoDia'")
        ->join('agendamento_itens','agendamento_itens.cd_agendamento','agendamento.cd_agendamento')
        ->join('exames','exames.cd_exame','agendamento_itens.cd_exame')
        ->join('profissional','profissional.cd_profissional','agendamento.cd_profissional')
        ->selectRaw("agendamento.*, agendamento_itens.cd_exame,nm_exame,date_format(dt_atendimento,'%d/%m/%Y') dt_atend,
        date_format(dt_laudo,'%d/%m/%Y') data_laudo,agendamento_itens.cd_agendamento_item");
        if($request->user()->cd_profissional){
              $query=$query->whereRaw("profissional.cd_profissional=".$request->user()->cd_profissional);
        }
        $query=$query->get();   
      }
 
 
  
      return view('rpclinica.inicial.xls_laudo',compact('query','request'));
     
   

    }

}

