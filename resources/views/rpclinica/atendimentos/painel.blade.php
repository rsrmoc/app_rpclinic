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
            <div class="col-md-8">
                <h3>Relação de Atendimentos</h3>
                <div class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('consultorio') }}">Relação</a></li>
                    </ol>
                </div>
            </div>

            <div class="col-md-4" style="text-align: right;margin-top: 10px;">

                <div class="btn-group">
      
                    <a href="{{ route('atendimento.add') }}" class="btn btn-default" 
                    data-toggle="tooltip" data-placement="top" title="" data-original-title="Cadastrar Atendimento">
                    <span aria-hidden="true" class="icon-note"></span>
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
    </style>

    <div id="app" x-data="app">
        <div id="main-wrapper" class="">
            <div class="col-md-12 " style="padding-right: 5px; padding-left: 5px;">
                <div class="panel panel-white">
                    <div class="panel-body">    
                        
                        <!-- 
                        <form class="form-horizontal"  action="/rpclinica/json/central-laudos/img/97"
                            enctype="multipart/form-data"  
                            id="form_EXAME_IMG" method="post"> 
                            <div class="form-group " style="margin-bottom: 5px;">
                                @csrf
                                <div class="col-sm-5"
                                    style="padding-right: 5px; padding-left: 5px;">
                                    <label class="control-label">Arquivo: <span class="red normal">*</span></label>
                                    <input type="file" class="form-control" multiple
                                    accept="image/*,application/pdf"  name="image[]">
                                </div>
                                <div class="col-md-5" style="padding-right: 5px; padding-left: 5px;" >  
                                    <label class="control-label">Descrição: <span class="red normal"></span></label>
                                    <input type="text" class="form-control" name="descricao" 
                                    maxlength="100" aria-required="true" > 
                                </div>  
                                <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;" >
                                    <label for="input-help-block"
                                        class="control-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-success"
                                        style="width: 100%;" x-html="buttonSalvarExaImg">
                                    </button>
                                </div>
                            </div> 
                        </form>
                        <br>
                        -->
 
                        <form x-on:submit.prevent="getPage" id="form-parametros" style="">
                            <div class="row">
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Data Inicial: <span class="red normal"></span></label>
                                        <input type="date" class="form-control required" name="dti" value="{{$request['dti']}}" maxlength="10"
                                            aria-required="true" >
                                    </div>
                                </div> 
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>Data Final: <span class="red normal"></span></label>
                                        <input type="date" class="form-control required"  name="dtf" value="{{$request['dtf']}}"  maxlength="10"
                                            aria-required="true" >
                                    </div>
                                </div> 
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
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
                                <!--
                                <div class="col-md-2" style="padding-right: 5px; padding-left: 5px;">
                                    <div class="form-group">
                                        <label>CPF: <span class="red normal"></span></label>
                                        <input type="text" class="form-control required" name="cpf"
                                            maxlength="20" aria-required="true">
                                    </div>
                                </div>  
                                -->
                                <div class="col-md-3" style="padding-right: 5px; padding-left: 5px;">
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
                                            <th>CPF</th>
                                            <th>Contato</th>
                                            <th>Convênio</th>
                                            <th>Profissional</th> 
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
                                                <tr x-show="!loadingPesq">
                                                    <th> 
                                                        <a target="_blank" x-bind:href="'/rpclinica/recepcao-ficha/'+query.cd_agendamento" x-html="query.cd_agendamento"></a>
                                                    </th>
                                                    <td>
                                                        <span x-html="query.data_agenda"></span>
                                                    </td>
                                                    <td>
                                                        <span x-html="query.paciente?.nm_paciente"></span>
                                                    </td>
                                                    <td>
                                                        <span x-html="query.paciente?.cpf"></span>
                                                    </td>
                                                    <td> 

                                                        <template x-if="!query.celular==''" >
                                                            <span> 
                                                                <template x-if="query.whast=='S'" >
                                                                    <span x-html="query.celular+' '+iconeWhasOk"></span>
                                                                </template>
                                                                <template x-if="query.whast!='S'" >
                                                                    <span x-html="query.celular+' '+iconeWhasERRO"></span>
                                                                </template>
                                                            </span>
                                                        </template> 
                                                        
                                                        <template x-if="query.celular==''" >
                                                            <code>Não Informado</code>
                                                        </template>
                                                    </td>
                                                    <td>
                                                        <span x-html="query.convenio?.nm_convenio"></span>
                                                    </td>
                                                    <td>
                                                        <span x-html="query.profissional?.nm_profissional"></span>
                                                    </td> 
                                                    <td>

                                                        <template x-for="(valor, index2) in query.itens">
                                                            <div>  
                                                                <template x-if="((valor.img.length) === 0)">
                                                                    <button type="button"
                                                                        class="btn btn-default btn-rounded btn-xs "
                                                                        style="font-size: 12px;  margin-top: 3px; margin-right: 5px; 
                                                                            background: #f7f6f6; color: #f44336; border: 1px solid #e7847c; font-weight: bold"
                                                                        data-toggle="modal" data-target=".modalAtendimento"
                                                                        x-on:click="getExames(valor,index2,query,index)">
                                                                            <i class="fa fa-stethoscope"
                                                                                style="margin-right: 2px;"></i>
                                                                            <span x-text="valor.exame?.nm_exame"> </span>
                                                                    </button>
                                                                </template>

                                                                <template x-if="((valor.img.length) > 0)">
                                                                    <button type="button"
                                                                        class="btn btn-default btn-rounded btn-xs "
                                                                        style="font-size: 12px;  margin-top: 3px; margin-right: 5px; 
                                                                            background: #f9f9f9; color: #009688; border: 1px solid #62bdb5; font-weight: bold"
                                                                        data-toggle="modal" data-target=".modalAtendimento"
                                                                        x-on:click="getExames(valor,index2,query,index)">
                                                                            <i class="fa fa-stethoscope"
                                                                                style="margin-right: 2px;"></i>
                                                                            <span x-text="valor.exame?.nm_exame"> </span>
                                                                    </button>
                                                                </template>
                                                            </div>
                                                        </template>

                                                    </td>
                                                    <td style="width: 100px;  ">

                                                        <span class="label" x-on:click="clickModal(query,index)"
                                                            style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;"
                                                            x-html="query.situacao.icone + ' '+ query.situacao.nm_situacao"
                                                            x-bind:class="query.situacao.class">

                                                    </td>
                                                </tr>
                                            </template>

                                        </template>

                                    </tbody>
                                </table>
                                <style>
                                    .pagination>li>button, .pagination>li>span {
                                        position: relative;
                                        float: left;
                                        padding: 6px 12px;
                                        margin-left: -1px;
                                        line-height: 1.42857143; 
                                        text-decoration: none;
                                        background-color: #fff;
                                        border: 1px solid #ddd;
                                    }
                                </style>

                                <!-- Botões de Paginação -->
                                <div align="right ">
                                    
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

                        <div class="modal fade modalDetalhes">
                            <div class="modal-dialog modal-lg">

                                <div class="modal-content">
                                    <div class="modal-header m-b-sm">
                                        <div class="line" style="justify-content: space-between"> 
                                            <h4 class="modal-title"
                                                x-html="infoModal.paciente.nm_paciente+' { '+infoModal.cd_agendamento + ' }'">
                                            </h4>
 
                                            <div class="linee">

                                                <button type="button" class="close m-l-sm" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true" style="padding-top: 20px;">
                                                        <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                                        
                                                    </span>
                                                </button>

                                                <a class="close m-l-sm"  x-bind:href="'/rpclinica/atendimento-edit/' + infoModal.cd_agendamento">
                                                    <span aria-hidden="true"   >
                                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                    </span>
                                                </a> 

                                                <a class="close m-l-sm" x-on:click="deleteAtend(infoModal.cd_agendamento)" >
                                                    <span aria-hidden="true"   >
                                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                    </span>
                                                </a> 

                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body">

                                        <div class="line" x-show="loadingModal" >
                                            <div class="loading"></div>
                                            <span>Processando Informação...</span> 
                                        </div>

                                        <div class="slimScrollDiv"
                                            style="position: relative; overflow: hidden; width: auto;  ">
                                            <div class="inbox-widget slimscroll"
                                                style="overflow: hidden; width: auto; height: auto !important;">
                                                <a href="#">
                                                    <div class="row">

                                                        <div class="col-md-2">
                                                            <div class="inbox-item" style="padding: 5px 0;">
                                                                <p class="inbox-item-author" style="line-height: 18px;">
                                                                    Atendimento</p>
                                                                <p class="inbox-item-text"
                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                    x-html="infoModal.cd_agendamento">
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <div class="inbox-item" style="padding: 5px 0;">
                                                                <p class="inbox-item-author" style="line-height: 18px;">
                                                                    Paciente</p>
                                                                <p class="inbox-item-text"
                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                    x-html="infoModal.paciente.nm_paciente">
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="inbox-item" style="padding: 5px 0;">
                                                                <p class="inbox-item-author" style="line-height: 18px;">
                                                                    Data Nascimento</p>
                                                                <p class="inbox-item-text"
                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                    x-html="(infoModal.paciente.data_nasc) ? infoModal.paciente.data_nasc : ' --'">
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="inbox-item" style="padding: 5px 0;">
                                                                <p class="inbox-item-author" style="line-height: 18px;">
                                                                    <i class="fa fa-whatsapp" style="margin-right: 10px;"></i>
                                                                    Celular</p>
                                                                <p class="inbox-item-text"
                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                    x-html="(infoModal.paciente.celular) ? infoModal.paciente.celular : ' --'">
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="inbox-item" style="padding: 5px 0;">
                                                                <p class="inbox-item-author" style="line-height: 18px;">
                                                                    CPF</p>
                                                                <p class="inbox-item-text"
                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                    x-html="(infoModal.paciente.cpf) ? infoModal.paciente.cpf : ' --'">
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="inbox-item" style="padding: 5px 0;">
                                                                <p class="inbox-item-author" style="line-height: 18px;">
                                                                    Data do Atendimento</p>
                                                                <p class="inbox-item-text"
                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                    x-html="infoModal.data_agenda">
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inbox-item" style="padding: 5px 0;">
                                                                <p class="inbox-item-author" style="line-height: 18px;">
                                                                    Profissional</p>
                                                                <p class="inbox-item-text"
                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                    x-html="infoModal.profissional?.nm_profissional"> </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inbox-item" style="padding: 5px 0;">
                                                                <p class="inbox-item-author" style="line-height: 18px;">
                                                                    Local de Atendimento</p>
                                                                <p class="inbox-item-text"
                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                    x-html="(infoModal?.local) ? infoModal.local?.nm_local : ' --'">
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="inbox-item" style="padding: 5px 0;">
                                                                <p class="inbox-item-author" style="line-height: 18px;">
                                                                    Tipo do Atendimento</p>
                                                                <p class="inbox-item-text"
                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                    x-html="(infoModal.tipo_atend) ? infoModal.tipo_atend.nm_tipo_atendimento : ' --'">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="col-md-4">
                                                            <div class="inbox-item" style="padding: 5px 0;">
                                                                <p class="inbox-item-author" style="line-height: 18px;">
                                                                    Convênio</p>
                                                                <p class="inbox-item-text"
                                                                    style="line-height: 18px; color: #3a3939; font-size: 12px;"
                                                                    x-html="infoModal.convenio?.nm_convenio">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <br>
                                                <a href="#">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="inbox-item" style="padding: 5px 0;">
                                                                <p class="inbox-item-author" style="line-height: 21px;">
                                                                    Exames</p>
                                                                <template x-for=" (exa, id)  in infoModal.itens">
                                                                    <p class="inbox-item-text"
                                                                        style="line-height: 21px; color: #3a3939; font-size: 12px;"
                                                                        x-html="(exa.exame) ? exa.exame.nm_exame : '' ">
                                                                    </p>
                                                                </template>

                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>

                                            </div>
                                        </div>

                                    </div>
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
                                                            style="padding: 10px 15px;background-color: #f5f5f5; text-align: left;
                                                        border-top: 1px solid #ddd;border-bottom-right-radius: 3px;border-bottom-left-radius: 3px;">
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

                                                            <div class="col-sm-5"
                                                                style="padding-right: 5px; padding-left: 5px;">
                                                                <label class="control-label">Arquivo: <span class="red normal">*</span></label>
                                                                <input type="file" class="form-control" multiple
                                                                accept="image/*,application/pdf"  name="image[]">
                                                            </div>
                                                            <div class="col-md-5" style="padding-right: 5px; padding-left: 5px;" >  
                                                                <label class="control-label">Descrição: <span class="red normal"></span></label>
                                                                <input type="text" class="form-control" name="descricao" 
                                                                 maxlength="100" aria-required="true" > 
                                                            </div>  
                                                            <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;" >
                                                                <label for="input-help-block"
                                                                    class="control-label">&nbsp;</label>
                                                                <button type="submit" class="btn btn-success"
                                                                    style="width: 100%;" x-html="buttonSalvarExaImg">
                                                                </button>
                                                            </div>
                                                        </div> 
                                                    </form>
                                                    <br>
                                                    <div x-show="loadingModal" class="col-sm-11 col-md-offset-1">
                                                        <div class="line">
                                                            <div class="loading"></div>
                                                            <span>Buscando Imagens...</span>
                                                        </div>
                                                    </div>

                                                    <template x-for="img in EXAME.array_img">

                                                        <template x-if="img.sn_visualiza=='S'">
                                                            
                                                            <div class="panel panel-white">
                                                                <div class="panel-heading">
                                                                    <div class="col-md-12">
                                                                        <h3 class="panel-title"
                                                                            style="font-style: italic; font-weight: 300;"
                                                                            x-html="'<b>' + img.usuario + '&nbsp;&nbsp;&nbsp; [ Olho  : ' + img.descricao + ' ] ' + '</b><br>' + FormatData(img.data)  + ' &nbsp;&nbsp;&nbsp; <span > storage: ' + img.sn_storage + '</span>' ">
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
    
                                                                        <template x-if="(img.tipo=='img')">
                                                                            <img class="img-fluid"
                                                                                style="max-height: 500px;max-width: 100%;"
                                                                                x-bind:src="img.conteudo_img" />
                                                                        </template>
        
                                                                        <template x-if="(img.tipo=='pdf')">
                                                                            <iframe 
                                                                                x-bind:src="img.conteudo_img"
                                                                                frameBorder="0"
                                                                                scrolling="auto" 
                                                                                style="height: 500px; width: 100%; border: 5px solid #525659">
                                                                            </iframe>
                                                                        </template> 

                                                                </div>
                                                            </div>

                                                        </template>
            
                                                    </template>
                                                         
                                                    <div class="row">
                                                        <template x-for="img in EXAME.array_img">

                                                            <template x-if="img.sn_visualiza=='N'">
    
                                                                    <div class="col-md-4  " style="text-align: center; "> 
                                                                
                                                                        <div class="panel panel-white" style="border: 1px solid #dce1e4;">
                                                                            <div class="panel-heading"> 
                                                                                    <h3 class="panel-title"
                                                                                        style="font-style: italic; font-weight: 300;"
                                                                                        x-html="FormatData(img.data) ">
                                                                                    </h3>
                                                                                    <div class="panel-control">
                                                                                        <a href="javascript:void(0);" data-toggle="tooltip"
                                                                                            x-on:click="deleteExaImg(img.cd_img_formulario)"
                                                                                            data-placement="top" title=""
                                                                                            data-original-title="Exluir"><i
                                                                                                class="icon-close"></i></a>
                                                                                    </div> 
                                                                            </div>
                                                                            <div class="panel-body" style="text-align: center;  margin-top: 20px;">
                
                                                                                <template x-if="img.tipo=='pdf'">
                                                                                    <a x-bind:href="'/rpclinica/central-laudos-visualizar/doc/'+img.codigo" target="_blank">
                                                                                        <img class="img-fluid "
                                                                                        style="max-width: 100%; "
                                                                                        src="{{ asset('assets\images\ficheiro-pdf.png') }}">
                                                                                    </a>
                                                                                </template>

                                                                                <template x-if="img.tipo=='img'">
                                                                                    <a x-bind:href="'/rpclinica/central-laudos-visualizar/doc/'+img.codigo" target="_blank">
                                                                                        <img class="img-fluid "
                                                                                        style="max-width: 100%; "
                                                                                        src="{{ asset('assets\images\ficheiro-imagem.png') }}">
                                                                                    </a>
                                                                                </template>

                                                                                <br>
                                                                                <h5 
                                                                                    style="font-style: italic; text-align: left; font-weight: 300; font-size: 13px;"
                                                                                    x-html="img.descricao ">
                                                                                </h5>
                                                                                
                                                                            </div>
                                                                        </div>

                                                                    </div> 
                                                                    
                                                            </template> 
                                                        </template>
                                                    </div>

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

            .text-danger {
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
        <script src="{{ asset('js/rpclinica/atendimento.js') }}"></script>
    @endsection
