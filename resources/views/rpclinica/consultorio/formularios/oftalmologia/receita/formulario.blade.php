<div class="col-md-7 col-sm-7 col-lg-6 col-xs-12  ">

    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Receitas
                    </h3>
                    <div class="panel-control">
                        <a href="javascript:void(0);" data-toggle="tooltip"
                            x-on:click="deleteReceita(RECEITA.formData.cd_documento)" data-placement="top" title=""
                            data-original-title="Exluir"><i class="icon-close"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body" x-data="{ index: 0, showHistory: false }">
            <form class="form-horizontal" x-on:submit.prevent="storeReceita" id="form_RECEITA" method="post"
                x-show="!showHistory" x-data="{
                    profissional: '{{ $agendamento->cd_profissional }}',
                    profissionalNome: '{{ $agendamento->profissional->nm_profissional }}'
                }">
                @csrf
                <div class="form-group " style="margin-bottom: 5px;">
                    <div class="col-sm-12">
                        <label for="input-help-block" class="control-label">Profissional: <span
                                class="red normal">*</span></label>
                        <select class="form-control " name="cd_profissional" required style="width: 100%;">
                            <option value="{{ $agendamento->cd_profissional }}">
                                {{ $agendamento->profissional->nm_profissional }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label"
                            style="  padding-top: 0px;">Comentário:</label>
                        <textarea rows="6" class="form-control" name="descricao" x-model="RECEITA.formData.descricao"></textarea>
                    </div>
                </div>

                <div class="panel-footer col-md-12">

                    <div class="row">
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-success" x-html="buttonSalvar"
                                x-bind:disabled="buttonDisabled"> </button>

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
                            <label for="input-help-block" class="control-label">Profissional: <span
                                    class="red normal">*</span></label>
                            <input type="text" value="{{ $agendamento->profissional->nm_profissional }}"
                                class="form-control ">

                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 5px;">
                        <div class="col-md-12">
                            <label for="input-placeholder" class=" control-label"
                                style="  padding-top: 0px;">Comentário:</label>
                            <textarea rows="6" class="form-control" name="descricao" x-model="RECEITA.history[index].descricao"></textarea>
                        </div>
                    </div>
                </fieldset>

                <div class="panel-footer col-md-12">
                    <div class="row">
                        <div class="col-md-6" style="text-align: left">
                            <div class="btn-group" role="group" aria-label="First group">

                                <button type="button" class="btn btn-default" @click="index = 0"
                                    :disabled="index === 0"><i class="fa fa-fast-backward"></i></button>

                                <button type="button" class="btn btn-default" @click="index = Math.max(0, index - 1)"
                                    :disabled="index === 0">
                                    <i class="fa fa-backward"></i>
                                </button>
                                <button type="button" class="btn btn-default"
                                    @click="index = Math.min(RECEITA.history.length - 1, index + 1)"
                                    :disabled="index === RECEITA.history.length - 1">
                                    <i class="fa fa-forward"></i>
                                </button>
                                <button type="button" class="btn btn-default"
                                    @click="index = RECEITA.history.length - 1"
                                    :disabled="index === RECEITA.history.length - 1">
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

                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Histórico Completo"><i class="icon-docs"
                                x-on:click="modalReceita({{ $agendamento->cd_paciente }},null)"></i></a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-group" role="tablist" aria-multiselectable="true">

        <template x-if="RECEITA.history.length > 0">
            <div class="panel panel-default" style="border-radius: 0px;">
                <template x-for="item in RECEITA.history">

                    <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
                        <h4 class="panel-title"
                            style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; "
                            x-html=" iconHistry + ' ' + FormatData(item.created_data)+ ' -  { ' + item.cd_agendamento + ' } ' "
                            x-on:click="modalReceita({{ $agendamento->cd_paciente }},item.cd_documento)">
                        </h4>
                    </div>

                </template>
            </div>
        </template>

    </div>
</div>
