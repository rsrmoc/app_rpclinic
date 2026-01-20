@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Relação de Entrada</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('estoque.entrada.listar') }}">Relação</a></li>
            </ol>
        </div>
    </div>

    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white"><br>
                <div class="panel-heading clearfix" style="padding-bottom: 4px;">
                    <form method="GET" id="searchList">
                        <div class="row">
                            <div class="col-md-2 ">
                                <input type="date" name="data" class="form-control" value="{{ request()->query('data') }}">
                            </div>
                            <div class="col-md-3">
                                <select class=" form-control" tabindex="-1" style=" width: 100%" id="select-formularios"
                                    name="fornecedor">
                                    <option value="">Fornecedor</option>
                                    @foreach ($fornecedores as $fornecedor)
                                        <option value="{{ $fornecedor->cd_fornecedor }}"
                                            @if (request()->query('fornecedor') == $fornecedor->cd_fornecedor) selected @endif>
                                            {{ $fornecedor->nm_fornecedor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class=" form-control" tabindex="-1" style=" width: 100%" id="select-formularios"
                                    name="estoque">
                                    <option value="">Estoque</option>
                                    @foreach ($estoques as $estoque)
                                        <option value="{{ $estoque->cd_estoque }}"
                                            @if (request()->query('estoque') == $estoque->cd_estoque) selected @endif>
                                            {{ $estoque->nm_estoque }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-3 col-sm-3 col-md-5" style="display: flex; gap: 12px">
                                <div class="input-group m-b-sm" style="width: 100%">
                                    <input type="text" name="b" class="form-control"
                                        placeholder="Pesquisar por código...">

                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-success btn-addon m-b-sm">
                                            <span class="fa fa-filter"></span>&ensp; Filtrar
                                        </button>
                                    </span>
                                </div>

                                <a href="{{ route('estoque.entrada.create') }}"
                                    class="btn btn-success btn-addon m-b-sm">
                                    <span class="item">
                                        <span aria-hidden="true" class="icon-note"></span>&nbsp;Novo
                                    </span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Data</th>
                                <th>Nr.Doc</th>
                                <th>Ordem de Compras</th>
                                <th>Fornecedor</th>
                                <th>Estoque</th>
                                <th class="text-center">Ação</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($entradas as $entrada)
                                <tr id="entrada-{{ $entrada->cd_solicitacao }}">
                                    <th>{{ $entrada->cd_solicitacao }}</th>
                                    <td>{{ date_format(date_create($entrada->dt_solicitacao), 'd/m/Y') }}</td>
                                    <td>{{ $entrada->nr_doc }}</td>
                                    <td>{{ $entrada->cd_ord_Com }}</td>
                                    <td>{{ $entrada->fornecedor?->nm_fornecedor }}</td>
                                    <td>{{ $entrada->estoque?->nm_estoque }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('estoque.entrada.edit', ['entrada' => $entrada->cd_solicitacao]) }}"
                                                class="btn btn-success">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button
                                                onclick="delete_cadastro('{{ route('estoque.entrada.destroy', ['entrada' => $entrada->cd_solicitacao]) }}', '#entrada-{{ $entrada->cd_solicitacao }}')"
                                                class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if(count($entradas) == 0)
                                <tr>
                                    <td colspan="7" class="text-center">Nenhuma entrada</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
