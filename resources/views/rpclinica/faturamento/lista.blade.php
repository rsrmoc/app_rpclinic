@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <table style="width: 100%">
            <tr>
                <td style="width: 60%">
                    <h3>Relação de Contas</h3>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="{{ route('paciente.listar') }}">Relação</a></li>
                        </ol>
                    </div>
                </td>
                <td style="width: 40%; text-align: right; font-size: 300">
                    <div class="panel-body">
                        <div class="btn-group">

                            <button type="button" style="padding: 20px; font-style: italic;" class="btn btn-default">
                                <span aria-hidden="true" data-toggle="tooltip" title="Conferencia Automatica" data-placement="top"
                                    class="icon-cloud-upload"></span>
                            </button>

                            <button type="button" style="padding: 20px; font-style: italic;" class="btn btn-default"
                                data-toggle="tooltip" data-placement="top" title="Gerar XLS"> 
                                <i class="fa fa-table"></i>
                            </button>


                        </div>
                    </div>
                </td>
            </tr>
        </table>

 

    </div>

    <div id="main-wrapper" x-data="app">
        <div class="col-md-12 ">
            <div class="panel panel-white"><br>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h5>Houve alguns erros:</h5>

                        <ul>
                            {!! implode('', $errors->all('<li>:message</li>')) !!}
                        </ul>
                    </div>
                @endif

                <div class="panel-heading clearfix" style="padding-bottom: 4px; height: auto; padding-top: 5px;">
                    <form x-on:submit.prevent="getPage" id="form-parametros" style="width: 100%">
                        <div class="row">
                            <div class="col-xs-2 " style="padding-left: 10px;">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label>Data Inicial: <spam class="red"> *</spam> </label>
                                    <input type="date" name="dti" value="{{ $parametros['dti'] }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-2 " style="padding-left: 10px;">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label>Data Final: <spam class="red"> *</spam> </label>
                                    <input type="date" name="dtf" value="{{ $parametros['dtf'] }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-2 " style="padding-left: 10px;">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label>Atendimento: </label>
                                    <input type="text" name="atendimento" placeholder="Atendimento"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-2" style="padding-left: 10px;">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label>Beneficiário: </label>
                                    <input type="text" name="beneficiario" placeholder="Beneficiário"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-2 " style="padding-left: 10px;">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label>Nr.Guia: </label>
                                    <input type="text" name="guia" placeholder="Nr Guia" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-2" style="padding-left: 10px;">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label>Situação: </label>
                                    <select class="form-control m-b-sm" style="width: 100%" name="situacao">
                                        <option value="">Selecione</option>
                                        @foreach ($parametros['situacao_conta'] as $val)
                                            <option value="{{ $val->cd_situacao_itens }}">{{ $val->nm_situacao_itens }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-2 "style="padding-left: 10px;">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label>Convênio: </label>
                                    <select class="form-control m-b-sm" style="width: 100%" name="convenio">
                                        <option value="">Selecione</option>
                                        @foreach ($parametros['convenio'] as $val)
                                            <option value="{{ $val->cd_convenio }}">{{ $val->nm_convenio }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3" style="padding-left: 10px;">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label>Agenda: </label>
                                    <select class="form-control m-b-sm" style="width: 100%" name="agenda">
                                        <option value="">Selecione</option>
                                        @foreach ($parametros['agenda'] as $val)
                                            <option value="{{ $val->cd_agenda }}">{{ $val->nm_agenda }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3" style="padding-left: 10px;">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label>Profissional: </label>
                                    <select class="form-control m-b-sm" style="width: 100%" name="profissional">
                                        <option value="">Selecione</option>
                                        @foreach ($parametros['profissional'] as $val)
                                            <option value="{{ $val->cd_profissional }}">{{ $val->nm_profissional }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3" style="padding-left: 10px;">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label>Itens: </label>
                                    <select class="form-control m-b-sm" style="width: 100%" name="cd_item">
                                        <option value="">Selecione</option>
                                        @foreach ($parametros['itens'] as $val)
                                            <option value="{{ $val->cd_exame }}">{{ $val->nm_exame }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-1">
                                <button type="submit" class="btn btn-success" x-html="buttonPesquisar"
                                    style="width: 100%; margin-top: 24px;"></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2" style="padding-left: 10px;">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label style="padding-left: 0px;">
                                            <input name="vencida" value="S" type="checkbox"> Conta Vencida
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-2" style="padding-left: 10px;">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label style="padding-left: 0px;">
                                            <input name="sancoop" value="S" type="checkbox"> Fatura Sancoop
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="display table dataTable table-striped">
                            <thead>
                                <tr>
                                    <th>Atendimento</th>
                                    <th>Data</th>
                                    <th>Paciente</th>
                                    <th>Profissional</th>
                                    <th>Convênio</th>
                                    <th>Item</th>
                                    <th>Guia</th> 
                                    <th class="text-right">Qtde</th>
                                    <th class="text-right">Valor</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">Recebido</th>
                                    <th class="text-right">Glosado</th>
                                    <th class="text-center">Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr x-show="loadingPesq">
                                    <td colspan="13">
                                        <div class="line">
                                            <div class="loading"></div>
                                            <span>Processando Informação...</span>
                                        </div>
                                    </td>
                                </tr>

                                <template x-if="paginatedData">
                                    <template x-for=" (query, index)  in paginatedData">

                                        <tr x-show="!loadingPesq" >
                                            <th>
                                                <span data-toggle="tooltip"
                                                    x-bind:title="query.atendimento?.tab_situacao?.nm_situacao"
                                                    data-placement="top"
                                                    x-html="query.atendimento?.tab_situacao?.icone_classe + ' ' + query.cd_agendamento"></span>
                                            </th>
                                            <td>
                                                <span
                                                    x-html="(query.atendimento.data_atendimento) ? query.atendimento.data_atendimento : (  query.atendimento.data_agenda ) "></span>
                                            </td>
                                            <td>
                                                <span x-html="query.atendimento?.paciente?.nm_paciente"></span>
                                            </td>
                                            <td>
                                                <span
                                                    x-html="(query.atendimento?.profissional) ? query.atendimento?.profissional?.nm_profissional : ''"></span>
                                            </td>
                                            <td>
                                                <span
                                                    x-html="(query.atendimento?.nm_convenio) ? query.atendimento?.nm_convenio : ''"></span>
                                            </td>
                                            <td>
                                                <span x-html="query.exame?.nm_exame"></span>
                                            </td>
                                            <td>
                                                <span x-html="(query.guia?.nr_guia) ? query.guia?.nr_guia : ' -- '"></span>
                                            </td>  
                                            <td class="text-right">
                                                <span x-html="query.qtde"></span>
                                            </td>
                                            <td class="text-right">
                                                <span
                                                    x-html="(query.vl_item) ? query.valor_item : '<code>Erro</code>'"></span>
                                            </td>
                                            <td class="text-right">
                                                <span
                                                    x-html="(query.vl_total) ? query.valor_total : '0,00'"></span>
                                            </td>
                                            <td class="text-right">
                                                <span
                                                    x-html="(query.vl_recebido) ? query.valor_recebido : '0,00'"></span>
                                            </td>
                                            <td class="text-right">
                                                <span
                                                    x-html="(query.vl_glosado) ? query.valor_glosado : '0,00'"></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="label"
                                                data-toggle="modal" data-target="#ModalContas" x-on:click="clickModal(query,index)"
                                                style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;"
                                                x-html="query.status_faturamento?.html"
                                                x-bind:class="query.status_faturamento?.classe">
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
                                        style="border-radius: 0px 4px 4px 0px;" :disabled="currentPage === totalPages">
                                        <i class="fa fa-angle-double-right"></i>
                                    </button>
                                </li>

                            </ul><br>
                            <span style="font-style: italic"
                                x-html="'<strong>Pagina </strong> ' + currentPage + ' <strong> de </strong> ' + totalPages">
                            </span> <br>
                            <span style="font-style: italic" x-html="'<strong>Total de Linhas</strong> ' + totalLinhas ">
                            </span>

                        </div>

                        <div class="box-footer clearfix"></div>
                    </div>


                </div>

            </div>
        </div>
 

        <div class="modal fade" id="ModalContas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="absolute-loading" style="display: none">
                        <div class="line">
                            <div class="loading"></div>
                            <span style="font-weight: bold; font-size: 1.3em; font-style: italic" x-html="loadingAcao"></span>
                        </div>
                    </div>
                    
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel" >
                            <span x-html="dataConta.atendimento?.paciente?.nm_paciente"></span>
                            <small x-html="dataConta.atendimento?.cd_agendamento"> </small>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <form x-on:submit.prevent="storefaturamento" id="form-faturamento" style="width: 100%">
                            
                            <div class="row">
                                <div class="form-group col-md-4" style="padding-right:5px;padding-left:5px; margin-bottom: 5px;">
                                    <label for="exampleInputName">Data Atendimento</label>
                                    <input type="text" class="form-control" readonly x-model="dataConta.atendimento?.data_agenda">
                                </div>
                                <div class="form-group col-md-3" style="padding-right:5px;padding-left:5px; margin-bottom: 5px;">
                                    <label for="exampleInputName">Guia</label>
                                    <input type="text" class="form-control" readonly x-model="dataConta.guia?.nr_guia">
                                </div>
                                <div class="form-group col-md-5" style="padding-right:5px;padding-left:5px; margin-bottom: 5px;">
                                    <label for="exampleInputName">Convênio</label>
                                    <input type="text" class="form-control" readonly x-model="dataConta.atendimento?.nm_convenio">
                                </div>
                                <div class="form-group col-md-8" style="padding-right:5px;padding-left:5px;"> 
                                    <label for="exampleInputName">Item</label>
                                    <input type="text" class="form-control" 
                                        x-model="dataConta.exame?.nm_exame" readonly style="font-weight: bold">
                                </div>
                                <div class="form-group col-md-4" style="padding-right:5px;padding-left:5px;"> 
                                    <label for="exampleInputName">Valor Total</label>
                                    <input type="text" class="form-control" x-model="(dataConta.valor_total) ? dataConta.valor_total : '0,00'"
                                         readonly style="font-weight: bold;text-align: right">
                                </div>
                                <div class="form-group col-md-6" style="padding-right:5px;padding-left:5px;">
                                    <label for="exampleInputName">Situação</label>
                                    <select class="form-control m-b-sm" style="width: 100%" id="situacaoFat" name="situacao">
                                        <option value="">Selecione</option>
                                        @foreach ($parametros['situacao_conta'] as $val)
                                            <option value="{{ $val->cd_situacao_itens }}">{{ $val->nm_situacao_itens }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3" style="padding-right:5px;padding-left:5px;">
                                    <label for="exampleInputName">Recebido</label>
                                    <input type="text" class="form-control" name="vl_recebido" x-mask:dynamic="$money($input, ',')"
                                           x-model="dataConta.valor_recebido"  style="font-weight: bold; text-align: right">
                                </div>
                                <div class="form-group col-md-3" style="padding-right:5px;padding-left:5px;">
                                    <label for="exampleInputName">Glosado</label>
                                    <input type="text" class="form-control" name="vl_glosado"  x-model="dataConta.valor_glosado"   
                                           x-mask:dynamic="$money($input, ',')" style="font-weight: bold; text-align: right">
                                </div>
                            </div>
                            <div style="text-align: center"> 
                                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle-o"></i> Salvar</button>
                            </div>
                        </form>
                        
                    </div>

                </div>
            </div>
        </div>



    </div>
@endsection

@if (auth()->guard('rpclinica')->user()->cd_profissional)
    @section('scripts')
        <style>
            #calendar .datepicker.datepicker-inline {
                width: 100%;
            }

            #calendar .datepicker.datepicker-inline table {
                margin: 0 auto;
                width: 100%;
            }

            .select2-container.select2-container--default.select2-container--open,
            .swal2-container {
                    
            }
        </style>
        <script>
            const query = null;
        </script>
        
        <script src="{{ asset('js/rpclinica/faturamento-listar.js') }}"></script>

    @endsection
@endif
