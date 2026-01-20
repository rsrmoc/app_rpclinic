
<style> 
.class_enviado{ color: #08bd0f }
.class_aguardando{color: #c8c6c6 }
</style>

<form x-on:submit.prevent="pesquisaConfirmacao" id="form-pesquisa-confirmacao"   style="">
    <input type="hidden" name="tipo" value="avanc">
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label>Data: <span class="red normal">*</span></label>
                <input type="date" class="form-control required" value="{{ old('dti') }}"
                    name="data" maxlength="100" aria-required="true" required>
            </div>
        </div> 
        <div class="col-md-5">
            <div class="form-group">
                <label>Agenda: <span class="red normal"></span></label>
                <select class="form-control"  tabindex="-1" style="width: 100%"  name="agenda"  >
                    <option value="">Selecione</option>
                    @foreach ($agendas as $linha)
                        <option value="{{ $linha->cd_agenda }}">{{ $linha->nm_agenda }}</option>
                    @endforeach
                </select>
            </div>
        </div>
 

   
        <div class="col-md-3">
            <div class="form-group">
                <label>Situação: <span class="red normal"></span></label>
                <select class="form-control"  tabindex="-1" style="width: 100%"  name="situacao"  >
                    <option value="">Selecione</option>
                    @foreach ($situacoes as $linha)
                        <option value="{{ $linha->cd_situacao }}">{{ $linha->nm_situacao}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <button type="submit" style="font-size: 15px; margin-top: 22px; width: 100%;" class="btn btn-success">
                
                <template x-if="buttonPesqAvanc">
                    <i class="fa fa-refresh fa-spin "></i> 
                  </template>
                  <template x-if="!buttonPesqAvanc">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span> 
                  </template>
            </button>
        </div>
    </div>
</form>

<form x-on:submit.prevent="EnvioMensagem" id="form-envio-confirmacao"   style="">
    <table  class="table table-striped table-hover " style="margin-top: 40px;"> 
        <thead>
            <tr class="active"> 
                <th class="text-center"> <input type="checkbox"  class="marcar" onclick='marcardesmarcar();' ></th>
                <th class="text-left">Agendamento</th>
                <th class="text-left">Data</th>
                <th class="text-left">Agenda</th>
                <th class="text-left">Especialidade</th>
                <th class="text-left">Paciente</th>
                <th class="text-left">Convênio</th>
                <th class="text-left">Celular</th>   
                <th class="text-center">Opções</th>
                <th class="text-center">Ação</th>
            </tr>
        </thead> 

        <tbody>
            <template x-if="tablePesqConfir">
                <template x-for="item in tablePesqConfir">
                    <tr> 
                        <th class="text-center">
                            <template x-if="item.situacao?.agendar=='S'">
                                <input type="checkbox" name="agendamento[]" class="marcar" x-bind:value="item.cd_agendamento">
                            </template>
                        </th>
                        <th class="text-left">
                            <span x-text="item.cd_agendamento"></span> 
                        </th>
                        <td class="text-left">
                            <span x-text="item.data"></span> 
                        </td>
                        <td class="text-left">
                            <span x-text="item.agenda.nm_agenda"></span> 
                        </td>
                        <td class="text-left">
                            <span x-text="item.especialidade.nm_especialidade"></span> 
                        </td>
                        <td class="text-left">
                            <span x-text="item.paciente.nm_paciente"></span>  
                        </td>
                        <td class="text-left">
                            <span x-text="item.convenio.nm_convenio"></span>   
                        </td>
                        <td class="text-left">
                            <span x-text="item.celular"></span>   
                        </td>
                        <td class="text-center">
                            <i class="fa fa-whatsapp" x-bind:class="(item.tab_whast_send.length >0 ) ? 'class_enviado' : 'class_aguardando'" data-toggle="modal" data-target="#ModalEnvios" x-on:click="getEnvios(item);" 
                            style="font-size: 15px; cursor: pointer;" aria-hidden="true"></i> 
    
                        </td> 
                        <td class="text-center">
                            <div class="btn-group ">
                                <span data-toggle="dropdown" aria-expanded="false" class="label" style="cursor: pointer;" x-bind:class="item.situacao.class" x-html="item.situacao.icone+' '+item.situacao.nm_situacao"></span>
                            
                                <template x-if="( ( (item.sn_finalizado) ? item.sn_finalizado : 'N' ) =='N' )"> 
                                    <ul class="dropdown-menu" role="menu">
                                        @foreach ($situacoes as $linha)
                                            @if($linha->agendamento=='S')
                                                <li ><a href="#" x-on:click="storeConfirmacao(item,'{{ $linha->cd_situacao }}')" style=" color: {!! $linha->color  !!};font-weight: 700; padding: 4px 10px;">{!! $linha->icone . ' ' . $linha->nm_situacao  !!}</a></li> 
                                            @endif
                                        @endforeach 
                                    </ul>
                                </template>
                            </div>
                        </td>
                    
                    </tr>
                </template>
            </template>
        </tbody>
    </table>
    
    <template x-if="tablePesqConfir">
    <div class="row"> 
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-addon m-b-sm btn-rounded"><span class="glyphicon glyphicon-send" style="margin-right: 3px;" aria-hidden="true"></span> Enviar Mensagem</button>
        </div>
        <div class="col-md-offset-8 col-md-2">
            <button type="button" x-on:click="AtualizaRetorno();" class="btn btn-warning btn-addon m-b-sm btn-rounded"><span class="glyphicon glyphicon-refresh" style="margin-right: 3px;" aria-hidden="true"></span> Atualizar Retorno</button>
        </div>
    </div>
    </template>
</form>


<template x-if="!tablePesqConfir">
    <p class="text-center" style="padding: 1.7em"> 
        <img src="{{ asset('assets\images\calendario.png') }}"> <br>
        <span style="font-weight: bold; font-size: 1.2em; font-style: italic" > Confirmação de Agenda</span> 
    </p>
</template>