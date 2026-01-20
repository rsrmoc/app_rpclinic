@extends('rpclinica.layout.layout')


@section('content')
    <div class="page-title">
        <h3>Relação de Tipo de Escala</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('localidade.listar') }}">Relação</a></li>
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
                                        <a href="{{ route('escala-tipo.create') }}"
                                            class="btn btn-success btn-addon m-b-sm"><span class="item"><span
                                                    aria-hidden="true" class="icon-note"></span>&nbsp;Novo</span></a>
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
                                    <th>Localidade</th> 
                                    <th class="text-center" style="width: 80px;">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Tipos as $tipo)
                                    <tr id="tipo-{{ $tipo->cd_escala_tipo }}">
                                        <td>{{ $tipo->cd_escala_tipo }}</td>

                                        <td>{{ $tipo->nm_tipo_escala }}</td>
  
                                        <td>
                                            <span class="{{ CSS_SN_ATIVO[$tipo->sn_ativo] }}">
                                                {{ SN_ATIVO[$tipo->sn_ativo] }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('escala-tipo.edit', ['tipo' => $tipo->cd_escala_tipo]) }}"
                                                    class="btn btn-success">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <button
                                                    onclick="delete_cadastro('escala-tipo-delete/{{ $tipo->cd_escala_tipo }}', '#tipo-{{ $tipo->cd_escala_tipo }}')"
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

    </div><!-- Main Wrapper -->
@endsection
