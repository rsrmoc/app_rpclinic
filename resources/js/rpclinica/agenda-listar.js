Alpine.data('app', () => ({

 

    loadingGetHorarios: false,
    agendaHorarios: null,
    inputsGeracao: {
        cd_agenda: null,
        cd_escala: null,
        horarios_bloqueados: [],
        feriados: []
    },
    loadingGeracao: false,
    loadingExclusao: false,
    dadosExclusao: [],
    datasParaExluir: [],
    loadingExclusaoToggle: false,
    param:{
        agenda: null,
        escala: null
    },
    openModalGeracao(cdAgenda,cdEscala) {
        this.agendaHorarios = null;
        this.loadingGetHorarios = true;
        this.inputsGeracao.cd_agenda = cdAgenda;
        this.inputsGeracao.cd_escala = cdEscala;
        this.inputsGeracao.horarios_bloqueados = [];
        this.inputsGeracao.feriados = [];
        this.dadosExclusao = [];
        this.datasParaExluir = [];

        this.getHorarios(cdAgenda,cdEscala);

        $('#cadastro-geracao').modal('toggle');
    },
    getHorarios(cdAgenda,cdEscala) {
        this.param.agenda=cdAgenda;
        this.param.escala=cdEscala;

        axios.post('/rpclinica/json/agenda/horarios',  this.param )
            .then((res) => {
                this.agendaHorarios = res.data;
                console.log(res.data);

                this.inputsGeracao.feriados = this.agendaHorarios.feriados.map((feriado) => feriado.dt_feriado);

                if (this.agendaHorarios.escalas.bloqueios_gerados) {
                    this.inputsGeracao.horarios_bloqueados = this.agendaHorarios.escalas.bloqueios_gerados.lista_horarios.split(',');
                }

                if (this.agendaHorarios.escalas.feriados_gerados) {
                    this.inputsGeracao.feriados = this.agendaHorarios.escalas.feriados_gerados.lista_datas.split(',');
                }
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingGetHorarios = false);
    },

    timeCut(time) {
        return time?.substr(0, 5);
    },
    calcIntervalo(minutes) {
        return `${parseInt(minutes / 60).toString().padStart(2, 0)}:${parseInt(minutes % 60).toString().padStart(2, 0)}`;
    },

    gerarAgendamentos() {
        let escala = this.agendaHorarios.escalas;

        if (!escala.cd_dia)
        {
            toastr['error']('Não há dias da semana marcados na genda!');
            return;
        }
        console.log(this.inputsGeracao);
       
        this.loadingGeracao = true;

        axios.post('/rpclinica/json/agenda/horarios/gerar-agendamentos', this.inputsGeracao)
            .then((res) => {
                toastr['success'](res.data.message);
                this.getHorarios(this.inputsGeracao.cd_agenda,this.inputsGeracao.cd_escala);
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingGeracao = false);
    },

    pesquisaExclusao() {
        this.loadingExclusao = true;
        console.log(this.agendaHorarios.agenda.cd_agenda);
        let form = new FormData(document.querySelector('#form-exclusao'));
        form.append('cd_agenda', this.agendaHorarios.agenda.cd_agenda); 

        axios.post('/rpclinica/json/agenda/horarios/pesquisa-exclusao', form)
            .then((res) => this.dadosExclusao = res.data)
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingExclusao = false);
    },

    excluirDatas() {
        this.loadingExclusaoToggle = true;

        axios.post('/rpclinica/json/agenda/horarios/excluir-datas', {
            cd_agenda: this.inputsGeracao.cd_agenda,
            datas: this.datasParaExluir
        })
            .then((res) => {
                toastr['success'](res.data.message);

                this.datasParaExluir.forEach((data) => {
                    delete this.dadosExclusao[data];
                });

                this.datasParaExluir = [];
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingExclusaoToggle = false);
    }
}));

$(document).ready(() => {
    $('#cadastro-geracao').modal({
        backdrop: 'static',
        show: false
    });
});
