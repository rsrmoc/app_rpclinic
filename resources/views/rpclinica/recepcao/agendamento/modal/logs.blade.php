<div class="panel-body">
    <div class="tabs-left" role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#tabWhatsApp" role="tab" data-toggle="tab">WhatsApp</a></li>
            <li role="presentation"><a href="#tabAlteracoes" role="tab" data-toggle="tab">Alterações</a></li> 
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active fade in" id="tabWhatsApp" style="text-align: center;">
                <br>
                <h4 class="modal-title" style="font-size: 1.7em; text-align: center;">
                    Logs de Envio</h4>
                <br>
                <table  class="table table-striped table-hover" style="margin-top: 40px;"> 
                    <thead>
                        <tr class="active">
                            <th class="text-left"></th>
                            <th class="text-left">Tipo</th>
                            <th class="text-left">ID</th>
                            <th class="text-left">Celular</th>
                            <th class="text-left">Data</th>
                            <th class="text-left">Usuario</th> 
                            <th class="text-left">Tipo de Mensagem</th>
                            <th class="text-left">Mensagem</th>
                        </tr>
                    </thead> 

                    <tbody>

                        <template x-for="log in logsWhsat">
                            <tr>
                                <th class="text-left">
                                    <template x-if="log.tipo=='ENVIO'">
                                        <i class="fa fa-arrow-circle-o-right" style="font-size: 17px;"></i>
                                    </template>
                                    <template x-if="log.tipo=='RETORNO'">
                                        <i class="fa fa-arrow-circle-o-left" style="font-size: 17px;"></i>
                                    </template>
                                </th>
                                <th class="text-left">
                                    <span x-text="log.tipo"></span> 
                                </th>
                                <td class="text-left">
                                    <span 
                                        x-text="log.id"></span>
                                </td>
                                <td class="text-left">
                                    <span 
                                        x-text="log.nr_send.split('@')[0]"></span>
                                </td>
                                <td class="text-left">
                                    <span 
                                        x-text="log.dt_envio"></span>
                                </td>
                                <td class="text-left">
                                    <span 
                                        x-text="log.cd_usuario"></span>
                                </td>
                                <td class="text-left">
                                    <span 
                                        x-text="log.tp_msg"></span>
                                </td>
                                <td class="text-left">
                                    <span 
                                        x-text="log.msg"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <template x-if="logsWhsat.length == 0 ">
                    <p class="text-center" style="padding: 1.5em">

                        <img src="{{ asset('assets\images\logs.png') }}"> <br>
                        Não há logs gerado para esse agendamento
                    </p>
                </template>


       
         
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tabAlteracoes" style="text-align: center;">

                <span style="text-align: center;">
                    <br><br><br>
                    <img  style="text-align: center;" src="{{ asset('assets\images\logs.png') }}"  >
                </span> <br>  

            </div>
      
        
        </div>
    </div>
</div>