@extends('rpclinica.layout.layout')


@section('content')
    <div class="page-title">


        <table style="width: 100%">
            <tr>
                <td style="width: 60%"> 
                    <h3>Relação de Grupo de Procedimentos</h3>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index-2.html">Relação</a></li>
                        </ol>
                    </div>
                </td>
                <td style="width: 40%; text-align: right; font-size: 300"> 
                 
                </td>
            </tr>
        </table> 


      


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
                                        <a href="{{ route('grupo.procedimento.create') }}"
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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th> 
                                    <th>Descrição</th>
                                    <th>Tipo</th>
                                    <th>Produto</th>
                                    <th>Situação</th>
                                    <th class="text-center" style="width: 80px;">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $marcar = 0;
                                @endphp
                                @foreach ($dados as $val)
                                    @php
                                        if ($marcar == 0) {
                                            $marcar = 0;
                                            $class1 = 'sorting_1';
                                            $class2 = 'odd';
                                            ++$marcar;
                                        } elseif ($marcar == 1) {
                                            $marcar = 0;
                                            $class1 = 'sorting_1';
                                            $class2 = 'even';
                                        }
                                    @endphp
                                    <tr id="procedimento-{{ $val->cd_grupo }}" class="<?= $class2 ?>" role="row">
                                        <td class="<?= $class2 ?>">{{ $val->cd_grupo }}</td>
                                        <td>{{ $val->nm_grupo }}</td> 
                                        <td>
                                            @if ($val->tp_grupo == 'SH') Serviços Hospitalares @endif
                                            @if ($val->tp_grupo == 'SP') Serviços Profissionais @endif
                                            @if ($val->tp_grupo == 'SD') Serviços Diagnósticos @endif
                                            @if ($val->tp_grupo == 'ME') Medicamentos @endif
                                            @if ($val->tp_grupo == 'OP') Orteses e Proteses @endif
                                            @if ($val->tp_grupo == 'OL') Outros Lançamentos @endif
                                        </td>
                                        <td>
                                            @if ($val->sn_produto == 'S') SIM @else NÃO @endif
                                        </td>
                                        <td>
                                            @if ($val->sn_ativo == 'S')
                                                <span class="label label-success">ATIVO</span>
                                            @else
                                                <span class="label label-danger">INATIVO</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a class="btn btn-success"
                                                href="{{ route('grupo.procedimento.edit', ['grupo' => $val->cd_grupo]) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
    
                                                <button onclick="delete_cadastro('grupo-procedimento-delete/{{ $val->cd_grupo }}', '#procedimento-{{ $val->cd_grupo }}')"
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

    </div><!-- Main Wrapper -->
@endsection
