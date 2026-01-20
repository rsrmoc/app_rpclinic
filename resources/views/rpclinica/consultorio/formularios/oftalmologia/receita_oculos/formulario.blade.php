<div class="col-md-7 col-sm-7 col-lg-6 col-xs-12  ">

    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>Receita de
                        Óculos
                    </h3>
                    <div class="panel-control">
                        <a href="javascript:void(0);" data-toggle="tooltip"
                            x-on:click="deleteReceitaOculos(RECEITA_OCULOS.formData.cd_receita_oculo)" data-placement="top"
                            title="" data-original-title="Exluir"><i class="icon-close"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body" x-data="{ index: 0, showHistory: false }">
            <div role="tabpanel">

                <form class="form-horizontal" x-on:submit.prevent="storeReceitaOculos" id="form_RECEITA_OCULOS"
                    method="post" x-show="!showHistory">
                    @csrf
                    <div class="form-group " style="margin-bottom: 5px;">
                        <div class="col-sm-6">
                            <label for="input-help-block" class="control-label">Profissional: <span
                                    class="red normal">*</span></label>
                            <select class="form-control " name="cd_profissional" required style="width: 100%;">
                                <option value="{{ $agendamento->cd_profissional }}">
                                    {{ $agendamento->profissional->nm_profissional }}</option>
                            </select>
                        </div>
                        <div class="col-sm-6" style=" ">
                            <label for="input-help-block" class="control-label">Tipo de Lente:</label>
                            <select class="form-control " name="tipo_lente" id="tipo_lente" style="width: 100%">
                                <option value="escolher">A Escolher</option>
                                <option value="multifocais">Multifocais</option>
                                <option value="uso_com_lc">Uso com LC</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group  " style="margin-bottom: 5px;">
                        <div class="col-md-12">
                            <label for="input-placeholder" class=" control-label"
                                style="  padding-top: 0px;">Orientação:</label>
                            <textarea rows="3" class="form-control" name="orientacao" x-model="RECEITA_OCULOS.formData.orientacao"></textarea>
                        </div>
                    </div>

                    <div>
                        <div class="form-group" style="margin-bottom: 0px;">
                            <div class="col-sm-12" style="padding-right: 5px;">
                                <h5 style="margin-bottom: 0px;">Para Longe:</h5>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <div class="col-sm-3" style="padding-right: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">OD
                                    DE</label>
                                <input type="text" class="form-control input-sm text-right" name="longe_od_de"
                                    x-model="RECEITA_OCULOS.formData.longe_od_de" x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                <input type="text" class="form-control input-sm text-right" name="longe_od_dc"
                                    x-model="RECEITA_OCULOS.formData.longe_od_dc" x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">Eixo</label>
                                <input type="text" class="form-control input-sm text-right" name="longe_od_eixo"
                                    x-model="RECEITA_OCULOS.formData.longe_od_eixo"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style=" padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">Adição:</label>
                                <input type="text" class="form-control input-sm text-right" name="longe_od_add"
                                    x-model="RECEITA_OCULOS.formData.longe_od_add"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 5px;">
                            <div class="col-sm-3" style="padding-right: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">OE
                                    DE</label>
                                <input type="text" class="form-control input-sm text-right" name="longe_oe_de"
                                    x-model="RECEITA_OCULOS.formData.longe_oe_de"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                <input type="text" class="form-control input-sm text-right" name="longe_oe_dc"
                                    x-model="RECEITA_OCULOS.formData.longe_oe_dc"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">Eixo</label>
                                <input type="text" class="form-control input-sm text-right" name="longe_oe_eixo"
                                    x-model="RECEITA_OCULOS.formData.longe_oe_eixo"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style=" padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">Adição:</label>
                                <input type="text" class="form-control input-sm text-right" name="longe_oe_add"
                                    x-model="RECEITA_OCULOS.formData.longe_oe_add"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-group" style="margin-bottom: 0px;">
                            <div class="col-sm-12" style="padding-right: 5px;">
                                <h5 style="margin-bottom: 0px;">Para Perto:</h5>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 5px;">
                            <div class="col-sm-3" style="padding-right: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">OD
                                    DE</label>
                                <input type="text" class="form-control input-sm text-right" name="perto_od_de"
                                    x-model="RECEITA_OCULOS.formData.perto_od_de"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                <input type="text" class="form-control input-sm text-right" name="perto_od_dc"
                                    x-model="RECEITA_OCULOS.formData.perto_od_dc"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">Eixo</label>
                                <input type="text" class="form-control input-sm text-right" name="perto_od_eixo"
                                    x-model="RECEITA_OCULOS.formData.perto_od_eixo"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <div class="col-sm-3" style="padding-right: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">OE
                                    DE</label>
                                <input type="text" class="form-control input-sm text-right" name="perto_oe_de"
                                    x-model="RECEITA_OCULOS.formData.perto_oe_de"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                <input type="text" class="form-control input-sm text-right" name="perto_oe_dc"
                                    x-model="RECEITA_OCULOS.formData.perto_oe_dc"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">Eixo</label>
                                <input type="text" class="form-control input-sm text-right" name="perto_oe_eixo"
                                    x-model="RECEITA_OCULOS.formData.perto_oe_eixo"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="form-group" style="margin-bottom: 0px;">
                            <div class="col-sm-12" style="padding-right: 5px;">
                                <h5 style="margin-bottom: 0px;">Intermediário:</h5>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <div class="col-sm-3" style="padding-right: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">OD
                                    DE</label>
                                <input type="text" class="form-control input-sm text-right" name="inter_od_de"
                                    x-model="RECEITA_OCULOS.formData.inter_od_de"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                <input type="text" class="form-control input-sm text-right" name="inter_od_dc"
                                    x-model="RECEITA_OCULOS.formData.inter_od_dc"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">Eixo</label>
                                <input type="text" class="form-control input-sm text-right" name="inter_od_eixo"
                                    x-model="RECEITA_OCULOS.formData.inter_od_eixo"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <div class="col-sm-3" style="padding-right: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">OE
                                    DE</label>
                                <input type="text" class="form-control input-sm text-right" name="inter_oe_de"
                                    x-model="RECEITA_OCULOS.formData.inter_oe_de"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                <input type="text" class="form-control input-sm text-right" name="inter_oe_dc"
                                    x-model="RECEITA_OCULOS.formData.inter_oe_dc"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                            <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">Eixo</label>
                                <input type="text" class="form-control input-sm text-right" name="inter_oe_eixo"
                                    x-model="RECEITA_OCULOS.formData.inter_oe_eixo"
                                    x-mask:dynamic="$money($input, ',')">

                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 5px;">
                        <div class="col-md-12">
                            <label for="input-placeholder" class=" control-label"
                                style="  padding-top: 0px;">Comentário:</label>
                            <textarea rows="3" class="form-control" name="obs" x-model="RECEITA_OCULOS.formData.obs"></textarea>
                        </div>
                    </div>

                    <div class="panel-footer col-md-12">

                        <div class="row">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-success" x-html="buttonSalvar"
                                    x-bind:disabled="buttonDisabled"> </button>
                                <button type="reset" id="reset-button" class="btn btn-default"
                                    style="display:none;"><i class="fa fa-ban"></i> Limpar</button>

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
                            <div class="col-sm-6">
                                <label for="input-help-block" class="control-label">Profissional: <span
                                        class="red normal">*</span></label>
                                <input type="text" class="form-control input-sm "
                                    value="{{ $agendamento->profissional->nm_profissional }}">
                            </div>
                            <div class="col-sm-6" style=" ">
                                <label for="input-help-block" class="control-label">Tipo de Lente:</label>
                                <input type="text" class="form-control input-sm "
                                    x-model="RECEITA_OCULOS.formData.tipo_lente">
                            </div>
                        </div>

                        <div class="form-group  " style="margin-bottom: 5px;">
                            <div class="col-md-12">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">Orientação:</label>
                                <textarea rows="3" class="form-control" name="orientacao" x-model="RECEITA_OCULOS.history[index].orientacao"></textarea>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" style="margin-bottom: 0px;">
                                <div class="col-sm-12" style="padding-right: 5px;">
                                    <h5 style="margin-bottom: 0px;">Para Longe:</h5>
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-3" style="padding-right: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">OD
                                        DE</label>
                                    <input type="text" class="form-control input-sm text-right" name="longe_od_de"
                                        x-model="RECEITA_OCULOS.history[index].longe_od_de">

                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                    <input type="text" class="form-control input-sm text-right" name="longe_od_dc"
                                        x-model="RECEITA_OCULOS.history[index].longe_od_dc">
                                    @if ($errors->has('od_dc_dinamica'))
                                        <div class="error">{{ $errors->first('od_dc_dinamica') }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="  padding-top: 0px;">Eixo</label>
                                    <input type="text" class="form-control input-sm text-right" name="longe_od_eixo"
                                        x-model="RECEITA_OCULOS.history[index].longe_od_eixo">

                                </div>
                                <div class="col-sm-3" style=" padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="  padding-top: 0px;">Adição:</label>
                                    <input type="text" class="form-control input-sm text-right" name="longe_od_add"
                                        x-model="RECEITA_OCULOS.history[index].longe_od_add">

                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-3" style="padding-right: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">OE
                                        DE</label>
                                    <input type="text" class="form-control input-sm text-right" name="longe_oe_de"
                                        x-model="RECEITA_OCULOS.history[index].longe_oe_de">

                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                    <input type="text" class="form-control input-sm text-right" name="longe_oe_dc"
                                        x-model="RECEITA_OCULOS.history[index].longe_oe_dc">

                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="  padding-top: 0px;">Eixo</label>
                                    <input type="text" class="form-control input-sm text-right" name="longe_oe_eixo"
                                        x-model="RECEITA_OCULOS.history[index].longe_oe_eixo">

                                </div>
                                <div class="col-sm-3" style=" padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="  padding-top: 0px;">Adição:</label>
                                    <input type="text" class="form-control input-sm text-right" name="longe_oe_add"
                                        x-model="RECEITA_OCULOS.history[index].longe_oe_add">

                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="form-group" style="margin-bottom: 0px;">
                                <div class="col-sm-12" style="padding-right: 5px;">
                                    <h5 style="margin-bottom: 0px;">Para Perto:</h5>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-3" style="padding-right: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">OD
                                        DE</label>
                                    <input type="text" class="form-control input-sm text-right" name="perto_od_de"
                                        x-model="RECEITA_OCULOS.history[index].perto_od_de">

                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                    <input type="text" class="form-control input-sm text-right" name="perto_od_dc"
                                        x-model="RECEITA_OCULOS.history[index].perto_od_dc">

                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="  padding-top: 0px;">Eixo</label>
                                    <input type="text" class="form-control input-sm text-right" name="perto_od_eixo"
                                        x-model="RECEITA_OCULOS.history[index].perto_od_eixo">

                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-3" style="padding-right: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">OE
                                        DE</label>
                                    <input type="text" class="form-control input-sm text-right" name="perto_oe_de"
                                        x-model="RECEITA_OCULOS.history[index].perto_oe_de">

                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                    <input type="text" class="form-control input-sm " name="perto_oe_dc"
                                        x-model="RECEITA_OCULOS.history[index].perto_oe_dc">

                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="  padding-top: 0px;">Eixo</label>
                                    <input type="text" class="form-control input-sm text-right" name="perto_oe_eixo"
                                        x-model="RECEITA_OCULOS.history[index].perto_oe_eixo">

                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="form-group" style="margin-bottom: 0px;">
                                <div class="col-sm-12" style="padding-right: 5px;">
                                    <h5 style="margin-bottom: 0px;">Intermediário:</h5>
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-3" style="padding-right: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">OD
                                        DE</label>
                                    <input type="text" class="form-control input-sm text-right" name="inter_od_de"
                                        x-model="RECEITA_OCULOS.history[index].inter_od_de">

                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                    <input type="text" class="form-control input-sm text-right" name="inter_od_dc"
                                        x-model="RECEITA_OCULOS.history[index].inter_od_dc">

                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="  padding-top: 0px;">Eixo</label>
                                    <input type="text" class="form-control input-sm text-right" name="inter_od_eixo"
                                        x-model="RECEITA_OCULOS.history[index].inter_od_eixo">

                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-3" style="padding-right: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">OE
                                        DE</label>
                                    <input type="text" class="form-control input-sm text-right" name="inter_oe_de"
                                        x-model="RECEITA_OCULOS.history[index].inter_oe_de">

                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="font-size: 0.9em; padding-top: 0px;">DC</label>
                                    <input type="text" class="form-control input-sm text-right" name="inter_oe_dc"
                                        x-model="RECEITA_OCULOS.history[index].inter_oe_dc">

                                </div>
                                <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
                                    <label for="input-placeholder" class=" control-label"
                                        style="  padding-top: 0px;">Eixo</label>
                                    <input type="text" class="form-control input-sm text-right" name="inter_oe_eixo"
                                        x-model="RECEITA_OCULOS.history[index].inter_oe_eixo">

                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <div class="col-md-12">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">Comentário:</label>
                                <textarea rows="3" class="form-control" name="obs" x-model="RECEITA_OCULOS.history[index].obs"></textarea>
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
                                        @click="index = Math.min(RECEITA_OCULOS.history.length - 1, index + 1)"
                                        :disabled="index === RECEITA_OCULOS.history.length - 1">
                                        <i class="fa fa-forward"></i>
                                    </button>
                                    <button type="button" class="btn btn-default"
                                        @click="index = RECEITA_OCULOS.history.length - 1"
                                        :disabled="index === RECEITA_OCULOS.history.length - 1">
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
</div>

<div class="col-md-2 col-sm-2 col-xs-12 col-lg-3">
    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title">Histórico</h3>
                    <div class="panel-control">

                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Histórico Completo"><i class="icon-docs"
                                x-on:click="modalReceitaOculos({{ $agendamento->cd_paciente }},null)"></i></a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-group" role="tablist" aria-multiselectable="true">

        <template x-if="RECEITA_OCULOS.history.length > 0">
            <div class="panel panel-default" style="border-radius: 0px;">
                <template x-for="item in RECEITA_OCULOS.history">

                    <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
                        <h4 class="panel-title"
                            style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; "
                            x-html=" iconHistry + ' ' + FormatData(item.created_data)+ ' -  { ' + item.cd_agendamento + ' } ' "
                            x-on:click="modalReceitaOculos({{ $agendamento->cd_paciente }},item.cd_receita_oculo)">
                        </h4>
                    </div>

                </template>
            </div>
        </template>


    </div>
</div>
