
    <form role="form"  x-on:submit.prevent="cadastrarGuia" method="post" id="form-recepcao-guia">
        @csrf  
        
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Atendimento: <span class="red normal">*</span></label> 
                        <span class="form-control" style="border: 1px solid #dce1e4; background-color: #f1f3f5;" 
                        x-text="modalAtendimentos.cd_agendamento " > </span> 
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Profissional: <span class="red normal">*</span></label>                    
                        <span class="form-control" style="border: 1px solid #dce1e4; background-color: #f1f3f5;" 
                        x-text="modalAtendimentos.profissional" > </span> 
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Carater: <span class="red normal">*</span></label>
                        <span class="form-control" style="border: 1px solid #dce1e4; background-color: #f1f3f5;" 
                        x-text="modalAtendimentos.carater" > </span> 
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Solicitação: <span class="red normal">*</span></label>
                        <input type="date" name="dt_solicitacao" required  class="form-control" />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Nr. Guia: <span class="red normal">*</span></label>
                        <input type="text" name="nr_guia" required  class="form-control" />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Senha: <span class="red normal">*</span></label>
                        <input type="text" name="senha"  required class="form-control" />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tipo: <span class="red normal">*</span></label>
                        <select class="form-control" tabindex="-1" required style="width: 100%"  name="tipo" id="guia-tipo"  >
                            <option value="">Selecione</option>
                            <option value="consulta">Consulta</option>
                            <option value="internacao">Internação</option>
                            <option value="procedimento">Procedimento</option>
                            <option value="opme">OPME</option> 
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Situação: <span class="red normal">*</span></label>
                        <select class="form-control" tabindex="-1" required style="width: 100%"  name="situacao" id="guia-situacao"  >
                            <option value="">Selecione</option>
                            @if(isset($lista_guia))

                                @foreach($lista_guia as $val) 
                                    <option value="{{$val->cd_situacao_guia}}">{{$val->nm_situacao}}</option> 
                                @endforeach 

                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Itens: <span class="red normal">*</span></label>
                        <select class="form-control" tabindex="-1" multiple required style="width: 100%"  name="itens[]" id="guia-item"  >
                            <template x-if="modalData.itens_pendente">
                                <template x-for="item in modalData.itens_pendente">
                                    <option :value="item.cd_agendamento_item" x-text="item.exame?.nm_exame" ></option>
                                </template>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group" style="  margin-top: 23px;">
                        <button type="submit" class="btn btn-success" style="width: 100%" x-html="buttonSalvarGuia"> </button>
                    </div>
                </div>
            </div>
    
        
        <h4 class="modal-title" style="font-size: 1.7em; text-align: center;">
            Guias do Atendimento</h4>
        <br>
        <table  class="table table-striped table-hover " style="margin-top: 40px;"> 
            <thead>
                <tr class="active"> 
                    <th class="text-left">ID</th>
                    <th class="text-left">Guia</th>
                    <th class="text-left">Senha</th>
                    <th class="text-left">Data</th>
                    <th class="text-left">Tipo</th>
                    <th class="text-left">Usuario</th>  
                    <th class="text-left">Itens</th>  
                    <th class="text-left">Situação</th> 
                </tr>
            </thead> 

            <tbody>
                <template x-if="modalAgenda.itensGuia">
                <template x-for="item in modalAgenda.itensGuia">
                    <tr> 
                        <th class="text-left">
                            <span x-text="item.cd_agendamento_guia"></span> 
                        </th>
                        <th class="text-left">
                            <span x-text="item.nr_guia"></span> 
                        </th>
                        <th class="text-left">
                            <span x-text="item.senha"></span> 
                        </th>
                        <td class="text-left">
                            <span x-text="item.data_solicitacao"></span>  
                        </td>
                        <td class="text-left">
                            <span x-text="item.tp_guia"></span>  
                        </td>
                        <td class="text-left">
                            <span x-text="item.usuario.nm_usuario"></span>   
                        </td>
                        <td class="text-left">
                            <template x-for="ex in item.itens">
                                <span x-html="ex.exame.nm_exame+'<br>'"></span>   
                            </template> 
                        </td>
                        <td class="text-left" style=""> 
                                <div v class="btn-group m-b-sm" style="margin-top: 0px; margin-bottom: 0px">
                                    <button type="button" class="btn btn-default dropdown-toggle"  x-bind:style="'font-weight: bold; color:'+ item.situacao_guia.cor  +'; font-size:1.2rem !important'" id="situacaoButton" data-toggle="dropdown" x-html="item.situacao_guia.icone+ ' ' + item.situacao_guia.nm_situacao " aria-expanded="false"> 
        
                                    </button>
                                    
                                    <ul class="dropdown-menu" role="menu">

                                        @if(isset($lista_guia))

                                            @foreach($lista_guia as $val)  

                                                <li  x-on:click="statusGuia('autorizada',item)" >
                                                    <a href="#" style="color:{{ $val->cor }} ; font-weight: bold;padding: 4px 10px;"> 
                                                        {!! $val->icone !!} 
                                                        {{ $val->nm_situacao }} 
                                                    </a>
                                                </li>
                                            @endforeach 
                                            
                                        @endif
          
                                        <li role="presentation" class="divider"></li>

                                        <li x-on:click="statusGuia('excluir',item)" >
                                            <a href="#" style="color:#ea0033; font-weight: bold;padding: 4px 10px;"> 
                                                <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>   
                                                Excluir Guia 
                                            </a>
                                        </li>

                                    </ul>
                                </div> 
                        </td>
                
                    
                    </tr>
                </template>
                </template>
            </tbody>
        </table>

    

    </form>
 