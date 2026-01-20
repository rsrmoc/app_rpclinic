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

        .PreExamePendente {
            color: #f87171;
        }

        .PreExameRealizado {
            color: #4ade80;
        }
        .etapaRealizado {
            color: #4ade80;
            border: 1px solid #4ade80;
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
    </style>

    <div class="page-title">
        <h3>Pré Exame</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('pre-exame.listar') }}">Relação</a></li>
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

    <div id="app" x-data="app">
        <div id="main-wrapper">
            <div class="col-md-12 ">
                <div class="panel panel-white">
                    <div class="panel-body">

                        <div role="tabpanel" class="tab-pane active fade in" id="tabConsultorio">

                            <div class="row">

                                <form x-on:submit.prevent="getHorarios" id="form-horario" class="col-md-3"
                                    style="margin-bottom: 22px">

                                    <div id="calendar"></div>
                                    <input type="hidden" name="data" id="data-input" />
                                    <input type="hidden" name="tela" value="consultorio" />

                                    <div style="text-align: right">
                                        <span class="btn" x-on:click="getAtendimentos()"
                                            style=" color: #22BAA0; font-weight: bold; font-size: 13px;">
                                            <i class="fa fa-refresh"></i>
                                        </span>
                                    </div>

                                    <hr style="margin-top: 7px;" />

                                    <label>Profissional:</label>
                                    <div class="form-data" style="margin-bottom: 7px">
                                        <select class="form-control" name="profissional">
                                            <option value="">Todos os Profissionais</option>
                                            @foreach ($parametros['profissionais'] as $profissional)
                                                <option value="{{ $profissional->cd_profissional }}">
                                                    {{ $profissional->nm_profissional }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <label>Local de Atendimento:</label>
                                    <div class="form-data" style="margin-bottom: 7px">
                                        <select class="form-control" name="cd_local">
                                            <option value="">Todos os Locais</option>
                                            @foreach ($parametros['local'] as $local)
                                                <option value="{{ $local->cd_local }}"> {{ $local->nm_local }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <label>Tipo de Atendimento:</label>
                                    <div class="form-data" style="margin-bottom: 7px">
                                        <select class="form-control" name="cd_tipo">
                                            <option value="">Todos os Tipos</option>
                                            @foreach ($parametros['tipo'] as $tipo)
                                                <option value="{{ $tipo->cd_tipo_atendimento }}">
                                                    {{ $tipo->nm_tipo_atendimento }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <label>Situação:</label>
                                    <div class="form-data" style="margin-bottom: 7px">
                                        <select id="select-situacao" class="form-control" style="width: 100%; z-index: 5000"
                                            name="situacao" required>
                                            <option value="">Todos as Situação</option>
                                            @foreach ($parametros['situacao'] as $situacao)
                                                <option value="{{ $situacao->cd_situacao }}"> {{ $situacao->nm_situacao }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                </form>

                                <div class="col-md-9">
                               
                                    <template x-if="messageDanger">
                                        <div class="alert alert-danger">
                                            <span x-text="messageDanger"></span>
                                            <button x-on:click="messageDanger = null" type="button" class="close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                    </template>

                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="active">
                                                <th class="text-center">Atendimento</th>
                                                <th>Paciente</th>
                                                <th>Local Atend</th>
                                                <th>Convênio</th>
                                                <th>Procedimentos</th>
                                                <th class="text-center">Pre-Exames</th>
                                                <th>Situação</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr x-show="loading">
                                                <td colspan="6">
                                                    <div class="line">
                                                        <div class="loading"></div>
                                                        <span>Buscando Atendimentos...</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <template x-for="(item, index) in relacaoAtend">

                                                <tr style="cursor: pointer">

                                                    <th class="text-center">
                                                        <span style="font-size: 1.2em; font-style: italic;"
                                                            class="btn btn-default btn-rounded btn-xs "
                                                            x-bind:class="(item.sn_pre_exame==true) ? 'btn-success' : ''"
                                                            x-html="(item.sn_atendimento=='S') ? item.cd_agendamento : '#####' "></span>
                                                        <br><span style="font-weight: 400">
                                                            <i class="fa fa-calendar" style="margin-right: 1px;"></i>
                                                            <span x-text="item.hr_agenda"> </span>
                                                        </span>

                                                    </th>

                                                    <td>
                                                        <span x-text="item.paciente?.nm_paciente"></span>
                                                        <span style="font-size: 12px; font-style: italic;"><br><b>Nome
                                                                Social: </b>
                                                            <span
                                                                x-text="(item.paciente?.nome_social) ? item.paciente?.nome_social : ' -- '">
                                                            </span>
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span x-text="item.local?.nm_local"></span>
                                                        <span style="font-size: 12px; font-style: italic;"><br><b>Tipo
                                                                Atend.: </b>
                                                            <span x-text="item.tipo_atend?.nm_tipo_atendimento"> </span>
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span x-text="item.convenio?.nm_convenio"></span>
                                                        <span style="font-size: 12px; font-style: italic;"><br><b>Profissional:
                                                            </b>
                                                            <span x-text="item.profissional?.nm_profissional"> </span>
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <template x-for="(valor, index2) in item.itens">

                                                            <button type="button"
                                                                class="btn btn-default btn-rounded btn-xs "
                                                                style="font-size: 12px;  margin-top: 3px; color: #7a6fbe; font-weight: bold"
                                                                data-toggle="modal" data-target=".modalAtendimento"
                                                                x-on:click="getExames(valor,index2,item,index)">
                                                                <i class="fa fa-stethoscope" style="margin-right: 2px;"></i>
                                                                <span x-text="valor.exame?.nm_exame"> </span> </button>

                                                        </template>
                                                    </td>

                                                    <td class="text-center">


                                                        <button type="button" class="btn btn-default btn-rounded btn-xs"
                                                            x-bind:class="(item.auto_refracao?.dt_liberacao != null) ?
                                                            'PreExameRealizado' : 'PreExamePendente'"
                                                            x-on:click="getAutoRefracao(item,index,item.cd_agendamento,item.cd_profissional)"
                                                            data-toggle="modal" data-target=".modalAutoRefracao"
                                                            style="font-size: 12px; margin-top: 3px;">
                                                            <template x-if="item.ceratometria?.dt_liberacao != null">
                                                                <i class="fa fa-check-circle "></i>
                                                            </template>
                                                            <template x-if="item.formularios_imagens_ceratometria_comp?.dt_exame == null">
                                                                <i class="fa fa-times-circle "></i>
                                                            </template>
                                                            AutoRefração</button>


                                                        <button type="button" class="btn btn-default btn-rounded btn-xs "
                                                            x-bind:class="(item.ceratometria?.dt_liberacao != null) ?
                                                            'PreExameRealizado' : 'PreExamePendente'"
                                                            x-on:click="getCeratometria(item,index,item.cd_agendamento,item.cd_profissional)"
                                                            data-toggle="modal" data-target=".modalCeratometria"
                                                            style="font-size: 12px;  margin-top: 3px;">
                                                            <template x-if="item.ceratometria?.dt_liberacao != null">
                                                                <i class="fa fa-check-circle "></i>
                                                            </template>
                                                            <template x-if="item.ceratometria?.dt_liberacao == null">
                                                                <i class="fa fa-times-circle "></i>
                                                            </template>
                                                            Ceratometria</button>

                                                        <button type="button"
                                                            class="btn btn-default btn-rounded btn-xs PreExamePendente"
                                                            x-bind:class="(item.formularios_imagens_ceratometria_comp == null) ?
                                                            'PreExamePendente' : 'PreExameRealizado'"
                                                            x-on:click="getCeratoscopiaComp(index,item.cd_agendamento,item.cd_profissional)"
                                                            data-toggle="modal" data-target=".CeratoscopiaComp"
                                                            style="font-size: 12px;margin-top: 3px;">
                                                            <template
                                                                x-if="item.formularios_imagens_ceratometria_comp != null">
                                                                <i class="fa fa-check-circle "></i>
                                                            </template>
                                                            <template
                                                                x-if="item.formularios_imagens_ceratometria_comp == null">
                                                                <i class="fa fa-times-circle "></i>
                                                            </template>
                                                            CeratoscopiaComp.</button>
                                                    </td>

                                                    <td style="width: 80px;" x-on:click="finalizarPreExame(item,index)">
                                                        <span x-html="item.situacao?.icone+' '+item.situacao?.nm_situacao"
                                                            x-bind:class="'label ' + item.situacao?.class"
                                                            style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;">
                                                        </span>
                                                    </td>
                                                </tr>

                                            </template>


                                        </tbody>
                                    </table>

                                    <template x-if="relacaoAtend.length == 0 && !loading">
                                        <p class="text-center" style="padding: 1.5em">Não há Atendimentos para essa data
                                        </p>
                                    </template>
                                </div>

                            </div>

                        </div>

                        <div class="modal fade modalAtendimento" tabindex="-1" role="dialog"
                            aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="padding: 10px; ">
                                        <button type="button" class="close m-l-sm" data-dismiss="modal"
                                            style="margin-top: 10px; margin-right: 15px;" aria-label="Close">
                                            <span aria-hidden="true" style="padding-top: 20px;"><span aria-hidden="true"
                                                    class="icon-close"></span></span>
                                        </button>
                                        <h4 class="modal-title" id="myLargeModalLabel"
                                            style="font-weight: 300; font-size: 16px; font-style: italic;">
                                            <span style="font-weight: 400" x-html="NOME_MODAL"></span><br>
                                            <span x-html="EXAME_MODAL"></span>
                                        </h4>
                                    </div>
                                    <div class="modal-body">

                                        <div role="tabpanel" style="margin-top: 20px;">
                                            <!-- Nav tabs   -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#tabDADOS"
                                                        role="tab" data-toggle="tab">Anotações do Exame</a></li>
                                                <li role="presentation"><a href="#tabIMG" role="tab"
                                                        data-toggle="tab">Imagens do Exame</a></li>
                                            </ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="tabDADOS">

                                                    <form class="form-horizontal" x-on:submit.prevent="storeExaAnot"
                                                        id="form_ANOT_EXAME" method="post">
                                                        <div class="form-group row">
                                                            <div class="col-md-12">
                                                                <label for="input-placeholder" class=" control-label"
                                                                    style="  padding-top: 0px;">Comentário:</label>
                                                                <textarea rows="6" class="form-control" name="ds_historico"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer"
                                                            style="padding: 10px 15px;background-color: #0f172a; text-align: left;
                                                        border-top: 1px solid rgba(255, 255, 255, 0.1);border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;">
                                                            <button type="submit" class="btn btn-success"
                                                                x-html="buttonSalvarExaAnot"> </button>
                                                        </div>
                                                    </form>
                                                    <br><br>
                                                    <template x-for="item in DADOS_EXAME.historico">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" style="  font-style: italic;">
                                                                <h3 class="panel-title" style="width: 100%;">
                                                                    <div class="row">
                                                                        <div class="col-md-7 "
                                                                            x-text="item.usuario.nm_usuario"> </div>
                                                                        <div class="col-md-5 "
                                                                            style="font-weight: 300; text-align: right"
                                                                            x-text="item.data"> </div>
                                                                    </div>

                                                                </h3>
                                                            </div>
                                                            <div class="panel-body" style="padding-top: 10px;">
                                                                <p x-html="nl2br(item.ds_historico)"></p>
                                                            </div>
                                                        </div>
                                                    </template>


                                                </div>

                                                <div role="tabpanel" class="tab-pane" id="tabIMG">
                   
                                                    <form class="form-horizontal" x-on:submit.prevent="storeExaImg"
                                                        id="form_EXAME_IMG" method="post"> 
                                                        <div class="form-group " style="margin-bottom: 5px;">
                                                            <div class="col-sm-8 col-md-offset-1"
                                                                style="padding-right: 5px;">
                                                                <label for="input-help-block"
                                                                    class="control-label">Arquivo:</label>
                                                                <input type="file" class="form-control"
                                                                    name="image">
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

                                                    <div x-show="loadingModal" class="col-sm-10 col-md-offset-1">
                                                        <div class="line">
                                                            <div class="loading"></div>
                                                            <span>Buscando Imagens...</span>
                                                        </div>
                                                    </div>
                                               

                                                    <template x-for="img in EXAME.array_img">
            
                                                        <div class="panel panel-white">
                                                            <div class="panel-heading">
                                                                <div class="col-md-12">
                                                                    <h3 class="panel-title"
                                                                        style="font-style: italic; font-weight: 300;"
                                                                        x-html="'<b>'+img.usuario+'</b><br>'+FormatData(img.data)">
                                                                    </h3>
                                                                    <div class="panel-control">
                                                                        <a href="javascript:void(0);" data-toggle="tooltip"
                                                                            x-on:click="deleteExaImg(img.cd_img_formulario)"
                                                                            data-placement="top" title=""
                                                                            data-original-title="Exluir"><i
                                                                                class="icon-close"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-body" style="text-align: center;  margin-top: 20px;">
                                                                <img class="img-fluid" style="max-width: 100%;"
                                                                    x-bind:src="img.conteudo_img">
                                                            </div>
                                                        </div>
            
                                                    </template>

                                                    <template x-if="EXAME.array_img.length == 0 ">
                                                        <div class="text-center"><img class="img-fluid "
                                                                style="max-width: 100%; margin-top: 40px;"
                                                                src="{{ asset('assets\images\sem_img.jpeg') }}">
                                                    </template>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal fade CeratoscopiaComp" tabindex="-1" role="dialog"
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
                                            style="font-weight: 300; font-size: 16px; font-style: italic;">Ceratoscopia
                                            Computadorizada</h4>
                                    </div>
                                    <div class="modal-body">

                                        <form class="form-horizontal" x-on:submit.prevent="storeCeratometriaComp"
                                            id="form_CERATOMETRIA_COMP" method="post">

                                            <div class="form-group " style="margin-bottom: 5px;">
                                                <div class="col-sm-8 col-md-offset-1" style="padding-right: 5px;">
                                                    <label for="input-help-block" class="control-label">Arquivo:</label>
                                                    <input type="file" class="form-control" name="image">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="input-help-block" class="control-label">&nbsp;</label>
                                                    <button type="submit" class="btn btn-success" style="width: 100%;"
                                                        x-html="buttonSalvarCeratComp"> </button>
                                                </div>
                                            </div>

                                        </form>

                                        <div x-show="loadingModal" class="col-sm-10 col-md-offset-1">
                                            <div class="line">
                                                <div class="loading"></div>
                                                <span>Buscando Imagens...</span>
                                            </div>
                                        </div>
                                        <template x-for="img in CERATOMETRIA_COMP.array_img">

                                            <div class="panel panel-white">
                                                <div class="panel-heading">
                                                    <div class="col-md-12">
                                                        <h3 class="panel-title"
                                                            style="font-style: italic; font-weight: 300;"
                                                            x-html="'<b>'+img.usuario+'</b><br>'+FormatData(img.data)">
                                                        </h3>
                                                        <div class="panel-control">
                                                            <a href="javascript:void(0);" data-toggle="tooltip"
                                                                x-on:click="deleteCeratometriaComp(img.cd_img_formulario)"
                                                                data-placement="top" title=""
                                                                data-original-title="Exluir"><i
                                                                    class="icon-close"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-body" style="text-align: center;  margin-top: 20px;">
                                                    <img class="img-fluid" style="max-width: 100%;"
                                                        x-bind:src="img.conteudo_img">
                                                </div>
                                            </div>

                                        </template>
                                        <template x-if="CERATOMETRIA_COMP.array_img.length == 0 ">
                                            <div class="text-center"><img class="img-fluid "
                                                    style="max-width: 100%; margin-top: 40px;"
                                                    src="{{ asset('assets\images\sem_img.jpeg') }}">
                                        </template>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade modalCeratometria" tabindex="-1" role="dialog"
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
                                            style="font-weight: 300; font-size: 16px; font-style: italic;">Ceratometria
                                        </h4>
                                    </div>

                                    <form class="form-horizontal" x-on:submit.prevent="storeCeratometria"
                                        id="form_CERATOMETRIA" method="post">

                                        <div class="modal-body">

                                            <div class="row">
                                                <div class="col-sm-4 col-md-offset-2">
                                                    <label for="input-Default" class="control-label">Data do Exame: <span
                                                            class="red normal">*</span></label>
                                                    <input type="datetime-local" class="form-control input-sm text-center"
                                                        name="dt_exame" x-model="CERATOMETRIA.dt_exame">
                                                </div>

                                                <div class="col-sm-4">
                                                    <label for="input-Default" class="  control-label ">Data da
                                                        Liberação:</label>
                                                    <input type="datetime-local" class="form-control input-sm text-center"
                                                        name="dt_liberacao" x-model="CERATOMETRIA.dt_liberacao">
                                                </div>

                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-sm-6  ">
                                                    <div style="text-align: center;">
                                                        <label for="input-Default" class="control-label  "
                                                            style="font-weight: 600;   text-align: center; "><i
                                                                class="fa fa-eye" style="font-size: 15px;"></i> Olho
                                                            Direito
                                                        </label>
                                                    </div>
                                                    <div class="form-group  " style="margin-bottom: 5px;">
                                                        <div class="col-sm-4" style="padding-right: 5px;">
                                                            <label for="input-Default "
                                                                class="  control-label   ">Curva1:</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="od_curva1_ceratometria"
                                                                x-model="formatValor(CERATOMETRIA.od_curva1_ceratometria)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4"
                                                            style="  padding-left: 5px; padding-right: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">&nbsp;</label>
                                                            <input type="text"
                                                                class="form-control input-sm text-right"
                                                                name="od_curva1_milimetros"
                                                                x-model="formatValor(CERATOMETRIA.od_curva1_milimetros)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4" style="  padding-left: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">Eixo1:</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="od_eixo1_ceratometria"
                                                                x-model="formatValor(CERATOMETRIA.od_eixo1_ceratometria)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>
                                                    </div>

                                                    <div class="form-group  " style="margin-bottom: 5px;">
                                                        <div class="col-sm-4" style="padding-right: 5px;">
                                                            <label for="input-Default "
                                                                class="  control-label   ">Curva2:</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="od_curva2_ceratometria"
                                                                x-model="formatValor(CERATOMETRIA.od_curva2_ceratometria)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4"
                                                            style="  padding-left: 5px; padding-right: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">&nbsp;</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="od_curva2_milimetros"
                                                                x-model="formatValor(CERATOMETRIA.od_curva2_milimetros)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4" style="  padding-left: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">Eixo2:</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="od_eixo2_ceratometria"
                                                                x-model="formatValor(CERATOMETRIA.od_eixo2_ceratometria)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>
                                                    </div>

                                                    <div class="form-group  " style="margin-bottom: 5px;">
                                                        <div class="col-sm-4" style="padding-right: 5px;">
                                                            <label for="input-Default "
                                                                class="  control-label   ">Média:</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="od_media_ceratometria"
                                                                x-model="formatValor(CERATOMETRIA.od_media_ceratometria)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4"
                                                            style="  padding-left: 5px; padding-right: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">&nbsp;</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="od_media_milimetros"
                                                                x-model="formatValor(CERATOMETRIA.od_media_milimetros)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>
                                                    </div>

                                                    <div class="form-group  " style="margin-bottom: 5px;">
                                                        <div class="col-sm-4" style="padding-right: 5px;">
                                                            <label for="input-Default "
                                                                class="  control-label   ">Cilíndro(-):</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="od_cilindro_neg"
                                                                x-model="formatValor(CERATOMETRIA.od_cilindro_neg)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4 col-md-offset-4"
                                                            style="  padding-left: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">Eixo(-):</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="od_eixo_neg"
                                                                x-model="formatValor(CERATOMETRIA.od_eixo_neg)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>
                                                    </div>

                                                    <div class="form-group  " style="margin-bottom: 5px;">
                                                        <div class="col-sm-4" style="padding-right: 5px;">
                                                            <label for="input-Default "
                                                                class="  control-label   ">Cilíndro(+):</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="od_cilindro_pos"
                                                                x-model="formatValor(CERATOMETRIA.od_cilindro_pos)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4 col-md-offset-4"
                                                            style="  padding-left: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">Eixo(+):</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="od_eixo_pos"
                                                                x-model="formatValor(CERATOMETRIA.od_eixo_pos)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-sm-6">
                                                    <div style="text-align: center;">
                                                        <label for="input-Default" class="control-label  "
                                                            style="font-weight: 600;   text-align: center; "><i
                                                                class="fa fa-eye" style="font-size: 15px;"></i> Olho
                                                            Esquerdo</label>
                                                    </div>
                                                    <div class="form-group  " style="margin-bottom: 5px;">
                                                        <div class="col-sm-4" style="padding-right: 5px;">
                                                            <label for="input-Default "
                                                                class="  control-label   ">Curva1:</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_curva1_ceratometria"
                                                                x-model="formatValor(CERATOMETRIA.oe_curva1_ceratometria)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4"
                                                            style="  padding-left: 5px; padding-right: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">&nbsp;</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_curva1_milimetros"
                                                                x-model="formatValor(CERATOMETRIA.oe_curva1_milimetros)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4" style="  padding-left: 5px; ">
                                                            <label for="input-Default"
                                                                class="  control-label ">Eixo1:</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_eixo1_ceratometria"
                                                                x-model="formatValor(CERATOMETRIA.oe_eixo1_ceratometria)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>
                                                    </div>

                                                    <div class="form-group  " style="margin-bottom: 5px;">
                                                        <div class="col-sm-4" style="padding-right: 5px;">
                                                            <label for="input-Default "
                                                                class="  control-label   ">Curva2:</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_curva2_ceratometria"
                                                                x-model="formatValor(CERATOMETRIA.oe_curva2_ceratometria)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4"
                                                            style="  padding-left: 5px; padding-right: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">&nbsp;</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_curva2_milimetros"
                                                                x-model="formatValor(CERATOMETRIA.oe_curva2_milimetros)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4" style="  padding-left: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">Eixo2:</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_eixo2_ceratometria"
                                                                x-model="formatValor(CERATOMETRIA.oe_eixo2_ceratometria)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>
                                                    </div>

                                                    <div class="form-group  " style="margin-bottom: 5px;">
                                                        <div class="col-sm-4" style="padding-right: 5px;">
                                                            <label for="input-Default "
                                                                class="  control-label   ">Média:</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_media_ceratometria"
                                                                x-model="formatValor(CERATOMETRIA.oe_media_ceratometria)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4"
                                                            style="  padding-left: 5px; padding-right: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">&nbsp;</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_media_milimetros"
                                                                x-model="formatValor(CERATOMETRIA.oe_media_milimetros)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>
                                                    </div>

                                                    <div class="form-group  " style="margin-bottom: 5px;">
                                                        <div class="col-sm-4" style="padding-right: 5px;">
                                                            <label for="input-Default "
                                                                class="  control-label   ">Cilíndro(-):</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_cilindro_neg"
                                                                x-model="formatValor(CERATOMETRIA.oe_cilindro_neg)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4 col-md-offset-4"
                                                            style="  padding-left: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">Eixo(-):</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_eixo_neg"
                                                                x-model="formatValor(CERATOMETRIA.oe_eixo_neg)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>
                                                    </div>

                                                    <div class="form-group  " style="margin-bottom: 5px;">
                                                        <div class="col-sm-4" style="padding-right: 5px;">
                                                            <label for="input-Default "
                                                                class="  control-label   ">Cilíndro(+):</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_cilindro_pos"
                                                                x-model="formatValor(CERATOMETRIA.oe_cilindro_pos)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>

                                                        <div class="col-sm-4 col-md-offset-4"
                                                            style="  padding-left: 5px;">
                                                            <label for="input-Default"
                                                                class="  control-label ">Eixo(+):</label>
                                                            <input type="text" class="form-control input-sm text-right"
                                                                name="oe_eixo_pos"
                                                                x-model="formatValor(CERATOMETRIA.oe_eixo_pos)"
                                                                x-mask:dynamic="$money($input, ',')">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-md-12">
                                                    <label for="input-placeholder" class=" control-label"
                                                        style="  padding-top: 0px;">Comentário:</label>
                                                    <textarea rows="3" class="form-control" name="obs" x-model="CERATOMETRIA.obs"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer"
                                            style="padding: 10px 15px;background-color: #f5f5f5; text-align: left;
                                        border-top: 1px solid #ddd;border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;">
                                            <button type="submit" class="btn btn-success" x-html="buttonSalvarCerat">
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade modalAutoRefracao" tabindex="-1" role="dialog"
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
                                            style="font-weight: 300; font-size: 16px; font-style: italic;">Auto Refração
                                        </h4>
                                    </div>
                                    <form class="form-horizontal" x-on:submit.prevent="storeAutoRefracao"
                                        id="form_AUTO_REFRACAO" method="post">
                                        <div class="modal-body">
                                            @csrf
                                            <input type="hidden" name="tipo" value="Auto Refração">

                                            <div class="form-group">
                                                <div class="col-sm-4" style="padding-right: 5px;">
                                                    <label for="input-Default" class="control-label">Data do Exame: <span
                                                            class="red normal">*</span></label>
                                                    <input type="datetime-local" class="form-control input-sm text-center"
                                                        required name="dt_exame"
                                                        x-model="(AUTO_REFRACAO.dt_exame) ? AUTO_REFRACAO.dt_exame.substr(0, 16) : null">
                                                </div>

                                                <div class="col-sm-4" style="padding-right: 5px; padding-left: 5px;">
                                                    <label for="input-Default" class="control-label">Data da
                                                        Liberação:</label>
                                                    <input type="datetime-local" class="form-control input-sm text-center"
                                                        name="dt_liberacao"
                                                        x-model="(AUTO_REFRACAO.dt_liberacao) ? AUTO_REFRACAO.dt_liberacao.substr(0, 16) : null">
                                                </div>

                                                <div class="col-sm-4" style="padding-left: 5px;">
                                                    <label for="input-Default" class="control-label bold">DP:</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="dp" x-model="formatValor(AUTO_REFRACAO.dp)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                            </div>

                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-sm-6">
                                                    <h5>Auto Refração Dinâmica:</h5>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label id="label_receita_dinamica">
                                                        <input type="checkbox" name="receita_dinamica"
                                                            id="receita_dinamica">
                                                        Receita
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-sm-3" style="padding-right: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="font-size: 0.9em; padding-top: 0px;">OD DE</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="od_de_dinamica"
                                                        x-model="formatValor(AUTO_REFRACAO.od_de_dinamica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="od_dc_dinamica"
                                                        x-model="formatValor(AUTO_REFRACAO.od_dc_dinamica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="padding-top: 0px;">Eixo</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="od_eixo_dinamica"
                                                        x-model="formatValor(AUTO_REFRACAO.od_eixo_dinamica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="padding-top: 0px;">Reflexo
                                                        OD:</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="od_reflexo_dinamica"
                                                        x-model="formatValor(AUTO_REFRACAO.od_reflexo_dinamica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                            </div>

                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-sm-3" style="padding-right: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="font-size: 0.9em; padding-top: 0px;">OE DE</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="oe_de_dinamica"
                                                        x-model="formatValor(AUTO_REFRACAO.oe_de_dinamica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="oe_dc_dinamica"
                                                        x-model="formatValor(AUTO_REFRACAO.oe_dc_dinamica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="padding-top: 0px;">Eixo</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="oe_eixo_dinamica"
                                                        x-model="formatValor(AUTO_REFRACAO.oe_eixo_dinamica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="padding-top: 0px;">Reflexo
                                                        OE:</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="oe_reflexo_dinamica"
                                                        x-model="formatValor(AUTO_REFRACAO.oe_reflexo_dinamica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <h5>Auto Refração Estática:</h5>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label id="label_receita_estatica">
                                                        <input type="checkbox" name="receita_estatica"
                                                            id="receita_estatica">
                                                        Receita
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-sm-3" style="padding-right: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="font-size: 0.9em; padding-top: 0px;">OD DE</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="od_de_estatica"
                                                        x-model="formatValor(AUTO_REFRACAO.od_de_estatica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="od_dc_estatica"
                                                        x-model="formatValor(AUTO_REFRACAO.od_dc_estatica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="padding-top: 0px;">Eixo</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="od_eixo_estatica"
                                                        x-model="formatValor(AUTO_REFRACAO.od_eixo_estatica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="padding-top: 0px;">Reflexo
                                                        OD:</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="od_reflexo_estatica"
                                                        x-model="formatValor(AUTO_REFRACAO.od_reflexo_estatica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                            </div>

                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-sm-3" style="padding-right: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="font-size: 0.9em; padding-top: 0px;">OE DE</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="oe_de_estatica"
                                                        x-model="formatValor(AUTO_REFRACAO.oe_de_estatica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="oe_dc_estatica"
                                                        x-model="formatValor(AUTO_REFRACAO.oe_dc_estatica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="padding-top: 0px;">Eixo</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="oe_eixo_estatica"
                                                        x-model="formatValor(AUTO_REFRACAO.oe_eixo_estatica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                                <div class="col-sm-3" style="padding-left: 5px;">
                                                    <label for="input-placeholder" class="control-label"
                                                        style="padding-top: 0px;">Reflexo
                                                        OE:</label>
                                                    <input type="text" class="form-control input-sm text-right"
                                                        name="oe_reflexo_estatica"
                                                        x-model="formatValor(AUTO_REFRACAO.oe_reflexo_estatica)"
                                                        x-mask:dynamic="$money($input, ',')">
                                                </div>
                                            </div>

                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-md-12">
                                                    <label for="input-placeholder" class=" control-label"
                                                        style="  padding-top: 0px;">Comentário:</label>
                                                    <textarea rows="3" class="form-control" name="comentario" x-model="AUTO_REFRACAO.comentario"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer"
                                            style="padding: 10px 15px;background-color: #f5f5f5; text-align: left;
                                        border-top: 1px solid #ddd;border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;">
                                            <button type="submit" class="btn btn-success" x-html="buttonSalvarAutoRef">
                                            </button>
                                        </div>
                                    </form>
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
    <script src="{{ asset('js/rpclinica/pre-exame.js') }}"></script>
@endsection
