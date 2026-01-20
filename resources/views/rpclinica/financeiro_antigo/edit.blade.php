@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Edição de Lançamentos</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Edição</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <style>
            .label-recebido {
                background-color: #5cb85c;
            }
        </style>

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
                        <div role="tabpanel" class="tab-pane active fade in" id="TabLancamentos" x-data="appLancamentos">
                            <form x-on:submit.prevent="submitUpdateParcela" id="formUpdateParcela" class="panel panel-white">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-xs-12 ">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Empresa <span class="red normal">*</span> </label>
                                                    <select name="empresa"
                                                        class="js-states form-control select2-hidden-accessible"
                                                        tabindex="-1" style="width: 100%" required aria-hidden="true"  
                                                        id="lancamentosEmpresas" readonly disabled>
                                                        <option value="">Selecione</option>
                                                        @foreach ($empresas as $empresa)
                                                            @if ($empresa->cd_empresa == $documentoBoleto->cd_empresa)
                                                                <option value="{{ $empresa->cd_empresa }}"
                                                                    @if ($empresa->cd_empresa == $documentoBoleto->cd_empresa) selected @endif>{{ $empresa->nm_empresa }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Categoria <span class="red normal">*</span> </label>
                                                    <select name="categoria"
                                                        class="js-states form-control select2-hidden-accessible"
                                                        tabindex="-1" style="width: 100%" required aria-hidden="true"
                                                        id="lancamentosCategorias">
                                                        @foreach ($categorias as $val)
                                                            @if ($val->cd_categoria == $documentoBoleto->cd_categoria)
                                                                <option value="{{ $val->cd_categoria }}"
                                                                    @if ($val->cd_categoria == $documentoBoleto->cd_categoria) selected @endif>{{ $val->nm_categoria}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-8 col-xs-12  ">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Conta ou Cartão <span class="red normal">*</span> </label>
                                                    <select name="conta"
                                                        class="js-states form-control select2-hidden-accessible"
                                                        tabindex="-1" style="width: 100%" required aria-hidden="true"
                                                        id="lancamentosConta"> 
                                                        @foreach ($contasBancaria as $conta)
                                                            @if ($conta->cd_conta == $documentoBoleto->cd_conta)
                                                                <option value="{{ $conta->cd_conta }}"
                                                                    @if ($conta->cd_conta == $documentoBoleto->cd_conta) selected @endif>{{ $conta->nm_conta }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Forma de Pagamento <span class="red normal">*</span> </label>
                                                    <select name="forma"
                                                        class="js-states form-control select2-hidden-accessible"
                                                        tabindex="-1" style="width: 100%" required aria-hidden="true"
                                                        id="lancamentosFormaPagamento"> 
                                                        @foreach ($formasPagamento as $forma)
                                                        @if ($forma->cd_forma_pag == $documentoBoleto->cd_forma)
                                                            <option value="{{ $forma->cd_forma_pag }}"
                                                                @if ($forma->cd_forma_pag == $documentoBoleto->cd_forma) selected @endif>{{ $forma->nm_forma_pag }}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-8 col-xs-12 ">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Cliente e Fornecedor <span class="red normal">*</span> </label>
                                                    <select name="fornecedor"
                                                        class="js-states form-control select2-hidden-accessible"
                                                        tabindex="-1" style="width: 100%" required aria-hidden="true"
                                                        id="lancamentosFornecedor"> 
                                                        @foreach ($fornecedores as $fornecedor)
                                                            @if ($fornecedor->cd_fornecedor == $documentoBoleto->cd_fornecedor)
                                                                <option value="{{ $fornecedor->cd_fornecedor }}"
                                                                    @if ($fornecedor->cd_fornecedor == $documentoBoleto->cd_fornecedor) selected @endif>{{ $fornecedor->nm_fornecedor }}</option>
                                                            @endif
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
                                                    <label>Setor <span class="red normal"></span> </label>
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
                                                        @foreach ($turmas as $turma)
                                                        @if ($turma->cd_turma == $documentoBoleto->cd_turma)
                                                            <option value="{{ $turma->cd_turma }}" @if ($turma->cd_turma == $documentoBoleto->cd_turma) selected @endif>{{ mb_strtoupper($turma->nm_turma) }}
                                                            </option>
                                                        @endif
                                                        @endforeach
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
                                                        @foreach ($eventos as $evento)
                                                        @if ($evento->cd_evento == $documentoBoleto->cd_evento)
                                                            <option value="{{ $evento->cd_evento }}" @if ($evento->cd_evento == $documentoBoleto->cd_evento) selected @endif>{{ mb_strtoupper($evento->nm_evento) }}
                                                            </option>
                                                        @endif
                                                        @endforeach
                                                     
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- 
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Marca <span class="red normal"></span> </label>
                                                    <select name="empresa"
                                                        class="js-states form-control select2-hidden-accessible"
                                                        tabindex="-1" style="width: 100%" aria-hidden="true"
                                                        id="lancamentosMarcas">
                                                        <option value="">Selecione</option>
                                                        @foreach ($marcas as $marca)
                                                            <option value="{{ $marca->cd_marca }}"
                                                                @if ($marca->cd_marca == $documentoBoleto->cd_marca) selected @endif>{{ $marca->nm_marca }}</option>
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
                                            <input type="text" class="form-control required" required=""
                                                name="dia_fechamento" maxlength="100" aria-required="true"
                                                placeholder="Descrição" value="{{ $documentoBoleto->ds_boleto }}"
                                                id="lancamentosDescricao" x-model="inputsLancamento.descricao">
                                        </div>
                                        <div class="col-md-2 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label class="mat-label" @click="xx" >Emissão <span
                                                        class="red normal"></span></label>
                                                <input type="date" class="form-control required"  
                                                    name="dia_fechamento" maxlength="100" aria-required="true"
                                                    value="{{ $documentoBoleto->dt_emissao }}" x-model="inputsLancamento.dt_emissao">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label class="mat-label">Documento <span
                                                        class="red normal"></span></label>
                                                <input type="text" class="form-control required"  
                                                    name="dia_fechamento" maxlength="100" aria-required="true"
                                                    placeholder="Documento" value="{{ $documentoBoleto->doc_boleto }}" x-model="inputsLancamento.documento">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row"
                                        style="background: #f9f9f9; padding: 7px;border: 1px solid #dce1e4; ">
                                        <div class="col-md-10 col-md-offset-1">
                                            <label class="panel-title">Tipo de Lançamento </label>
                                            &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                            <label style="padding: 0" class="panel-title red"
                                                id="labelLancamentosDespesa">
                                                <span>
                                                    <input type="radio" name="enviar_email" @if ($documentoBoleto->tipo == 'despesa') checked @endif
                                                        value="despesa" x-model="inputsLancamento.tp_lancamento" />
                                                </span>
                                                <i class="fa fa-arrow-down"></i> Despesa
                                            </label>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <label style="padding: 0" class="panel-title text-info"
                                                id="labelLancamentosReceita">
                                                <span>
                                                    <input type="radio" name="enviar_email" @if ($documentoBoleto->tipo == 'receita') checked @endif
                                                        value="receita" x-model="inputsLancamento.tp_lancamento" />
                                                </span>
                                                <i class="fa fa-arrow-up"></i> Receita
                                            </label>
                                        </div>
                                    </div>
                                    <style>
                                        .linaParcela label{
                                            margin-bottom: 2px;
                                        }
                                        
                                        </style>

                                     
                                    <template x-for="parcela, index in inputsLancamento.parcelas">
                                        <div class="row linaParcela" style="margin-top: 10px;" >
                                            <div class="col-md-1 col-sm-2 col-xs-12  "
                                                style="padding-right:2px; padding-left: 2px; text-align: center;">
                                                <label  style="text-align: center; font-weight: bold;   font-size: 11px; color: #f9f9f9;  margin-bottom: 3px;
                                                 border-radius: 5px; padding-left: 5px; padding-right: 5px;" x-bind:class="classStatus[parcela.statuss]"  
                                                 x-text="parcela.statuss" >  </label>
                                                
                                                <div class="form-control disabled"
                                                    style="text-align: center; font-weight: 900;" x-text="index + 1"></div>
                                            </div>

                                            <template x-if="parcela.cd_documento_boleto == inputsLancamento.cd_documento_boleto">
                                                <div class="col-md-3 col-sm-6 col-xs-12" style="padding-right:2px; padding-left: 2px;">
                                                    <label class="bold" x-html="'Descrição <i>#'+parcela.cd_documento_boleto+'<i>'" > </label>   
                                                    <input type="text" class="form-control required" required=""
                                                        value="" name="dia_fechamento" maxlength="100"
                                                       
                                                        aria-required="true" placeholder="Descrição"
                                                        x-model="inputsLancamento.descricao">
                                                </div>
                                            </template>

                                            <template x-if="parcela.cd_documento_boleto == inputsLancamento.cd_documento_boleto">
                                                <div class="col-md-1 col-sm-2 col-xs-12" style="padding-right:2px; padding-left: 2px;">
                                                    <label class="bold" >Documento </label>
                                                    <input type="text" class="form-control required"  
                                                        value="" name="dia_fechamento" maxlength="100" 
                                                        
                                                        aria-required="true" placeholder="Doc."
                                                        x-model="inputsLancamento.documento">
                                                </div>
                                            </template>

                                            <template x-if="parcela.cd_documento_boleto != inputsLancamento.cd_documento_boleto">
                                                <div class="col-md-3 col-sm-6 col-xs-12" style="padding-right:2px; padding-left: 2px;">
                                                    
                                                    <label class="bold" x-html="'Descrição <i>#'+parcela.cd_documento_boleto+'<i>'" > </label>    
                                                    <input type="text" class="form-control required" required=""
                                                        value=""  maxlength="100" 
                                                       
                                                        aria-required="true" placeholder="Descrição"
                                                        x-model="parcela.ds_boleto">
                                                </div>
                                            </template>

                                            <template x-if="parcela.cd_documento_boleto != inputsLancamento.cd_documento_boleto">
                                                <div class="col-md-1 col-sm-2 col-xs-12" style="padding-right:2px; padding-left: 2px;">
                                                    <label class="bold" >Documento </label>
                                                    <input type="text" class="form-control required" required=""
                                                        value=""  maxlength="100"
                                                    
                                                        aria-required="true" placeholder="Doc."
                                                        x-model="parcela.doc_boleto">
                                                </div>
                                            </template>
                                            
                                            <template x-if="boleto.conta?.tp_conta == 'CA'">
                                                <div class="col-md-2 col-sm-2 col-xs-12 "
                                                    style="padding-right:2px; padding-left: 2px;">
                                                    <label class="bold" >Data da Compra </label>
                                                    <input type="date" class="form-control required"
                                                    
                                                        value="" name="data_compra" maxlength="100"
                                                        aria-required="true"  
                                                        x-model="parcela.data_compra">
                                                </div>
                                            </template>
                                            <template x-if="boleto.conta?.tp_conta != 'CA'">
                                                <div
                                                    class="col-md-2 col-sm-2 col-xs-12 "style="padding-right:2px; padding-left: 2px;">
                                                    <label class="bold" > Vencimento </label>
                                                    <input type="date" class="form-control required" required=""
                                                        value="" name="dia_fechamento" maxlength="100"
                                                
                                                        aria-required="true" placeholder="Descrição"
                                                        x-model="parcela.dt_vencimento">
                                                </div>
                                            </template>
                                            <div
                                                class="col-md-2 col-sm-2 col-xs-12 "style="padding-right:2px; padding-left: 2px;">
                                                <label  class="bold" >Valor Documento <span class="red normal">*</span> </label> 
                                                <input  class="form-control   required" required=""
                                                    x-mask:dynamic="$money($input, ',')" 
                                                    value="" name="dia_fechamento" maxlength="100"
                                                   
                                                    aria-required="true"  
                                                    x-model="parcela.vl_boleto">
                                            </div>

                                            <template x-if="boleto.conta?.tp_conta != 'CA'">
                                                <div> 
                                                    <div class="col-md-2 col-sm-2 col-xs-12 "
                                                        style="padding-right:2px; padding-left: 2px;">
                                                        <label class="bold" x-text="(parcela.tipo=='despesa') ? 'Pagamento' : 'Rececimento' " > </label>
                                                        <input type="date" class="form-control required"
                                                        
                                                            value="" name="dia_fechamento" maxlength="100"
                                                            aria-required="true"  
                                                            x-model="parcela.dt_pagrec">
                                                    </div>
                                                    <div  class="col-md-1 col-sm-2 col-xs-12 "style="padding-right:2px; padding-left: 2px;">
                                                        
                                                        <label class="bold" x-text="(parcela.tipo=='despesa') ? 'Valor Pago' : 'Valor Recebido' " > </label>

                                                        <input   class="form-control   required"
                                                            name="dia_fechamento" maxlength="100"
                                                            x-mask:dynamic="$money($input, ',')"
                                                            
                                                            aria-required="true"  
                                                            x-model="parcela.vl_pagrec">
                                                    </div>
                                                </div>
                                            </template>

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
                                        <a class="btn btn-default" href="{{ url()->previous() }}" > Voltar </a>  
                                    </div>

                                </div>
                            </form>
                        </div>

                        <div role="tabpanel" class="tab-pane  " id="tabTransferencia">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-1">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Empresa de Origem <span class="red normal">*</span> </label>
                                                    <select name="empresa"
                                                        class="js-states form-control select2-hidden-accessible"
                                                        tabindex="-1" style="width: 100%" required aria-hidden="true">
                                                        <option value="">Selecione</option>
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
                                                        class="js-states form-control select2-hidden-accessible"
                                                        tabindex="-1" style="width: 100%" required aria-hidden="true">
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
                                                        class="js-states form-control select2-hidden-accessible"
                                                        tabindex="-1" style="width: 100%" required aria-hidden="true">
                                                        <option value="">Selecione</option>
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
                                                        class="js-states form-control select2-hidden-accessible"
                                                        tabindex="-1" style="width: 100%" required aria-hidden="true">
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
                                                    <input type="text" class="form-control required" required=""
                                                        value="" name="dia_fechamento" maxlength="100"
                                                        aria-required="true" placeholder="Descrição">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 col-xs-12 ">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Data <span class="red normal">*</span> </label>
                                                    <input type="date" class="form-control required" required=""
                                                        value="" name="dia_fechamento" maxlength="100"
                                                        aria-required="true" placeholder="Descrição">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 col-xs-12 ">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Valor da Transf. <span class="red normal">*</span> </label>
                                                    <input type="text" class="form-control required" required=""
                                                        value="" name="dia_fechamento" maxlength="100"
                                                        aria-required="true" placeholder="Valor">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="box-footer">
                                        <input type="submit" class="btn btn-success" value="Salvar">
                                        <input type="reset" class="btn btn-default" value="Limpar">
                                    </div>

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
    <script src=" {{ asset('assets\js\jquery.mask.js') }} "></script>
    <script>
        $(".moedaReal").mask('#.##0,00', {
            reverse: true
        });
    </script>
@endsection

@section('scripts')
    <script>
        const boleto = @js($documentoBoleto);
        const parcelas = @js($parcelas);
        const categorias = @js($categorias);
        const setores = @js($setores);
        const contasBancaria = @js($contasBancaria);
        const eventos = @js($eventos); 
        console.log(categorias);
    </script>
    <script src="{{ asset('js/rpclinica/financeiro-edicao.js') }}"></script>
@endsection
