@extends('rpclinica.layout.layout')

@section('content')
    <!-- conteudo da pagina -->
    <div class="page-title">
        <h3>Relação de Saída</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('estoque.saida.listar') }}">Relação</a></li>
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
                                <input type="date" name="data" class="form-control" />
                            </div>

                            <div class="col-md-3">
                                <select class=" form-control" tabindex="-1" style=" width: 100%" id="select-formularios" name="setor">
                                    <option value="">Setor</option>
                                    @foreach ($setores as $setor)
                                        <option value="{{ $setor->cd_setor }}"
                                            @if(request()->query('setor') == $setor->cd_setor) selected @endif>
                                            {{ $setor->nm_setor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select class=" form-control" tabindex="-1" style=" width: 100%" id="select-formularios" name="estoque">
                                    <option value="">Estoque</option>
                                    @foreach ($estoques as $estoque)
                                        <option value="{{ $estoque->cd_estoque }}"
                                            @if(request()->query('estoque') == $estoque->cd_estoque) selected @endif>
                                            {{ $estoque->nm_estoque }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-4 col-sm-4 col-md-4" style="display: flex; gap: 12px">
                                <div class="input-group m-b-sm" style="width: 100%">
                                    <input type="text" name="b" class="form-control" placeholder="Pesquisar por código..."
                                        value="{{ request()->query('b') }}" />

                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-success">
                                            <span class="fa fa-filter"></span>&ensp; Filtrar
                                        </button>
                                    </span>
                                </div>

                                <a href="{{ route('estoque.saida.create') }}"
                                    class="btn btn-success btn-addon m-b-sm" style="display: block">
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
                                <th>Setor</th>
                                <th>Estoque</th>
                                <th class="text-center">Ação</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($saidas as $saida)
                                <tr id="saida-{{ $saida->cd_solicitacao }}">
                                    <th>{{ $saida->cd_solicitacao }}</th>
                                    <td>{{ date_format(date_create($saida->dt_saida), 'd/m/Y') }}</td>
                                    <td>{{ $saida->nr_doc }}</td>
                                    <td>{{ $saida->setor->nm_setor }}</td>
                                    <td>{{ $saida->estoque->nm_estoque }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a class="btn btn-success"
                                                href="{{ route('estoque.saida.edit', ['saida' => $saida->cd_solicitacao]) }}">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button onclick="delete_cadastro('{{ route('estoque.saida.destroy', ['saida' => $saida->cd_solicitacao]) }}', '#saida-{{ $saida->cd_solicitacao }}')"
                                                class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if(count($saidas) == 0)
                                <tr>
                                    <td colspan="6" class="text-center">Nenhuma saída</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
