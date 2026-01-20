@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Saldo de Estoque</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('estoque.saldo.listar') }}">Relação</a></li>
            </ol>
        </div>
    </div>

    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white"><br>
                <div class="panel-heading clearfix" style="padding-bottom: 4px;">
                    <form action="">
                        <div class="row">
                            <div class="col-md-4">
                                <select class=" form-control" tabindex="-1" style=" width: 100%"
                                    id="select-formularios">
                                    <option value="">Estoque</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <select class=" form-control" tabindex="-1" style=" width: 100%"
                                    id="select-formularios">
                                    <option value="">Produto</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <select class=" form-control" tabindex="-1" style=" width: 100%"
                                    id="select-formularios">
                                    <option value="">Classe de Produto</option>
                                </select>
                            </div>

                        </div>
                    </form>
                </div>
                <br>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Estoque</th>
                                <th>Produto</th>
                                <th>Classe</th>
                                <th>Saldo</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
@endsection
