@extends('rpclinica.layout.layout')

@section('content')

<style>
    .btn_add {
        color: #899dc1;
        cursor: pointer;
    }
 
 
</style>
<div class="page-title">
    <h3>Cadastro de Lançamentos</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="index-2.html">Cadastrar</a></li>
        </ol>
    </div>
</div>
<div id="main-wrapper">

    <div class="col-md-12 ">

        <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#TabLancamentos" role="tab" data-toggle="tab"
                        id="buttonTabAgendamentos"> Lançamentos</a></li>
                <li role="presentation"><a href="#tabTransferencia" role="tab" data-toggle="tab"> Transferencias</a>
                </li>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div x-data="appLancamentos" role="tabpanel" class="tab-pane active fade in" id="TabLancamentos">

                        <form x-on:submit.prevent="salvarLancamento" class="panel panel-white">
                            <div class="panel-body">
    
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12  ">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Empresa <span class="red normal">*</span> </label>
                                                <select name="empresa"
                                                    class="js-states form-control select2-hidden-accessible"
                                                    tabindex="-1" style="width: 100%" required aria-hidden="true"
                                                    id="lancamentosEmpresas">
                                                    <option value="">Selecione</option>
                                                    @foreach ($empresas as $empresa)
                                                    <option value="{{ $empresa->cd_empresa }}">
                                                        {{ $empresa->nm_empresa }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <style>
                                        .select2-container--default .select2-results__option[aria-disabled=true] {
                                            color: black;
                                            cursor: default;
                                            display: block;
                                            padding: 6px;
                                            font-weight: bold;
                                        }
                                    </style>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Categoria <span class="red normal">*</span> </label>
                                                <select name="categoria"
                                                    class="js-states form-control select2-hidden-accessible js-example-basic-single "
                                                    tabindex="-1" style="width: 100%" required aria-hidden="true"
                                                    id="lancamentosCategorias">
                                                    <option value="">Selecione</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-sm-8 col-xs-12 ">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Conta ou Cartão <span class="red normal">*</span> </label>
                                                <select name="conta"
                                                    class="js-states form-control select2-hidden-accessible"
                                                    tabindex="-1" style="width: 100%" required aria-hidden="true"
                                                    id="lancamentosConta">
                                                    <option value="">Selecione</option>
                                                    @foreach ($contasBancaria as $conta)
                                                    <option value="{{ $conta->cd_conta }}"
                                                        data-tp="{{ $conta->tp_conta }}">{{ $conta->nm_conta }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-8 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label><span class="glyphicon glyphicon-plus btn_add"
                                                        @click="modal('FORMA')" aria-hidden="true"></span> &nbsp; Forma
                                                    de Pagamento <span class="red normal">*</span> </label>
                                                <select name="forma"
                                                    class="js-states form-control select2-hidden-accessible"
                                                    tabindex="-1" style="width: 100%" required aria-hidden="true"
                                                    id="lancamentosFormaPagamento">
                                                    <option value="">Selecione</option>
                                                    @foreach ($formasPagamento as $forma)
                                                    <option value="{{ $forma->cd_forma_pag }}" data-tp="{{ $forma->tipo }}" >
                                                         {{ $forma->nm_forma_pag }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label><span class="glyphicon glyphicon-plus btn_add"
                                                        @click="modal('FORN')" aria-hidden="true"></span> &nbsp; Cliente
                                                    e Fornecedor <span class="red normal">*</span> </label>
                                                <select name="fornecedor"
                                                    class="js-states form-control select2-hidden-accessible"
                                                    tabindex="-1" style="width: 100%" required aria-hidden="true"
                                                    id="lancamentosFornecedor">
                                                    <option value="">Selecione</option>
                                                    @foreach ($fornecedores as $fornecedor)
                                                    <option value="{{ $fornecedor->cd_fornecedor }}">
                                                        {{ $fornecedor->nm_fornecedor }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="row">
                 
                                    <div class="col-md-4 col-sm-8 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label><span class="glyphicon glyphicon-plus btn_add"
                                                        @click="modal('SETOR')" aria-hidden="true"></span> &nbsp; Setor
                                                    <span class="red normal"></span> </label>
                                                <select name="setor"
                                                    class="js-states form-control select2-hidden-accessible"
                                                    tabindex="-1" style="width: 100%"   aria-hidden="true"
                                                    id="lancamentosSetores">

                                                    <option value="">Selecione</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-8 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>  Turma
                                                    <span class="red normal"></span> </label>
                                                <select name="turma"
                                                    class="js-states form-control select2-hidden-accessible"
                                                    tabindex="-1" style="width: 100%" aria-hidden="true"
                                                    id="lancamentosTurmas">
                                                    <option value="">Selecione</option>
                                                 
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-8 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Evento
                                                    <span class="red normal"></span> </label>
                                                <select name="evento"
                                                    class="js-states form-control select2-hidden-accessible"
                                                    tabindex="-1" style="width: 100%" aria-hidden="true"
                                                    id="lancamentosEventos">
                                                    <option value="">Selecione</option> 
                                                 
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!--
                                    <div class="col-md-4 col-sm-8 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label><span class="glyphicon glyphicon-plus btn_add"
                                                        @click="modal('MARCA')" aria-hidden="true"></span> &nbsp; Marca
                                                    <span class="red normal"></span> </label>
                                                <select name="marca"
                                                    class="js-states form-control select2-hidden-accessible"
                                                    tabindex="-1" style="width: 100%" aria-hidden="true"
                                                    id="lancamentosMarcas">
                                                    <option value="">Selecione</option>
                                                    @foreach ($marcas as $marca)
                                                    <option value="{{ $marca->cd_marca }}">{{ $marca->nm_marca }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    -->


                                </div>

                                <div class="row">
                                    <div class="col-md-8 col-sm-12 col-xs-12">
                                        <label class="mat-label">Descrição <span class="red normal">*</span></label>
                                        <input type="text" class="form-control required" required="" value=""
                                            name="descricao" maxlength="100" aria-required="true"
                                              id="lancamentosDescricao"
                                            x-model="inputsLancamento.descricao">
                                    </div>

                                    <div class="col-md-2 col-sm-6 col-xs-12 ">
                                        <label class="mat-label">Data de emissão <span
                                                class="red normal"></span></label>
                                        <input type="date" class="form-control required" value="" name="dt_emissao"
                                            maxlength="100" aria-required="true" placeholder="Descrição"
                                            x-model="inputsLancamento.dt_emissao">
                                    </div>

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="mat-label">Documento <span class="red normal"></span></label>
                                            <input type="text" class="form-control required" value="" name="documento"
                                                maxlength="100" aria-required="true"   x-model="inputsLancamento.documento">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12"  >
                                        <div class="col-md-12 col-sm-12 col-xs-12" style="background: #f9f9f9; padding: 7px;border: 1px solid #dce1e4; "> 
                                            <div class="col-md-10 col-md-offset-1"  >
                                                <label class="panel-title">Tipo de Lançamento </label>
                                                &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                                <label style="padding: 0" class="panel-title red" id="labelLancamentosDespesa">
                                                    <span>
                                                        <input type="radio" name="tp_lancamento"
                                                            x-model="inputsLancamento.tp_lancamento" value="despesa" />
                                                    </span><i class="fa fa-arrow-down"></i> Despesa
                                                </label>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <label style="padding: 0" class="panel-title text-info"
                                                    id="labelLancamentosReceita">
                                                    <span>
                                                        <input type="radio" name="tp_lancamento"
                                                            x-model="inputsLancamento.tp_lancamento" value="receita" />
                                                    </span>
                                                    <i class="fa fa-arrow-up"></i> Receita
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               

                                <div class="row m-b-md" style="margin-top: 10px;">

                                    <div class="col-md-2 col-sm-4 col-xs-12 ">
                                        <label
                                            x-text="disabledInputsPagamento==false ? 'Data do Vencimento' : disabledInputsPagamento==true ? 'Data da compra' : 'Data'"><span
                                                class="red normal">*</span></label>
                                        <input type="date" class="form-control required" required="" value=""
                                            name="data_vencimento" maxlength="100" aria-required="true"
                                            placeholder="Descrição" x-model="inputsLancamento.data_vencimento">   
                                    </div>

                                    <div class="col-md-2 col-sm-4 col-xs-12 ">
                                        <label class="moedaReal"
                                            x-html="inputsLancamento.tp_lancamento === 'despesa' ? 'Valor Contas a Pagar' : inputsLancamento.tp_lancamento === 'receita' ? 'Valor Contas a Receber' : 'Valor'">
                                            </label>
                                        <input   class="form-control   required" name="valor" maxlength="100" x-mask:dynamic="$money($input, ',')"
                                        aria-required="true"  x-model="inputsLancamento.valor">
                                    </div>

                                    <template x-if="!disabledInputsPagamento">
                                        <div class="col-md-2 col-sm-4 col-xs-12 ">
                                            <label
                                                x-text="inputsLancamento.tp_lancamento === 'despesa' ? 'Data do Pagamento' : inputsLancamento.tp_lancamento === 'receita' ? 'Data do Recebimento' : 'Data Pag./Rec.'">
                                            </label>
                                            <input type="date" class="form-control required" value=""
                                                name="data_pagamento" maxlength="100" aria-required="true"
                                                placeholder="Descrição" x-model="inputsLancamento.data_pagamento">
                                        </div>
                                    </template>

                                    <template x-if="!disabledInputsPagamento">
                                        <div class="col-md-2 col-sm-4 col-xs-12 ">
                                            <label
                                                x-text="inputsLancamento.tp_lancamento === 'despesa' ? 'Valor do Pagamento' : inputsLancamento.tp_lancamento === 'receita' ? 'Valor do Recebimento' : 'Valor Pag./Rec.'">
                                            </label>
                                            <input    class="form-control   required" x-mask:dynamic="$money($input, ',')"
                                                value="" name="valor_pago" maxlength="100" aria-required="true"
                                                x-model="inputsLancamento.valor_pago" />
                                        </div>
                                    </template>

                                    <div class="col-md-2 col-sm-4 col-xs-12" x-show="inputsLancamento.parcelar">
                                        <label  >Periodicidade </label>
                                        <select name="periodicidade"
                                            class="js-states form-control select2-hidden-accessible" tabindex="-1"
                                            style="width: 100%" x-bind:required="inputsLancamento.parcelar"
                                            aria-hidden="true" id="lancamentosPeriodicidade">
                                            <option value="">Periodicidade</option>
                                            <option value="mensal">Mensal</option>
                                            <option value="quinzenal">Quinzenal</option>
                                            <option value="semanal">Semanal</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 col-sm-4 col-xs-12" x-show="inputsLancamento.parcelar">
                                        <label >Parcelas </label> 
                                        <select name="parcelas" class="js-states form-control select2-hidden-accessible"
                                            tabindex="-1" style="width: 100%"
                                            x-bind:required="inputsLancamento.parcelar" aria-hidden="true"
                                            id="lancamentosParcelas">
                                            <option value="">Parcelas</option>
                                            @foreach (range(1, 180) as $val)
                                            <option value="{{ $val }}">
                                                {{ ($val==1) ? $val.' Parcela' : $val.' Parcelas'  }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6 col-sm-12 col-xs-12 ">
                                        <label style="padding: 0">
                                            <div class="checker">
                                                <span>
                                                    <input type="checkbox" name="parcelar"
                                                        x-model="inputsLancamento.parcelar" />
                                                </span>
                                            </div> Parcelar
                                        </label>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-xs-12" x-show="inputsLancamento.parcelar">
                                        <label style="padding: 0">
                                            <div class="checker">
                                                <span>
                                                    <input type="checkbox" name="dividir_parcelas"
                                                        x-model="inputsLancamento.dividir_parcelas" />
                                                </span>
                                            </div> Dividir entre Parcelas
                                        </label>
                                    </div>
 
                                </div>

                                <hr>

                                <template x-for="parcela, index in inputsLancamento.parcelas">
                                    <div> 
                                        <template x-if="index==0">
                                            <div class="row" style="margin-top: 12px;">
                                                <div class="col-md-1 col-sm-2 col-xs-12 " style="padding-right:2px; padding-left: 2px;"> <label class="bold">Parcela <span class="red normal">*</span> </label> </div>
                                                <div class="col-md-3 col-sm-6 col-xs-12 " style="padding-right:2px; padding-left: 2px;"> <label class="bold">Descrição <span class="red normal">*</span> </label> </div>
                                                <div class="col-md-1 col-sm-2 col-xs-12 " style="padding-right:2px; padding-left: 2px;"> <label class="bold">Documento <span class="red normal">*</span> </label> </div> 
                                                <div class="col-md-6 col-sm-10 col-xs-12 " style="padding:0px; "> 
                                                    <template x-if="disabledInputsPagamento==true"> 
                                                        <div class="col-md-3 col-sm-2 col-xs-12 " style="padding-right:2px; padding-left: 2px;"> <label class="bold" > Data da compra </label> <span class="red normal">*</span></div>
                                                    </template>
                                                    <div class="col-md-3 col-sm-2 col-xs-12 " style="padding-right:2px; padding-left: 2px;"> <label class="bold" >Data do Vencimento  </label> <span class="red normal">*</span></div>
                                                    <div class="col-md-3 col-sm-2 col-xs-12 " style="padding-right:2px; padding-left: 2px;"> <label class="bold">Valor Parcela <span class="red normal">*</span> </label> </div>
                                                    <template x-if="disabledInputsPagamento==false"> 
                                                        <div>
                                                            <div class="col-md-3 col-sm-2 col-xs-12 " style="padding-right:2px; padding-left: 2px;"> <label class="bold">Data Quitação <span class="red normal">*</span> </label> </div>
                                                            <div class="col-md-3 col-sm-2 col-xs-12 " style="padding-right:2px; padding-left: 2px;"> <label class="bold">Valor Quitação <span class="red normal">*</span> </label> </div>
                                                        </div>
                                                    </template>
                                                
                                                </div>
                                            </div>
                                        </template>
                                        <div class="row" style="margin-bottom: 12px;">
                                            <div class="col-md-1 col-sm-2 col-xs-12  "
                                                style="padding-right:2px; padding-left: 2px;">
                                                <div class="form-control disabled"
                                                    style="text-align: center; font-weight: 900;" x-text="index + 1">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-xs-12 "
                                                style="padding-right:2px; padding-left: 2px;">
                                                <input type="text" class="form-control required" required="" value=""
                                                    name="parcelas[][descricao]" maxlength="100" aria-required="true"
                                                    placeholder="Descrição" x-model="parcela.descricao">
                                            </div>
                                            <div class="col-md-1 col-sm-2 col-xs-12 "
                                                style="padding-right:2px; padding-left: 2px;">
                                                <input type="text" class="form-control required" required="" value=""
                                                    name="parcelas[][documento]" maxlength="100" aria-required="true"
                                                    placeholder="Doc." x-model="parcela.documento">
                                            </div>
                                            <div class="col-md-6 col-sm-10 col-xs-12 " style="padding:0px; ">
                                                <template x-if="disabledInputsPagamento==true"> 
                                                    <div class="col-md-3 col-sm-2 col-xs-12 "
                                                        style="padding-right:2px; padding-left: 2px;">
                                                        <input type="date" class="form-control required" required="" value=""
                                                            name="parcelas[][data_compra]" maxlength="10"
                                                            aria-required="true" placeholder="Descrição"
                                                            x-model="parcela.data_compra">
                                                    </div>
                                                </template>
                                                <div class="col-md-3 col-sm-2 col-xs-12 "
                                                    style="padding-right:2px; padding-left: 2px;">
                                                    <input type="date" class="form-control required" required="" value=""
                                                        name="parcelas[][data_vencimento]" maxlength="10"
                                                        aria-required="true" placeholder="Descrição"
                                                        x-model="parcela.data_vencimento">
                                                </div>
                                                <div class="col-md-3 col-sm-2 col-xs-12 "
                                                    style="padding-right:2px; padding-left: 2px;">
                                                    <input  x-mask:dynamic="$money($input, ',')"
                                                        class="form-control required" required="" value=""
                                                        name="parcelas[][valor]" maxlength="100" aria-required="true"
                                                        placeholder="Valor" x-model="parcela.valor" />
                                                </div>
                                                <template x-if="disabledInputsPagamento==false"> 
                                                    <div> 
                                                        <div class="col-md-3 col-sm-2 col-xs-12 "
                                                            style="padding-right:2px; padding-left: 2px;">
                                                            <input type="date" class="form-control required" value=""
                                                                name="parcelas[][data_pagamento]" maxlength="100"
                                                                aria-required="true" placeholder="Descrição"
                                                                x-model="parcela.data_pagamento">
                                                        </div>
                                                        <div class="col-md-3 col-sm-2 col-xs-12 "
                                                            style="padding-right:2px; padding-left: 2px;">
                                                            <input  x-mask:dynamic="$money($input, ',')"
                                                                class="form-control   required"  value="" name="parcelas[][valor_pago]"
                                                                maxlength="100" aria-required="true" placeholder="Valor"  
                                                                x-model="parcela.valor_pago">
                                                        </div>
                                                    </div>
                                                </template>

                                            </div>
                                            <div class="col-md-1 col-sm-2 col-xs-12 "
                                                style="padding-right:2px; padding-left: 2px;">
                                                <label class="panel-title center red " x-on:click="excluirParcela(index)">
                                                    <i style="font-weight: 400; font-size: 22px; padding-top: 5px; cursor: pointer"
                                                        class="fa fa-trash-o"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <hr>

                                <div class="box-footer">
                                    <button type="submit" class="btn btn-success"
                                        style="display: inline-flex; align-items: center; gap: 10px"
                                        x-bind:disabled="loading">
                                        Salvar
                                        <template x-if="loading">
                                            <div class="loading loading-sm"></div>
                                        </template>
                                    </button>

                                    <a class="btn btn-default" href="{{ route('financeiro.listar') }}" > Voltar </a>  
                                </div>
                            </div>
 
                        </form>

                        <div class="modal" id="modalOpcoesValorPago">
                            <div class="modal-dialog">
                                <form x-on:submit.prevent="submitModalValorRestante" class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title">Novo Lançamento</h4>
                                    </div>

                                    <div class="modal-body">
                                        <div class="panel" style="margin-bottom: 0">
                                            <div class="panel-body">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <label for="opcoesDispiniveisValorPago"
                                                        style="white-space: nowrap; margin-bottom: 0">Opções
                                                        disponiveis:</label>
                                                    <select id="opcoesDispiniveisValorPago" style="width: 100%">
                                                        <option value="confirmar">Confirmar o Pagamento com o Valor
                                                            Menor</option>
                                                        <option value="gerar">Gerar outro Lançamento com o Valor
                                                            Restante</option>
                                                    </select>
                                                </div>

                                                <hr />

                                                <div
                                                    style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px">
                                                    <label for="opcoesDispiniveisValorPago"
                                                        style="white-space: nowrap; margin-bottom: 0">Nova data de
                                                        vencimento:</label>
                                                    <input type="date" class="form-control"
                                                        x-bind:disabled="opcaoValorRestante == 'confirmar'"
                                                        x-model="inputsLancamentoValorRestante.data_vencimento"
                                                        x-bind:required="opcaoValorRestante == 'gerar'" />
                                                    <label style="white-space: nowrap;">
                                                        <span>
                                                            <input type="checkbox"
                                                                x-bind:disabled="opcaoValorRestante == 'confirmar'"
                                                                x-model="inputsLancamentoValorRestante.parcela_descricao" />
                                                        </span>
                                                        Incluir parcela na descrição
                                                    </label>
                                                </div>

                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <label for="opcoesDispiniveisValorPago"
                                                        style="white-space: nowrap; margin-bottom: 0">Valor
                                                        restante:</label>
                                                    <input type="text" class="form-control" style="width: 200px"
                                                        x-bind:disabled="opcaoValorRestante == 'confirmar'"
                                                        x-model="inputsLancamentoValorRestante.valor_restante"
                                                        x-bind:required="opcaoValorRestante == 'gerar'" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Cancelar</button>

                                        <button type="submit" class="btn btn-success">Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal fade" id="modalCadastro"  >
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    
                                    <form x-on:submit.prevent="salvarCadastro" class="panel panel-white">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title" id="myModalLabel" x-text="campoCadastro.titulo">...</h4>
                                        </div>
                                        <div class="modal-body">
 
                                            <template x-if="campoCadastro.tp_cadastro=='MARCA'">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="mat-label" x-text="campoCadastro.nm_campo"> </label> <span
                                                            class="red normal">*</span>
                                                            <input type="text" class="form-control required" required="" name="descricao"
                                                            x-model="campoCadastro.descricao"   maxlength="100" aria-required="true">
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <template x-if="campoCadastro.tp_cadastro=='FORMA'">

                                                <div class="row">
                                                    <div class="col-md-9 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="mat-label" x-text="campoCadastro.nm_campo"> </label> <span
                                                            class="red normal">*</span>
                                                            <input type="text" class="form-control required" required=""name="descricao"
                                                            x-model="campoCadastro.descricao"  maxlength="100" aria-required="true">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3  ">
                                                        <div class="form-group">
                                                            <div class="mat-div">
                                                                <label>Tipo<span class="red normal">*</span> </label>
                                                                <select class=" form-control " tabindex="-1" name="tipo" x-model="campoCadastro.tipo"
                                                                    style="width: 100%">
                                                                    <option value="">Selecione</option>
                                                                    <option value="">Selecione</option>
                                                                    <option value="BO"  >Boleto</option>
                                                                    <option value="CA"  >Cartão</option>
                                                                    <option value="CH"  >Cheque</option>
                                                                    <option value="DI"  >Dinheiro</option>
                                                                    <option value="PI"  >Pix</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 

                                            </template>

                                            <template x-if="campoCadastro.tp_cadastro=='SETOR'">

                                                <div class="row">
                                                    <div class="col-md-9 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="mat-label" x-text="campoCadastro.nm_campo"> </label> <span
                                                            class="red normal">*</span>
                                                            <input type="text" class="form-control required" required="" name="descricao"
                                                            x-model="campoCadastro.descricao"   maxlength="100" aria-required="true">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3  ">
                                                        <div class="form-group">
                                                            <div class="mat-div">
                                                                <label>Grupo<span class="red normal">*</span> </label>
                                                                <select class=" form-control " tabindex="-1" name="grupo" x-model="campoCadastro.grupo" required style="width: 100%">
                                                                    <option value="">Selecione</option> 
                                                                    <option value="A">Administrativo</option>
                                                                    <option value="P">Apoio</option>
                                                                    <option value="R">Produtivo</option>
                                                                    <option value="N">Não Operacional</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 

                                            </template>

                                            <template x-if="campoCadastro.tp_cadastro=='FORN'">

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="mat-label" x-text="campoCadastro.nm_campo"> </label> <span
                                                            class="red normal">*</span>
                                                            <input type="text" class="form-control required" required=""name="descricao"
                                                            x-model="campoCadastro.descricao"  maxlength="100" aria-required="true">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4  ">
                                                        <div class="form-group">
                                                            <div class="mat-div">
                                                                <label>Tipo Pessoa <span class="red normal">*</span> </label>
                                                                <select class=" form-control " tabindex="-1" name="tipo_pessoa" x-model="campoCadastro.tipo_pessoa" 
                                                                    style="width: 100%">
                                                                    <option value="">Selecione</option>
                                                                    <option value="PF" >PESSOA FISICA</option>
                                                                    <option value="PJ" >PESSOA JURIDICA</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4  ">
                                                        <div class="form-group">
                                                            <div class="mat-div">
                                                                <label>Tipo <span class="red normal">*</span> </label>
                                                                <select class=" form-control " tabindex="-1" name="tipo" x-model="campoCadastro.tipo"
                                                                    style="width: 100%">
                                                                    <option value="">Selecione</option>
                                                                    <option value="F" >FORNECEDOR</option>
                                                                    <option value="C" >CLIENTE</option>
                                                                    <option value="A" >AMBOS</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4  ">
                                                        <div class="form-group">
                                                            <label class="mat-label"  > CPF/CNPJ </label>  
                                                            <input type="text" class="form-control required" required="" name="documento" x-model="campoCadastro.documento"
                                                                maxlength="100" aria-required="true">
                                                        </div>
                                                    </div>
                                                </div> 

                                            </template>

                                        </div>
                                        <div class="modal-footer"> 
                                            <button type="submit" class="btn btn-success"
                                                style="display: inline-flex; align-items: center; gap: 10px"
                                                x-bind:disabled="loadingModal">
                                                Salvar
                                                <template x-if="loadingModal">
                                                    <div class="loading loading-sm"></div>
                                                </template>
                                            </button>
                                        </div>

                                    </form>

                                </div>

                            </div>
                        </div>

                    </div>


                    <div x-data="appTransferencias" role="tabpanel" class="tab-pane  " id="tabTransferencia">
                        <div class="panel panel-white">
                            <form x-on:submit.prevent="submit" class="panel-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-1">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Empresa de Origem <span class="red normal">*</span> </label>
                                                <select name="empresa"
                                                    class="js-states form-control select2-hidden-accessible" tabindex="-1"
                                                    style="width: 100%" required aria-hidden="true"
                                                    id="empresasOrigemTransferencia">
                                                    <option value="">Selecione</option>
                                                    @foreach ($empresas as $empresa)
                                                    <option value="{{ $empresa->cd_empresa }}">
                                                        {{ $empresa->nm_empresa }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Conta Bancária de Origem <span class="red normal">*</span>
                                                </label>
                                                <select name="empresa"
                                                    class="js-states form-control select2-hidden-accessible" tabindex="-1"
                                                    style="width: 100%" required aria-hidden="true"
                                                    id="contasOrigemTransferencia">
                                                    <option value="">Selecione</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-1">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Empresa de Destino <span class="red normal">*</span> </label>
                                                <select name="empresa"
                                                    class="js-states form-control select2-hidden-accessible" tabindex="-1"
                                                    style="width: 100%" required aria-hidden="true"
                                                    id="empresasDestinoTransferencia">
                                                    <option value="">Selecione</option>
                                                    @foreach ($empresas as $empresa)
                                                    <option value="{{ $empresa->cd_empresa }}">
                                                        {{ $empresa->nm_empresa }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Conta Bancária de Destino <span class="red normal">*</span>
                                                </label>
                                                <select name="empresa"
                                                    class="js-states form-control select2-hidden-accessible" tabindex="-1"
                                                    style="width: 100%" required aria-hidden="true"
                                                    id="contasDestinoTransferencia">
                                                    <option value="">Selecione</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12  col-md-offset-1">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Descrição <span class="red normal">*</span> </label>
                                                <input type="text" class="form-control required" required="" value=""
                                                    name="dia_fechamento" maxlength="100" aria-required="true"
                                                     x-model="inputsTransferencia.descricao">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12 ">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Data <span class="red normal">*</span> </label>
                                                <input type="date" class="form-control required" required="" value=""
                                                    name="dia_fechamento" maxlength="100" aria-required="true"
                                                     x-model="inputsTransferencia.data">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12 ">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Valor da Transf. <span class="red normal">*</span> </label>
                                                <input x-mask:dynamic="$money($input, ',')" class="form-control   required"
                                                    required="" value="" name="dia_fechamento" maxlength="100"
                                                    aria-required="true" x-model="inputsTransferencia.valor">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-success"
                                        style="display: inline-flex; align-items: center; gap: 10px"
                                        x-bind:disabled="loading">
                                        Salvar
                                        <template x-if="loading">
                                            <div class="loading loading-sm"></div>
                                        </template>
                                    </button>

                                    <input type="reset" class="btn btn-default" value="Limpar">
                                </div>
                            </form>
                        </div>
                    </div>

                    
                </div>
        </div>
    </div>
    </ul>
</div>
<br><br>

</div><!-- Main Wrapper -->
<br><br>
 
@endsection

@section('scripts')
<style>
    .select2-container.select2-container--default.select2-container--open,
    .swal2-container {
        z-index: 9999;
    }
</style>
<script>
    
    const categorias = @js($categorias);
    const setores = @js($setores);
    const contasBancaria = @js($contasBancaria);
    const contasBancariaTransf = @js($contasBancariaTransf);  
    const formas = @js($formasPagamento);
    const empresaUsuario = @js($empresaUsuario);
  
</script>
<script src="{{ asset('js/rpclinica/financeiro-cadastro.js') }}"></script>

@endsection