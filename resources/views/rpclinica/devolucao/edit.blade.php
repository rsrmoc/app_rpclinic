@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Editar Devolução</h3>
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

                    <form role="form" action="{{ route('estoque.devolucao.update') }}" method="post" role="form">
                        @method('PUT')
                        @csrf

                        <br>
                        <div class="row">
                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Codigo da Saida</label>
                                    <input type="number" class="form-control" id="exampleInputEmail1" placeholder="Saida"
                                        value="{{ $devolucao->cd_solicitacao_saida }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Data</label>
                                    <input type="date" class="form-control" disabled id="exampleInputEmail1" placeholder="data" value=""
                                        x-model="data">
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Estoque</label>
                                    <input type="text" class="form-control" disabled id="exampleInputEmail1" placeholder="Estoque" value=""
                                        x-model="estoque">
                                </div>
                            </div>
                            <div class="col-md-3 ">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Setor</label>
                                    <input type="text" class="form-control" disabled id="exampleInputEmail1" placeholder="Setor" value=""
                                        x-model="setor">
                                </div>
                            </div>
                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nr.Doc</label>
                                    <input type="text" class="form-control" disabled id="exampleInputEmail1" placeholder="Documento" value=""
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

                        <button class="btn btn-success">Salvar</button>

                        <template x-for="devolucao, indice in devolucoes">
                            <template x-if="devolucao.qtde > 0">
                                <div>
                                    <input type="hidden" x-bind:name="`produtos[${indice}][cd_devolucao_prod]`" x-bind:value="devolucao.cd_devolucao_prod" />
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
    <script>
        const devolucao = @js($devolucao);
    </script>
    <script src="{{ asset('js/rpclinica/estoque-devolucao.js') }}"></script>
@endsection
