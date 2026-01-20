<form x-on:submit.prevent="GetSessao" id="GetSessao">


    <input type="hidden" name="cd_agendamento"
        x-bind:value="modalData.horario?.cd_agendamento" />
    <br>

    <div class="row">
        <div class="col-md-4 col-md-offset-3">
            <label>Qtde. de Agendamentos</label>
            <select class="form-control" style="width: 100%;"
                name="qtde_sessao">
                <option value="">...</option>
                <template x-for="i in ((qtde_sessao) ? qtde_sessao : 0 )">
                    <option :value="i" x-text="i"></option>
                </template>

            </select>
        </div>


        <div class="col-md-2">
            <div class="form-group" style="  margin-top: 23px;">
                <button type="submit" class="btn btn-default"><i
                        class="fa fa-bars"></i> Montar Sessão</button>
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

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table table-striped">
                <thead>
                    <tr class="active">
                        <th>#</th>
                        <th>Código</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Dia da Semana</th>
                        <th>Tipo</th>
                        <th>Situação</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="ListaHorariosSessao">
                        <template x-for="historico in ListaHorariosSessao">
                            <tr>
                                <td>
                                    <template
                                        x-if="historico.situacao == 'livre'">
                                        <input type="checkbox"
                                            name="cds_agendamento_sessao[]"
                                            :value="historico.cd_agendamento" />
                                    </template>
                                </td>
                                <th
                                    x-text="historico.cd_agendamento.toString().padStart(5, 0)">
                                </th>
                                <td x-text="formatDate(historico.dt_agenda)">
                                </td>
                                <td x-text="historico.hr_agenda"></td>
                                <td
                                    x-text="GetDiaSemana(historico.dia_semana)">
                                </td>
                                <td x-text="historico.tipo"></td>
                                <td>
                                    <span class="label"
                                        x-bind:class="classLabelSituacao[historico
                                            .situacao]"
                                        x-text="historico.situacao"></span>
                                </td>
                            </tr>
                        </template>
                    </template>
                </tbody>
            </table>
            <template x-if="ListaHorariosSessao?.length == 0 && !loading">
                <p class="text-center" style="padding: 1.5em">Nenhum
                    Agendamento</p>
            </template>
        </div>
    </div>
    <template x-if="ListaHorariosSessao">
        <div class="row">
            <div class="col-md-3 col-md-offset-2">
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

</form>