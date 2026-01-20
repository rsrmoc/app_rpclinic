@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Relação de Devolução</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('estoque.devolucao.listar') }}">Relação</a></li>
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
                                <input type="date" name="data" class="form-control" placeholder="Data" value="{{ request()->query('data') }}">
                            </div>

                            <div class="col-md-2 ">
                                <input type="text" name="saida" class="form-control" placeholder="Codigo da Saida" value="{{ request()->query('saida') }}">
                            </div>

                            <div class="col-md-3">
                                <select class=" form-control" tabindex="-1" style=" width: 100%" id="select-formularios" name="estoque">
                                    <option value="">Estoque</option>
                                    @foreach ($estoques as $estoque)
                                        <option @if (request()->query('estoque') == $estoque->cd_estoque) selected @endif
                                            value="{{ $estoque->cd_estoque }}">
                                            {{ $estoque->nm_estoque }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5" style="display: flex; gap: 12px">
                                <div class="input-group m-b-sm" style="width: 100%">
                                    <input type="text" name="b" class="form-control"
                                        placeholder="Pesquisar por código..." value="{{ request()->query('b') }}">

                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-success btn-addon m-b-sm">
                                            <span class="fa fa-filter"></span>&ensp; Filtrar
                                        </button>
                                    </span>
                                </div>

                                <a href="{{ route('estoque.devolucao.create') }}"
                                    class="btn btn-success btn-addon m-b-sm"><span class="item">
                                        <span aria-hidden="true" class="icon-note"></span>&nbsp;Novo</span>
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
                                <th>Saida</th>
                                <th>Estoque</th>
                                <th class="text-center">Ação</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($devolucoes as $devolucao)
                                <tr id="devolucao-{{ $devolucao->cd_devolucao }}">
                                    <th>{{ $devolucao->cd_devolucao }}</th>
                                    <td>{{ date_format(date_create($devolucao->dt_devolucao), 'd/m/Y')  }}</td>
                                    <td>{{ $devolucao->cd_solicitacao_saida }}</td>
                                    <td>{{ $devolucao->solicitacaoSaida->estoque->nm_estoque }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('estoque.devolucao.edit', ['devolucao' => $devolucao->cd_devolucao]) }}" class="btn btn-success">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button
                                                onclick="delete_cadastro('{{ route('estoque.devolucao.destroy', ['devolucao' => $devolucao->cd_devolucao]) }}', '#devolucao-{{ $devolucao->cd_devolucao }}')"
                                                class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
