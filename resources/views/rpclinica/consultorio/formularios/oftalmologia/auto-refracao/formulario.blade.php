<div class="col-md-7 col-sm-7 col-lg-6 col-xs-12  ">

  <div class="panel panel-white ui-sortable-handle">
      <div class="panel-heading">
          <div class="row">
              <div class="col-md-12">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Auto Refração
                  </h3>
                  <div class="panel-control">
                      <a href="javascript:void(0);"
                          x-on:click="() => {deleteAutoRefracao(AUTO_REFRACAO.formData.cd_auto_refracao);}"
                          data-toggle="tooltip" data-placement="top" title=""
                          data-original-title="Exluir Formulário"><i class="icon-close"></i></a>
                  </div>
              </div>
          </div>
      </div>

      <div class="panel-body" x-data="{ index: 0, showHistory: false }"  >

          <form class="form-horizontal" x-on:submit.prevent="storeAutoRefracao" id="form_AUTO_REFRACAO"
              method="post" x-show="!showHistory">
              @csrf
              <input type="hidden" name="tipo" value="Auto Refração">
              <div class="form-group ">
                <div class="col-sm-12">
                  <label for="input-help-block" class="control-label">Profissional: <span class="red normal">*</span></label>
                  <select class="form-control " name="cd_profissional" required style="width: 100%">
                    <option value="{{ $agendamento->cd_profissional }}">{{ $agendamento->profissional->nm_profissional }}</option>
                  </select>
  
                </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-4" style="padding-right: 5px;">
                      <label for="input-Default" class="control-label">Data do Exame: <span
                              class="red normal">*</span></label>
                      <input type="datetime-local" class="form-control input-sm text-center" required
                          name="dt_exame" x-model="AUTO_REFRACAO.formData.dt_exame"> 
                  </div>

                  <div class="col-sm-4" style="padding-right: 5px; padding-left: 5px;">
                      <label for="input-Default" class="control-label">Data da Liberação:</label>
                      <input type="datetime-local" class="form-control input-sm text-center" name="dt_liberacao"
                          x-model="AUTO_REFRACAO.formData.dt_liberacao"> 
                  </div>

                  <div class="col-sm-4" style="padding-left: 5px;">
                      <label for="input-Default" class="control-label bold">DP:</label>
                      <input type="text" class="form-control input-sm text-right" name="dp"
                          x-model="AUTO_REFRACAO.formData.dp" x-mask:dynamic="$money($input, ',')"> 
                  </div>
              </div>

              <div class="form-group" style="margin-bottom: 5px;">
                  <div class="col-sm-6">
                      <h5>Auto Refração Dinâmica:</h5>
                  </div>
                  <div class="col-sm-6">
                    <label  id="label_receita_dinamica">  
                            <input type="checkbox" name="receita_dinamica" id="receita_dinamica" >
                        Receita
                    </label> 
                  </div>
              </div>

              <div class="form-group" style="margin-bottom: 5px;">
                  <div class="col-sm-3" style="padding-right: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="font-size: 0.9em; padding-top: 0px;">OD DE</label>
                      <input type="text" class="form-control input-sm text-right" name="od_de_dinamica"
                          x-model="AUTO_REFRACAO.formData.od_de_dinamica" x-mask:dynamic="$money($input, ',')"> 
                  </div>
                  <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="font-size: 0.9em; padding-top: 0px;">DC</label>
                      <input type="text" class="form-control input-sm text-right" name="od_dc_dinamica"
                          x-model="AUTO_REFRACAO.formData.od_dc_dinamica" x-mask:dynamic="$money($input, ',')"> 
                  </div>
                  <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="padding-top: 0px;">Eixo</label>
                      <input type="text" class="form-control input-sm text-right" name="od_eixo_dinamica"
                          x-model="AUTO_REFRACAO.formData.od_eixo_dinamica" x-mask:dynamic="$money($input, ',')"> 
                  </div>
                  <div class="col-sm-3" style="padding-left: 5px;">
                      <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Reflexo
                          OD:</label>
                      <input type="text" class="form-control input-sm text-right" name="od_reflexo_dinamica"
                          x-model="AUTO_REFRACAO.formData.od_reflexo_dinamica" x-mask:dynamic="$money($input, ',')"> 
                  </div>
              </div>

              <div class="form-group" style="margin-bottom: 5px;">
                  <div class="col-sm-3" style="padding-right: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="font-size: 0.9em; padding-top: 0px;">OE DE</label>
                      <input type="text" class="form-control input-sm text-right" name="oe_de_dinamica"
                          x-model="AUTO_REFRACAO.formData.oe_de_dinamica" x-mask:dynamic="$money($input, ',')">
                  </div>
                  <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="font-size: 0.9em; padding-top: 0px;">DC</label>
                      <input type="text" class="form-control input-sm text-right" name="oe_dc_dinamica"
                          x-model="AUTO_REFRACAO.formData.oe_dc_dinamica" x-mask:dynamic="$money($input, ',')">
                  </div>
                  <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="padding-top: 0px;">Eixo</label>
                      <input type="text" class="form-control input-sm text-right" name="oe_eixo_dinamica"
                          x-model="AUTO_REFRACAO.formData.oe_eixo_dinamica" x-mask:dynamic="$money($input, ',')">
                  </div>
                  <div class="col-sm-3" style="padding-left: 5px;">
                      <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Reflexo
                          OE:</label>
                      <input type="text" class="form-control input-sm text-right" name="oe_reflexo_dinamica"
                          x-model="AUTO_REFRACAO.formData.oe_reflexo_dinamica" x-mask:dynamic="$money($input, ',')">
                  </div>
              </div>

              <div class="form-group">
                  <div class="col-sm-6">
                      <h5>Auto Refração Estática:</h5>
                  </div>
                  <div class="col-sm-6">
                      <label  id="label_receita_estatica">  
                            <input type="checkbox" name="receita_estatica" id="receita_estatica" >
                           Receita
                      </label>
                  </div>
              </div>

              <div class="form-group" style="margin-bottom: 5px;">
                  <div class="col-sm-3" style="padding-right: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="font-size: 0.9em; padding-top: 0px;">OD DE</label>
                      <input type="text" class="form-control input-sm text-right" name="od_de_estatica"
                          x-model="AUTO_REFRACAO.formData.od_de_estatica" x-mask:dynamic="$money($input, ',')">
                  </div>
                  <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="font-size: 0.9em; padding-top: 0px;">DC</label>
                      <input type="text" class="form-control input-sm text-right" name="od_dc_estatica"
                          x-model="AUTO_REFRACAO.formData.od_dc_estatica" x-mask:dynamic="$money($input, ',')">
                  </div>
                  <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="padding-top: 0px;">Eixo</label>
                      <input type="text" class="form-control input-sm text-right" name="od_eixo_estatica"
                          x-model="AUTO_REFRACAO.formData.od_eixo_estatica" x-mask:dynamic="$money($input, ',')">
                  </div>
                  <div class="col-sm-3" style="padding-left: 5px;">
                      <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Reflexo
                          OD:</label>
                      <input type="text" class="form-control input-sm text-right" name="od_reflexo_estatica"
                          x-model="AUTO_REFRACAO.formData.od_reflexo_estatica" x-mask:dynamic="$money($input, ',')">
                  </div>
              </div>

              <div class="form-group" style="margin-bottom: 5px;">
                  <div class="col-sm-3" style="padding-right: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="font-size: 0.9em; padding-top: 0px;">OE DE</label>
                      <input type="text" class="form-control input-sm text-right" name="oe_de_estatica"
                          x-model="AUTO_REFRACAO.formData.oe_de_estatica" x-mask:dynamic="$money($input, ',')">
                  </div>
                  <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="font-size: 0.9em; padding-top: 0px;">DC</label>
                      <input type="text" class="form-control input-sm text-right" name="oe_dc_estatica"
                          x-model="AUTO_REFRACAO.formData.oe_dc_estatica" x-mask:dynamic="$money($input, ',')">
                  </div>
                  <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                      <label for="input-placeholder" class="control-label"
                          style="padding-top: 0px;">Eixo</label>
                      <input type="text" class="form-control input-sm text-right" name="oe_eixo_estatica"
                          x-model="AUTO_REFRACAO.formData.oe_eixo_estatica" x-mask:dynamic="$money($input, ',')">
                  </div>
                  <div class="col-sm-3" style="padding-left: 5px;">
                      <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Reflexo
                          OE:</label>
                      <input type="text" class="form-control input-sm text-right" name="oe_reflexo_estatica"
                          x-model="AUTO_REFRACAO.formData.oe_reflexo_estatica" x-mask:dynamic="$money($input, ',')">
                  </div>
              </div>

              <div class="form-group" style="margin-bottom: 5px;">
                  <div class="col-md-12">
                      <label for="input-placeholder" class=" control-label"
                          style="  padding-top: 0px;">Comentário:</label>
                      <textarea rows="3" class="form-control" name="comentario" x-model="AUTO_REFRACAO.formData.comentario"></textarea>
                  </div>
              </div>

              <div class="panel-footer col-md-12">

                  <div class="row">
                      <div class="col-md-6">
                          <button type="submit" class="btn btn-success" x-html="buttonSalvar"
                              x-bind:disabled="buttonDisabled"> </button> 

                          <button class="btn btn-default" type="button" @click="showHistory = !showHistory">
                              <i class="fa fa-history"></i> Histórico
                          </button>
                      </div>
                      <div class="col-md-6" style="text-align: right">

                      </div>

                  </div>

              </div>
          </form>

          <form class="form-horizontal" x-show="showHistory">
              <fieldset disabled>
                  <input type="hidden" name="tipo" value="Auto Refração">
                  <div class="form-group ">
                      <div class="col-sm-12">
                          <label for="input-help-block" class="control-label">Profissional: <span
                                  class="red normal">*</span></label>
                          <input type="text" class="form-control input-sm text-left"  
                                  name="dt_exame" 
                                  x-model="AUTO_REFRACAO.history[index]['profissional']['nm_profissional']"> 
                      </div> 
                  </div>

                  <div class="form-group ">
                      <div class="col-sm-4" style="padding-right: 5px;">
                          <label for="input-Default " class="  control-label   ">Data do Exame: <span
                                  class="red normal">*</span></label>
                          <input type="datetime-local" class="form-control input-sm text-center" required
                              name="dt_exame" 
                              x-model="AUTO_REFRACAO.history[index]['dt_exame']"> 
                      </div>

                      <div class="col-sm-4" style="padding-right: 5px; padding-left: 5px;">
                          <label for="input-Default" class="  control-label ">Data da Liberação:</label>
                          <input type="datetime-local" class="form-control input-sm text-center"
                              name="dt_liberacao" 
                              x-model="AUTO_REFRACAO.history[index]['dt_liberacao']"> 
                      </div>

                      <div class="col-sm-4" style="  padding-left: 5px;">
                          <label for="input-Default" class="  control-label bold ">DP:</label>
                          <input type="text" class="form-control input-sm text-right" name="dp"
                            x-model="AUTO_REFRACAO.history[index]['dp']"> 
                      </div>
                  </div>

                  <div class="form-group" style="margin-bottom: 5px;">
                      <div class="col-sm-6" style="">
                          <h5>Auto Refração Dinâmica:</h5>
                      </div>
                      <div class="col-sm-6" >
                            <template x-if="AUTO_REFRACAO.history[index]['receita_dinamica']==1">
                                <label style="margin-top: 5px;">
                                <div class="checker"><span class="checked"><input type="checkbox" checked ></span></div> Receita 
                                </label> 
                            </template>
                            <template x-if="AUTO_REFRACAO.history[index]['receita_dinamica'] != 1">
                                <label style="margin-top: 5px;">
                                <div class="checker"><span><input type="checkbox"  ></span></div> Receita 
                                </label> 
                            </template>
                      </div>
                  </div>

                  <div class="form-group" style="margin-bottom: 5px;">
                      <div class="col-sm-3" style="padding-right: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="font-size: 0.9em; padding-top: 0px;">OD
                              DE</label>
                          <input type="text" class="form-control input-sm text-right" name="od_de_dinamica" 
                              x-model="AUTO_REFRACAO.history[index]['od_de_dinamica']"> 
                      </div>
                      <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="font-size: 0.9em; padding-top: 0px;">DC</label>
                          <input type="text" class="form-control input-sm text-right" name="od_dc_dinamica" 
                              x-model="AUTO_REFRACAO.history[index]['od_dc_dinamica']"> 
                      </div>
                      <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="  padding-top: 0px;">Eixo</label>
                          <input type="text" class="form-control input-sm text-right"
                              name="od_eixo_dinamica" 
                              x-model="AUTO_REFRACAO.history[index]['od_eixo_dinamica']">
                         
                      </div>
                      <div class="col-sm-3" style=" padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="  padding-top: 0px;">Reflexo
                              OD:</label>
                          <input type="text" class="form-control input-sm text-right"
                              name="od_reflexo_dinamica" 
                              x-model="AUTO_REFRACAO.history[index]['od_reflexo_dinamica']"> 
                      </div>
                  </div>

                  <div class="form-group" style="margin-bottom: 5px;">
                      <div class="col-sm-3" style="padding-right: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="font-size: 0.9em; padding-top: 0px;">OE
                              DE</label>
                          <input type="text" class="form-control input-sm text-right" name="oe_de_dinamica" 
                              x-model="AUTO_REFRACAO.history[index]['oe_de_dinamica']">
                      </div>
                      <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="font-size: 0.9em; padding-top: 0px;">DC</label>
                          <input type="text" class="form-control input-sm text-right" 
                              x-model="AUTO_REFRACAO.history[index]['oe_dc_dinamica']">
                      </div>
                      <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="  padding-top: 0px;">Eixo</label>
                          <input type="text" class="form-control input-sm text-right"
                              name="oe_eixo_dinamica" 
                              x-model="AUTO_REFRACAO.history[index]['oe_eixo_dinamica']">
                      </div>
                      <div class="col-sm-3" style=" padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="  padding-top: 0px;">Reflexo
                              OE:</label>
                          <input type="text" class="form-control input-sm text-right"
                              name="oe_reflexo_dinamica" 
                              x-model="AUTO_REFRACAO.history[index]['oe_reflexo_dinamica']">
                      </div>
                  </div>

                  <div class="form-group">
                      <div class="col-sm-6">
                          <h5>Auto Refração Estática:</h5>
                      </div>
                      <div class="col-sm-6">
                        
                        <template x-if="AUTO_REFRACAO.history[index]['receita_estatica']==1">
                            <label style="margin-top: 5px;">
                            <div class="checker"><span class="checked"><input type="checkbox" checked ></span></div> Receita 
                            </label> 
                        </template>
                        <template x-if="AUTO_REFRACAO.history[index]['receita_estatica'] != 1">
                            <label style="margin-top: 5px;">
                            <div class="checker"><span><input type="checkbox"  ></span></div> Receita 
                            </label> 
                        </template>
 
                      </div>
                  </div>

                  <div class="form-group" style="margin-bottom: 5px;">
                      <div class="col-sm-3" style="padding-right: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="font-size: 0.9em; padding-top: 0px;">OD
                              DE </label>
                          <input type="text" class="form-control input-sm text-right"
                              x-mask:dynamic="$money($input, ',')" name="od_de_estatica" 
                              x-model="AUTO_REFRACAO.history[index]['od_de_estatica']">
                      </div>
                      <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="font-size: 0.9em; padding-top: 0px;">DC</label>
                          <input type="text" class="form-control input-sm text-right"
                              x-mask:dynamic="$money($input, ',')" name="od_dc_estatica" 
                              x-model="AUTO_REFRACAO.history[index]['od_dc_estatica']">
                      </div>
                      <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="  padding-top: 0px;">Eixo</label>
                          <input type="text" class="form-control input-sm text-right"
                              x-mask:dynamic="$money($input, ',')" name="od_eixo_estatica" 
                              x-model="AUTO_REFRACAO.history[index]['od_eixo_estatica']">
                      </div>
                      <div class="col-sm-3" style=" padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="  padding-top: 0px;">Reflexo
                              OD:</label>
                          <input type="text" class="form-control input-sm text-right"
                              x-mask:dynamic="$money($input, ',')" name="od_reflexo_estatica" 
                              x-model="AUTO_REFRACAO.history[index]['od_reflexo_estatica']">
                      </div>
                  </div>

                  <div class="form-group" style="margin-bottom: 5px;">
                      <div class="col-sm-3" style="padding-right: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="font-size: 0.9em; padding-top: 0px;">OE
                              DE</label>
                          <input type="text" class="form-control input-sm text-right" name="oe_de_estatica"
                              x-mask:dynamic="$money($input, ',')" x-model="AUTO_REFRACAO.history[index]['oe_de_estatica']">
                      </div>
                      <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="font-size: 0.9em; padding-top: 0px;">DC</label>
                          <input type="text" class="form-control input-sm text-right" name="oe_dc_estatica"
                              x-mask:dynamic="$money($input, ',')" x-model="AUTO_REFRACAO.history[index]['oe_dc_estatica']">
                      </div>
                      <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="  padding-top: 0px;">Eixo</label>
                          <input type="text" class="form-control input-sm text-right"
                              name="oe_eixo_estatica" x-mask:dynamic="$money($input, ',')" 
                              x-model="AUTO_REFRACAO.history[index]['oe_eixo_estatica']">
                      </div>
                      <div class="col-sm-3" style=" padding-left: 5px;">
                          <label for="input-placeholder" class=" control-label"
                              style="  padding-top: 0px;">Reflexo
                              OE:</label>
                          <input type="text" class="form-control input-sm text-right"
                              name="oe_reflexo_estatica" x-mask:dynamic="$money($input, ',')" 
                              x-model="AUTO_REFRACAO.history[index]['oe_reflexo_estatica']">
                      </div>
                  </div>

                  <div class="form-group" style="margin-bottom: 5px;">
                      <div class="col-md-12">
                          <label for="input-placeholder" class=" control-label"
                              style="  padding-top: 0px;">Comentário:</label>
                          <textarea rows="3" class="form-control" name="comentario" x-model="AUTO_REFRACAO.history[index]['comentario']"></textarea>
                      </div>
                  </div>
              </fieldset>

              <div class="panel-footer col-md-12">
                  <div class="row">
                      <div class="col-md-6" style="text-align: left">
                          <div class="btn-group" role="group" aria-label="First group">

                              <button type="button" class="btn btn-default" @click="index = 0; "
                                  :disabled="index === 0"><i class="fa fa-fast-backward"></i></button>

                              <button type="button" class="btn btn-default"
                                  @click="index = Math.max(0, index - 1)   " :disabled="index === 0">
                                  <i class="fa fa-backward"></i>
                              </button>
                              <button type="button" class="btn btn-default"
                                  @click="index = Math.min(AUTO_REFRACAO.history.length - 1, index + 1)  "
                                  :disabled="index === AUTO_REFRACAO.history.length - 1">
                                  <i class="fa fa-forward"></i>
                              </button>
                              <button type="button" class="btn btn-default"
                                  @click="index = AUTO_REFRACAO.history.length - 1 " :disabled=" index === AUTO_REFRACAO.history.length - 1">
                                  <i class="fa fa-fast-forward"></i>
                              </button>
                              <button type="button" class="btn btn-default"
                                  @click="showHistory = !showHistory">
                                  <i class="fa fa-file-text"></i> Formulário
                              </button> 
                          </div>
                      </div>

                  </div>
              </div>
          </form>

      </div>
  </div>
</div>

<div class="col-md-2 col-sm-2 col-xs-12 col-lg-3">

  <div class="panel panel-white ui-sortable-handle">
      <div class="panel-heading">
          <div class="row">
              <div class="col-md-12">
                  <h3 class="panel-title">Histórico</h3>
                  <div class="panel-control">
                   
                    <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top"
                    title="" data-original-title="Historico Completo"><i class="icon-docs"
                        x-on:click="modalAutoRefracao({{$agendamento->cd_paciente}},null,)"></i></a>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="panel-group" role="tablist" aria-multiselectable="true">
    <template x-if="AUTO_REFRACAO.history.length > 0" >
      <div class="panel panel-default" style="border-radius: 0px;">
        <template x-for="item in AUTO_REFRACAO.history" >
         
          <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
            <h4 class="panel-title" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; " 
                x-html=" iconHistry + ' ' + FormatData(item.dt_exame) + ' -  { ' + item.cd_agendamento + ' } ' "
                x-on:click="modalAutoRefracao({{$agendamento->cd_paciente}},item.cd_auto_refracao)">
            </h4>
          </div>

        </template> 
      </div> 
    </template>
  </div>
</div>
