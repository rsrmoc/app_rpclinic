@extends('rpclinica.layout.layout')

@php

$tipo_relatorio = [
    'REL' => 'Relatório HTML',
    'PDF' => 'Relatório PDF',
    'XLS' => 'Relatório Excel',
    'GCOL' => 'Grafico Coluna',
    'GPIZ' => 'Grafico Pizza',
    'GCOLC' => 'Grafico Coluna Comparativo'
];

@endphp

@section('content')

<style>
.report {
    box-sizing: border-box;
    position: relative;
    display: inline-block;
    padding: 6px;
    border: 1px solid #ced4dc;
    background-color: #e9edf2;
    background-repeat: no-repeat;
    color: #444;
    text-decoration: none;
    width: 100%;
    margin: 0 9px 9px 0;
    cursor: pointer;
    text-align: left;
    user-select: none;
    transition: background-color .1s ease-in;
}
.report .subtitle {
    opacity: .8;
}
.report .title {
    font-size: 1.2em;
    font-weight: 400;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.report .report-icon {
    color: #009b88;
    line-height: 36px;
    vertical-align: middle;
    font-size: 1.5em;
    margin-right: 12px;
    float: left;
    height: 100%;
}
</style>

    <div class="page-title">
        <div class="row">
            <div class="col-md-10">
                <h3>Report Designer</h3>
                <div class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('relatorios.list') }}">Relação</a></li>
                    </ol>
                </div>
            </div>
            <div class="col-md-2" style="text-align: right; ">
                <div class="row">


                    <div class="col-md-6 col-md-offset-6" style="text-align: right;margin-top: 10px;"> 
                        <a href="{{ route('relatorios.add') }}"
                            style="text-decoration: none; font-size: 18px;font-weight: 300;color: #74767d; cursor: pointer; font-style: italic;">
                            <span aria-hidden="true" class="icon-note"></span>
                        </a>
                    </div>

                   
                </div>
            </div>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">

                    <div class="row">
                        @foreach ($relatorios as $relatorio)
                            <a href="{{ route('relatorios.edit', ['relatorio_id'=> $relatorio->id]) }}" class="col-md-4" style="padding-right: 8px;  padding-left: 8px;">
                                <div class="report" draggable="true">
                                      <span>
                                        <i class="report-icon fa fa-file-text-o"></i>
                                      </span>
                                      <span class="report-data">
                                        <span class="title">{{ $relatorio->titulo }}</span>
                                        <span class="subtitle">{{ $tipo_relatorio[$relatorio->tipo_relatorio] }}</span>
                                      </span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
