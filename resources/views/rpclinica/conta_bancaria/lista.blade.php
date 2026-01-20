@extends('rpclinica.layout.layout')


@section('content')
    <div class="page-title">
        <h3>Relação de Contas</h3>
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
                                        <a href="{{ route('conta.bancaria.create') }}"
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
                                    <th>#ID</th>
                                    <th>Nome da Conta</th>
                                    <th>Investimento</th>
                                    <th>Resumo</th>
                                    <th>Tipo</th>
                                    <th class="right">Saldo</th>
                                    <th class="text-center" style="width: 80px;">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contas as $conta)
                                    <tr id="conta-{{ $conta->cd_conta }}">
                                        <th>{{ $conta->cd_conta }}</th>
                                        <td>{{ $conta->nm_conta }}</td>
                                        <td>{{ ($conta->sn_investimento=='S') ? 'SIM' : 'NÃO'  }}</td>
                                        <td>{{ ($conta->sn_exibir_resumo=='S') ? 'SIM' : 'NÃO'  }}</td>
                                        <td>{{  $conta->tab_tipo->nm_tipo_conta }}</td>
                                        <td style="text-align:right;">R$ {{  number_format( ($conta->saldo_inicial) ? $conta->saldo_inicial : 0, 2, ',', '.') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('conta.bancaria.edit', ['conta' => $conta->cd_conta]) }}" class="btn btn-success">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <button onclick="delete_cadastro('{{ route('conta.bancaria.delete', ['conta' => $conta->cd_conta]) }}', '#conta-{{ $conta->cd_conta }}')"
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
