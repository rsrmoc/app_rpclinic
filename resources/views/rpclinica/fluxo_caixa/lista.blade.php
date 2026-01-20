@extends('rpclinica.layout.layout')

@section('content')
 
    <div class="page-title">
        <h3>Fluxo de Caixa</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Finaneiro</a></li>
            </ol>
        </div>
    </div>
    <style>
 

        .linhaDespesa {
            color: #d9534f;
            ;
            border-bottom: 1px solid #d9534f;
            font-size: 1em;
            cursor: pointer;
        }

        .linhaReceita {
            color: #138ae9;
            border-bottom: 1px solid #138ae9;
            font-size: 1em;
            cursor: pointer;
        }

        .linhaSaldo {
            color: #18a890;
            border-bottom: 1px solid #22BAA0;
            font-size: 1em;
            font-weight: 900;
            cursor: pointer;
        }

        .linhaTransferencia {
            color: #F19958;
            border-bottom: 1px solid #F19958;
            font-size: 1em;
            cursor: pointer;
        }

        .cursor{
            cursor: pointer;
        }

        @media (min-width: 1250px) {
            .modal-lg {
                width: 1200px;
            }
        }

        label { 
            margin-bottom: 1px; 
        }

         .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
            padding-right: 5px;
            padding-left: 5px;
        }

    </style>
    <div id="main-wrapper" x-data="app" style="min-height: 600px;">

        <div class="col-md-12 " style="margin-left: 2px; margin-right: 2px;">
             
            <div class="panel panel-white">
                <div class="panel-body">
 
                        <div class="row">
                                
                            <div class="col-md-2"  >
                                <label style="text-align: left; font-weight: 900;">Fluxo de Caixa</label>
                                <select class="form-control" required="" name="fluxo_caixa" id="IdFluxoCaixa" style="width: 100%; font-weight: 900;"> 
                                    <option value="M">Mensal</option>
                                    <option value="D">Diario</option> 
                                    <option value="A">Anual</option> 
                                </select> 
                            </div> 
                            <div class="col-md-2 " >  
                                    <label style="text-align: left; font-weight: 900;" x-text="(inputsFilter.fluxo_caixa == 'A') ? 'Ano Inicial' : 'Ano'">Ano</label>
                                    <div class="btn-group" role="group" aria-label="First group" style="margin-bottom: 8px; text-align: center; width: 100%;">
                                        <button type="button" class="btn btn-default" style="width: 25%" x-on:click="prevYear('I')" >
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        </button>
                                        <div class="btn btn-default" style="width: 50%" x-text="inputsFilter.ano"></div>
                                        <button type="button" class="btn btn-default" style="width: 25%"  x-on:click="nextYear('I')">
                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                        </button>
                                    </div> 
                            </div> 
                            <div class="col-md-2 " x-show=" inputsFilter.fluxo_caixa == 'A' " >  
                                    <label style="text-align: left; font-weight: 900;" >Ano Final</label>
                                    <div class="btn-group" role="group" aria-label="First group" style="margin-bottom: 8px; text-align: center; width: 100%;">
                                        <button type="button" class="btn btn-default" style="width: 25%" x-on:click="prevYear('F')" >
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        </button>
                                        <div class="btn btn-default" style="width: 50%" x-text="inputsFilter.ano_final"></div>
                                        <button type="button" class="btn btn-default" style="width: 25%"  x-on:click="nextYear('F')">
                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                        </button>
                                    </div> 
                            </div> 
                            <div class="col-md-2" x-show="(inputsFilter.fluxo_caixa == 'M') || (inputsFilter.fluxo_caixa == 'D') "  >
                                <label style="text-align: left; font-weight: 900;" x-text="(inputsFilter.fluxo_caixa == 'M') ? 'Mês Inicial' : 'Mês'"></label>
                                <select class="form-control" required="" name="mesInicial" id="mesInicial" style="width: 100%"> 
                                    <option value="1">Janeiro</option>
                                    <option value="2">Fevereiro</option>
                                    <option value="3">Março</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Maio</option>
                                    <option value="6">Junho</option>
                                    <option value="7">Julho</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Setembro</option>
                                    <option value="10">Outubro</option>
                                    <option value="11">Novembro</option>
                                    <option value="12">Dezembro</option>
                                </select> 
                            </div>


                            <div class="col-md-2" x-show="(inputsFilter.fluxo_caixa == 'M')" >
                                <label style="text-align: left; font-weight: 900;">Mês Final</label>
                                <select class="form-control" required="" name="mesFinal" id="mesFinal" style="width: 100%"> 
                                    <option value="1">Janeiro</option>
                                    <option value="2">Fevereiro</option>
                                    <option value="3">Março</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Maio</option>
                                    <option value="6">Junho</option>
                                    <option value="7">Julho</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Setembro</option>
                                    <option value="10">Outubro</option>
                                    <option value="11">Novembro</option>
                                    <option value="12">Dezembro</option>
                                </select>
                            </div>
                            <div class="col-md-2" >
                                <label style="text-align: left; font-weight: 900;">Visão </label>
                                <select class="form-control" required="" name="IdVisao" id="IdVisao" style="width: 100%"> 
                                    <option value="REAL">Realizado</option>
                                    <option value="PREV">Previsto</option>
                                    <option value="PRRE">Previsto e Realizado</option> 
                                </select>
                            </div>
                    
                            <div class="col-md-2" >
                                <label style="text-align: left; font-weight: 900;">Detalhes </label>
                                <select class="form-control" required="" name="idDetalhe" id="idDetalhe" style="width: 100%">
                                    <!-- <option value="ANL">Analitico</option> -->
                                    <option value="cd_categoria">Categoria</option> 
                                    <option value="cd_fornecedor">Fornecedor</option> 
                                    <option value="cd_conta">Conta Bancaria</option>  
                                </select>
                            </div>

                        </div>
                        
                        <div class="row">
                            <div class="col-md-3" >
                                <label style="text-align: left; font-weight: 900;">Categoria </label>
                                <select class="form-control" required="" name="categoria" id="cdCatgoria" style="width: 100%">
                                    <!-- <option value="ANL">Analitico</option> -->
                                    <option value="">Todas as Categorias</option> 
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->cd_categoria }}">
                                            {{ $categoria->nm_categoria }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2" >
                                <label style="text-align: left; font-weight: 900;">Contas </label>
                                <select class="form-control" required="" name="cdConta" id="cdConta" style="width: 100%">
                                    <!-- <option value="ANL">Analitico</option> -->
                                    <option value="">Todas as Contas</option> 
                                    @foreach ($contasBancaria as $conta)
                                        <option value="{{ $conta->cd_conta }}">
                                            {{ $conta->nm_conta }}</option>
                                    @endforeach
                                </select>
                            </div> 
                            <div class="col-md-2" >
                                <label style="text-align: left; font-weight: 900;">Setor </label>
                                <select class="form-control" required="" name="cdSetor" id="cdSetor" style="width: 100%">
                                    <!-- <option value="ANL">Analitico</option> -->
                                    <option value="">Todos os Setores</option> 
                                    @foreach ($setores as $setor)
                                        <option value="{{ $setor->cd_setor }}">
                                            {{ $setor->nm_setor }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3" >
                                <label style="text-align: left; font-weight: 900;">Fornecedor/Cliente </label>
                                <select class="form-control" required="" name="cdFornecedor" id="cdFornecedor" style="width: 100%">
                                    <!-- <option value="ANL">Analitico</option> -->
                                    <option value="">Todos os Fornecedores</option> 
                                    @foreach ($fornecedores as $fornecedor)
                                        <option value="{{ $fornecedor->cd_fornecedor }}">
                                            {{ $fornecedor->nm_fornecedor }}</option>
                                    @endforeach
                                </select>
                            </div> 
                            <div class="col-md-2" >
                                <button type="button"  class="btn btn-success "  x-on:click="submitFilters" 
                                style="width:100%; margin-top: 21px; padding: 6px 6px; " x-html="buttonPesquisar" > </button>
                            </div>

                            <div class="col-md-12 text-center">
                               <!--<h1 x-html="tituloFluxo"></h1> -->
                            </div>
                        </div>
                
                        <div x-show="loading" style="text-align: center; padding: 150px;">
                            <img src="{{ asset('assets\images\carregando_rpclinic.gif') }}" style="text-align: center;width: 250px;">
                        </div>

                    <div x-show="!loading">
                        <div class="table-responsive" style="margin-top: 20px;"> 
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-left">Descrição</th>
                                        
                                        <template x-for="label, index in labelTable">
                                            <th class="text-right" style="width: 100px;" x-text="label.nome"></th> 
                                        </template>
                                    </tr>
                                </thead>

                                <tbody>
                                    <!-- saldo_inicial -->
                                    <tr class="linhaSaldo"  style="padding-top: 10px;">

                                        <th class="linhaSaldo" scope="row"  style="min-width: 200px;" >
                                            <span x-html="detalhestable.iconSaldoInicial"></span> Saldo Inicial
                                        </th>
                                        <template x-for="valor, index in saldoInicial">
                                            <td class="text-right linhaSaldo"  x-text="formatValor(valor)"></td>
                                        </template>
                                    </tr> 

                                    <!-- receita -->
                                    <tr class="linhaReceita" x-on:click="detalhesFilters('receita')" style="padding-top: 10px;">
                                        <th class="linhaReceita" scope="row" style="min-width: 200px;">
                                            <span x-html="detalhestable.iconReceita"></span> Receitas
                                        </th>
                                        <template x-for="valor, indice in Receitas">
                                            <td class="text-right linhaReceita" x-text="formatValor(valor)"></td>
                                        </template>
                                    </tr>  
                                    <template x-if="detalhestable.receita">
                                        <template x-for="linha, index in detalhestable.receita">
                                            <tr x-show="detalhestable.SNreceita">
                                                <template x-for="valor, index in linha"> 
                                                    <td x-bind:class="(index > 0) ? 'text-right cursor' : 'text-left bold' " 
                                                        x-on:click="(index > 0) ? movimentosFilters('receita',valor.codigo,valor.cd_filtro,valor.nm_filtro) : '' "
                                                        x-html="(index==0) ? detalhestable.iconSaldos+' '+valor.nome : formatValor(valor.nome)"></td>   
                                                </template>
                                            </tr>
                                        </template>
                                    </template>
                                    <!-- despesa -->
                                    <tr class="linhaDespesa" x-on:click="detalhesFilters('despesa')">
                                        <th class="linhaDespesa" scope="row"  > <span
                                                x-html="detalhestable.iconDespesa"></span> Despesas</th>
                                        <template x-for="valor, indice in Despesas">
                                            <td class="text-right linhaDespesa" x-text="formatValor(valor)"></td>
                                        </template>
                                    </tr> 
                                    <template x-if="detalhestable.despesa">
                                        <template x-for="linha, index in detalhestable.despesa">
                                            <tr x-show="detalhestable.SNdespesa">
                                                <template x-for="valor, index in linha"> 
                                                    <td x-bind:class="(index > 0) ? 'text-right cursor' : 'text-left bold' " 
                                                        x-on:click="(index > 0) ? movimentosFilters('despesa',valor.codigo,valor.cd_filtro,valor.nm_filtro) : '' "
                                                        x-html="(index==0) ? detalhestable.iconSaldos+' '+valor.nome : formatValor(valor.nome)"></td>   
                                                </template>
                                            </tr>
                                        </template> 
                                    </template> 

                                    <!-- saldo_operacional -->
                                    <tr class="linhaSaldo" >
                                        <th class="linhaSaldo" scope="row"  > <span
                                                x-html="detalhestable.iconSaldoOpe"></span> Saldo Operacional</th>
                                        <template x-for="valor, indice in saldoOpe">
                                            <td class="text-right linhaSaldo" x-text="formatValor(valor)"></td>
                                        </template>
                                    </tr>  

                                    <!-- transferencia -->
                                    <tr class="linhaTransferencia" x-on:click="detalhesFilters('transferencia')">
                                        <th class="linhaTransferencia" scope="row" > <span
                                                x-html="detalhestable.iconTrans"></span> Transferências</th>
                                        <template x-for="valor, indice in Transferencia">
                                            <td class="text-right linhaTransferencia" class="linhaTransferencia"
                                                x-text="formatValor(valor)"></td>
                                        </template>
                                    </tr> 
                                    <template x-if="detalhestable.transf">
                                        <template x-for="linha, index in detalhestable.transf">
                                            <tr x-show="detalhestable.SNtransf">
                                                <template x-for="valor, index in linha"> 
                                                    <td x-bind:class="(index > 0) ? 'text-right cursor' : 'text-left bold' " 
                                                        x-on:click="(index > 0) ? movimentosFilters('transferencia',valor.codigo,valor.cd_filtro,valor.nm_filtro) : '' "
                                                        x-html="(index==0) ? detalhestable.iconSaldos+' '+valor.nome  : formatValor(valor.nome)"></td>   
                                                </template>
                                            </tr>
                                        </template>  
                                    </template>
                                    
                                    <!-- saldo_final --> 
                                    <tr class="linhaSaldo" x-on:click="detalhesFilters('saldo_final')">
                                        <th class="linhaSaldo" scope="row"> <span
                                                x-html="detalhestable.iconSaldoFinal"></span> Saldo Final</th>
                                        <template x-for="valor, indice in saldoFinal">
                                            <td class="text-right linhaSaldo" x-text="formatValor(valor)"></td>
                                        </template>
                                    </tr>  
                                    <template x-if="detalhestable.saldo_final">
                                        <template x-for="linha, index in detalhestable.saldo_final">
                                            <tr x-show="detalhestable.SNsaldoFinal">
                                                <template x-for="valor, index in linha"> 
                                                    <td x-bind:class="(index > 0) ? 'text-right cursor' : 'text-left bold' " 
                                                        x-on:click="(index > 0) ? movimentosFilters('saldo_final',valor.codigo,valor.cd_filtro,valor.nm_filtro) : '' "
                                                        x-html="(index==0) ? detalhestable.iconSaldos+' '+valor.nome  : formatValor(valor.nome)"></td>   
                                                </template>
                                            </tr>
                                        </template>  
                                    </template>
                                </tbody>

                            </table>
                        </div>
                    </div>

                   
                    <!-- Modal Movimento -->
                    <div class="modal fade modalMovimento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">×</font></font></span></button>
                                    <h4 class="modal-title  " id="myLargeModalLabel"> <span style="font-size: 24px;font-weight: 300;color: #74767d;padding: 0;  " x-html="tituloModal"> </span> </h4>
                                </div>
                                <div class="modal-body">
                                    
                                    <div x-show="loadingModal" style="text-align: center; padding-top: 80px; padding-bottom: 80px;">
                                        <img src="{{ asset('assets\images\carregando_rpclinic.gif') }}" style="text-align: center;width: 250px;">
                                    </div>
                                    <div x-show="!loadingModal">
                                        <br>
                                        <table class="table table-hover table-responsive table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-left">Codigo</th>
                                                    <th class="text-left">Mov</th>
                                                    <th class="text-left">Tipo</th>
                                                    <th class="text-left">Fornecedor</th>
                                                    <th class="text-left">Conta</th>
                                                    <th class="text-left">Descrição</th>
                                                    <th class="text-center">Vencimento</th>
                                                    <th class="text-right">Valor</th>
                                                    <th class="text-center">Pag./Rec.</th>
                                                    <th class="text-right">Valor</th>
                                                    <th class="text-center">Situação</th> 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-if="relacaoModal"> 
                                                    <template x-for="valor, index in relacaoModal">
                                                        <tr >
                                                            <td x-html="valor.cd_documento_boleto"></td> 
                                                            <td x-html="valor.tp_mov"></td> 
                                                            <td x-html="valor.tipo[0].toUpperCase() + valor.tipo.substring(1)"></td> 
                                                            <td x-html="(valor.fornecedor) ? valor.fornecedor?.nm_fornecedor : '--'"></td> 
                                                            <td x-html="valor.conta?.nm_conta"></td> 
                                                            <td x-html="valor.ds_boleto"></td> 
                                                            <td class="text-center" x-html="valor.data_vencimento"></td> 
                                                            <td class="text-right" x-html="formatValor(valor.vl_boleto)"></td> 
                                                            <td class="text-center" x-html="valor.data_pagrec"></td>
                                                            <td class="text-right" x-html="formatValor(valor.vl_pagrec)"></td>
                                                            <td class="text-center" x-html="valor.situacao"></td>
                                                        </tr>
                                                    </template>
                                                </template>

                                            </tbody>
                                        </table>
                                        <template x-if="relacaoModal==''">  
                                                    <div  style="text-align: center; padding-top: 50px;  ">
                                                        <img src="{{ asset('assets\images\calendario.png') }}" style="text-align: center; "><br>
                                                        #Sem Movimentação
                                                    </div> 
                                        </template>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default"  data-dismiss="modal">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">Fechar</font>
                                        </font>
                                    </button>
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
    <script src="{{ asset('js/rpclinica/fluxo-caixa.js') }}"></script>
@endsection
