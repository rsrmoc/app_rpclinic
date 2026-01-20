@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>{{$relatorio->titulo}} </h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="#">Codigo do Indicador  [ {{ $relatorio->id }} ]</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div  x-data="appLancamentos" class="panel panel-white">
                <div class="panel-body">
                    @error('error')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @include('rpclinica.relatorios.relacao_tabela', ['colunas' => $colunas, 'dados_view' => $dados_view])

                </div>
            </div>
        </div>

    </div><!-- Main Wrapper -->
@endsection
