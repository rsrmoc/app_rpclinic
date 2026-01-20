<div class="col-md-7 col-sm-7 col-lg-6 col-xs-12  ">
    <style>
      .select2-container--default .select2-selection--multiple .select2-selection__choice__remove { 
          border-right: 0px; 
          padding-left: 5px;
      }
      .select2-container--default .select2-selection--multiple .select2-selection__choice { 
          padding-right: 5px; 
      }
    </style>
    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Reserva de
                        Cirúrgica
                    </h3>
                    <div class="panel-control">
                        <a href="javascript:void(0);" data-toggle="tooltip"
                            x-on:click="deleteReservaCirurgia(RESERVA.formData.cd_reserva_cirurgia)"
                            data-placement="top" title="" data-original-title="Exluir"><i
                                class="icon-close"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body" x-data="{ index: 0, showHistory: false }">
            <form class="form-horizontal" x-on:submit.prevent="storeReservaCirurgia" id="form_RESERVA_CIRURGIA"
                method="post" x-show="!showHistory">
                @csrf
                <div class="form-group " style="margin-bottom: 5px;">
                    <div class="col-sm-12">
                        <label for="input-help-block" class="control-label">Solicitante:</label>
                        <select class="form-control " name="cd_profissional" style="width: 100%;">
                            <option value="{{ $agendamento->cd_profissional }}">
                                {{ $agendamento->profissional->nm_profissional }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group " style="margin-bottom: 5px;">
                    <div class="col-sm-12">
                        <label for="input-help-block" class="control-label">Cirurgia:</label>
                        <select class="form-control " name="cd_cirurgia" id="cd_cirurgia"  style="width: 100%;">
                            <option value="">SELECIONE</option>
                            @foreach ($tabelas['cirurgia'] as $key => $val)
                                <option value="{{ $val->cd_exame }}">{{ $val->nm_exame }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group " style="margin-bottom: 5px;">
                    <div class="col-sm-12">
                        <label for="input-help-block" class="control-label">Cirurgião:</label>
                        <select class="form-control " name="cd_cirurgiao" id="cd_cirurgiao" style="width: 100%;">
                            <option value="">SELECIONE</option>
                            @foreach ($tabelas['profissional'] as $key => $val)
                                <option value="{{ $val->cd_profissional }}">{{ $val->nm_profissional }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

          


                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="col-sm-12">
                        <label for="input-help-block" class="control-label">OPMEs:</label> 
                        <select class="js-states form-control" multiple="multiple" id="opme" name="opme[]" tabindex="-1"
                            style="display: none; width: 100%; min-height: 40px;"> 
                            @foreach ($tabelas['opme'] as $key => $val)
                              <option value="{{ $val->cd_produto }}">{{ $val->nm_produto }}</option> 
                            @endforeach 
                        </select>
                     
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label"
                            style="  padding-top: 0px;">Comentário:</label>
                        <textarea rows="4" class="form-control" name="comentarios" x-model="RESERVA.formData.comentarios"> 
                        </textarea>
                    </div>
                </div>

                <div class="panel-footer col-md-12">
                    <div class="row">
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-success" x-html="buttonSalvar"
                                x-bind:disabled="buttonDisabled"> </button>
                            <button type="reset" id="reset-button" class="btn btn-default" style="display:none;"><i
                                    class="fa fa-ban"></i> Limpar</button>

                            <button class="btn btn-default" type="button" @click="showHistory = !showHistory">
                                <i class="fa fa-history"></i> Histórico
                            </button>
                        </div>


                    </div>

                </div>
            </form>

            <form class="form-horizontal" x-show="showHistory">
                <fieldset disabled>
                    <div class="form-group " style="margin-bottom: 5px;">
                        <div class="col-sm-12">
                            <label for="input-help-block" class="control-label">Profissional:</label> 
                            <input type="text" class="form-control "  value="{{ $agendamento->profissional->nm_profissional }}" > 
                        </div>
                    </div>

                    <div class="form-group " style="margin-bottom: 5px;">
                        <div class="col-sm-12">
                            <label for="input-help-block" class="control-label">Cirurgia:</label>
                            <input type="text" class="form-control "  x-model="RESERVA.history[index].cirurgia?.nm_exame" >  
                        </div>
                    </div>

                    <div class="form-group " style="margin-bottom: 5px;">
                        <div class="col-sm-12">
                            <label for="input-help-block" class="control-label">Cirurgião:</label>
                            <input type="text" class="form-control "  x-model="RESERVA.history[index].cirurgiao?.nm_profissional" > 
                        </div>
                    </div>

                    <div class="form-group " style="margin-bottom: 5px;">
                        <div class="col-sm-12">
                            <label for="input-help-block" class="control-label">OPMEs:</label>
                            <div style=" width: 100%; min-height: 60px;" class="form-control ">  
                                  <template x-for="item in RESERVA.history[index].opme">
                                    <span class="label label-default" style="padding: 4px; margin-right: 5px; color: #4E5E6A"" x-html="item.produtos.nm_produto"></span>
                                  </template>
                                    
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 5px;">
                        <div class="col-md-12">
                            <label for="input-placeholder" class=" control-label"
                                style="  padding-top: 0px;">Comentário:</label>
                            <textarea rows="4" class="form-control" name="comentarios" x-model="RESERVA.history[index].comentarios">{{ old('comentario') }}</textarea>
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
                                    @click="index = Math.min(RESERVA.history.length - 1, index + 1)"
                                    :disabled="index === RESERVA.history.length - 1">
                                    <i class="fa fa-forward"></i>
                                </button>
                                <button type="button" class="btn btn-default" @click="index = RESERVA.history.length - 1"
                                    :disabled="index === RESERVA.history.length - 1">
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
                          x-on:click="modalReservaCirurgia({{$agendamento->cd_paciente}},null)"></i></a>
 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-group" role="tablist" aria-multiselectable="true">


      <template x-if="RESERVA.history.length > 0" >
        <div class="panel panel-default" style="border-radius: 0px;">
          
          <template x-for="item in RESERVA.history" >
           
            <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
              <h4 class="panel-title" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; " 
                  x-html=" iconHistry + ' ' + FormatData(item.created_data)+ ' -  { ' + item.cd_agendamento + ' } ' "
                  x-on:click="modalReservaCirurgia({{$agendamento->cd_paciente}},item.cd_reserva_cirurgia)">
              </h4>
            </div>
  
          </template> 
  
        </div> 
      </template>

   
    </div>
</div>
