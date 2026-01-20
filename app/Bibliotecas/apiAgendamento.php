<?php

namespace App\Bibliotecas;

use App\Model\rpclinica\Agenda;
use App\Model\rpclinica\AgendaEscala;
use App\Model\rpclinica\AgendamentoSituacao;
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Feriado;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Facades\Auth;


class apiAgendamento
{

   public function principal($request)
   {

      try {

         $di = $request['di'];
         $df = $request['df'];

         $situacaoLivre = AgendamentoSituacao::where('livre', 'S')->first();
         $IconeLivre = $situacaoLivre->icone;
         $ArrayDias = [];
         $listaAgendamentoManual = [];
         $listaAgendamento = [];
         $listaAgendamentoCorrecao = [];
         $arrayFeriados = [];
         $retorno = [];
         $Escala = [];
         $EscalaDiaria = [];
         $Numero = [];
         $DiasEscalaManual = null;

         if (empty($request['agendas'])) {
            $request['agendas'] = 0;
            $Agendas = array(0);
         } else {
            $Agendas = explode(',', $request['agendas']);
         }

         $request['start'] = date('Y-m-d', $request['start']);
         $request['end'] = date('Y-m-d', $request['end']);

         $request['agendas'] = $Agendas;
 
         if ($di <= $df) {
            $dt = date('Y-m-d', $di); 
            $CountEscalaDiaria = $this->qtdeEscalaDiaria($request['agendas'], $dt);

            $ArrayDias[] = [
               'data' => date('Y-m-d', $di),
               'dia' => date('w', strtotime($dt)),
               'diario' => (($CountEscalaDiaria > 0) ? 'S' : 'N')
            ];

            $DiasEscalaManual[] = ['data' => date('Y-m-d', $di), 'dia' => date('w', strtotime($dt))];
 
            while ($di <  $df) {
               $di = strtotime('@' . $di . '+ 1 day');
               $dt = date('Y-m-d', $di);

               $CountEscalaDiaria = $this->qtdeEscalaDiaria($request['agendas'], $dt);

               $ArrayDias[] = ['data' => date('Y-m-d', $di), 'dia' => date('w', strtotime($dt)), 'diario' => (($CountEscalaDiaria > 0) ? 'S' : 'N')];
               $DiasEscalaManual[] = ['data' => date('Y-m-d', $di), 'dia' => date('w', strtotime($dt))];
               $Numero[] =  date('w', strtotime($dt));
            }
         }
  
         /* Gerar Agendamentos do periodo */
         $Agendamentos = RpclinicaAgendamento::where('dt_agenda', ">=", $request['start'])->where('dt_agenda', "<=", $request['end'])
            ->with('local', 'convenio', 'profissional', 'especialidade', 'paciente', 'tipo_atend', 'agenda', 'tab_situacao')
            ->whereIn('cd_agenda', $request['agendas'])->get();
         $seg=0;   
         foreach ($Agendamentos as $Agendamento) {
            if(empty($Agendamento->cd_agenda_escala_horario)){
               $seg=($seg+1);   
            }
            if ($Agendamento->cd_agenda_escala_horario) {
               $listaAgendamento[$Agendamento->dt_agenda][$Agendamento->cd_agenda_escala_horario][] = $Agendamento->toArray();
            } else {
               $listaAgendamentoManual[$Agendamento->dt_agenda][] = $Agendamento->toArray();
            }

            $listaAgendamentoCorrecao[$Agendamento->dt_agenda][($Agendamento->cd_agenda_escala_horario) ? $Agendamento->cd_agenda_escala_horario : ($Agendamento->cd_agendamento)][] = $Agendamento->toArray();
         }

        // dd( $listaAgendamentoCorrecao,$listaAgendamento);
          
         /* Gerar Escala de Feriados */
         $feriados = Feriado::where('dt_feriado', ">=", $request['start'])->where('dt_feriado', "<=", $request['end'])
            ->where('sn_bloqueado', 'S')->get();
         $empresa = Empresa::find($request->user()->cd_empresa);
         foreach ($feriados as $val) {
            $arrayFeriados[$val->dt_feriado] = $val->dt_feriado;
            foreach ($request['agendas'] as $ag) {
               $retorno[]  = array(
                  "tp" => 1,
                  "cd_agendamento" => null,
                  "Tipo" => 'BLO',
                  "cd_horario" => null,
                  "description" => null,
                  "encaixe" => null,
                  "especialidade" => null,
                  "local" =>  null,
                  "convenio" => null,
                  "id" => null,
                  "start" => $val->dt_feriado . ' ' . (($empresa->hr_inicial) ? substr($empresa->hr_inicial, 0, 5) : '08:00'),
                  "ds_start" => (($empresa->hr_inicial) ? substr($empresa->hr_inicial, 0, 5) : '08:00'),
                  "nm_profissional" => null,
                  "end" => $val->dt_feriado . ' ' . (($empresa->hr_final) ? substr($empresa->hr_final, 0, 5) : '18:00'),
                  "ds_end" => (($empresa->hr_final) ? substr($empresa->hr_final, 0, 5) : '08:00'),
                  "resourceId" => $ag,
                  "resource" => $ag,
                  "nm_resource" => ucfirst($val->nm_feriado),
                  "escalaId" => $val->cd_feriado,
                  "escalaIntervalo" => null,
                  "escalaNmIntervalo" => null,
                  "title" => null,
                  "cd_dia" => null,
                  "titulo" => 'Bloqueado - ' . ucfirst(strtolower($val->nm_feriado)),
                  "className" => 'event-livre',
                  "icone" => null,
                  "nm_paciente" => null,
                  "dt_nasc" => null,
                  "situacao" =>  'bloqueado',
                  "usuario_geracao" => "",
                  "rendering" => null,
                  "backgroundColor" => null,
                  "icone_livre" => $IconeLivre,
                  "obs" => "",
                  "tipo_dados"=> "feriados",
                  "tipo_atend"=> ""
               );
            }
         }

         /* Gerar Escala Normal */
         $query = AgendaEscala::whereIn('cd_agenda', $request['agendas'])
            ->with('agenda.profissional', 'agenda.especialidade', 'escala_horarios')
            ->whereIn('nr_dia', $Numero)
            ->where('sn_ativo', 'S')
            ->whereNull('escala_manual')->get();
         foreach ($query as $keyLinha => $linha) {
            foreach ($linha->escala_horarios as $keyHorarios => $horario) {
               $horaNova = strtotime("$horario->cd_horario + " . $linha->intervalo . " minutes");
               $horaNovaFormatada = date("H:i", $horaNova);

               $Escala[$linha->nr_dia][$linha->cd_agenda][$horario->cd_agenda_escala_horario]  = array(
                  "tp" => 1,
                  "cd_agendamento" => null,
                  "Tipo" => 'LI',
                  "cd_horario" => $horario->cd_agenda_escala_horario,
                  "description" => $linha->agenda->nm_agenda,
                  "agenda_aberta" => $linha->agenda->sn_agenda_aberta,
                  "encaixe" => null,
                  "especialidade" => null,
                  "local" =>  null,
                  "convenio" => null,
                  "id" => null,
                  "start" => $horario->cd_horario,
                  "nm_profissional" => null,
                  "end" => trim($horaNovaFormatada),
                  "resourceId" => $linha->cd_agenda,
                  "resource" => $linha->cd_agenda,
                  "nm_resource" => $linha->agenda->nm_agenda,
                  "escalaId" => $linha->cd_escala_agenda,
                  "escalaIntervalo" => $linha->intervalo,
                  "escalaNmIntervalo" => $linha->nm_intervalo,
                  "title" => null,
                  "cd_dia" => $linha->nr_dia,
                  "titulo" => 'Livre',
                  "className" => 'event-livre',
                  "icone" => null,
                  "nm_paciente" => null,
                  "dt_nasc" => null,
                  "situacao" =>  'livre',
                  "usuario_geracao" => "",
                  "rendering" => null,
                  "backgroundColor" => null,
                  "icone_livre" => $IconeLivre,
                  "obs" => ""
               );
            }
         }
 

         /* Gerar Escala Diaria */
         $query = AgendaEscala::whereIn('cd_agenda', $request['agendas'])
            ->with('agenda.profissional', 'agenda.especialidade', 'escala_horarios')
            ->where('dt_inicial', '>=', $request['start'])
            ->where('dt_inicial', '<=', $request['end'])
            ->where('sn_ativo', 'S')
            ->where('escala_manual', 'S')
            ->where('escala_diaria', 'S')->get();
         foreach ($query as $keyLinha => $linha) {
            foreach ($linha->escala_horarios as $keyHorarios => $horario) {
               $horaNova = strtotime("$horario->cd_horario + " . $linha->intervalo . " minutes");
               $horaNovaFormatada = date("H:i", $horaNova);

               $EscalaDiaria[$linha->dt_inicial][$linha->cd_agenda][$horario->cd_agenda_escala_horario]  = array(
                  "tp" => 1,
                  "cd_agendamento" => null,
                  "Tipo" => 'LI',
                  "cd_horario" => $horario->cd_agenda_escala_horario,
                  "description" => $linha->agenda->nm_agenda,
                  "agenda_aberta" => $linha->agenda->sn_agenda_aberta,
                  "encaixe" => null,
                  "especialidade" => null,
                  "local" =>  null,
                  "convenio" => null,
                  "id" => null,
                  "start" => $horario->cd_horario,
                  "nm_profissional" => null,
                  "end" => trim($horaNovaFormatada),
                  "resourceId" => $linha->cd_agenda,
                  "resource" => $linha->cd_agenda,
                  "nm_resource" => $linha->agenda->nm_agenda,
                  "escalaId" => $linha->cd_escala_agenda,
                  "escalaIntervalo" => $linha->intervalo,
                  "escalaNmIntervalo" => $linha->nm_intervalo,
                  "title" => null,
                  "cd_dia" => $linha->nr_dia,
                  "titulo" => 'Livre',
                  "className" => 'event-livre',
                  "icone" => null,
                  "nm_paciente" => null,
                  "dt_nasc" => null,
                  "situacao" =>  'livre',
                  "usuario_geracao" => "",
                  "rendering" => null,
                  "backgroundColor" => null,
                  "icone_livre" => $IconeLivre,
                  "obs" => ""
               );
            }
         }


         /* Gerar Escala FINAL */
         foreach ($ArrayDias as $keyDIA => $DIA) {
            $NR_DIA = $DIA['dia'];
            $DATA = $DIA['data'];
            $SN_DIARIO = $DIA['diario'];
            $Visualizar = 'N';

            if ($DATA >= date('Y-m-d')) {
               $Visualizar = 'S';
            }
            if (isset($arrayFeriados[$DATA])) {
               $Visualizar = 'N';
            }

            //Escala Manual
            $escalaManual = AgendaEscala::whereIn('cd_agenda', $request['agendas'])
               ->with('agenda.profissional', 'agenda.especialidade', 'escala_horarios')
               ->where('escala_manual', 'S')
               ->whereNull('escala_diaria')
               ->where('sn_ativo', 'S')
               ->where('dt_inicial', '>=', $DATA)
               ->where('dt_inicial', '<=', $DATA)->get();
            foreach ($escalaManual as $Ag => $age) {
               if (isset($age->escala_horarios)) {
                  foreach ($age->escala_horarios as $Ho => $Time) {
                     $horaNova = strtotime("$Time->cd_horario + " . $age->intervalo . " minutes");
                     $horaNovaFormatada = date("H:i", $horaNova);
                     $cd_horario = $Time->cd_agenda_escala_horario;
                     $Horario = $Time->cd_horario;
                     $agendamento =  (isset($listaAgendamento[$DATA][$cd_horario][0])) ? $listaAgendamento[$DATA][$cd_horario][0] : null;
                     unset($listaAgendamentoCorrecao[$DATA][$cd_horario]);
                     // echo (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'].'<br>' : '';
                     
                     if (isset($agendamento['hr_agenda'])) {
                        if ($agendamento['hr_agenda']) {
                           $Horario = $agendamento['hr_agenda'];
                        }
                     }
                     if (isset($agendamento['hr_final'])) {
                        if ($agendamento['hr_final']) {
                           $horaNovaFormatada = $agendamento['hr_final'];
                        }
                     }
                     if ($Visualizar == 'S') {
                        $retorno[]  = array(
                           "tp" => 1,
                           "cd_agendamento" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,
                           "Tipo" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                           "cd_horario" => $cd_horario,
                           "description" => $Time->agenda->nm_agenda,
                           "agenda_aberta" => $age->agenda->sn_agenda_aberta,
                           "encaixe" => null,
                           "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : null,
                           "local" => (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : null,
                           "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : null,
                           "id" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,
                           "start" => trim($DATA) . ' ' . trim($Horario),
                           "ds_start" =>  trim($Horario),
                           "nm_profissional" => (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : null,
                           "end" => trim($DATA) . ' ' . trim($horaNovaFormatada),
                           "ds_end" => trim($horaNovaFormatada),
                           "resourceId" => $age->agenda->cd_agenda,
                           "resource" => $age->agenda->cd_agenda,
                           "nm_resource" => $age->agenda->nm_agenda,
                           "escalaId" => $Time->cd_escala_agenda,
                           "escalaIntervalo" => $age->intervalo,
                           "escalaNmIntervalo" => $age->nm_intervalo,
                           "title" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                           "cd_dia" => $age->nr_dia,
                           "titulo" => (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'Livre',
                           "className" => (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                           "icone" => (isset($agendamento['tab_situacao']['icone_classe'])) ? $agendamento['tab_situacao']['icone_classe'] : null,
                           "nm_paciente" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                           "dt_nasc" => (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : null,
                           "situacao" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                           "usuario_geracao" => "",
                           "rendering" => null,
                           "backgroundColor" => null,
                           "icone_livre" => $IconeLivre,
                           "obs" => "",
                           "tipo_dados"=> "Escala_Manual",
                           "tipo_atend"=> (isset($agendamento['tipo_atend']['nm_tipo_atendimento'])) ? ($agendamento['tipo_atend']['nm_tipo_atendimento']) : ''
                        );
                     } else {
                        if ($agendamento) {

                           $retorno[]  = array(
                              "tp" => 1,
                              "cd_agendamento" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,
                              "Tipo" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                              "cd_horario" => $cd_horario,
                              "description" => $Time->agenda->nm_agenda,
                              "agenda_aberta" => $age->agenda->sn_agenda_aberta,
                              "encaixe" => null,
                              "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : null,
                              "local" => (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : null,
                              "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : null,
                              "id" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,
                              "start" => trim($DATA) . ' ' . trim($Horario),
                              "ds_start" =>  trim($Horario),
                              "nm_profissional" => (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : null,
                              "end" => trim($DATA) . ' ' . trim($horaNovaFormatada),
                              "ds_end" => trim($horaNovaFormatada),
                              "resourceId" => $age->agenda->cd_agenda,
                              "resource" => $age->agenda->cd_agenda,
                              "nm_resource" => $age->agenda->nm_agenda,
                              "escalaId" => $Time->cd_escala_agenda,
                              "escalaIntervalo" => $age->intervalo,
                              "escalaNmIntervalo" => $age->nm_intervalo,
                              "title" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                              "cd_dia" => $age->nr_dia,
                              "titulo" => (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                              "className" => (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                              "icone" => (isset($agendamento['tab_situacao']['icone_classe'])) ? $agendamento['tab_situacao']['icone_classe'] : null,
                              "nm_paciente" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                              "dt_nasc" => (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : null,
                              "situacao" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                              "usuario_geracao" => "",
                              "rendering" => null,
                              "backgroundColor" => null,
                              "icone_livre" => $IconeLivre,
                              "obs" => "",
                              "tipo_dados"=> "Escala_Manual2",
                              "tipo_atend"=> (isset($agendamento['tipo_atend']['nm_tipo_atendimento'])) ? ($agendamento['tipo_atend']['nm_tipo_atendimento']) : ''
                           );
                        }
                     }
                  }
               }
            }

            //Escala Normal 
            if (isset($Escala[$NR_DIA])) {

               foreach ($Escala[$NR_DIA] as $cd_agenda => $agenda) {

                  //Verifica se tem escala diaria 
                  if (isset($EscalaDiaria[$DATA][$cd_agenda])) {
                     $agenda = $EscalaDiaria[$DATA][$cd_agenda];
                  }


                  foreach ($agenda as $cd_horario => $horario) {

                     $Horarios[] = $cd_horario;
                     $agendamento =  (isset($listaAgendamento[$DATA][$cd_horario][0])) ? $listaAgendamento[$DATA][$cd_horario][0] : null;
                     unset($listaAgendamentoCorrecao[$DATA][$cd_horario]);

                     if (isset($agendamento['hr_agenda'])) {
                        if ($agendamento['hr_agenda']) {
                           $horario['start'] = $agendamento['hr_agenda'];
                        }
                     }

                     if (isset($agendamento['hr_final'])) {
                        if ($agendamento['hr_final']) {
                           $horario['end'] = $agendamento['hr_final'];
                        }
                     }

                     if ($Visualizar == 'S') {

                        $retorno[]  = array(
                           "tp" => 1,
                           "cd_agendamento" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,
                           "Tipo" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                           "cd_horario" => $horario['cd_horario'],
                           "description" => ($horario['description']) ? $horario['description'] : '',
                           "agenda_aberta" => $horario['agenda_aberta'],
                           "encaixe" => null,
                           "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : null,
                           "local" => (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : null,
                           "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : null,
                           "id" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,
                           "start" => trim($DATA) . ' ' . trim($horario['start']),
                           "ds_start" =>  trim($horario['start']),
                           "nm_profissional" => (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : null,
                           "end" => trim($DATA) . ' ' . trim($horario['end']),
                           "ds_end" => trim($horario['end']),
                           "resourceId" => $horario['resourceId'],
                           "resource" => $horario['resource'],
                           "nm_resource" => $horario['nm_resource'],
                           "escalaId" => $horario['escalaId'],
                           "escalaIntervalo" => $horario['escalaIntervalo'],
                           "escalaNmIntervalo" => $horario['escalaNmIntervalo'],
                           "title" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                           "cd_dia" => $horario['cd_dia'],
                           "titulo" => (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                           "className" => (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                           "icone" => (isset($agendamento['tab_situacao']['icone_classe'])) ? $agendamento['tab_situacao']['icone_classe'] : null,
                           "nm_paciente" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                           "dt_nasc" => (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : null,
                           "situacao" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                           "usuario_geracao" => "",
                           "rendering" => null,
                           "backgroundColor" => null,
                           "icone_livre" => $IconeLivre,
                           "obs" => "",
                           "tipo_dados"=> "Escala_Normal",
                           "tipo_atend"=> (isset($agendamento['tipo_atend']['nm_tipo_atendimento'])) ? ($agendamento['tipo_atend']['nm_tipo_atendimento']) : ''
                        );

                     } else {

                        if ($agendamento) {

                           $retorno[]  = array(
                              "tp" => 1,
                              "cd_agendamento" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',
                              "Tipo" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                              "cd_horario" => $horario['cd_horario'],
                              "description" => ($horario['description']) ? $horario['description'] : '',
                              "agenda_aberta" => $horario['agenda_aberta'],
                              "encaixe" => null,
                              "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : '',
                              "local" => (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : '',
                              "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : '',
                              "id" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',
                              "start" => trim($DATA) . ' ' . trim($horario['start']),
                              "ds_start" =>  trim($horario['start']),
                              "nm_profissional" => (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : '',
                              "end" => trim($DATA) . ' ' . trim($horario['end']),
                              "ds_end" => trim($horario['end']),
                              "resourceId" => $horario['resourceId'],
                              "resource" => $horario['resource'],
                              "nm_resource" => $horario['nm_resource'],
                              "escalaId" => $horario['escalaId'],
                              "escalaIntervalo" => $horario['escalaIntervalo'],
                              "escalaNmIntervalo" => $horario['escalaNmIntervalo'],
                              "title" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : '',
                              "cd_dia" => $horario['cd_dia'],
                              "titulo" => (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                              "className" => (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                              "icone" => (isset($agendamento['tab_situacao']['icone_classe'])) ? $agendamento['tab_situacao']['icone_classe'] : null,
                              "nm_paciente" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] :  '',
                              "dt_nasc" => (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : '',
                              "situacao" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                              "usuario_geracao" => "",
                              "rendering" =>  '',
                              "backgroundColor" => '',
                              "icone_livre" => $IconeLivre,
                              "obs" => "",
                              "tipo_dados"=> "Escala_Normal2",
                              "tipo_atend"=> (isset($agendamento['tipo_atend']['nm_tipo_atendimento'])) ? ($agendamento['tipo_atend']['nm_tipo_atendimento']) : ''
                           );
                        }
                     }
                  }
               }
               $ateManual =  (isset($listaAgendamentoManual[$DATA])) ? $listaAgendamentoManual[$DATA] : null;

               if ($ateManual) {
                  foreach ($ateManual as $agendamento) {

                     unset($listaAgendamentoCorrecao[$agendamento['dt_agenda']][$agendamento['cd_agendamento']]);

                     $retorno[]  = array(
                        "tp" => 1,
                        "cd_agendamento" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',
                        "Tipo" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                        "cd_horario" => null,
                        "description" => $agendamento['agenda']['nm_agenda'],
                        "encaixe" => '&nbsp;&nbsp; <code><b> ENCAIXE </b></code>',
                        "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : '',
                        "local" => (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : '',
                        "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : '',
                        "id" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',
                        "start" => trim($agendamento['dt_agenda']) . ' ' . trim(substr($agendamento['hr_agenda'], 0, 5)),
                        "ds_start" => trim($agendamento['hr_agenda']),
                        "nm_profissional" => (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : '',
                        "end" => trim($agendamento['dt_agenda']) . ' ' . trim(substr($agendamento['hr_final'], 0, 5)),
                        "ds_end" => trim($agendamento['hr_final']),
                        "resourceId" => $agendamento['cd_agenda'],
                        "resource" => $agendamento['cd_agenda'],
                        "nm_resource" => $agendamento['agenda']['nm_agenda'],
                        "escalaId" => $agendamento['cd_escala'],
                        "escalaIntervalo" => $agendamento['intervalo'],
                        "escalaNmIntervalo" => null,
                        "title" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : '',
                        "cd_dia" => $agendamento['dia_semana'],
                        "titulo" => (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                        "className" => (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                        "icone" => (isset($agendamento['tab_situacao']['icone_classe'])) ? $agendamento['tab_situacao']['icone_classe'] : null,
                        "nm_paciente" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] :  '',
                        "dt_nasc" => (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : '',
                        "situacao" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                        "usuario_geracao" => "",
                        "rendering" =>  '',
                        "backgroundColor" => '',
                        "icone_livre" => $IconeLivre,
                        "obs" => "",
                        "tipo_dados"=> "ateManual",
                        "tipo_atend"=> (isset($agendamento['tipo_atend']['nm_tipo_atendimento'])) ? ($agendamento['tipo_atend']['nm_tipo_atendimento']) : ''
                     );
                  }
               }
            }
         }
 
         if ($listaAgendamentoCorrecao) {
            foreach ($listaAgendamentoCorrecao as $data) {

               foreach ($data as $val) {

                  $agendamento = $val[0];
                  $retorno[]  = array(
                     "tp" => 1,
                     "cd_agendamento" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',
                     "Tipo" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                     "cd_horario" => null,
                     "description" => $agendamento['agenda']['nm_agenda'],
                     "encaixe" => '&nbsp;&nbsp; <code><b> ENCAIXE </b></code>',
                     "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : '',
                     "local" => (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : '',
                     "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : '',
                     "id" => (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',
                     "start" => trim($agendamento['dt_agenda']) . ' ' . trim(substr($agendamento['hr_agenda'], 0, 5)),
                     "ds_start" => trim($agendamento['hr_agenda']),
                     "nm_profissional" => (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : '',
                     "end" => trim($agendamento['dt_agenda']) . ' ' . trim(substr($agendamento['hr_final'], 0, 5)),
                     "ds_end" => trim($agendamento['hr_final']),
                     "resourceId" => $agendamento['cd_agenda'],
                     "resource" => $agendamento['cd_agenda'],
                     "nm_resource" => $agendamento['agenda']['nm_agenda'],
                     "escalaId" => $agendamento['cd_escala'],
                     "escalaIntervalo" => $agendamento['intervalo'],
                     "escalaNmIntervalo" => null,
                     "title" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : '',
                     "cd_dia" => $agendamento['dia_semana'],
                     "titulo" => (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                     "className" => (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                     "icone" => (isset($agendamento['tab_situacao']['icone_classe'])) ? $agendamento['tab_situacao']['icone_classe'] : null,
                     "nm_paciente" => (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] :  '',
                     "dt_nasc" => (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : '',
                     "situacao" => (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                     "usuario_geracao" => "",
                     "rendering" =>  '',
                     "backgroundColor" => '',
                     "icone_livre" => $IconeLivre,
                     "obs" => "",
                     "tipo_dados"=> "listaAgendamentoCorrecao",
                     "tipo_atend"=> (isset($agendamento['tipo_atend']['nm_tipo_atendimento'])) ? ($agendamento['tipo_atend']['nm_tipo_atendimento']) : ''
                  );
               }
            }
         }

 
      return ['retorno' => true, 'dados' => $retorno];
       
      } catch (Exception $e) {

         return ['retorno' => false, 'dados' => $e];
      }
      
   }




   public function qtdeEscalaDiaria($agendas, $dt)
   {
      try {
      } catch (Exception $e) {

         $count = AgendaEscala::where('dt_inicial', $dt)
            ->where('sn_ativo', 'S')->where('escala_manual', 'S')
            ->whereIn('cd_agenda', $agendas)
            ->where('escala_diaria', 'S')->count();

         return ($count) ? $count : 0;
      }
   }
}
