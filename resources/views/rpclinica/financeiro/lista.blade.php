@extends('rpclinica.layout.layout')

@section('content')
    <style>
        /* Force Dark Theme Overrides */
        .page-content, .page-inner, .tab-content, .tab-pane, body {
            background-color: transparent !important;
        }
        .panel {
            background-color: rgba(30, 41, 59, 0.8) !important; /* Slate-800 with opacity */
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
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
        .btn-default {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #cbd5e1 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        .btn-default:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        .select2-container--default .select2-results__option[aria-disabled=true] {
            color: #64748b !important;
            font-weight: bold;
            padding: 6px;
        }
    </style>
    <div class="page-title" style="padding-bottom: 10px;  ">
        <div class="row">
            <div class="col-md-10 ">
                <h3>Relação de Receitas e Despesas</h3>
                <div class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('financeiro.listar') }}">Relação</a></li>
                    </ol>
                </div>
            </div>
            <div class="col-md-2" style="text-align: right; ">
                <div class="row">
                    <div class="col-md-6 col-md-offset-6" style="text-align: right;">

                        <div class="btn-group">
      
                            <a href="{{ route('financeiro.add') }}" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cadastrar Lançamento">
                            <span aria-hidden="true" class="icon-note"></span>
                            </a>
         
                        </div>
 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-group>.btn {
            padding: 6px 10px;
        }

        .btn-default.active {
            background-color: #0f766e;
            color: white;
            font-weight: 900;
        }
        .label-recebido{
            background-color: #5cb85c;
        }
        label { 
            margin-bottom: 0px; 
        }
        .redNot{
            color: #a94442;
            font-weight: bold;
        }
    </style>

    <div id="main-wrapper" x-data="app">
        <div id="main-wrapper">

            <div class="col-md-12 " style="margin-left: 2px; margin-right: 2px;">


                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified" role="tablist">

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade in">

                                <div class="panel glass-panel">
                                    <div class="panel-body" style="background: transparent;">
                                        <div class="row">

                                            <form x-on:submit.prevent="submitFilters" id="form-horario" class="col-md-3"
                                                style="margin-bottom: 22px; padding-right: 2px;  padding-left: 2px; ">

                                                <div class="form-data" style="margin-bottom: 30px">
                                                    <div class="col-md-6" style="padding-right: 2px;  padding-left: 2px;">
                                                        <label>Período Inicial:</label>
                                                        <input type="date" class="form-control"
                                                            x-model="inputsFilter.dt_inicial">
                                                    </div>

                                                    <div class="col-md-6" style="padding-right: 2px;  padding-left: 2px;">
                                                        <label>Período Final:</label>
                                                        <input type="date" class="form-control"
                                                            x-model="inputsFilter.dt_final">
                                                    </div>
                                                </div>
                                                <br> 
                                                <div class="form-data"  >
                                                    <div class="col-md-12" style="padding-right: 2px;  padding-left: 2px; margin-top: 5px;">
                                                        <label style="cursor: pointer" >Categoria :</label>
                                                        <select class="form-control" name="profissional"  
                                                            style="width: 100%;" id="filterCategoria" > 
                                                            <option value="">Todos</option>
                                                            @foreach ($categorias as $categoria)
                                                                <option value="{{ $categoria->cd_categoria }}">
                                                                    {{ $categoria->cod_estrutural.' - '.$categoria->nm_categoria }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                               
                                                <div class="form-data" >
                                                    <div class="col-md-12" style="padding-right: 2px;  padding-left: 2px; margin-top: 5px;">
                                                        <label style="cursor: pointer"   >Setor :</label>
                                                        <select class="form-control" name="profissional" 
                                                            style="width: 100%;" id="filterSetor" > 
                                                            <option value="">Todos</option>
                                                            @foreach ($setores as $setor)
                                                                <option value="{{ $setor->cd_setor }}">
                                                                    {{ $setor->nm_setor }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
 
                                                <div class="form-data"  >
                                                    <div class="col-md-12" style="padding-right: 2px;  padding-left: 2px; margin-top: 5px;">
 
                                                        <label style="cursor: pointer" >Fornecedor ou Cliente :</label>
                                                        <select class="form-control" name="profissional"  
                                                            style="width: 100%;" id="filterFornecedor" > 
                                                            <option value="">Todos</option>
                                                            @foreach ($fornecedores as $fornecedor)
                                                                <option value="{{ $fornecedor->cd_fornecedor }}">
                                                                    {{ $fornecedor->nm_fornecedor }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
 
                                                <div class="form-data"  >
                                                    <div class="col-md-12" style="padding-right: 2px;  padding-left: 2px; margin-top: 5px;">
                                                        <label style="cursor: pointer" >Conta e Cartão :</label>
                                                        <select class="form-control" name="profissional"  
                                                            style="width: 100%;" id="filterConta"  >
                                                            <option value="">Todos</option>
                                                            @foreach ($contasBancaria as $conta)
                                                                <option value="{{ $conta->cd_conta }}">
                                                                    {{ $conta->nm_conta }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
 
                                                <div class="form-data" >
                                                    <div class="col-md-12" style="padding-right: 2px;  padding-left: 2px; margin-top: 5px;">
                                                        <label style="cursor: pointer" >Forma de Pagamento :</label>
                                                        <select class="form-control" name="profissional" 
                                                            style="width: 100%;" id="filterForma"  > 
                                                            <option value="">Todos</option>
                                                            @foreach ($formasPagamento as $forma)
                                                                <option value="{{ $forma->cd_forma_pag }}">
                                                                    {{ $forma->nm_forma_pag }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
 

                                                <div class="form-data" >
                                                    <div class="col-md-7" style="padding-right: 2px;  padding-left: 2px; margin-top: 5px;">
                                                        <label>Descrição:</label>
                                                        <input type="text" x-model="inputsFilter.ds_boleto" class="form-control" >
                                                    </div>
                                                    <div class="col-md-5" style="padding-right: 2px;  padding-left: 2px; margin-top: 5px;">
                                                        <label>Documento:</label>
                                                        <input type="text" x-model="inputsFilter.nr_documento" class="form-control" >
                                                    </div>
                                                </div>
 
                                                <div class="form-data" >

                                                    <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0; margin-top: 5px;">
                                                        <div class="col-md-6"
                                                            style="padding-right: 2px;  padding-left: 2px; margin-top: 5px; ">
                                                            <label style="padding: 0">
                                                                <div class="checker">
                                                                    <span>
                                                                        <input type="checkbox"  
                                                                            x-model="inputsFilter.credito" checked />
                                                                    </span>
                                                                </div> Crédito
                                                            </label>
                                                        </div>
                                                        <div class="col-md-6"
                                                            style="padding-right: 2px;  padding-left: 2px; margin-top: 5px;">
                                                            <label style="padding: 0">
                                                                <div class="checker">
                                                                    <span>
                                                                        <input type="checkbox" 
                                                                            x-model="inputsFilter.debito" checked />
                                                                    </span>
                                                                </div> Débito
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                                        <div class="col-md-6"
                                                            style="padding-right: 2px;  padding-left: 2px;">
                                                            <label style="padding: 0">
                                                                <div class="checker">
                                                                    <span>
                                                                        <input type="checkbox"  
                                                                            x-model="inputsFilter.realizado" checked />
                                                                    </span>
                                                                </div> Realizado
                                                            </label>
                                                        </div>
                                                        <div class="col-md-6"
                                                            style="padding-right: 2px;  padding-left: 2px;">
                                                            <label style="padding: 0">
                                                                <div class="checker">
                                                                    <span>
                                                                        <input type="checkbox"  
                                                                            x-model="inputsFilter.n_realizado" checked />
                                                                    </span>
                                                                </div>Ñ Realizado
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="checkbox m-r-md"
                                                        style="margin-top: 0; margin-bottom: 0; ">
                                                        <div class="col-md-6"
                                                            style="padding-right: 2px;  padding-left: 2px;">
                                                            <label style="padding: 0">
                                                                <div class="checker">
                                                                    <span>
                                                                        <input type="checkbox"   
                                                                            x-model="inputsFilter.vencido"  checked  />
                                                                    </span>
                                                                </div> Vencido
                                                            </label>
                                                        </div>
                                                        <div class="col-md-6"
                                                            style="padding-right: 2px;  padding-left: 2px;">
                                                            <label style="padding: 0">
                                                                <div class="checker">
                                                                    <span>
                                                                        <input type="checkbox"  
                                                                            x-model="inputsFilter.a_vencer" checked />
                                                                    </span>
                                                                </div> A Vencer
                                                            </label>
                                                        </div>
                                                    </div> 

                                                    <div style="padding-top: 15px;">
                                                        <div class="checkbox m-r-md"
                                                            style="margin-top: 0; margin-bottom: 0">
                                                            <div class="col-md-6"
                                                                style="padding-right: 2px;  padding-left: 2px;">
                                                                <label style="padding: 0;  ">
                                                                    <div class="checker">
                                                                        <span>
                                                                            <input type="checkbox"  
                                                                                x-model="inputsFilter.transferencia" checked />
                                                                        </span>
                                                                    </div> Transf. entre Contas
                                                                </label>
                                                            </div>
                                                            <div class="col-md-6"
                                                                style="padding-right: 2px;  padding-left: 2px;">
                                                                <label style="padding: 0;padding-bottom: 10px;">
                                                                    <div class="checker">
                                                                        <span>
                                                                            <input type="checkbox"  
                                                                                x-model="inputsFilter.lancamentos" checked />
                                                                        </span>
                                                                    </div> Lançamentos
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <br><br>
                                                    <div class="form-data" style="margin-bottom: 30px">
                                                        <div class="col-md-12"
                                                            style="padding-right: 2px;  padding-left: 2px;">

                                                            <button type="submit" class="btn btn-success "
                                                                style="width:100%; " x-html="buttonPesquisar">  </button>
                                                        </div>
                                                        <div class="col-md-12"  style="padding-right: 2px;  padding-left: 2px;">  
                                                            <div class="text-right">
                                                                <h4 class="no-m m-t-sm">Saldo Atual</h4>
                                                                <h2 class="no-m"  x-html="labelSaldo"></h2>
                                                                <hr style="margin-top: 10px; margin-bottom: 10px;">
                                                              
                                                            </div>
                                                        </div>
                                                        <hr style="margin-bottom: 0px;">


                                                    </div>

                                                </div>



                                            </form>
 
                                            <div class="col-md-9">
                                          
                                                <div style="text-align: center;">
                                                  
                                                    <div class="btn-group" role="group" aria-label="First group"
                                                        style="margin-bottom: 10px;">
                                                        <button type="button" class="btn btn-default"
                                                            x-on:click="prevYear">
                                                            <span class="glyphicon glyphicon-chevron-left"
                                                                aria-hidden="true"></span>
                                                        </button>
                                                        <div class="btn btn-default" x-text="inputsFilter.ano"></div>
                                                        <button type="button" class="btn btn-default"
                                                            x-on:click="nextYear">
                                                            <span class="glyphicon glyphicon-chevron-right"
                                                                aria-hidden="true"></span>
                                                        </button>
                                                    </div>

                                                    <div class="btn-group" role="group" aria-label="First group"
                                                        style="margin-bottom: 10px; margin-left: 15px;;">
                                                        <button type="button" class="btn btn-default"
                                                            style="  font-weight: bold;" x-on:click="prevMonth">
                                                            <span class="glyphicon glyphicon-chevron-left"
                                                                aria-hidden="true"></span>
                                                        </button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 1 }"
                                                            x-on:click="selectMonth(1)">Jan</button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 2 }"
                                                            x-on:click="selectMonth(2)">Fev</button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 3 }"
                                                            x-on:click="selectMonth(3)">Mar</button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 4 }"
                                                            x-on:click="selectMonth(4)">Abr</button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 5 }"
                                                            x-on:click="selectMonth(5)">Mai</button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 6 }"
                                                            x-on:click="selectMonth(6)">Jun</button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 7 }"
                                                            x-on:click="selectMonth(7)">Jul</button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 8 }"
                                                            x-on:click="selectMonth(8)">Ago</button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 9 }"
                                                            x-on:click="selectMonth(9)">Set</button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 10 }"
                                                            x-on:click="selectMonth(10)">Out</button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 11 }"
                                                            x-on:click="selectMonth(11)">Nov</button>
                                                        <button type="button" class="btn btn-default"
                                                            x-bind:class="{ active: inputsFilter.mes == 12 }"
                                                            x-on:click="selectMonth(12)">Dez</button>
                                                        <button type="button" class="btn btn-default"
                                                            style=" font-weight: bold;" x-on:click="nextMonth">
                                                            <span class="glyphicon glyphicon-chevron-right"
                                                                aria-hidden="true"></span>
                                                        </button>
                                                    </div>
                                                </div>

                                                <form  id="form-boletos"> 
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                            <tr class="active">
                                                                <th>
                                                                    <label style="padding: 0px; margin: 0px;">
                                                                        <input x-on:click="checkAll()" class="checkAll"
                                                                            type="checkbox" name="enviar_email"
                                                                            style="padding: 0px; margin: 0px;" />
                                                                    </label>
                                                                </th>
                                                                <th  >Tp.  </th>
                                                                <th x-on:click="ordenarPor('data')">Data <span
                                                                        style="display: none" class="data"></span></th>
                                                                <th >Nr.Doc <span  class="data"></span></th>
                                                                <th x-on:click="ordenarPor('fornecedor')">Cliente/Fornecedor <span
                                                                        style="display: none" class="fornecedor"></span></th>
                                                                <th x-on:click="ordenarPor('descricao')">Descrição <span
                                                                        style="display: none" class="descricao"></span></th>
                                                                <th x-on:click="ordenarPor('conta')" >Conta/Cartão  
                                                                </th>
                                                                <th x-on:click="ordenarPor('forma')" >Forma  
                                                                </th>
                                                                <th x-on:click="ordenarPor('valor')" class="right">Valor
                                                                    <span style="display: none" class="valor"></span>
                                                                </th>

                                                                <th> </th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>

                                                            <!-- Loading Spinner -->
                                                            <tr x-show="loadingPesq">
                                                                <td colspan="10">
                                                                    <div class="line">
                                                                        <div class="loading"></div>
                                                                        <span>Processando Informação...</span>
                                                                    </div>
                                                                </td>
                                                            </tr> 
                                                            <template x-for="boleto, indice in paginatedData">
                                                                <tr x-show="!loadingPesq">
                                                                    <td>
                                                                        <label style="padding: 0px; margin: 0px;">
                                                                            <input class="checkOne" type="checkbox"
                                                                             name="checkBoleto[]" x-bind:value="boleto.cd_documento_boleto"
                                                                             style="padding: 0px; margin: 0px;" />
                                                                        </label>
                                                                    </td>
                                                                    <td style="font-style: italic;" x-text=" boleto.tp_mov "></td>
                                                                    <td
                                                                        x-text="boleto.statuss == 'Vencido' || boleto.statuss == 'A vencer' || boleto.statuss == 'A receber' ? formatDate(boleto.dt_vencimento) : formatDate(boleto.dt_pagrec)">
                                                                    </td>
                                                                    <td x-text="(boleto.doc_boleto) ? boleto.doc_boleto : ' -- '"></td>
                                                                    <td x-text="(boleto.fornecedor?.nm_fornecedor) ? boleto.fornecedor?.nm_fornecedor : ' -- '"></td>
                                                                    <td x-text="boleto.ds_boleto"></td> 
                                                                    <td x-text="(boleto.conta?.nm_conta) ? boleto.conta?.nm_conta : ' -- '" ></td>
                                                                    <td x-text="(boleto.forma?.nm_forma_pag) ? boleto.forma?.nm_forma_pag : ' -- '" ></td>
                                                                    <td class="right"
                                                                        x-bind:class="boleto.tipo == 'receita' ? 'text-info' : 'text-danger'">
                                                                        <strong
                                                                            x-text="boleto.statuss == 'Vencido' || boleto.statuss == 'A vencer' || boleto.statuss == 'A receber' ? formatValor(boleto.vl_boleto) : formatValor(boleto.vl_pagrec)"></strong>
                                                                    </td>
                                                                
                                                                    <td style="text-align: center;">
                                                                        <span class="label"
                                                                            x-bind:class="classStatus[boleto.statuss]"
                                                                            style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;" x-text="boleto.statuss"
                                                                            x-on:click="selecionarBoleto(boleto)"></span>
                                                                    </td>
                                                                </tr>
                                                            </template>

                                                
                                                            <template x-if="paginatedData.length > 0">
                                                                
                                                                <tr x-show="!loadingPesq">
                                                                    <template x-if="labelSaldo.loadingSaldo">
                                                                        <td colspan="10" class="text-center"> <span class="glyphicon glyphicon-filter" aria-hidden="true"></span> </td>
                                                                    </template>
                                                                    <th  style="text-align: right; font-size: 14px;">
                                                                        <span class="label label-recebido" x-on:click="Quitar"
                                                                            style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;">
                                                                            Quitar</span>
                                                                    </th>
                                                                    <th  style="text-align: right; font-size: 14px;">
                                                                        <span class="label label-danger"  x-on:click="Excluir" 
                                                                            style="cursor: pointer;display: block;font-size: 11px; padding-bottom: 5px; padding-top: 3px;" 
                                                                            >Excluir</span>
                                                                    </th>

                                                                    <th colspan="3"
                                                                        style="text-align: right; font-size: 14px;">Total de
                                                                        Lançamentos</th>

                                                                    <template x-if="!labelSaldo.loadingSaldo"> 
                                                                        
                                                                            <td colspan="4"
                                                                                style="text-align: right; font-size: 14px;">
                                                                                <strong class="text-info"
                                                                                    x-text="formatValor(labelResumo.vl_receita)"></strong> &nbsp;
                                                                                - &nbsp;
                                                                                <strong class="text-danger"
                                                                                    x-text="formatValor(labelResumo.vl_despesa)"></strong> &nbsp;
                                                                                = &nbsp;&nbsp;
                                                                                <strong
                                                                                    x-bind:class="(labelResumo.vl_receita-labelResumo.vl_despesa) < 0 ? 'text-danger' :
                                                                                        'text-info'"
                                                                                    x-text="formatValor(labelResumo.vl_receita-labelResumo.vl_despesa)"></strong> 
                                                                            </td>
                                                                            <th  ></th>
                                                                    
                                                                    </template>
                                                                
                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                </form>

                                                <div x-show="!loadingPesq">


                                                    <template x-if="paginatedData.length == 0">
                                                        <p class="text-center" style="padding: 1.5em">

                                                            <img src="{{ asset('assets/images/calendario.png') }}"
                                                                draggable="false"> <br>
                                                            Não há Lançamentos para esse período
                                                        </p>
                                                    </template> 

                                                    <!-- Botões de Paginação -->
                                                    <template x-if="paginatedData.length > 0">
                                                        <div class="right">
 
                                                            <button type="button" class="btn btn-default" @click="goToPage(1)" :disabled="currentPage === 1">
                                                                <i class="fa fa-fast-backward"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-default" @click="previousPage()" :disabled="currentPage === 1">
                                                                <i class="fa fa-backward"></i>
                                                            </button>

                                                            <template x-for="(page,index) in totalPages" :key="index">

                                                                <button type="button" class="btn btn-default" @click="goToPage(page)" :disabled="currentPage === page">
                                                                    <i x-html="(page)"></i>
                                                                </button>
                                                            </template>
 
                                                            <button type="button" class="btn btn-default" @click="nextPage()" :disabled="currentPage === totalPages">
                                                                <i class="fa fa-forward"></i>
                                                            </button>

                                                            <button type="button" class="btn btn-default" @click="goToPage(totalPages)" :disabled="currentPage === totalPages">
                                                                <i class="fa fa-fast-forward"></i>
                                                            </button>

                                                            <div  class="btn-group m-b-sm" style="    margin-bottom: 0px;">

                                                                <button type="button" class="btn btn-default dropdown-toggle" id="situacaoButton" data-toggle="dropdown"   aria-expanded="false">
                                                                    <span class="btnConfirmado"><i class="fa fa-list" ></i>  &nbsp;&nbsp;   50 Registros&nbsp;&nbsp;&nbsp;<span class="caret"></span></span>
                                                                </button>

                                                                <ul class="dropdown-menu" role="menu">
  
                                        
                                                                    <li x-on:click="registroPagina(100)">
                                                                        <a href="#" style="color:#333; font-weight: bold;padding: 4px 10px;">
                                                                            <i class="fa fa-list" style="padding-left:2px;  "></i>   100 Registros
                                                                        </a>
                                                                    </li>

                                                                    <li x-on:click="registroPagina(150)">
                                                                        <a href="#" style="color:#333; font-weight: bold;padding: 4px 10px;">
                                                                            <i class="fa fa-list" style="padding-left:2px;  "></i>   150 Registros
                                                                        </a>
                                                                    </li>

                                                                    <li x-on:click="registroPagina(200)">
                                                                        <a href="#" style="color:#333; font-weight: bold;padding: 4px 10px;">
                                                                            <i class="fa fa-list" style="padding-left:2px;  "></i>   200 Registros
                                                                        </a>
                                                                    </li>
                                        
                                                                </ul>

                                                            </div>

                                                        </div>
                                                    </template>
                                                    
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modal-parcela">
                                <div class="modal-dialog modal-lg">
                                    <form
                                        x-on:submit.prevent="updateParcela(!inputsValorPagoShow && opcaoValorRestante == 'confirmar')"
                                        id="modalParcelaForm" class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title" style="font-style: italic;">
                                                <a href="#"
                                                    x-text="  `Pagamento/Recebimento ( Conta #${boletoSelecionado?.cd_documento_boleto} )`"></a>
                                            </h4>
                                        </div>

                                        <div class="modal-body">
                                            
                                            <div x-show="inputsValorPagoShow">
                                                <div class="panel" style="margin: 0 auto; max-width: 536px;">
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
                                                            <input type="text" class="form-control"
                                                                style="width: 200px"
                                                                x-bind:disabled="opcaoValorRestante == 'confirmar'"
                                                                x-model="inputsLancamentoValorRestante.valor_restante"
                                                                x-bind:required="opcaoValorRestante == 'gerar'" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div x-show="!inputsValorPagoShow" class="row">
                                                <div class="col-md-5 col-sm-12 col-xs-12 ">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Empresa <span class="red normal">*</span> </label>
                                                            <div class=" form-control"
                                                                x-text="boletoSelecionado?.empresa?.nm_empresa"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Categoria <span class="red normal">*</span> </label>
                                                            <select name="cd_categoria"
                                                                class="js-states form-control select2-hidden-accessible"
                                                                tabindex="-1" style="width: 100%" required
                                                                aria-hidden="true" id="modalParcelaCategoria">
                                                                <option value="">Selecione</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Conta ou Cartão <span class="red normal">*</span>
                                                            </label>
                                                            <select name="cd_conta"
                                                                class="js-states form-control select2-hidden-accessible"
                                                                tabindex="-1" style="width: 100%" required
                                                                aria-hidden="true" id="modalParcelaConta">
                                                                <option value="">Selecione</option>
                                                                @foreach ($contasBancaria as $conta)
                                                                    <option value="{{ $conta->cd_conta }}"
                                                                        data-tp="{{ $conta->tp_conta }}">
                                                                        {{ $conta->nm_conta }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Forma de Pagamento <span class="red normal">*</span>
                                                            </label>
                                                            <select name="cd_forma"
                                                                class="js-states form-control select2-hidden-accessible"
                                                                tabindex="-1" style="width: 100%" required
                                                                aria-hidden="true" id="modalParcelaForma">
                                                                <option value="">Selecione</option>
                                                                @foreach ($formasPagamento as $forma)
                                                                    <option value="{{ $forma->cd_forma_pag }}">
                                                                        {{ $forma->nm_forma_pag }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Cliente ou Fornecedor<span class="red normal">*</span>
                                                            </label>
                                                            <select name="cd_fornecedor"
                                                                class="js-states form-control select2-hidden-accessible"
                                                                tabindex="-1" style="width: 100%" required
                                                                aria-hidden="true" id="modalParcelaFornecedor">
                                                                <option value="">Selecione</option>
                                                                @foreach ($fornecedores as $fornecedor)
                                                                    <option value="{{ $fornecedor->cd_fornecedor }}">
                                                                        {{ $fornecedor->nm_fornecedor }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Setor <span class="red normal"></span> </label>
                                                            <select name="cd_setor"
                                                                class="js-states form-control select2-hidden-accessible"
                                                                tabindex="-1" style="width: 100%"  
                                                                aria-hidden="true" id="modalParcelaSetor">
                                                                <option value="">Selecione</option>
                                                                @foreach ($setores as $setor)
                                                                    <option value="{{ $setor->cd_setor }}" >
                                                                        {{ $setor->nm_setor }}</option>
                                                                @endforeach
                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Turma <span class="red normal"></span> </label>
                                                            <select name="cd_turma"
                                                                class="js-states form-control select2-hidden-accessible"
                                                                tabindex="-1" style="width: 100%"  
                                                                aria-hidden="true" id="lancamentosTurmas">
                                                                <option value="">Selecione</option>
                                                                @foreach ($turmas as $turma)
                                                                    <option value="{{ $turma->cd_turma }}" >
                                                                        {{ $turma->nm_turma }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Evento</label>
                                                            <select name="cd_evento"
                                                                class="js-states form-control select2-hidden-accessible"
                                                                tabindex="-1" style="width: 100%" aria-hidden="true"
                                                                id="lancamentosEventos"> 
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                               

                                                <div class="col-md-2 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label style="margin-bottom: 2px;">Tipo de Lanc. <span
                                                                class="red normal">*</span> </label>
                                                        <label style="padding: 0;margin-bottom: 2px;" class=" red"
                                                            id="modalParcelaDespesa">
                                                            <span>
                                                                <input type="radio" name="tipo" value="despesa"
                                                                    id="tipoDespesa" />
                                                            </span>
                                                            Despesa
                                                        </label>
                                                        <label style="padding: 0" class=" text-info"
                                                            id="modalParcelaReceita">
                                                            <span>
                                                                <input type="radio" name="tipo" value="receita"
                                                                    id="tipoReceita" />
                                                            </span>
                                                            Receita
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <hr
                                                        style="margin-top: 10px;
                                                    margin-bottom: 10px;">
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Descrição <span class="red normal">*</span> </label>
                                                            <input type="text" class="form-control" name="ds_boleto"
                                                            x-model="boletoSelecionado.ds_boleto" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Emissão <span class="red normal"></span> </label>
                                                            <input type="date" class="form-control" name="dt_emissao"
                                                            x-model="boletoSelecionado.dt_emissao">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Documento <span class="red normal"></span> </label>
                                                            <input type="text" class="form-control" name="doc_boleto"
                                                            x-model="boletoSelecionado.doc_boleto" >
                                                        </div>
                                                    </div>
                                                </div>
 

                                                <template x-if="boletoSelecionado.tp_mov=='CA'">
                                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <div class="mat-div">
                                                                <label>Data da compra <span class="red normal">*</span>
                                                                </label>
                                                                <input type="date" name="data_compra"
                                                                    class="form-control" id="modalParcelaCompra" x-model="boletoSelecionado.data_compra">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>

                                                <template x-if="boletoSelecionado.tp_mov!='CA'">
                                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <div class="mat-div">
                                                                <label>Data de Vencimento <span class="red normal">*</span>
                                                                </label>
                                                                <input type="date" name="dt_vencimento" required x-model="boletoSelecionado.dt_vencimento"
                                                                    class="form-control" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                 
                                                <div class="col-md-3 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Valor  <span class="red normal">*</span>
                                                            </label>
                                                            <input  name="vl_boleto" required x-model="formatValor(boletoSelecionado.vl_boleto).replace('R$ ', '')"
                                                                class="form-control "  x-mask:dynamic="$money($input, ',')" id="modalParcelaValor" >
                                                        </div>
                                                    </div>
                                                </div>

                                                <template x-if="boletoSelecionado.tp_mov!='CA'">
                                                    <div> 
                                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <div class="mat-div">
                                                                    <label id="labelData">Data  </label>
                                                                    <input type="date" name="dt_pagrec" x-model="boletoSelecionado.dt_pagrec"
                                                                        class="form-control" >
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <div class="mat-div">
                                                                    <label id="labelValor">Valor Pag./Rec.</label>
                                                                    <input  
                                                                        name="vl_pagrec" class="form-control"  x-mask:dynamic="$money($input, ',')"
                                                                       id="modalParcelaValorPago" x-model="(boletoSelecionado.vl_pagrec) ? formatValor(boletoSelecionado.vl_pagrec).replace('R$ ', '') : null" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <div class="row">

                                                <div class="col-md-3 col-sm-12 col-xs-12" style="text-align: left">
                                                    <a x-bind:href="`/rpclinica/financeiro-edit/${boletoSelecionado?.cd_documento_boleto}`"
                                                        style="width: 100%; color: #22BAA0; "
                                                        class="btn btn-default  btn-rounded">
                                                        <i class="fa fa-list"></i> Todas as Parcelas
                                                    </a>
                                                </div>


                                               
                                                    <div class="col-md-3 col-sm-12 col-xs-12" style="text-align: left;">
                                                        <button type="button"
                                                            style="width: 100%; color: red; display: flex; align-items: center; justify-content: center; gap: 10px"
                                                            class="btn btn-default btn-rounded" x-on:click="excluirParcela"
                                                            x-bind:disabled="loadingExclusao">
                                                            <span><i class="fa fa-trash"></i> Excluir Parcela</span>
                                                            <template x-if="loadingExclusao">
                                                                <div class="loading loading-sm"></div>
                                                            </template>
                                                        </button>
                                                    </div>
                                               
 
                                                    <div class="col-md-3 col-sm-12 col-xs-12" style="text-align: left;">
                                                        <template x-if="boletoSelecionado.situacao == 'QUITADO'">
                                                            <template x-if="boletoSelecionado.tp_mov=='LC'">
                                                                <button type="button"
                                                                    style="width: 100%; color: #EE802F; display: flex; align-items: center; justify-content: center; gap: 10px"
                                                                    class="btn btn-default btn-rounded"
                                                                    x-on:click="estornarParcela" x-bind:disabled="loadingEstorno">
                                                                    <span><i class="fa fa-mail-reply-all"></i> Estornar
                                                                        Parcela</span>
                                                                    <template x-if="loadingEstorno">
                                                                        <div class="loading loading-sm"></div>
                                                                    </template>
                                                                </button>
                                                            </template>
                                                        </template> 
                                                        <template x-if="boletoSelecionado.situacao != 'QUITADO'">
                                                            
                                                            <template x-if="boletoSelecionado.tp_mov=='LC'">
                                                                <a href="#" x-on:click="QuitarRapido(boletoSelecionado)"
                                                                    style="width: 100%; color: #22BAA0; "
                                                                    class="btn btn-default  btn-rounded">
                                                                    <i class="fa fa-check"></i> Quitação Rapida
                                                                </a>
                                                            </template>
                                                        </template>
                                                    </div> 

                                              



                                                <div class="col-md-3 col-sm-12 col-xs-12">

                                                    <template x-if="boletoSelecionado.situacao !='QUITADO'">
                                                        <button type="submit" class="btn btn-success" x-html="buttonSalvar"
                                                            style="display: inline-flex; align-items: center; gap: 10px; justify-content: center"
                                                            x-bind:disabled="loadingUpdate">
                                                            Salvar
                                                            <template x-if="loadingUpdate">
                                                                <div class="loading loading-sm"></div>
                                                            </template>
                                                        </button>
                                                    </template>

                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Fechar</button>
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
        const eventos = @js($eventos);
        const formas = @js($formasPagamento);

        document.addEventListener('DOMContentLoaded', function() {
            var despesaRadio = document.getElementById('tipoDespesa');
            var receitaRadio = document.getElementById('tipoReceita');
            var labelValor = document.getElementById('labelValor');
            var labelData = document.getElementById('labelData');

            

            
            despesaRadio.addEventListener('change', function() {
                if (this.checked) {
                    labelValor.textContent = 'Valor Pago';
                    labelData.textContent = 'Data Pagamento';
                }
            });

            receitaRadio.addEventListener('change', function() {
                if (this.checked) {
                    labelValor.textContent = 'Valor Recebido';
                    labelData.textContent = 'Data Recebimento';
                }
            });
        });
    </script> 
    <script src="{{ asset('js/rpclinica/financeiro-listar.js') }}"></script>

@endsection
