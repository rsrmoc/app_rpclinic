<form x-on:submit.prevent="addProcConta" id="GetFinanceiro">



    <input type="hidden" name="cd_agendamento"
        x-bind:value="modalData.horario?.cd_agendamento" />
    <br>

    <div class="row">
        <div class="col-md-6 col-md-offset-1">
            <label>Procedimento</label>
            <select class="form-control" style="width: 100%;"
                name="procedimento" id="procConta">
                <option value="">...</option>
                <template x-for="proc in modalAgenda.procConv">
                    <template x-if="proc.cd_procedimento">

                        <option :value="proc.cd_procedimento" x-text="proc.nm_proc + ' ( ' + proc.cod_proc + ' ) '"></option>

                    </template>
                </template>

            </select>
        </div>

        <div class="col-md-2">
            <label>Qtde.</label>
            <input type="text" class="form-control" value=""
                name="data_de_nascimento" x-model.number="addConta.qtde" required maxlength="10" aria-required="true">
        </div>

        <div class="col-md-2">
            <div class="form-group" style="  margin-top: 23px;">
                <button type="submit" class="btn btn-default"><i class="fa fa-check"></i> Salvar Procedimento</button>
            </div>
        </div>

    </div>
</form>

<div class="row">
    <div class="col-md-3 col-md-offset-3">
        <div class="absolute-loading-sessao"
            style="display: none; text-align: center;">
            <div class="line" style="text-align: center;">
                <div class="loading"></div>
                <span>Pesquisando...</span>
            </div>
        </div>
    </div>
</div>


<form x-on:submit.prevent="atualizarSessao" id="atualizarSessao">

    <input type="hidden" name="cd_agendamento"
        x-bind:value="modalData.horario?.cd_agendamento" />


        <div class="absolute-loading" style="display: none">
            <div class="line">
                <div class="loading"></div>
                <span>Agendando...</span>
            </div>
        </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <table class="table table-striped">
                <thead>
                    <tr class="active">
                        <th>Procedimento</th>
                        <th>Descrição</th>
                        <th style="text-align: right;">Qtde.</th>
                        <th style="text-align: right;">Valor</th>
                        <th style="text-align: center;">Conferido</th>
                        <th style="text-align: center;">Ação</th>
                    </tr>
                </thead>
                <tbody>

                        <template x-for="conta, index in modalAgenda.dadosConta">
                            <tr >

                                <td x-text="conta.cod_proc "> </td>
                                <td  x-text="conta.ds_proc"> </td>
                                <td  style="text-align: right;" x-text="conta.qtde"> </td>
                                <td  style="text-align: right;" x-text="(conta.vl_total) ? conta.vl_total.toLocaleString('pt-br', {minimumFractionDigits: 2}) : '0,00'"></td>
                                <td style="text-align: center;">
                                  <template x-if="conta.sn_confere == 'S'">
                                    <img x-on:click="confereProcConta(conta)" style="cursor: pointer;"
                                    src="{{ asset("assets\images\check-circle-verde.svg") }}" >
                                  </template>
                                  <template x-if="!conta.sn_confere">
                                    <img x-on:click="confereProcConta(conta)" style="cursor: pointer;"
                                    src="{{ asset("assets\images\check-circle.svg") }}" >
                                  </template>
                                </td>

                                <td style="text-align: center;" >  <i x-on:click="deleteProcConta(conta)" class="fa fa-trash" style="color: red; font-size: 1.4em; cursor: pointer;"></i> </td>
                            </tr>
                        </template>

                </tbody>
            </table>
            <template x-if="modalAgenda.tp_convenio === 'PA' ">
                <div>
                    <div class="row">
                        <template x-if="modalAgenda.recebido == true ">
                            <div>
                                <div class="col-md-1 col-md-offset-1">
                                    <span>Situação : </span><br>
                                    <span > Data :</span><br>
                                    <span> Usuario : </span><br>
                                </div>

                                <div class="col-md-2  ">
                                    <span class="label label-success"  >Recebido</span> <br>
                                    <span x-text="modalAgenda.dt_receb">  </span><br>
                                    <span x-text="modalAgenda.usuario_receb"> </span><br>
                                </div>

                                <div class="col-md-2 ">
                                    <span class="text-info pull-right" >Valor : </span><br>
                                    <span class="text-danger  pull-right"> Desconto :</span><br>
                                    <span class="text-success pull-right"> Acrescimo : </span><br>
                                </div>

                                <div class="col-md-1  ">
                                    <span class="pull-right" x-text="modalAgenda.valor"> </span><br>
                                    <span class="pull-right" x-text="(modalAgenda.vl_desconto) ? modalAgenda.vl_desconto : '0,00' "> </span><br>
                                    <span class="pull-right" x-text="(modalAgenda.vl_acrescimo) ? modalAgenda.vl_acrescimo : '0,00' "> </span><br>
                                </div>
                            </div>
                        </template>

                        <template x-if="modalAgenda.recebido == false ">
                            <div class="col-md-7"> </div>
                        </template>

                        <div class="col-md-2 col-md-offset-3" style="text-align: right"><button type="button" class="btn btn-default btn-xs" x-on:click="recebimento()"><span class="menu-icon glyphicon glyphicon-usd"></span> Receber</button>  </div>
                    </div>
                </div>
            </template>

            <template x-if="ListaHorariosSessao?.length == 0 && !loading">
                <p class="text-center" style="padding: 1.5em">Nenhum
                    Agendamento</p>
            </template>
        </div>
    </div>
    <template x-if="ListaHorariosSessao">
        <div class="row">
            <div class="col-md-3 ">
                <div class="form-group"
                    style="display: flex; align-items: center; gap: 10px; margin-top: 23px;">
                    <button type="submit" class="btn btn-success"
                        x-bind:disabled="loadingAgendamentoSessao">
                        <i class="fa fa-check"></i> Salvar Sessão
                    </button>

                    <template x-if="loadingAgendamentoSessao">
                        <x-loading message="Marcando sessções..." />
                    </template>
                </div>
            </div>
        </div>
    </template>




    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <div class="text-right" style="margin-right: 15px;">
                <hr>
                <h4 style="margin-top:0px;" class="no-m m-t-sm">
                    <img   style="cursor: pointer;"  src="{{ asset("assets\images\check-circle.svg") }}" > Total Não Conferido</h4>
                <h2 class="no-m" x-text="'R$ '+totalContaNConf.toLocaleString('pt-br', {minimumFractionDigits: 2})"></h2>

            </div>
        </div>
        <div class="col-md-4">
            <div class="text-right" style="margin-right: 15px;">
                <hr>
                <h4 style="margin-top:0px;" class="no-m m-t-sm">                    <img   style="cursor: pointer;"  src="{{ asset("assets\images\check-circle-verde.svg") }}" > Total Conferido</h4>
                <h2 class="no-m" x-text="'R$ '+totalContaConf.toLocaleString('pt-br', {minimumFractionDigits: 2})"></h2>

            </div>
        </div>
        <div class="col-md-3 ">
            <div class="text-right" style="margin-right: 15px;">
                <hr>
                <h4 style="margin-top:0px;" class="no-m m-t-sm">Total</h4>
                <h2 class="no-m" x-text="'R$ '+totalConta.toLocaleString('pt-br', {minimumFractionDigits: 2})"></h2>

            </div>
        </div>
    </div>


</form>

<template x-if="modalAgenda.tp_convenio === 'TESTE' ">
    <form x-on:submit.prevent="addProcConta" id="form-Rec">

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default "  style="margin-top: 1em; margin-bottom: 0px; border: 1px solid rgb(221, 221, 221) !important;">
                <div class="panel-heading" style="height: auto;      color: #4E5E6A;   padding: 10px;">
                    <h3 class="panel-title">Dados do Recebimento</h3>
                </div>

                <div class="panel-body" style="padding-top: 1.5em">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Conta / Caixa: <span class="red normal">*</span></label>
                                <select class="form-control" style="width: 100%"
                                    name="cd_conta" id="rec-conta" required>
                                    <option value="">SELECIONE</option>
                                    @foreach ($Contas as $ct)
                                        <option value="{{ $ct->cd_conta }}">
                                            {{ $ct->nm_conta }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Forma de Recebimento: <span class="red normal">*</span></label>

                                <select class="form-control" style="width: 100%"
                                    name="cd_forma" id="rec-forma" required>
                                    <option value="">SELECIONE</option>
                                    @foreach ($Forma as $fr)
                                        <option value="{{ $fr->cd_forma_pag }}">
                                            {{ $fr->nm_forma_pag }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label>Data da transação :</label>
                            <div class="form-group">
                                <input type="date" class="form-control" x-model="recAgendamento.dt_transacao" name="dt_transacao" />
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label>Valor:</label>
                            <div class="form-group">
                                <input type="text" class="form-control" x-model="recAgendamento.valor.toLocaleString('pt-br', {minimumFractionDigits: 2})"  name="cartao" />
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Parcelamento: <span class="red normal">*</span></label>

                                <select class="form-control" style="width: 100%"
                                    name="parcela" x-on:change="parcelamento($el.value)" required>
                                    <option value="">A vista</option>
                                    @foreach (range(1, 24) as $val)
                                        <option value="{{ $val }}">{{ ($val==1) ? $val.' Parcela' : $val.' Parcelas'  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                    <template x-if="recAgendamento.parcela">
                        <template  x-for="xx, index in recAgendamento.parcela">
                            <div x-bind:id="index+'div'">
                                <div class="col-sm-3">
                                    <label x-text="'Parcela: '+(index+1)"></label>
                                    <div class="form-group">
                                        <div class="input-group m-b-sm">
                                            <input type="text" x-mask:dynamic="$money($input, ',')"  placeholder="Valor" name="vl_parcela[]" class="form-control" required>
                                            <span class="input-group-btn" style="width: 50%;">
                                                <input type="date" placeholder="Vencimento"
                                                name="dt_venc[]" class="form-control" required>
                                            </span>
                                        </div>
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
    </form>
</template>


