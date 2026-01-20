

<div x-show="loadingModal"> 
    <p class="text-center">
        <br><br> <img style="margin-top: 50px; height: 80px;" src="{{ asset('assets\images\carregandoFormulario.gif') }}"><br><br><br>
    </p>
</div>
<div x-show="loadingModal==false" >
<div class="container-fluid" style="padding-left: 20px; padding-right: 20px;">
 <form  x-on:submit.prevent="storeAgendamento" id=form-Agenda>
    @csrf
    <input type="hidden" class="form-control" id="data-agendamento"
        name="data_horario" x-bind:value="data_agenda+' '+modalAgenda.cd_horario" />

    <input type="hidden" name="cd_agendamento"
        x-bind:value="modalAgenda.agendamento?.cd_agendamento" />

    <input type="hidden" name="cd_escala"
        x-bind:value="modalAgenda.cd_escala_agenda" />
 
    <input type="hidden" name="cd_dia"
        x-bind:value="modalAgenda.nr_dia" />
  
    <input type="hidden" name="intervalo"
        x-bind:value="modalAgenda.intervalo" />
        
    <input type="hidden" name="nm_intervalo"
        x-bind:value="modalAgenda.nm_intervalo" />

    <input type="hidden" name="cd_horario"
        x-bind:value="modalAgenda.cd_agenda_escala_horario" />

    <div class="row">

        <div class="col-md-4"> 
            <label>Agenda: <span class="red normal">*</span>
                <template x-if="modalAgenda.agendamento?.cd_agendamento">
                    <span class="label label-info" style="background: #30daca;border: 1px solid #009688;"
                    x-html="'&nbsp;&nbsp;Agendamento : &nbsp; '+modalAgenda.agendamento?.cd_agendamento+'&nbsp;&nbsp;'"></span>
                </template>
            </label>
            <div class="form-group"  > 
                <select class="form-control"  style="width: 100%"
                        name="cd_agenda" id="cod_agenda"  required>  
                        <option value="">Selecione </option>
                        <option x-bind:value="modalAgenda.agenda?.cd_agenda" 
                                x-text="modalAgenda.agenda?.nm_agenda">
                        </option>
                </select>
            </div>
            
        </div>

        <div class="col-md-4">
            <label>Profissional: <span class="red normal">*</span></label> 
                <div class="form-group" >
                    <select class="form-control" style="width: 100%"
                        name="cd_profissional" id="agendamento-profissional"
                        required>
                        <option value="">Selecione</option> 
                        <template x-if="camposModal.profissional">
                            <template x-for="profissional in camposModal.profissional">
                                <option x-bind:value="profissional.cd_profissional" x-text="profissional.nm_profissional"></option>
                            </template>
                        </template>
                        
                    </select>
                </div> 
 
        </div>

        <div class="col-md-2">
            <label>Data <span class="red normal">*</span></label>
            <div class="form-group">
                <input type="date" name="dt_agenda"
                    x-bind:value="data_agenda" readonly
                    class="form-control" id="dt_agenda" />
            </div>
        </div>

        <div class="col-md-1">
            <label>Inicio <span class="red normal">*</span></label>
            <div class="form-group">
                <input type="text" name="hr_inicio"  x-mask="99:99"
                    x-model="modalAgenda.cd_horario" readonly 
                    class="form-control center" id="hr_inicio" />
            </div>
        </div>

        <div class="col-md-1">
            <label>Término <span class="red normal">*</span></label>
            <div class="form-group">
                <input type="text" name="hr_fim" x-mask="99:99"
                    x-model="horaFinal" readonly 
                    class="form-control center" id="hr_fim" />
            </div>
        </div>
    </div> <!-- End Row 1 -->

    <div class="row"> <!-- Start Row 2 -->
        <div class="col-md-4">
            <label>Local de atendimento:
            <span  class="red normal">*</span></label> 
            <div class="form-group" >
                <select class="form-control" style="width: 100%"
                    name="cd_local_atendimento" id="agendamento-local" >
                    <option value="">Selecione</option>
                    <template x-if="camposModal.local">
                        <template x-for="local in camposModal.local">
                            <option x-bind:value="local.cd_local" x-text="local.nm_local"></option>
                        </template>
                    </template>
                </select>
            </div>  
        </div>

        <div class="col-md-4">
                <label>Tipo de Atendimento: <span class="red normal">*</span></label>
                <div class="form-group" >
                    <select class="form-control" style="width: 100%"
                        name="tipo" id="agendamento-tipo" required>
                        <option value="">Selecione</option>
                        <template x-if="camposModal.tipo_atendimento">
                            <template x-for="tipo in camposModal.tipo_atendimento">
                                <option x-bind:value="tipo.cd_tipo_atendimento" x-text="tipo.nm_tipo_atendimento"></option>
                            </template>
                        </template>
                    </select>
                </div> 
        </div>

        <div class="col-md-4">
            <label>Especialidade: <span class="red normal">*</span></label> 
            <div class="form-group" >
                <select class="form-control" style="width: 100%"
                    name="cd_especialidade" id="agendamento-especialidade" >
                        <option value="">Selecione</option>
                        <template x-if="camposModal.especialidade">
                            <template x-for="especialidade in camposModal.especialidade">
                                <option x-bind:value="especialidade.cd_especialidade" x-text="especialidade.nm_especialidade"></option>
                            </template>
                        </template>
                </select>
            </div>  
        </div>

    </div>

    <div class="row">

        <div class="col-md-4">
            <label>Paciente <span class="red normal">*</span>
                <span class="red normal" style="font-style: italic; font-weight: 900;" id="dadosIdade" ></span>
            </label>
            <div class="form-group">
                <select class="form-control m-b-sm" style="width: 100%"
                    id="agendamento-paciente" name="cd_paciente" required>
                        <option value="">Selecione</option>
                </select>
  
            </div>
        </div>
        <div class="col-md-2">
            <label>Data de Nascimento <span class="red normal">*</span>  </label>
            <div class="form-group">
                <input type="date" name="dt_nasc"
                    x-model="modalAgenda.paciente?.dt_nasc"
                    class="form-control" id="data-de-nasc" required />
            </div>
        </div>
        <div class="col-md-2">
            <label>Profissão <span class="red normal"></span>  </label>
            <div class="form-group">
                <input type="text" name="profissao" 
                x-model="modalAgenda.paciente?.profissao"
                class="form-control" id="ds_profissao" />
            </div>
        </div>
        <div class="col-md-2">
            <label>RG</label>
            <div class="form-group">
                <input type="text" name="rg" id="rg"
                    x-model="modalAgenda.paciente?.rg"
                    class="form-control"  />
            </div>
        </div>
        <div class="col-md-2">
            <label>CPF</label>
            <div class="form-group">
                <input type="text" name="cpf"
                    x-model="modalAgenda.paciente?.cpf"
                    class="form-control" id="cpf" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">

            <div class="row">
                <div class="col-md-4">
                    <label>Celular: <span class="red normal"></span></label>

                    <div class="input-group m-b-sm">
                        <input  class="form-control" placeholder="Celular" 
                          x-model="modalAgenda.paciente?.celular" name="celular"  
                          id="agendamento-celular"  />
                        <span class="input-group-addon" style=" padding: 2px 10px;" >
                            <i aria-hidden="true" id="Zap"
                                style="margin-right: 0px; font-size: 24px; padding: 4px px 8px; cursor: pointer;"
                                class="classWhast fa fa-whatsapp whastNeutro ">
                            </i>
                        </span>
                    </div>
 

                </div>

                <div class="col-md-8">
                    <label>Email:</label>
                    <input type="email" class="form-control" placeholder="Email"
                        name="email" id="agendamento-email"
                        x-model="modalAgenda.paciente?.email" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Convênio: <span class="red normal">*</span></label>

                        <select class="form-control" style="width: 100%"
                            name="cd_convenio" id="agendamento-convenio" required>
                            <option value="">Selecione</option> 
                            <template x-if="camposModal.convenio">
                                <template x-for="convenio in camposModal.convenio">
                                    <option x-bind:value="convenio.cd_convenio" x-text="convenio.nm_convenio"></option>
                                </template>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <label>Cartão:</label>
                    <div class="form-group">
                        <input type="text" class="form-control"
                            id="agendamento-cartao" 
                            x-model="modalAgenda.cartao" name="cartao" />
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Validade:</label>
                    <div class="form-group">
                        <input type="date" class="form-control"
                            id="cartao-validade" 
                            x-model="modalAgenda.dt_validade"  
                            name="validade" />
                    </div>
                </div>
            </div>
 
        </div>

        <div class="col-md-5">
            <div class="row">
                <div class="col-md-12">
                    <label>Observação</label>
                    <textarea rows="5" class="form-control" name="obs" id="obs-agendamento" 
                    x-model="modalAgenda.obs"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="row"  >
        <div class="col-md-12">
            <div class="form-group">
                <label>Itens do Atendimento: <span class="red normal">*</span></label>
                <select class="form-control" tabindex="-1"  multiple="multiple" style="width: 100%" 
                required name="item_agendamento[]" id="item_agendamento" >
                    <option value="">Selecione</option>

                    <template x-if="camposModal.itens">
                        <template x-for="item in camposModal.itens">
                            <option :value="item.exames.cd_exame"    x-text="item.exames.nm_exame" ></option>
                        </template>
                    </template>

                </select>
            </div>
        </div>
    </div>

    <div class="row">

        <!--
        <div class="col-md-2"> 
            <template x-if="modalAgenda.situacao != 'livre'">
                <div class="form-group" style="text-align: left; margin-top: 20px; width: 100%">
                    <div v class="btn-group m-b-sm">
                        <button type="button" class="btn btn-default dropdown-toggle" id="situacaoButton" data-toggle="dropdown" x-html="buttonAgendamento(modalAgenda.situacao)" aria-expanded="false"> 

                        </button>
                        
                        <ul class="dropdown-menu" role="menu">

                            <template x-for="situacao in modalData.situacoes" >
                                <li x-on:click="atualizaStatus(situacao.cd_situacao)"  >
                                    <a  href="#" x-bind:style="'font-weight: bold; padding: 4px 10px; color:' + situacao.color">
                                        <span x-html="situacao.icone"> </span>
                                        <span x-html="situacao.nm_situacao"> </span> 
                                    </a>
                                </li>
                            </template>

                            
                        </ul>
                    </div>
                </div> 
            </template>
        </div>
        -->

        <div class="col-md-10">
            <template x-if="modalAgenda.dt_presenca">
                <code x-html="modalAgenda.retorno_presenca"></code>
            </template>
        </div>
 
        <div class="col-md-2">
            <div class="form-group"
                style="text-align: right; margin-top: 10px;">
                <button type="submit" class="btn btn-success" x-html="buttonSalvarAgendamneto">
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <template x-if="modalAgenda.dt_presenca">
                <code x-html="modalAgenda.retorno_presenca"></code>
            </template>
        </div>
    </div>

  </form>
</div>
</div>
