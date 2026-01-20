@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">


        <div class="row">
            <div class="col-md-8">
                <h3> Relatório de Produção</h3>
                <div class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="index-2.html">Relação</a></li>
                    </ol>
                </div>
            </div>



        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @error('record')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <form method="get">
                        @csrf
                        <input type="hidden" name="relatorio" value="S">
                        <div class="row">
                            <div class="col-md-2 ">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label for="area" class="mat-label">Parametro de Data <span
                                            class="red normal">*</span></label>
                                    <select class="form-control" required="" name="tp_data">
                                        <option value="dt_atendimento" @if ($request['tp_data'] == 'dt_atendimento') selected @endif>
                                            DATA DE ATENDIMENTO</option>
                                        <option value="dt_laudo" @if ($request['tp_data'] == 'dt_laudo') selected @endif>DATA DO
                                            LAUDO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label for="titulo" class="mat-label">Data Inicial <span
                                            class="red normal">*</span></label>
                                    <input type="date" class="form-control" required="" value="{{ $request['dti'] }}"
                                        name="dti">
                                </div>
                            </div>
                            <div class="col-md-2 ">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label for="titulo" class="mat-label">Data Final <span
                                            class="red normal">*</span></label>
                                    <input type="date" class="form-control" required="" value="{{ $request['dtf'] }}"
                                        name="dtf">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label for="tipo_relatorio" class="mat-label">Profissional <span
                                            class="red normal"></span></label>
                                    <select class="form-control" name="profissional">
                                        @if (empty($dados['setar_prof']))
                                            <option value="">Todos</option>
                                        @endif
                                        @foreach ($dados['profissional'] as $view)
                                            <option value="{{ $view->cd_profissional }}"
                                                @if ($request['profissional'] == $view->cd_profissional) selected @endif>
                                                {{ $view->nm_profissional }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label for="tipo_relatorio" class="mat-label">Convênio <span
                                            class="red normal"></span></label>
                                    <select class="form-control" name="convenio">
                                        <option value="">Todos</option>
                                        @foreach ($dados['convenio'] as $view)
                                            <option value="{{ $view->cd_convenio }}"
                                                @if ($request['convenio'] == $view->cd_convenio) selected @endif>{{ $view->nm_convenio }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4 ">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label for="conteudo" class="mat-label">Exame <span class="red normal">*</span></label>
                                    <select class="form-control" name="exame">
                                        <option value="">Todos</option>
                                        @foreach ($dados['exame'] as $view)
                                            <option value="{{ $view->cd_exame }}"
                                                @if ($request['exame'] == $view->cd_exame) selected @endif>{{ $view->nm_exame }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label for="titulo" class="mat-label">Paciente <span
                                            class="red normal"></span></label>
                                    <input type="text" class="form-control" value="{{ $request['paciente'] }}"
                                        name="paciente">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label for="tipo_relatorio" class="mat-label">Tipo de Relatório <span
                                            class="red normal">*</span></label>
                                    <select class="form-control" required="" name="tipo_relatorio">
                                        <option value="REL" @if ($request['tipo_relatorio'] == 'REL') selected @endif>Relatório
                                            Lista</option>
                                        <option value="GRA" @if ($request['tipo_relatorio'] == 'GRA') selected @endif>Graficos
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label for="tipo_relatorio" class="mat-label">Situação <span
                                            class="red normal"></span></label>
                                    <select class="form-control" name="situacao">
                                        <option value="">Todos</option>
                                        @foreach ($dados['situacao'] as $view)
                                            <option value="{{ $view->cd_situacao_itens }}"
                                                @if ($request['situacao'] == $view->cd_situacao_itens) selected @endif>
                                                {{ $view->nm_situacao_itens }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button class="btn btn-success" style="width: 100%; margin-top: 22px;"> <i
                                            class="fa fa-search"></i> </button>
                                </div>
                            </div>


                        </div>




                    </form>

                    @if ($request['tipo_relatorio'] == 'REL')
                        <div class="row">
                            <div class="col-md-12" style="padding-right: 5px; padding-left: 5px;">
                                <table class="table table-hover" style="padding-right: 5px; padding-left: 5px;">
                                    <thead>
                                        <tr class="active">
                                            <th> Atendimento</th>
                                            <th> Data</th>
                                            <th>Paciente</th>
                                            <th>Convênio</th>
                                            <th>Executante</th>
                                            <th>Exame</th>
                                            <th>Situação</th>
                                            <th>Data do Laudo</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($dados['query'] as $key => $value)
                                            <tr>
                                                <th>{{ $value->cd_agendamento }}</th>
                                                <td>{{ $value->data_atendimento }}</td>
                                                <td>{{ $value->nm_paciente }}</td>
                                                <td>{{ $value->nm_convenio }}</td>
                                                <td>{{ $value->nm_profissional }}</td>
                                                <td>{{ $value->exame?->nm_exame }}</td>
                                                <td>{{ $value->nm_situacao_itens }}</td>
                                                <td>{{ $value->data_laudo }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                                <!-- Botões de Paginação -->
                                <div class="row">
                                    <div class="col-md-6">
                                        @if ($request['relatorio'] == 'S')
                                            <div class="btn-group">

                                                <a href="{{ $dados['request'] . '&tp_relatorio=EXCEL' }}"
                                                    class="btn btn-default" data-toggle="tooltip" data-placement="top"
                                                    title="" data-original-title="Gerar Excel">
                                                    <i class="fa fa-file-excel-o" style="color: forestgreen"></i>
                                                </a>
                                                <a href="{{ $dados['request'] . '&tp_relatorio=PDF' }}" target="_blank"
                                                    class="btn btn-default" data-toggle="tooltip" data-placement="top"
                                                    title="" data-original-title="Gerar PDF"
                                                    style="margin-left: 5px;">
                                                    <i class="fa fa-file-pdf-o" style="color: brown;"></i>
                                                </a>

                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6" style="text-align: right" align="right">

                                        <span style="font-style: italic"
                                            x-html="'&lt;strong&gt;Total de Linhas&lt;/strong&gt; ' + totalLinhas "><strong>Total
                                                de Linhas</strong> {{ count($dados['query']) }}</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($request['tipo_relatorio'] == 'GRA')
                        <!-- Resources -->
                        <script src="{{ asset('assets/grafico/amcharts/amcharts.js') }}"></script>
                        <script src="{{ asset('assets/grafico/amcharts/serial.js') }}"></script>
                        <script src="{{ asset('assets/grafico/amcharts/pie.js') }}"></script>
                        <script src="{{ asset('assets/grafico/amcharts/export.min.js') }}"></script>
                        <link rel="stylesheet" href="{{ asset('assets/grafico/amcharts/export.css') }}" type="text/css"  media="all" />

                        <style>

                            #chartdiv_Movimento {
                                width: 100%;
                                height: 450px;
                                margin: 0px;
                            }

                            #chartdiv_Profissional {
                                width: 100%;
                                height: 450px;
                                margin: 0px;
                            }
 
                            #chartdiv_laudo {
                                width: 100%;
                                height: 550px;
                                margin: 0px;
                                padding: 0px;
                            }

                        </style>


                        <script> 
                            var chart = AmCharts.makeChart("chartdiv_laudo", {
                                "type": "pie",
                                "theme": "none",
                                "titles": [{
                                        "text": "Laudos Por Periodo",
                                        "size": 14
                                    },
                                    {
                                        "text": "Periodo : {{ date( 'd/m/Y' , strtotime($request['dti'])) }} - {{ date( 'd/m/Y' , strtotime($request['dtf'])) }}",
                                        "bold": false
                                    }
                                ],
                                "colorField": "color",
                                "labelColorField": "labelColor",
                                "dataProvider": [
                                    @foreach($dados['laudo'] as $key => $value)
                                        @if($key>0) , @endif
                                        {
                                            "country": "{{$value->exame->nm_exame}}",
                                            "visits": "{{$value->qtde}}", 
                                            "color": "{{$dados['paleta'][$key]}}"
                                        }
                                    @endforeach 
                                ],
                                "valueField": "visits",
                                "titleField": "country",
                                "startEffect": "elastic",
                                "startDuration": 2,
                                "labelRadius": -40,
                                "innerRadius": "40%",
                                "depth3D": 10,
                                "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                                "autoMargins": false,
                                "outlineAlpha": 0.2,
                                "outerRadius": 5,
                                "angle": 10,
                                "marginTop": 5,
                                "marginBottom": 5,
                                "marginLeft": 8,
                                "marginRight": 8,
                                "pullOutRadius": 0

                            }); 
                        </script>
                        <!-- HTML -->
                        <div class="row">
                            <div class="col-md-12 ">
                                <div id="chartdiv_laudo"></div>
                            </div>

                            <div class="col-md-12 ">

                                <div class="table-scrollable table-wrapper-scroll-y my-custom-scrollbar"
                                    style="margin: 15px;">
                                    <table style="width: 60%; margin-left: 20%;"
                                        class="table table-striped table-bordered table-hover">
                                        <thead class="bordered-blue"
                                            style=" color: #ffffff; font-size: 1.5rem; background-color: #65bc7b;">
                                            <tr>
                                                <th scope="col" style="border-bottom: 3px solid green;">Exames</th>
                                                <th scope="col" class="right"
                                                    style="border-bottom: 3px solid green; ">Qtde.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php 
                                              $total=0;
                                            @endphp
                                            @foreach($dados['laudo'] as $key => $value)
                                                @php  
                                                $total=($value->qtde +$total);
                                                @endphp
                                                <tr class="even pointer">
                                                    <td class="left"> {{ $value->exame->nm_exame }}</td>
                                                    <td class="right"> {{ $value->qtde }}</td>
                                                </tr> 

                                            @endforeach
                                            @if($dados['laudo'])
                                            <tr class="even pointer">
                                                <th class="left"><i class="fa fa-long-arrow-right"></i> TOTAL</th> 
                                                <th class="right" style="font-weight: 900"> {{ $total }}</th>
                                            </tr> 
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br> <br> <br> 


                        <script>
                            var chart = AmCharts.makeChart("chartdiv_Movimento", {
                                "theme": "none",
                                "type": "serial",
                                "titles": [{
                                        "text": "Movimento Por Periodo "
                                    },
                                    {
                                        "text": "Periodo : {{ date( 'd/m/Y' , strtotime($request['dti'])) }} - {{ date( 'd/m/Y' , strtotime($request['dtf'])) }}",
                                        "bold": false
                                    }
                                ],
                                "legend": {
                                    "useGraphSettings": true
                                },
                                "dataProvider": [


                                    @foreach($dados['atendimento'] as $key => $value)
                                        @if($key>0) , @endif
                                        {
                                            "country": "{{$value->exame->nm_exame}}",
                                            "year2004": "{{$value->qtde_laudo}}",
                                            "year2005": "{{$value->qtde_pendente}}",
                                        } 
                                    @endforeach  


                                ],
                                "startDuration": 1,
                                "graphs": [{
                                    "balloonText": "[[category]] : <b>[[value]]</b>",
                                    "title": "Realizado",
                                    "labelText": "[[value]]",
                                    "lineColor": "#08622d",
                                    "axisColor": "#08622d",
                                    "fillAlphas": 0.5,
                                    "lineAlpha": 0.9,
                                    "type": "column",
                                    "valueField": "year2004"
                                }, {
                                    "balloonText": "[[category]] : <b>[[value]]</b>",
                                    "title": "Pendente",
                                    "fillAlphas": 0.7,
                                    "lineAlpha": 0.8,
                                    "labelText": "[[value]]",
                                    "axisColor": "#ff8a65",
                                    "lineColor": "#ff8a65",
                                    "type": "column",
                                    "valueField": "year2005"
                                }],
                                "plotAreaFillAlphas": 0.2,
                                "depth3D": 60,
                                "angle": 30,
                                "categoryField": "country",
                                "categoryAxis": {
                                    "gridPosition": "start"
                                },
                                "export": {
                                    "enabled": false
                                } 
                              
                            });

                             
                        </script>
                        <!-- HTML -->
                        <div id="chartdiv_Movimento"></div>
                        <div class="table-scrollable table-wrapper-scroll-y my-custom-scrollbar" style="margin: 15px;">
                            <table style="width: 80%; margin-left: 10%;"
                                class="table table-striped table-bordered table-hover">
                                <thead class="bordered-blue"
                                    style=" color: #ffffff; font-size: 1.5rem; background-color: #65bc7b;">
                                    <tr>
                                        <th scope="col" style="border-bottom: 3px solid green;">Exame</th>
                                        <th scope="col" class="right" style="border-bottom: 3px solid green; ">Qtde. Realizado</th>
                                        <th scope="col" class="right" style="border-bottom: 3px solid green; ">Qtde. Pendente</th>
                                        <th scope="col" class="right" style="border-bottom: 3px solid green; ">Qtde. Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $laudo=0;$pendente=0;$total=0;
                                    @endphp
                                    @foreach($dados['atendimento'] as $key => $value)
                                        @php 
                                        $laudo=($value->qtde_laudo+$laudo);
                                        $pendente=($value->qtde_laudo+$pendente);
                                        $total=(( ( ($value->qtde_pendente) ? $value->qtde_pendente : 0 ) + ( ($value->qtde_laudo) ? $value->qtde_laudo : 0 ) )+$total);
                                        @endphp
                                        <tr class="even pointer">
                                            <td class="left"> {{ $value->exame->nm_exame }}</td>
                                            <td class="right"> {{ $value->qtde_laudo }}</td>
                                            <td class="right"> {{ $value->qtde_pendente }}</td>
                                            <td class="right" style="font-weight: 900"> {{ ( ( ($value->qtde_pendente) ? $value->qtde_pendente : 0 ) + ( ($value->qtde_laudo) ? $value->qtde_laudo : 0 ) ) }}</td>
                                        </tr> 

                                    @endforeach
                                    @if($dados['atendimento'])
                                        <tr class="even pointer">
                                            <th class="left"><i class="fa fa-long-arrow-right"></i> TOTAL</th>
                                            <th class="right"> {{ $laudo }}</th>
                                            <th class="right"> {{ $pendente }}</th>
                                            <th class="right" style="font-weight: 900"> {{ $total }}</th>
                                        </tr> 
                                    @endif
                                  
                                </tbody>
                            </table>
                        </div>
                        <br> <br> <br> 
 

                        <script>
 
                            var chart = AmCharts.makeChart("chartdiv_Profissional", {
                                "theme": "none",
                                "type": "serial",
                                        "titles": [{
                                        "text": "Movimento Por Profissional "
                                        }, 
                                        {
                                            "text": "Periodo : {{ date( 'd/m/Y' , strtotime($request['dti'])) }} - {{ date( 'd/m/Y' , strtotime($request['dtf'])) }}",
                                            "bold": false
                                        }],
                                        "legend": {
                                            "useGraphSettings": true
                                        },
                                        "dataProvider": [
                                                                   
                                        
                                        @foreach($dados['profissional'] as $key => $value)
                                            @if($key>0) , @endif
                                            {
                                                "country": "{{$value->nm_profissional}}",
                                                "year2004": "{{$value->qtde_laudo}}",
                                                "year2005": "{{$value->qtde_pendente}}",
                                            } 
                                        @endforeach    
                                        
                                        ],
                                "startDuration": 1,
                                "graphs": [{
                                    "balloonText": "[[category]] : <b>[[value]]</b>",
                                    "title": "Realizado",
                                    "labelText": "[[value]]",
                                    "lineColor": "#b1d34b",
                                    "axisColor": "#b1d34b",
                                    "fillAlphas": 0.5,
                                    "lineAlpha": 0.9,
                                    "type": "column",
                                    "valueField": "year2004"
                                }, {
                                    "balloonText": "[[category]] : <b>[[value]]</b>",
                                    "title": "Pendente",
                                    "fillAlphas": 0.7,
                                    "lineAlpha": 0.8, 
                                    "labelText": "[[value]]",
                                    "axisColor": "#e9a744",
                                    "lineColor": "#e9a744",
                                    "type": "column",
                                    "valueField": "year2005"
                                }],
                                "plotAreaFillAlphas": 0.2,
                                "depth3D": 60,
                                "angle": 30,
                                "categoryField": "country",
                                "categoryAxis": {
                                    "gridPosition": "start"
                                },
                                "export": {
                                    "enabled": false
                                }
                            }); 
           
                        </script> 
                        <!-- HTML -->
                        <div id="chartdiv_Profissional"></div>
                        <div class="table-scrollable table-wrapper-scroll-y my-custom-scrollbar" style="margin: 15px;">
                            <table style="width: 80%; margin-left: 10%;"
                                class="table table-striped table-bordered table-hover">
                                <thead class="bordered-blue"
                                    style=" color: #ffffff; font-size: 1.5rem; background-color: #65bc7b;">
                                    <tr>
                                        <th scope="col" style="border-bottom: 3px solid green;">Profissional</th>
                                        <th scope="col" class="right" style="border-bottom: 3px solid green; ">Qtde. Realizado</th>
                                        <th scope="col" class="right" style="border-bottom: 3px solid green; ">Qtde. Pendente</th>
                                        <th scope="col" class="right" style="border-bottom: 3px solid green; ">Qtde. Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $laudo=0;$pendente=0;$total=0;
                                    @endphp
                                    @foreach($dados['profissional'] as $key => $value)
                                        @php 
                                        $laudo=($value->qtde_laudo+$laudo);
                                        $pendente=($value->qtde_laudo+$pendente);
                                        $total=(( ( ($value->qtde_pendente) ? $value->qtde_pendente : 0 ) + ( ($value->qtde_laudo) ? $value->qtde_laudo : 0 ) )+$total);
                                        @endphp
                                        <tr class="even pointer">
                                            <td class="left"> {{ $value->nm_profissional }}</td>
                                            <td class="right"> {{ $value->qtde_laudo }}</td>
                                            <td class="right"> {{ $value->qtde_pendente }}</td>
                                            <td class="right" style="font-weight: 900"> {{ ( ( ($value->qtde_pendente) ? $value->qtde_pendente : 0 ) + ( ($value->qtde_laudo) ? $value->qtde_laudo : 0 ) ) }}</td>
                                        </tr> 

                                    @endforeach
                                    @if($dados['atendimento'])
                                        <tr class="even pointer">
                                            <th class="left"><i class="fa fa-long-arrow-right"></i> TOTAL</th>
                                            <th class="right"> {{ $laudo }}</th>
                                            <th class="right"> {{ $pendente }}</th>
                                            <th class="right" style="font-weight: 900"> {{ $total }}</th>
                                        </tr> 
                                    @endif
                                  
                                </tbody>
                            </table>
                        </div>

                    @endif
                </div>
            </div>
        </div>


    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/rpclinica/relatorios-add.js') }}"></script>
@endsection
