
<div class="row line">
  <div class="col-md-3">

  </div>
  <div class="col-md-6">
    <h4 class="modal-title" style="font-size: 1.7em; text-align: center;">
        Itens da Conta</h4>
  </div>
  <div class="col-md-3" style="text-align: right">
    <div class="panel-body" style="text-align: right;     padding: 5px;">
    <div class="btn-group">
        <template x-if="(modalCompleto.situacao_conta=='A')"> 
            <span> 
                <button type="button" style="  font-style: italic;" x-on:click="recalcularConta(modalCompleto)"
                    class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Recalcular Conta">
                        <span aria-hidden="true" class="icon-refresh"></span>
                </button> 
                <button type="button" style="  font-style: italic;" x-on:click="fecharAbrirConta(modalCompleto,'F')"
                    class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Fechar Conta">
                        <span aria-hidden="true" class="icon-check"></span>
                </button>
            </span>
        </template>
        <template x-if="(modalCompleto.situacao_conta=='F')"> 
            <button type="button" style="  font-style: italic;" x-on:click="fecharAbrirConta(modalCompleto,'A')"
                x-show="(inputsConta.reabrirConta==true)"
                class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Fechar Conta">
                    <span aria-hidden="true" class="icon-logout"></span>
            </button>
        </template>

 
    </div>
    </div>

  </div>
</div>


<br>
<template x-if="loading">
    <div class="line">
        <div class="loading"></div>
        <span style="font-weight: bold; font-size: 1.3em; font-style: italic" x-html="loadingAcao"></span>
    </div>
</template>

<template x-if="(loading==false)">
    <table  class="table table-striped table-hover " style="margin-top: 40px;"> 
            <thead>
                <tr class="active"> 
                    <th class="text-left">Codigo</th>
                    <th class="text-left">Item</th> 
                    <th class="text-left">Procedimento</th> 
                    <th class="text-left">Data</th>
                    <th class="text-left">Usuario</th>  
                    <th class="text-right">Valor</th> 
                </tr>
            </thead> 

            <tbody>  
                <template x-if="modalConta">
                    <template x-for="(item, index) in modalConta">
                        <tr> 
                            <th class="text-left">
                                <span x-text="item.cd_agendamento_item"></span> 
                            </th>
                            <th class="text-left">
                                <span x-text="item.cd_exame + ' - ' + item.exame.nm_exame"></span> 
                            </th>
                        
                            <td class="text-left">
                                <span x-html="(item.exame?.cod_proc) ? item.exame?.cod_proc + ' - ' + item.exame?.procedimento?.nm_proc : ' <code> Não Configurado </code> '"></span> 
                            </td>
                        
                            <td class="text-left">
                                <span x-text="item.data_hora"></span>  
                            </td>
                            <td class="text-left">
                                <span x-text="(item.usuario?.nm_usuario) ? item.usuario?.nm_usuario : ' -- '"></span>   
                            </td>
                            <td class="text-right">
                                <span x-html="(item.vl_item) ? formatValor(item.vl_item) : ' <code> Não Configurado </code> '"></span>    
                            </td> 
                        </tr>
                    </template>  
                </template>

                <tr  >
                    <th colspan="8" class="text-right" style="font-size: 1.2em; font-weight: 600; font-style: italic">
                        <i class="fa fa-angle-double-right"></i><i class="fa fa-angle-double-right"></i> Total da Conta: 
                        <span x-html="valorConta"> </span>
                    </th>   
                </tr> 
        </tbody>
    </table>
</template>
