<div class="modal fade" id="cadastro-bloqueio">

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
                    <h4 class="modal-title"> Bloqueio de Agenda </h4>

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

                <div class="tab-content">

                    <div class="tab-pane active" role="tabpanel" id="TabSobre">



                        <style> 
                            .class_enviado{ color: #08bd0f }
                            .class_aguardando{color: #c8c6c6 }
                            </style>
                            
                            <form x-on:submit.prevent="storeBloqueio" id="form-store-bloqueio"   style="">
                                <input type="hidden" name="tipo" value="avanc">
                                <div class="row">
                                                                  
                                    <div class="col-md-4 col-md-offset-1">
                                        <div class="form-group">
                                            <label>Profissional: <span class="red normal">*</span></label>
                                            <select class="form-control"  tabindex="-1" style="width: 100%" required name="profissional" id="bloqueio-profissional"  >
                                                <option value="">Selecione</option>
                                                @foreach ($parametros['profissionais'] as $linha)
                                                    <option value="{{ $linha->cd_profissional }}">{{ $linha->nm_profissional}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                               

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Data Inicial: <span class="red normal">*</span></label>
                                            <input type="date" class="form-control required" value="{{ old('dti') }}"
                                                name="dti" maxlength="100" aria-required="true" required>
                                        </div>
                                    </div> 

                                    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Data Final: <span class="red normal">*</span></label>
                                            <input type="date" class="form-control required" value="{{ old('dtf') }}"
                                                name="dtf" maxlength="100" aria-required="true" required>
                                        </div>
                                    </div> 

                                    <div class="col-md-2">
                                        <button type="submit" style="font-size: 15px; margin-top: 22px; width: 100%;" class="btn btn-success"> 
                                            <template x-if="buttonPesqAvanc">
                                                <span style="font-weight: bold"> 
                                                    <i class="fa fa-refresh fa-spin " style="margin-right: 5px;" ></i> Salvando ... 
                                                </span>
                                              </template>
                                              <template x-if="!buttonPesqAvanc">  
                                                <span style="font-weight: bold"> 
                                                    <span aria-hidden="true" class="icon-check" style="margin-right: 5px;" ></span> Salvar
                                                </span>
                                              </template>
                                        </button>
                                    </div>
                                </div>
                            </form>
                             
                                <table  class="table table-striped table-hover " style="margin-top: 40px; width: 80%; margin-left: 10%;"> 
                                    <thead>
                                        <tr class="active">  
                                            <th class="text-left">Profissional</th>
                                            <th class="text-left">Data Inicial</th>
                                            <th class="text-left">Data Final</th>  
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead> 
                            
                                    <tbody>
                                        <template x-if="tablePesqBloq">
                                            <template x-for="item in tablePesqBloq">
                                                <tr>  
                                                    <th class="text-left">
                                                        <span x-text="item.tab_profissional?.nm_profissional"></span> 
                                                    </th>
                                                    <td class="text-left">
                                                        <span x-text="item.data_inicio"></span> 
                                                    </td>
                                                    <td class="text-left">
                                                        <span x-text="item.data_final"></span> 
                                                    </td> 
                                                    <td class="text-center">
                                                        <div class="btn-group">  
                                                            <button  class="btn btn-danger" x-on:click="ExcluirBloqueio(item.cd_agenda_bloqueio)">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td> 
                                                
                                                </tr>
                                            </template>
                                        </template>
                                    </tbody>
                                </table>
                                 
                            
                            
                            <template x-if="!tablePesqBloq">
                                <p class="text-center" style="padding: 1.7em"> 
                                    <img src="{{ asset('assets\images\calendario.png') }}"> <br>
                                    <span style="font-weight: bold; font-size: 1.2em; font-style: italic" > Bloqueio de Agenda</span> 
                                </p>
                            </template>

                      

                    </div>

                </div>
                
            </div>

        </div>

    </div>
</div>
