<?php


namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\rpclinica\AgendamentoDocumento;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoDocumentos;
use App\Model\rpclinica\ComunicacaoSend;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\Usuario;
use App\Model\rpclinica\WebhookMessage;
use App\Model\rpclinica\WhastApi;
use App\Model\rpclinica\WhastReceive;
use App\Model\rpclinica\WhastRetornoAgenda;
use App\Model\rpclinica\WhastSend;
use App\Model\rpclinica\WhastSituacao;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Bibliotecas\SimpleXLSX;
use App\Bibliotecas\SimpleXLS;

class Amigo extends Controller
{


    public function import(){



        dd("Parado");

        /*
        $path = 'C:\amigo_bkp_final\records_recipes.xlsx';
        if ($xlsAndamento = SimpleXLSX::parse($path)) {
            foreach ($xlsAndamento->rows() as $key => $Linha) {

                echo "<br>";
                echo "<br>";
                $array =array(
                    'id' => $Linha[0],
                    'name' => $Linha[1],
                    'dose' => $Linha[2],
                    'special' => $Linha[3],
                    'quantidade' => $Linha[4],
                    'posologia' => $Linha[5],
                    'observation' => $Linha[6],
                    'type' => $Linha[7],
                    'source' => $Linha[8],
                    'via' => $Linha[9],
                    'continuous_use' => $Linha[10],
                    'created_at' => $Linha[11],
                    'updated_at' => $Linha[12],
                    'deleted_at' => $Linha[13],
                    'record_id' => $Linha[14],
                    'medicament_id' => $Linha[15],
                    'company_medicament_id' => $Linha[16],
                    'company_id' => $Linha[17],
                );
                print_r($array);
                DB::table('clinicabkp.records_recipes')->insert($array);
            }
        }

        $path = 'C:\amigo_bkp_final\records.xlsx';
        if ($xlsAndamento = SimpleXLSX::parse($path)) {
            foreach ($xlsAndamento->rows() as $key => $Linha) {

                echo "<br>";
                echo "<br>";

                $array =array(
                    'id' => $Linha[0],
                    'title' => $Linha[1],
                    'text' => $Linha[2],
                    'type' => $Linha[3],
                    'subtype' => $Linha[4],
                    'pinned' => $Linha[5],
                    'extra' => $Linha[6],
                    'is_table' => $Linha[7],
                    'table_orientation' => $Linha[8],
                    'has_digital_signature' => $Linha[9],
                    'is_comment' => $Linha[10],
                    'is_public' => $Linha[11],
                    'created_at' => $Linha[12],
                    'updated_at' => $Linha[13],
                    'deleted_at' => $Linha[14],
                    'patient_id' => $Linha[15],
                    'user_id' => $Linha[16],
                    'destroyer_id' => $Linha[17],
                    'config_id' => $Linha[18],
                    'company_id' => $Linha[19],
                    'attendance_id' => $Linha[20],
                    'company_address_id' => $Linha[21],
                );
                print_r($array);
                DB::table('clinicabkp.records')->insert($array);
                echo "<BR><BR><BR>FIM<BR><BR>";
            }
        }
         */
    }

    public function atendimento(Request $request)
    {

        $Query=DB::table('clinicabkp.records')->get();

    }

    public function paciente(Request $request)
    {
        dd("Parado");
        set_time_limit(50000);
        echo "<pre>";
        $Query=DB::table('clinicabkp.patients')->get();
        foreach($Query as $valor){

            $NonePac = Paciente::find($valor->id);

            if(!isset($NonePac->cd_paciente)){

                $Sexo='H';
                $Sexo=null;
                if($valor->gender=='Masculino'){ $Sexo='H';}
                if($valor->gender=='Feminino'){ $Sexo='M';}
                $Uf=null;
                if($valor->address_state=='Minas Gerais'){ $Uf='MG';}

                $EstadoCivil=null;
                if($valor->civil_status=='Casado(a)'){ $EstadoCivil='C'; }
                if($valor->civil_status=='Solteiro(a)'){ $EstadoCivil='C'; }
                if($valor->is_vip=='TRUE'){ $Insert['vip']='S'; }
                $Insert['cd_paciente']=$valor->id;
                $Insert['nm_paciente']=mb_strtoupper($valor->name);
                $Insert['cd_categoria']=(empty($valor->insurance_id)) ? null : $valor->insurance_id;
                $Insert['cartao']=$valor->insurance_number;
                $Insert['dt_nasc']= (empty($valor->born)) ? null : $valor->born;
                $Insert['sexo']=$Sexo;
                $Insert['estado_civil']=$EstadoCivil;
                $Insert['rg']=$valor->rg;
                $Insert['cpf']=$valor->cpf;

                $Insert['nm_mae']=$valor->mother_name;
                $Insert['nm_pai']=$valor->father_name;
                $Insert['logradouro']=$valor->address_address;
                $Insert['numero']=$valor->address_number;
                $Insert['complemento']=$valor->address_complement;
                $Insert['nm_bairro']=$valor->address_district;
                $Insert['cidade']=$valor->address_city;
                $Insert['uf']=$Uf;
                $Insert['cep']=$valor->address_cep;
                $Insert['fone']=$valor->contact_phone_home;
                $Insert['celular']=$valor->contact_cellphone;
                $Insert['email']=$valor->email;
                $Insert['sn_ativo']='S';
                $Insert['sn_legado']='S';
                $Insert['cd_usuario']='ADMIN';
                $Insert['created_at']=date('Y-m-d H:i');
                $Insert['updated_at']=date('Y-m-d H:i');
                print_r( $Insert);
                Paciente::insert($Insert);
                unset($Insert);

            }
        }

    }

    public function prof(Request $request)
    {

        dd("Parado");
        set_time_limit(5000);
        echo "<pre>";
        $Query=DB::select("
            select records.title, extra conteudo,records.created_at,records.patient_id,records.user_id,users.name,users.id
            from clinicabkp.records
            left join clinicabkp.records_recipes on records_recipes.record_id=records.id
            inner join clinicabkp.users on users.user_id=records.user_id
            where records.type='CUSTOM' and records.subtype='ANAMNESE' ");
        foreach($Query as $valor){

        }

    }

    public function atend(Request $request)
    {

        dd("Parado");

        set_time_limit(5000);
        echo "<pre>";
        $Query=DB::select("
            select records.title, extra conteudo,records.created_at,records.patient_id,
            records.user_id,users.name,users.id,DATE_FORMAT(records.created_at, '%Y-%m-%d') dt_agenda
            ,DATE_FORMAT(records.created_at, '%H:%i') hr_agenda,WEEKDAY(records.created_at)+1 dia_semana
            from clinicabkp.records
            left join clinicabkp.records_recipes on records_recipes.record_id=records.id
            inner join clinicabkp.users on users.user_id=records.user_id
            where records.type='CUSTOM' and records.subtype='ANAMNESE' ");

        foreach($Query as $key => $valor){

            $CodAgendamento = ($key+1000);
            $Pac = Paciente::whereRaw("cd_paciente=".$valor->patient_id)->first();
            if(isset($Pac->cd_paciente)){
                echo "<br>";
                echo $key;
                echo "<br>";
                $Agendamento['cd_agendamento']=$CodAgendamento;
                $Agendamento['dt_agenda']=$valor->dt_agenda;
                $Agendamento['hr_agenda']=$valor->hr_agenda;
                $Agendamento['dia_semana']=$valor->dia_semana;
                $Agendamento['cd_paciente']=$valor->patient_id;
                $Agendamento['situacao']='atendimento';
                $Agendamento['data_horario']=$valor->created_at;
                $Agendamento['created_at']=$valor->created_at;
                $Agendamento['cd_profissional']=$valor->id;
                $Agendamento['usuario_finalizacao']=$valor->user_id;
                $Agendamento['sn_finalizado']='S';
                $Agendamento['dt_finalizacao']=$valor->created_at;
                $Agendamento['sn_legado']='S';
                print_r($Agendamento);

                Agendamento::insert($Agendamento);

            }

        }
    }

    public function anamnese(Request $request)
    {
        dd("Parado");
        set_time_limit(5000);
        echo "<pre>";
        $Query=DB::select("
            select records.title, extra conteudo,records.created_at,records.patient_id,records.user_id,users.name,users.id
            from clinicabkp.records
            left join clinicabkp.records_recipes on records_recipes.record_id=records.id
            inner join clinicabkp.users on users.user_id=records.user_id
            where records.type='CUSTOM' and records.subtype='ANAMNESE' ");
        foreach($Query as $valor){
            $CodAgendamento = 111;
            $Pac = Paciente::whereRaw("cd_paciente=".$valor->patient_id)->first();
            if(isset($Pac->cd_paciente)){

                $Agendamento['cd_agendamento']=$CodAgendamento;
                $Agendamento['dt_agenda']='';
                $Agendamento['hr_agenda']='';
                $Agendamento['dia_semana']='';
                $Agendamento['cd_paciente']='';
                $Agendamento['situacao']='atendimento';
                $Agendamento['data_horario']='';
                $Agendamento['created_at']='';
                $Agendamento['cd_profissional']='';

                $Agendamento['usuario_finalizacao']='';
                $Agendamento['sn_finalizado']='S';
                $Agendamento['usuario_triagem']='';



                $Documento=null; $Formulario = null;
                foreach (json_decode($valor->conteudo) as $key => $value) {

                    $Pergunta=DB::table('clinicabkp.records_config_fields')->whereRaw("id = ".$key)
                    ->first();

                    $Tipos=array('IMC','MULTIPLE','MULTIPLE_CID');
                    if (in_array($Pergunta->type, $Tipos)){
                        $Valores="";
                        foreach($value as $ValorPergunta){

                            if($Pergunta->type=='MULTIPLE_CID'){
                                    foreach ($ValorPergunta as $key => $xx) {
                                        $Valores=$Valores." ".$xx;
                                    }
                            }else{
                                $Valores=$Valores." ".$ValorPergunta;
                            }

                        }
                        $value=$Valores;
                    }
                $Documento=$Documento."<br><br>".$Pergunta->title."<br>".$value;
                }

                $dados['cd_prof']=$valor->id;
                $dados['cd_pac']=$valor->patient_id;
                $dados['conteudo']=$Documento;
                $dados['cd_formulario']=73;
                $dados['nm_formulario']='Anamnese [ AMIGO ]';
                $dados['created_at']=$valor->created_at;
                $dados['cd_usuario']=$valor->user_id;
                AgendamentoDocumentos::create($dados);


            }else{
                echo '<br><table width="100%" border="1">
                <tr  width="100%" >
                    <td> <p align="center"><strong>PACIENTE N&Atilde;O ENCONTRADO</strong></p>
                      <p>Paciente: '.$valor->patient_id.'</p>
                      <p>Nome: NOME DO PACIENTE</p></td>
                </tr>
                </table>';
            }
        }

    }

    public function obs(Request $request)
    {
        dd("Parado");
        set_time_limit(5000);
        echo "<pre>";
        $Query=DB::select("
        select records.title, text conteudo,records.created_at,records.patient_id,records.user_id,users.name,users.id
        from clinicabkp.records
        left join clinicabkp.records_recipes on records_recipes.record_id=records.id
        inner join clinicabkp.users on users.user_id=records.user_id
        where records.type='TEXT'");
        foreach($Query as $valor){

            $Pac = Paciente::whereRaw("cd_paciente=".$valor->patient_id)->first();
            if(isset($Pac->cd_paciente)){

                $dados['cd_prof']=$valor->id;
                $dados['cd_pac']=$valor->patient_id;
                $dados['cd_usuario']=$valor->user_id;
                $dados['conteudo']=$valor->conteudo;
                $dados['cd_formulario']=66;
                $dados['nm_formulario']='Observações [ AMIGO ]';
                $dados['created_at']=$valor->created_at;
                print_r($dados);
                AgendamentoDocumentos::create($dados);

            }
        }

    }

    public function receita(Request $request)
    {
        dd("Parado");
        set_time_limit(50000);
        echo "<pre>";
        $Query=DB::select("
        select records.id id_record, records.created_at,records.patient_id,records.user_id,users.name,users.id
        from  clinicabkp.records
        inner join clinicabkp.users on users.user_id=records.user_id
        where records.type='MEDICINE' ");
        foreach($Query as $valor){

            $Pac = Paciente::whereRaw("cd_paciente=".$valor->patient_id)->first();
            if(isset($Pac->cd_paciente)){

                $Query2=DB::select(" select * from clinicabkp.records_recipes where record_id=".$valor->id_record);
                $CONTEUDO="";
                foreach($Query2 as $key => $rem){
                    if($rem->type=='MEDICINE'){
                        $CONTEUDO = $CONTEUDO."<b>".$rem->name."</b>, ".$rem->dose."<br><br>";
                        $CONTEUDO = $CONTEUDO.$rem->posologia."<br>";
                        $CONTEUDO = $CONTEUDO."Qtd: ".$rem->quantidade."<br><br><hr style='border-top: 1px solid #e5e6e7;'> ";
                    }
                    if($rem->type=='TEXT'){
                        $CONTEUDO = $CONTEUDO.$rem->observation;
                    }
                }

                $dados['cd_prof']=$valor->id;
                $dados['cd_pac']=$valor->patient_id;
                $dados['cd_usuario']=$valor->user_id;
                $dados['conteudo']=$CONTEUDO;
                $dados['cd_formulario']=67;
                $dados['nm_formulario']='Receituário digital [ AMIGO ]';
                $dados['created_at']=$valor->created_at;
                AgendamentoDocumentos::create($dados);


            }

        }

    }


    public function outros(Request $request)
    {
        dd("Parado");
        set_time_limit(50000);
        echo "<pre>";
        $Query=DB::select("
        select records.title, text conteudo,records.created_at,records.patient_id,records.user_id,users.name,users.id,records.type
        from  clinicabkp.records
        left join  clinicabkp.records_recipes on records_recipes.record_id=records.id
        inner join clinicabkp.users on users.user_id=records.user_id
        where records.type IN ('EXAM_REQUEST','MEDICAL_REPORT','CERTIFICATE') ");

        foreach($Query as $valor){

            $Pac = Paciente::whereRaw("cd_paciente=".$valor->patient_id)->first();
            if(isset($Pac->cd_paciente)){

                if($valor->type=='EXAM_REQUEST'){ $CodForm='68'; $DsForm='Solicitação de exames  [ AMIGO ]'; }
                if($valor->type=='MEDICAL_REPORT'){ $CodForm='72'; $DsForm='Laudo Médico  [ AMIGO ]'; }
                if($valor->type=='CERTIFICATE'){ $CodForm='71'; $DsForm='Atestado, Declaração e Outros  [ AMIGO ]'; }

                if($valor->title){ $Title="<h3>".$valor->title."</h3>"; }else{ $Title="";  }

                $dados['cd_prof']=$valor->id;
                $dados['cd_pac']=$valor->patient_id;
                $dados['cd_usuario']=$valor->user_id;
                $dados['conteudo']=$Title.$valor->conteudo;
                $dados['cd_formulario']= $CodForm;
                $dados['nm_formulario']=$DsForm;
                $dados['created_at']=$valor->created_at;
                AgendamentoDocumentos::create($dados);

            }
        }
    }



}
