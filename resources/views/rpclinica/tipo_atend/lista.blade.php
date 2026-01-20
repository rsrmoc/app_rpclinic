@extends('rpclinica.layout.layout')


@section('content')
    <div class="page-title">
        <h3>Relação de Tipo de Atendimento</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Relação</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white"><br>
                <div class="panel-heading clearfix" style="padding-bottom: 4px;">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-7">
                            <h4 class="panel-title"> </h4>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-5">
                            <form method="GET" id="searchList">
                                <div class="input-group m-b-sm">
                                    <input type="text" name="b" class="form-control"
                                        placeholder="Pesquisar por ID, código ou nome...">
                                    <span class="input-group-btn">
                                        <a href="{{ route('tipo.atend.create') }}" class="btn btn-success btn-addon m-b-sm"><span
                                                class="item"><span aria-hidden="true"
                                                    class="icon-note"></span>&nbsp;Novo</span></a>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <br>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="display table dataTable table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tipo</th> 
                                    <th class="text-center" style="width: 80px;">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($setores as $setor)
                                    <tr id="setor-{{ $setor->cd_tipo_atendimento }}">
                                        <th>{{ $setor->cd_tipo_atendimento }}</th>
                                        <td>{{ $setor->nm_tipo_atendimento }}</td> 
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('tipo.atend.edit', ['tipo' => $setor->cd_tipo_atendimento]) }}"
                                                    class="btn btn-success">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <button onclick="delete_cadastro('{{  route('tipo.atend.delete', ['tipo' => $setor->cd_tipo_atendimento])  }}', '#setor-{{ $setor->cd_tipo_atendimento }}')"
                                                    class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
