<table class="table table-striped">
    <thead>
        <tr class="active">
            <th>Código</th>
            <th>Data</th>
            <th>Prestador</th>
            <th>Convênio</th>
            <th>Especialidade</th>
            <th>Situação</th>
        </tr>
    </thead>
    <tbody>
        <template x-if="modalAgenda.historico">
            <template x-for="historico in modalAgenda.historico">
                <tr>
                    <th
                        x-text="historico.cd_agendamento.toString().padStart(5, 0)">
                    </th>
                    <td x-text="formatDate(historico.data_horario)"></td>
                    <td x-text="historico.profissional?.nm_profissional.toUpperCase();"></td>
                    <td x-text="historico.convenio?.nm_convenio.toUpperCase()"></td>
                    <td x-text="historico.especialidade?.nm_especialidade.toUpperCase()">
                    </td>
                    <td>
                        <span class="label"
                            x-bind:class="historico.tab_situacao.class "
                            x-html="historico.tab_situacao.icone  + ' ' + historico.tab_situacao.nm_situacao "></span>
                    </td>
                </tr>
            </template>
        </template>

        <template
            x-if="!modalAgenda.historico || modalAgenda.historico.length == 0">
            <tr>
                <td colspan="6" class="text-center">Nenhum historico</td>
            </tr>
        </template>
    </tbody>
</table>
