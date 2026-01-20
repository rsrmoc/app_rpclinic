<div class="col-md-7 col-sm-7 col-xs-12 col-lg-6">

  <div class="panel panel-white ui-sortable-handle">
    <div class="panel-heading">
      <div class="row">
        <div class="col-md-12">
          <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Anamnese Inicial
          </h3>
          <div class="panel-control">

            <a href="javascript:void(0);" x-on:click="deleteAnamnese(ANAMNESE.formData.cd_anamnese)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Exluir"><i class="icon-close"></i></a>
 

          </div>

        </div>
      </div>
    </div>
   
    <div class="panel-body" x-data="{ index: 0, showHistory: false}" >
      <form class="form-horizontal" x-on:submit.prevent="storeAnamnese" id="form_ANAMNESE" method="post" x-show="!showHistory" x-data="{ 
                    profissional: '{{ $agendamento->cd_profissional }}',
                    profissionalNome: '{{ $agendamento->profissional->nm_profissional }}'
                }">
        @csrf
        <div class="form-group" style="margin-bottom: 5px;">
         
          <div class="col-sm-8">
            <label for="input-help-block" class="control-label">Profissional: <span class="red normal">*</span></label>
            <select class="form-control " name="cd_profissional" required style="width: 100%">
              <option value="{{ $agendamento->cd_profissional }}">{{ $agendamento->profissional->nm_profissional }}</option>
            </select>
          </div>
          <div class="col-sm-4" style="padding-right: 5px;">
            <label for="input-Default" class="control-label">Data da Anamnese: <span class="red normal">*</span></label>
            <input type="datetime-local" class="form-control input-sm text-center" readonly name="dt_anamnese" x-model="ANAMNESE.formData.dt_anamnese">
          </div>
        </div>

 

        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-md-12">
            <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Motivo da consulta:</label>
            <textarea rows="3" class="form-control" name="motivo" x-model="ANAMNESE.formData.motivo"></textarea>
          </div>
        </div>
        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-md-12">
            <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Historia Oftalmológica prévia:</label>
            <textarea rows="3" class="form-control" name="historia" x-model="ANAMNESE.formData.historia"></textarea>
          </div>
        </div>
        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-md-12">
            <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Medicamentos:</label>
            <textarea rows="3" class="form-control" name="medicamentos" x-model="ANAMNESE.formData.medicamentos"></textarea>
          </div>
        </div>
        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-md-12">
            <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Alergias:</label>
            <textarea rows="3" class="form-control" name="alergias" x-model="ANAMNESE.formData.alergias"></textarea>
          </div>
        </div>
        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-md-12">
            <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Conduta:</label>
            <textarea rows="3" class="form-control" name="conduta" x-model="ANAMNESE.formData.conduta"></textarea>
          </div>
        </div>

        <div class="panel-footer col-md-12">

          <div class="row">
            <div class="col-md-6">
              <button type="submit" class="btn btn-success" x-html="buttonSalvar" x-bind:disabled="buttonDisabled"> </button>
              <button type="reset" id="reset-button" class="btn btn-default" style="display:none;"><i class="fa fa-ban"></i> Limpar</button>

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
          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-sm-4" style="padding-right: 5px;">
              <label for="input-Default" class="control-label">Data da Anamnese:</label>
              <input type="datetime-local" class="form-control input-sm text-center" name="dt_anamnese" x-model="ANAMNESE.history[index].dt_anamnese"> 
            </div> 
            <div class="col-sm-8">
              <label for="input-help-block" class="control-label">Profissional: <span class="red normal">*</span></label>
              <input type="text" class="form-control input-sm text-left"   value="{{ $agendamento->profissional->nm_profissional }}"> 
             
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-md-12">
              <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Motivo da consulta:</label>
              <textarea rows="3" class="form-control" name="motivo" x-model="ANAMNESE.history[index].motivo"></textarea>
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-md-12">
              <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Historia Oftalmológica prévia:</label>
              <textarea rows="3" class="form-control" name="historia" x-model="ANAMNESE.history[index].historia"></textarea>
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-md-12">
              <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Medicamentos:</label>
              <textarea rows="3" class="form-control" name="medicamentos" x-model="ANAMNESE.history[index].medicamentos"></textarea>
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-md-12">
              <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Alergias:</label>
              <textarea rows="3" class="form-control" name="alergias" x-model="ANAMNESE.history[index].alergias"></textarea>
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-md-12">
              <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Conduta:</label>
              <textarea rows="3" class="form-control" name="conduta" x-model="ANAMNESE.history[index].conduta"></textarea>
            </div>
          </div>
        </fieldset>

        <div class="panel-footer col-md-12">
          <div class="row">
            <div class="col-md-6" style="text-align: left">
              <div class="btn-group" role="group" aria-label="First group">

                <button type="button" class="btn btn-default" @click="index = 0" :disabled="index === 0"><i class="fa fa-fast-backward"></i></button>

                <button type="button" class="btn btn-default" @click="index = Math.max(0, index - 1)" :disabled="index === 0">
                  <i class="fa fa-backward"></i>
                </button>
                <button type="button" class="btn btn-default" @click="index = Math.min(ANAMNESE.history.length - 1, index + 1)" :disabled="index === ANAMNESE.history.length - 1">
                  <i class="fa fa-forward"></i>
                </button>
                <button type="button" class="btn btn-default" @click="index = ANAMNESE.history.length - 1" :disabled="index === ANAMNESE.history.length - 1">
                  <i class="fa fa-fast-forward"></i>
                </button>
                <button type="button" class="btn btn-default" @click="showHistory = !showHistory">
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
                title="" data-original-title="Histórico Completo"><i class="icon-docs"
                    x-on:click="modalAnamnese({{$agendamento->cd_paciente}},null)"></i></a>
              </div>
        </div> 
      </div>
    </div>
  </div>
  <div class="panel-group" role="tablist" aria-multiselectable="true">
    <template x-if="ANAMNESE.history.length > 0" >
      <div class="panel panel-default" style="    border-radius: 0px;"> 
        <template x-for="item in ANAMNESE.history" > 
          <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
            <h4 class="panel-title" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; " 
                x-html=" iconHistry + ' ' + FormatData(item.dt_anamnese)+ ' -  { ' + item.cd_agendamento + ' } ' "
                x-on:click="modalAnamnese({{$agendamento->cd_paciente}},item.cd_anamnese)">
            </h4>
          </div> 
        </template>  
      </div> 
    </template> 

  </div>
</div>
