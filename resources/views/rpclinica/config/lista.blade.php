@extends('rpclinica.layout.layout')


@section('content')

    <style>
        .red{ color: red; }
        .green{color: green; }
        .nav-tabs>li>a {
            border-bottom: none;
        }
    </style>

    <div class="page-title">
        <h3>Configuração Geral</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('categoria.listar') }}">Relação</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">


                <div class="panel-body">


                    <div role="tabpanel">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#Tabelas" role="tab" data-toggle="tab" aria-expanded="false">Tabelas</a></li>
                            <li role="presentation" class=""><a href="#Colunas" role="tab" data-toggle="tab" aria-expanded="true">Colunas</a></li>
                            <li role="presentation" class=""><a href="#Diferenca" role="tab" data-toggle="tab" aria-expanded="false">Diferença Entre Campos</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="Tabelas">


                                    <table class="table table-striped" style="width: 100%;">

                                        <thead >
                                            <tr class="active" >
                                                <th style="width: 40%;"   > Nome da Tabela </th>
                                                <th style="width: 30%;" > Tipo  </th>
                                                <th style="width: 30%;" > Collation </th>
                                            </tr>
                                        </thead>

                                        <tbody >
                                        @foreach ($dados['query'] as $val )
                                            @if($val->TABLE_NAME!=$val->TABLE_NAME_BD)
                                                    <tr  >
                                                      <th style="width: 40%;"  > {{ mb_strtoupper($val->TABLE_NAME) }} </th>
                                                      <td style="width: 30%;" > {{ $val->ENGINE  }} </td>
                                                      <td style="width: 30%;" > {{ $val->TABLE_COLLATION  }}
                                                     </td>
                                                    </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>

                            </div>

                            <div role="tabpanel" class="tab-pane " id="Colunas">

                                <table class="table table-striped" style="width: 100%;">
                                    <thead >
                                        <tr class="active" >
                                            <th   > SQL </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dados['colunas'] as $vall )
                                            <tr >
                                                <td  > ALTER TABLE {{ $vall->TABLE_NAME }}  ADD {{ $vall->COLUMN_NAME }} {{ $vall->COLUMN_TYPE  }};</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div role="tabpanel" class="tab-pane" id="Diferenca">

                                @foreach ($dados['query'] as $val )
                                <table class="table table-striped" style="width: 100%;">
                                    <thead >
                                        <tr class="active" >
                                            <th style="width: 40%;" class= " @if($val->TABLE_NAME!=$val->TABLE_NAME_BD) red @else green @endif " > {{ mb_strtoupper($val->TABLE_NAME) }} </th>
                                            <th style="width: 30%;" class= " @if($val->ENGINE!=$val->ENGINE_BD) red @else green @endif "> {{ $val->ENGINE  }} </th>
                                            <th style="width: 30%;" class= " @if($val->TABLE_COLLATION!=$val->TABLE_COLLATION_BD) red @else green @endif "> {{ $val->TABLE_COLLATION  }} </th>
                                        </tr>
                                    </thead>
                                </table>

                                <table class="table table-striped" style="width: 100%;">
                                    <tbody>
                                        @foreach ($dados['itquery'] as $vall )
                                            @if($vall->TABLE_NAME == $val->TABLE_NAME )
                                            <tr >
                                                <th style="width: 5%;">
                                                    <i class="red fa fa-arrow-right"></i>
                                                </th>
                                                <td style="width: 20%;" > {{ $vall->COLUMN_NAME }} </td>
                                                <td style="width: 10%;" > {{ $vall->COLUMN_DEFAULT  }} </td>
                                                <td style="width: 15%;" > {{ $vall->COLUMN_TYPE  }} </td>
                                                <td style="width: 30%;" > {{ $vall->COLUMN_KEY  }} </td>
                                                <td style="width: 20%;" > {{ $vall->EXTRA  }} </td>
                                            </tr>
                                            @endif

                                        @endforeach
                                    </tbody>
                                </table>

                            @endforeach

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
