@extends('rpclinica.layout.layout')

@section('content')
    <style>
        .ModalAgendamento .modal {
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

        .server-load>.server-stat {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 10px;
        }

        .h1,
        .h2,
        .h3,
        h1,
        h2,
        h3 {
            margin-top: 0px;
            margin-bottom: 0px;
        }

        .list-paciente-info {
            margin-bottom: 3px;
        }

        .label-black {
            background: #34425a;
        }

        .ms-settings-toggle {
            position: fixed;
            bottom: 35px;
            right: 15px;
            z-index: 4;
            width: 120px;
            height: 60px;
            margin-right: -40px;
            cursor: pointer;
            -webkit-transition: 0.3s;
            transition: 0.3s;
        }

        .ms-d-block-lg {
            display: block;
        }

        .ms-settings-toggle i {
            width: 50%;
            height: 100%;
            border-radius: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #22baa0;
            color: #fff;
            -webkit-box-shadow: 0 -2px 16px rgba(0, 0, 0, .1);
            box-shadow: 0 -2px 16px rgba(0, 0, 0, .1);
            font-size: 2.5em;
        }
    </style>

    <div class="page-title">
        <h3>Consultório</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('consultorio') }}">Relação</a></li>
            </ol>
        </div>
    </div>

    <style>
        .loader {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
            border-top: 3px solid #FFF;
            border-right: 3px solid transparent;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }


        .PreExamePendente {
            color: #a94442
        }

        .PreExameRealizado {
            color: #3c763d;
        }

        .etapaRealizado {
            color: #3c763d;
            border: 1px solid #3c763d;
        }

        .etapaExame {
            color: #60a5fa;
            background-color: rgba(96, 165, 250, 0.1);
        }

        .btn-rounded {
            padding-left: 10px;
            padding-right: 10px;
            border-radius: 40px;
        }

        .nav-tabs>li>a {
            border-bottom: 0px;
        }

        .nav-tabs>li>a {
            margin-right: 0px;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .server-load .server-stat p {
            font-weight: 300;
            font-size: 18px;
            margin-bottom: 3px;
            margin-right: 10px;
        }
    </style>
    <script>
        document.addEventListener('alpine:init', () => {
             // Make function available to Alpine scope if needed, or just keep global
        });
        
        function formatDateLong(dateStr) {
            if (!dateStr) return '';
            // Handle YYYY-MM-DD
            const parts = dateStr.split('-');
            if(parts.length !== 3) return dateStr;
            
            const months = [
                'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ];
            
            const year = parts[0];
            const monthIndex = parseInt(parts[1]) - 1;
            const day = parts[2];
            
            return `${day} of ${months[monthIndex]} of ${year}`.replace(/of/g, 'de');
        }
    </script>
    <style>
        .datePickerAgendamento {
            background: #fff;
            border-radius: 24px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            margin-bottom: 25px;
        }
        /* Card Visualization Styles */
        .card-patient {
            background: #fff;
            border-radius: 24px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            margin-bottom: 25px;
            border: 1px solid #edf2f7;
            font-family: 'Open Sans', sans-serif;
            transition: all 0.3s ease;
            position: relative;
        }

        .card-patient:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .cp-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .cp-init-circle {
            min-width: 48px;
            width: 48px;
            height: 48px;
            background-color: #d1fae5;
            color: #0d9488;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
        }

        .cp-info {
            flex: 1;
            overflow: hidden;
        }

        .cp-name {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cp-status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
            font-weight: 500;
        }

        .cp-status i {
            color: #2AB09C;
        }

        .cp-datetime {
            color: #0d9488;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 0;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cp-doctor {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .cp-doc-info {
            display: flex;
            align-items: start;
            gap: 12px;
        }

        .cp-doc-img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #f1f5f9;
        }

        .cp-doc-text h5 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #334155;
        }

        .cp-doc-spec {
            color: #0d9488;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            display: block;
            margin-top: 2px;
        }

        .cp-call-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #f0fdf9;
            color: #0d9488;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .cp-call-btn:hover {
            background: #ccfbf1;
        }

        .cp-actions {
            display: flex;
            gap: 10px;
        }

        .cp-btn-type {
            flex: 1;
            background: #d1fae5;
            color: #115e59;
            border: none;
            padding: 10px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: default;
        }

        .cp-btn-start {
            flex: 1;
            background: #f8fafc;
            color: #334155;
            border: 1px solid #e2e8f0;
            padding: 10px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
        }

        .cp-btn-start:hover {
            background: #e2e8f0;
            color: #1e293b;
        }
    </style>

    <div id="app" x-data="app">

        <div id="main-wrapper">
            <div class="col-md-12 ">
                <div class="panel panel-white">
                    <div class="panel-body">
 
                        <div x-show="loadingModal"> 
                            <p class="text-center">
                                <br><br> <img style="margin-top: 50px; height: 80px;" src="{{ asset('assets\images\carregandoFormulario.gif') }}"><br><br><br>
                            </p>
                        </div>

                        <div class="absolute-loading" x-show="loading" >
                            <div class="line">
                                <div class="loading"></div>
                                <span style="font-weight: bold; font-size: 1.3em; font-style: italic" x-html="'Carregando Informação...'"></span>
                            </div>
                        </div>


                        <div role="tabpanel">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-justified" role="tablist">

                                <li role="presentation" class="active"><a href="#tabConsultorio" role="tab"
                                        data-toggle="tab" id="buttonTabAgendamentos"><i class="fa fa-stethoscope"></i>
                                        Consultório</a></li>

                                <li role="presentation"><a href="#tabAtendimento" role="tab" data-toggle="tab"> <i
                                            class="fa fa-pencil-square-o"></i> Cadastrar Atendimento </a></li>

                                <li role="presentation"><a href="#tabPesquisa" role="tab" data-toggle="tab"> <i
                                            class="fa fa-search-plus"></i> Pesquisar Paciente </a></li>

                                <!-- Tab panes -->
                                <div class="tab-content">

                                    <div role="tabpanel" class="tab-pane active fade in" id="tabConsultorio">

                                        <div class="row">

                                            <form x-on:submit.prevent="getHorarios" id="form-horario" class="col-md-3"
                                                style="margin-bottom: 22px">

                                                <div id="calendar"></div>
                                                <input type="hidden" name="data" id="data-input" />
                                                <input type="hidden" name="tela" value="consultorio" />

                                                <hr />

                                                <label>Situação:</label>
                                                <div class="form-data" style="margin-bottom: 16px">
                                                    <select id="select-status" class="form-control"
                                                        style="width: 100%; z-index: 5000" name="status" required>
                                                        <option value="">Todos</option>
                                                        <option value="A">Abertos</option>
                                                        <option value="F">Finalizados</option>
                                                        <!--
                                                        @foreach($situacao as $val)
                                                            <option value="{{$val->cd_situacao}}">{!!$val->icone . ' ' . $val->nm_situacao!!}</option>
                                                        @endforeach 
                                                        -->        
                                                    </select>
                                                </div>

                                                <label>Ordenação:</label>
                                                <div class="form-data" style="margin-bottom: 16px">
                                                    <select id="select-ordenacao" class="form-control"
                                                        style="width: 100%; z-index: 5000" name="ordenacao" required> 
                                                        <option value="agenda">Por Ordem de Agendamento</option>
                                                        <option value="presenca">Por Ordem de Chegada</option>
                                                    </select>
                                                </div>


                                            </form>

                                            <div class="col-md-9"
                                                style="    padding-right: 0px;
                                            padding-left: 0px;">
                                                <template x-if="messageDanger">
                                                    <div class="alert alert-danger">
                                                        <span x-text="messageDanger"></span>
                                                        <button x-on:click="messageDanger = null" type="button"
                                                            class="close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                </template>


                                                <div class="row" style="margin-top: 10px; padding: 0 10px;">
                                                    <template x-for="(item, index) in horarios">
                                                        <div class="col-md-4 col-sm-6" style="padding: 0 10px;">
                                                            <div class="card-patient" style="padding: 20px; border-radius: 20px; background: #fff; border: 1px solid #eef2f6; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px;">
                                                                
                                                                <!-- Header: Initial + Name + Status -->
                                                                <div class="cp-header" style="display: flex; gap: 12px; align-items: flex-start; margin-bottom: 15px;">
                                                                    <div style="min-width: 45px; width: 45px; height: 45px; background-color: #f2fcf9; color: #1e293b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 600; border: 1px solid #f0f0f0;">
                                                                        <span x-text="item.paciente?.nm_paciente ? item.paciente?.nm_paciente.charAt(0) : '?'"></span>
                                                                    </div>
                                                                    <div style="flex: 1; overflow: hidden;">
                                                                        <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #334155; text-transform: uppercase; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" x-text="item.paciente?.nm_paciente"></h3>
                                                                        <div style="display: flex; align-items: center; gap: 5px; margin-top: 4px; font-size: 13px; color: #64748b; font-weight: 500;">
                                                                            <i class="fa fa-check-circle" style="color: #2AB09C; font-size: 14px;"></i>
                                                                            <span x-text="item.situacao?.nm_situacao"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Date & Time Separator -->
                                                                <div style="border-top: 1px solid #eef2f6; padding-top: 12px; margin-bottom: 15px;">
                                                                    <span style="color: #0d9488; font-size: 14px; font-weight: 600;" 
                                                                          x-text="(item.dt_agenda ? formatDateLong(item.dt_agenda) : '') + ' • ' + item.hr_agenda"></span>
                                                                </div>

                                                                <!-- Doctor Section -->
                                                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
                                                                    <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0;">
                                                                        <!-- Doctor Avatar -->
                                                                        <div style="position: relative; width: 48px; height: 48px; flex-shrink: 0;">
                                                                            <div style="width: 100%; height: 100%; border-radius: 50%; background-color: #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                                                <i class="fa fa-user-md" style="font-size: 20px; color: #94a3b8;"></i>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <!-- Doctor Text -->
                                                                        <div style="flex: 1; min-width: 0;">
                                                                            <h5 style="margin: 0; font-size: 14px; font-weight: 700; color: #334155; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" x-text="item.profissional?.nm_profissional"></h5>
                                                                            <span style="display: block; font-size: 10px; font-weight: 700; color: #0d9488; text-transform: uppercase; margin-top: 2px; letter-spacing: 0.5px;" 
                                                                                  x-text="item.especialidade?.nm_especialidade || 'CLÍNICO GERAL'"></span>
                                                                            <span style="display: block; font-size: 12px; color: #0d9488; margin-top: 2px;" 
                                                                                  x-text="item.celular || '(38) 99999-9999'"></span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Side Phone Button -->
                                                                    <a x-bind:href="'tel:' + item.celular" title="Ligar" 
                                                                       style="width: 42px; height: 42px; background-color: #f0fdf9; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0d9488; font-size: 16px; text-decoration: none; border: 1px solid #ccfbf1; flex-shrink: 0;">
                                                                        <i class="fa fa-phone"></i>
                                                                    </a>
                                                                </div>

                                                                <!-- Footer Buttons -->
                                                                <div style="display: flex; gap: 8px;">
                                                                    <!-- Tipo Atendimento Button -->
                                                                    <div style="flex: 1; background-color: #d1fae5; color: #115e59; padding: 10px 4px; border-radius: 9999px; text-align: center; display: flex; align-items: center; justify-content: center;">
                                                                        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 100%;" x-text="item.tipo_atend?.nm_tipo_atendimento || 'Atendimento'"></span>
                                                                    </div>
                                                                    
                                                                    <!-- Atendimento Action Button -->
                                                                    <button style="flex: 1; background-color: #fff; color: #334155; border: 1px solid #e2e8f0; padding: 10px 4px; border-radius: 9999px; text-align: center; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;"
                                                                            x-on:click="clickHorario(item,index)">
                                                                        Atendimento
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>

                                                <template x-if="horarios.length == 0 && !loading">
                                                    <p class="text-center" style="padding: 1.5em">Não há Atendimentos para
                                                        essa data
                                                    </p>
                                                </template>

                                            </div>
                                        </div>
                                    </div>

                                    <div role="tabpanel" class="tab-pane" id="tabPesquisa">

                                        <form x-on:submit.prevent="getPesquisaPac" id="form-pesquisa-pac" style="">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Data Inicial: <span class="red normal">*</span></label>
                                                        <input type="date" class="form-control required"
                                                            value="{{ old('dti') }}" name="dti" maxlength="100"
                                                            aria-required="true" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Data Final: <span class="red normal">*</span></label>
                                                        <input type="date" class="form-control required"
                                                            value="{{ old('dtf') }}" name="dtf" maxlength="100"
                                                            aria-required="true" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Nome do Paciente: <span class="red normal"></span></label>
                                                        <input type="text" class="form-control required"
                                                            value="{{ old('paciente') }}" name="paciente" maxlength="100"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Nome do Profissional: <span
                                                                class="red normal"></span></label>
                                                        <input type="text" class="form-control required"
                                                            value="{{ old('profissional') }}" name="profissional"
                                                            maxlength="100" aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="submit" style="font-size: 15px; margin-top: 22px;"
                                                        class="btn btn-success"><span class="glyphicon glyphicon-search"
                                                            aria-hidden="true"></span> </button>
                                                </div>
                                            </div>
                                        </form>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="active">
                                                    <th>Código</th>
                                                    <th>Data</th>
                                                    <th>Paciente</th>
                                                    <th>Profissional</th>
                                                    <th>Especialidade</th>
                                                    <th>Situação</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr x-show="loadingPesq">
                                                    <td colspan="6">
                                                        <div class="line">
                                                            <div class="loading"></div>
                                                            <span>Processando Informação...</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <template x-for="query in QueryPesqPac">
                                                    <tr style="cursor: pointer" x-on:click="clickPesquisaPac(query)">
                                                        <th>
                                                            <span x-text="query.cd_agendamento"></span>
                                                        </th>
                                                        <th>
                                                            <span x-text="query.dt_agenda"></span>
                                                        </th>
                                                        <td>
                                                            <span x-text="query.nm_paciente"></span>
                                                        </td>
                                                        <td>
                                                            <span x-text="query.nm_profissional"></span>
                                                        </td>
                                                        <td>
                                                            <span x-text="query.nm_especialidade"></span>
                                                        </td>
                                                        <td>
                                                            <span x-text="query.situacao" class="label"
                                                                x-bind:class="classLabelSituacao[query.situacao.toLocaleLowerCase()]">
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div role="tabpanel" class="tab-pane" id="tabAtendimento">

                                        <form x-on:submit.prevent="getPesquisaAtend" id="form-pesquisa-atend">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Nome do Paciente: <span class="red normal"></span></label>
                                                        <input type="text" class="form-control required"
                                                            value="" name="paciente" maxlength="100"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Nome da Mãe: <span class="red normal"></span></label>
                                                        <input type="text" class="form-control required"
                                                            value="" name="mae" maxlength="100"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Nome do Pai: <span class="red normal"></span></label>
                                                        <input type="text" class="form-control required"
                                                            value="" name="pai" maxlength="100"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Nascimento: <span class="red normal"></span></label>
                                                        <input type="date" class="form-control required"
                                                            value="" name="nasc" maxlength="100"
                                                            aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="submit" style="font-size: 15px; margin-top: 22px;"
                                                        class="btn btn-success"><span class="glyphicon glyphicon-search"
                                                            aria-hidden="true"></span> </button>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="{{ route('paciente.create') }}"
                                                        style="font-size: 15px; margin-top: 22px;"
                                                        class="btn btn-success"><span class="glyphicon glyphicon-user"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Cadastrar Paciente" aria-hidden="true"></span> </a>
                                                </div>
                                            </div>
                                        </form>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="active">
                                                    <th>Codigo</th>
                                                    <th>Paciente</th>
                                                    <th>Nascimento</th>
                                                    <th>Nome da Mãe</th>
                                                    <th>Nome do Pai</th>
                                                    <th>Atender</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr x-show="loadingPesqAtend">
                                                    <td colspan="6">
                                                        <div class="line">
                                                            <div class="loading"></div>
                                                            <span>Processando Informação...</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <template x-for="query in QueryPesqPacAtend">
                                                    <tr style="cursor: pointer" x-on:click="getPacAtend(query)">
                                                        <td x-text="query.cd_paciente"> </td>
                                                        <td x-text="query.nm_paciente">
                                                        </td>
                                                        <td x-text="formatDate(query.dt_nasc)"> </td>
                                                        <td x-text="query.nm_mae"> </td>
                                                        <td x-text="query.nm_pai"> </td>
                                                        <td data-toggle="modal" class="text-center"
                                                            data-target="#atendimento-pesq-pac"
                                                            style="padding: 3px!important;">
                                                            <button type="button"
                                                                style="padding: 2px 10px; font-size: 15px; "
                                                                class="btn btn-default btn-sm"><i
                                                                    class="fa fa-stethoscope text-success"
                                                                    style="font-weight: normal"></i></button>
                                                        </td>

                                                    </tr>
                                                </template>

                                            </tbody>

                                        </table>


                                    </div>

                                </div>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade " id="modalHistoricoLog" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header" style="padding-left: 8px; padding-right: 8px; padding-top: 8px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                        <h5 class="modal-title">   
                            <span style="font-style: italic"
                                x-html=" '{ ' + modalData.horario.cd_agendamento +' } &nbsp;&nbsp; ' + titleize(modalData.horario.paciente?.nm_paciente)"></span>
                        </h5>
                    </div>
                    <div class="modal-body" style="padding-left: 8px; padding-right: 8px;">

                        <table class="table ">
                            <thead >
                                <tr style="font-size: 11px;" class="active">
                                    <th>Usuário</th>
                                    <th>Situação</th>
                                    <th class="text-center">Data</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="log in modalData.logs">  
                                    <tr style="font-size: 11px;">
                                        <th scope="row" style="font-size: 11px;">
                                            <span x-html="log.tab_usuario?.nm_usuario"></span>
                                        </th>
                                        <td> 
                                            <span x-html="log.tab_situacao?.nm_situacao"></span>
                                        </td>
                                        <td class="text-center">
                                            <span x-html="log.data"></span>
                                        </td> 
                                    </tr>
                                </template> 
                            </tbody>
                        </table>
                  
                    </div>
             
                </div>
            </div>
        </div>

        <div class="modal fade " id="cadastro-consulta" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                        <h4 class="modal-title">
                            <template x-if="viewVip">
                                <i class="fa fa-star" x-on:click="vip('N')"
                                    style="color: #f8ac59;cursor: pointer;  "></i>
                            </template>
                            <template x-if="!viewVip">
                                <i class="fa fa-star-o" x-on:click="vip('S')" style=" cursor: pointer;  "></i>
                            </template>
                            <span style="font-style: italic"
                                x-html="titleize(modalData.horario.paciente?.nm_paciente)"></span>
                        </h4>
                    </div>
                    <div class="modal-body">

                        <div class="server-stat">
                            <span
                                x-html="` <b style='font-style: italic'>Nome Social:&nbsp;&nbsp; </b> ` + ( modalData.horario.paciente?.nome_social ? titleize(modalData.horario.paciente?.nome_social) : 'Não Informado' ) ">
                            </span><br>
                            <span
                                x-html="` <b style='font-style: italic'>idade:&nbsp;&nbsp; </b> ` + ( modalData.horario.paciente?.idade_paciente ? titleize(modalData.horario.paciente?.idade_paciente) : 'Não Informado' ) ">
                            </span><br>
                            <span
                                x-html="` <b style='font-style: italic'>Profissão:&nbsp;&nbsp; </b> ` + ( modalData.horario.paciente?.profissao ? titleize(modalData.horario.paciente?.profissao) : 'Não Informado' ) ">
                            </span><br> 
                            <span
                                x-html="` <b style='font-style: italic'>Convênio:&nbsp;&nbsp; </b> ` + ( modalData.horario.convenio?.nm_convenio ? titleize(modalData.horario.convenio?.nm_convenio) : 'Não Informado' ) ">
                            </span><br> 
                            <span
                                x-html="`  <b style='font-style: italic'>Profissional:&nbsp;&nbsp; </b> ` + ( modalData.horario.profissional?.nm_profissional ? titleize(modalData.horario.profissional?.nm_profissional) : 'Não Informado' ) ">
                            </span><br>  
                            <span
                                x-html="`  <b style='font-style: italic'>Tipo de atendimento: </b> ` + ( modalData.horario.tipo_atend?.nm_tipo_atendimento ? titleize(modalData.horario.tipo_atend?.nm_tipo_atendimento) : 'Não Informado' ) ">
                            </span> 
                            <hr>
 
                            <span
                            x-html="`  <b style='font-style: italic'>Data Atend.: </b> ` + ( modalData.horario.data_atendimento  ? titleize(modalData.horario.data_atendimento) : 'Não Informado' ) ">
                            </span><br>  
                            <span
                            x-html="`  <b style='font-style: italic'>Data Alta: </b> ` +( modalData.horario.data_finalizacao  ? titleize(modalData.horario.data_finalizacao) : 'Não Informado' ) ">
                            </span><br>  
                            <span
                            x-html="`  <b style='font-style: italic'>Usuario: </b> ` +( modalData.horario.user_finalizacao?.nm_usuario  ? titleize(modalData.horario.user_finalizacao?.nm_usuario) : 'Não Informado' ) ">
                            </span>
                        </div>

                    </div>
                    <div class="modal-footer" style="text-align: center;">
                        <template x-if="modalData.horario.tab_situacao.permite_atender == 'S'">
                            <template x-if="modalData.horario.cd_profissional == PROF_LOGADO"> 
                                <div> 
                                    <template x-if="modalData.horario.sn_finalizado=='S'">
                                        <a x-bind:href="`/rpclinica/consultorio-formularios/${modalData.horario.cd_agendamento}`"
                                        type="submit" class="btn btn-primary"><i class="fa fa-sign-in"
                                            style="margin-right: 10px;"></i> Reabrir Consulta</a> 
                                    </template>

                                    <template x-if="!modalData.horario.sn_finalizado">
                                        <a x-bind:href="`/rpclinica/consultorio-formularios/${modalData.horario.cd_agendamento}`"
                                        type="submit" class="btn btn-success"><i class="fa fa-stethoscope"
                                            style="margin-right: 10px;"></i> Iniciar Consulta</a> 
                                    </template>
                                </div> 
                            </template>
                        </template>

                        <template x-if="modalData.horario.tab_situacao.permite_atender == 'N'">
                            <code> A situação do atendimento não permite o profissional Iniciar a Consulta! </code>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="ModalAgendamento">
            <div class="modal" id="agendamento-pesq-pac">
                <div class="modal-dialog modal-lg">

                    <div class="absolute-loading" x-show="loadingPesqPac" >
                        <div class="line">
                            <div class="loading"></div>
                            <span style="font-weight: bold; font-size: 1.3em; font-style: italic" x-html="'Carregando Informação...'"></span>
                        </div>
                    </div>

                    <div class="modal-header">

                        <div class="line" style="justify-content: space-between">

                            <h4 class="modal-title"
                                x-html="'&nbsp;'+modalPac.atend.nm_paciente+' [ #'+ modalPac.atend.cd_agendamento+' ]  '  ">
                            </h4>

                            <div class="line">
                                <template x-if="modalPac.agendamento.permite_atender=='S'">
                                    <template x-if="profLogado == modalPac.agendamento.cd_profissional">
                                        <button type="button" class="btn btn-default btn-rounded" style="color: #22BAA0"
                                            x-on:click="reabrirAtend(modalPac.agendamento.cd_agendamento)">
                                            <i class="fa fa-stethoscope"></i> Reabrir Atendimento
                                        </button>
                                    </template>
                                </template>

                                <button type="button" class="close m-l-sm" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" style="padding-top: 20px;"><span aria-hidden="true"
                                            class="icon-close"></span></span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="modal-body ">

                        <div class="tab-content">

                            <div class="tab-pane active">

                                <h2 class="no-m m-b-lg" style="margin-bottom: 0px;"> <span aria-hidden="true"
                                        class="icon-user"></span> Dados do Paciente</h2>
                                <hr style="color: #333; margin-top: 10px;">

                                <ul class="list-paciente-info" style="gap: 1rem;">

                                    <li class="col-md-5">
                                        <strong>Nome do Paciente:</strong><br>
                                        <span x-text="modalPac.agendamento.nm_paciente" class="upper"></span>
                                    </li>

                                    <li class="col-md-3">
                                        <strong> Data de Nascimento:</strong><br>
                                        <span x-text="modalPac.agendamento.dt_nascimento" class="upper"></span>
                                    </li>

                                    <li class="col-md-3">
                                        <strong> Sexo:</strong><br>
                                        <span x-text="modalPac.agendamento.ds_sexo" class="upper"></span>
                                    </li>

                                    <li class="col-md-2">
                                        <strong>CPF do Paciente:</strong><br>
                                        <span x-text="modalPac.agendamento.cpf" class="upper"></span>
                                    </li>

                                    <li class="col-md-2">
                                        <strong>RG do Paciente:</strong><br>
                                        <span x-text="modalPac.agendamento.rg" class="upper"></span>
                                    </li>

                                    <li class="col-md-3">
                                        <strong>Celular do Paciente:</strong><br>
                                        <span x-text="modalPac.agendamento.celular" class="upper"></span>
                                    </li>

                                    <li class="col-md-4">
                                        <strong>Email do Paciente:</strong><br>
                                        <span x-text="modalPac.agendamento.email" class="upper"></span>
                                    </li>

                                </ul>
                                <br><br>

                                <h2 class="no-m m-b-lg" style="margin-bottom: 0px;"> <span aria-hidden="true"
                                        class="icon-docs"></span> Dados do Atendimento</h2>
                                <hr style="color: #333; margin-top: 10px;">

                                <ul class="list-paciente-info" style="gap: 1rem;">

                                    <li class="col-md-3">
                                        <strong>Codigo do Atendimento:</strong><br>
                                        <span x-text="modalPac.agendamento.cd_agendamento" class="upper"></span>
                                    </li>

                                    <li class="col-md-3">
                                        <strong> Data do Atendimento:</strong><br>
                                        <span x-text="modalPac.agendamento.dt_agendamento" class="upper"></span>
                                    </li>

                                    <li class="col-md-5">
                                        <strong> Profissional do Atendimento:</strong><br>
                                        <span x-text="modalPac.agendamento.nm_profissional" class="upper"></span>
                                    </li>

                                    <li class="col-md-4">
                                        <strong> Especialidade do Atendimento:</strong><br>
                                        <span x-text="modalPac.agendamento.nm_especialidade" class="upper"></span>
                                    </li>

                                    <li class="col-md-4">
                                        <strong> Local de Atendimento:</strong><br>
                                        <span x-text="modalPac.agendamento.nm_especialidade" class="upper"></span>
                                    </li>

                                    <li class="col-md-3">
                                        <strong> Tipo de Atendimento:</strong><br>
                                        <span x-text="modalPac.agendamento.tipo" class="upper"></span>
                                    </li>

                                    <li class="col-md-4">
                                        <strong> Descrição do Procedimento:</strong><br>
                                        <span x-text="modalPac.agendamento.nm_proc" class="upper"></span>
                                    </li>

                                    <li class="col-md-4">
                                        <strong> Convênio do Atendimento:</strong><br>
                                        <span x-text="modalPac.agendamento.nm_convenio" class="upper"></span>
                                    </li>

                                    <li class="col-md-3">
                                        <strong> Valor:</strong><br>
                                        <span x-text="modalPac.agendamento.valor" class="upper"></span>
                                    </li>
                                </ul>
                                <br><br>
                                <div class="row"> 
                                    <div class="col-md-8"> 
                                        <h2 class="no-m m-b-lg" style="margin-bottom: 0px;"> <span aria-hidden="true" class="icon-docs"></span> Anamnese</h2>
                                    </div>
                                    <div class="col-md-4" style="text-align: right">           
                                        <button type="button" class="btn btn-success m-b-xs"
                                            style="padding: 2px 12px; font-size: 15px; margin-bottom: 0px;"
                                            x-html="loading_btn === true ? `<span class='loader'></span>` : `<i class='fa fa-print' style='margin-right: 0px;''></i>`"
                                            x-on:click="imprimirAnamnese(modalPac.agendamento.cd_agendamento)">
                                        </button>
                                    </div>
                                </div>
                                 
                                <hr style="color: #333; margin-top: 10px;">
   
                                <ul class="list-paciente-info" style="gap: 1rem;">
                                    <li class="col-md-11">
                                        <strong>Anamnese:</strong><br>
                                        <span x-html="nl2br(modalPac.agendamento.anamnese)"></span>
                                    </li>

                                    <li class="col-md-11">
                                        <strong>Exame Fisico:</strong><br>
                                        <span x-html="nl2br(modalPac.agendamento.exame_fisico)"></span>
                                    </li>

                                    <li class="col-md-11">
                                        <strong>Hipótese Diagnóstica:</strong><br>
                                        <span x-html="nl2br(modalPac.agendamento.hipotese_diagnostica)"></span>
                                    </li>

                                    <li class="col-md-11">
                                        <strong>Conduta:</strong><br>
                                        <span x-html="modalPac.agendamento.conduta"></span>
                                    </li>

                                </ul>
                                <br><br>

                                <h2 class="no-m m-b-lg" style="margin-bottom: 0px;"> <span aria-hidden="true"
                                        class="icon-doc"></span> Documentos</h2>
                                <hr style="color: #333; margin-top: 10px;">

                                <table class="table table-hover">
                                    <thead>
                                        <tr class="active">
                                            <th>Codigo do Documento</th>
                                            <th>Descrição do Documento</th>
                                            <th>Data do Documento</th>
                                            <th class="text-center">Imprimir</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template x-for="query in modalPac.doc">
                                            <tr>
                                                <th>
                                                    <span x-text="query.cd_documento"></span>
                                                </th>
                                                <td>
                                                    <span x-text="query.formulario?.nm_formulario"></span>
                                                </td>
                                                <td>
                                                    <span x-text="query.dt_doc"></span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-success m-b-xs"
                                                        style="padding: 2px 12px; font-size: 15px;     margin-bottom: 0px;"
                                                        x-html="loading_btn && query.cd_documento === codDoc ? `<span class='loader'></span>` : `<i class='fa fa-print' style='margin-right: 0px;''></i>`"
                                                        x-on:click="imprimirDocumento(query.cd_documento,query.cd_agendamento)">
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>

                                </table>

                                <br><br>

                                <h2 class="no-m m-b-lg" style="margin-bottom: 0px;"> <span aria-hidden="true"
                                        class="icon-tag"></span> Exames</h2>
                                <hr style="color: #333; margin-top: 10px;">

                                <ul class="list-paciente-info" style="gap: 1rem;">
                                    <li class="col-md-11">
                                        <span x-html="modalPac.agendamento.historico_exames"></span>
                                    </li>
                                </ul>

                                <br><br>

                                <h2 class="no-m m-b-lg" style="margin-bottom: 0px;"> <span aria-hidden="true"
                                        class="icon-bag"></span> Anexos</h2>
                                <hr style="color: #333; margin-top: 10px;">

                                <table class="table table-hover">
                                    <thead>
                                        <tr class="active">
                                            <th>Codigo do Anexo</th>
                                            <th>Descrição do Anexo</th>
                                            <th>Data do Anexo</th>
                                            <th class="text-center">Visualizar</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <template x-for="query in modalPac.anexos">
                                            <tr>
                                                <th>
                                                    <span x-text="query.cd_anexo"></span>
                                                </th>
                                                <th>
                                                    <span x-text="query.nome"></span>
                                                </th>
                                                <th>
                                                    <span x-text="query.dt_anexo"></span>
                                                </th>
                                                <td class="text-center">
 
                                                    <a class="btn btn-success" x-bind:href="query.url_arquivo"
                                                        x-bind:download="query.nome"
                                                        style="padding: 2px 12px; font-size: 15px;     margin-bottom: 0px;">
                                                        <i class="fa fa-cloud-download" style="margin-right: 0px;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="atendimento-pesq-pac">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"><span aria-hidden="true" class="icon-note"></span> Gerar Atendimento</h4>
                    </div>

                    <div class="modal-body">

                        <form action="{{ route('consulta.atendimento') }}" method="post">
                            @csrf
                            <input type="hidden" name="paciente" x-bind:value="infoPac.cd_paciente">
                            <div class="row">

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Nome do Paciente: <span class="red normal"></span></label>
                                        <div class="form-control required" x-text="infoPac.nm_paciente"
                                            style="background-color: #eee;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data de Nascimento: <span class="red normal"></span></label>
                                        <div class="form-control required" x-text="formatDate(infoPac.dt_nasc)"
                                            style="background-color: #eee;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nome da Mãe: <span class="red normal"></span></label>
                                        <div class="form-control required" x-text="infoPac.nm_mae"
                                            style="background-color: #eee;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nome do Pai: <span class="red normal"></span></label>
                                        <div class="form-control required" x-text="infoPac.nm_pai"
                                            style="background-color: #eee;"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Convênio: <span class="red normal"></span></label>
                                        <div class="form-data" style="margin-bottom: 16px; width: 100%;">
                                            <select class="form-control" name="convenio" style="  width: 100%;">
                                                <option value=""  >SELECIONE</option>
                                                @foreach ($convenios as $val)
                                                    <option value="{{ $val->cd_convenio }}" @if($val->cd_convenio == $empresa->atend_convenio) selected @endif >
                                                        {{ mb_strtoupper($val->nm_convenio) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tipo de Atendimento: <span class="red normal"></span></label>
                                        <div class="form-data" style="margin-bottom: 16px; width: 100%;">
                                            <select class="form-control" name="tipo" style="  width: 100%;">
                                                <option value="">SELECIONE</option>
                                                @foreach ($tipoAtend as $val)
                                                    <option value="{{ $val->cd_tipo_atendimento }}" @if($val->cd_tipo_atendimento == $empresa->atend_tipo) selected @endif >
                                                        {{ mb_strtoupper($val->nm_tipo_atendimento) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Local de Atendimento: <span class="red normal"></span></label>
                                        <div class="form-data" style="margin-bottom: 16px; width: 100%;">
                                            <select class="form-control" name="local" style="  width: 100%;">
                                                <option value="">SELECIONE</option>
                                                @foreach ($localAtend as $val)
                                                    <option value="{{ $val->cd_local }}" @if($val->cd_local == $empresa->atend_local) selected @endif >
                                                        {{ mb_strtoupper($val->nm_local) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Especialidade: <span class="red normal"></span></label>
                                        <div class="form-data" style="margin-bottom: 16px; width: 100%;">
                                            <select class="form-control" name="especialidade" style="  width: 100%;">
                                                <option value="" name="agenda">SELECIONE</option>
                                                @foreach ($especialidades as $val)
                                                    <option value="{{ $val->cd_especialidade }}" @if($val->cd_especialidade == $empresa->atend_espec) selected @endif>
                                                        {{ mb_strtoupper($val->nm_especialidade) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="text-align: right">
                                    <button type="submit" style="font-size: 15px; margin-top: 22px;"
                                        class="btn btn-success"><i class="fa fa-stethoscope"></i> &nbsp;&nbsp;Gerar
                                        Atendimento </button>
                                </div>
                            </div>
                        </form>


                    </div>

                    <div class="modal-footer">
                        <template x-if="modalData.horario.sn_atende == 'S'">
                            <a x-bind:href="`/rpclinica/consulta/${modalData.horario.cd_agendamento}`" type="submit"
                                class="btn btn-success">Iniciar consulta</a>
                        </template>
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
    </style>

    <script>
        const profLogado = @js(auth()->guard('rpclinica')->user()->cd_profissional);
    </script>
    <script src="{{ asset('js/simple-calendar/simple-calendar.js') }}"></script>
    <script src="{{ asset('js/rpclinica/consultorio.js') }}"></script>
@endsection
