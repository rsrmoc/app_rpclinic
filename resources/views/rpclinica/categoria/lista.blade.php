@extends('rpclinica.layout.layout')


@section('content')
    <div class="page-title">
        <h3>Relação de Categoria</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('categoria.listar') }}">Relação</a></li>
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
                                        <a href="{{ route('categoria.create') }}"
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
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Estrutural</th>
                                <th>Nome</th>
                                <th>Permitir Lançamento</th>
                                <th>Tipo de Lançamento</th>
                                <th class="text-center">Ação</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($categorias as $categoria)
                                @php
                                    $Espacos= "&nbsp;"; $Icone='<i class="fa fa-asterisk"></i>';  
                                    for ($i=0; $i < $categoria->espacos; $i++) {
                                        $Espacos = $Espacos."&nbsp;&nbsp;&nbsp;&nbsp;";
                                    }
                                    if($categoria->cd_categoria_pai){
                                        $Icone='<i class="fa fa-long-arrow-right"></i>';
                                    }
                                @endphp
                                <tr id="cat-{{ $categoria->cd_categoria }}">
                                    <th>{{ $categoria->cd_categoria }}</th>
                                    <th>{!! $Espacos.$Icone.$categoria->cod_estrutural !!}</th>
                                    <td>{{ $categoria->nm_categoria }}</td>
                                    <td>{{ ($categoria->sn_lancamento=='S') ? 'SIM' : 'NÃO' }}</td>
                                    <td>
                                        <span class="{{ CSS_DESP_RECEI[$categoria->tp_lancamento] }}">{{ $categoria->tp_lancamento }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('categoria.edit', ['categoria' => $categoria->cd_categoria]) }}" class="btn btn-success">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button onclick="delete_cadastro('{{ route('categoria.delete', ['categoria' => $categoria->cd_categoria]) }}', '#cat-{{ $categoria->cd_categoria }}')"
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
