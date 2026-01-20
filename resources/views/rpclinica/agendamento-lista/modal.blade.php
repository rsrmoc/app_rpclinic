<div class="modal fade" id="cadastro-consulta">

    <div class="modal-dialog modal-lg" style="width: 95%; max-width: 1400px; z-index: 99999;">

        <div class="modal-content">

            <div class="absolute-loading" style="display: none">
                <div class="line">
                    <div class="loading"></div>
                    <span style="font-weight: bold; font-size: 1.3em; font-style: italic" x-html="loadingAcao"></span>
                </div>
            </div>
 
            <div class="modal-header m-b-sm">

                <div class="line" style="justify-content: space-between">
                    <h4 class="modal-title"
                        x-html="modalAgenda.agenda?.nm_agenda + ' {  ' + modalAgenda.cd_horario +'  }'"> </h4>
                    <div class="line">
                        <button type="button" x-show="snExcluir" class="btn btn-default btn-rounded" 
                        x-on:click="ExcluirAgenda" style="color: red">
                            <i class="fa fa-trash"></i> Excluir
                        </button>
                      
                        <button type="button" x-show="snBloquea" class="btn btn-default btn-rounded" 
                        x-on:click="bloquearHorario" style="color: red">
                                 <i class="fa fa-ban"></i> Bloquear
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
                                <li x-text="error"></li>
                            </template>
                        </ul>
                    </div>
                </template>

                <div class="tabpanel">

                    
                        <ul class="nav nav-tabs nav-justified" role="tablist">
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

                            @include('rpclinica.agendamento-lista.modal-agendamento')

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
