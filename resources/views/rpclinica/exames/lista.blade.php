@extends('rpclinica.layout.layout')


@section('content')
    <div class="page-title">
        <h3>Relação de Itens Cadastrados</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Relação de Itens</a></li>
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
                                        <a href="{{ route('exame.create') }}"
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
                                    <th>Descrição</th>
                                    <th>Tipo</th>
                                    <th>Local</th>
                                    <th>Procedimento</th>
                                    <th>Situação</th>
                                    <th class="text-center" style="width: 110px;">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($exames as $exame)
                                    <tr id="est-{{ $exame->cd_exame }}">
                                        <th>{{ $exame->cd_exame }}</th>
                                        <td>{{ $exame->nm_exame }}</td>
                                        <td>
                                            @if($exame->tp_item=='CI') Cirurgia @endif
                                            @if($exame->tp_item=='EX') Exame @endif
                                            @if($exame->tp_item=='PR') Pre-Exame @endif
                                            @if($exame->tp_item=='CO') Consulta @endif 
                                        </td>
                                        <td>{{ $exame->local?->nm_local }}</td>
                                        <th>{{ $exame->procedimento?->nm_proc }}</th>
                                        <td>
                                            <span class="{{ CSS_SN_ATIVO[$exame->sn_ativo] }}">{{ SN_ATIVO[$exame->sn_ativo] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">

                                                <a href="{{ route('exame.formulario', ['exame' => $exame->cd_exame]) }}" class="btn btn-primary">
                                                    <i class="fa fa-file-text-o"></i>
                                                </a>

                                                <a href="{{ route('exame.edit', ['exame' => $exame->cd_exame]) }}" class="btn btn-success">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <button onclick="delete_cadastro('{{ route('exame.delete', ['exame' => $exame->cd_exame]) }}', '#est-{{ $exame->cd_exame }}')"
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
