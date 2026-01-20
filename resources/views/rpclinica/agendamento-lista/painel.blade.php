@extends('rpclinica.layout.layout')

@section('content')
    <style>
    <style>
        /* 
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
            background: #f1f3f5;
        }
        */

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
            color: #a94442
        }

        .PreExameRealizado {
            color: #3c763d;
        }

        .etapaRealizado {
            color: #3c763d;
            border: 1px solid #3c763d;
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

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right: 0px;
        }

        .select2-container .select2-selection--multiple {
            min-height: 55px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 2px;
            padding-right: 5px;
        }

        .select2-container .select2-search--inline .select2-search__field,
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: none;
        }
    </style>


    <div id="app" x-data="app">

        <div class="page-title">
            <div class="row">
                <div class="col-md-10 "> 
                    <h3>Tela de Agendamento</h3>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="{{ route('pre-exame.listar') }}">Relação</a></li>
                        </ol>
                    </div>  
                </div>
                <div class="col-md-2" style="text-align: right; ">
                   
                </div>

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
            
            .info-box .color-atendido i {
                color: #F19958;
            }

            
            .panel-body-atendido {
                box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
                transition: box-shadow 0.2s ease-in-out;
                border-top: 3px solid #F19958;
            }



            .label-black {
                background: #34425a;
            }

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

            .info-box .info-box-stats p {
                margin-bottom: 4px;
            }

            .info-box .info-box-stats span.info-box-title {
                margin-bottom: 0px;
            }

            .text-danger {
                color: #f25656;
            }

            .text-info {
                color: #12AFCB;
            }
        </style>

        <div id="main-wrapper">



            <div class="col-md-12 ">

                <div class="panel panel-white">
   
                    
                    <div class="panel-body">

                        <div role="tabpanel" class="tab-pane active fade in" id="tabConsultorio">


                            <div class="row">

                                <form x-on:submit.prevent="getAgendamentos" id="form-horario" class="col-md-3" style="margin-bottom: 22px">
                                    
                                    @csrf
                                    <div id="calendar"></div>
                                    <input type="hidden" name="data" id="data-input" />
                                    <input type="hidden" name="tela" value="consultorio" />

                                    <div style="text-align: right">
                                        <div class="btn-group" role="group" style="margin-top: 5px;" aria-label="Third group">
 
                                            <button type="button" class="btn btn-twitter m-b-xs" x-on:click="getModalManual" style="margin-left: 5px;"
                                            aria-hidden="true" data-toggle="tooltip" title="" data-placement="top" data-original-title="Agendamento">
                                                <span aria-hidden="true" class="icon-note"></span> 
                                            </button>

                                            <button type="button" class="btn btn-youtube m-b-xs" style="margin-left: 5px;"
                                            aria-hidden="true" data-toggle="tooltip" title="" data-placement="top" x-on:click="getModalBloqueio" data-original-title="Bloqueio de Agenda">
                                                <span aria-hidden="true" class="icon-ban"></span>
                                            </button>

                                            <button type="button" class="btn btn-rss m-b-xs" style="margin-left: 5px;"
                                            aria-hidden="true" data-toggle="tooltip" title="" data-placement="top" x-on:click="getModalConfirmacao" data-original-title="Confirmação de Agenda" >
                                                <span aria-hidden="true" class="icon-calendar"></span>
                                                
                                            </button>
     
                                            <button type="button" class="btn btn-success m-b-xs"  x-on:click="getAtendimentos()" 
                                            data-toggle="tooltip" title="" data-placement="top" data-original-title="Atualizar" style="margin-left: 5px;" >
                                              
                                                <span  style="    font-size: 14px;" aria-hidden="true" class="icon-refresh"></span>
                                            </button>

                                        </div>
                                        
                                    </div>

                                    <hr style="margin-top: 7px;" />

                                    <label>Agenda:</label>
                                    <div class="form-data" style="margin-bottom: 7px">
                                        <select class="form-control"   name="agenda">
                                            <option value="">Todos as Agendas</option>
                                            @foreach ($parametros['agenda'] as $agenda)
                                                <option value="{{ $agenda->cd_agenda }}">
                                                    {{ $agenda->nm_agenda }}</option>
                                            @endforeach
                                        </select>
                                    </div>

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

 
                                    <div class="row">



                                        <div class="col-lg-3 col-md-4" style="padding-right: 5px; padding-left: 5px;">
                                            <div class="panel info-box panel-white " style="margin-bottom: 10px;">
                                                <div class="panel-body panel-body-agendado" style="padding: 10px;">
                                                    <div class="info-box-stats ">
                                                        <p class="counter color-agendado" x-html="headerAgendados"></p>
                                                        <span class="info-box-title">Agendados</span>
                                                    </div>
                                                    <div class="info-box-icon color-agendado">
                                                        <i class="icon-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-4" style="padding-right: 5px; padding-left: 5px;">
                                            <div class="panel info-box panel-white" style="margin-bottom: 10px;">
                                                <div class="panel-body panel-body-confirmado" style="padding: 10px;">
                                                    <div class="info-box-stats">
                                                        <p class="counter" x-html="headerConfirmados"></p>
                                                        <span class="info-box-title">Confirmados</span>
                                                    </div>
                                                    <div class="info-box-icon color-confirmado">
                                                        
                                                        <i class="fa fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-lg-3 col-md-4" style="padding-right: 5px; padding-left: 5px;">
                                            <div class="panel info-box panel-white" style="margin-bottom: 10px;">
                                                <div class="panel-body panel-body-atendido " style="padding: 10px;">
                                                    <div class="info-box-stats">
                                                        <p class="counter" x-html="headerAguardando"></p>
                                                        <span class="info-box-title">Check-in</span>
                                                    </div>
                                                    <div class="info-box-icon color-atendido">
                                                        <i class="icon-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-4" style="padding-right: 5px; padding-left: 5px;">
                                            <div class="panel info-box panel-white" style="margin-bottom: 10px;">
                                                <div class="panel-body panel-body-livre" style="padding: 10px;">
                                                    <div class="info-box-stats">
                                                        <p><span class="counter color-livre" x-html="headerAtendidos"></span>
                                                        </p>
                                                        <span class="info-box-title">Atendidos</span>
                                                    </div>
                                                    <div class="info-box-icon color-livre">
                                                        <i class="icon-lock-open"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <template x-if="messageDanger">
                                        <div class="alert alert-danger">
                                            <span x-text="messageDanger"></span>
                                            <button x-on:click="messageDanger = null" type="button" class="close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                    </template>
 
                                    <template x-if="( (inicioIcone == false) && (bloqueado == false))">

                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="active">
                                                    <th class="text-center">Atendimento</th>
                                                    <th>Agenda</th>
                                                    <th>Paciente</th>
                                                    <th>Convênio</th>
                                                    <th>Local Atend</th>
                                                    <th>Itens</th>
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

                                                <template x-for="(item, idx) in lista">
                                                    <tr style="cursor: pointer">

                                                        <th class="text-center">  
                                                            <span>
                                                                     <span style="font-size: 1em; font-style: italic;"
                                                                            class="btn btn-default btn-rounded btn-xs "
                                                                            x-text="(item.cd_agendamento) ? item.cd_agendamento : 'XXXXX' ">
                                                                     </span><br>
                                                            </span>  
                                                            <span style="font-weight: 400">
                                                                <i class="fa fa-calendar" style="margin-right: 1px;"></i>
                                                                <span x-text="item.hr_agenda"> </span>
                                                            </span>

                                                        </th>

                                                        <td>
                                                            <span style="font-size: 1em; font-style: italic;"
                                                                class="btn btn-default btn-rounded btn-xs "
                                                                x-text="item.agenda.nm_agenda"></span>
 
                                                                    <span style="font-size: 12px; font-style: italic;"><br><b>Profissional:
                                                                        </b>
                                                                        <span
                                                                            x-text="(item.profissional) ? item.profissional.nm_profissional : ' -- ' ">
                                                                        </span>
                                                                    </span> 
                                                        </td>

                                                        <td>  
                                                   
                                                                    <span>
                                                                        <span
                                                                            x-text="(item.paciente) ? item.paciente?.nm_paciente : ' ... ' ">
                                                                        </span>
                                                                        <span
                                                                            style="font-size: 12px; font-style: italic;"><br><b>Nome
                                                                                Social: </b>
                                                                            <span
                                                                                x-text="(item.paciente?.nome_social) ? item.paciente?.nome_social : ' ... ' ">
                                                                            </span>
                                                                        </span>
                                                                    </span> 
                                                        </td>

                                                        <td> 
                                                                    <span>
                                                                        <span
                                                                            x-text="(item.convenio) ? item.convenio?.nm_convenio : ' ... ' "></span>
                                                                        <span
                                                                            style="font-size: 12px; font-style: italic;"><br><b>Cartão:
                                                                            </b>
                                                                            <span
                                                                                x-text="(item.cartao) ? item.cartao : ' ... ' ">
                                                                            </span>
                                                                        </span>
                                                                    </span> 
                                                        </td>

                                                        <td> 
                                                                    <span>
                                                                        <span
                                                                            x-text="(item.local) ? item.local?.nm_local : ' ... ' "></span>
                                                                        <span
                                                                            style="font-size: 12px; font-style: italic;"><br><b>Tipo
                                                                                Atend.: </b>
                                                                            <span
                                                                                x-text="(item.tipo_atend) ? item.tipo_atend?.nm_tipo_atendimento : ' ... ' ">
                                                                            </span>
                                                                        </span>
                                                                    </span>
                                                            
                                                        </td>

                                                        <td>
                                                          
                                                            <template x-for="(valor, index2) in item.itens">
                                                                <button type="button"
                                                                    class="btn btn-default btn-rounded btn-xs "
                                                                    style="font-size: 12px;  margin-top: 3px; color: #7a6fbe; font-weight: bold"
                                                                    data-toggle="modal">
                                                                    <i class="fa fa-stethoscope"
                                                                        style="margin-right: 2px;"></i>
                                                                    <span x-text="valor.exame?.nm_exame"> </span>
                                                                </button>
                                                            </template>
                                                           
                                                        </td>

                                                        <td style="width: 80px;">
                                                            <span
                                                                x-bind:class="'label ' + ((item.tab_situacao?.class) ? item.tab_situacao.class : 'situacaoLivreClass')"
                                                                x-on:click="getModal(item,idx)"    
                                                                x-html="( (item.tab_situacao?.icone) ? (item.tab_situacao.icone +item.tab_situacao.nm_situacao ) : 'situacaoLivre'  )"
                                                                style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px; margin-bottom: 2px;">
                                                                 
                                                            </span>
                                                            <!--
                                                            <template x-if="(item)">
                                                                <template x-if="(!item.tab_situacao?.bloqueado)">
                                                                    <div class="line">
                                                                        <span
                                                                            class="fa fa-check text-info btn btn-default btn-rounded"
                                                                            x-on:click="situacaoAgendamento('CO',item.cd_agendamento)"
                                                                            style="margin-left: 3px; margin-right: 3px; font-size: 12px;">
                                                                        </span>
                                                                        <span
                                                                            class="fa fa-remove text-danger  btn btn-default btn-rounded"
                                                                            x-on:click="situacaoAgendamento('CA',item.cd_agendamento)"
                                                                            style="margin-left: 3px; margin-right: 3px; font-size: 12px;">
                                                                        </span>
                                                                    </div>
                                                                </template>
                                                            </template>
                                                            -->
                                                        </td>
                                                    </tr>
                                                </template>
 
                                            </tbody>
                                        </table>

                                    </template>

                                    <template x-if="(inicioIcone == true)">
                                        <p class="text-center">
                                            <img src="{{ asset('assets\images\agendamento.png') }}"><br>
                                        </p>
                                    </template>
                                    
                                    <template x-if="(bloqueado == true)">
                                        <div style="text-align: center;"> 
                                            <img src="{{ asset('assets\images\bloqueada.png') }}"><br>
                                            <code style="font-size: 18px;" x-html="ds_bloqueado"> </code>
                                        </div>
                                    </template>

                                    <template>
                                        <p class="text-center" style="padding: 1.5em">Não há Atendimentos para essa data
                                        </p>
                                    </template>
                                </div>

                            </div>

                        </div>

                        <div class="ModalAgendamento">
                            
                            @include('rpclinica.agendamento-lista.modal-add',[$request,$parametros])
                            
                            @include('rpclinica.agendamento-lista.modal-confirmacao',[$request,$parametros])
                            
                            @include('rpclinica.agendamento-lista.modal-bloqueio',[$request,$parametros])

                        </div>

                        <div class="modal fade bs-example-modal-lg" id="ModalEnvios" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" style="border: 1px solid #22baa0;">
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
        const situacaoConfirmar = @js($parametros['confirmar']);
        const situacaoCancelar = @js($parametros['cancelar']);
        const situacaoLivre = @js($parametros['livre']);
        const situacaoLivreClass = @js($parametros['class_livre']); 
        const agendas = @js($parametros['agenda']);  
        const convenios = @js($parametros['convenio']);  
    </script>
    <script src="{{ asset('js/simple-calendar/simple-calendar.js') }}"></script>
    <script src="{{ asset('js/rpclinica/agendamento-lista.js') }}"></script>
@endsection
