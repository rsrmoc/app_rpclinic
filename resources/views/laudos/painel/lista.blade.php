@extends('laudos.layout.layout')
 
@section('content')
    <div class="page-title">
        <h3>Instituto Adonhiran de Assistência à Saúde</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><strong>Paciente:</strong> {{ $Paciente->nm_paciente }} ( {{ $Paciente->cd_paciente }} ) </li>
                <li><strong>Data de Nascimento:</strong> {{ ($Paciente->dt_nasc) ? date('d/m/Y', strtotime($Paciente->dt_nasc)) : ' -- ' }}</li>
                <li><strong>CPF:</strong> {{ $Paciente->cpf }}</li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
             
                <div class="panel-body panel panel-white">
                    <div class="panel-group" id="accordiona" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading{{$Atendimento->cd_agendamento}}">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <ol class="breadcrumb" style="font-weight: normal;padding: 0px; margin: 0px;background-color: transparent;">
                                            <li><i class="fa fa-asterisk"></i> <strong>Atendimento:</strong> {{$Atendimento->cd_agendamento}}   </li>
                                            <li><strong> <i class="fa fa-calendar"></i> Data:</strong>  {{ ($Atendimento->dt_agenda) ? date('d/m/Y', strtotime($Atendimento->dt_agenda)) : ' -- ' }}</li>  
                                        </ol>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading{{$Atendimento->cd_agendamento}}">
 
                                <div class="panel-body">
                                
                                    <div class="table-responsive project-stats">  
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Data do Laudo</th>
                                                    <th>Profissional</th> 
                                                    <th>Convênio</th> 
                                                    <th>Exame</th> 
                                                    <th>Laudo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($Atendimento->itens as $key => $atend)
                                                    <tr>
                                                        <th scope="row">{{$atend->cd_agendamento_item}}</th>
                                                        <td>@if($atend->dt_laudo)  {{ ($atend->dt_laudo) ? date('d/m/Y', strtotime($atend->dt_laudo)) : ' -- ' }} @else <span class="label label-danger">Laudo Pendente</span> @endif </td>
                                                        <td>{{$Atendimento->profissional->nm_profissional}}</td>
                                                        <td>{{$Atendimento->convenio->nm_convenio}}</td>
                                                        <td>{{$atend->exame->nm_exame}}</td>
                                                        <td>
                                                            @if($atend->dt_laudo)
                                                                <a target="_blank" href="{{route('rpclinica.laudo.paciente',['exame'=>$atend->cd_agendamento_item,'key'=>$atend->key])}}"><img src="{{asset('assets\images\pdf.png')}}"></a>
                                                            @else 
                                                                <span class="label label-danger">Laudo Pendente</span>
                                                            @endif
                                                            
                                                        </td>
                                                    </tr>
                                                @endforeach 
                                                 
                                            </tbody>
                                         </table>
                                     </div>

                                </div>
                            </div>
                        </div>
                         
                        @if(isset(($Historico)))
                        <h3 style="font-weight: 400; font-style: italic"><i class="fa fa-history"></i> Histórico</h3>
                        @endif
                        @foreach($Historico as $key => $atend)
                            <div class="panel panel-default"> 
                                <div class="panel-heading" role="tab" id="heading{{$atend->cd_agendamento}}">
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$atend->cd_agendamento}}" aria-expanded="false" aria-controls="collapse{{$atend->cd_agendamento}}">
                                            <ol class="breadcrumb" style="font-weight: normal;padding: 0px; margin: 0px;background-color: transparent;">
                                                <li> <i class="fa fa-asterisk"></i> <strong>Atendimento:</strong> {{$atend->cd_agendamento}}   </li>
                                                <li><strong> <i class="fa fa-calendar"></i> Data:</strong>  {{ ($atend->dt_agenda) ? date('d/m/Y', strtotime($atend->dt_agenda)) : ' -- ' }}</li>  
                                            </ol>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{$atend->cd_agendamento}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$atend->cd_agendamento}}">
                                    <div class="panel-body">
                                            <div class="table-responsive project-stats">  
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Codigo</th>
                                                            <th>Data do Laudo</th>
                                                            <th>Profissional</th> 
                                                            <th>Convênio</th> 
                                                            <th>Exame</th> 
                                                            <th>Laudo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($atend->itens as $key => $item)
                                                            <tr>
                                                                <th scope="row">{{ $item->cd_agendamento_item}}</th>
                                                                <td>@if($item->dt_laudo)  {{ ($item->dt_laudo) ? date('d/m/Y', strtotime($item->dt_laudo)) : ' -- ' }} @else <span class="label label-danger">Laudo Pendente</span> @endif </td>
                                                                <td>{{$atend->profissional->nm_profissional}}</td>
                                                                <td>{{$atend->convenio->nm_convenio}}</td>
                                                                <td>{{$item->exame->nm_exame}}</td>
                                                                <td> 
                                                                    @if($item->dt_laudo)
                                                                        <a target="_blank" href="{{route('rpclinica.laudo.paciente',['exame'=>$item->cd_agendamento_item,'key'=>$item->key])}}"><img src="{{asset('assets\images\pdf.png')}}"></a>
                                                                    @else 
                                                                        <span class="label label-danger">Laudo Pendente</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach 
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
