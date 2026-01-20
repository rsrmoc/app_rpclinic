<div class="col-md-7 col-sm-7 col-lg-6 col-xs-12  ">

  <div class="panel panel-white ui-sortable-handle">
    <div class="panel-heading">
      <div class="row">
        <div class="col-md-12">
          <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Tonometria Aplanação
          </h3>
          <div class="panel-control">
            <a href="javascript:void(0);" x-on:click="deleteTonometriaAplanacao(TONOMETRIA_APLANACAO.formData.cd_tonometria_aplanacao)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Exluir"><i class="icon-close"></i></a>
          </div>
        </div>
      </div>
    </div>
    <div class="panel-body" x-data="{ index: 0, showHistory: false}"  >
      <form class="form-horizontal" x-on:submit.prevent="storeTonometriaAplanacao" id="form_TONOMETRIA_APLANACAO" method="post" x-show="!showHistory" 
       >
        @csrf
        <div class="form-group " style="margin-bottom: 5px;">
          <div class="col-sm-12">
            <label for="input-help-block" class="control-label">Profissional: <span class="red normal">*</span></label>
            <select class="form-control " name="cd_profissional" required style="width: 100%;">
              <option value="{{ $agendamento->cd_profissional }}">{{ $agendamento->profissional->nm_profissional }}</option>
            </select>
          </div>
        </div>

        <div class="form-group  " style="margin-bottom: 5px;">
          <div class="col-sm-4" style="padding-right: 5px;">
            <label for="input-Default " class="  control-label   ">Data do Exame: <span class="red normal">*</span></label>
            <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame" x-model="TONOMETRIA_APLANACAO.formData.dt_exame">
        
          </div>

          <div class="col-sm-4" style="  padding-left: 5px; padding-right: 5px;">
            <label for="input-Default" class="  control-label ">OD (mmHg):</label>
            <input type="text" class="form-control input-sm text-right" name="pressao_od" x-model="TONOMETRIA_APLANACAO.formData.pressao_od" x-mask:dynamic="$money($input, ',')">
        
          </div>

          <div class="col-sm-4" style="  padding-left: 5px; ">
            <label for="input-Default" class="  control-label ">OE (mmHg):</label>
            <input type="text" class="form-control input-sm text-right" name="pressao_oe" x-model="TONOMETRIA_APLANACAO.formData.pressao_oe" x-mask:dynamic="$money($input, ',')">
           
          </div>

        </div>

        <div class="form-group " style=" ">
          <div class="col-sm-12" style=" ">
            <label for="input-help-block" class="control-label">Equipamento:</label>
            <select class="form-control " name="cd_equipamento"  id="cd_equipamento" style="width: 100%" >
              <option value="">SELECIONE</option> 
              @foreach($tabelas['equipamento'] as $item)
              <option value="{{$item->cd_equipamento}}">{{$item->nm_equipamento}}</option>
              @endforeach 
            </select>
          
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-md-12">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Comentário:</label>
            <textarea rows="3" class="form-control" name="obs" x-model="TONOMETRIA_APLANACAO.formData.obs"></textarea>
          </div>
        </div>

        <div class="panel-footer col-md-12">

          <div class="row">
            <div class="col-md-7">
              <button type="submit" class="btn btn-success" x-html="buttonSalvar" x-bind:disabled="buttonDisabled"> </button>
              <button type="reset" id="reset-button" class="btn btn-default" style="display:none;"><i class="fa fa-ban"></i> Limpar</button>

              <button class="btn btn-default" @click="showHistory = !showHistory" type="button">
                <i class="fa fa-history"></i> Histórico
              </button>
            </div>
            <div class="col-md-5" style="text-align: right">
              
            </div>

          </div>

        </div>

      </form>

      <form class="form-horizontal" x-show="showHistory">
        <fieldset disabled>
          <div class="form-group " style="margin-bottom: 5px;">
            <div class="col-sm-12">
              <label for="input-help-block" class="control-label">Profissional: <span class="red normal">*</span></label> 
              <input type="text" class="form-control input-sm text-left" name="dt_exame" value="{{ $agendamento->profissional->nm_profissional }}">
            </div>
          </div>

          <div class="form-group  " style="margin-bottom: 5px;">
            <div class="col-sm-4" style="padding-right: 5px;">
              <label for="input-Default " class="  control-label   ">Data do Exame:</label>
              <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame" x-model="TONOMETRIA_APLANACAO.history[index].dt_exame"> 
            </div>

            <div class="col-sm-4" style="  padding-left: 5px; padding-right: 5px;">
              <label for="input-Default" class="  control-label ">OD (mmHg):</label>
              <input type="text" class="form-control input-sm text-right"  name="pressao_od" x-model="TONOMETRIA_APLANACAO.history[index].pressao_od"> 
            </div>

            <div class="col-sm-4" style="  padding-left: 5px; ">
              <label for="input-Default" class="  control-label ">OE (mmHg):</label>
              <input type="text" class="form-control input-sm text-right" name="pressao_oe" x-model="TONOMETRIA_APLANACAO.history[index].pressao_oe"> 
            </div>

          </div>

          <div class="form-group " style=" ">
            <div class="col-sm-12" style=" ">
              <label for="input-help-block" class="control-label">Equipamento:</label>
              <input type="text" class="form-control input-sm text-left"  x-model="TONOMETRIA_APLANACAO.history[index].nm_equipamento"> 
              
             
            </div>
          </div>

          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-md-12">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Comentário:</label>
              <textarea rows="3" class="form-control" name="obs" x-model="TONOMETRIA_APLANACAO.history[index].obs"></textarea>
            </div>
          </div>

         
        </fieldset>
        <div class="panel-footer col-md-12">
          <div class="row">
            <div class="col-md-12" style="text-align: left">
              <div class="btn-group" role="group" aria-label="First group">

                <button type="button" class="btn btn-default" @click="index = 0" :disabled="index === 0"><i class="fa fa-fast-backward"></i></button>

                <button type="button" class="btn btn-default" @click="index = Math.max(0, index - 1)" :disabled="index === 0">
                  <i class="fa fa-backward"></i>
                </button>
                <button type="button" class="btn btn-default" @click="index = Math.min(TONOMETRIA_APLANACAO.history.length - 1, index + 1)" :disabled="index === TONOMETRIA_APLANACAO.history.length - 1">
                  <i class="fa fa-forward"></i>
                </button>
                <button type="button" class="btn btn-default" @click="index = TONOMETRIA_APLANACAO.history.length - 1" :disabled="index === TONOMETRIA_APLANACAO.history.length - 1">
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
                x-on:click="modalTonometriaAplanacao({{$agendamento->cd_paciente}},null)"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="panel-group" role="tablist" aria-multiselectable="true">


    <template x-if="TONOMETRIA_APLANACAO.history.length > 0" >
      <div class="panel panel-default" style="border-radius: 0px;">
        <template x-for="item in TONOMETRIA_APLANACAO.history" >
         
          <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
            <h4 class="panel-title" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; " 
                x-html=" iconHistry + ' ' + FormatData(item.dt_exame)+ ' -  { ' + item.cd_agendamento + ' } ' "
                x-on:click="modalTonometriaAplanacao({{$agendamento->cd_paciente}},item.cd_tonometria_aplanacao)">
            </h4>
          </div>

        </template> 
      </div> 
    </template>
 
  </div>
</div>
