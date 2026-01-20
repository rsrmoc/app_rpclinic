
<div class="container-fluid" style="padding: 20px;">
    <form x-on:submit.prevent="storeAgendamento" id=form-Agenda>
        @csrf
        <input type="hidden" class="form-control" id="data-agendamento"
            name="data_horario" x-bind:value="modalAgenda.data_horario" />

        <input type="hidden" name="cd_agendamento"
            x-bind:value="modalAgenda.cd_agendamento" />

        <input type="hidden" name="cd_escala"
            x-bind:value="modalAgenda.cd_escala" />
    
        <input type="hidden" name="cd_dia"
            x-bind:value="modalAgenda.cd_dia" />
    
            
        <input type="hidden" name="intervalo"
            x-bind:value="modalAgenda.intervalo" />
            
        <input type="hidden" name="nm_intervalo"
            x-bind:value="modalAgenda.nm_intervalo" />

        <input type="hidden" name="cd_horario"
            x-bind:value="modalAgenda.cd_horario" />
        <div class="row">

            <div class="col-md-4">
                <label>Agenda: <span class="red normal">*</span>
                    <template x-if="modalAgenda.cd_agendamento">
                        <span class="label label-info" style="background: #30daca;border: 1px solid #009688;"
                        x-html="'&nbsp;&nbsp;Agendamento : &nbsp; '+modalAgenda.cd_agendamento+'&nbsp;&nbsp;'"></span>
                    </template>
                </label>
                <div class="form-group"  >
                    
                    <select class="form-control"  style="width: 100%"
                        name="cd_agenda" id="cod_agenda" required> 
                        <template x-if="modalAgenda.cd_agenda">
                            <option x-bind:value="modalAgenda.cd_agenda" x-text="modalAgenda.nm_agenda"></option>
                        </template>
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
                            <template x-if="modalData.prof">
                                <template x-for="profissional in modalData.prof">
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
                        x-bind:value="modalAgenda.dt_agenda" readonly
                        class="form-control" id="dt_agenda" />
                </div>
            </div>
        
            <div class="col-md-1">
                <label>Inicio <span class="red normal">*</span></label>
                <div class="form-group">
                    <input type="text" name="hr_inicio"  x-mask="99:99"
                        x-bind:value="modalAgenda.hr_inicio" @if($request['edita_horario']<>'sim') readonly @endif  
                        class="form-control center" id="hr_inicio" />
                </div>
            </div>

            <div class="col-md-1">
                <label>Término <span class="red normal">*</span></label>
                <div class="form-group">
                    <input type="text" name="hr_fim" x-mask="99:99"
                        x-bind:value="modalAgenda.hr_fim" @if($request['edita_horario']<>'sim') readonly @endif 
                        class="form-control center" id="hr_fim" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label>Local de atendimento: <span class="red normal">*</span></label>
                <div class="form-group">
                    <select class="form-control" style="width: 100%" name="cd_local_atendimento"
                        id="agendamento-local">
                        <option value="">Selecione</option>
                        <template x-if="modalData.local">
                            <template x-for="local in modalData.local">
                                <option x-bind:value="local.cd_local" x-text="local.nm_local"></option>
                            </template>
                        </template>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <label>Tipo de Atendimento: <span class="red normal">*</span></label>
                <div class="form-group">
                    <select class="form-control" style="width: 100%" name="tipo"
                        id="agendamento-tipo" required>
                        <option value="">Selecione</option>
                        <template x-if="modalData.tipo">
                            <template x-for="tipo in modalData.tipo">
                                <option x-bind:value="tipo.cd_tipo_atendimento"
                                    x-text="tipo.nm_tipo_atendimento"></option>
                            </template>
                        </template>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <label>Especialidade: <span class="red normal">*</span></label>
                <div class="form-group">
                    <select class="form-control" style="width: 100%" name="cd_especialidade"
                        id="agendamento-especialidade">
                        <option value="">Selecione</option>
                        <template x-if="modalData.espec">
                            <template x-for="especialidade in modalData.espec">
                                <option x-bind:value="especialidade.cd_especialidade"
                                    x-text="especialidade.nm_especialidade"></option>
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
                            <option value="">Pesquise pelo Nome ou CPF </option>
                    </select>
    
                </div>
            </div>

            <div class="col-md-8">
    
                <div class="col-md-2" style="padding-left: 0px;">
                    <label>Sexo <span class="red normal">*</span>  </label>
                    <div class="form-group">
                        <select class="form-control m-b-sm" style="width: 100%" name="sexo" id="sexo_pac" required>
                            <option value="">... </option>
                            <option value="H" >Masculino</option>
                            <option value="M" >Feminino</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <label>Data de Nascimento <span class="red normal">*</span>  </label>
                    <div class="form-group">
                        <input type="date" name="dt_nasc"
                            x-model="modalAgenda.dt_nasc"
                            class="form-control" id="data-de-nasc" required />
                    </div>
                </div>
                <div class="col-md-2">
                    <label>CPF @if($request['obriga_cpf']=='sim') <span  class="red normal">*</span>  @endif</label>
                    <div class="form-group">
                        <input type="text" name="cpf"
                            x-model="modalAgenda.cpf"
                            @if($request['obriga_cpf']=='sim') required @endif
                            class="form-control" id="cpf" />
                    </div>
                </div>
                <div class="col-md-2">
                    <label>RG</label>
                    <div class="form-group">
                        <input type="text" name="rg" id="rg"
                            x-model="modalAgenda.rg"
                            class="form-control"  />
                    </div>
                </div>
                <div class="col-md-3" style="padding-right: 0px;">
                    <label>Profissão <span class="red normal"></span>  </label>
                    <div class="form-group">
                        <input type="text" name="profissao" 
                        x-model="modalAgenda.profissao"
                        class="form-control" id="ds_profissao" />
                    </div>
                </div>

            </div>



        </div>
    
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-6" style="padding-left: 0px;">
                    <label>Nome da Mãe: <span  class="red normal">*</span></label>
                    <div class="form-group">
                        <input type="text" class="form-control"  
                            x-model="modalAgenda.nm_mae"
                            name="nm_mae" id="nm_mae_pac"    />
                    </div>
                </div>
                <div class="col-md-3"  >
                    <label>Dt.Nasc. Mãe: </label>
                    <input type="date" class="form-control"  
                        x-model="modalAgenda.dt_nasc_mae"
                        name="dt_nasc_mae" id="dt_nasc_mae_pac"    />
                </div>
                <div class="col-md-3" style="padding-right: 0px;">
                    <label>Celular Mãe:</label>
                    <input type="text" class="form-control"  
                        x-model="modalAgenda.celular_mae"
                        x-mask="(99) 99999-9999"
                        name="celular_mae" id="celular_mae_pac"      />
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-6" style="padding-left: 0px;">
                    <label>Nome do Pai:</label>
                    <div class="form-group">
                        <input type="text" class="form-control" 
                                x-model="modalAgenda.nm_pai"
                            name="nm_pai" id="nm_pai_pac"    />
                    </div>
                </div>
                <div class="col-md-3"  >
                    <label>Dt.Nasc. Pai:</label>
                    <input type="date" class="form-control" 
                        x-model="modalAgenda.dt_nasc_pai"
                        name="dt_nasc_pai" id="dt_nasc_pai_pac"  />
                </div>
                <div class="col-md-3" style="padding-right: 0px;">
                    <label>Celular Pai:</label>
                    <input type="text" class="form-control"  
                        x-model="modalAgenda.celular_pai"
                        x-mask="(99) 99999-9999"
                        name="celular_pai" id="celular_pai_pac"    />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">

                <div class="row">
                    <div class="col-md-4">
                        <label>Celular: <span class="red normal"></span></label>

                        <div class="input-group m-b-sm">
                            <input  class="form-control" placeholder="Celular" x-mask="(99) 99999-9999" x-model="modalAgenda.celular" name="celular"  
                            id="agendamento-celular"  />
                            <span class="input-group-addon" style=" padding: 2px 10px;" x-on:click="validarZap">
                                <i aria-hidden="true" id="Zap"
                                    style="margin-right: 0px; font-size: 24px; padding: 4px px 8px; cursor: pointer;"
                                    x-bind:class="classWhast"></i>
                            </span>
                        </div>  
                        <input type="hidden" name="SituacaoWhast" x-model="SituacaoWhast" value="">
                        <input type="hidden" name="foneWhast" x-model="foneWhast" value="">
                    </div>
    
    
                    <div class="col-md-8">
                        <label>Email:</label>
                        <input type="email" class="form-control" placeholder="Email"
                            name="email" id="agendamento-email"
                            x-model="modalAgenda.email" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Convênio: <span class="red normal">*</span></label>

                            <select class="form-control" style="width: 100%"
                                name="cd_convenio" id="agendamento-convenio" required>
                                <option value="">Selecione</option> 
                                <template x-if="modalData.conv">
                                    <template x-for="convenio in modalData.conv">
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
                                id="agendamento-cartao" x-model="modalAgenda.cartao" name="cartao" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Validade:</label>
                        <div class="form-group">
                            <input type="date" class="form-control"
                                id="cartao-validade" x-model="modalAgenda.validade"  name="validade" />
                        </div>
                    </div>
                </div>
    
            </div>

            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-12">
                        <label>Observação</label>
                        <textarea rows="5" class="form-control" name="obs" id="obs-agendamento" 
                        x-model="modalAgenda.observacao"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row"  >
            <div class="col-md-12">
                <div class="form-group">
                    <label>Itens do Atendimento: <span class="red normal"> @if($empresa->sn_item_agendamento == 'S' ) * @endif </span></label>
                    <select class="form-control" tabindex="-1"  multiple="multiple" style="width: 100%" @if($empresa->sn_item_agendamento == 'S' ) required @endif  name="item_agendamento[]" id="item_agendamento" >
                    
                        <template x-if="modalAgenda.itensAgendamento">
                            <template x-for="item in modalAgenda.itensAgendamento">
                                <option :value="item.cd_exame"    x-text="item.nm_exame" ></option>
                            </template>
                        </template>

                    </select>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-2">  
                <template x-if="modalAgenda.situacao != 'livre'">
                    <div class="form-group" style="text-align: left; margin-top: 20px; width: 100%;margin-bottom: 0px;">
                        <div v class="btn-group m-b-sm">
                            <button type="button" class="btn btn-default dropdown-toggle" id="situacaoButton" data-toggle="dropdown" x-html="buttonAgendamento(modalAgenda.situacao)" aria-expanded="false"> 
    
                            </button>
                                <template x-if="( ( (modalAgenda.sn_finalizado) ? modalAgenda.sn_finalizado : 'N' ) == 'N' )"> 
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
                                </template>
    
                        </div>
                    </div> 
                </template>
            </div>

            <div class="col-md-8">
            
            </div>
            <div class="col-md-2">
                <div class="form-group"
                    style="text-align: right; margin-top: 10px;margin-bottom: 0px;">
                    <button type="submit" class="btn btn-success" x-html="buttonSalvarAgendamneto">
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <template x-if="dataAtendimento">
                    <span>
                        <code x-html="iconCalendar+ ' ' + dataAtendimento"></code><br>
                        <code x-html="iconUser+ ' ' + userAtendimento "></code>
                    </span>
                </template>
            </div>
        </div>

    </form>
</div>
