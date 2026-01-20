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
                height: 600px;
                }
                
                .amcharts-export-menu-top-right {
                top: 10px;
                right: 0;
                }
            </style>
    
            <!-- Resources -->
            <script src="{{ asset('assets/grafico/amcharts/amcharts.js') }}"></script>
            <script src="{{ asset('assets/grafico/amcharts/pie.js') }}"></script>  
            <script src="{{ asset('assets/grafico/amcharts/export.min.js') }}"></script>  
            <link rel="stylesheet" href="{{ asset('assets/grafico/amcharts/export.css') }}" type="text/css" media="all" /> 
        
            <!-- Chart code -->
            <script>
                var chart = AmCharts.makeChart( "chartdiv", {
                "type": "pie",
                "theme": "none",
                "dataProvider": [  
                    @foreach($dados_view as $key => $value) 
                            @if($key>0) {{ ',{' }} @else {{ '{'  }} @endif
                            @php $Colunas=0; @endphp
                            @foreach($value as $keyy => $col)
                                    
                                    @if($Colunas==0) "country": "{{ formatarItem($colunas[$keyy]['mascara'], $col) }}",  @endif
                                    @if($Colunas==1) "litres": "{{ formatarItem($colunas[$keyy]['mascara'], $col) }}"  @endif  
                                    @php     
                                        $Colunas=($Colunas+1); 
                                    @endphp
                            @endforeach
                           
                            {{ '}' }}
                       
                    @endforeach 
                
                ],
                "valueField": "litres",
                "titleField": "country",
                "balloon":{
                "fixedPosition":true
                },
                "export": {
                    "enabled": false
                }
                } );
            </script>
    
            <!-- HTML -->
            <div id="chartdiv"></div>
            



 
        </div>

    </div><!-- Main Wrapper -->
@endsection
