@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Tela da Recepção</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('agendamento') }}">Relação</a></li>
            </ol>
        </div>
    </div>

    <style>
        .panel-body-livre {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #2ecc71;
        }

        .info-box .color-livre i {
            color: #2ecc71;
        }

        .whastValido {
            color: #2ecc71;
        }

        .whastInvalido {
            color: #ee6414;
        }

        .whastNeutro {
            color: #333;

        }

        .panel-body-agendado {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #7a6fbe;
        }

        .info-box .color-agendado i {
            color: #7a6fbe;
        }

        .panel-body-confirmado {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #12AFCB;
        }

        .info-box .color-confirmado i {
            color: #12AFCB;
        }

        .panel-body-aguardando {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #FF9800;
        }

        .info-box .color-aguardando i {
            color: #FF9800;
        }

        .panel-body-atendido {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #f6d433;
        }

        .info-box .color-atendido i {
            color: #f6d433;
        }

        .panel-body-cancelado {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #f25656;
        }

        .info-box .color-cancelado i {
            color: #f25656;
        }

        .label-black {
            background: #34425a;
        }

        <style>.ModalAgendamento .modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            overflow: hidden;
        }

        .ModalAgendamento .modal-dialog {
            position: fixed;
            margin: 0;
            width: 100%;
            height: 100%;
            padding: 0;
        }

        .ModalAgendamento .modal-content {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0;
            box-shadow: none;
        }

        .ModalAgendamento .modal-header {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 60px;
            padding: 10px;
            background: #0f172a;
            border: 0;
        }

        .ModalAgendamento .modal-title {
            font-weight: 300;
            font-size: 2em;
            color: #cbd5e1;
            line-height: 30px;
        }

        .ModalAgendamento .modal-body {
            position: absolute;
            top: 50px;
            bottom: 0px;
            width: 100%;
            overflow: auto;
            background: #0f172a;
        }

        .ModalAgendamento .modal-footer {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            /*padding: 2px;*/
            background: #0f172a;
        }

        .ModalAgendamento .form-group {
            margin-bottom: 10px;
        }

        .form-control[disabled],
        .form-control[readonly],
        fieldset[disabled] .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            opacity: 1;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove { 
            border-right: 0px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            padding-right: 5px; 
        }
    </style>

    <div id="app" x-data="app">
        <div id="main-wrapper">

            <div class="col-md-12 ">

                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified" role="tablist">

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade in" id="tabAgendamnento">

                                <div class="panel panel-white">
                                    <div class="panel-body">
                                        <div class="row">

                                            <form x-on:submit.prevent="getHorarios" id="form-horario" class="col-md-3"
                                                style="margin-bottom: 22px">

                                                <div id="loading-dados-mes" style="display: none">
                                                    <x-loading message="Buscando dados do mês..." />
                                                </div>
                                                <div id="calendar"></div>

                                                <input type="hidden" name="data" id="data-input" />
                                                <input type="hidden" name="tela" value="agendamento" />

                                                <hr />

                                                <label>Agenda:</label>
                                                <div class="form-data" style="margin-bottom: 16px">
                                                    <select class="form-control" name="agenda" id="CodAgenda">
                                                        <option value="">Sem agenda</option>
                                                        @foreach ($agendas as $agenda)
                                                            <option value="{{ $agenda->cd_agenda }}">
                                                                {{ $agenda->nm_agenda }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                @if (auth()->guard('rpclinica')->user()->sn_todos_agendamentos == 'S' ||
                                                        auth()->guard('rpclinica')->user()->admin == 'S')
                                                    <label>Profissional:</label>
                                                    <div class="form-data" style="margin-bottom: 16px">
                                                        <select class="form-control" name="profissional">
                                                            <option value="">Sem profissional</option>
                                                            @foreach ($profissionais as $profissional)
                                                                <option value="{{ $profissional->cd_profissional }}">
                                                                    {{ $profissional->nm_profissional }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <label>Especialidade:</label>
                                                <div class="form-data" style="margin-bottom: 16px">
                                                    <select class="form-control" name="especialidade">
                                                        <option value="">Sem especialidade</option>
                                                        @foreach ($especialidades as $especialidade)
                                                            <option value="{{ $especialidade->cd_especialidade }}">
                                                                {{ $especialidade->nm_especialidade }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <label>Situação:</label>
                                                <div class="form-data" style="margin-bottom: 16px">
                                                    <select id="select-situacao" class="form-control"
                                                        style="width: 100%; z-index: 5000" name="situacao" required>
                                                        <option value="">Sem Situação</option>
                                                        <option value="agendado">Agendado</option>
                                                        <option value="aguardando">Aguardando</option>
                                                        <option value="atendido">Atendido</option>
                                                        <option value="bloqueado">Bloqueado</option>
                                                        <option value="cancelado">Cancelado</option>
                                                        <option value="confirmado">Confirmado</option>
                                                        <option value="livre">Livre</option>
                                                    </select>
                                                </div>

                                                <label>Ordenação:</label>
                                                <div class="form-data" style="margin-bottom: 16px">
                                                    <select id="select-ordenar" class="form-control"
                                                        style="width: 100%; z-index: 5000" name="ordenar" required>
                                                        <option value="age">Agendamento</option>
                                                        <option value="con">Confirmação</option>
                                                    </select>
                                                </div>
                                            </form>

                                            <div class="col-md-9">
                                                <template x-if="messageDanger">
                                                    <div class="alert alert-danger">
                                                        <span x-text="messageDanger"></span>
                                                        <button x-on:click="messageDanger = null" type="button"
                                                            class="close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                </template>

                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr class="active">
                                                            <th class="text-center">Atendimento</th> 
                                                            <th>Paciente</th>
                                                            <th>Agenda</th>
                                                            <th>Especialidade</th>
                                                            <th>Local</th>
                                                            <th>Convênio</th>
                                                            <th>Situação</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr x-show="loading">
                                                            <td colspan="7">
                                                                <div class="line">
                                                                    <div class="loading"></div>
                                                                    <span>Buscando horários...</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <template x-if="horarios">
                                                            <template x-for="(horario, index) in horarios">

                                                                <tr style="cursor: pointer"
                                                                    x-on:click="clickHorario(horario,index)">

                                                                    <th class="text-center">
                                                                        <span style="font-size: 1.2em; font-style: italic;"
                                                                            class="btn btn-default btn-rounded btn-xs "
                                                                            x-bind:class="(horario.sn_atendimento=='S') ? 'btn-success' : ''"
                                                                            x-html="(horario.sn_atendimento=='S') ? horario.cd_agendamento : '#####' "></span>
                                                                        <br><span style="font-weight: 400">
                                                                            <i class="fa fa-calendar" style="margin-right: 1px;"></i>
                                                                            <span x-text="horario.hr_agenda"> </span>
                                                                        </span>
                
                                                                    </th>
 
                                                                    <td>
                                                                        <span x-text="horario.paciente?.nm_paciente"></span> 
                                                                        <span style="font-size: 12px; font-style: italic;"><br><b>Nome
                                                                            Social: </b>
                                                                            <span
                                                                                x-text="(horario.paciente?.nome_social) ? horario.paciente?.nome_social : ' -- '">
                                                                            </span>
                                                                        </span> 
                                                                    </td>

                                                                    <td>
                                                                        <span x-text="horario.agenda?.nm_agenda"></span>
                                                                        <template
                                                                            x-if="horario.profissional?.nm_profissional">
                                                                            <span
                                                                                style="font-size: 12px; font-style: italic;"
                                                                                x-html="'<br><b>Profissional: </b>'+horario.profissional?.nm_profissional"></span>
                                                                        </template>
                                                                    </td>

                                                                    <td>
                                                                        <span
                                                                            x-text="horario.especialidade?.nm_especialidade"></span>
                                                                        <template
                                                                            x-if="horario.tipo_atend?.nm_tipo_atendimento">
                                                                            <span
                                                                                style="font-size: 12px; font-style: italic;"
                                                                                x-html="'<br><b>Tipo Atend.: </b>'+horario.tipo_atend?.nm_tipo_atendimento"></span>
                                                                        </template>
                                                                    </td>

                                                                    <td>
                                                                        <span
                                                                            x-text="horario.local?.nm_local"></span>

                                                                        <span style="font-size: 12px; font-style: italic;"><br><b>Origem: </b>
                                                                            <span
                                                                                x-text="(horario.origem?.nm_origem) ? horario.origem?.nm_origem : ' -- '">
                                                                            </span>
                                                                        </span>
                                                                     
                                                                    </td>

                                                                    <td>
                                                                        <span
                                                                            x-text="horario.convenio?.nm_convenio"></span>

                                                                        <span style="font-size: 12px; font-style: italic;"><br>
                                                                            <template x-if="horario.convenio.tp_convenio == 'CO'">
                                                                                <span> 
                                                                                    <b>Cartão: </b>
                                                                                    <span
                                                                                        x-text="(horario.cartao) ? horario.cartao : ' -- '">
                                                                                    </span>
                                                                                </span>
                                                                            </template>
                                                                            <template x-if="horario.convenio.tp_convenio == 'PA'">
                                                                                <span> 
                                                                                    <template x-if="horario.recebido=='N'">
                                                                                        <code>Conta Bloqueada </code>
                                                                                    </template>

                                                                                    <template x-if="horario.recebido=='S'">
                                                                                        <code style="color: #176f60; background-color: #f4f9f2; " >Conta Liberada </code>
                                                                                    </template>
                                                                                </span>
                                                                            </template>
                                                                            <template x-if="horario.convenio.tp_convenio == 'SU'">
                                                                                <span> 
                                                                                    <b>Cartão SUS: </b>
                                                                                    <span
                                                                                        x-text="(horario.paciente.cartao_sus) ? horario.paciente.cartao_sus : ' -- '">
                                                                                    </span>
                                                                                </span>
                                                                            </template>
                                                                        </span>
                                                                    </td>


                                                                    <td style="min-width: 80px;" >
                                                                        <span
                                                                            x-html="horario.situacao?.icone + ' ' + horario.situacao?.nm_situacao "
                                                                            class="label"
                                                                            x-bind:class="horario.situacao?.class"
                                                                            style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;">
                                                                        </span>
                                                                    </td>

                                                                </tr>
                                                            </template>
                                                        </template>
                                                    </tbody>
                                                </table>
                                                <template x-if="!horarios">
                                                    <p class="text-center" style="padding: 1.5em">

                                                        <img src="{{ asset('assets\images\calendario.png') }}"> <br>
                                                        Não há atendimentos para esse dia
                                                    </p>
                                                </template>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
 

                        </div>

                </div>
            </div>

            <div class="ModalAgendamento">
                @include('rpclinica.recepcao.agendamento.modal_atend')
            </div>

            <div class="modal fade" id="modalProfExt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true" style="display: none; border: 1px solid #dee2e8;">
                <div class="modal-dialog " style="border: 1px solid #dee2e8;">
                    <form x-on:submit.prevent="storeProfExt" id=form-ProfExt>
                        @csrf  
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="myModalLabel">Cadastro do Profissional Externo</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label>Nome: <span class="red normal">*</span></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="nome" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Conselho: <span class="red normal"></span></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="conselho" />
                                        </div>
                                    </div>
                                </div> 
                            </div> 
                        </div>
                        <div class="modal-footer"
                            style="padding: 10px 15px;background-color: #0f172a; 
                                border-top: 1px solid rgba(255, 255, 255, 0.1);border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;">
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="modalOrigem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true" style="display: none; border: 1px solid #dee2e8;">
                <div class="modal-dialog " style="border: 1px solid #dee2e8;">
                    <form x-on:submit.prevent="storeOrigem" id=form-Origem>
                        @csrf  
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="myModalLabel">Cadastro de Origem</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Nome: <span class="red normal">*</span></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="nome" />
                                        </div>
                                    </div> 
                                </div> 
                            </div> 
                        </div>
                        <div class="modal-footer"
                            style="padding: 10px 15px;background-color: #0f172a; 
                                    border-top: 1px solid rgba(255, 255, 255, 0.1);border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;">
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>


    </div>

    </div>
    </div>
@endsection

@section('scripts')
    <style>
        .box-btn-float {
            position: fixed;
            bottom: 2em;
            right: 2em;
            z-index: 999;
        }

        .text-aguardando {
            color: #FF9800;
        }

        .box-btn-float button {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 3.5em;
            width: 3.5em;
            background-color: #22baa0;
            color: #ffffff;
            border: none;
            border-radius: 100%;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        .box-btn-float button i {
            margin: 0;
            font-size: 2rem;
        }

        .box-btn-float button:hover,
        .box-btn-float button:focus {
            background-color: #1be1bf;
            transform: translateX(0px) scale(1.1);
            transition: transform 0.3s;

        }

        .text-success {
            font-weight: bold;
        }

        #calendar .datepicker.datepicker-inline {
            width: 100%;
        }

        #calendar .datepicker.datepicker-inline table {
            margin: 0 auto;
            width: 100%;
        }

        .select2-container.select2-container--default.select2-container--open,
        .swal2-container {
            z-index: 9999;
        }

        #cadastro-consulta .tabpanel .nav.nav-tabs li {

            text-align: center;
        }

        #cadastro-consulta .tabpanel .nav.nav-tabs li.active {
            font-weight: bold;
        }
    </style>

    <script src="{{ asset('js/simple-calendar/simple-calendar.js') }}"></script>
    <script src="{{ asset('js/rpclinica/recepcao.js') }}"></script>
@endsection
