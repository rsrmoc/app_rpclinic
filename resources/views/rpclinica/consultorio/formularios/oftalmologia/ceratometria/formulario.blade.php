<div class="col-md-7 col-sm-7 col-xs-12 col-lg-6">

    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                        Ceratometria
                    </h3>
                    <div class="panel-control">
                        <a href="javascript:void(0);"
                            x-on:click="() => {deleteCeratometria(CERATOMETRIA.formData.cd_ceratometria);}"
                            data-toggle="tooltip" data-placement="top" title="" data-original-title="Exluir"><i
                                class="icon-close"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body" x-data="{ index: 0, showHistory: false }"  >
            
            <form class="form-horizontal" x-on:submit.prevent="storeCeratometria" id="form_CERATOMETRIA" method="post"
                x-show="!showHistory" x-data="{
                    formData: JSON.parse(''),
                    profissional: '{{ $agendamento->cd_profissional }}',
                    profissionalNome: '{{ $agendamento->profissional->nm_profissional }}'
                }">
                @csrf
                <div class="form-group ">
                    <div class="col-sm-12">
                        <label for="input-help-block" class="control-label">Profissional: <span
                                class="red normal">*</span></label>
                        <select class="form-control " name="cd_profissional" required style="width: 100%">
                            <option value="{{ $agendamento->cd_profissional }}">
                                {{ $agendamento->profissional->nm_profissional }}</option>
                        </select>
                    </div>
                </div>
            
               
                <div class="row">
                    <div class="col-sm-5">
                        <label for="input-Default" class="control-label">Data do Exame: <span
                            class="red normal">*</span></label>
                        <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame"
                            x-model="CERATOMETRIA.formData.dt_exame"> 
                    </div>

                    <div class="col-sm-5">
                        <label for="input-Default" class="  control-label ">Data da Liberação:</label>
                        <input type="datetime-local" class="form-control input-sm text-center" name="dt_liberacao"
                            x-model="CERATOMETRIA.formData.dt_liberacao"> 
                    </div>

                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6  ">
                        <div style="text-align: center;">
                            <label for="input-Default" class="control-label  "
                                style="font-weight: 600;   text-align: center; "><i class="fa fa-eye"
                                    style="font-size: 15px;"></i> Olho Direito
                            </label>
                        </div>
                        <div class="form-group  " style="margin-bottom: 5px;">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default " class="  control-label   ">Curva1:</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="od_curva1_ceratometria" x-model="CERATOMETRIA.formData.od_curva1_ceratometria"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4" style="  padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="  control-label ">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-center"
                                    name="od_curva1_milimetros" x-model="CERATOMETRIA.formData.od_curva1_milimetros"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4" style="  padding-left: 5px;">
                                <label for="input-Default" class="  control-label ">Eixo1:</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="od_eixo1_ceratometria" x-model="CERATOMETRIA.formData.od_eixo1_ceratometria"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>
                        </div>

                        <div class="form-group  " style="margin-bottom: 5px;">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default " class="  control-label   ">Curva2:</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="od_curva2_ceratometria" x-model="CERATOMETRIA.formData.od_curva2_ceratometria"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4" style="  padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="  control-label ">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="od_curva2_milimetros" x-model="CERATOMETRIA.formData.od_curva2_milimetros"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4" style="  padding-left: 5px;">
                                <label for="input-Default" class="  control-label ">Eixo2:</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="od_eixo2_ceratometria" x-model="CERATOMETRIA.formData.od_eixo2_ceratometria"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>
                        </div>

                        <div class="form-group  " style="margin-bottom: 5px;">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default " class="  control-label   ">Média:</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="od_media_ceratometria" x-model="CERATOMETRIA.formData.od_media_ceratometria"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4" style="  padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="  control-label ">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="od_media_milimetros" x-model="CERATOMETRIA.formData.od_media_milimetros"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>
                        </div>

                        <div class="form-group  " style="margin-bottom: 5px;">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default " class="  control-label   ">Cilíndro(-):</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="od_cilindro_neg" x-model="CERATOMETRIA.formData.od_cilindro_neg"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4 col-md-offset-4" style="  padding-left: 5px;">
                                <label for="input-Default" class="  control-label ">Eixo(-):</label>
                                <input type="text" class="form-control input-sm text-right" name="od_eixo_neg"
                                    x-model="CERATOMETRIA.formData.od_eixo_neg" x-mask:dynamic="$money($input, ',')"> 
                            </div>
                        </div>

                        <div class="form-group  " style="margin-bottom: 5px;">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default " class="  control-label   ">Cilíndro(+):</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="od_cilindro_pos" x-model="CERATOMETRIA.formData.od_cilindro_pos"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4 col-md-offset-4" style="  padding-left: 5px;">
                                <label for="input-Default" class="  control-label ">Eixo(+):</label>
                                <input type="text" class="form-control input-sm text-right" name="od_eixo_pos"
                                    x-model="CERATOMETRIA.formData.od_eixo_pos" x-mask:dynamic="$money($input, ',')"> 
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-6">
                        <div style="text-align: center;">
                            <label for="input-Default" class="control-label  "
                                style="font-weight: 600;   text-align: center; "><i class="fa fa-eye"
                                    style="font-size: 15px;"></i> Olho Esquerdo</label>
                        </div>
                        <div class="form-group  " style="margin-bottom: 5px;">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default " class="  control-label   ">Curva1:</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="oe_curva1_ceratometria" x-model="CERATOMETRIA.formData.oe_curva1_ceratometria"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4" style="  padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="  control-label ">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="oe_curva1_milimetros" x-model="CERATOMETRIA.formData.oe_curva1_milimetros"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4" style="  padding-left: 5px; ">
                                <label for="input-Default" class="  control-label ">Eixo1:</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="oe_eixo1_ceratometria" x-model="CERATOMETRIA.formData.oe_eixo1_ceratometria"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>
                        </div>

                        <div class="form-group  " style="margin-bottom: 5px;">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default " class="  control-label   ">Curva2:</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="oe_curva2_ceratometria" x-model="CERATOMETRIA.formData.oe_curva2_ceratometria"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4" style="  padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="  control-label ">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="oe_curva2_milimetros" x-model="CERATOMETRIA.formData.oe_curva2_milimetros"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4" style="  padding-left: 5px;">
                                <label for="input-Default" class="  control-label ">Eixo2:</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="oe_eixo2_ceratometria" x-model="CERATOMETRIA.formData.oe_eixo2_ceratometria"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>
                        </div>

                        <div class="form-group  " style="margin-bottom: 5px;">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default " class="  control-label   ">Média:</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="oe_media_ceratometria" x-model="CERATOMETRIA.formData.oe_media_ceratometria"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4" style="  padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="  control-label ">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="oe_media_milimetros" x-model="CERATOMETRIA.formData.oe_media_milimetros"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>
                        </div>

                        <div class="form-group  " style="margin-bottom: 5px;">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default " class="  control-label   ">Cilíndro(-):</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="oe_cilindro_neg" x-model="CERATOMETRIA.formData.oe_cilindro_neg"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4 col-md-offset-4" style="  padding-left: 5px;">
                                <label for="input-Default" class="  control-label ">Eixo(-):</label>
                                <input type="text" class="form-control input-sm text-right" name="oe_eixo_neg"
                                    x-model="CERATOMETRIA.formData.oe_eixo_neg" x-mask:dynamic="$money($input, ',')"> 
                            </div>
                        </div>

                        <div class="form-group  " style="margin-bottom: 5px;">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default " class="  control-label   ">Cilíndro(+):</label>
                                <input type="text" class="form-control input-sm text-right"
                                    name="oe_cilindro_pos" x-model="CERATOMETRIA.formData.oe_cilindro_pos"
                                    x-mask:dynamic="$money($input, ',')"> 
                            </div>

                            <div class="col-sm-4 col-md-offset-4" style="  padding-left: 5px;">
                                <label for="input-Default" class="  control-label ">Eixo(+):</label>
                                <input type="text" class="form-control input-sm text-right" name="oe_eixo_pos"
                                    x-model="CERATOMETRIA.formData.oe_eixo_pos" x-mask:dynamic="$money($input, ',')"> 
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label"
                            style="  padding-top: 0px;">Comentário:</label>
                        <textarea rows="3" class="form-control" name="obs" x-model="CERATOMETRIA.formData.obs"></textarea>
                    </div>
                </div>


                <div class="panel-footer col-md-12">

                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success" x-html="buttonSalvar"
                                x-bind:disabled="buttonDisabled"> </button>
                            <button type="reset" id="reset-button" class="btn btn-default"
                                style="display:none;"><i class="fa fa-ban"></i> Limpar</button>

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
                    <input type="hidden" name="tipo" value="Ceratometria">
                    <div class="form-group ">
                        <div class="col-sm-12">
                            <label for="input-help-block" class="control-label">Profissional:</label>
                            <input type="text" class="form-control input-sm text-left"  
                            x-model="CERATOMETRIA.history[index].profissional.nm_profissional"> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5">
                            <label for="input-Default" class="control-label">Data do Exame:</label>
                            <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame"
                                x-model="CERATOMETRIA.history[index].dt_exame">
                        </div>

                        <div class="col-sm-5">
                            <label for="input-Default" class="control-label">Data da Liberação:</label>
                            <input type="datetime-local" class="form-control input-sm text-center" name="dt_liberacao"
                                x-model="CERATOMETRIA.history[index].dt_liberacao">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <div style="text-align: center;">
                                <label for="input-Default" class="control-label"
                                    style="font-weight: 600; text-align: center;">
                                    <i class="fa fa-eye" style="font-size: 15px;"></i> Olho Direito
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <label for="input-Default" class="control-label">Curva1:</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="od_curva1_ceratometria" x-model="CERATOMETRIA.history[index].od_curva1_ceratometria">
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                    <label for="input-Default" class="control-label">&nbsp;</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="od_curva1_milimetros" x-model="CERATOMETRIA.history[index].od_curva1_milimetros">
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px;">
                                    <label for="input-Default" class="control-label">Eixo1:</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="od_eixo1_ceratometria" x-model="CERATOMETRIA.history[index].od_eixo1_ceratometria">
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <label for="input-Default" class="control-label">Curva2:</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="od_curva2_ceratometria" x-model="CERATOMETRIA.history[index].od_curva2_ceratometria">
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                    <label for="input-Default" class="control-label">&nbsp;</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="od_curva2_milimetros" x-model="CERATOMETRIA.history[index].od_curva2_milimetros">
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px;">
                                    <label for="input-Default" class="control-label">Eixo2:</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="od_eixo2_ceratometria" x-model="CERATOMETRIA.history[index].od_eixo2_ceratometria">
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <label for="input-Default" class="control-label">Média:</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="od_media_ceratometria" x-model="CERATOMETRIA.history[index].od_media_ceratometria">
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                    <label for="input-Default" class="control-label">&nbsp;</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="od_media_milimetros" x-model="CERATOMETRIA.history[index].od_media_milimetros">
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <label for="input-Default" class="control-label">Cilíndro(-):</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="od_cilindro_neg" x-model="CERATOMETRIA.history[index].od_cilindro_neg">
                                </div>
                                <div class="col-sm-4 col-md-offset-4" style="padding-left: 5px;">
                                    <label for="input-Default" class="control-label">Eixo(-):</label>
                                    <input type="text" class="form-control input-sm text-right" name="od_eixo_neg"
                                        x-model="CERATOMETRIA.history[index].od_eixo_neg">
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <label for="input-Default" class="control-label">Cilíndro(+):</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="od_cilindro_pos" x-model="CERATOMETRIA.history[index].od_cilindro_pos">
                                </div>
                                <div class="col-sm-4 col-md-offset-4" style="padding-left: 5px;">
                                    <label for="input-Default" class="control-label">Eixo(+):</label>
                                    <input type="text" class="form-control input-sm text-right" name="od_eixo_pos"
                                        x-model="CERATOMETRIA.history[index].od_eixo_pos">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div style="text-align: center;">
                                <label for="input-Default" class="control-label"
                                    style="font-weight: 600; text-align: center;">
                                    <i class="fa fa-eye" style="font-size: 15px;"></i> Olho Esquerdo
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <label for="input-Default" class="control-label">Curva1:</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="oe_curva1_ceratometria" x-model="CERATOMETRIA.history[index].oe_curva1_ceratometria">
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                    <label for="input-Default" class="control-label">&nbsp;</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="oe_curva1_milimetros" x-model="CERATOMETRIA.history[index].oe_curva1_milimetros">
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px;">
                                    <label for="input-Default" class="control-label">Eixo1:</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="oe_eixo1_ceratometria" x-model="CERATOMETRIA.history[index].oe_eixo1_ceratometria">
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <label for="input-Default" class="control-label">Curva2:</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="oe_curva2_ceratometria" x-model="CERATOMETRIA.history[index].oe_curva2_ceratometria">
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                    <label for="input-Default" class="control-label">&nbsp;</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="oe_curva2_milimetros" x-model="CERATOMETRIA.history[index].oe_curva2_milimetros">
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px;">
                                    <label for="input-Default" class="control-label">Eixo2:</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="oe_eixo2_ceratometria" x-model="CERATOMETRIA.history[index].oe_eixo2_ceratometria">
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <label for="input-Default" class="control-label">Média:</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="oe_media_ceratometria" x-model="CERATOMETRIA.history[index].oe_media_ceratometria">
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                    <label for="input-Default" class="control-label">&nbsp;</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="oe_media_milimetros" x-model="CERATOMETRIA.history[index].oe_media_milimetros">
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <label for="input-Default" class="control-label">Cilíndro(-):</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="oe_cilindro_neg" x-model="CERATOMETRIA.history[index].oe_cilindro_neg">
                                </div>
                                <div class="col-sm-4 col-md-offset-4" style="padding-left: 5px;">
                                    <label for="input-Default" class="control-label">Eixo(-):</label>
                                    <input type="text" class="form-control input-sm text-right" name="oe_eixo_neg"
                                        x-model="CERATOMETRIA.history[index].oe_eixo_neg">
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <label for="input-Default" class="control-label">Cilíndro(+):</label>
                                    <input type="text" class="form-control input-sm text-right"
                                        name="oe_cilindro_pos" x-model="CERATOMETRIA.history[index].oe_cilindro_pos">
                                </div>
                                <div class="col-sm-4 col-md-offset-4" style="padding-left: 5px;">
                                    <label for="input-Default" class="control-label">Eixo(+):</label>
                                    <input type="text" class="form-control input-sm text-right" name="oe_eixo_pos"
                                        x-model="CERATOMETRIA.history[index].oe_eixo_pos">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 5px;">
                        <div class="col-md-12">
                            <label for="input-placeholder" class="control-label"
                                style="padding-top: 0px;">Comentário:</label>
                            <textarea rows="3" class="form-control" name="obs" x-model="CERATOMETRIA.history[index].obs"></textarea>
                        </div>
                    </div>
                </fieldset>

                <div class="panel-footer col-md-12">
                    <div class="row">
                        <div class="col-md-6" style="text-align: left">
                            <div class="btn-group" role="group" aria-label="First group">

                                <button type="button" class="btn btn-default" @click="index = 0"
                                    :disabled="index === 0"><i class="fa fa-fast-backward"></i></button>

                                <button type="button" class="btn btn-default"
                                    @click="index = Math.max(0, index - 1)" :disabled="index === 0">
                                    <i class="fa fa-backward"></i>
                                </button>
                                <button type="button" class="btn btn-default"
                                    @click="index = Math.min(CERATOMETRIA.history.length - 1, index + 1)"
                                    :disabled="index === CERATOMETRIA.history.length - 1">
                                    <i class="fa fa-forward"></i>
                                </button>
                                <button type="button" class="btn btn-default" @click="index = CERATOMETRIA.history.length - 1"
                                    :disabled="index === CERATOMETRIA.history.length - 1">
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
                            x-on:click="modalCeratometria({{$agendamento->cd_paciente}},null)"></i></a>
                      </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-group" role="tablist" aria-multiselectable="true">
        <template x-if="CERATOMETRIA.history.length > 0" >
          <div class="panel panel-default" style="border-radius: 0px;">
            <template x-for="item in CERATOMETRIA.history" >
             
              <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
                <h4 class="panel-title" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; " 
                    x-html=" iconHistry + ' ' + FormatData(item.dt_exame) + ' -  { ' + item.cd_agendamento + ' } ' "
                    x-on:click="modalCeratometria({{$agendamento->cd_paciente}},item.cd_ceratometria)">
                </h4>
              </div>
    
            </template> 
          </div> 
        </template>
    </div>

</div>
