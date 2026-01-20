@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastrar Ajuste de Estoque</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('estoque.ajuste.listar') }}">Ajuste de Estoque</a></li>
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

                    <form role="form" action="{{ route('estoque.ajuste.store') }}" method="post" x-ref="formAjuste"
                        x-on:submit.prevent="submit">
                        <input type="submit" style="display: none" />

                        @csrf

                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Estoque</label>

                                    <select class=" form-control" tabindex="-1" style=" width: 100%"
                                        id="select-formularios" name="estoque" required>
                                        <option value="">SELECIONE</option>

                                        @foreach ($estoques as $estoque)
                                            <option value="{{ $estoque->cd_estoque }}"
                                                @if (old('estoque') == $estoque->cd_estoque) selected @endif>
                                                {{ $estoque->nm_estoque }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tipo de Ajuste </label>
                                    <select class=" form-control" tabindex="-1" style=" width: 100%"
                                        id="select-formularios" name="tipo_de_ajuste" required>
                                        <option value="">SELECIONE</option>

                                        @foreach ($tiposAjuste as $ajuste)
                                            <option value="{{ $ajuste->cd_tipo_ajuste }}"
                                                @if (old('tipo_de_ajuste') == $ajuste->cd_tipo_ajuste) selected @endif>
                                                {{ $ajuste->nm_tipo_ajuste }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Setor </label>
                                    <select class=" form-control" tabindex="-1" style=" width: 100%"
                                        id="select-formularios" name="setor" required>
                                        <option value="">SELECIONE</option>

                                        @foreach ($setores as $setor)
                                            <option value="{{ $setor->cd_setor }}"
                                                @if (old('setor') == $setor->cd_setor) selected @endif>
                                                {{ $setor->nm_setor }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nr.Documento</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1"
                                        placeholder="Documento" name="numero_documento"
                                        value="{{ old('numero_documento') }}" required />
                                </div>
                            </div>
                        </div>

                        <template x-for="produto, indice in produtosEntrada">
                            <div>
                                <input type="hidden" x-bind:name="`produtos[${indice}][cd_produto]`"
                                    x-bind:value="produto.cd_produto" />
                                <input type="hidden" x-bind:name="`produtos[${indice}][cd_lote_produto]`"
                                    x-bind:value="produto.cd_lote_produto" />
                                <input type="hidden" x-bind:name="`produtos[${indice}][qtde]`"
                                    x-bind:value="produto.qtde" />
                            </div>
                        </template>
                    </form>

                    <hr />

                    <form x-on:submit.prevent="addEntradaProduto" class="row m-b-sm">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Produto </label>

                                <select class=" form-control" tabindex="-1" style=" width: 100%"
                                    id="select-formulario-produto" required>
                                    <option value="">SELECIONE</option>
                                    @foreach ($produtos as $produto)
                                        <option value="{{ $produto->cd_produto }}" data-lote="{{ $produto->sn_lote }}">
                                            {{ $produto->nm_produto }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Lote </label>

                                <div class="line">
                                    <div style=" width: 100%">
                                        <select class=" form-control" tabindex="-1" style=" width: 100%"
                                            x-bind:required="inputsLoteRequired" id="entradaLotes">
                                            <option value="">SELECIONE</option>
                                        </select>
                                    </div>

                                    <div>
                                        <button class="btn btn-default" type="button" x-on:click="openModalLote">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 ">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Qtde.</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" placeholder="Qtde"
                                    required x-model="inputsEntrada.qtde" />
                            </div>
                        </div>

                        <div class="col-md-1 ">
                            <div class="form-group" style="margin-top: 25px;">
                                <button type="submit" class="btn btn-success">Adicionar</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produto</th>

                                <th>Lote</th>

                                <th>Qtde.</th>

                                <th class="text-center">Ação</th>
                            </tr>
                        </thead>

                        <tbody>
                            <template x-for="produto, indice in produtosEntrada">
                                <tr>
                                    <td x-text="nomeDoProduto(produto.cd_produto)"></td>

                                    <td x-html="nomeDoLote(produto.cd_lote_produto)"></td>

                                    <td x-text="produto.qtde"></td>

                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-danger" x-on:click="deleteEntradaProduto(indice)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            <template x-if="produtosEntrada.length == 0">
                                <tr class="text-center">
                                    <td colspan="5">Nenhum produto adicionado</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <hr />

                    <button x-on:click="$refs.formAjuste.querySelector('input[type=submit]').click()"
                        class="btn btn-success">Cadastrar</button>
                </div>
            </div>
        </div>

        <form class="modal" id="modal-lote" x-ref="modalLote" x-on:submit.prevent="addLoteProduto">
            <div class="modal-dialog modal-sm">
                <div x-show="loadingModalLote">
                    <x-absolute-loading message="Salvando..." />
                </div>

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>

                        <h4 class="modal-title" id="mySmallModalLabel">Novo lote</h4>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">Lote</label>
                        <input type="text" class="form-control m-b-sm" placeholder="Nome do lote" required
                            x-model="inputsLote.nm_lote" />

                        <div class="m-b-sm">
                            <label for="exampleInputEmail1">Produto </label>

                            <select class="form-control" tabindex="-1" style=" width: 100%" required
                                id="modal-lote-produtos-controla-lote" x-ref="inputProdutoModalLotes">
                                <option value="">SELECIONE</option>
                                @foreach ($produtos as $produto)
                                    @if ($produto->sn_lote == 'S')
                                        <option value="{{ $produto->cd_produto }}">{{ $produto->nm_produto }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <label class="form-label">Validade</label>
                        <input type="date" class="form-control" required x-model="inputsLote.validade" />
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <style>
        #modal-lote .datepicker.datepicker-inline {
            width: 100%;
        }

        #modal-lote .datepicker.datepicker-inline table {
            margin: 0 auto;
            width: 100%;
        }

        .select2-container.select2-container--default.select2-container--open,
        .swal2-container {
            z-index: 9999;
        }
    </style>
    <script>
        const lotes = @js($lotes);
        const produtos = @js($produtos);
    </script>
    <script src="{{ asset('js/rpclinica/estoque-ajuste.js') }}"></script>
@endsection
