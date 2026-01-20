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
                    <h4 class="modal-title"> Agendar</h4>
                    <div class="line">
                        <template x-if="( ( (modalAgenda.sn_finalizado) ? modalAgenda.sn_finalizado : 'N' ) == 'N' )"> 
                            <div>
                                <template x-if="modalAgenda.cd_agendamento">
                                    <div>
                                        <template x-if="modalAgenda.situacao != 'livre'">
                                            <template x-if="modalAgenda.situacao != 'bloqueado'">
                                                <button type="button" class="btn btn-default btn-rounded" x-on:click="ExcluirAgenda" style="color: red">
                                                    <i class="fa fa-trash"></i> Excluir
                                                </button>
                                            </template>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="modalAgenda.situacao=='livre'">
                                    <button type="button" class="btn btn-default btn-rounded" x-on:click="bloquearHorario" style="color: red">
                                        <i class="fa fa-ban"></i> Bloquear
                                    </button>
                                </template>
                            </div>
                        </template>
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

                <div class="tabpanel"  >

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
                         
                        <li role="presentation" class="">
                            <a href="#TabHistorico" role="tab" data-toggle="tab" style="padding: 3px 15px;">
                                <span aria-hidden="true" class="icon-layers" style="margin-right: 10px;"></span>Historico
                                de Atendimento
                            </a>
                        </li>  
                    </ul>

                    <div class="tab-content">

                        <div class="tab-pane active" role="tabpanel" id="TabSobre">
                           
                            @include('rpclinica.recepcao.agendamento.modal.sobre_agenda',$request)

                        </div>

                        <div class="tab-pane" role="tabpanel" id="TabPaciente">
                            @include('rpclinica.recepcao.agendamento.modal.paciente',$request)
                        </div>

                        <div class="tab-pane" role="tabpanel" id="TabItensAgendamento">
                         <!--   @include('rpclinica.recepcao.agendamento.modal.itens_agendamento',['exames'=>$exames]) -->
                        </div>

                        
                        <div class="tab-pane" role="tabpanel" id="TabHistorico">
                            @include('rpclinica.recepcao.agendamento.modal.historico',['exames'=>$exames])  
                        </div>
                    </div>
                    <code style="font-weight: 400; font-size: 10px; color: #2dd4bf; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                        <b>Escala:</b><span x-text="modalAgenda.cd_escala"></span> 
                        <b style="margin-left: 20px;">Horario:</b><span x-text="modalAgenda.cd_horario"></span>
                        <b style="margin-left: 20px;">Tipo de Escala:</b><span x-text=""></span>
                    </code>

                </div>

            </div>

        </div>

    </div>
</div>