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
        <h3>Relatórios</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('relatorios') }}">Relação de Relatórios</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    <br><br>
                    <div class="row">
                        @foreach ($relatorios as $relatorio)
                            @if(empty($relatorio->rpsys))
                            <a href="{{ route('relatorios.relatorios', ['relatorio_id'=> $relatorio->id]) }}" class="col-md-4" style="padding-right: 8px;  padding-left: 8px;">
                                <div class="report" draggable="true">
                                      <span>
                                        @if($relatorio->tipo_relatorio == 'REL')   <i class="report-icon fa fa-file-text-o"></i>  @endif
                                        @if($relatorio->tipo_relatorio == 'GCOL')   <i class="report-icon fa fa-bar-chart-o"></i>  @endif
                                        @if($relatorio->tipo_relatorio == 'GPIZ')   <i class="report-icon fa fa-pie-chart"></i>  @endif
                                        @if($relatorio->tipo_relatorio == 'GCOLC')   <i class="report-icon fa fa-file-text-o"></i>  @endif
                                        @if($relatorio->tipo_relatorio == 'PDF')   <i class="report-icon fa fa-file-pdf-o"></i>  @endif
                                        @if($relatorio->tipo_relatorio == 'XLS')   <i class="report-icon fa fa-file-excel-o"></i>  @endif
                                      </span>
                                      <span class="report-data">
                                        <span class="title">{{ $relatorio->titulo }}</span>
                                        <span class="subtitle">{{ $tipo_relatorio[$relatorio->tipo_relatorio] }} </span>
                                      </span>
                                </div>
                            </a>
                            @else 

                            <a href="{{ route($relatorio->conteudo) }}" class="col-md-4" style="padding-right: 8px;  padding-left: 8px;">
                                <div class="report" draggable="true">
                                      <span>
                                        @if($relatorio->tipo_relatorio == 'REL')   <i class="report-icon fa fa-file-text-o"></i>  @endif
                                        @if($relatorio->tipo_relatorio == 'GCOL')   <i class="report-icon fa fa-bar-chart-o"></i>  @endif
                                        @if($relatorio->tipo_relatorio == 'GPIZ')   <i class="report-icon fa fa-pie-chart"></i>  @endif
                                        @if($relatorio->tipo_relatorio == 'GCOLC')   <i class="report-icon fa fa-file-text-o"></i>  @endif
                                        @if($relatorio->tipo_relatorio == 'PDF')   <i class="report-icon fa fa-file-pdf-o"></i>  @endif
                                        @if($relatorio->tipo_relatorio == 'XLS')   <i class="report-icon fa fa-file-excel-o"></i>  @endif
                                      </span>
                                      <span class="report-data">
                                        <span class="title">{{ $relatorio->titulo }}</span>
                                        <span class="subtitle">{{ $tipo_relatorio[$relatorio->tipo_relatorio] }} </span>
                                      </span>
                                </div>
                            </a>

                            @endif
                        @endforeach
                        
                        @if(count($relatorios->toArray())<=0)
                            
                            <div style="text-align: center; min-height: 400px; margin-top: 80px;"> 
                                <img style=" height: 80px;" src="{{asset('assets/images/logo_rpclinic.jpg')}}"><br>
                                <h4> Não existe Relatorio configurado para esse Perfil  </h4>
                            </div>
                            
                        @endif
                    </div>
                    <br><br>
                </div>
            </div>
        </div>

    </div>
@endsection
