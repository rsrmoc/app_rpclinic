 
 <form role="form"  x-on:submit.prevent="cadastrarItem" method="post" id="form-item">
    @csrf  
     
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-7 col-md-offset-2">
                <div class="form-group">
                    <label>Nome do Item: <span class="red normal">*</span></label>
                    <select class="form-control" tabindex="-1" style="width: 100%" required  name="exame" id="cod_exme_item" >
                        <option value="">Selecione</option>
                        <template x-if="modalData.itens">
                            <template x-for="item in modalData.itens">
                                <option :value="item.cd_exame" x-text="item.nm_exame" ></option>
                            </template>
                        </template>
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>Qtde: <span class="red normal">*</span></label>
                    <input type="text" name="qtde" value="1"   x-mask="99" required class="form-control center"  /> 
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group" style="  margin-top: 23px;">
                    <button type="submit" class="btn btn-success" x-html="buttonSalvarItem"></button>
                </div>
            </div>
        </div>
  
    <br>
    <h4 class="modal-title" style="font-size: 1.7em; text-align: center;">
        Itens do Atendimento</h4>
    <br>
    <table  class="table table-striped table-hover " style="margin-top: 40px;"> 
        <thead>
            <tr class="active"> 
                <th class="text-left">ID</th>
                <th class="text-left">Item</th>
                <th class="text-left">Descrição</th>
                <th class="text-left">Procedimento</th>
                <th class="text-left">Qtde</th>
                <th class="text-left">Data</th>
                <th class="text-left">Usuario</th>  
                <th class="text-center">Ação</th>
            </tr>
        </thead> 

        <tbody>
            <template x-if="modalAgenda.itensAgendamento">
            <template x-for="item in modalAgenda.itensAgendamento">
                <tr> 
                    <th class="text-left">
                        <span x-text="item.cd_agendamento_item"></span> 
                    </th>
                    <th class="text-left">
                        <span x-text="item.cd_exame"></span> 
                    </th>
                    <th class="text-left">
                        <span x-text="item.exame.nm_exame"></span> 
                    </th>
                    <th class="text-left">
                        <span x-text="(item.exame.cod_proc) ? item.exame.cod_proc : ' -- '"></span> 
                    </th>
                    <td class="text-left">
                        <span x-text="item.qtde"></span>  
                    </td>
                    <td class="text-left">
                        <span x-text="item.data"></span>  
                    </td>
                    <td class="text-left">
                        <span x-text="item.usuario.nm_usuario"></span>   
                    </td>
                    <td class="text-center">
                        <span class="glyphicon glyphicon-trash red" x-on:click="excluirItem(item)" style="cursor: pointer;" aria-hidden="true"></span>
                    </td>
                   
                </tr>
            </template>
            </template>
        </tbody>
    </table>

  

 </form>