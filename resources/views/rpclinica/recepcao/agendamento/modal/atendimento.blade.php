


<style>
    #form-Agenda label {
        margin-bottom: 1px;
    }

    #form-Agenda .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
        padding-right: 5px;
        padding-left: 5px;
    }
</style> 
  
    <form x-on:submit.prevent="storeAgendamento" id=form-Agenda>
        @csrf  
        <input type="hidden" name="cd_agendamento"
            x-bind:value="dadosAtend.cd_agendamento" />
    
        <div class="row"> 
            <div class="col-md-4">
                <label>Profissional: <span class="red normal">*</span></label> 
                    <div class="form-group" >
                        <span class="form-control" style="border: 1px solid rgba(255, 255, 255, 0.1); background-color: #0f172a; color: #cbd5e1;" 
                        x-text="modalAtendimentos.profissional" > </span> 
                    </div>  
            </div>

            <div class="col-md-2">
                <label>Data <span class="red normal">*</span></label>
                <div class="form-group">
                    <input type="date" name="dt_agenda"
                        x-bind:value="modalAtendimentos.data" readonly
                        class="form-control" id="dt_agenda" />
                </div>
            </div>
 
            <div class="col-md-2">
                <label>Local de atendimento:
                <span  class="red normal">*</span></label> 
                <div class="form-group" >
                    <select class="form-control" style="width: 100%"
                        name="cd_local_atendimento" id="agendamento-local" required >
                        <option value="">Selecione</option>
                        <template x-if="modalData.local">
                            <template x-for="local in modalData.local">
                                <option x-bind:value="local.cd_local" x-text="local.nm_local"></option>
                            </template>
                        </template>
                    </select>
                </div>  
            </div>

            <div class="col-md-2">
                    <label>Tipo de Atendimento: <span class="red normal">*</span></label>
                    <div class="form-group" >
                        <select class="form-control" style="width: 100%"
                            name="tipo" id="agendamento-tipo" required>
                            <option value="">Selecione</option>
                            <template x-if="modalData.tipo">
                                <template x-for="tipo in modalData.tipo">
                                    <option x-bind:value="tipo.cd_tipo_atendimento" x-text="tipo.nm_tipo_atendimento"></option>
                                </template>
                            </template>
                        </select>
                    </div> 
            </div> 
            <div class="col-md-2">
                    <label>Situação: <span class="red normal">*</span></label>
                    <div class="form-group">
                        <span  
                         x-bind:style="'border: 1px solid rgba(255, 255, 255, 0.1); background-color: #0f172a; font-weight: bold; color:' + modalAtendimentos.situacao.color " 
                            x-html="modalAtendimentos.situacao.icone + '&nbsp;&nbsp; ' + modalAtendimentos.situacao.nm_situacao"
                            class="form-control">   </span>
                    </div>
            </div>

            <div class="col-md-3">
                <label>Especialidade: <span class="red normal">*</span></label> 
                <div class="form-group" >
                    <select class="form-control" required style="width: 100%"
                        name="cd_especialidade" id="agendamento-especialidade" >
                            <option value="">Selecione</option>
                            <template x-if="modalData.espec">
                                <template x-for="especialidade in modalData.espec">
                                    <option x-bind:value="especialidade.cd_especialidade" x-text="especialidade.nm_especialidade"></option>
                                </template>
                            </template>
                    </select>
                </div>  
            </div>

            <div class="col-md-3">
                <label>Caráter Atendimento: <span class="red normal">*</span></label> 
                <div class="form-group" >
                    <select class="form-control" required style="width: 100%"
                        name="carater" id="agendamento-carater" >
                            <option value="">Selecione</option>
                            <option value="eletivo">Eletivo</option>
                            <option value="urgencia">Urgência/Emergência</option>
                            
                    </select>
                </div>  
            </div>
  
            <div class="col-md-4">
                <label>Paciente: <span class="red normal">*</span>
                    <span class="red normal" style="font-style: italic; font-weight: 900;" id="dadosIdade" ></span>
                </label>
                <div class="form-group">
                    <span class="form-control" style="border: 1px solid rgba(255, 255, 255, 0.1); background-color: #0f172a; color: #cbd5e1;" 
                    x-text="modalAtendimentos.paciente" > </span> 
                </div>
            </div>
            <div class="col-md-2">
                <label>Data de Nascimento: <span class="red normal">*</span></label>
                <div class="form-group">
                    <input type="date" name="dt_nasc"
                        x-model="modalAtendimentos.dt_nasc"
                        class="form-control" id="data-de-nasc" required />
                </div>
            </div>
            <div class="col-md-2">
                <label>RG</label>
                <div class="form-group">
                    <input type="text" name="rg" id="rg"
                        x-model="modalAtendimentos.rg"
                        class="form-control"  />
                </div>
            </div>
            <div class="col-md-2">
                <label>CPF</label>
                <div class="form-group">
                    <input type="text" name="cpf"
                        x-model="modalAtendimentos.cpf"
                        class="form-control" id="cpf" />
                </div>
            </div>

            <div class="col-md-2">
                <label>Celular: <span class="red normal"></span></label>

                <div class="input-group m-b-sm">
                    <input  class="form-control" placeholder="Celular" x-model="modalAtendimentos.celular" name="celular"  
                    id="agendamento-celular"  />
                    <span class="input-group-addon" style=" padding: 2px 10px;" >
                        <i aria-hidden="true" id="Zap"
                            style="margin-right: 0px; font-size: 24px; padding: 4px px 8px; cursor: pointer;"
                            class="classWhast fa fa-whatsapp whastNeutro ">
                        </i>
                    </span>
                </div> 

            </div>

            <div class="col-md-3">
                <label>Email:</label>
                <input type="email" class="form-control" placeholder="Email"
                    name="email" id="agendamento-email"
                    x-model="modalAtendimentos.email" />
            </div>

            <div class="col-md-3">
                <label>Profissão:</label>
                <input type="text" class="form-control" placeholder="Profissao"
                    name="email" id="agendamento-profissao"
                    x-model="modalAtendimentos.profissao" />
            </div>
  
            <div class="col-md-12" style="padding-left: 0px;">
                <div class="col-md-7" style="padding-left: 0px;"> 

                    <div class="col-md-4">
                        <label>Origem do Paciente: 
                            <span class="red normal"> * 
                                <i class="fa fa-plus-circle" data-toggle="modal" data-target="#modalOrigem"  style="color: #22BAA0; cursor: pointer;"></i></span>    
                            </span></label> 
                        <div class="form-group" >
                            <select class="form-control" style="width: 100%" required
                                name="cd_origem" id="agendamento-origem" >
                                    <option value="">Selecione</option>
                                    <template x-if="modalData.origem">
                                        <template x-for="origem in modalData.origem">
                                            <option x-bind:value="origem.cd_origem" x-text="origem.nm_origem"></option>
                                        </template>
                                    </template>
                                     
                            </select>
                        </div>  
                    </div>

                    <div class="col-md-8">
                        <label>Médico Solicitante:  
                            <span class="red normal">
                                <i class="fa fa-plus-circle" data-toggle="modal" data-target="#modalProfExt"  style="color: #22BAA0; cursor: pointer;"></i></span>
                        </label> 
                        <div class="form-group" >
                            <select class="form-control" style="width: 100%"
                                name="cd_profisional_externo" id="agendamento-prof-ext" >
                                    <option value="">Selecione</option>
                                    <template x-if="modalData.prof_ext">
                                        <template x-for="prof in modalData.prof_ext">
                                            <option x-bind:value="prof.cd_profissional_externo" x-text="prof.nm_profissional_externo"></option>
                                        </template>
                                    </template>
                            </select>
                        </div>  
                    </div>
                    <div class="col-md-6">
                        <label>Convênio: <span class="red normal"></span></label> 
                        <div class="form-group" >
                            <select class="form-control" style="width: 100%" required
                                name="cd_especialidade" id="agendamento-convenio" >
                                    <option value="">Selecione</option>
                                    <template x-if="modalData.conv">
                                        <template x-for="conv in modalData.conv">
                                            <option 
                                                x-bind:value="conv.cd_convenio" 
                                                x-text="conv.nm_convenio"
                                                x-bind:data-link="conv.link_autorizacao"
                                                x-bind:data-user="conv.user_autorizacao"
                                                x-bind:data-senha="conv.senha_autorizacao"
                                                >
                                            </option>
                                        </template>
                                    </template>
                            </select>
                        </div>  
                    </div>
                    <div class="col-md-3">
                        <label>Cartão: <span class="red normal"></span></label> 
                        <div class="form-group" >
                            <input type="text" class="form-control"
                                x-model="modalAtendimentos.cartao"   />
                        </div>  
                    </div>
                    <div class="col-md-3">
                        <label>Validade: <span class="red normal"></span></label> 
                        <div class="form-group" >
                            <input type="date" class="form-control"
                             x-model="modalAtendimentos.dt_validade"    />
                        </div>  
                    </div>
                </div>
                <div class="col-md-5"> 
                    <label>Observação:</label>
                    <textarea  style="height: 100px;" class="form-control" 
                    name="obs" id="obs-agendamento"
                    x-model="modalAtendimentos.obs"  ></textarea> 
                </div>
            </div>
              
        </div>

        <div class="row">

            <div class="col-md-2"> 
                <template x-if="modalAtendimentos.situacao.cd_situacao != 'livre'" >
                    <div class="form-group" style="text-align: left;  width: 100%; ">
                        <div v class="btn-group m-b-sm" style="margin-top: 10px;">
                            <button type="button" class="btn btn-default dropdown-toggle"  
                                    x-bind:style="'font-weight: bold; color: #7a6fbe' "  
                                    data-toggle="dropdown" 
                                    aria-expanded="false"> <i class="fa fa-print" style="padding-left:2px; "></i> Impressão
                            </button>
                            
                            <ul class="dropdown-menu" role="menu">
 
                                <li  ><a target="_blank" x-bind:href="'/rpclinica/recepcao-ficha/' + modalAgenda.cd_agendamento" style="color:#22BAA0; font-weight: bold;padding: 4px 10px;"> <i class="fa fa-file-text-o" style="padding-left:2px;  "></i> Ficha do Atendimento</a></li>

                                <li ><a target="_blank" x-bind:href="'/rpclinica/recepcao-etiqueta/' + modalAgenda.cd_agendamento" style="color:#ea0033; font-weight: bold;padding: 4px 10px;">   <i class="fa fa-newspaper-o" tyle="padding-right: 10px;"></i>  Etiqueta</a></li>
 
                            </ul>
                            
                        </div>
                    </div> 
                </template>
            </div>

            <div class="col-md-4" style="text-align: center; ">
                <template x-if="modalAtendimentos.sn_atendimento == 'S'">
                    <code style=" text-align: center;   background-color: rgba(56, 13, 231, 0.1);    color: #60a5fa; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <strong>* Dados do Atendimento *</strong>  <br>
                        <strong>Usuario Agendamento: </strong> <span x-text="modalAtendimentos.user_agenda.nm_usuario + ' [ ' + modalAtendimentos.data_agendamento + ' ]'"></span>
                        <br>
                        <strong>Usuario Atendimento: </strong> <span x-text="modalAtendimentos.user_atend.nm_usuario + ' [ ' + modalAtendimentos.data_atendimento + ' ]'"></span>
                    </code>
                </template>
            </div>

            <div class="col-md-4" style="text-align: center; ">
                <template x-if="modalAtendimentos.link_convenio">
                    <code style=" text-align: center;   background-color: rgba(0, 155, 136, 0.1);    color: #4ade80; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <strong>* Acesso ao portal do Convênio *</strong><br>
                       <a href="" target="_blank" x-text="modalAtendimentos.link_convenio" style="color: #4ade80;"></a> <br>
                        <strong>Usuario: </strong> <span x-text="modalAtendimentos.usuario_convenio"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Senha: </strong> <span x-text="modalAtendimentos.senha_convenio"></span>
                    </code>
                </template>

            </div>
            
            <div class="col-md-2">
                 
                    <div class="form-group"
                        style="text-align: right; margin-top: 10px;">
                        <button type="submit" class="btn btn-success" 
                        x-bind:disabled="buttonDisabled"
                        x-html="buttonGerarAtend"> </button>
                    </div>
                
            </div>
        </div>

 

    </form>
 
