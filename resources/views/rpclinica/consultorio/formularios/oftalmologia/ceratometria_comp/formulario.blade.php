<style>
    .formFormulario>.control-label {
        padding-top: 0px;
    }
</style>
<div class="col-md-7 col-sm-7 col-xs-12 col-lg-6">

    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Ceratoscopia Computadorizada
                    </h3>

                </div>
            </div>
        </div>
        <div class="panel-body">

            <form class="form-horizontal" x-on:submit.prevent="storeCeratometriaComp" id="form_CERATOMETRIA_COMP"
                method="post">
                @csrf
                <div class="form-group" style="margin-bottom: 5px; display: none">
                    <label for="input-help-block" class="control-label">Profissional: <span
                            class="red normal">*</span></label>
                    <select class="form-control " name="cd_profissional" required>
                        <option value="{{ $agendamento->cd_profissional }}">{{ $agendamento->nm_usuario }}</option>
                    </select>
                </div>
                <div class="form-group " style="margin-bottom: 5px;">
                    <div class="col-sm-10" style="padding-right: 5px;">
                        <label for="input-help-block" class="control-label">Arquivo:</label>
                        <input type="file" class="form-control" name="image"> 
                    </div>
                    <div class="col-sm-2">
                        <label for="input-help-block" class="control-label">&nbsp;</label>
                        <input type="submit" class="btn btn-success" style="width: 100%;" value="Salvar">
                    </div>

                </div>

            </form>


            <template x-for="img in CERATOMETRIA_COMP.formData.array_img"> 

              <div class="panel panel-white">
                <div class="panel-heading">
                    <div class="col-md-12">
                        <h3 class="panel-title" style="font-style: italic; font-weight: 300;" x-html="'<b>'+img.usuario+'</b><br>'+FormatData(img.data)">   </h3>
                        <div class="panel-control">
                            <a href="javascript:void(0);" data-toggle="tooltip"
                                x-on:click="deleteCeratometriaComp(img.cd_img_formulario)"
                                data-placement="top" title="" data-original-title="Exluir"><i
                                    class="icon-close"></i></a>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="text-align: center;  margin-top: 20px;"> 
                    <img class="img-fluid" style="max-width: 100%;" x-bind:src="img.conteudo_img" >
                </div>
              </div>

            </template>

        
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
                          x-on:click="modalCompletoCeratometriaComp({{$agendamento->cd_paciente}},null)"></i></a>
                    </div>
              </div>
 
            </div>
        </div>
    </div>

    <div class="panel-group" role="tablist" aria-multiselectable="true">
 

     

            <template x-if="CERATOMETRIA_COMP.history.length > 0" >
              <div class="panel panel-default" style="border-radius: 0px;">
                <template x-for="item in CERATOMETRIA_COMP.history" >
                 
                  <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
                    <h4 class="panel-title" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; " 
                        x-html=" iconHistry + ' ' + FormatData(item.dt_exame) + ' -  { ' + item.cd_agendamento + ' } ' "
                        x-on:click="modalCompletoCeratometriaComp({{$agendamento->cd_paciente}},item.cd_img_formulario)">
                    </h4>
                  </div>
        
                </template> 
              </div> 
            </template>

             

    </div>

</div>
