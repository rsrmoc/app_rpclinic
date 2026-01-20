@extends('rpclinica.layout.layout')



@section('content')

 
@php

    function formatarItem($tipo, $item){
        if($tipo == 'dt'){
            return date('d/m/Y', strtotime($item));
        }

        if($tipo == 'hr'){
            return date('H:i', strtotime($item));
        }

        if($tipo == 'dthr'){
            return date('d/m/Y H:i', strtotime($item));
        }

        if($tipo == 'mo'){
            return 'R$ ' . number_format($item, 2, ',', '.');
        }

        return $item;
    }

    function corAleatoria() {
      static $corAnterior = 0;
      static $cor = array( '#CFF', '#9FF', '#600', '#FF0', '#C69', '#0F0' );

      $aleatorio = rand( $corAnterior?1:0, count( $cor ) - 1 );
      if( $aleatorio >= $corAnterior ) $aleatorio++;
      $corAnterior = $aleatorio;
      return $cor[$aleatorio - 1];
    }

@endphp


    <div class="page-title">
        <h3>{{$relatorio->titulo}} </h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="#">Codigo do Indicador  [ {{ $relatorio->id }} ]</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12  panel panel-white"> 

            <!-- Styles -->
            <style>
                #chartdiv {
                width: 100%;
                height: 500px;
                }
                
                .amcharts-export-menu-top-right {
                top: 10px;
                right: 0;
                }
            </style>
    
            <!-- Resources -->
            <script src="{{ asset('assets/grafico/amcharts/amcharts.js') }}"></script>
            <script src="{{ asset('assets/grafico/amcharts/serial.js') }}"></script> 
            <script src="{{ asset('assets/grafico/amcharts/export.min.js') }}"></script>  
            <link rel="stylesheet" href="{{ asset('assets/grafico/amcharts/export.css') }}" type="text/css" media="all" /> 
    
            <!-- Chart code -->
            <script>
                var chart = AmCharts.makeChart("chartdiv", {
                "type": "serial",
                "theme": "none",
                "marginRight": 70,
                "dataProvider": [
                    @php $DescricaoColuna=""; @endphp
                    @foreach($dados_view as $key => $value) 
                            @if($key>0) {{ ',{' }} @else {{ '{'  }} @endif
                            @php $Colunas=0; @endphp
                            @foreach($value as $keyy => $col)
                                    
                                    @if($Colunas==0) "country": "{{ formatarItem($colunas[$keyy]['mascara'], $col) }}",  @endif
                                    @if($Colunas==1) "visits": "{{ formatarItem($colunas[$keyy]['mascara'], $col) }}",  @endif 
                                    @if($Colunas==0)
                                    @php    $DescricaoColuna=$keyy;  @endphp
                                    @endif
                                    @php     
                                        $Colunas=($Colunas+1); 
                                    @endphp
                            @endforeach
                          
                            "color": "{{ corAleatoria() }}"
                            {{ '}' }}
                       
                    @endforeach 
                ],
                "valueAxes": [{
                    "axisAlpha": 0,
                    "position": "left",
                    "title": "{{ $DescricaoColuna }}"
                }],
                "startDuration": 1,
                "graphs": [{
                    "balloonText": "<b>[[category]]: [[value]]</b>",
                    "fillColorsField": "color",
                    "labelText": "[[value]]",
                    "fillAlphas": 0.9,
                    "lineAlpha": 0.2,
                    "type": "column",
                    "valueField": "visits"
                }],
                "chartCursor": {
                    "categoryBalloonEnabled": false,
                    "cursorAlpha": 0,
                    "zoomable": false
                },
                "categoryField": "country",
                "categoryAxis": {
                    "gridPosition": "start",
                    "labelRotation": 45
                },
                "export": {
                    "enabled": false
                }
                
                });
            </script>
    
            <!-- HTML -->
            <div id="chartdiv"></div>
            



 
        </div>

    </div><!-- Main Wrapper -->
@endsection
