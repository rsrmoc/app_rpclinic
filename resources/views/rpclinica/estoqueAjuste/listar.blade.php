@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Relação de Ajuste de Estoque</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('estoque.ajuste.listar') }}">Relação</a></li>
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

                            <div class="col-md-2">
                                <select class=" form-control" tabindex="-1" style=" width: 100%" id="select-formularios" name="tipo_ajuste">
                                    <option value="">Tipo</option>
                                    @foreach ($tiposAjuste as $ajuste)
                                        <option value="{{ $ajuste->cd_tipo_ajuste }}"
                                            @if(request()->query('tipo_ajuste') == $ajuste->cd_tipo_ajuste) selected @endif>
                                            {{ $ajuste->nm_tipo_ajuste }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
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

                            <div class="col-md-2">
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
                                    <input type="text" name="b" class="form-control"
                                        placeholder="Pesquisar por código..." value="{{ request()->query('b') }}">

                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-success">
                                            <span class="fa fa-filter"></span>&ensp; Filtrar
                                        </button>
                                    </span>
                                </div>

                                <a href="{{ route('estoque.ajuste.create') }}" class="btn btn-success btn-addon m-b-sm">
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
                                <th>Tipo</th>
                                <th>Setor</th>
                                <th>Nr.Doc</th>
                                <th>Estoque</th>
                                <th class="text-center">Ação</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($ajustes as $ajuste)
                                <tr id="ajuste-{{ $ajuste->cd_ajuste }}">
                                    <th>{{ $ajuste->cd_ajuste }}</th>
                                    <td>{{ date_format(date_create($ajuste->dt_ajuste), 'd/m/Y') }}</td>
                                    <td>{{ $ajuste->tipoAjuste->nm_tipo_ajuste }}</td>
                                    <td>{{ $ajuste->setor->nm_setor }}</td>
                                    <td>{{ $ajuste->nr_doc }}</td>
                                    <td>{{ $ajuste->estoque->nm_estoque }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('estoque.ajuste.edit', ['ajuste' => $ajuste->cd_ajuste]) }}" class="btn btn-success">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button onclick="delete_cadastro('{{ route('estoque.ajuste.destroy', ['ajuste' => $ajuste->cd_ajuste]) }}', '#ajuste-{{ $ajuste->cd_ajuste }}')"
                                                class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if(count($ajustes) == 0)
                                <tr>
                                    <td colspan="7" class="text-center">Nenhuma saída</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
