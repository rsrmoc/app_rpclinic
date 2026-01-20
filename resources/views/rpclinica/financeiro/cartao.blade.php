@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title" style="padding-bottom: 10px;  ">
        <h3>Fatura - Cartão de Crédito</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{route('financeiro.listar')}}">Relação</a></li>
            </ol>
        </div>
    </div>
    <style>
        /* Force Dark Theme Overrides */
        .page-content, .page-inner, .tab-content, .tab-pane, body {
            background-color: transparent !important;
        }
        .panel {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #cbd5e1 !important;
        }
        .panel-body {
            background: transparent !important;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }
        .form-control:focus {
            border-color: #2dd4bf !important;
            box-shadow: 0 0 0 2px rgba(45, 212, 191, 0.2) !important;
        }
        label {
            color: #cbd5e1 !important;
        }
        select option {
            background-color: #1e293b !important;
            color: #fff !important;
        }
        .checker span {
            border-color: #64748b !important;
        }

        .btn-group > .btn {
            padding: 6px 10px;

        }
        .btn-default.active{
            background-color: #0f766e;
            color: white;
            font-weight: 900;
        }
    </style>
    <div id="main-wrapper" x-data="app">
        <div id="main-wrapper">

            <div class="col-md-12 ">


                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified" role="tablist">

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade in" >

                                <div class="panel glass-panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <form x-on:submit.prevent="submitFilters" id="form-horario" class="col-md-3"
                                                style="margin-bottom: 22px">
                                                <div class="form-data" style="margin-bottom: 30px" >
                                                    <div  class="col-md-12" style="padding-right: 2px;  padding-left: 2px;">
                                                        <label>Cartão de Crédito:</label>
                                                        <select class="form-control" name="profissional" style="width: 100%;" id="filtersCartao">
                                                            <option value="">Todas as Categorias</option>
                                                            @foreach ($cartoes as $cartao)
                                                                <option value="{{ $cartao->cd_conta }}">{{ $cartao->nm_conta }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <br><br>
                                                <div class="form-data" style="margin-bottom: 30px" >
                                                    <div  class="col-md-12" style="padding-right: 2px;  padding-left: 2px;">
                                                        <label>Forma de Pagamento:</label>
                                                        <select class="form-control" name="profissional" style="width: 100%;" id="filtersForma">
                                                            <option value="">Todas as Formas </option>
                                                            @foreach ($formasPagamento as $forma)
                                                                <option value="{{ $forma->cd_forma_pag }}">{{ $forma->nm_forma_pag }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <br><br>

                                                <div class="form-data" style="margin-bottom: 30px" >

                                                    <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                                        <div  class="col-md-4" style="padding-right: 2px;  padding-left: 2px;">
                                                            <label style="padding: 0">
                                                                <div class="checker">
                                                                    <span>
                                                                        <input type="checkbox" id="checkboxAberto" checked x-model="inputsFilter.aberto" />
                                                                    </span>
                                                                </div> Em Aberto
                                                            </label>
                                                        </div>
                                                        <div  class="col-md-4" style="padding-right: 2px;  padding-left: 2px;">
                                                            <label style="padding: 0">
                                                                <div class="checker">
                                                                    <span>
                                                                        <input type="checkbox" id="checkboxFechado" checked x-model="inputsFilter.fechado" />
                                                                    </span>
                                                                </div> Fechado
                                                            </label>
                                                            </div>
                                                    </div>

                                                    <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                                        <div  class="col-md-4" style="padding-right: 2px;  padding-left: 2px;">
                                                        <label style="padding: 0">
                                                            <div class="checker">
                                                                <span>
                                                                    <input type="checkbox" id="checkboxQuitado" checked x-model="inputsFilter.quitado" />
                                                                </span>
                                                            </div> Quitado
                                                        </label>
                                                        </div> 
                                                    </div>
                                                </div>

                                                <br>
                                                <div class="form-data" style="margin-bottom: 30px" >
                                                    <div  class="col-md-12" style="padding-right: 2px;  padding-left: 2px;">

                                                        <button type="submit" class="btn btn-success " style="width:100%; " >
                                                            <span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Pesquisar
                                                        </button>
                                                    </div>
                                                </div>

                                                <br /><br />

                                                <template x-if="cartaoFiltroSelecionado">
                                                    <table class="table   table-hover" style="border: 1px solid #ddd; ">
                                                        <thead>
                                                            <tr class="active">
                                                                <th class="text-center" style="border-bottom: #22BAA0 solid 2px;;">Resumo do Cartão</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <strong> <i class="fa fa-long-arrow-right"></i> Dia de Fechamento : </strong>
                                                                    <span x-text="cartaoFiltroSelecionado.dia_fechamento "></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong> <i class="fa fa-long-arrow-right"></i> Dia de Vencimento : </strong>
                                                                    <span x-text="cartaoFiltroSelecionado.dia_vencimento"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong> <i class="fa fa-long-arrow-right"></i> Limite : </strong>
                                                                    <span x-text="formatValor(cartaoFiltroSelecionado.vl_limite)"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong> <i class="fa fa-long-arrow-right"></i> Saldo : </strong>
                                                                    <span x-html="saldoCartao"></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </template>
                                            </form>

                                            <div class="col-md-9">
                                                {{-- <template x-if="messageDanger">
                                                    <div class="alert alert-danger">
                                                        <span x-text="messageDanger"></span>
                                                        <button x-on:click="messageDanger = null" type="button"
                                                            class="close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                </template> --}}
                                                <div style="text-align: center;">
                                                    <div class="btn-group" role="group" aria-label="First group" style="margin-bottom: 10px;">
                                                        <button type="button" class="btn btn-default" x-on:click="prevYear">
                                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                        </button>
                                                        <div class="btn btn-default" x-text="inputsFilter.ano"></div>
                                                        <button type="button" class="btn btn-default" x-on:click="nextYear">
                                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                        </button>
                                                    </div>

                                                    <div class="btn-group" role="group" aria-label="First group" style="margin-bottom: 10px; margin-left: 15px;;">
                                                        <button type="button" class="btn btn-default" style="  font-weight: bold;" x-on:click="prevMonth">
                                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                        </button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 1 }" x-on:click="selectMonth(1)">Jan</button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 2 }" x-on:click="selectMonth(2)">Fev</button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 3 }" x-on:click="selectMonth(3)">Mar</button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 4 }" x-on:click="selectMonth(4)">Abr</button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 5 }" x-on:click="selectMonth(5)">Mai</button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 6 }" x-on:click="selectMonth(6)">Jun</button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 7 }" x-on:click="selectMonth(7)">Jul</button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 8 }" x-on:click="selectMonth(8)">Ago</button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 9 }" x-on:click="selectMonth(9)">Set</button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 10 }" x-on:click="selectMonth(10)">Out</button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 11 }" x-on:click="selectMonth(11)">Nov</button>
                                                        <button type="button" class="btn btn-default" x-bind:class="{ active: inputsFilter.mes == 12 }" x-on:click="selectMonth(12)">Dez</button>
                                                        <button type="button" class="btn btn-default" style=" font-weight: bold;" x-on:click="nextMonth">
                                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                        </button>
                                                    </div>
                                                </div>

                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr class="active">

                                                            <th>Descrição</th>
                                                            <th>Competência</th>
                                                            <th>Inicio</th>
                                                            <th>Fechamento</th>
                                                            <th>Vencimento</th>
                                                            <th class="right">Valor Fatura</th>
                                                            <th class="right">Valor Pago</th>
                                                            <th> </th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>

                                                        <template x-for="cartao in cartoes">
                                                            <tr> 
                                                                <td x-text="cartao.descricao"></td>
                                                                <td x-text="cartao.fechamento"></td>
                                                                <td x-text="cartao.data_abertura"></td>
                                                                <td x-text="cartao.fechamento"></td>
                                                                <td x-text="cartao.vencimento"></td> 
                                                                <td class="right">
                                                                    <strong x-text=" formatValor(cartao.vl_boletos)"></strong>
                                                                </td>
                                                                <td class="right">

                                                                    <strong x-text="formatValor(cartao.vl_fatura)"></strong>
                                                                </td>
                                                                <td class="center" >
                                                                    <span class="label" x-bind:class="classStyleStatusCartao[cartao.situacao]" style="cursor: pointer;"
                                                                        x-text="cartao.situacao" x-on:click="selectCartao(cartao)"></span>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="modal fade" id="modal-detalhes">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <template x-if="loadingModalCartao">
                                                            <x-absolute-loading message="Carregando..." />
                                                        </template>

                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <h4 class="modal-title" x-text="cartaoSelecionado.descricao"></h4>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="panel-body">
                                                                <div role="tabpanel">
                                                                    <!-- Nav tabs -->
                                                                    <ul class="nav nav-tabs" role="tablist">
                                                                        <li role="presentation" class="active">
                                                                            <a href="#tabResumo" role="tab" style="border-bottom: hidden;" data-toggle="tab">Resumo da Fatura</a>
                                                                        </li>

                                                                        <li role="presentation">
                                                                            <a href="#tabLanc" style="border-bottom: hidden;"
                                                                                x-bind:style=" ( cartaoSelecionado.situacao == 'ABERTA' ) ? 'pointer-events: none; cursor: not-allowed; opacity: .5;' : '' "
                                                                                role="tab" data-toggle="tab">Detalhes do Pagamento</a>
                                                                        </li>
                                                                    </ul>
                                                                    <!-- Tab panes -->
                                                                    <div class="tab-content">
                                                                        <div role="tabpanel" class="tab-pane active" id="tabResumo">
                                                                            <table class="table table-striped table-hover">
                                                                                <thead>
                                                                                    <tr class="active">

                                                                                        <th>Data</th>
                                                                                        <th>Documento</th>
                                                                                        <th>Descrição</th>
                                                                                        <th>Fornecedor</th>
                                                                                        <th>Vencimento</th>
                                                                                        <th class="right">Valor</th>
                                                                                    </tr>
                                                                                </thead>

                                                                                <tbody>
                                                                                    <template x-for="lancamento in cartaoSelecionado?.boleto_item">
                                                                                        <tr>
                                                                                            <td x-text="lancamento.dt_compra"></td>
                                                                                            <td x-text="lancamento.doc_boleto"></td>
                                                                                            <td x-text="lancamento.ds_boleto"></td>
                                                                                            <td x-text="lancamento.fornecedor?.nm_fornecedor"></td>
                                                                                            <td x-text="lancamento.data_vencimento"></td>
                                                                                            <td class="right">
                                                                                                <strong x-text="formatValor(lancamento.vl_boleto)"></strong>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </template>
                                                                                </tbody>
                                                                                <thead>
                                                                                    <tr class="active">
                                                                                        <th colspan="5">
                                                                                            <template x-if="cartaoSelecionado.situacao   == 'ABERTA'">
                                                                                                <span class="label label-primary" style="cursor: pointer"
                                                                                                    x-on:click="fecharFatura">
                                                                                                    <i class="fa fa-check-square-o"></i> Fechar Fatura
                                                                                                </span>
                                                                                            </template>

                                                                                            <template x-if="cartaoSelecionado.situacao ==  'FECHADA'">
                                                                                                <div style="display: flex; gap: 6px;">
                                                                                                    <span class="label label-default" style="color: #4E5E6A;">
                                                                                                        <i class="fa fa-check-square-o"></i> Fatura fechada
                                                                                                    </span>

                                                                                                    <span  class="label label-warning" style="cursor: pointer" x-on:click="abrirFatura(cartaoSelecionado.cd_fatura)">
                                                                                                        <i class="fa fa-check-square-o"></i> Abrir fatura
                                                                                                    </span>
                                                                                                </div>
                                                                                            </template>

                                                                                            <template x-if="cartaoSelecionado.situacao ==  'QUITADA'">
                                                                                                <div style="display: flex; gap: 6px;">
                                                                                                <span  class="label label-default" style="color: #22BAA0">
                                                                                                    <i class="fa fa-check-square-o"></i> Fatura paga
                                                                                                </span>

                                                                                                <span  class="label label-danger" style="cursor: pointer" x-on:click="estornarFatura(cartaoSelecionado.cd_fatura)">
                                                                                                    <i class="fa fa-mail-reply"></i> Estornar Pagamento
                                                                                                </span>
                                                                                                </div>
                                                                                            </template>
                                                                                        </th>

                                                                                        <th class="right" x-text="formatValor(sumValueItemsCartoes(cartaoSelecionado?.lancamentos))"></th>
                                                                                    </tr>
                                                                                </thead>
                                                                            </table>
                                                                        </div>

                                                                        <div role="tabpanel" class="tab-pane" id="tabLanc">


                                                                            <form x-on:submit.prevent="atualizarFatura" id="atualizarFatura">
                                                                                <div class="row"> 
                                                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                                                        <div class="form-group">
                                                                                            <div class="mat-div">
                                                                                                <label>Conta <span class="red normal">*</span> </label>
                                                                                                <select name="cd_conta_pag"
                                                                                                    class="js-states form-control select2-hidden-accessible"
                                                                                                    tabindex="-1" style="width: 100%"
                                                                                                    required
                                                                                                    aria-hidden="true"
                                                                                                    id="contaModalCartao">
                                                                                                    <option value="">Selecione</option>
                                                                                                    @foreach ($contas as $conta)
                                                                                                        <option value="{{ $conta->cd_conta }}">{{ $conta->nm_conta }}</option>
                                                                                                    @endforeach
                                                                                            </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                                                                        <div class="form-group">
                                                                                            <div class="mat-div">
                                                                                                <label>Forma de Pagamento <span class="red normal">*</span> </label>
                                                                                                <select name="cd_forma"
                                                                                                    class="js-states form-control select2-hidden-accessible"
                                                                                                    tabindex="-1" style="width: 100%"
                                                                                                    required
                                                                                                    aria-hidden="true"
                                                                                                    id="formaModalCartao">
                                                                                                    <option value="">Selecione</option>
                                                                                                    @foreach ($formasPagamento as $forma)
                                                                                                        <option value="{{ $forma->cd_forma_pag }}">{{ $forma->nm_forma_pag }}</option>
                                                                                                    @endforeach
                                                                                            </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                                                        <div class="form-group">
                                                                                            <div class="mat-div">
                                                                                                <label>Descrição <span class="red normal">*</span> </label>
                                                                                                <input type="text" class="form-control" name="ds_pagamento" required x-model="cartaoSelecionado.ds_pagamento" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                                                                        <div class="form-group">
                                                                                            <div class="mat-div">
                                                                                                <label>Valor Da Fatura <span class="red normal"></span> </label>
                                                                                                <input type="text" class="form-control" readonly name="vl_fatura" x-model="cartaoSelecionado.valor_fatura"  />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                                                                        <div class="form-group">
                                                                                            <div class="mat-div">
                                                                                                <label>Documento <span class="red normal"></span> </label>
                                                                                                <input type="text" class="form-control" name="documento"  x-model="cartaoSelecionado.documento" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                                                                        <div class="form-group">
                                                                                            <div class="mat-div">
                                                                                                <label>Data do Pagamento <span class="red normal">*</span> </label>
                                                                                                <input type="date" class="form-control" name="dt_pagamento" required x-model="cartaoSelecionado.dt_pagamento" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                                                                        <div class="form-group">
                                                                                            <div class="mat-div">
                                                                                                <label>Valor Pago <span class="red normal">*</span> </label>
                                                                                                <input type="text" class="form-control" name="vl_pago" x-mask:dynamic="$money($input, ',')" x-model="cartaoSelecionado.valor_pago"required  />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <template x-if="cartaoSelecionado.situacao == 'FECHADA'">
 
                                                                                    <div class="text-right">
                                                                                        <button type="submit" class="btn btn-success" x-html="buttonSalvar" >Salvar</button>
                                                                                    </div>

                                                                                </template>
                                                                            </form>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"  data-dismiss="modal">Fechar</button>
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
    </div>
@endsection

@section('scripts')
    <style>
        .select2-container.select2-container--default.select2-container--open,
        .swal2-container {
            z-index: 9999;
        }
    </style>
    <script>
        const cartoesParaSelecao = @js($cartoes);
    </script>
    <script src="{{ asset('/js/rpclinica/financeiro-cartao.js') }}"></script>
@endsection
