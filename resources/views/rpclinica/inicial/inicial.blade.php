@extends('rpclinica.layout.layout')

@section('filterHeader')
    <li>
        <a href="javascript:void(0);" class="waves-effect waves-button waves-classic " data-toggle="modal" data-target=".modalParametros"><i class="fa fa-filter"></i></a>
    </li>
@endsection

@section('content')
  

    <div id="app" x-data="app">
        <div id="main-wrapper">
            <style>
                .info-box .info-box-stats span.info-box-title {
                    font-size: 15px;
                    margin-bottom: 0px;
                    color: #94a3b8 !important;
                }

                .stats-info ul li {
                    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                    padding: 12px 0;
                    color: #cbd5e1;
                }
                .bold{
                    font-weight: bold;
                    color: #2dd4bf !important;
                }
                .panel-body {
                    padding: 24px !important;
                }
                .info-box-icon i {
                    font-size: 32px !important;
                    opacity: 0.8;
                }
                .counter {
                    font-size: 28px !important;
                    margin-bottom: 4px !important;
                }
                
                /* Chart Background Fix */
                #flot1, #flot2, #flot3, #flot4, #flot5 {
                    background: transparent !important;
                }
                
                #flot1 canvas, #flot2 canvas, #flot3 canvas {
                    background: transparent !important;
                }
                
                .flot-base {
                    background: transparent !important;
                }
                
                /* Override any inline styles on chart container */
                .panel-body > div {
                    background: transparent !important;
                    background-color: transparent !important;
                }
            </style>

            <!-- Cards -->
            @if(($user->perfil?->dashboard_inicial=='exame')||($user->perfil?->dashboard_inicial=='consultorio')) 
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel info-box panel-white">
                            <div class="panel-body">
                                <div class="info-box-stats">
                                    <p class="counter" x-html="headerAtendimento"></p>
                                    <span class="info-box-title" x-html="cardUm">Atendimentos</span>
                                </div>
                                <div class="info-box-icon">
                                    <i style="color: #a78bfa;" class="fa fa-user-md"></i>
                                </div>
                                <span class="info-box-title"
                                    style=" margin-left: 20px; font-size:14px; margin-bottom: 10px; color: #64748b;" x-html="dt"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel info-box panel-white">
                            <div class="panel-body">
                                <div class="info-box-stats">
                                    <p class="counter" x-html="headerExame"></p>
                                    <span class="info-box-title" x-html="cardDois">Total de Exames</span>
                                </div>
                                <div class="info-box-icon">
                                    <i style="color: #2dd4bf;" class="fa fa-stethoscope"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel info-box panel-white">
                            <div class="panel-body">
                                <div class="info-box-stats">
                                    <p><span class="counter" x-html="headerPendente"></span></p>
                                    <span class="info-box-title" x-html="cardTres">Exames Pendentes</span>
                                </div>
                                <div class="info-box-icon">
                                    <i style="color: #fb7185;" class="fa fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel info-box panel-white">
                            <div class="panel-body">
                                <div class="info-box-stats">
                                    <p class="counter" x-html="headerLaudado"></p>
                                    <span class="info-box-title" x-html="cardQuatro">Exames Laudados</span>
                                </div>
                                <div class="info-box-icon">
                                    <i style="color: #4ade80;" class="fa fa-check-square"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($user->perfil?->dashboard_inicial=='exame') 

                <!-- Grafico exame -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="panel panel-white overflow-hidden">
                            <div class="panel-heading" style="padding: 20px 25px;">
                                <h3 class="panel-title" style="font-size: 16px; letter-spacing: 1px;" x-html="'ATENDIMENTOS - ' + dt_extenso + '<span class=\'text-teal-400 ml-3 opacity-80\'>' + nmProfissional + '</span>'"></h3>
                            </div>
                            <div class="panel-body" style="padding: 25px !important;">
                                <div id="flot1" style="text-align: center; height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-white overflow-hidden">
                            <div class="panel-heading" style="padding: 20px 25px;">
                                <h3 class="panel-title" style="font-size: 16px; letter-spacing: 1px;" x-html="'PENDÊNCIA DE LAUDOS'"></h3>
                            </div>
                            <div class="panel-body" style="padding: 25px !important;">
                                <div id="flot3" style="text-align: center; height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div> 


                <!-- Lista exame -->
                <div class="row">
                <div class="col-md-4">
                        <div class="panel panel-white overflow-hidden" >
                            <style>
                                .pac_atend::-webkit-scrollbar {
                                    display: none;
                                }
                                .list-unstyled li {
                                    border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
                                    padding: 15px !important;
                                    transition: background 0.3s ease;
                                }
                                .list-unstyled li:hover {
                                    background: rgba(255, 255, 255, 0.03);
                                }
                            </style>
                            <div class="stats-info" style="width: 100%;">
                                <div class="panel-heading" style="width: 100%; padding: 20px;">
                                    <h4 class="panel-title" style="width: 100%; margin: 0;">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fa fa-exclamation-triangle mr-3" style="color: #fb7185; font-size: 18px;"></i>
                                                <span class="text-white">Laudos Pendentes</span>
                                            </div>
                                            <a x-bind:href="'/rpclinica/inicio-xls-laudo/'+data+'/0'" class="text-teal-400 hover:text-teal-300 transition-colors">
                                                <i class="fa fa-file-excel-o"></i>
                                            </a>
                                        </div>
                                    </h4>
                                </div>
                                <div class="panel-body pac_atend"  style="width: 100%; height: 350px; overflow-y: auto; padding: 0 !important;">
                                    <ul class="list-unstyled mb-0">
                                        
                                        <template x-if="examePendente"> 
                                            <template x-for=" (val, index)  in examePendente"> 
                                                <li class="flex justify-between items-center">
                                                    <span class="text-slate-300" x-html="val.nome"></span> 
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-bold text-teal-400" x-html="val.laudado"></span>
                                                        <span class="text-slate-600">/</span>
                                                        <span class="font-bold text-rose-400" x-html="val.qtde"></span>
                                                    </div>
                                                </li> 
                                            </template>
                                        </template>
                                        <template x-if="!examePendente && !loadListaExame"> 
                                            <div class="flex flex-col items-center justify-center h-full py-10">
                                                <img style="width: 80px; opacity: 0.3; filter: grayscale(1);" src="{{ asset('assets\images\vazio.png') }}">
                                                <p class="text-slate-500 mt-4">Nenhum laudo pendente</p>
                                            </div>
                                        </template>
                                        
                                        <div class="flex items-center justify-center py-20" x-show="loadListaExame"> 
                                            <i class="fa fa-spinner fa-spin text-teal-400 text-3xl"></i>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>      <div class="col-md-4">
                        <div style="text-align: center; padding: 20px;">
                            @if ($emp->type_logo)
                                <img src = "data:{{ $emp->type_logo }};base64,{{ $emp->logo }}" style=" max-height: 100px;  ">
                            @else
                                <img height="100" src="{{ asset('assets\images\logo_inicial.svg') }}">
                            @endif
                        </div>
                    </div>
    
                    <div class="col-md-4">
                        <div class="panel panel-white overflow-hidden">
                            <div class="stats-info" style="width: 100%;">
                                <div class="panel-heading" style="width: 100%; padding: 20px;">
                                    <h4 class="panel-title" style="width: 100%; margin: 0;"> 
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fa fa-check-square-o mr-3" style="color: #4ade80; font-size: 18px;" aria-hidden="true"></i>
                                                <span class="text-white">Laudos Realizados</span>
                                            </div>
                                            <a x-bind:href="'/rpclinica/inicio-xls-laudo/'+data+'/1'" class="text-teal-400 hover:text-teal-300 transition-colors">
                                                <i class="fa fa-file-excel-o"></i>
                                            </a>
                                        </div>
                                    </h4>
                                </div>
                                <div class="panel-body pac_atend"  style="width: 100%; height: 350px; overflow-y: auto; padding: 0 !important;">
                                    <ul class="list-unstyled mb-0">
                                        <template x-if="exameLaudado">
                                            <template x-for=" (val, index)  in exameLaudado"> 
                                                <li class="flex justify-between items-center">
                                                    <span class="text-slate-300" x-html="val.nome"></span> 
                                                    <div class="font-bold text-teal-400" x-html="val.qtde"></div>
                                                </li> 
                                            </template>
                                        </template>
                                        <template x-if="!exameLaudado && !loadListaExame"> 
                                            <div class="flex flex-col items-center justify-center h-full py-10">
                                                <img style="width: 80px; opacity: 0.3; filter: grayscale(1);" src="{{ asset('assets\images\vazio.png') }}">
                                                <p class="text-slate-500 mt-4">Nenhum laudo realizado</p>
                                            </div>
                                        </template>
                                        <div class="flex items-center justify-center py-20" x-show="loadListaExame"> 
                                            <i class="fa fa-spinner fa-spin text-teal-400 text-3xl"></i>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
            @endif 

            @if($user->perfil?->dashboard_inicial=='consultorio') 
                <!-- Grafico consultorio -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-white overflow-hidden">
                            <div class="panel-heading" style="padding: 20px 25px;">
                                <h3 class="panel-title" style="font-size: 16px; letter-spacing: 1px;" x-html="'ATENDIMENTOS - ' + dt_extenso + '<span class=\'text-teal-400 ml-3 opacity-80\'>' + nmProfissional + '</span>'"></h3>
                            </div>
                            <div class="panel-body" style="padding: 25px !important;">
                                <div id="flot1" style="text-align: center; height: 400px;"></div>
                            </div>
                        </div>
                    </div> 
                </div> 

                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-white overflow-hidden" >
                            <div class="stats-info">
                                <div class="panel-heading" style="padding: 20px;">
                                    <h4 class="panel-title" style="margin: 0;">
                                        <i style="color: #2dd4bf; font-size: 18px;" class="fa fa-medkit mr-2"></i> 
                                        <span class="text-white">Pacientes Atendidos</span>
                                    </h4>
                                </div>
                                <div class="panel-body pac_atend"  style="width: 100%; height: 350px; overflow-y: auto; padding: 0 !important;">
                                    <ul class="list-unstyled mb-0">
                                        <template x-if="exameLaudado"> 
                                            <template x-for=" (val, index)  in exameLaudado"> 
                                                <li class="flex justify-between items-center px-4 py-3 border-b border-white/5">
                                                    <span class="text-slate-300" x-html="val.paciente"></span> 
                                                    <span class="text-teal-400 font-bold" x-html="val.data"></span>
                                                </li> 
                                            </template>
                                        </template>
                                        <template x-if="!exameLaudado && !loadListaExame"> 
                                            <div class="flex flex-col items-center justify-center h-full py-10">
                                                <img style="width: 80px; opacity: 0.3; filter: grayscale(1);" src="{{ asset('assets\images\vazio.png') }}">
                                                <p class="text-slate-500 mt-4">Nenhum paciente atendido</p>
                                            </div>
                                        </template>
                                        <div class="flex items-center justify-center py-20" x-show="loadListaExame" > 
                                            <i class="fa fa-spinner fa-spin text-teal-400 text-3xl"></i>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-md-4">
                        <div class="panel panel-white overflow-hidden flex items-center justify-center" style="height: 428px;">
                            <div style="text-align: center; padding: 20px;">
                                @if ($emp->type_logo)
                                    <img src = "data:{{ $emp->type_logo }};base64,{{ $emp->logo }}" style="max-height: 250px; filter: drop-shadow(0 0 20px rgba(45, 212, 191, 0.2));">
                                @else
                                    <img height="100" src="{{ asset('assets\images\logo_inicial.svg') }}" style="opacity: 0.8;">
                                @endif
                            </div>
                        </div>
                    </div>
    
                    <div class="col-md-4">
                        <div class="panel panel-white overflow-hidden">
                            <div class="stats-info">
                                <div class="panel-heading" style="padding: 20px;">
                                    <h4 class="panel-title" style="margin: 0;"> 
                                        <i class="glyphicon glyphicon-calendar mr-2" style="color: #2dd4bf; font-size: 16px;" aria-hidden="true"></i>  
                                        <span class="text-white">Pacientes Agendados</span>
                                    </h4>
                                </div>
                                <div class="panel-body pac_atend"  style="width: 100%; height: 350px; overflow-y: auto; padding: 0 !important;">
                                    <ul class="list-unstyled mb-0">
                                        <template x-if="examePendente">
                                            <template x-for=" (val, index)  in examePendente"> 
                                                <li class="flex justify-between items-center px-4 py-3 border-b border-white/5">
                                                    <span class="text-slate-300" x-html="val.paciente"></span> 
                                                    <span class="text-teal-400 font-bold" x-html="val.data"></span>
                                                </li> 
                                            </template>
                                        </template>
                                        <template x-if="!examePendente && !loadListaExame"> 
                                            <div class="flex flex-col items-center justify-center h-full py-10">
                                                <img style="width: 80px; opacity: 0.3; filter: grayscale(1);" src="{{ asset('assets\images\vazio.png') }}">
                                                <p class="text-slate-500 mt-4">Nenhum agendamento</p>
                                            </div>
                                        </template>
                                        <div class="flex items-center justify-center py-20" x-show="loadListaExame" > 
                                            <i class="fa fa-spinner fa-spin text-teal-400 text-3xl"></i>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            @endif 

            
            @if($user->perfil?->dashboard_inicial=='logo') 
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div style="text-align: center; padding: 20px; padding-top: 80px;">
                            @if ($emp->type_logo)
                                <img src = "data:{{ $emp->type_logo }};base64,{{ $emp->logo }}" style="  ">
                            @else
                                <img height="100" src="{{ asset('assets\images\logo_inicial.svg') }}">
                            @endif
                        </div>
                    </div>
                </div>

            @endif

            <div class="modal fade modalParametros" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="mySmallModalLabel">Parametros de Pesquisa</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row" style="margin-bottom: 10px; margin-top: 15px;">
 
                                <div class="col-md-offset-2 col-md-8 col-sm-12 col-xs-12 ">
                                    <div class="form-group  ">
                                        <label>Competência: <span class="red normal"></span></label>
                                        <input type="month" class="form-control " x-model="data"  maxlength="10" aria-required="true">
                                    </div>
                                </div>
  
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="button" x-on:click="getDataPanel" data-dismiss="modal" class="btn btn-success">Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>

  

        </div>
    </div>
 
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/flot/jquery.flot.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.time.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.symbol.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.resize.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.pie.min.js') }}"></script>

    <script> 


        const data = @js($request['data']);
        const msgWhast = @js($msgZap);
        
        // Define routes for JS use
        const routePanelCompromisso = @js(url('rpclinica/json/panel-dashboard-compromisso'));
        const routePanelConsultorio = @js(url('rpclinica/json/panel-dashboard-consultorio'));
        const routePanelExames = @js(url('rpclinica/json/panel-dashboard'));

    </script>

    @if($user->perfil?->dashboard_inicial=='exame')
        <script src="{{ asset('js/rpclinica/inicial-dashboard-exames.js') }}"></script>
    @endif

    @if($user->perfil?->dashboard_inicial=='consultorio')
        <script src="{{ asset('js/rpclinica/inicial-dashboard-consultorio.js') }}"></script>
    @endif
   
@endsection
