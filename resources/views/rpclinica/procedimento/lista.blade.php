@extends('rpclinica.layout.layout')


@section('content')
    <div class="page-title">


        <table style="width: 100%">
            <tr>
                <td style="width: 60%"> 
                    <h3>Relação de Procedimentos</h3>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index-2.html">Relação</a></li>
                        </ol>
                    </div>
                </td>
                <td style="width: 40%; text-align: right; font-size: 300"> 
                    <div class="panel-body" >
                        <div class="btn-group" data-toggle="tooltip" title="Importar XLSX">
                            <button type="button" style="padding: 20px; font-style: italic;" class="btn btn-default" data-toggle="modal" data-placement="top"  data-target="#myModal" ><span aria-hidden="true" class="icon-cloud-upload"></span></button>  
                        </div>
                    </div>
                </td>
            </tr>
        </table> 


        <!-- Modal XLS-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form role="form"  enctype="multipart/form-data" action="{{ route('procedimento.import') }}"  method="post" role="form"> 
                @csrf   
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">                            
                    <span aria-hidden="true" style="padding-top: 20px;"><span aria-hidden="true"
                    class="icon-close"></span></span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                   
                    Importar Planilha de Procedimentos</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                            <br>
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label for="fname" class="mat-label">Planilha XLS <span class="red normal">*</span></label>
                                <input type="file" class="form-control " required  name="xls"  aria-required="true">
                                @if ($errors->has('cod_proc'))
                                    <div class="error">{{ $errors->first('cod_proc') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
  
                      

                </div>
                <div class="modal-footer"> 

                    
                    <a href="{{ asset('assets/xls/procedimento.xlsx ') }}" class="btn btn-default" style="text-align: left
                    ;"><i class="fa fa-file-excel-o"></i> Planilha Modelo</a>
                    <button type="submit" class="btn btn-success"> <i class="fa fa-upload"></i> Importar Informações</button>
                </div> 
                </form>
            </div>
            </div>
        </div>
        <!-- FIm Modal XLS-->


    </div>
    <div id="main-wrapper">

        @if (session('error'))
            <div class="alert alert-danger">
                <h5>Houve alguns erros:</h5> 
                <ul>
                    {!! session('error') !!}
                </ul>
            </div>
        @endif
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
                                        <a href="{{ route('procedimento.create') }}"
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
                                    <th>Codigo</th>
                                    <th>Descrição</th>
                                    <th>Grupo</th>
                                    <th>Unidade</th>
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
                                    <tr id="procedimento-{{ $val->cd_proc }}" class="<?= $class2 ?>" role="row">
                                        <td class="<?= $class2 ?>">{{ $val->cd_proc }}</td>
                                        <td>{{ $val->cod_proc }}</td>
                                        <td>{{ $val->nm_proc }}</td>
                                        <td>{{ $val->grupo?->nm_grupo }}</td>
                                        <td>{{ $val->unidade }}</td>
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
                                                href="{{ route('procedimento.edit', ['procedimento' => $val->cd_proc]) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
    
                                                <button onclick="delete_cadastro('procedimento-delete/{{ $val->cd_proc }}', '#procedimento-{{ $val->cd_proc }}')"
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
