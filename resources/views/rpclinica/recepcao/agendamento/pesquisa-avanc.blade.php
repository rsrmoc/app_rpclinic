<form x-on:submit.prevent="pesquisaAvancada" id="form-pesquisa-avanc"   style="">
    <input type="hidden" name="tipo" value="avanc">
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label>Data Inicial: <span class="red normal">*</span></label>
                <input type="date" class="form-control required" value="{{ old('dti') }}"
                    name="dti" maxlength="100" aria-required="true" required>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Data Final: <span class="red normal">*</span></label>
                <input type="date" class="form-control required" value="{{ old('dtf') }}"
                    name="dtf" maxlength="100" aria-required="true" required>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>Agenda: <span class="red normal">*</span></label>
                <select class="form-control" multiple="multiple" required tabindex="-1" style="width: 100%"  name="agenda[]"  >
                    <option value="">Selecione</option>
                    @foreach ($agendas as $linha)
                        <option value="{{ $linha->cd_agenda }}">{{ $linha->nm_agenda }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>Especialidade: <span class="red normal"></span></label>
                <select class="form-control" style="width: 100%"
                name="especialidade"  >
                    <option value="">Selecione</option>
                    @foreach ($especialidades as $linha)
                        <option value="{{ $linha->cd_especialidade }}">{{ $linha->nm_especialidade }}</option>
                    @endforeach
               
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>Profissional: <span class="red normal"></span></label>
                <select class="form-control" style="width: 100%"
                name="profissional"  >
                    <option value="">Selecione</option>
                    @foreach ($profissionais as $linha)
                        <option value="{{ $linha->cd_profissional }}">{{ $linha->nm_profissional }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Local de Atendimento: <span class="red normal"></span></label>
                <select class="form-control" style="width: 100%" name="local" >
                    <option value="">Selecione</option>
                    @foreach ($localAtendimentos as $linha)
                        <option value="{{ $linha->cd_local }}">{{ $linha->nm_local }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Exame: <span class="red normal"></span></label>
                <select class="form-control" multiple="multiple" tabindex="-1" style="width: 100%"  name="exame[]"  >
                    <option value="">Selecione</option>
                    @foreach ($exames as $linha)
                        <option value="{{ $linha->cd_exame }}">{{ $linha->nm_exame }}</option>
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
<template x-if="tablePesqAvanc">
    <template x-for="(linhaData, indexaData) in tablePesqAvanc">
        <template x-if="linhaData.agendas">
        
            <table class="table">  
                <thead>
                    <tr class="active">
                        <th class="text-center" style=" font-size: 1.3em; font-style: italic; font-weight: 500; " x-html="linhaData.titulo"> </th> 
                    </tr>
                </thead>  
                <tbody>
                    <tr>
                        <td>
                            <div class="row">
                            <template x-for="(linhaAgenda, indexAgenda) in linhaData.agendas">
                                <div  class="col-md-6 ">
                                    <template x-if="linhaAgenda.horarios">
                                    
                                        <div class="panel glass-panel"   style="margin-top: 1.5em; margin-bottom: 0; border: 1px solid rgba(255,255,255,0.1) !important">
                                            <div class="panel-heading" style="padding: 10px; height: auto; border-bottom: 1px solid rgba(255,255,255,0.1); color: #cbd5e1;">
                                                <h3 class="panel-title" > 
                                                    <span  style=" font-size: 1em; font-style: italic; font-weight: 500; " x-html="linhaAgenda.titulo_agenda"></span> 
                                                </h3>
                                            </div> 
                                            <div class="panel-body" style="padding-top: 1.5em">
                                                <div class="row">
                                                    <template x-for="(linhaHorarios, indexHorario) in linhaAgenda.horarios"> 
                                                        <div class="col-xs-4 col-sm-2 col-md-2 linhaHorarios" style="margin-bottom: 5px;">
                                                            <button type="button" class="btn btn-success btn-rounded" x-on:click="Modal(linhaHorarios)" x-text="linhaHorarios.hr_start"></button>  
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                    </template>

                                </div> 
                            </template> 
                            </div>
                        </td>
                    </tr> 
                </tbody> 
            </table> 
            
        </template>
    </template>  
</template>

<template x-if="!tablePesqAvanc">
    <p class="text-center" style="padding: 1.7em"> 
        <img src="{{ asset('assets\images\calendario.png') }}"> <br>
        <span style="font-weight: bold; font-size: 1.2em; font-style: italic" > Pesquisar Agenda Livre</span> 
    </p>
</template>