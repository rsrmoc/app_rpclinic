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
                        
                        <span x-html="tituloModal"></span></h4> 

                        <div class="line">
                              <template x-if="modalCompleto.recebido == 'N'">
                                <span> 
                                    <button type="button" class="btn btn-default btn-rounded" 
                                        x-show="(inputsConta.liberarConta==true)"
                                        x-on:click="liberarAtendimento" style="color: #22baa0">
                                        <i class="fa fa-thumbs-o-up"></i> Liberar Atendimento
                                    </button>
                                    <button type="button" class="btn btn-default btn-rounded" 
                                        x-show="(inputsConta.liberarConta==false)"
                                         style="color: #afbbb9">
                                        <i class="fa fa-thumbs-o-up"></i> Liberar Atendimento
                                    </button>
                                </span> 
                              </template> 

                              <template x-if="modalCompleto.recebido == 'S'">
                                <button type="button" class="btn btn-default btn-rounded" x-on:click="liberarAtendimento" style="color: #f25656">
                                    <i class="fa fa-thumbs-o-down"></i> Bloquear Atendimento
                                </button>
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
                                <li x-text="error"></li>
                            </template>
                        </ul>
                    </div>
                </template>

                <div class="tabpanel">

                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#TabConta" role="tab" data-toggle="tab"
                                style="padding: 3px 15px; border-left: 0px;"><span aria-hidden="true"
                                    class="icon-calendar" style="margin-right: 10px;"></span> Conta [ Faturamento ]</a>
                        </li>

                        <li role="presentation" class="">
                            <a href="#TabRecebimento" role="tab" data-toggle="tab" style="padding: 3px 15px;">
                                <span aria-hidden="true" class="icon-user" style="margin-right: 10px;"></span>Recebimento [ Financeiro ]
                           
                            </a>
                        </li> 
                    </ul>

                    <div class="tab-content">
  
                        <div class="tab-pane active" role="tabpanel" id="TabConta">
                            @include('rpclinica.recepcao.agendamento.modal.faturamento-pac')
                        </div>

                        <div class="tab-pane" role="tabpanel" id="TabRecebimento">
                            @include('rpclinica.recepcao.agendamento.modal.recebimento-pac')
                        </div>
 
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>