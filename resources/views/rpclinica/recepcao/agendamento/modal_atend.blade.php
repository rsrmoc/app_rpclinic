<div class="modal fade" id="cadastro-consulta">
    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content">

            <div class="absolute-loading" style="display: none">
                <div class="line">
                    <div class="loading"></div>
                    <span style="font-weight: bold; font-size: 1.3em; font-style: italic" x-html="loadingAcao"></span>
                </div>
            </div>
 
       

            <div class="modal-header m-b-sm">

                <div class="line" style="justify-content: space-between" >
                    <h4 class="modal-title" style="margin-left: 10px; " >
                        <span class="glyphicon glyphicon-bullhorn btn btn-default" aria-hidden="true" style="margin-right: 15px; font-size: 18px; padding: 1px 12px;"></span>
                        <span x-html="tituloModal"></span></h4> 

                        <div class="line">
    
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
                            <a href="#TabAtendimento" role="tab" data-toggle="tab"
                                style="padding: 3px 15px; border-left: 0px;"><span aria-hidden="true"
                                    class="icon-note" style="margin-right: 10px;"></span> Atendimento</a>
                        </li>

                        <li role="presentation" class="">
                            <a href="#TabPaciente" role="tab" data-toggle="tab" style="padding: 3px 15px;">
                                <span aria-hidden="true" class="icon-user" style="margin-right: 10px;"></span>Cadastro
                                de Paciente
                            </a>
                        </li>

                         
                            <li role="presentation" class="" x-show="modalAtendimentos.tp_convenio =='CO'">
                                <a href="#TabGuiaFaturamento" role="tab" data-toggle="tab" style="padding: 3px 15px;"> 
                                    <i class="fa fa-list-alt" style="margin-right: 10px;"></i>Guias de Faturamento 
                                </a>
                            </li>
                    
                         
                        <li role="presentation" class="">
                            <a href="#TabItensAgendamento" role="tab" data-toggle="tab" style="padding: 3px 15px;">
                                <span aria-hidden="true" class="icon-list" style="margin-right: 10px;"></span>Itens do Atendimento 
                            </a>
                        </li>
                       
                    </ul>

                    <div class="tab-content">



                        <div class="tab-pane active" role="tabpanel" id="TabAtendimento">  
                            @include('rpclinica.recepcao.agendamento.modal.atendimento') 
                        </div>

                        <div class="tab-pane" role="tabpanel" id="TabPaciente">
                            @include('rpclinica.recepcao.agendamento.modal.paciente')
                        </div>

                        <div class="tab-pane" role="tabpanel" id="TabItensAgendamento">
                            @include('rpclinica.recepcao.agendamento.modal.itens_agendamento',['exames'=>$exames])
                        </div>

                        <div class="tab-pane" role="tabpanel" id="TabGuiaFaturamento">
                            @include('rpclinica.recepcao.agendamento.modal.guias_faturamento',['lista_guia'=>$lista_guia])
                        </div>

                     

                        <div class="tab-pane" role="tabpanel" id="TabHistorico">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>