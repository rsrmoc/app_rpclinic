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
        <div class="row">
            <div class="col-md-8" >
                <h3>Painel de Cirurgias</h3>
                <div class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('cirurgias.listar') }}">Relação</a></li>
                    </ol>
                </div>
            </div>


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

        #form-pesquisa-pac label {
            margin-bottom: 0px;
        }

        #form-pesquisa-pac .form-group {
            margin-bottom: 5px;
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
                                        <input type="date" class="form-control required" name="dti" maxlength="10"
                                            aria-required="true" required>
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Data Final: <span class="red normal">*</span></label>
                                        <input type="date" class="form-control required" name="dtf" maxlength="10"
                                            aria-required="true" required>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Nome do Paciente: <span class="red normal"></span></label>
                                        <input type="text" class="form-control required" name="paciente" maxlength="100"
                                            aria-required="true">
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Atendimento: <span class="red normal"></span></label>
                                        <input type="text" class="form-control required" name="atendimento"
                                            maxlength="20" aria-required="true">
                                    </div>
                                </div>

                                <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Cirurgia: <span class="red normal"></span></label>
                                        <select class="form-control " name="cd_exame">
                                            <option value="">SELECIONE</option>
                                            @foreach ($parametros['exame'] as $key => $val)
                                                <option value="{{ $val->cd_exame }}">{{ $val->nm_exame }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Profissional Solicitante: <span class="red normal"></span></label>
                                        <select class="form-control " name="cd_solicitante">
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
                                        <label>Profissional Executante: <span class="red normal"></span></label>
                                        <select class="form-control " name="cd_executante">
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
                                        <label>Convênio: <span class="red normal"></span></label>
                                        <select class="form-control " name="cd_convenio">
                                            <option value="">SELECIONE</option>
                                            @foreach ($parametros['convenio'] as $key => $val)
                                                <option value="{{ $val->cd_convenio }}">{{ $val->nm_convenio }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Situação: <span class="red normal"></span></label>
                                        <select class="form-control " name="situacao">
                                            <option value="">SELECIONE</option>
                                            @foreach ($parametros['situacao'] as $key => $val)
                                                <option value="{{ $val->cd_situacao_itens }}">{{ $val->nm_situacao_itens }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1" style="padding-right: 5px; padding-left: 5px;">
                                    <button type="submit" style="font-size: 15px; margin-top: 23px; width: 100%;"
                                        class="btn btn-success" x-html="buttonPesquisar"> </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-md-12" style="padding-right: 5px; padding-left: 5px;">
                                <table class="table table-hover" style="padding-right: 5px; padding-left: 5px;">
                                    <thead>
                                        <tr class="active">
                                            <th>Atendimento</th>
                                            <th>Data</th>
                                            <th>Paciente</th>
                                            <th>Convênio</th>
                                            <th>Solicitante</th>
                                            <th>Executante</th>
                                            <th>Local de Atendimento</th>
                                            <th>Exames</th>
                                            <th>Situação</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr x-show="loadingPesq">
                                            <td colspan="8">
                                                <div class="line">
                                                    <div class="loading"></div>
                                                    <span>Processando Informação...</span>
                                                </div>
                                            </td>
                                        </tr>

                                        <template x-if="retornoLista">

                                            <template x-for=" (query, index)  in paginatedData">
                                                <tr x-show="!loadingPesq" style="cursor: pointer"
                                                    x-on:click="clickModal(query,query)">
                                                    <th>
                                                        <span x-html="query.cd_agendamento"></span>
                                                    </th>
                                                    <td>
                                                        <span x-html="query.created_data"></span>
                                                    </td>
                                                    <td>
                                                        <span x-html="query.atendimento?.paciente?.nm_paciente"></span>
                                                    </td>
                                                    <td>
                                                        <span x-html="query.atendimento?.convenio?.nm_convenio"></span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            x-html="query.atendimento?.profissional?.nm_profissional"></span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            x-html="query.atendimento?.profissional?.nm_profissional"></span>
                                                    </td>
                                                    <td>
                                                        <span x-html="query.atendimento?.local?.nm_local"></span>
                                                    </td>
                                                    <td>
                                                        <span x-html="query.exame?.nm_exame"></span>
                                                    </td>
                                                    <td style="width: 100px;  ">

                                                        <span class="label"
                                                            style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;"
                                                            x-html="situacaoExame[query.situacao]"
                                                            x-bind:class="classSituacaoExame[query.situacao]">

                                                    </td>
                                                </tr>
                                            </template>

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

                        <div class="ModalAgendamento">
                            <div class="modal fade modalDetalhes">
                                <div class="modal-dialog modal-fullscreen">

                                    <div class="modal-content">
                                        <div class="modal-header m-b-sm">
                                            <div class="line" style="justify-content: space-between">
                                                <h4 class="modal-title"
                                                    x-html="infoModal.atendimento.paciente.nm_paciente+' { '+infoModal.cd_agendamento_item + ' }'">
                                                </h4>
                                                <div class="line">
                                                    <button type="button" class="close m-l-sm" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true" style="padding-top: 20px;"><span
                                                                aria-hidden="true" class="icon-close"></span></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <div class="tabpanel">
                                                <ul class="nav nav-tabs nav-justified" role="tablist">
                                                    <li role="presentation" class="active">
                                                        <a href="#TabLaudo" role="tab" data-toggle="tab"
                                                            style="padding: 3px 15px; border-left: 0px;">
                                                            <i class="fa fa-edit" style="margin-right: 10px;"></i> Laudo
                                                            do Exame </a>
                                                    </li>

                                                    <li role="presentation" class="">
                                                        <a href="#TabImg" role="tab" data-toggle="tab"
                                                            style="padding: 3px 15px;">
                                                            <i class="fa fa-file-image-o" style="margin-right: 10px;"></i>
                                                            Imagens do Exame</a>
                                                    </li>

                                                    <li role="presentation" class="">
                                                        <a href="#TabAnotacoes" role="tab" data-toggle="tab"
                                                            style="padding: 3px 15px;">
                                                            <i class="fa fa-file-text-o" style="margin-right: 10px;"></i>
                                                            Anotações</a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content">
                                                    <div class="tab-pane active" role="tabpanel" id="TabLaudo">
                                                        <br>
                                                        <div class="slimScrollDiv"
                                                            style="position: relative; overflow: hidden; width: auto;  ">
                                                            <div class="inbox-widget slimscroll"
                                                                style="overflow: hidden; width: auto; height: auto !important;">
                                                                <a href="#">
                                                                    <div class="row">
                                                                        <div class="col-md-2">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Atendimento</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="infoModal.cd_agendamento">
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Data do Atendimento</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="infoModal.atendimento.data_horario">
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Data do Exame</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="infoModal.atendimento.dt_agenda">
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Local do Atendimento</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="infoModal.atendimento.local.nm_local">
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Exame</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="infoModal.exame.nm_exame"> </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                                <a href="#">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Solicitante</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="infoModal.atendimento.profissional.nm_profissional">
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Executante</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="infoModal.atendimento.profissional.nm_profissional">
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Convênio</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="infoModal.atendimento.convenio.nm_convenio">
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Situação</p>
                                                                                <p class="inbox-item-text"
                                                                                    x-show="infoModal.situacao == 'A'"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;">
                                                                                    Aguardando </p>
                                                                                <p class="inbox-item-text"
                                                                                    x-show="infoModal.situacao == 'E'"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;">
                                                                                    Executado </p>
                                                                                <p class="inbox-item-text"
                                                                                    x-show="infoModal.situacao == 'R'"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;">
                                                                                    Realizado </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <form id="form_LAUDO" style=""
                                                            x-on:submit.prevent="submitLaudo">
                                                            <div class="row" x-show="!infoModal.sn_laudo">
                                                                <div class="col-md-10 col-md-offset-1"
                                                                    style="margin-top: 30px;">
                                                                    <div class="form-group">
                                                                        <label>Texto Padrão: <span
                                                                                class="red normal"></span></label>
                                                                        <select class="form-control " name="texto_padrao"
                                                                            id="texto_padrao" style="width: 100%;">
                                                                            <option value="">Selecione o Texto Padrão
                                                                            </option>
                                                                            <template x-if="textoPadrao">
                                                                                <template x-for="query in textoPadrao">
                                                                                    <option :value="query.ds_texto_padrao"
                                                                                        x-text="query.titulo"></option>
                                                                                </template>
                                                                            </template>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="row">

                                                                <div class="col-md-10 col-md-offset-1"
                                                                    style="margin-top: 20px;">

                                                                    <div class="form-group">
                                                                        <textarea id="conteudo" style="height: 400px;" class="form-control" name="conteudo_laudo"
                                                                            x-model="infoModal.conteudo_laudo"></textarea>
                                                                    </div>

                                                                    <div class="panel-footer">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <button type="submit"
                                                                                    class="btn btn-success"
                                                                                    x-show="!infoModal.sn_laudo"
                                                                                    x-html="buttonSalvarLaudo"> 
                                                                                </button>

                                                                                <button type="button"
                                                                                    class="btn btn-info"
                                                                                    x-show="infoModal.sn_laudo == true"><i
                                                                                        class="fa fa-print"></i>
                                                                                    Imprimir Laudo </button>
                                                                            </div>
                                                                            <div class="col-md-6 right">
                                                                                <div class="checkbox m-r-md"
                                                                                    x-show="infoModal.sn_laudo != 'E'"
                                                                                    x-on:change="liberarLaudo()"
                                                                                    style="margin-top: 0; margin-bottom: 0">
                                                                                    <label id="label_laudo_checkbox"
                                                                                        style="padding: 0">
                                                                                        <input id="sn_laudo_checkbox"
                                                                                            type="checkbox"
                                                                                            name="sn_laudo" />
                                                                                        Liberar Laudo?
                                                                                    </label>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <div class="tab-pane" role="tabpanel" id="TabImg"
                                                        style="text-align: center;">

                                                        <template x-for="item in infoModal.array_img">
                                                            <div class="panel panel-white">
                                                                <div class="panel-heading"
                                                                    style="padding: 12px; height: 60px;">
                                                                    <h3 class="panel-title"style="width: 100%;">
                                                                        <table width="100%">
                                                                            <tr>
                                                                                <td width="50%"
                                                                                    style="text-align: left">
                                                                                    <span
                                                                                        style=" margin-right: 15px; font-style: italic"
                                                                                        x-text="item.usuario"></span>
                                                                                    <br>
                                                                                    <span
                                                                                        style=" margin-right: 15px;font-weight: 300;"
                                                                                        x-text="item.data"> </span>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </h3>
                                                                </div>
                                                                <div class="panel-body" style="text-align: center"
                                                                    style="min-height: 300px;max-height: 500px">
                                                                    <img class="img-fluid" style="max-width: 100%;"
                                                                        x-bind:src="item.conteudo_img" />
                                                                </div>
                                                            </div>
                                                        </template>
                                                        <template x-if="(!infoModal.array_img)">
                                                            <div><img class="img-fluid" style="max-width: 100%;"
                                                                    src="{{ asset('assets\images\sem_img.jpeg') }}">
                                                            </div>
                                                        </template>

                                                    </div>

                                                    <div role="tabpanel" class="tab-pane" id="TabAnotacoes">

                                                        <form x-on:submit.prevent="addHistory" class="form-horizontal"
                                                            id="form_RESERVA_CIRURGIA" method="post">
                                                            <div class="form-group" style="margin-bottom: 5px;">
                                                                <div class="col-md-12">
                                                                    <label for="input-placeholder" class=" control-label"
                                                                        style="  padding-top: 0px;">Comentário:</label>
                                                                    <textarea rows="4" class="form-control" name="ds_historico" id="ds_historico"></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="panel-footer">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <button type="submit" class="btn btn-success" x-html="buttonSalvarAnot">  </button>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </form>
                                                        <br>

                                                        <template x-for="value in infoModal.notes">
                                                            <div class="panel panel-white">
                                                                <div class="panel-body">
                                                                    <p x-html="value.ds_historico">

                                                                    </p>
                                                                    <div class="row">

                                                                        <div class="col-md-6">
                                                                            <p> <b> Usuario : </b> <span
                                                                                    x-html="value.nm_usuario"></span> </p>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <p> <b> Data : </b> <span
                                                                                    x-html="value.created_data"></span>
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>

                                                </div>
                                            </div>
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
        <script src="{{ asset('js/rpclinica/cirurgias.js') }}"></script>
    @endsection
