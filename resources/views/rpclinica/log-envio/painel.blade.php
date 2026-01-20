@extends('rpclinica.layout.layout')

@section('content')
    <style>
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
            <div class="col-md-8"  >
                <h3>Logs de Envio</h3>
                <div class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('consultorio') }}">Relação</a></li>
                    </ol>
                </div>
            </div>  
 
            <div class="col-md-4 panel-body" style="text-align: right;">
                <div class="btn-group">
  
                    <a href="{{ route('logs.rotina.manual') }}" class="btn btn-default" 
                    data-toggle="tooltip" data-placement="top" title="" data-original-title="Executar Rotina">
                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                    </a>
 
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
 
        .nav-tabs>li>a {
            border-bottom: 0px;
        }
        .nav-tabs>li>a {
            margin-right: 0px;
        }
        .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {
            color: #2dd4bf !important;
            background-color: rgba(45, 212, 191, 0.1) !important;  
            border: 1px solid rgba(45, 212, 191, 0.3) !important;
        }
        .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active{
            color: #2dd4bf !important;
            background-color: rgba(45, 212, 191, 0.1) !important;
            font-weight: 700;
        }
    </style>

    <div id="app" x-data="app">
        <div id="main-wrapper" class="">
            <div class="col-md-12 " style="padding-right: 5px; padding-left: 5px;">

                <div role="tabpanel " style="">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified panel panel-white" role="tablist" style=" margin-bottom: 15px; ">
                        <li role="presentation" class="active" ><a href="#TabLaudos" role="tab" data-toggle="tab" aria-expanded="false"> Laudos</a></li> 
                        <li role="presentation"><a  href="#TabAgendamento" role="tab" data-toggle="tab" aria-expanded="true">Agendamentos</a></li>  
                    </ul> 
                </div>

                <div class="panel panel-white">
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" role="tabpanel" id="TabLaudos">

                                <form x-on:submit.prevent="getPage" id="form-parametros" style="">
                                    <div class="row">
                                        <div class="col-md-4" style="padding-right: 0px; padding-left: 0px;">

                                            <div class="col-md-4" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group">
                                                    <label>Data Inicio Laudo: <span class="red normal"></span></label>
                                                    <input type="date" class="form-control required" value="{{$request['dti']}}" name="dti" maxlength="10"
                                                        aria-required="true" >
                                                </div>
                                            </div> 
                                            <div class="col-md-4" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group">
                                                    <label>Data Fim Laudo: <span class="red normal"></span></label>
                                                    <input type="date" class="form-control required" value="{{$request['dtf']}}" name="dtf" maxlength="10"
                                                        aria-required="true" >
                                                </div>
                                            </div> 
                                            <div class="col-md-4" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group">
                                                    <label>Data Envio: <span class="red normal"></span></label>
                                                    <input type="date" class="form-control required" name="dtenvio" maxlength="10"
                                                        aria-required="true" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8" style="padding-right: 0px; padding-left: 0px;">

                                            <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group">
                                                    <label>Nome do Paciente: <span class="red normal"></span></label>
                                                    <input type="text" class="form-control required" name="paciente" maxlength="100"
                                                        aria-required="true">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-5" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group">
                                                    <label>Profissional Executante: <span class="red normal"></span></label>
                                                    <select class="form-control " name="cd_executante">
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
                                                    <label>Status Envio: <span class="red normal"></span></label>
                                                    <select class="form-control " name="status_envio">
                                                        <option value="">Todos</option>
                                                        @foreach ($parametros['status'] as $key => $val)
                                                            <option value="{{ $val->cd_situacao_itens }}" >{{ $val->nm_situacao_itens }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>   
                                            <div class="col-md-1" style="padding-right: 5px; padding-left: 5px;">
                                                <button type="submit" style="font-size: 15px; margin-top: 23px; width: 100%;"
                                                    class="btn btn-success" x-html="buttonPesquisar"> </button>
                                            </div>
                                        </div>

                                    </div>
                                </form>

                                <div class="row">
                                    <div class="col-md-12" style="padding-right: 5px; padding-left: 5px;">
                                        <form class="form-horizontal" action="{{ route('logs.rotina.lote') }}"  method="post">
                                            @csrf
                                            <table class="table table-hover" style="padding-right: 5px; padding-left: 5px;">
                                                <thead>
                                                    <tr class="active">
                                                        <th>#</th>
                                                        <th>Atendimento</th>
                                                        <th>Data</th>
                                                        <th>Laudo</th>
                                                        <th>Paciente</th> 
                                                        <th>Celular</th>
                                                        <th>Profissional</th>
                                                        <th>Exames</th>
                                                        <th>Dt.Envio</th> 
                                                        <th>Situação</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr x-show="loadingPesq">
                                                        <td colspan="10">
                                                            <div class="line">
                                                                <div class="loading"></div>
                                                                <span>Processando Informação...</span>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <template x-if="retornoLista">

                                                        <template x-for=" (query, index)  in paginatedData">
                                                            <tr x-show="!loadingPesq">
                                                                <th>  
                                                                    <template x-if="(query.atendimento?.celular)"> 
                                                                        <input type="checkbox" name="item[]" x-bind:value="query.cd_agendamento_item">  
                                                                    </template>
                                                                    
                                                                </th>
                                                                <th>
                                                                    <span x-html="query.cd_agendamento"></span>
                                                                </th>
                                                                <td>
                                                                    <span x-html="query.atendimento.data_atendimento"></span>
                                                                </td>
                                                                <td>
                                                                    <span x-html="query.data_laudo"></span>
                                                                </td> 
                                                                <td>
                                                                    <span x-html="query.atendimento?.paciente?.nm_paciente"></span>
                                                                </td>
                                                                <td>
                                                                    <span x-html="(query.atendimento?.celular) ? query.atendimento?.celular : '<code>Não Informado</code>'"></span>
                                                                </td>
                                                                <td>
                                                                    <span x-html="query.atendimento?.profissional?.nm_profissional"></span>
                                                                </td>
                                                                <td>
                                                                    <span x-html="query.exame?.nm_exame"></span>
                                                                </td>
                                                                <td> 
                                                                    <span x-html="query.data_envio"></span> 
                                                                </td> 
                                                                <td style="width: 100px;  ">

                                                                    <span class="label" x-on:click="clickModal(query,index)"
                                                                        style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;"
                                                                        x-html="query.status_envio.html"
                                                                        x-bind:class="query.status_envio.classe">

                                                                </td>
                                                            </tr>
                                                        </template>

                                                    </template>

                                                </tbody>
                                            </table>

                                            <div class="row">
                                                <div class="col-md-12" style="text-align: right"  >
                                                    <code style="color: #0062a5; background-color: #f5f6fa;margin-right: 20px;" > <i class="fa fa-asterisk"></i>  Ultima execução da Rotina: <b> {{ \Carbon\Carbon::createFromTimestamp(strtotime($rotina->dt_rotina))->format('d/m/Y H:i')}} </b> </code> 
                                                    <code style="color: #22baa0; background-color: #edf3f3; margin-right: 20px;" > <i class="fa fa-thumbs-up"></i> Enviados:  <span x-html="qtdeEnviado"></span></code> 
                                                    <code style="color: #ed8130; background-color: #f6eee9; margin-right: 20px;"  > <i class="fa fa-thumbs-down"></i> Não Enviados:  <span x-html="qtdeNaoEnviado"></span></code>  
                                                    <code    > <i class="fa fa-close "></i> Erros: <span x-html="qtdeErro"></span> </code>  
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6"  >
                                                    <button type="submit" class="btn btn-success"   >
                                                    <i class="fa fa-send"></i> Enviar 
                                                </button>
                                                </div>

                                                <div class="col-md-6"  >
                                                    <!-- Botões de Paginação -->
                                                    <div align="right">

                                                        <ul class="pagination" style="border-radius: 4px; margin-bottom: 0px;">
                                                            <li>
                                                                <button type="button" class="btn btn-default" @click="goToPage(1)" style="border-radius: 4px 0px 0px 4px;"
                                                                    :disabled="currentPage === 1">
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
                                                                <button type="button" class="btn btn-default" @click="goToPage(totalPages)" style="border-radius: 0px 4px 4px 0px;"
                                                                    :disabled="currentPage === totalPages">
                                                                    <i class="fa fa-angle-double-right"></i>
                                                                </button> 
                                                            </li>
                                                            
                                                        </ul><br>
                                                        <span style="font-style: italic" x-html="'<strong>Pagina </strong> ' + currentPage + ' <strong> de </strong> ' + totalPages"> </span> <br>
                                                        <span style="font-style: italic" x-html="'<strong>Total de Linhas</strong> ' + totalLinhas ">  </span>
                                                        
                                                    </div>
                                                </div>
                                            </div>

                                        </form>

                                    </div>
                                </div>
        
                                <div class="modal fade modalDetalhes">
                                    <div class="modal-dialog modal-lg">

                                        <div class="modal-content">
                                            <div class="modal-header m-b-sm">
                                                <div class="line" style="justify-content: space-between"> 
                                                    <h4 class="modal-title"
                                                        x-html="infoModal.atendimento.paciente.nm_paciente+'<br>'+'Atendimento: '+ infoModal.cd_agendamento+' { ' + infoModal.cd_agendamento_item + ' } '+infoModal.exame.nm_exame">
                                                    </h4>
        
                                                    <div class="linee">

                                                        <button type="button" class="close m-l-sm" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true" style="padding-top: 20px;">
                                                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                                                
                                                            </span>
                                                        </button>
        

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-body">

                                                <div class="line" x-show="loadingModal">
                                                    <div class="loading"></div>
                                                    <span>Processando Informação...</span>
                                                </div>

                                                <table class="table table-hover" style="padding-right: 5px; padding-left: 5px;">
                                                    <thead>
                                                        <tr class="active">
                                                            <th>Codigo</th>
                                                            <th>Data</th>
                                                            <th>Celular</th> 
                                                            <th>Mensagem</th>
                                                            <th>URL</th> 
                                                        </tr>
                                                    </thead>
                                                    <template x-if="HISTORICO">
                                                        <tbody>
                                                        <template x-for=" (query, index)  in HISTORICO.whast_send">
                                                            <tr > 
                                                                <th>
                                                                    <span x-html="query.cd_whast_send"></span>
                                                                </th>
                                                                <td>
                                                                    <span x-html="query.data"></span>
                                                                </td>
                                                                <td>
                                                                    <span x-html="query.nr_send"></span>
                                                                </td>
                                                                <td>
                                                                    <span x-html="query.conteudo"></span>
                                                                </td>
                                                                <td >
                                                                    <span x-html="query.url"></span>
                                                                </td>
                                                            </tr>

                                                        </template>
                                                        </tbody>
                                                    </template>



                                                </table>
        
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
 
                            
                            <div class="tab-pane" role="tabpanel" id="TabAgendamento">

                                <form x-on:submit.prevent="getPageAgendamento" id="form-param-agend" style="">

                                    <div class="row"> 
                                            <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group" style="    margin-bottom: 5px;">
                                                    <label>Tipo: <span class="red normal"> *</span></label>
                                                    <select class="form-control " required name="tp_rotina" style="width: 100%">
                                                        <option value="">...</option>
                                                        @foreach ($parametros['tp_rotina'] as $key => $val)
                                                            <option value="{{ $val->tipo }}">{{ $val->nome }}  </option>
                                                        @endforeach
                                                         
                                                    </select>
                                                </div>
                                            </div>   

                                            <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group" style="    margin-bottom: 5px;">
                                                    <label>Data Envio (Inicio): <span class="red normal">*</span></label>
                                                    <input type="date" class="form-control required" value="{{$request['dti']}}" name="dti" maxlength="10"
                                                        aria-required="true" >
                                                </div>
                                            </div> 

                                            <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group" style="    margin-bottom: 5px;">
                                                    <label>Data Envio (Fim): <span class="red normal">*</span></label>
                                                    <input type="date" class="form-control required" value="{{$request['dtf']}}" name="dtf" maxlength="10"
                                                        aria-required="true" >
                                                </div>
                                            </div>  

                                            <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group" style="    margin-bottom: 5px;">
                                                    <label>Agendamento: <span class="red normal"></span></label>
                                                    <input type="text" class="form-control required" name="agendamento" maxlength="100"
                                                        aria-required="true">
                                                </div>
                                            </div>
                                         
                                            <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group" style="    margin-bottom: 5px;">
                                                    <label>Nome do Paciente: <span class="red normal"></span></label>
                                                    <input type="text" class="form-control required" name="paciente" maxlength="100"
                                                        aria-required="true">
                                                </div>
                                            </div>

                                            <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group" style="    margin-bottom: 5px;">
                                                    <label>Celular: <span class="red normal"></span></label>
                                                    <input type="text" class="form-control required" name="celular" maxlength="100"
                                                        aria-required="true">
                                                </div>
                                            </div>
  
                                            <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group">
                                                    <label>Agenda: <span class="red normal"></span></label>
                                                    <select class="form-control " name="agenda" style="width: 100%">
                                                        <option value="">Todos</option>
                                                        @foreach ($parametros['agenda'] as $key => $val)
                                                            <option value="{{ $val->cd_agenda }}">{{ $val->nm_agenda }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>  
                                            
                                            <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group">
                                                    <label>Profissional: <span class="red normal"></span></label>
                                                    <select class="form-control " name="profissional" style="width: 100%">
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
                                                    <label>Tipo de Atendimento: <span class="red normal"></span></label>
                                                    <select class="form-control " name="tipo_atend" style="width: 100%">
                                                        <option value="">Todos</option>
                                                        @foreach ($parametros['tipo'] as $key => $val)
                                                            <option value="{{ $val->cd_tipo_atendimento }}">{{ $val->nm_tipo_atendimento }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>  

                                            <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                                <div class="form-group">
                                                    <label>Status Envio: <span class="red normal"></span></label>
                                                    <select class="form-control " name="status_envio" style="width: 100%">
                                                        <option value="1">Enviado</option>
                                                        <option value="0">Erro</option>
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
                                        <form class="form-horizontal" action="{{ route('logs.rotina.lote') }}"  method="post">
                                            @csrf
                                            <table class="table table-hover" style="padding-right: 5px; padding-left: 5px;">
                                                <thead>
                                                    <tr class="active">
                                                        <th>Codigo</th>
                                                        <th>Tipo</th>
                                                        <th>Atendimento</th>
                                                        <th>Dt.Envio</th> 
                                                        <th>Paciente</th> 
                                                        <th>Celular</th>
                                                        <th >Mensagem</th> 
                                                        <th style="text-align: center"> Situação</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr x-show="loadingPesqAgenda">
                                                        <td colspan="10">
                                                            <div class="line">
                                                                <div class="loading"></div>
                                                                <span>Processando Informação...</span>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <template x-if="paginatedDataAgend">

                                                        <template x-for=" (query, index)  in paginatedDataAgend">
                                                            <tr x-show="!loadingPesqAgenda">
                                                               
                                                                <th>
                                                                    <span x-html="query.cd_whast_send"></span>
                                                                </th>
                                                                <th>
                                                                    <span x-html="query.tipo.nome"></span>
                                                                </th>
                                                                <td>
                                                                    <span x-html="(query.agendamento?.cd_agendamento) ? query.agendamento?.cd_agendamento : ' -- '"></span>
                                                                </td> 
                                                                <td>
                                                                    <span x-html="query.data_envio"></span>
                                                                </td>
                                                                <td>
                                                                    <span x-html="(query.agendamento?.paciente?.nm_paciente) ? query.agendamento?.paciente?.nm_paciente : query.tab_paciente?.nm_paciente "></span>
                                                                </td>
                                                                <td>
                                                                    <span x-html="query.nr_send"></span>
                                                                </td>
                                                                <td>
                                                                    <span x-html="query.conteudo"></span>
                                                                </td>
                                                                <td style="text-align: center">  
                                                                    <template x-if="(query.from_me==1)">
                                                                        <span style="width: 100%;" class="label label-success">Enviado</span>
                                                                    </template>
                                                                    <template x-if="(query.from_me==0)">
                                                                        <span style="width: 100%;" class="label label-danger">Erro</span>
                                                                    </template>
                                                                   
                                                                </td>  
                                                            </tr>
                                                        </template>

                                                    </template>

                                                </tbody>
                                            </table>
 
                                            <div class="row">
                                                <div class="col-md-6"  >
                                         
                                                </div>

                                                <div class="col-md-6"  >
                                                    <!-- Botões de Paginação -->
                                                    <div align="right">

                                                        <ul class="pagination" style="border-radius: 4px; margin-bottom: 0px;">
                                                            <li>
                                                                <button type="button" class="btn btn-default" @click="goToPage(1)" style="border-radius: 4px 0px 0px 4px;"
                                                                    :disabled="currentPageAgend === 1">
                                                                    <i class="fa fa-angle-double-left"></i>
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <button type="button" class="btn btn-default" @click="previousPage()"
                                                                    :disabled="currentPageAgend === 1">
                                                                    <i class="fa fa-angle-left"></i>
                                                                </button> 
                                                            </li>
                                                            <li>
                                                                <button type="button" class="btn btn-default" @click="nextPage()"
                                                                    :disabled="currentPageAgend === totalPagesAgend">
                                                                    <i class="fa fa-angle-right"></i>
                                                                    
                                                                </button> 
                                                            </li>
                                                            <li>
                                                                <button type="button" class="btn btn-default" @click="goToPage(totalLinhasAgend)" style="border-radius: 0px 4px 4px 0px;"
                                                                    :disabled="currentPageAgend === totalPagesAgend">
                                                                    <i class="fa fa-angle-double-right"></i>
                                                                </button> 
                                                            </li>
                                                            
                                                        </ul><br>
                                                        <span style="font-style: italic" x-html="'<strong>Pagina </strong> ' + currentPage + ' <strong> de </strong> ' + totalPages"> </span> <br>
                                                        <span style="font-style: italic" x-html="'<strong>Total de Linhas</strong> ' + totalLinhas ">  </span>
                                                        
                                                    </div>
                                                </div>
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
        <script src="{{ asset('js/rpclinica/logs-envio.js') }}"></script>
    @endsection
