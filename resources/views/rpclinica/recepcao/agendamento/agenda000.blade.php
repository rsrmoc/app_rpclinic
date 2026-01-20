@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Relação De Agendamentos</h3>
    </div>

    <style>
        .btn-red {
            color: #f25656;
            font-weight: 500;
            font-size: 1.4em;
        }
        .btnAgendado
        .linhaHorarios .btn-success {
            color: #22BAA0;
            background-color: #fff;
            border: 1px solid #22BAA0;
        }

        .btn-success:hover {
            color: #fff;
            background-color: #22BAA0;
            border: 1px solid #22BAA0;
        }



        .b-dayview-day-container .b-calendar-cell .b-cal-event {
            align-items: stretch;
            background-color: var(--cal-event-color);
            color: #606060;
            border-radius: 0;
            position: relative;
        }

        .b-cal-event {
            display: flex;
            border-radius: 4px;
            align-items: center;
            flex: 1 1 auto;
            overflow: hidden;
            color: #606060;
            line-height: 1;
        }

        .b-dayview-day-container .b-cal-event-wrap.b-selected:not(.b-dragging-item):hover .b-cal-event-body,
        .b-dayview-day-container .b-cal-event-wrap.b-selected:not(.b-dragging-item) .b-cal-event-body {
            background-color: #fff9;
        }

        .b-dayview-day-container .b-calendar-cell .b-cal-tentative-event.b-short-event .b-cal-event-body,
        .b-dayview-day-container .b-calendar-cell .b-cal-event-wrap.b-short-event .b-cal-event-body {
            padding-block: 0;
            align-items: center;
            flex-flow: row-reverse nowrap;
            justify-content: flex-end;
            gap: .2em;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            padding-right: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right: 0px;
        }
        .fc-time-grid-event .fc-time {
            font-weight: bold;
            line-height: 1.2;
        }
        .fc-time{
            line-height: 1;
        }
        #calendar .fc-time > i{
            padding: 0px;
        }
        #calendar .fc-time > span{
            padding: 0px;
        }
        
        #calendar .fc-title{
            line-height: 1.1;
        }
        .fc-time-grid .fc-bgevent, .fc-time-grid .fc-event { 
            border-left: 4px solid;
            padding-left: 4px;
        }
        
 
    </style>
 
    <div id="app" x-data="app">
        <div id="main-wrapper" style=" ">
            
            <div role="tabpanel " style="">

                @include('rpclinica.recepcao.agendamento.sub_menu', ['tipo' => 'diario'])

            </div>

            <div id=loadingPesqAvanc>
                <div class="absolute-loading" style="display: none">
                    <div class="line">
                        <div class="loading"></div>
                        <span style="font-weight: bold; font-size: 1.3em; font-style: italic" x-html="loadingAcao"></span>
                    </div>
                </div>
            </div>

            

            <div class="panel panel-white">

                <div class="tab-content">

                    <div class="tab-pane active" role="tabpanel" id="TabCalendar">
                        <div class="panel-body">

                 
                            <div class="row">

                                <div class="col-md-12">

                                    <div id="calendar"></div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane " role="tabpanel" id="TabAgendaAvancada">

                        @include('rpclinica.recepcao.agendamento.pesquisa-avanc', [
                            'especialidades' => $especialidades,
                            'profissionais' => $profissionais,
                            'localAtendimentos' => $localAtendimentos,
                            'exames' => $exames,
                            'agendas' => $agendas,
                        ])

                    </div>

                    <div class="tab-pane " role="tabpanel" id="TabAgendaConfirmacao">
                        @include('rpclinica.recepcao.agendamento.agenda-confirmacao', [
                            'especialidades' => $especialidades,
                            'profissionais' => $profissionais,
                            'localAtendimentos' => $localAtendimentos,
                            'exames' => $exames,
                            'agendas' => $agendas,
                        ])
                    </div>

                </div>

            </div>

            <div class="ModalAgendamento">
                @include('rpclinica.recepcao.agendamento.modal',$request)
            </div>
            

            <div class="modal fade modalParametros " id="form-parametro" tabindex="-1" role="dialog"
                aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content ">
                        <form role="form" action="" method="post" role="form">
                            @csrf
                            <input type="hidden" name="param" value="S">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="mySmallModalLabel">Parametros</h4>
                            </div>
                            <div class="modal-body">

                                <div class="row">
                                    @foreach ($agendas as $agenda)
                                        <div class="col-md-6">
                                            <div class="todo-list" style="margin: 0px 0;">
                                                <label class="m-r-sm">
                                                    <div class="checker">
                                                        <span>
                                                            <input type="checkbox" name="relacaoAgendas[]"  
                                                             @if(isset($selectAgenda[$agenda->cd_agenda]))   @endif  
                                                            value="{{ $agenda->cd_agenda }}">
                                                        </span>
                                                    </div> {{ substr($agenda->nm_agenda, 0, 28) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach 
                                </div>
 
                            </div>
                            <div class="modal-footer">
                                <div class="row">
                                    <div class="col-md-6" style="text-align: left; padding-right: 3px;padding-left: 3px;">

                                        <select name="relacaoAgenda" class="form-control" style="width: 100%;">
                                            <option value="">Agenda</option>
                                            @foreach ($agendas as $agenda)
                                                <option value="{{ $agenda->cd_agenda }}">
                                                    {{ $agenda->nm_agenda }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-md-3" style="text-align: left;padding-right: 3px;padding-left: 3px;">

                                        <select name="intervalo_campo" class="form-control" style="width: 100%;">
                                            <option value="">Intervalo</option>
                                            @foreach ($Horarios as $horario)
                                                <option value="{{ $horario->nm_intervalo }}" @if($horario->nm_intervalo==$horariosPadrao) selected @endif >
                                                    {{ $horario->nm_intervalo }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-md-3" style="padding-right: 3px;padding-left: 3px;">
                                        <button type="submit" class="btn btn-success" style="width: 100%;">
                                            <span class="glyphicon glyphicon-search"></span> Pesquisar</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
 
            <div class="modal fade modalOpcoes" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none; padding-top: 20%;">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><span aria-hidden="true" class="icon-close"></span></span></button>
                            <h4 class="modal-title" id="mySmallModalLabel">Opções</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row" style="margin-top: 20px;">  
                              
                                    <button type="button" class="btn btn-info btn-addon m-b-sm btn-rounded btn-lg col-xs-8 col-md-8 col-md-offset-2" 
                                     data-toggle="modal" data-target=".modalEscalaManual"
                                        data-dismiss="modal" >
                                        <i class='fa fa-line-chart' style='margin-right: 0px;'></i> Criar Escala Manual   
                                    </button>

                                    <button type="button" class="btn btn-success btn-addon m-b-sm btn-rounded btn-lg col-xs-8 col-md-8 col-md-offset-2" 
                                    x-on:click="Modal(dadosOpcoes)" data-dismiss="modal">
                                        <i class="fa fa-calendar" style="margin-right: 0px;"></i> Agendar Encaixe
                                    </button>
                           
                            </div>

                        </div>
 
                    </div>
                </div>
            </div>
 
            <div class="modal fade modalEscalaManual" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;  ">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><span aria-hidden="true" class="icon-close"></span></span></button>
                            <h4 class="modal-title" id="mySmallModalLabel">Opções</h4>
                        </div>

                        <form x-on:submit.prevent="storeAgendamento" id="form-escala">
                            @csrf
                            <div class="modal-body" style="padding-top: 5px;">
                                <div class="row" >   
                                    <input type="hidden" name="resource" x-model="(dadosOpcoes.resource) ? dadosOpcoes.resource : ''" >
                                    <div class="col-md-12">
                                        <label>Agenda <span class="red normal">*</span></label>
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <input type="text" name="nm_resource" x-model="(dadosOpcoes.nm_resource) ? dadosOpcoes.nm_resource : ''"
                                            readonly   class="form-control center"  />
                                        </div>
                                    </div>    
                                    <div class="col-md-6">
                                        <label>Hr.Inicial <span class="red normal">*</span></label>
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <input type="text" name="hr_inicio" x-model="(dadosOpcoes.hr_start) ? dadosOpcoes.hr_start : ''"
                                                x-model="modalAgenda.hr_inicio" readonly 
                                                class="form-control center"   />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Hr.Final <span class="red normal">*</span></label>
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <input type="text" name="hr_final"  x-model="(dadosOpcoes.hr_end) ? dadosOpcoes.hr_end : ''"  readonly 
                                                class="form-control center"   />
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label>Data<span class="red normal">*</span></label>
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <input type="text" name="data"   readonly x-model="(dadosOpcoes.data_start) ? dadosOpcoes.data_start : ''"
                                                class="form-control center"  />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Intervalo <span class="red normal">*</span></label>
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <select class="form-control" required="" name="intervalo"   style="width: 100%">
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
                                <div class="row" style="margin-top: 20px;">   
                                
                                        <button type="submit" class="btn btn-primary btn-addon m-b-sm btn-rounded col-xs-8 col-md-8 col-md-offset-2" 
                                        x-on:click="escalaManual" data-dismiss="modal">
                                            <i class="fa fa-calendar" style="margin-right: 0px;"></i> Gerar Escala
                                        </button> 
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
            
            <div class="modal fade modalBloqueio" id="form-blqueio" tabindex="-1" role="dialog"
                aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="mySmallModalLabel">Bloqueio de Agenda</h4>
                            </div>
                            <div class="modal-body">
                                <form role="form" x-on:submit.prevent="listaBloqueio" id="form-bloqueio"  method="post" role="form">
                                    @csrf 
                                    <input type="hidden" name="tipo" value="bloqueio">
                                    <div class="row"> 
                                        <div class="col-md-7" style="text-align: left; padding-right: 3px;padding-left: 3px;">
                                            <div class="form-group" >
                                                <select name="agendas" id="relacaoAgendaBloqueio" class="form-control" style="width: 100%;">
                                                    <option value="">Agenda</option>
                                                    @foreach ($agendas as $agenda)
                                                        <option value="{{ $agenda->cd_agenda }}">
                                                            {{ $agenda->nm_agenda }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-3" style="padding-right: 3px;padding-left: 3px;">
                                            <input type="date" class="form-control" x-model="bloqueio.data"  name="data">
                                        </div>
                                        <div class="col-md-2" style="padding-right: 3px;padding-left: 3px;">
                                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                                <span class="glyphicon glyphicon-search"></span> </button>
                                        </div>
                                    </div> 
                                </form>

                                <form role="form" x-on:submit.prevent="storageBloqueio" id="form-storage-bloqueio"  method="post" role="form">
                                    @csrf 
                                    <div x-show="bloqueio.load"> 
                                        <div class="line" style="text-align: center; margin-top: 30px;">
                                            <div class="loading"></div>
                                            <span style="font-weight: bold; font-size: 1.3em; font-style: italic" x-html="loadingAcao"></span> Carregando as Informações...
                                        </div>
                                    </div>
                                    <div x-show="!bloqueio.load" > 
                                        <table class="table table-striped" style="margin-top: 10px; width: 100%">
                                            <thead>
                                                <tr class="active">
                                                    <th>#</th>
                                                    <th>Horario</th> 
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                    <template x-for="lista in bloqueio.lista">
                                                        <template x-if="(bloqueio.data == lista.start.substr(0, 10))">
 
                                                            <tr>
                                                                <td>
                                                                    <template  x-if="lista.situacao == 'livre'">
                                                                        <input type="checkbox"
                                                                            name="cd_agendamento_sessao[]"
                                                                            x-bind:value="JSON.stringify(lista)" />
                                                                    </template>
                                                                </td> 
                                                                <td x-html="((lista.icone) ?  lista.icone  : lista.icone_livre) + ' ' + lista.ds_start + ' - ' + lista.ds_end    "> </td>
                                                            </tr>

                                                        </template>
                                                    </template>
                                            </tbody>
                                        </table>
                                    </div> 
                                    <div class="row"> 
                                        <div class="col-md-4 col-md-offset-4" style="padding-right: 3px;padding-left: 3px;">
                                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                                <span class="glyphicon glyphicon-check"></span> Salvar </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                       
                        
                    </div>
                </div>
            </div>

            <div class="modal fade modalEscalaDiario" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;  ">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><span aria-hidden="true" class="icon-close"></span></span></button>
                            <h4 class="modal-title" id="mySmallModalLabel">Escala Personalizada</h4>
                        </div>

                        <form id="storeEscalaPersonalizada" >
                            @csrf
                            <div class="modal-body" style="padding-top: 5px;">
                                <div class="row" >    
                                    <input type="hidden" name="sn_diario" value="S">
                                    <div class="col-md-12">
                                        <label>Agenda <span class="red normal">*</span></label>
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <select class="form-control" required name="resource" id="modalEscalaDiarioAGENDA" style="width: 100%">
                                                <option value="">SELECIONE</option>
                                                @foreach ($agendas as $val)
                                                    <option value="{{ $val->cd_agenda }}" >{{ $val->nm_agenda }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> 

                                    <div class="col-md-6" style="padding-right: 5px;">
                                        <label>Hr.Inicial <span class="red normal">*</span></label>
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <input type="text" name="hr_inicio"   required
                                                x-model="modalAgenda.hr_inicio"  x-mask="99:99" 
                                                class="form-control center"   />
                                        </div>
                                    </div>

                                    <div class="col-md-6" style="padding-left: 5px;">
                                        <label>Hr.Final <span class="red normal">*</span></label>
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <input type="text" name="hr_final" required x-mask="99:99"
                                                class="form-control center"   />
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6" style="padding-right: 5px;">
                                        <label>Data<span class="red normal">*</span></label>
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <input type="date" name="data" required    
                                                class="form-control center"  />
                                        </div>
                                    </div>

                                    <div class="col-md-6" style="padding-left: 5px;">
                                        <label>Intervalo <span class="red normal">*</span></label>
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <select class="form-control" required="" name="intervalo" 
                                            id="modalEscalaDiarioINTERVALO"  style="width: 100%">
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
                                <div class="row" style="margin-top: 20px;">   
                                
                                        <button type="button" class="btn btn-primary btn-addon m-b-sm btn-rounded col-xs-8 col-md-8 col-md-offset-2" 
                                        x-on:click="storeEscalaPersonalizada" data-dismiss="modal">
                                            <i class="fa fa-calendar" style="margin-right: 0px;"></i> Gerar Escala
                                        </button> 
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div> 

            <div class="modal fade bs-example-modal-lg" id="ModalEnvios" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-close"></span></button>
                            <h4 class="modal-title" id="myModalLabel" x-html="modalEnvios.titulo"></h4>
                        </div>
                        <div class="modal-body">
                            <table  class="table table-striped table-hover " style="margin-top: 40px;"> 
                                <thead>
                                    <tr class="active">  
                                        <th class="text-left">Tipo</th>
                                        <th class="text-left">Data</th>
                                        <th class="text-left">Conteudo</th> 
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead> 
                        
                                <tbody>
                                    <template x-if="modalEnvios.lista">
                                    <template x-for="item in modalEnvios.lista">
                                        <tr> 
                                       
                                            <th class="text-left">
                                                <span x-text="item.tipo"></span> 
                                            </th>
                                             
                                            <th class="text-left">
                                                <span x-text="item.data"></span> 
                                            </th>
                                            
                                            <th class="text-left">
                                                <template x-if="item.from_me==1">
                                                    <span x-text="item.conteudo"></span> 
                                                </template>
                                                <template x-if="item.from_me==0">
                                                    <span x-text="item.retorno"></span>
                                                </template>
                                            </th>
                                            
                                            <th class="text-left">
                                                <template x-if="item.from_me==1">
                                                    <span data-toggle="dropdown" aria-expanded="false" class="label label-info" style="cursor: pointer;" >Enviado</span>
                                                </template>
                                                <template x-if="item.from_me==0">
                                                    <span data-toggle="dropdown" aria-expanded="false" class="label label-danger" style="cursor: pointer;" >Não Enviado</span>
                                                </template>
                                            </th>
                                             
                                        
                                        </tr>
                                    </template>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@section('scripts')
    <script>

        function marcardesmarcar() {
            $('.marcar').each(function () {
                if (this.checked) this.checked = false;
                else this.checked = true;
            });
        }

        const url_eventos = @js(route('show.agendamento'));
        const businessHours = @js($businessHours);
        const resources = @js($resources);
        const AgendaItens = @js($AgendaItens);
        const empresa = @js($empresa);
        const DiasAgenda = @js($DiasAgenda);
        const horarioPadrao = @js($horariosPadrao);
    </script>

    <script src="{{ asset('js/rpclinica/agendamento-agenda.js') }}"></script>

@endsection
