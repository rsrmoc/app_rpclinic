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
            <div class="col-md-8">
                <h3>Central de Laudos</h3>
                <div class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('consultorio') }}">Relação</a></li>
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
                        <form x-on:submit.prevent="getSearch" id="form-parametros" style="">
                            <input type="hidden" name="order" x-model="order" value="">
                            <input type="hidden" name="orderOUT" x-model="orderOUT" value=""> 
                            <div class="row">
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Data Inicial: <span class="red normal">*</span></label>
                                        <input type="date" class="form-control required" name="dti" maxlength="10"
                                            aria-required="true" value="{{$parametros['dti']}}" required>
                                    </div>
                                </div>
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Data Final: <span class="red normal">*</span></label>
                                        <input type="date" class="form-control required" name="dtf" maxlength="10"
                                            aria-required="true" value="{{$parametros['dtf']}}" required>
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
                                        <label>Exame: <span class="red normal"></span></label>
                                        <select class="form-control " name="cd_exame">
                                            <option value="">Todos</option>
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
                                            <option value="">Todos</option>
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
                                            
                                            @if($parametros['prof_unico']=='N')
                                                <option value="">Todos</option>
                                            @endif

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
                                            <option value="">Todos</option>
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
                                            <option value="">Todos</option>
                                            @foreach ($parametros['situacao'] as $key => $val)
                                                <option value="{{ $val->cd_situacao_itens }}" @if($val->set_select=='S') selected @endif  >
                                                    {{ $val->nm_situacao_itens }}</option>
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
                                            <th style="cursor: pointer;" x-on:click="orderBy('at');"><i class="fa fa-sort" style="margin-right: 2px;" aria-hidden="true"></i>  Atendimento</th>
                                            <th style="cursor: pointer;" x-on:click="orderBy('dt');"> <i class="fa fa-sort" style="margin-right: 2px;" aria-hidden="true"></i> Data</th>
                                            <th>Paciente</th>
                                            <th>Convênio</th>
                                            <th>Solicitante</th>
                                            <th>Executante</th>
                                            <th>Exame</th>
                                            <th>Situação</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr x-show="loadingPesq">
                                            <td colspan="9">
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
                                                        <span x-html="query.atendimento.data_agenda"></span>
                                                    </td>
                                                    <td>
                                                        <span x-html="query.atendimento?.paciente?.nm_paciente"></span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            x-html="(query.atendimento?.convenio) ? query.atendimento?.convenio?.nm_convenio : ''"></span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            x-html="(query.atendimento?.profissional) ? query.atendimento?.profissional?.nm_profissional : ''"></span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            x-html="(query.atendimento?.profissional) ? query.atendimento?.profissional?.nm_profissional : ''"></span>
                                                    </td>

                                                    <td>
                                                        <span x-html="query.exame?.nm_exame"></span>
                                                    </td>
                                                    <td style="width: 100px;  ">

                                                        <span class="label"
                                                            style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;"
                                                            x-html="query.situacao_laudo.html"
                                                            x-bind:class="query.situacao_laudo.classe">

                                                    </td>
                                                </tr>
                                            </template>

                                        </template>

                                    </tbody>
                                </table>

                                <!-- Botões de Paginação -->
                                <div align="right">

                                    <ul class="pagination" style="border-radius: 4px; margin-bottom: 0px;">
                                        <li>
                                            <button type="button" class="btn btn-default" @click="goToPage(1)"
                                                style="border-radius: 4px 0px 0px 4px;" :disabled="currentPage === 1">
                                                <i class="fa fa-angle-double-left"></i>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-default" @click="previousPage()"
                                                :disabled="currentPage === 1">
                                                <i class="fa fa-angle-left"></i>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-default" @click="nextPage()"
                                                :disabled="currentPage === totalPages">
                                                <i class="fa fa-angle-right"></i>

                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-default" @click="goToPage(totalPages)"
                                                style="border-radius: 0px 4px 4px 0px;"
                                                :disabled="currentPage === totalPages">
                                                <i class="fa fa-angle-double-right"></i>
                                            </button>
                                        </li>

                                    </ul><br>
                                    <span style="font-style: italic"
                                        x-html="'<strong>Pagina </strong> ' + currentPage + ' <strong> de </strong> ' + totalPages">
                                    </span> <br>
                                    <span style="font-style: italic"
                                        x-html="'<strong>Total de Linhas</strong> ' + totalLinhas "> </span>

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
                                                    x-html="infoModal.atendimento.paciente.nm_paciente+' { Atend.: '+infoModal.cd_agendamento+ ' } '">
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
                                            <div class="absolute-loading" style="display: none">
                                                <div class="line">
                                                    <div class="loading"></div>
                                                    <span style="font-weight: bold; font-size: 1.3em; font-style: italic"
                                                        x-html="loadingAcao"></span>
                                                </div>
                                            </div>

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
                                                            Imagens do Exame
                                                            <span style="margin-left: 10px;" x-show="(nrImg > 0)"
                                                                x-text="nrImg" class="badge badge-success">8</span>
                                                        </a>
                                                    </li>

                                                    <li role="presentation" class="">
                                                        <a href="#TabAnotacoes" role="tab" data-toggle="tab"
                                                            style="padding: 3px 15px;">
                                                            <i class="fa fa-file-text-o" style="margin-right: 10px;"></i>
                                                            Anotações
                                                            <span style="margin-left: 10px;" x-show="(nrAnotacao > 0)"
                                                                x-text="nrAnotacao" class="badge badge-success"></span>
                                                        </a>
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
                                                                                    Codigo do Item</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="iconeKey + ' ' +infoModal.cd_agendamento_item"> </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Exame</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="icondeFile + ' ' + infoModal.exame.nm_exame"> </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Qtde.</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="(infoModal.qtde) ? infoModal.qtde : '--'">
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-2">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Olho</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html=" iconeOlho + ' ' + ( (infoModal.olho) ? infoModal.olho : '--' ) ">
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Observação</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="(infoModal.obs_exame) ? infoModal.obs_exame : ' -- '">
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">

                                                                        <div class="col-md-2">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Data do Atendimento</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html=" icondeCalendar + ' ' + infoModal.atendimento.data_atendimento">
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Idade</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="iconeIdade + ' ' + infoModal.atendimento?.paciente?.idade_paciente">
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Profissão</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="(infoModal.atendimento?.paciente?.profissao) ? infoModal.atendimento?.paciente?.profissao : ' -- '">
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
                                                                                    x-html="(infoModal.atendimento.local?.nm_local) ? infoModal.atendimento.local?.nm_local : ' --'">
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="inbox-item"
                                                                                style="padding: 5px 0;">
                                                                                <p class="inbox-item-author"
                                                                                    style="line-height: 18px;">
                                                                                    Tipo de Atendimento</p>
                                                                                <p class="inbox-item-text"
                                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                                    x-html="' --'">
                                                                                </p>
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
                                                                                    x-html="iconeSolic + ' ' + infoModal.atendimento.profissional.nm_profissional">
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
                                                                                    x-html=" iconeProf + ' ' + infoModal.atendimento.profissional.nm_profissional">
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
                                                         

                                                        <!-- Laudo Fechado -->
                                                        <div class="panel-footer" x-show="!snLaudo">
                                                            <div class="row">

                                                                <div class="col-md-6">

                                                                    <a style="  font-weight: bold;padding: 4px 10px;"
                                                                        class="btn btn-success panel-reload"
                                                                        x-on:click="copyText()"
                                                                        x-show="infoModal.sn_laudo == true">
                                                                        <i class="fa fa-copy"></i>
                                                                    </a>

                                                                    <a style="  font-weight: bold;padding: 4px 10px;"
                                                                        class="btn btn-success" x-on:click="copyTextZap()"
                                                                        x-show="infoModal.sn_laudo == true">
                                                                        <i class="fa fa-whatsapp"></i>
                                                                    </a>

                                                                    <a target="_blank"
                                                                        x-bind:href="'/rpclinica/central-laudos-documento/' +
                                                                        infoModal
                                                                            .cd_agendamento_item"
                                                                        style="  font-weight: bold;padding: 4px 10px;"
                                                                        class="btn btn-info"
                                                                        x-show="infoModal.sn_laudo == true">
                                                                        <i class="fa fa-print"></i>
                                                                        Imprimir Laudo
                                                                    </a>
                                                                </div>
                                                                <div class="col-md-6">
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <!-- Laudo Aberto -->
                                                        <div class="row" x-show="snLaudo">

                                                            <div class="col-md-9">

                                                                <form id="form_LAUDO" x-on:submit.prevent="submitLaudo">
                                                                    <div class="row" x-show="!infoModal.sn_laudo">
                                                                        <div class="col-md-12" style="margin-top: 30px;">
                                                                            <div class="form-group">
                                                                                <label>Texto Padrão: <span
                                                                                        class="red normal"></span></label>
                                                                                <select class="form-control "
                                                                                    name="texto_padrao" id="texto_padrao"
                                                                                    style="width: 100%;">
                                                                                    <option value="">Selecione o
                                                                                        Texto Padrão
                                                                                    </option>
                                                                                    <template x-if="textoPadrao">
                                                                                        <template
                                                                                            x-for="query in textoPadrao">
                                                                                            <option :value="query.cd_exame_formulario"
                                                                                                x-text="query.nm_formulario">
                                                                                            </option>
                                                                                        </template>
                                                                                    </template>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 " style="margin-top: 20px;">

                                                                            <div class="form-group">
                                                                                <textarea id="conteudo" style="height: 400px;" class="form-control" name="conteudo_laudo"
                                                                                    x-model="infoModal.conteudo_laudo"></textarea>
                                                                            </div>

                                                                            <div class="panel-footer">
                                                                                <div class="row">
                                                                                    <div class="col-md-7 line">

                                                                                        <button type="button"
                                                                                            class="btn btn-default m-b-xs"
                                                                                            x-on:click="popup(infoModal.cd_agendamento_item)">
                                                                                            <i class="fa fa-desktop"></i>
                                                                                        </button>

                                                                                        <button type="submit"
                                                                                            class="btn btn-success"
                                                                                            x-show="!infoModal.sn_laudo"
                                                                                            x-html="buttonSalvarLaudo">
                                                                                        </button>

                                                                                        <a target="_blank"
                                                                                            x-bind:href="'/rpclinica/central-laudos-documento/' +
                                                                                            infoModal
                                                                                                .cd_agendamento_item"
                                                                                            style="  font-weight: bold; "
                                                                                            class="btn btn-primary "
                                                                                            x-show="infoModal.sn_laudo == false">
                                                                                            <i class="fa fa-eye"></i>
                                                                                            Visualizar
                                                                                        </a>

                                                                                        <a style="  font-weight: bold;padding: 4px 10px;"
                                                                                            class="btn btn-success"
                                                                                            x-on:click="copyText()"
                                                                                            x-show="infoModal.sn_laudo == true">
                                                                                            <i class="fa fa-copy"></i>
                                                                                        </a>

                                                                                        <a style="  font-weight: bold;padding: 4px 10px;"
                                                                                            class="btn btn-success"
                                                                                            x-on:click="copyTextZap()"
                                                                                            x-show="infoModal.sn_laudo == true">
                                                                                            <i class="fa fa-whatsapp"></i>
                                                                                        </a>

                                                                                        <a target="_blank"
                                                                                            x-bind:href="'/rpclinica/central-laudos-documento/' +
                                                                                            infoModal
                                                                                                .cd_agendamento_item"
                                                                                            style="  font-weight: bold;padding: 4px 10px;"
                                                                                            class="btn btn-info"
                                                                                            x-show="infoModal.sn_laudo == true">
                                                                                            <i class="fa fa-print"></i>
                                                                                            Imprimir
                                                                                        </a>

                                                                                    </div>

                                                                                    <div class="col-md-5 right"
                                                                                        style="text-align: right">
                                                                                        <div class="  right"
                                                                                            style="text-align: right">

                                                                                            <button type="button"
                                                                                                class="btn btn-default btn-rounded"
                                                                                                style="color: #12AFCB; margin-right: 20px;"
                                                                                                x-on:click.prevent="storeModelo">
                                                                                                &nbsp;<span
                                                                                                    aria-hidden="true"
                                                                                                    class="icon-tag"></span>&nbsp;&nbsp;
                                                                                                Salvar Modelo&nbsp;
                                                                                            </button>

                                                                                            <template
                                                                                                x-if="(infoModal.sn_laudo == false)">
                                                                                                <button type="button"
                                                                                                    class="btn btn-default m-b-xs"
                                                                                                    x-on:click="liberarLaudo(true)"
                                                                                                    style="color: #27812a; font-weight: bold; text-align: right"><i
                                                                                                        class="fa fa-check"></i>
                                                                                                    Liberar Laudo </button>
                                                                                            </template>
                                                                                            <template
                                                                                                x-if="(infoModal.sn_laudo == true)">
                                                                                                <button type="button"
                                                                                                    class="btn btn-default m-b-xs"
                                                                                                    x-on:click="liberarLaudo(false)"
                                                                                                    style="color: #f44336; font-weight: bold; text-align: right"><i
                                                                                                        class="fa fa-close "></i>
                                                                                                    Cancelar Laudo</button>
                                                                                            </template>
                                                                                        </div>
                                                                                        <!--
                                                                                            <div class="checkbox m-r-md"
                                                                                                x-show="infoModal.sn_laudo != 'E'"
                                                                                                style="margin-top: 0; margin-bottom: 0">
                                                                                                <label
                                                                                                    id="label_laudo_checkbox"
                                                                                                    x-on:change="liberarLaudo()"
                                                                                                    style="padding: 0">
                                                                                                    <input
                                                                                                        id="sn_laudo_checkbox"
                                                                                                        type="checkbox"
                                                                                                        name="sn_laudo" />
                                                                                                    Liberar Laudo?
                                                                                                </label>
                                                                                            </div>
                                                                                            -->
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                            <!--
                                                                                <template x-if="infoModal.atendimento.whast =='S'">
                                                                                        <code style="color: #107d73; background-color: #e9e9e9;" x-html= "' Paciente possui Whatsapp Cadastrado! [ '+ infoModal.atendimento.celular +' ] [ Data de validação: '+ infoModal.atendimento.dt_whast +' ] '"></code>
                                                                                </template>
                                                                                <template x-if="infoModal.atendimento.whast =='N'">
                                                                                    <code x-html="'Paciente não possui Whatsapp Cadastrado! [ '+ infoModal.atendimento.celular +' ] [ Data de validação: '+ infoModal.atendimento.dt_whast +' ] '"></code>
                                                                                </template>
                                                                                <template x-if="!infoModal.atendimento.whast">
                                                                                    <code x-html="'Validação do WhatsApp não realizada! [Celular '+ infoModal.atendimento.celular +' ] '"></code>
                                                                                </template>
                                                                                <br>
                                                                                -->
                                                                            <template
                                                                                x-if="(infoModal.cd_status_envio=='S')">
                                                                                <code
                                                                                    x-html="'Whatsapp enviado para o paciente [ ' + infoModal.data_envio + ' ] '  ">
                                                                                </code>
                                                                            </template>


                                                                        </div>
                                                                    </div>
                                                                </form>

                                                            </div>

                                                            <div class="col-md-3 col-sm-3 col-xs-12 col-lg-3">

                                                                <div class="panel panel-white ui-sortable-handle">
                                                                    <div class="panel-heading">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <h3 class="panel-title">Histórico de Exames
                                                                                </h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="panel-group" role="tablist"
                                                                    aria-multiselectable="true">


                                                                    <template x-for="item in historico_exames">

                                                                        <div class="panel-group"
                                                                            style="margin-bottom: 5px;" role="tablist"
                                                                            aria-multiselectable="true">
                                                                            <div class="panel panel-default"
                                                                                style="border-radius: 0px;">
                                                                                <div class="panel-heading" role="tab"
                                                                                    style="margin: 1px; padding: 10px; padding-bottom: 2px; padding-top: 2px; ">
                                                                                    <h4 class="panel-title"
                                                                                        style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; ">
                                                                                        <div class="row">
                                                                                            <div class="col-md-10">
                                                                                                <spam
                                                                                                    style="font-weight: normal; font-size: 11px;"
                                                                                                    x-html="item.atendimento?.dt_agenda.substr(0, 10).split('-').reverse().join('/') ">
                                                                                                </spam> - <span
                                                                                                    x-html="item.atendimento?.profissional?.nm_profissional">
                                                                                                </span> <br>
                                                                                                <spam
                                                                                                    x-html="item.exame.nm_exame">
                                                                                                </spam>
                                                                                            </div>
                                                                                            <div class="col-md-2"
                                                                                                style="text-align: center">
                                                                                                <a target="_blank"
                                                                                                    x-bind:href="'/rpclinica/central-laudos-documento/' +
                                                                                                    item.cd_agendamento_item">
                                                                                                    <img
                                                                                                        src="{{ asset('assets\images\pdf.png') }}">
                                                                                                </a>

                                                                                            </div>
                                                                                        </div>
                                                                                    </h4>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        </div>

                                                                    </template>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="tab-pane" role="tabpanel" id="TabImg"
                                                    style="text-align: center;">

                                                    <form class="form-horizontal" x-on:submit.prevent="storeExaImg"
                                                        id="form_EXAME_IMG" method="post">
                                                        <div class="form-group " style="margin-bottom: 5px;">
                                                            <div class="col-sm-5 col-md-offset-1"
                                                                style="padding-right: 5px; text-align: left">
                                                                <label for="input-help-block"
                                                                    class="control-label">Arquivo: <span
                                                                        class="red normal">*</span></label>
                                                                <input type="file" class="form-control" multiple
                                                                    accept="image/*,application/pdf" name="image[]">
                                                            </div>
                                                            <div class="col-md-4"
                                                                style="padding-right: 5px; padding-left: 5px; text-align: left">
                                                                <label class="control-label">Descrição: <span
                                                                        class="red normal"></span></label>
                                                                <input type="text" class="form-control"
                                                                    name="descricao" maxlength="255"
                                                                    aria-required="true">
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <label for="input-help-block"
                                                                    class="control-label">&nbsp;</label>
                                                                <button type="submit" class="btn btn-success"
                                                                    style="width: 100%;" x-html="buttonSalvarExaImg">
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <br>

                                                    <template x-if="infoModal.array_img">
                                                        <template x-for="item in infoModal.array_img">

                                                            <template x-if="item.sn_visualiza=='S'">

                                                                <div class="panel panel-white">
                                                                    <div class="panel-heading"
                                                                        style="padding: 12px; height: 60px;">
                                                                        <h3 class="panel-title"style="width: 100%;">
                                                                            <table width="100%">
                                                                                <tr>
                                                                                    <td width="80%"
                                                                                        style="text-align: left">
                                                                                        <span
                                                                                            style=" margin-right: 15px; font-style: italic"
                                                                                            x-text="item.usuario + '&nbsp;&nbsp;&nbsp; [ Olho  : ' + item.olho +' ] '   ">
                                                                                        </span>
                                                                                        <br>
                                                                                        <span
                                                                                            style=" margin-right: 15px;font-weight: 300;"
                                                                                            x-html="item.data + ' &nbsp;&nbsp;&nbsp; <span > storage: ' + item.sn_storage + '</span>'">
                                                                                        </span>
                                                                                    </td>
                                                                                    <td width="20%"
                                                                                        style="text-align: left">
                                                                                        <div class="panel-control">
                                                                                            <a href="javascript:void(0);"
                                                                                                data-toggle="tooltip"
                                                                                                x-on:click="deleteExaImg(item.cd_img_formulario)"
                                                                                                data-placement="top"
                                                                                                title=""
                                                                                                data-original-title="Exluir"><i
                                                                                                    class="icon-close"></i></a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </h3>
                                                                    </div>
                                                                    <div class="panel-body" style="text-align: center"
                                                                        style="min-height: 300px;max-height: 500px">

                                                                        <template x-if="(item.tipo=='img')">
                                                                            <img class="img-fluid"
                                                                                style="max-height: 500px;max-width: 100%;"
                                                                                x-bind:src="item.conteudo_img" />
                                                                        </template>

                                                                        <template x-if="(item.tipo=='pdf')">
                                                                            <iframe x-bind:src="item.conteudo_img"
                                                                                frameBorder="0" scrolling="auto"
                                                                                style="height: 500px; width: 100%; border: 5px solid #525659">
                                                                            </iframe>
                                                                        </template>
                                                                    </div>
                                                                </div>

                                                            </template>

                                                        </template>
                                                    </template>

                                                    <div class="row">
                                                        <template x-for="img in infoModal.array_img">

                                                            <template x-if="img.sn_visualiza=='N'">

                                                                <div class="col-md-4  " style="text-align: center; ">

                                                                    <div class="panel panel-white"
                                                                        style="border: 1px solid #dce1e4;">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title"
                                                                                style="font-style: italic; font-weight: 300;"
                                                                                x-html="img.data ">
                                                                            </h3>
                                                                            <div class="panel-control">
                                                                                <a href="javascript:void(0);"
                                                                                    data-toggle="tooltip"
                                                                                    x-on:click="deleteExaImg(img.cd_img_formulario)"
                                                                                    data-placement="top" title=""
                                                                                    data-original-title="Exluir"><i
                                                                                        class="icon-close"></i></a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-body"
                                                                            style="text-align: center;  margin-top: 20px;">

                                                                            <template x-if="img.tipo=='pdf'">
                                                                                <a x-bind:href="'/rpclinica/central-laudos-visualizar/doc/' +
                                                                                img.codigo"
                                                                                    target="_blank">
                                                                                    <img class="img-fluid "
                                                                                        style="max-width: 100%; "
                                                                                        src="{{ asset('assets\images\ficheiro-pdf.png') }}">
                                                                                </a>
                                                                            </template>

                                                                            <template x-if="img.tipo=='img'">
                                                                                <a x-bind:href="'/rpclinica/central-laudos-visualizar/doc/' +
                                                                                img.codigo"
                                                                                    target="_blank">
                                                                                    <img class="img-fluid "
                                                                                        style="max-width: 100%; "
                                                                                        src="{{ asset('assets\images\ficheiro-imagem.png') }}">
                                                                                </a>
                                                                            </template>

                                                                            <br>
                                                                            <h5 style="font-style: italic; text-align: left; font-weight: 300; font-size: 13px;"
                                                                                x-html="img.descricao">
                                                                            </h5>

                                                                        </div>
                                                                    </div>

                                                                </div>

                                                            </template>
                                                        </template>
                                                    </div>

                                                    <template x-if="(!infoModal.array_img)">
                                                        <div>
                                                            <img class="img-fluid" style="max-width: 100%;"
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
                                                                    <button type="submit" class="btn btn-success"
                                                                        x-html="buttonSalvarAnot"> </button>
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </form>

                                                    <br>

                                                    <template x-for="value in infoModal.notes ">
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
        const snLaudo = @js(auth()->guard('rpclinica')->user()->laudar_exame == 'S' ? true : false);
        const snTodosLaudo = @js(auth()->guard('rpclinica')->user()->laudar_exame == 'S' ? true : false);
        const empresa = @js($empresa);
    </script>
    <script src="{{ asset('js/simple-calendar/simple-calendar.js') }}"></script>
    <script src="{{ asset('js/rpclinica/central-de-laudos.js') }}"></script>
@endsection
