@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastrar Devolução</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('estoque.devolucao.listar') }}">Devolução de Produtos</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper" x-data="app">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h5>Houve alguns erros:</h5>

                            <ul>
                                {!! implode('', $errors->all('<li>:message</li>')) !!}
                            </ul>
                        </div>
                    @endif

                    <form role="form" action="{{ route('estoque.devolucao.store') }}" method="post" role="form">
                        @csrf

                        <br>
                        <div class="row">
                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Codigo da Saida</label>
                                    <input type="number" class="form-control" id="exampleInputEmail1" placeholder="Saida" value=""
                                        x-model.debounce.500ms="codigoSaida">
                                </div>
                            </div>

                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Data</label>
                                    <input type="date" class="form-control" readonly id="exampleInputEmail1" placeholder="data" value=""
                                        x-model="data">
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Estoque</label>
                                    <input type="text" class="form-control" readonly id="exampleInputEmail1" placeholder="Estoque" value=""
                                        x-model="estoque">
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Setor</label>
                                    <input type="text" class="form-control" readonly id="exampleInputEmail1" placeholder="Setor" value=""
                                        x-model="setor">
                                </div>
                            </div>
                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nr.Doc</label>
                                    <input type="text" class="form-control" readonly id="exampleInputEmail1" placeholder="Documento" value=""
                                        x-model="numeroDoc">
                                </div>
                            </div>
                        </div>

                        <div style="display: none" x-show="loadingSaida" x-transition>
                            <x-loading message="Buscando saída..." />
                        </div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Qtde</th>
                                    <th>Lote</th>
                                    <th>Devoluções</th>
                                </tr>
                            </thead>

                            <tbody>
                                <template x-for="saidaProduto, indice in produtos">
                                    <tr>
                                        <td x-text="saidaProduto.produto.nm_produto"></td>
                                        <td x-text="saidaProduto.qtde">Qtde</td>
                                        <td x-text="saidaProduto.lote.nm_lote"></td>
                                        <td>
                                            <input type="number" class="form-control" style="max-width: 100px" x-model="devolucoes[indice].qtde"
                                                x-bind:max="saidaProduto.qtde" />
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <hr />

                        <button class="btn btn-success">Cadastrar</button>

                        <input type="hidden" name="cd_solicitacao" x-bind:value="codigoSaida" />

                        <template x-for="devolucao, indice in devolucoes">
                            <template x-if="devolucao.qtde > 0">
                                <div>
                                    <input type="hidden" x-bind:name="`produtos[${indice}][cd_produto]`" x-bind:value="devolucao.cd_produto" />
                                    <input type="hidden" x-bind:name="`produtos[${indice}][cd_lote_produto]`" x-bind:value="devolucao.cd_lote_produto" />
                                    <input type="hidden" x-bind:name="`produtos[${indice}][qtde]`" x-bind:value="devolucao.qtde" />
                                </div>
                            </template>
                        </template>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/rpclinica/estoque-devolucao.js') }}"></script>
@endsection
