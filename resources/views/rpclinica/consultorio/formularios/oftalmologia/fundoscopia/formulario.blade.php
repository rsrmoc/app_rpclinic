<div class="col-md-7 col-sm-7 col-xs-12 col-lg-6">

    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Fundoscopia
                    </h3>
                    <div class="panel-control">
                        <a href="javascript:void(0);"
                            x-on:click="deleteFundoscopia(FUNDOSCOPIA.formData.cd_fundoscopia)" data-toggle="tooltip"
                            data-placement="top" title="" data-original-title="Exluir"><i
                                class="icon-close"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active" class=""><a href="#tabCadExa"
                            style="border-bottom:0px; margin-right: 0px;" role="tab" data-toggle="tab"
                            aria-expanded="false">Cadastro do Exame</a></li>
                    <li role="presentation"><a href="#tabFotoExa" style="border-bottom:0px; margin-right: 0px;"
                            class="OpTab" role="tab" data-toggle="tab" aria-expanded="true">Imagens do Exame</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane fade  active in" id="tabCadExa" x-data="{ index: 0, showHistory: false }">

                        <form class="form-horizontal" x-on:submit.prevent="storeFundoscopia" x-show="!showHistory"  id="form_FUNDOSCOPIA"  method="post"   >
                            @csrf
                            <div class="form-group " style="margin-bottom: 5px;">
                                <div class="col-sm-12" style=" ">
                                    <label for="input-help-block" class="control-label">Profissional:</label>
                                    <select class="form-control " name="cd_profissional" required style="width: 100%;">
                                        <option value="{{ $agendamento->cd_profissional }}">
                                            {{ $agendamento->profissional->nm_profissional }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group  " style="margin-bottom: 5px;">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <label for="input-Default " class="  control-label   ">Data do Exame: <span
                                      class="red normal">*</span></label>
                                    <input type="datetime-local" class="form-control input-sm text-center"
                                        name="dt_exame" x-model="FUNDOSCOPIA.formData.dt_exame">
                                </div>

                                <div class="col-sm-4" style="  padding-left: 5px;">
                                    <label for="input-Default" class="  control-label ">Data da Liberação:</label>
                                    <input type="datetime-local" class="form-control input-sm text-center"
                                        name="dt_liberacao" x-model="FUNDOSCOPIA.formData.dt_liberacao">
                                </div>
                            </div>

                            <div class="line" style="padding-bottom: 10px;">
                                <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0; margin-left: 24px">
                                    <label style="padding: 0" id="label_midriase_od">
                                        <input type="checkbox" name="midriase_od" id="midriase_od">
                                        Midríase OD
                                    </label>
                                </div>
                                <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                    <label style="padding: 0" id="label_normal_od">
                                        <input type="checkbox" name="normal_od" id="normal_od">
                                        Normal OD
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-md-12">
                                    <label for="input-placeholder" class=" control-label"
                                        style="  padding-top: 0px;">Fundoscopia OD:</label>
                                    <textarea rows="3" class="form-control" name="od" x-model="FUNDOSCOPIA.formData.od"></textarea>
                                </div>
                            </div>

                            <div class="line" style="padding-bottom: 10px;">
                                <div class="checkbox m-r-md"
                                    style="margin-top: 0; margin-bottom: 0; margin-left: 24px">
                                    <label style="padding: 0" id="label_midriase_oe">
                                        <input type="checkbox" name="midriase_oe" id="midriase_oe">
                                        Midríase OE
                                    </label>
                                </div>
                                <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                    <label style="padding: 0" id="label_normal_oe">
                                        <input type="checkbox" name="normal_oe" id="normal_oe">
                                        Normal OE
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-md-12">
                                    <label for="input-placeholder" class=" control-label"
                                        style="  padding-top: 0px;">Fundoscopia OE:</label>
                                    <textarea rows="3" class="form-control" name="oe" x-model="FUNDOSCOPIA.formData.oe"></textarea>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-md-12">
                                    <label for="input-placeholder" class=" control-label"
                                        style="  padding-top: 0px;">Comentário:</label>
                                    <textarea rows="3" class="form-control" name="obs" x-model="FUNDOSCOPIA.formData.obs"></textarea>
                                </div>
                            </div>

                            <div class="panel-footer col-md-12">

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success" x-html="buttonSalvar"
                                            x-bind:disabled="buttonDisabled"> </button>
                                        <button type="reset" id="reset-button" class="btn btn-default"
                                            style="display:none;"><i class="fa fa-ban"></i> Limpar</button>

                                        <button class="btn btn-default" type="button"
                                            @click="showHistory = !showHistory">
                                            <i class="fa fa-history"></i> Histórico
                                        </button>
                                    </div>


                                </div>

                            </div>
                        </form>

                        <form class="form-horizontal" x-show="showHistory">
                            <fieldset disabled>
                                <div class="form-group " style="margin-bottom: 5px;">
                                    <div class="col-sm-12" style=" ">
                                        <label for="input-help-block" class="control-label">Profissional:</label> 
                                        <input type="text" class="form-control input-sm text-left"
                                        name="dt_exame" value="{{ $agendamento->profissional->nm_profissional }}"> 
                                    </div>
                                </div>

                                <div class="form-group  " style="margin-bottom: 5px;">
                                    <div class="col-sm-4" style="padding-right: 5px;">
                                        <label for="input-Default " class="  control-label   ">Data do Exame:</label>
                                        <input type="datetime-local" class="form-control input-sm text-center"
                                            name="dt_exame" x-model="FUNDOSCOPIA.history[index].dt_exame"> 
                                    </div>

                                    <div class="col-sm-4" style="  padding-left: 5px;">
                                        <label for="input-Default" class="  control-label ">Data da Liberação:</label>
                                        <input type="datetime-local" class="form-control input-sm text-center"
                                            name="dt_liberacao" x-model="FUNDOSCOPIA.history[index].dt_liberacao"> 
                                    </div>
                                </div>

                                <div class="line" style="padding-bottom: 10px;">
                                    <div class="checkbox m-r-md"
                                        style="margin-top: 0; margin-bottom: 0; margin-left: 24px">

                                        <template x-if="FUNDOSCOPIA.history[index].midriase_od ==1">
                                            <label style="margin-top: 5px;">
                                            <div class="checker"><span class="checked"><input type="checkbox" checked ></span></div> Midríase OD 
                                            </label> 
                                        </template>
                                        <template x-if="FUNDOSCOPIA.history[index].midriase_od  != 1">
                                            <label style="margin-top: 5px;">
                                            <div class="checker"><span><input type="checkbox"  ></span></div> Midríase OD 
                                            </label> 
                                        </template> 

                                    </div>
                                    <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">

                                        <template x-if="FUNDOSCOPIA.history[index].normal_od ==1">
                                            <label style="margin-top: 5px;">
                                            <div class="checker"><span class="checked"><input type="checkbox" checked ></span></div> Normal OD 
                                            </label> 
                                        </template>
                                        <template x-if="FUNDOSCOPIA.history[index].normal_od  != 1">
                                            <label style="margin-top: 5px;">
                                            <div class="checker"><span><input type="checkbox"  ></span></div> Normal OD 
                                            </label> 
                                        </template> 
 
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom: 5px;">
                                    <div class="col-md-12">
                                        <label for="input-placeholder" class=" control-label"
                                            style="  padding-top: 0px;">Fundoscopia OD:</label>
                                        <textarea rows="3" class="form-control" name="od" x-model="FUNDOSCOPIA.history[index].od"></textarea>
                                    </div>
                                </div>

                                <div class="line" style="padding-bottom: 10px;">
                                    <div class="checkbox m-r-md"
                                        style="margin-top: 0; margin-bottom: 0; margin-left: 24px">

                                        <template x-if="FUNDOSCOPIA.history[index].midriase_oe ==1">
                                            <label style="margin-top: 5px;">
                                            <div class="checker"><span class="checked"><input type="checkbox" checked ></span></div> Midríase OE
                                            </label> 
                                        </template>
                                        <template x-if="FUNDOSCOPIA.history[index].midriase_oe  != 1">
                                            <label style="margin-top: 5px;">
                                            <div class="checker"><span><input type="checkbox"  ></span></div> Midríase OE
                                            </label> 
                                        </template> 
  
                                    </div>
                                    <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">

                                        <template x-if="FUNDOSCOPIA.history[index].normal_oe ==1">
                                            <label style="margin-top: 5px;">
                                            <div class="checker"><span class="checked"><input type="checkbox" checked ></span></div> Midríase OD
                                            </label> 
                                        </template>
                                        <template x-if="FUNDOSCOPIA.history[index].normal_oe  != 1">
                                            <label style="margin-top: 5px;">
                                            <div class="checker"><span><input type="checkbox"  ></span></div> Midríase OD
                                            </label> 
                                        </template> 
 
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom: 5px;">
                                    <div class="col-md-12">
                                        <label for="input-placeholder" class=" control-label"
                                            style="  padding-top: 0px;">Fundoscopia OE:</label>
                                        <textarea rows="3" class="form-control" name="oe" x-model="FUNDOSCOPIA.history[index].oe"></textarea>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom: 5px;">
                                    <div class="col-md-12">
                                        <label for="input-placeholder" class=" control-label"
                                            style="  padding-top: 0px;">Comentário:</label>
                                        <textarea rows="3" class="form-control" name="obs" x-model="FUNDOSCOPIA.history[index].obs"></textarea>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="panel-footer col-md-12">
                                <div class="row">
                                    <div class="col-md-12" style="text-align: left">
                                        <div class="btn-group" role="group" aria-label="First group">

                                            <button type="button" class="btn btn-default" @click="index = 0"
                                                :disabled="index === 0"><i class="fa fa-fast-backward"></i></button>

                                            <button type="button" class="btn btn-default"
                                                @click="index = Math.max(0, index - 1)" :disabled="index === 0">
                                                <i class="fa fa-backward"></i>
                                            </button>
                                            <button type="button" class="btn btn-default"
                                                @click="index = Math.min(FUNDOSCOPIA.history.length - 1, index + 1)"
                                                :disabled="index === FUNDOSCOPIA.history.length - 1">
                                                <i class="fa fa-forward"></i>
                                            </button>
                                            <button type="button" class="btn btn-default"
                                                @click="index = FUNDOSCOPIA.history.length - 1"
                                                :disabled="index === FUNDOSCOPIA.history.length - 1">
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

                    <div role="tabpanel" class="tab-pane fade" id="tabFotoExa">
                        <form class="form-horizontal" x-on:submit.prevent="storeFundoscopiaImg"
                            id="form_FUNDOSCOPIA_IMG" method="post">
                            @csrf

                            <div class="form-group " style="margin-bottom: 5px;">
                                <div class="col-sm-8" style="padding-right: 5px;">
                                    <label for="input-help-block" class="control-label">Arquivo:</label>
                                    <input type="file" class="form-control" name="image">
                                </div>
                                <div class="col-sm-4">
                                    <label for="input-help-block" class="control-label">&nbsp;</label>
                                    <input type="submit" class="btn btn-success" style="width: 100%;"
                                        value="Salvar">
                                </div>

                            </div>
                        </form>

                        <template x-for="img in FUNDOSCOPIA.formDataImg">

                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <div class="col-md-12">
                                        <h3 class="panel-title" style="font-style: italic; font-weight: 300;"
                                            x-html="'<b>'+img.usuario+'</b><br>'+FormatData(img.data)"> </h3>
                                        <div class="panel-control">
                                            <a href="javascript:void(0);" data-toggle="tooltip"
                                                x-on:click="deleteFundoscopiaImg(img.cd_img_formulario)"
                                                data-placement="top" title="" data-original-title="Exluir"><i
                                                    class="icon-close"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body" style="text-align: center;  margin-top: 20px;">
                                    <img class="img-fluid" style="max-width: 100%;" x-bind:src="img.conteudo_img">
                                </div>
                            </div>

                        </template>

                    </div>
                    
                </div>
            </div>
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
                      title="" data-original-title="Histórico Completo"><i class="icon-docs"
                          x-on:click="modalFundoscopia({{$agendamento->cd_paciente}},null)"></i></a>
 
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="panel-group" role="tablist" aria-multiselectable="true">


      <template x-if="FUNDOSCOPIA.history.length > 0" >
        <div class="panel panel-default" style="border-radius: 0px;">
          <template x-for="item in FUNDOSCOPIA.history" >
           
            <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
              <h4 class="panel-title" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; " 
                  x-html=" iconHistry + ' ' + FormatData(item.dt_exame)+ ' -  { ' + item.cd_agendamento + ' } ' "
                  x-on:click="modalFundoscopia({{$agendamento->cd_paciente}},item.cd_agendamento)">
              </h4>
            </div>
  
          </template> 
        </div> 
      </template>
 
        
    </div>
</div>
