<div class="modal fade" id="cadastro-agendamento" style="z-index: 10050; overflow-x: hidden; overflow-y: auto;">

    <div class="modal-dialog modal-lg" style="width: 95%; max-width: 1400px; z-index: 10060; margin: 30px auto; pointer-events: auto;">

        <div class="modal-content">

            <div class="absolute-loading" style="display: none">
                <div class="line">
                    <div class="loading"></div>
                    <span style="font-weight: bold; font-size: 1.3em; font-style: italic" x-html="loadingAcao"></span>
                </div>
            </div>

            <div class="modal-header m-b-sm">

                <div class="line" style="justify-content: space-between">
                    <h4 class="modal-title" x-html="(modalAgenda) ? modalAgenda.agenda?.nm_agenda + ' &nbsp;  {  ' + modalAgenda.hr_agenda +'  }' : 'Cadastrar Agendamento'"> </h4>

                    <div class="line">


                        <button type="button" x-show="snExcluir" class="btn btn-default btn-rounded" 
                        x-on:click="ExcluirAgenda" style="color: red">
                            <i class="fa fa-trash"></i> Excluir
                        </button>
   

                        <button type="button" class="close m-l-sm" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="padding-top: 20px;"><span aria-hidden="true"
                                    class="icon-close"></span></span>
                        </button>

                    </div>

                </div>

            </div>

            <div class="modal-body">

                <template x-if="modalData.errors.length > 0">
                    <div class="alert alert-danger">
                        <h5>Houve alguns erros:</h5>
                        <ul>
                            <template x-for="error in modalData.errors">
                                <li x-html="error"></li>
                            </template>
                        </ul>
                    </div>
                </template>

                <div class="tabpanel">
 
                        <ul class="nav nav-tabs nav-justified" role="tablist" x-show="snExcluir">
                            <li role="presentation" class="active">
                                <a href="#TabSobre" role="tab" data-toggle="tab"
                                    style="padding: 3px 15px; border-left: 0px;"><span aria-hidden="true"
                                        class="icon-calendar" style="margin-right: 10px;"></span> Sobre a
                                    agenda</a>
                            </li>

                            <li role="presentation" class="">
                                <a href="#TabPaciente" role="tab" data-toggle="tab" style="padding: 3px 15px;">
                                    <span aria-hidden="true" class="icon-user" style="margin-right: 10px;"></span>Cadastro
                                    de Paciente
                                </a>
                            </li>
                        </ul> 

                    <div class="tab-content">

                        <div class="tab-pane active" role="tabpanel" id="TabSobre">
 
                            <div x-show="loadingModal">
                                <p class="text-center">
                                    <br><br> <img style="margin-top: 50px; height: 80px;"
                                        src="{{ asset('assets\images\carregandoFormulario.gif') }}"><br><br><br>
                                </p>
                            </div>
                            <div x-show="loadingModal==false">
                            <div class="container-fluid" style="padding-left: 20px; padding-right: 20px;">
                                <form x-on:submit.prevent="storeAgendamento" id=form-Agenda>
                                    @csrf 

                                    <input type="hidden" name="cd_agendamento"
                                        x-bind:value="modalAgenda.cd_agendamento" />
  
                                    <div class="row">

                                        <div class="col-md-4">
                                            <label>Agenda: <span class="red normal">*</span> <span x-html="loadCarregandoAgenda"></span>
                                                <template x-if="modalAgenda.cd_agendamento">
                                                    <span class="label label-info"
                                                        style="background: #30daca;border: 1px solid #009688;"
                                                        x-html="'&nbsp;&nbsp;Agendamento : &nbsp; '+modalAgenda.cd_agendamento+'&nbsp;&nbsp;'"></span>
                                                </template>
                                            </label>
                                            <div class="form-group">
                                                <select class="form-control" style="width: 100%" name="cd_agenda"
                                                    id="cod_agenda" required>
                                                    <option value="">Selecione </option>
                                                    <template x-if="dadosAgenda">
                                                        <template x-for="item in dadosAgenda">
                                                            <option :value="item.cd_agenda"
                                                                x-text="item.nm_agenda"></option>
                                                        </template>
                                                    </template>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="col-md-4">
                                            <label>Profissional: <span class="red normal">*</span></label>
                                            <div class="form-group">
                                                <select class="form-control" style="width: 100%" name="cd_profissional"
                                                    id="agendamento-profissional" required>
                                                    <option value="">Selecione</option>
                                                    <template x-if="camposModal.profissional">
                                                        <template x-for="profissional in camposModal.profissional">
                                                            <option x-bind:value="profissional.cd_profissional"
                                                                x-text="profissional.nm_profissional"></option>
                                                        </template>
                                                    </template>

                                                </select>
                                            </div>

                                        </div>

                                        <div class="col-md-2">
                                            <label>Data <span class="red normal">*</span></label>
                                            <div class="form-group">
                                                <input type="date" name="dt_agenda" x-bind:value="modalAgenda.dt_agenda"
                                                class="form-control" id="dt_agenda" />
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <label>Inicio <span class="red normal">*</span></label>
                                            <div class="form-group">
                                                <input type="text" name="hr_inicio" x-mask="99:99"
                                                    x-model="modalAgenda.hr_agenda"  
                                                    class="form-control center" id="hr_inicio" />
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <label>Término <span class="red normal">*</span></label>
                                            <div class="form-group">
                                                <input type="text" name="hr_fim" x-mask="99:99"
                                                    x-model="modalAgenda.hr_final"   class="form-control center"
                                                    id="hr_fim" />
                                            </div>
                                        </div>
                                    </div> <!-- End Row 1 -->

                                    <div class="row"> <!-- Start Row 2 -->
                                        <div class="col-md-4">
                                            <label>Local de atendimento:
                                                <span class="red normal">*</span></label>
                                            <div class="form-group">
                                                <select class="form-control" style="width: 100%"
                                                    name="cd_local_atendimento" id="agendamento-local">
                                                    <option value="">Selecione</option>
                                                    <template x-if="camposModal.local">
                                                        <template x-for="local in camposModal.local">
                                                            <option x-bind:value="local.cd_local"
                                                                x-text="local.nm_local"></option>
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
                                                    <template x-if="camposModal.tipo_atendimento">
                                                        <template x-for="tipo in camposModal.tipo_atendimento">
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
                                                <select class="form-control" style="width: 100%"
                                                    name="cd_especialidade" id="agendamento-especialidade">
                                                    <option value="">Selecione</option>
                                                    <template x-if="camposModal.especialidade">
                                                        <template x-for="especialidade in camposModal.especialidade">
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
                                                <span class="red normal" style="font-style: italic; font-weight: 900;"
                                                    id="dadosIdade"></span>
                                            </label>
                                            <div class="form-group">
                                                <select class="form-control m-b-sm" style="width: 100%"
                                                    id="agendamento-paciente" name="cd_paciente" required>
                                                    <option value="">Selecione</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Data de Nascimento <span class="red normal">*</span> </label>
                                            <div class="form-group">
                                                <input type="date" name="dt_nasc"
                                                    x-model=" modalAgenda.paciente?.dt_nasc "
                                                    class="form-control" id="data-de-nasc" required />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Profissão <span class="red normal"></span> </label>
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
                                                    class="form-control" />
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
                                                        <input class="form-control" placeholder="Celular"
                                                            x-model="modalAgenda.paciente?.celular"
                                                            name="celular" id="agendamento-celular" />
                                                        <span class="input-group-addon" style=" padding: 2px 10px;" x-on:click="validarZap">
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
                                                            <template x-if="dadosConvenios">
                                                                <template x-for="convenio in dadosConvenios">
                                                                    <option x-bind:value="convenio.cd_convenio" 
                                                                        x-text="convenio.nm_convenio"></option>
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
                                                            x-model="modalAgenda.cartao"
                                                            name="cartao" />
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

                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label>Itens do Atendimento: <span class="red normal"> </span></label>
                                                <select class="form-control" tabindex="-1" multiple="multiple"
                                                    style="width: 100%"   name="item_agendamento[]"
                                                    id="item_agendamento">
                                                    <option value="">Selecione</option>

                                                    <template x-if="camposModal.itens">
                                                        <template x-for="item in camposModal.itens">
                                                            <option :value="item.exames.cd_exame"
                                                                x-text="item.exames.nm_exame"></option>
                                                        </template>
                                                    </template>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2"> 
                                            <label>Valor:</label> 
                                            <div class="form-group" style="    margin-bottom: 5px;">
                                                <input x-mask:dynamic="$money($input, ',')" x-bind:value="modalAgenda.valor_recebido" class="form-control" 
                                                       placeholder="Valor" name="valor" id="agendamento-valor">
                                            </div> 
                                            <label class="m-r-sm">
                                                <div class="checker">
                                                    <span id="check-agend-receb">
                                                        <input type="checkbox" name="recebido" value="1" />
                                                    </span>
                                                </div> Valor Recebido
                                            </label> 
                                        </div>
                                    </div>

                                    <div class="row">
 
                                        <div class="col-md-2">  
                                            <template x-if="modalAgenda.situacao != 'livre'">
                                                <div class="form-group" style="text-align: left; margin-top: 20px; width: 100%;margin-bottom: 0px;">
                                                    <div  class="btn-group m-b-sm">
                                                        <button type="button"   id="situacaoButton" data-toggle="dropdown" 
                                                        x-bind:class="'btn btn-default dropdown-toggle' + modalAgenda.tab_situacao?.class"
                                                        x-bind:style="' font-weight: bold; color:' + modalAgenda.tab_situacao?.color"
                                                        x-html="modalAgenda.tab_situacao?.icone +' ' + modalAgenda.tab_situacao?.nm_situacao" aria-expanded="false"> 
                                
                                                        </button>
                                                            <template x-if="( ( (modalAgenda.tab_situacao.finalizado) ? modalAgenda.tab_situacao.finalizado : 'N' ) == 'N' )"> 
                                                                <ul class="dropdown-menu" role="menu"> 
                                                                    <template x-for="situacao in dadosSituacao" >
                                                                        <li x-on:click="atualizaStatus(situacao.cd_situacao)"  >
                                                                            <a  href="#" x-bind:style="'font-weight: bold; padding: 4px 10px; color:' + situacao.color">
                                                                                <span x-html="situacao.icone"> </span>
                                                                                <span x-html="situacao.nome_situacao"> </span> 
                                                                            </a>
                                                                        </li>
                                                                    </template> 
                                                                </ul>
                                                            </template>
                                
                                                    </div>
                                                </div> 
                                            </template>
                                        </div>
                               

                                        <div class="col-md-4" style="text-align: center; ">
                                            <template x-if="(modalAgenda.cd_agendamento)">
                                                <code style=" text-align: center;   background-color: #f2f2f9;    color: #380de7;">
                                                    <strong>* Dados do Atendimento *</strong>  <br>
                                                    <strong>Dados do Agendamento: </strong> <span x-text="( (modalAgenda.user_agendamento?.nm_usuario) ? modalAgenda.user_agendamento?.nm_usuario : '--' ) + ' [ ' + ( (modalAgenda.data_agendamento)?modalAgenda.data_agendamento:'--' ) + ' ]'"></span><br>
                                                     
                                                    <strong>Dados do Check-in: </strong> <span x-text=" ( (modalAgenda.user_presenca?.nm_usuario) ? modalAgenda.user_presenca?.nm_usuario : '--' )  + ' [ ' +  ( (modalAgenda.data_presenca)?modalAgenda.data_presenca:'--' )  + ' ]' "></span>
                                                </code>
                                            </template>
                                        </div>
                            
                                        <div class="col-md-4" style="text-align: center; ">
                                            <template x-if="modalAgenda.convenio.link_autorizacao">
                                                <code style=" text-align: center;   background-color: #f6f9f2;    color: #009b88;">
                                                    <strong>* Acesso ao portal do Convênio *</strong><br>
                                                   <a href="" target="_blank" x-text="modalAgenda.convenio.link_autorizacao" ></a> <br>
                                                    <strong>Usuario: </strong> <span x-text="modalAgenda.convenio.user_autorizacao"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Senha: </strong> <span x-text="modalAgenda.convenio.senha_autorizacao"></span>
                                                </code>
                                            </template>
                            
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group" style="text-align: right; margin-top: 10px;">
                                                <button type="submit" class="btn btn-success"
                                                    x-html="buttonSalvarAgendamneto">
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                            

                                </form>
                            </div>
                            </div>


                        </div>

                        <div class="tab-pane" role="tabpanel" id="TabPaciente">
                          
                                @include('rpclinica.agendamento-lista.modal-paciente')
                                 
                             
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
