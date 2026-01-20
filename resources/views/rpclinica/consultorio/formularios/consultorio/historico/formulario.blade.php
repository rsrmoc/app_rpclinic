<div class=" col-md-offset-1 col-md-10 col-sm-10 col-xs-12 col-lg-10">

  <div class="panel panel-white ui-sortable-handle">
    <div class="panel-heading">
      <h3 class="panel-title">  Historico do Paciente
      </h3> 
    </div>
    <div class="panel-body"  >
       
            <div class="panel-group" id="accordion" st role="tablist" aria-multiselectable="true">


              <template x-if="history.Geral"> 
                <template x-for="item in history.Geral">

                  <div class="panel panel-default" style="border-radius: 0px;">
                      <div class="panel-heading"   role="tab" x-bind:id="'heading'+item.cd_agendamento">
                          <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" x-bind:href="'#collapse'+item.cd_agendamento"   
                                  x-bind:aria-controls="'collapse'+item.cd_agendamento"  aria-expanded="false">
                                  <div class="row"> 
                                      <div class="col-md-2"> 
                                        <small style="font-size: 13px; font-weight: 900; font-style: italic;">Agendamento</small><br>
                                        <span x-html="item.cd_agendamento"></span>
                                      </div> 
                                    <div class="col-md-2">  
                                      <small style="font-size: 13px; font-weight: 900; font-style: italic;">Data</small><br>
                                      <span x-html="item.data"></span>
                                    </div> 
                                    <div class="col-md-5">  
                                      <small style="font-size: 13px; font-weight: 900; font-style: italic;">Profissional</small><br>
                                      <span x-html="titleize(item.profissional.nm_profissional)"></span>
                                    </div> 
                                    <div class="col-md-3"> 
                                      <small style="font-size: 13px; font-weight: 900; font-style: italic;">Agenda</small><br>
                                      <span x-html="(item.agenda?.nm_agenda) ? titleize(item.agenda.nm_agenda) : ' -- '"></span>
                                    </div> 
                                  </div>
                              </a>
                          </h4>
                      </div>
                      <div x-bind:id="'collapse'+item.cd_agendamento" class="panel-collapse collapse " role="tabpanel" x-bind:aria-labelledby="'heading'+item.cd_agendamento">
                          <div class="panel-body">
                             

                                <fieldset disabled>

                                    <div class="panel panel-default"
                                        style="margin-top: 1.5em; margin-bottom: 0; border: 1px solid #ddd !important; ">
                                        <div class="panel-heading" style="padding: 12px; height: 60px; background: #d4f8f5">
                                            <h3 class="panel-title"style="width: 100%;">
          
                                                <table width="100%">
                                                    <tr>
                                                        <td width="50%" style="text-align: left">
                                                            <span style=" margin-right: 15px; font-style: italic"
                                                                x-html="` <i class='fa fa-stethoscope'></i> Anamnese`">
                                                            </span>
                                                            <br>
                                                            <span style=" margin-right: 15px;font-weight: 300;"
                                                                x-html=" item.data ">
                                                            </span>
          
                                                        </td>
                                                        <td style="text-align: right">
                                                            <span style=" margin-right: 15px; font-style: italic"
                                                                x-html=" `<i class='fa fa-user-md'></i> ` + ( (item.profissional?.nm_profissional) ? titleize(item.profissional?.nm_profissional) : ' -- ' ) ">
                                                            </span>
                                                            <br>
          
                                                            <span style=" margin-right: 15px;font-weight: 300;"
                                                                x-html="' Usuario: ' + ( (item.user_anamnese?.nm_usuario) ? titleize(item.user_anamnese?.nm_usuario) : ' -- ' )">
                                                            </span>
          
                                                        </td>
                                                    </tr>
                                                </table>
                                            </h3>
                                        </div>
                                        <div class="panel-body" style="padding-top: 1.5em">
                                            <form>
                                               
                                                <template x-if="(item.historia_pregressa != '')">
                                                    <div class="form-group" style="margin-bottom: 5px;">
                                                        <div class="col-md-12">
                                                            <label for="input-placeholder" class=" control-label"
                                                                style="  padding-top: 0px;">História Pregressa:</label>
                                                            <!--<textarea style="min-height: 300px" class="form-control" name="comentario" x-html="item.historia_pregressa">
                                                        </textarea> -->
                                                            <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                            padding: 6px 10px !important;" x-html="item.historia_pregressa"> </div> 
                                                        </div>
                                                    </div>
                                                </template>
                                                 
 
                                                <template x-if="(item.anamnese != '')">
                                                <div class="form-group" style="margin-bottom: 5px;">
                                                    <div class="col-md-12">
                                                        <label for="input-placeholder" class=" control-label"
                                                            style="  padding-top: 0px;">Anamnese:</label>
                                                        <!--<textarea style="min-height:400px" class="form-control" name="comentario" x-html="item.anamnese">
                                                      </textarea>-->
                                                      <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                      padding: 6px 10px !important;" x-html="item.anamnese"> </div> 
                                                    </div>
                                                </div>
                                                </template> 
   
                                                <template x-if="(item.exame_fisico != '')">
                                                <div class="form-group" style="margin-bottom: 5px;">
                                                    <div class="col-md-12">
                                                        <label for="input-placeholder" class=" control-label"
                                                            style="  padding-top: 0px;">Exame Fisico:</label>
                                                        <!--<textarea style="min-height: 300px" class="form-control" name="comentario" x-html="item.exame_fisico">
                                                      </textarea>-->
                                                      <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                      padding: 6px 10px !important;" x-html="item.exame_fisico"> </div> 
                                                    </div>
                                                </div>
                                                </template> 

                                                 
                                                <template x-if="(item.hipotese_diagnostica != '')">
                                                <div class="form-group" style="margin-bottom: 5px;">
                                                    <div class="col-md-12">
                                                        <label for="input-placeholder" class=" control-label"
                                                            style="  padding-top: 0px;">Hipótese Diagnóstica:</label>
                                                        <!--<textarea style="min-height: 300px" class="form-control" name="comentario" x-html="item.hipotese_diagnostica">
                                                      </textarea>-->
                                                      <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                      padding: 6px 10px !important;" x-html="item.hipotese_diagnostica"> </div> 
                                                    </div>
                                                </div>
                                                </template> 

                                                
                                                <template x-if="(item.conduta != '')">
                                                <div class="form-group" style="margin-bottom: 5px;">
                                                    <div class="col-md-12">
                                                        <label for="input-placeholder" class=" control-label"
                                                            style="  padding-top: 0px;">Conduta:</label>
                                                        <!--<textarea style="min-height: 300px" class="form-control" name="comentario" x-html="item.conduta">
                                                      </textarea>-->
                                                      <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                      padding: 6px 10px !important;" x-html="item.conduta"> </div> 
                                                    </div>
                                                </div>
                                                </template>
                                                

                                            </form>
          
                                        </div>
                                    </div>

                                </fieldset>
 
                                <template x-if="item.documentos"> 
                                  <template x-for="doc in item.documentos">
 
                                    <fieldset disabled>

                                        <div class="panel panel-default"
                                            style="margin-top: 1.5em; margin-bottom: 0; border: 1px solid #ddd !important; ">
                                            <div class="panel-heading" style="padding: 12px; height: 60px; background: #dff0cb">
                                                <h3 class="panel-title"style="width: 100%;">
              
                                                    <table width="100%">
                                                        <tr>
                                                            <td width="50%" style="text-align: left">
                                                                <span style=" margin-right: 15px; font-style: italic"
                                                                    x-html="`<i class='fa fa-file-text'></i> Documento`">
                                                                </span>
                                                                <br>
                                                                <span style=" margin-right: 15px;font-weight: 300;"
                                                                    x-html="(doc.nm_formulario) ? titleize(doc.nm_formulario) : ' -- ' ">
                                                                </span>
              
                                                            </td>
                                                            <td style="text-align: right">
                                                                <span style=" margin-right: 15px; font-style: italic"
                                                                    x-html="`<i class='fa fa-user-md'></i> ` + ( (doc.profissional?.nm_profissional) ? titleize(doc.profissional?.nm_profissional) : ' -- ' ) ">
                                                                </span>
                                                                <br>
              
                                                                <span style=" margin-right: 15px;font-weight: 300;"
                                                                    x-html="doc.data_hora ">
                                                                </span>
              
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </h3>
                                            </div>
                                            <div class="panel-body" style="padding-top: 1.5em">
                                                <form>
              
                                                    <div class="form-group" style="margin-bottom: 5px;">
                                                        <div class="col-md-12">
                                                            <label for="input-placeholder" class=" control-label"
                                                                style="  padding-top: 0px;">Conteudo:</label>
                                                            <!--<textarea  style="min-height: 400px;" class="form-control" name="comentario" x-html="doc.conteudo">
                                                          </textarea> -->
                                                          <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                          padding: 6px 10px !important;" x-html="doc.conteudo"> </div> 
                                                        </div>
                                                    </div>
              
                                                    
              
                                                </form>
              
                                            </div>
                                        </div>
                                    </fieldset>
 
                                  </template>
                                </template>

                                <br><br>
                            
                          </div>
                      </div>
                  </div>

                </template>
              </template>


          
            </div>
</div>
</div> 












      
    </div>
  </div>
  
 

</div>

 
