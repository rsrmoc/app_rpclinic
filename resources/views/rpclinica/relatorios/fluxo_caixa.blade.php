@extends('rpclinica.layout.layout')

@section('content')

<style>
.report {
    box-sizing: border-box;
    position: relative;
    display: inline-block;
    padding: 6px;
    border: 1px solid #ced4dc;
    background-color: #e9edf2;
    background-repeat: no-repeat;
    color: #444;
    text-decoration: none;
    width: 100%;
    margin: 0 9px 9px 0;
    cursor: pointer;
    text-align: left;
    user-select: none;
    transition: background-color .1s ease-in;
}
.report .subtitle {
    opacity: .8;
}
.report .title {
    font-size: 1.2em;
    font-weight: 400;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.report .report-icon {
    color: #009b88;
    line-height: 36px;
    vertical-align: middle;
    font-size: 1.5em;
    margin-right: 12px;
    float: left;
    height: 100%;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

</style>
    <div class="page-title">
        <h3>Fluxo de Caixa</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Finaneiro</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper" x-data="fluxo_caixa">
        <template x-if="loading">
            <div class="mb-3">
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>&ensp;
                <span>Carregando...</span>
            </div>
        </template>
        <div class="col-md-12 ">
            <div class="panel glass-panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 " >  </div>
                        <div class="col-md-2 " style="text-align: center;">
                            <div class="btn-group" role="group" aria-label="First group" style="margin-bottom: 10px;">
                                <button type="button" class="btn btn-default" x-on:click="prevYear">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                </button>
                                <div class="btn btn-default" x-text="inputsFilter.ano"></div>
                                <button type="button" class="btn btn-default" x-on:click="nextYear">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2"  style="text-align: center;">
                            <select class="form-control" required="" name="mesInicial" id="mesInicial" style="width: 100%">
                                <option value="">Mês Inicial</option>
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

                        <div class="col-md-2"  style="text-align: center;">
                            <select class="form-control" required="" name="mesFinal" id="mesFinal" style="width: 100%">
                                <option value="">Mês Final</option>
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

                        <div class="col-md-2"  style="text-align: center;">
                            <select class="form-control" required="" name="tipoAgrupamento" id="tipoAgrupamento" style="width: 100%">
                                <!-- <option value="ANL">Analitico</option> -->
                                <option value="CAT">Categoria</option>
                                <option value="COB">Conta Bancária</option>
                                <option value="FOR">Fornecedor e Cliente</option>
                                <option value="MAR">Marca</option>
                                <option value="SET">Setor</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-12 table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center"></th>
                                        <th class="text-center">Janeiro</th>
                                        <th class="text-center">Fevereiro</th>
                                        <th class="text-center">Março</th>
                                        <th class="text-center">Abril</th>
                                        <th class="text-center">Maio</th>
                                        <th class="text-center">Junho</th>
                                        <th class="text-center">Julho</th>
                                        <th class="text-center">Agosto</th>
                                        <th class="text-center">Setembro</th>
                                        <th class="text-center">Outubro</th>
                                        <th class="text-center">Novembro</th>
                                        <th class="text-center">Dezembro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="info" data-toggle="collapse" data-target=".detalheReceitas">
                                        <th scope="row" style="min-width: 200px;"> <i class="fa fa-long-arrow-right"></i> Receitas</th>

                                        <template x-for="receita, indice in receitas">
                                            <td class="text-center" x-text="formatValor(receita.soma)"></td>
                                        </template>
                                    </tr>
                                    <tr class="collapse detalheReceitas">
                                        <td colspan="14" v-text="info_dados">Detalhes</td>
                                    </tr>
                                    <template x-for="detalhes, indice in detalhesReceitas">
                                        <tr class="collapse detalheReceitas">
                                            <td><i class="fa fa-long-arrow-right"></i> <span x-text="indice"></span></td>
                                            <template x-for="detalhe, indice2 in detalhes">
                                                <td x-on:click="abreModalDetalheMes(indice2, detalhe[0].cod_estrutural)" class="text-center" x-text="validaValor(detalhe)"></td>
                                            </template>
                                            <td></td>
                                        </tr>
                                    </template>
                                    <tr class="danger" data-toggle="collapse" data-target=".detalheDespesas">
                                        <th scope="row"> <i class="fa fa-long-arrow-right"></i> Despesas</th>

                                        <template x-for="despesa, indice in despesas">
                                            <td class="text-center" x-text="formatValor(despesa.soma)"></td>
                                        </template>
                                    </tr>
                                    <tr class="collapse detalheDespesas">
                                        <td colspan="14">Detalhes</td>
                                    </tr>
                                    <template x-for="detalhes, indice in detalhesDespesas">
                                        <tr class="collapse detalheDespesas">
                                            <td><i class="fa fa-long-arrow-right"></i> <span x-text="indice"></span></td>
                                            <template x-for="detalhe, indice2 in detalhes">
                                                <td x-on:click="abreModalDetalheMes(indice2, detalhe[0].cod_estrutural)" class="text-left" x-text="validaValor(detalhe)"></td>
                                            </template>
                                            <td></td>
                                        </tr>
                                    </template>
                                    <tr>
                                        <th scope="row" class="success"> <i class="fa fa-long-arrow-right"></i> Saldo</th>
                                        <template x-for="saldo, indice in saldos">
                                            <td x-bind:class="saldo.soma < 0 ? 'danger' : 'success'" class="text-right" x-text="formatValor(saldo.soma)"></td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-boletos">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" style="font-style: italic;">
                            <a href="#">Detalhe Mensal</a></h4>
                    </div>

                    <div class="modal-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">Data de recebimento</th>
                                    <th class="text-center">Descrição</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Categoria</th>
                                    <th class="text-center">Forma de pagamento</th>
                                    <th class="text-center">Fornecedor/Cliente</th>
                                    <th class="text-center">Marca</th>
                                    <th class="text-center">Setor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="detalhe in boletosMes">
                                    <tr>
                                        <td class="text-center" x-text="formatData(detalhe.dt_pagrec)"></td>
                                        <td class="text-left" x-text="detalhe.ds_boleto"></td>
                                        <td class="text-right
                                        " x-text="formatValor(detalhe.vl_boleto)"></td>
                                        <td class="text-left" x-text="detalhe.nm_categoria"></td>
                                        <td class="text-left" x-text="detalhe.nm_forma_pag"></td>
                                        <td class="text-left" x-text="detalhe.nm_fornecedor"></td>
                                        <td class="text-left" x-text="detalhe.nm_marca"></td>
                                        <td class="text-left" x-text="detalhe.nm_setor"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/rpclinica/relatorios-fluxo-caixa.js') }}"></script>
@endsection
