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
            border: 1px solid #ddd;
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
            background: #f1f3f5;
            border: 0;
        }

        .ModalAgendamento .modal-title {
            font-weight: 300;
            font-size: 2em;
            color: #444444;
            line-height: 30px;
        }

        .ModalAgendamento .modal-body {
            position: absolute;
            top: 50px;
            bottom: 0px;
            width: 100%;
            overflow: auto;
            background: #f1f3f5;
        }

        .ModalAgendamento .modal-footer {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            /*padding: 2px;*/
            background: #f1f3f5;
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
        <h3>Reserva de Cirúrgia</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('reserva-cirurgia.listar') }}">Relação</a></li>
            </ol>
        </div>
    </div>

    <!--
                                <div class="ms-toggler ms-settings-toggle ms-d-block-lg">
                                <i class="fa fa-stethoscope"></i>
                                </div>
                                -->

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

        #form-parametros label {
            margin-bottom: 0px;
        }

        #form-parametros .form-group {
            margin-bottom: 5px;
        }

        .label-aberto {
            background: #ffc107;
        }

        .label-fechado {
            background: #5cb85c;
        }

        .label-andamento {
            background: #12AFCB;
        }

        .label-fechado {
            background: #f25656;
        }

        .server-load .server-stat p {
            font-size: 14px;
        }

        .server-load .server-stat p {
            margin-bottom: 1px;
        }
    </style>

    <div id="app" x-data="app">
        <div id="main-wrapper" class="">
            <div class="col-md-12 " style="padding-right: 5px; padding-left: 5px;">
                <div class="panel panel-white">
                    <div class="panel-body">
                        <form x-on:submit.prevent="getPage" id="form-parametros" style="">
                            <div class="row">
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Data Inicial: <span class="red normal">*</span></label>
                                        <input type="date" class="form-control required" value="{{ old('dti') }}"
                                            name="dti" maxlength="100" aria-required="true" required>
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Data Final: <span class="red normal">*</span></label>
                                        <input type="date" class="form-control required" value="{{ old('dtf') }}"
                                            name="dtf" maxlength="100" aria-required="true" required>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Nome do Profissional: <span class="red normal"></span></label>
                                        <select class="form-control " name="cd_profissional">
                                            <option value="">SELECIONE</option>
                                            @foreach ($parametros['profissional'] as $key => $val)
                                                <option value="{{ $val->cd_profissional }}">{{ $val->nm_profissional }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Nome do Cirugião: <span class="red normal"></span></label>
                                        <select class="form-control " name="cd_cirurgiao">
                                            <option value="">SELECIONE</option>
                                            @foreach ($parametros['profissional'] as $key => $val)
                                                <option value="{{ $val->cd_profissional }}">{{ $val->nm_profissional }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Atendimento: <span class="red normal"></span></label>
                                        <input type="text" class="form-control required" value="{{ old('dtf') }}"
                                            name="atendimento">
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Nome do Paciente: <span class="red normal"></span></label>
                                        <input type="text" class="form-control required" value="{{ old('paciente') }}"
                                            name="paciente" maxlength="100" aria-required="true">
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Cirurgia: <span class="red normal"></span></label>
                                        <select class="form-control " name="cd_cirurgia">
                                            <option value="">SELECIONE</option>
                                            @foreach ($parametros['cirurgia'] as $key => $val)
                                                <option value="{{ $val->cd_exame }}">{{ $val->nm_exame }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>OPMEs: <span class="red normal"></span></label>
                                        <select class="form-control " name="cd_produto">
                                            <option value="">SELECIONE</option>
                                            @foreach ($parametros['opme'] as $key => $val)
                                                <option value="{{ $val->cd_produto }}">{{ $val->nm_produto }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Situação: <span class="red normal"></span></label>
                                        <select class="form-control " name="situacao">
                                            <option value="">SELECIONE</option>
                                            <option value="S">Solicitado</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1" style="padding-right: 5px; padding-left: 5px;">
                                    <button type="submit" style="font-size: 15px; margin-top: 17px; width: 100%;"
                                        class="btn btn-success" x-html="buttonSalvar"> </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-md-12" style="padding-right: 5px; padding-left: 5px;">

                                <table class="table table-hover table-striped"
                                    style="padding-right: 5px; padding-left: 5px;">
                                    <thead>
                                        <tr class="active">
                                            <th>Atendimento</th>
                                            <th>Data</th>
                                            <th>Paciente</th>
                                            <th>Solicitante</th>
                                            <th>Cirurgião</th>
                                            <th>Cirurgia</th>
                                            <th>Negociado</th>
                                            <th>Guia</th>
                                            <th>OPME</th>
                                            <th>Situação</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <!-- Loading Spinner -->
                                        <tr x-show="loadingPesq">
                                            <td colspan="8">
                                                <div class="line">
                                                    <div class="loading"></div>
                                                    <span>Processando Informação...</span>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Exibição dos dados paginados -->
                                        <template x-for="query in paginatedData">
                                            <tr x-show="!loadingPesq" style="cursor: pointer"
                                                x-on:click="clickModal(query)">
                                                <th>
                                                    <span x-html="query.cd_agendamento"></span>
                                                </th>
                                                <td>
                                                    <span x-html="moment(query.created_data).format('DD/MM/YYYY')"></span>
                                                </td>
                                                <td>
                                                    <span x-html="query.agendamento.paciente.nm_paciente"></span>
                                                </td>
                                                <td>
                                                    <span x-html="query.profissional.nm_profissional"></span>
                                                </td>
                                                <td>
                                                    <span x-html="query.cirurgiao.nm_profissional"></span>
                                                </td>
                                                <td>
                                                    <span x-html="query.cirurgia.nm_exame"></span>
                                                </td>
                                                <td>
                                                    <span x-html="query.negociado"></span>
                                                </td>
                                                <td>
                                                    <span x-html="(query.guia) ? query.guia : ' --'"></span>
                                                </td>
                                                <td>
                                                   <div x-html="(query.produto?.nm_produto) ? query.produto.nm_produto : ' -- ' "></div> 
                                                </td>
                                          
                                                <td style="width: 100px;" >
                                                    <span style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;"
                                                     class="label" x-html="situacaoReserva[query.situacao]" x-bind:class="classSituacaoReserva[query.situacao]">  </span>
                                                     
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>

                                <!-- Botões de Paginação -->
                                <div align="right">
                                    <button type="button" class="btn btn-default" @click="goToPage(1)"
                                        :disabled="currentPage === 1">
                                        <i class="fa fa-fast-backward"></i>
                                    </button>
                                    <button type="button" class="btn btn-default" @click="previousPage()"
                                        :disabled="currentPage === 1">
                                        <i class="fa fa-backward"></i>
                                    </button>
                                    <button type="button" class="btn btn-default" @click="nextPage()"
                                        :disabled="currentPage === totalPages">
                                        <i class="fa fa-forward"></i>
                                    </button>
                                    <button type="button" class="btn btn-default" @click="goToPage(totalPages)"
                                        :disabled="currentPage === totalPages">
                                        <i class="fa fa-fast-forward"></i>
                                    </button>
                                </div>

                            </div>
                        </div>


                        <div class="modal fade modalDetalhes" tabindex="-1" role="dialog"
                            aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close m-l-sm" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true" style="padding-top: 20px;"><span aria-hidden="true"
                                                    class="icon-close"></span></span>
                                        </button>
                                        <h4 class="modal-title" id="myLargeModalLabel"
                                            style="font-weight: 300; font-size: 16px; font-style: italic;"
                                            x-html="modalTitulo"></h4>
                                    </div>
                                    <div class="modal-body">

                                        <div role="tabpanel">
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#tabDadosReserva"
                                                        role="tab"
                                                        style="border-bottom: hidden; margin-right:0px; font-style: italic;"
                                                        data-toggle="tab">Dados da Reserva</a>
                                                </li>
                                                <li role="presentation"><a href="#tabAnotacoes" role="tab"
                                                        data-toggle="tab"
                                                        style="border-bottom: hidden; margin-right:0px; font-style: italic;">Anotações</a>
                                                </li>
                                                <li role="presentation"><a href="#tabFormulario" role="tab"
                                                    data-toggle="tab"
                                                    style="border-bottom: hidden; margin-right:0px; font-style: italic;">Formulário</a>
                                                </li>
                                            </ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content">

                                                <div role="tabpanel" class="tab-pane active" id="tabDadosReserva">
                                                    <br>
                                                    <div>
                                                        <div>

                                                            <table class="table table-striped"> 
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="padding: 1px !important;">
                                                                            <div class="row server-load">
                                                                                <div class="col-md-4 ">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Codigo da Reserva</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="infoModal.cd_reserva_cirurgia">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 ">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Data da Reserva</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="infoModal.created_data">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 ">
                                                                                    <div class="inbox-item server-stat "
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Atendimento</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="infoModal.cd_agendamento">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding: 1px !important;">
                                                                            <div class="row server-load">
                                                                                <div class="col-md-3">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Paciente</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px; font-weight: 900;"
                                                                                            x-html="infoModal.agendamento.paciente.cd_paciente">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Nome do Paciente</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px; font-weight: 900;"
                                                                                            x-html="infoModal.agendamento.paciente.nm_paciente">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding: 1px !important;">
                                                                            <div class="row server-load">
                                                                                <div class="col-md-6">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Nome da Mãe</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="( infoModal.agendamento.paciente.nm_mae ) ? infoModal.agendamento.paciente.nm_mae : ' -- ' ">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Nome do Pai</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="( infoModal.agendamento.paciente.nm_pai ) ? infoModal.agendamento.paciente.nm_pai : ' -- '">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding: 1px !important;">
                                                                            <div class="row server-load">
                                                                                <div class="col-md-3">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 5px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Data Nasc.:</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="(infoModal.agendamento.paciente.dt_nasc) ? FormatData(infoModal.agendamento.paciente.dt_nasc) : ' -- ' ">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            CPF:</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="( infoModal.agendamento.paciente.cpf ) ? infoModal.agendamento.paciente.cpf : ' -- ' ">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            RG:</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="( infoModal.agendamento.paciente.rg ) ? infoModal.agendamento.paciente.rg : ' -- ' ">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Cartão SUS:</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="( infoModal.agendamento.paciente.cartao_sus ) ? infoModal.agendamento.paciente.cartao_sus : ' -- ' ">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding: 1px !important;">
                                                                            <div class="row server-load">
                                                                                <div class="col-md-3">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Convênio:</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="(infoModal.agendamento.convenio.nm_convenio) ? infoModal.agendamento.convenio.nm_convenio : ' -- ' ">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Cartão:</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="( infoModal.agendamento.cartao ) ? infoModal.agendamento.cartao : ' -- ' ">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Telefone:</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="( infoModal.agendamento.paciente.fone ) ? infoModal.agendamento.paciente.fone : ' -- ' ">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Celular:</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="( infoModal.agendamento.paciente.celular ) ? infoModal.agendamento.paciente.celular : ' -- ' ">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding: 1px !important;">
                                                                            <div class="row server-load">
                                                                                <div class="col-md-6">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Solicitante</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="infoModal.profissional.nm_profissional">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Cirugião</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="infoModal.cirurgiao.nm_profissional">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding: 1px !important;">
                                                                            <div class="row server-load">
                                                                                <div class="col-md-9">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Cirurgia</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="infoModal.cirurgia.nm_exame">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <div class="inbox-item server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Situação</p>
                                                                                        <p class="inbox-item-text"
                                                                                            x-show="infoModal.situacao == 'A'"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;">
                                                                                            Aberta </p>
                                                                                        <p class="inbox-item-text"
                                                                                            x-show="infoModal.situacao == 'F'"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;">
                                                                                            Fechada </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding: 1px !important;">
                                                                            <div class="row server-load">
                                                                                <div class="col-md-12">
                                                                                    <div class="inbox-item  server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            Comentários</p>
                                                                                        <p class="inbox-item-text"
                                                                                            style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                            x-html="( infoModal.comentarios ) ? infoModal.comentarios : ' -- '">
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding: 1px !important;">
                                                                            <div class="row server-load">
                                                                                <div class="col-md-12">
                                                                                    <div class="inbox-item  server-stat"
                                                                                        style="padding: 1px 0;">
                                                                                        <p class="inbox-item-author"
                                                                                            style="line-height: 18px;">
                                                                                            OPMEs</p>
                                                                                        <template
                                                                                            x-for="item in infoModal.opme">
                                                                                            <p class="inbox-item-text server-stat"
                                                                                                style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;"
                                                                                                x-html="item.produtos.nm_produto">
                                                                                            </p>
                                                                                        </template>
                                                                                        <template
                                                                                            x-if="infoModal.opme == ''">
                                                                                            <p class="inbox-item-text server-stat"
                                                                                                style="line-height: 18px; color: #3a3939; font-size: 12px;font-weight: 900;">
                                                                                                --
                                                                                            </p>
                                                                                        </template>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>

                                                </div> 
                                                <div role="tabpanel" class="tab-pane" id="tabAnotacoes">

                                                    <form x-on:submit.prevent="addHistory" class="form-horizontal"
                                                        id="form_RESERVA_CIRURGIA" method="post">
                                                        <div class="form-group" style="margin-bottom: 5px;">
                                                            <div class="col-md-12">
                                                                <label for="input-placeholder" class=" control-label"
                                                                    style="  padding-top: 0px;">Comentário:</label>
                                                                <textarea rows="4" class="form-control" id="ds_historico" name="ds_historico"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="panel-footer">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <button type="submit" class="btn btn-success" x-html="buttonSalvarHist">  </button> 
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </form>
                                                    <br>

                                                    <template x-for="value in infoModal.notes">
                                                        <div class="panel panel-white">
                                                            <div class="panel-body">
                                                                <p x-html="nl2br(value.ds_historico)">

                                                                </p>
                                                                <div class="row">  
                                                                    <div class="col-md-6">
                                                                        <p> <b> Usuario : </b> <span
                                                                                x-html="value.nm_usuario"></span> </p>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <p> <b> Data : </b> <span
                                                                                x-html="value.created_data"></span> </p>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </template>


                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="tabFormulario">
                                               
                                                    <form x-on:submit.prevent="saveFormulario" class="form-horizontal"
                                                        id="form_FORMULARIO" method="post">

                                                        <div class="row">
                       
                                                            <div class="form-group" >
                                                                <div class="col-md-6"> 
                                                                    <label for="fname">Convênio: <span class="red normal"></span></label> 
                                                                    <select  style="  width: 100%" name="convenio" id="idConvenio" >
                                                                        <option value="">Selecione</option>
                                                                        @foreach($parametros['convenio'] as $key => $val)
                                                                            <option value="{{ $val->cd_convenio }}">{{ $val->nm_convenio }}</option> 
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3"> 
                                                                    <label for="fname">Data Solicitação: <span class="red normal"></span></label>
                                                                    <input type="date" class="form-control"  x-model="formsData.dt_solicitacao"> 
                                                                </div>
                                                                <div class="col-md-3"> 
                                                                    <label for="fname">Senha: <span class="red normal"></span></label>
                                                                    <input type="text" class="form-control"  x-model="formsData.guia"> 
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group" >
                                                                <div class="col-md-3"> 
                                                                    <label for="fname">Data autorização: <span class="red normal"></span></label>
                                                                    <input type="date" class="form-control"  x-model="formsData.dt_autorizacao"> 
                                                                </div>
                                                                <div class="col-md-3"> 
                                                                    <label for="fname">Negociado: <span class="red normal"></span></label>
                                                                    <select  id="idNegociado"   style=" width: 100%" >
                                                                        <option value="">Selecione</option>
                                                                        <option value="S">Sim</option>
                                                                        <option value="N">Não</option> 
                                                                    </select>
                                                                </div> 
                                                                <div class="col-md-3"> 
                                                                    <label for="fname">Valor Negociado: <span class="red normal"></span></label>
                                                                    <input type="text" class="form-control text-right" x-mask:dynamic="$money($input, ',')" x-model="formsData.valor"> 
                                                                </div>
                                                                
                                                                <div class="col-md-3"> 
                                                                    <label for="fname">Status: <span class="red normal"></span></label>
                                                                    <select  id="idSituacao"   style=" width: 100%" >
                                                                        <option value="A">Aberta</option>
                                                                        <option value="S">Solicitada</option>
                                                                        <option value="U">Autorizada</option>
                                                                        <option value="N">Negada</option>
                                                                    </select>
                                                                </div> 
                                                            </div> 
                                                            <div class="form-group" style="margin-bottom: 5px;"> 
                                                                <div class="col-md-3"> 
                                                                    <label for="fname">Codigo de Agendamento: <span class="red normal"></span></label>
                                                                    <input type="text" class="form-control"  x-model="formsData.agendamento"> 
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <label for="input-placeholder" class=" control-label"
                                                                        style="  padding-top: 0px;">OPME:</label>
                                                                        <select  id="idOpme"   class=" form-control  "  style="  width: 100%"  aria-hidden="true">
                                                                            <option value="">Selecione</option>
                                                                            <template x-for="item in infoModal.opme">
                                                                                <option :value="item.cd_produto" x-text="item.produtos.nm_produto"></option> 
                                                                            </template>
                                                                        </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group" style="margin-bottom: 5px;">
                                                                <div class="col-md-12">
                                                                    <label for="input-placeholder" class=" control-label"
                                                                        style="  padding-top: 0px;">Comentário:</label>
                                                                    <textarea rows="4" class="form-control" x-model="formsData.obs"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group" style="margin-bottom: 5px;">
                                                                <div class="col-md-12">
                                                                    <div class="panel-footer">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <button type="submit" class="btn btn-success" x-html="buttonSalvarForm">  </button> 
                                                                            </div>
            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </form>
                                                    

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
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
    </style>

    <script>
        const profLogado = @js(auth()->guard('rpclinica')->user()->cd_profissional);
    </script>
    <script src="{{ asset('js/simple-calendar/simple-calendar.js') }}"></script>
    <script src="{{ asset('js/rpclinica/reserva-cirurgia.js') }}"></script>
@endsection
