@extends('rpclinica.layout.layout')



@section('content')

 
    <style>
        .readonly{
            background-color: #f5f5f5;
        }
    
    </style>
            <div class="page-title">
                <h3>Edição de Agenda</h3>
                <div class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('agenda.listar') }}">Relação de Agendas</a></li>
                    </ol>
                </div>
            </div>

    @if ($errors->any())
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-10 col-md-offset-1 ">
            <div class="alert alert-danger">
                <h4 class="text-left">Houve alguns erros:</h4>

                <ul>
                    {!! implode('', $errors->all('<li class="text-left">:message</li>')) !!}
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div id="main-wrapper" x-data="app">

 
        <div class="col-md-12 ">

            <div role="tabpanel"  >
                
                @if($agenda->sn_agenda_aberta<>'S') 
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li role="presentation" class=" @if($Tab=='agenda') active @endif "><a href="#tabAgenda" role="tab"
                                data-toggle="tab">Cadastro de Agenda</a></li>
            
                        <li role="presentation" class=" @if($Tab=='escala') active @endif "><a href="#tabEscala" role="tab"
                                data-toggle="tab">Cadastro de Escala</a></li> 
                    </ul>
                    <!-- Tab panes -->
                @else
                    @php $Tab='agenda'; @endphp
                @endif 
                <div class="tab-content">

                        <div role="tabpanel" class="tab-pane @if($Tab=='agenda') active fade in @endif " id="tabAgenda">
                                <div class="panel-body">
                                    @error('error')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    @if ($agenda->agendamento_gerado)
                                        <div class="alert alert-warning">
                                            <i class="fa fa-info-circle"></i>&ensp;Agendamentos gerados
                                        </div>
                                    @endif

                                    <form role="form" id="formAgenda"
                                        action="{{ route('agenda.update', ['agenda' => $agenda->cd_agenda]) }}" method="post"
                                        role="form">
                                        @csrf

                                        <input type="submit" class="btn btn-success" style="display: none" />

                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Descrição da Agenda: <span class="red normal">*</span></label>
                                                    <input type="text" class="form-control required" value="{{ $agenda->nm_agenda }}"
                                                        name="descricao" maxlength="100" aria-required="true" required>
                                                </div>
                                            </div>
 
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Tela Executante: <span class="red normal">*</span></label>
                                                    <select class="form-control" required name="tela_exec" id="tela_exec"
                                                    style="display: none; width: 100%">
                                                    <option value="">...</option>
                                                    <option value="CO" @if (old('tela_exec',$agenda->tela_exec) == "CO") selected @endif >Consultório</option>
                                                    <option value="CE" @if (old('tela_exec',$agenda->tela_exec) == "CE") selected @endif >Central de Laudos</option>
                                                    <option value="AM" @if (old('tela_exec',$agenda->tela_exec) == "AM") selected @endif >Ambos</option>
                                                  
                                                </select>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Profissional:</label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="profissional" id="profissionais"
                                                            style="display: none; width: 100%">
                                                            <option value="">Todos</option>
                                                            @foreach ($profissionais as $profissional)
                                                                <option value="{{ $profissional->cd_profissional }}"
                                                                    @if ($agenda->cd_profissional == $profissional->cd_profissional) selected @endif>
                                                                    {{ $profissional->nm_profissional }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <span class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                                            title="Não editável">
                                                            <div class="checker">
                                                                <span>
                                                                    <input type="checkbox" aria-label="..." name="profissional-editavel"
                                                                        value="1" @if ($agenda->profissional_editavel) checked @endif>
                                                                </span>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row" style="margin-bottom: 10px">


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Tipo de Atendimento:</label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="tipo_atend" id="tipo_atend"
                                                            style="display: none; width: 100%">
                                                            <option value="">Todos</option>
                                                            @foreach ($tipo as $profissional)
                                                                <option value="{{ $profissional->cd_tipo_atendimento }}"
                                                                    @if ($agenda->cd_tipo_atend == $profissional->cd_tipo_atendimento) selected @endif>
                                                                    {{ $profissional->nm_tipo_atendimento }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <span class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                                            title="Não editável">
                                                            <div class="checker">
                                                                <span>

                                                                    <input type="checkbox" aria-label="..." name="tipo_atendimento-editavel"
                                                                    value="1" @if ($agenda->tipo_atend_editavel) checked @endif>
                                                                </span>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Especialidade:</label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="especialidade" id="especialidades"
                                                            style="display: none; width: 100%">
                                                            <option value="">Todos</option>
                                                            @foreach ($especialidades as $especialidade)
                                                                <option value="{{ $especialidade->cd_especialidade }}"
                                                                    @if ($agenda->cd_especialidade == $especialidade->cd_especialidade) selected @endif>
                                                                    {{ $especialidade->nm_especialidade }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                                            title="Não editável">
                                                            <div class="checker">
                                                                <span>
                                                                    <input type="checkbox" aria-label="..." name="especialidade-editavel"
                                                                        value="1" @if ($agenda->especialidade_editavel) checked @endif>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Local de Atendimento:</label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="local_atendimento" id="local_atendimento"
                                                            style="display: none; width: 100%">
                                                            <option value="">Todos</option>
                                                            @foreach ($locais as $local)
                                                                <option value="{{ $local->cd_local }}"
                                                                    @if ($agenda->cd_local_atendimento == $local->cd_local) selected @endif>
                                                                    {{ $local->nm_local }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                                            title="Não editável">
                                                            <div class="checker">
                                                                <span>
                                                                    <input type="checkbox" aria-label="..."
                                                                        name="local_etendimento-editavel" value="1"
                                                                        @if ($agenda->local_atendimento_editavel) checked @endif>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Itens de Agendamento:</label> 
                                                        <select class="form-control" name="cd_exame[]"  multiple="multiple"  
                                                            style="display: none; width: 100%">
                                                            <option value="">Todos</option>
                                                            @foreach ($exames as $exame)
                                                                <option value="{{ $exame->cd_exame }}" @if($exame->agenda_exame) selected @endif> {{ $exame->nm_exame }}</option>
                                                            @endforeach
                                                        </select> 
                                                </div>
                                            </div> 
                                        
                                        </div>

                                        <div class="row" style=" ">
                                            <div class="col-md-12">
                                                <label>Observação da Agenda: <span class="red normal"></span></label>
                                                <textarea id="w3review" class="form-control required" name="observacao" rows="4" cols="50">{{ $agenda->obs }}</textarea>
                                            </div>
                                        </div>

                                        <div class="row" >
                                            <div class="col-md-12">
                                                <div class="form-group" style="margin-top: 10px;">
                                                    <label>
                                                        <input type="checkbox" aria-label="..."
                                                            name="sn_agenda_aberta" 
                                                            @if ($agenda->sn_agenda_aberta=='S') checked @endif
                                                            value="S" > Agenda de escala Aberta
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
 
                                    </form>
                                </div>

                                <div class="panel-footer">
                                    <input type="submit" class="btn btn-success" value="Salvar" x-on:click="submitAgenda" />
                                    <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                                </div>
                        </div>

                        <div role="tabpanel" class="tab-pane @if($Tab=='escala') active fade in @endif  " id="tabEscala">

                            <form role="form" id="formAgendaEscala" x-on:submit.prevent="saveEscala" action="{{ route('agenda.escala.store') }}" method="post" role="form">
                                @csrf
                                <input type="hidden" name="cd_agenda" value="{{ $agenda->cd_agenda }}" >
                                <input type="hidden" name="cd_escala" x-model="codEscalaEdicao" >

                                <div class="row" style="margin-bottom: 10px; margin-top: 15px;" x-show="showDivEdicao">
 
                                    <div class="col-md-1 col-sm-12 col-xs-12 col-md-offset-2 ">
                                        <div class="form-group">
                                            <label>
                                                <span id="check-segunda">
                                                <input type="checkbox" name="semana[]" value="segunda" class="flat-red"  >
                                                </span>
                                                Seg.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>
                                                <span id="check-terca">
                                                <input type="checkbox" name="semana[]" value="terca" class="flat-red" >
                                                </span>
                                                Ter.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>
                                                <span id="check-quarta">
                                                <input type="checkbox" name="semana[]" value="quarta" class="flat-red"   >
                                                </span>
                                                Qua.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>
                                                <span id="check-quinta">
                                                <input type="checkbox" name="semana[]" value="quinta" class="flat-red">
                                                </span>
                                                Qui.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>
                                                <span id="check-sexta">
                                                <input type="checkbox" name="semana[]" value="sexta" class="flat-red">
                                                </span>
                                                Sex.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>
                                                <span id="check-sabado">
                                                <input type="checkbox" name="semana[]" value="sabado" class="flat-red">
                                                </span>
                                                Sab.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>
                                                <span id="check-domingo">
                                                <input type="checkbox" name="semana[]" value="domingo" class="flat-red">
                                                </span>
                                                Dom.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                                                   
                                    <div class="col-md-2 col-md-offset-1">
                                        <div class="form-group">
                                            <label>Horário Inicial: <span class="red normal">*</span></label>
                                            <input type="time" class="form-control required"
                                                value="{{ old('hora_inicial') }}" name="hora_inicial" maxlength="100" id="hora_inicial"
                                                aria-required="true" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Horário Final: <span class="red normal">*</span></label>
                                            <input type="time" class="form-control required" value="{{ old('hora_final') }}" id="hora_final"
                                                name="hora_final" maxlength="100" aria-required="true" required>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Qtde. Encaixe:</label>
                                            <select class="form-control" name="qtde_encaixe"
                                                id="qtde_encaixe"
                                                style="display: none; width: 100%">
                                                <option value="">Qtde</option>
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}"
                                                        @if (old('qtde_sessao') == $i) selected @endif>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Sessão:</label>
                                            <div class="input-group">
                                                <select class="form-control" name="qtde_sessao" id="qtde_sessao"
                                                    style="display: none; width: 100%">
                                                    <option value="">Qtde</option>
                                                    @for ($i = 1; $i <= 50; $i++)
                                                        <option value="{{ $i }}"
                                                            @if (old('qtde_sessao') == $i) selected @endif>{{ $i }}</option>
                                                    @endfor
                                                </select>

                                                <div class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                                    title="Agenda de Sessões?">
                                                    <div class="checker">
                                                        <span id="check-sessao">
                                                            <input type="checkbox"  aria-label="..." name="sn_sessao"   >
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Intervalo: <span class="red normal">*</span></label>

                                            <select class="form-control" required="" name="intervalo"  id="intervalo" style="width: 100%">
                                                <option value="">SELECIONE</option>
                                                @foreach ($intervalos as $intervalo)
                                                    <option value="{{ $intervalo->cd_intervalo }}"
                                                        @if (old('intervalo') == $intervalo->cd_intervalo) selected @endif >
                                                        {{ $intervalo->mn_intervalo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                </div>
 
                                <div class="row">
                                    <div class="col-md-2 col-sm-12 col-xs-12 col-md-offset-3 ">
                                        <div class="form-group">
                                            <label>
                                                <span id="check-sus">
                                                <input type="checkbox" name="sus" value="1" class="flat-red">
                                                </span>
                                               SUS
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12 ">
                                        <div class="form-group">
                                            <label>
                                                <span id="check-convenio">
                                                <input type="checkbox" name="convenio" value="1" class="flat-red">
                                                </span>
                                               Convênvio
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12 ">
                                        <div class="form-group">
                                            <label>
                                                <span id="check-particular">
                                                    <input type="checkbox" name="particular"    value="1" class="flat-red">
                                                </span>
                                               Particular
                                            </label>
                                        </div>
                                    </div>
                                </div>
  
                                <div class="panel-footer">
                                    <button type="submit" class="btn btn-success" x-html="textIcoSalvar"></button>
                                    <input type="reset" class="btn btn-default" value="Limpar" x-on:click="limparEscala()" />
                                    <span x-html="msgEdicao"> </span>
                                </div>

                            </form>
 
                            <div class="row" x-show="agendaEncaixe">
                                <div class="col-md-12">
                                    <div class="panel panel-default" style="margin-top: 10px;">
                                        <div class="panel-heading" style="padding: 10px; height: auto;">
                                            <h3 class="panel-title" >
                                                <span style="cursor: pointer; color: #078570;" 
                                                      x-on:click="getAgendamentoEncaixe(dadosEscala)"
                                                      class="glyphicon glyphicon-refresh" aria-hidden="true"></span> 
                                                Pacientes de Encaixe
                                            </h3>
                                        </div> 
                                        <ul class="list-group">

                                            <template x-for="val_encaixe, indice in listaAgendaExaixe">
                                                <li  class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-4" >
                                                            <span x-html="'<b>Agendamento :</b> ' + val_encaixe.cd_agendamento"></span><br>
                                                            <span x-html="val_encaixe.paciente?.nm_paciente"></span>
                                                        </div>
                                                        <div class="col-md-2" >
                                                            <span x-html=" val_encaixe.data" ></span><br>
                                                            <span x-html=" val_encaixe.hr_agenda"></span>
                                                        </div>
                                                        <div class="col-md-3" >
                                                            <span x-html=" val_encaixe.profissional?.nm_profissional"></span><br>
                                                            <span x-html=" val_encaixe.situacao?.nm_situacao"></span>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select class="form-control"  x-bind:id="'enc_horario'+val_encaixe.cd_agendamento"  style="  width: 100%">
                                                                <option value="" >...</option>
                                                                <template x-for="horarios, indice in val_encaixe.horario_disponivel">
                                                                    <option x-bind:value="horarios.cd_agenda_escala_horario" x-text="horarios.cd_horario"></option>
                                                                </template>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button type="button" x-on:click="storeAgendamentoEncaixe(val_encaixe.cd_agendamento)" class="btn btn-primary">Salvar</button>
                                                        </div> 
                                                    </div>
                                                </li>
                                            </template>

                                        </ul>
                                    </div>
                                </div>
                            </div><!-- Row -->


                            <style>
                                .through{
                                    text-decoration: line-through;
                                } 
                            </style>
 

                            <div class="table-responsive" style="margin-top: 20px;">
                                <table class="display table  ">
                                    <thead>
                                        <tr class="active"> 
                                            <th >Codigo</th>
                                            <th >Dia</th>
                                            <th>Hora Inicial</th>
                                            <th>Hora Final</th>
                                            <th>Intervalo</th>
                                            <th>Sessão</th>
                                            <th>Encaixe</th>
                                            <th>Tipo de Escala</th>
                                            <th>SUS</th>
                                            <th>Convênio</th>
                                            <th>particular</th>
                                            <th style="text-align: right">Qtd. Agend.</th>
                                            <th style="text-align: center">Encaixe</th>
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="escala, indice in listaEscalas">
                                             
                                                <tr x-bind:class="(escala.sn_ativo=='N') ? 'through' : '' " >
                                                
                                                    <td x-text="escala.cd_escala_agenda"></td>
                                                    <td  > 
                                                        <span  x-html="((escala.escala_manual=='S') ? (escala.data_inicial+'<br>') : '')  +  LetraMaiuscula(escala.cd_dia)"></span>
                                                    </td>
                                                    <td x-text="escala.hora_inicial"></td>
                                                    <td x-text="escala.hora_final"></td>
                                                    <td x-text="escala.nm_intervalo"></td>
                                                    <td x-text="(escala.sn_sessao=='1') ? 'Sim ('+escala.qtde_sessao+')' : 'Não' "></td>
                                                    <td x-text="escala.qtde_encaixe"></td>
                                                    <td >
                                                        <template x-if="((escala.escala_manual=='S')&&(escala.escala_diaria==null)) ">
                                                            <code>Escala Manual</code>
                                                        </template>
                                                        <template x-if="((escala.escala_manual=='S')&&(escala.escala_diaria=='S')) ">
                                                            <code>Escala Diaria</code>
                                                        </template>
                                                        <template x-if="(escala.escala_manual==null)">
                                                            <code style="color: #3F51B5;background-color: #ebeef1;">Escala Agenda</code>
                                                        </template>
                                                    </td>
                                                    <td x-text="(escala.sn_particular=='1') ? 'Sim' : 'Não'"></td>
                                                    <td x-text="(escala.sn_convenio=='1') ? 'Sim' : 'Não'"></td>
                                                    <td x-text="(escala.sn_sus=='1') ? 'Sim' : 'Não'"></td>
                                                    <td style="text-align: right" x-text="(escala.agendamento) ? _.keys(escala.agendamento).length : 0" >1</td>
                                                    <td style="text-align: center"  >

                                                        <button   type="button" class="btn btn-default"   
                                                        x-on:click="getAgendamentoEncaixe(escala)"
                                                        x-text="(escala.agendamento_pendente) ? _.keys(escala.agendamento_pendente).length : 0"
                                                        style=" padding: 0px 4px; padding: 2px 8px !important; font-size: 1.0rem !important;"> 
                                                        </button> 
                                                    </td>
                                                    <td class="text-center" style="cursor: pointer;     padding: 1px!important; " >

                                                        <template x-if="escala.sn_ativo=='S'">

                                                            <div class="btn-group" style="padding: 1px 6px !important;">
                                                                <template x-if="(escala.escala_manual!='S')">
                                                                    <button   type="button" class="btn btn-success" x-on:click="editarEscala(escala)"  style=" padding: 0px 4px; padding: 2px 8px !important; font-size: 1.0rem !important;" >
                                                                        <span aria-hidden="true" class="icon-note"></span>
                                                                    </button>
                                                                </template>
                                                            
                                                                <button   type="button" class="btn btn-danger"   x-on:click="execluirEscala(escala.cd_escala_agenda)"   style=" padding: 0px 4px; padding: 2px 8px !important; font-size: 1.0rem !important;">
                                                                    <span aria-hidden="true" class="icon-trash"></span>
                                                                </button>
                                                            </div>
                                                        </template> 
                                                    </td>
                                                </tr> 

                                        </template>
                                    </tbody> 
                                </table>
                                 
                            </div>
                        </div>


                        <div class="modal fade" id="editar-escala">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">


                                    <div class="modal-header m-b-sm">
                                        <div class="line" style="justify-content: space-between">
                                            <template x-if="loadingGetHorarios">
                                                <x-loading message="Buscando horários..." />
                                            </template>
                                            <h4 class="modal-title" x-html="'[ ' + modalEdicao.cd_dia.charAt(0).toUpperCase() + modalEdicao.cd_dia.slice(1) + ' - ' + modalEdicao.cd_escala_agenda + ' ]'" >  </h4>
                                           
                                            <button type="button" class="close m-l-sm" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true" style="padding-top: 20px;"><span aria-hidden="true"
                                                        class="icon-close"></span></span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="modal-body">
                                        <div class="absolute-loading" x-show="loadingGeracao">
                                            <div class="line">
                                                <div class="loading"></div>
                                                <span>Carregando...</span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                    </div>

                                </div>
                            </div>
                        </div>
 
                        <div class="modal fade" id="cadastro-geracao">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="absolute-loading" x-show="loadingGeracao">
                                        <div class="line">
                                            <div class="loading"></div>
                                            <span>Carregando...</span>
                                        </div>
                                    </div>

                                    <div class="modal-header m-b-sm">
                                        <div class="line" style="justify-content: space-between">
                                            <template x-if="loadingGetHorarios">
                                                <x-loading message="Buscando horários..." />
                                            </template>

                                            <template x-if="!loadingGetHorarios">
                                                <h4 class="modal-title" x-text="`${agendaHorarios?.escalas.cd_dia ? agendaHorarios?.escalas.cd_dia.toUpperCase(): '' } [${agendaHorarios?.escalas.cd_agenda}] [${agendaHorarios?.escalas.cd_escala_agenda}]`"></h4>
                                            </template>
                                            <button type="button" class="close m-l-sm" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true" style="padding-top: 20px;"><span aria-hidden="true"
                                                        class="icon-close"></span></span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="modal-body">

                                        <div role="tabpanel">
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                                <li role="presentation" class="active"><a href="#tabGeracao" role="tab" data-toggle="tab" aria-expanded="true">Geração de Agenda</a></li>
                                                <li role="presentation"><a href="#tabExcluir" role="tab" data-toggle="tab">Exclução por Periodo</a></li>
                                            </ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade active in" id="tabGeracao">
                                                    <div style="display: flex; justify-content: space-between; align-items: center">
                                                        <h3 x-show="!loadingGetHorarios"
                                                            style="font-weight: 300; text-align: center; margin: 0"
                                                            x-text="`${formatDate(agendaHorarios?.escalas.dt_inicial)} - ${formatDate(agendaHorarios?.escalas.dt_fim)} ( ${timeCut(agendaHorarios?.escalas.hr_inicial)} - ${timeCut(agendaHorarios?.escalas.hr_final)} ) [ ${calcIntervalo(agendaHorarios?.escalas.intervalo)} ]`"></h3>

                                                        <div>
                                                            <template x-if="!agendaHorarios?.escalas.escala_gerada && !loadingGetHorarios">
                                                                <div>
                                                                    <button type="button" class="btn btn-success" x-on:click="gerarAgendamentos">Gerar agendamentos</button>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>

                                                    <template x-if="agendaHorarios?.escalas.escala_gerada">
                                                        <div class="alert alert-warning" style="margin-top: 20px;">
                                                            <i class="fa fa-info-circle"></i>&ensp;Escala Gerada com sucesso!
                                                            [ <b>Usuario: </b> <span x-text="agendaHorarios?.escalas.usuario?.email"></span>  ]  [ <b>Data: </b><span x-text="formatDate(agendaHorarios?.escalas.dt_geracao)"></span> ]
                                                        </div>
                                                    </template>

                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <h3 style="font-weight: 300; text-align: center;">Horarios da Agenda</h3>
                                                            <table class="table table-striped table-hover">
                                                                <thead>
                                                                    <tr class="active">
                                                                        <th>Bloqueia</th>
                                                                        <th>Horário</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <template x-for="horario in agendaHorarios?.horarios ?? []">
                                                                        <tr class="">
                                                                            <td>
                                                                                <input type="checkbox" class="flat-red"
                                                                                    x-model="inputsGeracao.horarios_bloqueados" x-bind:value="horario.horario" />
                                                                            </td>

                                                                            <td x-text="timeCut(horario.horario)"></td>
                                                                        </tr>
                                                                    </template>
                                                                </tbody>
                                                            </table>

                                                        </div>

                                                        <div class="col-sm-8">

                                                            <h3 style="font-weight: 300; text-align: center;">Feriados</h3>
                                                            <table class="table table-striped table-hover">
                                                                <thead>
                                                                    <tr class="active">
                                                                        <th>Bloqueia</th>
                                                                        <th>Data</th>
                                                                        <th>Feriado</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <template x-for="feriado in agendaHorarios?.feriados">
                                                                        <tr class="">
                                                                            <td>
                                                                                <input type="checkbox" class="flat-red"
                                                                                    x-model="inputsGeracao.feriados" x-bind:value="feriado.dt_feriado" />
                                                                            </td>
                                                                            <td x-text="formatDate(feriado.dt_feriado)"></td>
                                                                            <td x-text="feriado.nm_feriado"></td>
                                                                        </tr>
                                                                    </template>
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>


                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="tabExcluir">

                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h3 style="font-weight: 300; text-align: center;">Exlução de Datas</h3>
                                                            <form x-on:submit.prevent="pesquisaExclusao" class="row" id="form-exclusao">
                                                                <div class="col-md-1"></div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Data Inicial: <span class="red normal">*</span></label>
                                                                        <input type="date" class="form-control required" name="data_inicial"
                                                                            aria-required="true" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Data Final: <span class="red normal">*</span></label>
                                                                        <input type="date" class="form-control required" name="data_final"
                                                                            aria-required="true" required>
                                                                    </div>
                                                                </div>

                                                                <button type="submit" class="btn btn-success col-md-2" style="margin-top: 23px;">Pesquisar</button>
                                                            </form>

                                                            <template x-if="loadingExclusao">
                                                                <x-loading message="Buscando datas..." />
                                                            </template>

                                                            <table class="table table-striped table-hover">
                                                                <thead>
                                                                    <tr class="active">
                                                                        <th>Exluir</th>
                                                                        <th>Data</th>
                                                                        <th>Ocupados</th>
                                                                        <th>Livres</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <template x-for="valores, data in dadosExclusao">
                                                                        <tr class="">
                                                                            <td>
                                                                                <template x-if="valores.ocupados == 0">
                                                                                    <input type="checkbox"  class="flat-red" x-bind:value="data" x-model="datasParaExluir" />
                                                                                </template>
                                                                            </td>
                                                                            <td x-text="formatDate(data)"></td>
                                                                            <td x-text="valores.ocupados"></td>
                                                                            <td x-text="valores.livres"></td>
                                                                        </tr>
                                                                    </template>
                                                                </tbody>
                                                            </table>

                                                            <template x-if="datasParaExluir.length > 0">
                                                                <div>
                                                                    <div class="alert alert-danger">
                                                                        <template x-for="data, indice in datasParaExluir">
                                                                            <span x-text="`${formatDate(data)}${datasParaExluir.length -1 != indice ? ', ': ''}`"></span>
                                                                        </template>
                                                                    </div>

                                                                    <div style="display: flex; justify-content: flex-end; align-items: center">
                                                                        <template x-if="loadingExclusaoToggle">
                                                                            <x-loading message="" />
                                                                        </template>

                                                                        <button class="btn btn-danger" x-on:click="excluirDatas" x-bind:disabled="loadingExclusaoToggle">
                                                                            <i class="fa fa-trash"></i>&ensp;Excluir datas
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                    </div>

                                </div>
                            </div>
                        </div>
 
  
                </div>
            </div>
        </div>

    </div><!-- Main Wrapper -->
    
@endsection

@section('scripts')
    <script>
        const especialidades = @js($especialidades);
        const procedimentos = @js($procedimentos);
        const profissionais = @js($profissionais);
        const locais = @js($locais);
        const convenios = @js($convenios);
        const agenda = @js($agenda);
        const escala = @js($escalas);

    </script>
    <script src="{{ asset('js/rpclinica/agenda.js') }}"></script>
 
 
@endsection
