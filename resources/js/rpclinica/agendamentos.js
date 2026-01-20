import axios from 'axios';
import moment from 'moment';

var agendamentoDadosMes = null;
var agendamentoDadosDiaControll = null;
var agendamentoDadosMesControll = null;

Alpine.data('app', () => ({
    dataInput: null,
    dataCelular : null,
    SituacaoWhast : null,
    foneWhast : null,
    classWhast : 'fa fa-whatsapp whastNeutro',
    CodAgenda: '',
    NomeAgenda: '',
    AgendaLocal: [],
    AgendaProc: [],
    AgendaConv: [],
    AgendaEspec: [],
    AgendaProf: [],
    logsWhsat: [],
    SnCadastra: false,
    loading: false,
    loading_avanc: false,
    loading_confir: false,
    reagManual: false,
    reagEscala: false,
    sn_sessao: null,
    qtde_sessao:null,
    messageDanger: null,
    messageDangerConfirm: null,
    horarios: [],
    horarios2: [],
    horariosConfir: [],
    header: [],
    ListaHorariosLivres: null,
    ListaHorariosSessao: null,
    DataConfirmacao: null,
    modalData: {
        horario: null,
        errors: []
    },
    classLabelSituacao: {
        livre: 'label-success',
        agendado: 'label-primary',
        confirmado: 'label-info',
        atendido: 'label-warning',
        bloqueado: 'label-danger',
        cancelado: 'label-danger',
        aguardando: 'label-aguardando',
        atendimento: 'label-aguardando'
    },
    loadingPaciente: false,
    loadingAgendamentoSessao: false,

    errorsAgendamentoManual: [],

    agendamentoDataControll: '',

    init() {
        $('#data-input').val(moment().format('YYYY-MM-DD'));
        $('#calendar').datepicker('setDate', moment().format('YYYY-MM-DD'));
        this.getHorarios();

        $('#calendar').on('changeDate', () => {
            $('#data-input').val(
                $('#calendar').datepicker('getFormattedDate')
            );

            if (this.agendamentoDataControll == $('#data-input').val() || agendamentoDadosDiaControll == $('#data-input').val()) return;

            this.getHorarios()
        });

        $('#form-horario select').on('select2:select', () => {
            this.getHorarios()
        });

        $('#cadastro-consulta').on('hidden.bs.modal', () => {
            this.modalData.horario = null;
            this.modalData.errors = [];
            $('#cadastro-consulta form').trigger('reset');

            $('#check-agendamento-receb span').removeClass('checked');
            $('#check-agendamento-receb input').prop('checked', false);
            $('#check-agendamento-sms span').removeClass('checked');
            $('#check-agendamento-sms input').prop('checked', false);
            $('#check-agendamento-whatsapp span').removeClass('checked');
            $('#check-agendamento-whatsapp input').prop('checked', false);
            $('#check-agendamento-presenca span').removeClass('checked');
            $('#check-agendamento-presenca input').prop('checked', false);
        });

        $('#agendamento-manual').on('hidden.bs.modal', () => {
            this.errorsAgendamentoManual = [];
            $('#agendamento-manual form').trigger('reset');

            $('#agendamento-manual-local').val(null).trigger('change');
            $('#agendamento-manual-profissional').val(null).trigger('change');
            $('#agendamento-manual-especialidade').val(null).trigger('change');
            $('#agendamento-manual-procedimento').val(null).trigger('change');
            $('#agendamento-manual-paciente').val(null).trigger('change');
            $('#agendamento-manual-convenio').val(null).trigger('change');
            $('#agendamento-manual-situacao').val(null).trigger('change');
            $('#agendamento-manual-tipo').val(null).trigger('change');

            $('#check-agendamento-manual-receb span').removeClass('checked');
            $('#check-agendamento-manual-receb input').prop('checked', false);
            $('#check-agendamento-manual-sms span').removeClass('checked');
            $('#check-agendamento-manual-sms input').prop('checked', false);
            $('#check-agendamento-manual-whatsapp span').removeClass('checked');
            $('#check-agendamento-manual-whatsapp input').prop('checked', false);
        });

        $('#agendamento-procedimento').on('select2:select', (evt) => this.setValorProfissionalProcedimento(evt.params.data.id));
        $('#agendamento-manual-procedimento').on('select2:select', (evt) => this.setValorProfissionalProcManual(evt.params.data.id));
    },

    getHorarios() {

        this.loading = true;
        this.CodAgenda = $('#CodAgenda').val();
        this.SnCadastra = false;
        /*
        if (this.CodAgenda)
            this.SnCadastra = true;
        else
            this.SnCadastra = false;
        */
        this.ListaHorariosLivres = null;

        let form = new FormData(document.querySelector('#form-horario'));
        axios.post('/rpclinica/json/horarios', form)
            .then((res) => {
                this.horarios = res.data.dados;
                console.log(res.data.dados.dt_pres);

                if (res.data.data_agenda.sn_agenda_manual=='SIM'){
                    this.SnCadastra = true;
                }
                else
                {
                    this.SnCadastra = false;
                }

                this.header = res.data.header[0];
                this.agendamentoDataControll = $('#data-input').val();
            })
            .catch((err) => this.messageDanger = err.response.data.message)
            .finally(() => this.loading = false);

    },

    getHorariosAvanc(){

        this.loading_avanc = true;
        let form = new FormData(document.querySelector('#form-horario-avanc'));
        axios.post('/rpclinica/json/horarios-avanc', form)
            .then((res) => {
                this.horarios2= res.data;
            })
            .catch((err) => this.messageDanger = err.response.data.message)
            .finally(() => this.loading_avanc = false);
    },

    clickHorario(horario) {


        this.SituacaoWhast = null;
        this.foneWhast = null;
        this.foneWhast = null;
        this.classWhast = 'fa fa-whatsapp whastNeutro';
        const element = document.querySelector('#Zap');
        element.classList.remove('whastValido');
        element.classList.remove('whastInvalido');
        this.dataCelular = horario.celular;
        if(horario.whast=='true'){
            this.classWhast = 'fa fa-whatsapp whastValido';
        }
        if(horario.whast=='false'){
            this.classWhast = 'fa fa-whatsapp whastInvalido';
        }

        this.modalData.horario = horario;
        this.ListaHorariosLivres = null;
        this.ListaHorariosSessao = null;
        this.sn_sessao = horario.agenda?.sn_sessao;
        this.qtde_sessao = horario.agenda?.qtde_sessao;
        this.DataConfirmacao = horario.dt_pres;

        axios.get(`/rpclinica/json/logs-whast?cd_agendamento=${horario.cd_agendamento}`)
        .then((res) => {
            console.log(res.data);
            this.logsWhsat = res.data;
        })
        .catch((err) => {
            toastr['error'](err.response.data.message);
        })


        $('#cadastro-consulta select').val(null).trigger('change');
        $('#cadastro-consulta select#select-situacao').val(horario.situacao.toLocaleLowerCase()).trigger('change');

        $('#cadastro-consulta select#agendamento-local').val(horario.agenda?.cd_local_atendimento).trigger('change');
        $('#cadastro-consulta select#agendamento-profissional').val(horario.agenda?.cd_profissional).trigger('change');
        $('#cadastro-consulta select#agendamento-procedimento').val(horario.agenda?.cd_proc).trigger('change');
        this.setValorProfissionalProcedimento(horario.agenda?.cd_proc);

        $('#cadastro-consulta select#agendamento-especialidade').val(horario.agenda?.cd_especialidade).trigger('change');
        $('#cadastro-consulta select#agendamento-tipo').val(horario.agenda?.tipo_agenda).trigger('change');

        if (horario.cd_local_atendimento) $('#cadastro-consulta select#agendamento-local').val(horario.cd_local_atendimento).trigger('change')

        if (horario.cd_profissional) $('#cadastro-consulta select#agendamento-profissional').val(horario.cd_profissional).trigger('change');

        if (horario.cd_procedimento) $('#cadastro-consulta select#agendamento-procedimento').val(horario.cd_procedimento).trigger('change');

        if (horario.cd_procedimento) this.setValorProfissionalProcedimento(horario.cd_procedimento);

        if (horario.paciente) {
            let newOption = new Option(horario.paciente.nm_paciente, horario.paciente.cd_paciente, false, false);
            $('#cadastro-consulta select#agendamento-paciente').append(newOption).trigger('change');
            $('#cadastro-consulta select#agendamento-paciente').val(horario.paciente.cd_paciente).trigger('change');
        }

        if (horario.sms) {
            $('#check-agendamento-sms span').addClass('checked');
            $('#check-agendamento-sms input').prop('checked', true);
        }
        if (horario.recebido) {
            $('#check-agendamento-receb span').addClass('checked');
            $('#check-agendamento-receb input').prop('checked', true);
        }
        if (horario.whatsapp) {
            $('#check-agendamento-whatsapp span').addClass('checked');
            $('#check-agendamento-whatsapp input').prop('checked', true);
        }
        if (horario.sn_presenca) {
            $('#check-agendamento-presenca span').addClass('checked');
            $('#check-agendamento-presenca input').prop('checked', true);
        }
        if (horario.cd_especialidade) $('#cadastro-consulta select#agendamento-especialidade').val(horario.cd_especialidade).trigger('change');

        if (horario.cd_convenio) $('#cadastro-consulta select#agendamento-convenio').val(horario.cd_convenio).trigger('change');

        if (horario.tipo) $('#cadastro-consulta select#agendamento-tipo').val(horario.tipo).trigger('change');

        if (horario.cartao) $('#cadastro-consulta #agendamento-cartao').val(horario.cartao);

        $('#cadastro-consulta').modal('toggle');
    },

    GetDiaSemana(Dia) {
        if(((Dia>=0)&&(Dia<=6))){
            let Dias = ["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"];
            return Dias[Dia];
        }else{
            return "Não Infor.";
        }

    },
    GetReagendamento() {


            this.reagEscala = true;
            this.reagManual = false;

            let form = new FormData(document.querySelector('#GetReagendamento'));
            $('#cadastro-consulta .absolute-loading-pesquisa').show();
            axios.post("/rpclinica/json/horario-livre", form)
            .then((res) => {

                this.ListaHorariosLivres = res.data;
            })
            .catch((err) => {
                Object.values(err.response.data.errors).forEach((errors) => {
                    this.modalData.errors = this.modalData.errors.concat(errors);
                    $('#cadastro-consulta .absolute-loading-pesquisa').hide();
                })
            })
            .finally(() => $('#cadastro-consulta .absolute-loading-pesquisa').hide());



    },
    atualizarReagendamento() {

        $('#cadastro-consulta .absolute-loading').show();
        let form = new FormData(document.querySelector('#FormReagendamento'));

        axios.post("/rpclinica/json/reagendamento", form)
        .then((res) => {
            this.getHorarios();
            $('#cadastro-consulta').modal('hide');
            toastr['success']('Reagendado com sucesso!');
        })
        .catch((err) => {
            toastr['error']((err.response.data.errors) ? err.response.data.errors : 'Erro Interno!');
            Object.values(err.response.data.errors).forEach((errors) => {

                //this.modalData.errors = this.modalData.errors.concat(errors);
                $('#cadastro-consulta .absolute-loading-pesquisa').hide();
            })
        })
        .finally(() => $('#cadastro-consulta .absolute-loading').hide());
    },

    atualizarReagendamentomanual(){

        toastr['warning']('Reagendado Manual em construção!');

        /*
        $('#cadastro-consulta .absolute-loading').show();
        let form = new FormData(document.querySelector('#atualizarReagendamentomanual'));
        axios.post("/rpclinica/json/reagendamento-manual", form)
        */
    },

    GetSessao() {

        $('#cadastro-consulta .absolute-loading-sessao').show();
        let form = new FormData(document.querySelector('#GetSessao'));
        axios.post("/rpclinica/json/horario-sessao", form)
        .then((res) => {
            this.ListaHorariosSessao = res.data;
        })
        .catch((err) => {
            toastr['error'](err.response.data.message);
            Object.values(err.response.data.errors).forEach((errors) => {
                this.modalData.errors = this.modalData.errors.concat(errors);
                $('#cadastro-consulta .absolute-loading-sessao').hide();
            })
        })
        .finally(() => $('#cadastro-consulta .absolute-loading-sessao').hide());

    },
    atualizarSessao() {
        this.loadingAgendamentoSessao = true;

        let form = new FormData(document.querySelector('#atualizarSessao'));

        axios.post('/rpclinica/json/agendamento-sessao', form)
            .then((res) => {
                toastr['success'](res.data.message)
                this.ListaHorariosSessao = null;
                this.GetSessao();
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingAgendamentoSessao = false);
    },
    atualizarAgendamento() {
        this.modalData.errors = [];
        $('#cadastro-consulta .absolute-loading').show();

        let form = new FormData(document.querySelector('#cadastro-consulta form'));

        axios.post('/rpclinica/json/agendamento?_method=PUT', form)
            .then((res) => {
                this.getHorarios();
                $('#cadastro-consulta').modal('hide');
            })
            .catch((err) => {
                Object.values(err.response.data.errors).forEach((errors) => {
                    this.modalData.errors = this.modalData.errors.concat(errors);
                })
            })
            .finally(() => $('#cadastro-consulta .absolute-loading').hide());
    },

    excluirAgendameto() {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse agendamento?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.modalData.errors = [];
                $('#cadastro-consulta .absolute-loading').show();

                axios.delete(`/rpclinica/json/agendamento/${this.modalData.horario.cd_agendamento}`)
                    .then((res) => {
                        this.getHorarios();
                        toastr['success']('Agendamento excluido!');
                        $('#cadastro-consulta').modal('hide');
                    })
                    .catch((err) => toastr['error'](err.response.data.message))
                    .finally(() => $('#cadastro-consulta .absolute-loading').hide());
            }
        });
    },
    
    bloquearHorario() {
        Swal.fire({
            title: 'Confirmação',
            text: "Deseja bloquear esse horário?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#cadastro-consulta .absolute-loading').show();

                axios.post('/rpclinica/json/agendamento/bloquear-horario', { cd_agendamento: this.modalData.horario.cd_agendamento })
                    .then((res) => {
                        this.modalData.horario.situacao = 'bloqueado';
                        this.getHorarios();
                        toastr['success'](res.data.message);
                    })
                    .catch((err) => toastr['error'](err.response.data.message))
                    .finally(() => $('#cadastro-consulta .absolute-loading').hide());
            }
        });
    },
    desbloquearHorario() {
        Swal.fire({
            title: 'Confirmação',
            text: "Deseja desbloquear esse horário?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#cadastro-consulta .absolute-loading').show();

                axios.post('/rpclinica/json/agendamento/desbloquear-horario', { cd_agendamento: this.modalData.horario.cd_agendamento })
                    .then((res) => {
                        this.modalData.horario.situacao = 'livre';
                        this.getHorarios();
                        toastr['success'](res.data.message);
                    })
                    .catch((err) => toastr['error'](err.response.data.message))
                    .finally(() => $('#cadastro-consulta .absolute-loading').hide());

            }
        });
    },

    setValorProfissionalProcedimento(cdProcedimento) {
        let procedimentoProfissional = procedimentosProfissional?.find((procedimento) => procedimento.cd_proc == cdProcedimento);
        if (procedimentoProfissional) {
            setTimeout(() => $('#agendamento-valor').val(procedimentoProfissional.vl_proc), 300);
        }
    },

    setValorProfissionalProcManual(cdProcedimento) {
        let procedimentoProfissional = procedimentosProfissional?.find((procedimento) => procedimento.cd_proc == cdProcedimento);
        if (procedimentoProfissional) {
            setTimeout(() => $('#agendamento-manual-valor').val(procedimentoProfissional.vl_proc), 300);
        }
    },

    openAgendamentoManual() {
        if(!this.CodAgenda){
            toastr['error']("Erro! Codigo da agenda não informada.");
            return false;
        }
        this.NomeAgenda = '...';
        axios.get(`/rpclinica/json/dados-agenda?cd_agenda=${this.CodAgenda}`)
        .then((res) => {
            console.log(res.data);
            console.log(res.data.agendamento.nm_agenda);
            $('#NomeAgenda').val(res.data.agendamento.nm_agenda);
            this.NomeAgenda = res.data.agendamento.nm_agenda;
            this.AgendaLocal = res.data.locais;
            this.AgendaProf = res.data.profissionais;
            this.AgendaEspec = res.data.especialidades;
            this.AgendaConv = res.data.convenios;
            this.AgendaProc = res.data.procedimentos;
        })
        .catch((err) => {
            toastr['error'](err.response.data.message);
        })
        .finally(() => $('#loading-dados-mes').hide());

        $('#agendamento-manual input[type=date]').val($('#data-input').val());
        $('#agendamento-manual').modal('show');
    },

    agendamentoManual() {
        this.errorsAgendamentoManual = [];
        $('#agendamento-manual .absolute-loading').show();

        let form = new FormData(document.querySelector('#agendamento-manual form'));

        axios.post('/rpclinica/json/agendamento', form)
            .then((res) => {
                console.log(res);
                toastr['success']('Agendamento criado com sucesso!');
                this.getHorarios();
                $('#agendamento-manual').modal('hide');
            })
            .catch((err) => {
                Object.values(err.response.data.errors).forEach((errors) => {
                    this.errorsAgendamentoManual = this.modalData.errors.concat(errors);
                })
            })
            .finally(() => $('#agendamento-manual .absolute-loading').hide());
    },

    clickHorarioAvancado(horario) {
        $('#buttonTabAgendamentos').click();
        $('#data-input').val(horario.dt_agenda);
        $('#calendar').datepicker('setDate', horario.dt_agenda);
    },

    validarZap(){

        const element = document.querySelector('#Zap');
        element.classList.remove('whastValido');
        element.classList.remove('whastInvalido');
        this.dataCelular= $('#agendamento-celular').val();

        this.SituacaoWhast = null;
        this.foneWhast = null;
        if(!this.dataCelular) {
            return toastr['error']("Informação Incompleta, Numero não Informado!");
        }
        console.log(this.modalData.horario.cd_agendamento+' '+this.dataCelular);
        axios.get(`/rpclinica/json/valida-whast?cd_agenda=${this.modalData.horario.cd_agendamento}&numero=${this.dataCelular}`)
        .then((res) => {
            this.SituacaoWhast = null;
            this.foneWhast = null;
            this.classWhast = 'fa fa-whatsapp whastNeutro';
            if(res.data.status==200) {
                this.SituacaoWhast = res.data.exists;
                this.foneWhast = this.dataCelular;
                if(res.data.exists==true){
                    this.classWhast = "fa fa-whatsapp whastValido";
                }else{
                    this.classWhast = "fa fa-whatsapp whastInvalido";
                }
            }
            if(res.data.status==401) {
                this.SituacaoWhast = res.data.exists;
                this.foneWhast = this.dataCelular;
                this.classWhast = "fa fa-whatsapp whastInvalido";

            }
            if(res.data.errors[0].message){
                this.SituacaoWhast = null;
                this.foneWhast = null;
                this.classWhast = 'fa fa-whatsapp whastNeutro';
                toastr['error']("Informação Incompleta, Numero não Informado!");
            }
            console.log(res);
        })
        .catch((err) => {
            toastr['error'](err.response.data.message);
        });
        //.finally(() => $('#loading-dados-mes').hide());

    },

    getHorariosConfir(){
        this.loading_confir = true;
        let form = new FormData(document.querySelector('#form-horario-confirm'));
        axios.post('/rpclinica/json/horario-confirm', form)
            .then((res) => {
                this.horariosConfir = res.data;
            })
            .catch((err) => this.messageDangerConfirm = err.response.data.message)
            .finally(() => this.loading_confir = false);
    },

    sendHorariosConfir(){
        this.loading_confir = true;
        let form = new FormData(document.querySelector('#form-horario-send'));
        axios.post('/rpclinica/json/send-horario', form)
            .then((res) => {
                console.log(res.data);
                this.loading_confir = true;
                $('#AgendCod').val();
                this.getHorariosConfir();
            })
            .catch((err) => this.messageDangerConfirm = err.response.data.message)
            .finally(() => this.loading_confir = false);
    }
}));

$(document).ready(function() {
    $.fn.datepicker.dates['en'] = {
        days: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabadao'],
        daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        daysMin: ["Do", "Se", "Te", "Qa", "Qi", "Se", "Sa"],
        months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
    };

    $('#calendar').datepicker({
        language: 'pt',
        format: 'yyyy-mm-dd',


        beforeShowDay: (e) => {

            //console.log(e.getDate().toString().padStart(2, '0')+'-'+e.getMonth()+'-'+e.getFullYear()+' = ');
            console.log(agendamentoDadosMesControll);
            if (agendamentoDadosMesControll !== e.getMonth()) return {enabled: true, classes: ''};

            let day = e.getDate().toString().padStart(2, 0);
            if (agendamentoDadosMes?.[day]) {
                let situacoes = agendamentoDadosMes?.[day];

                if (situacoes.livre > situacoes.agendado && situacoes.livre > situacoes.confirmado && situacoes.livre > situacoes.atendido &&
                    situacoes.livre > situacoes.bloqueado && situacoes.livre > situacoes.cancelado && situacoes.livre > situacoes.aguardando)
                {
                    return {enabled: true, classes: 'text-success'};
                }

                if (situacoes.agendado > situacoes.livre && situacoes.agendado > situacoes.confirmado && situacoes.agendado > situacoes.atendido &&
                    situacoes.agendado > situacoes.bloqueado && situacoes.agendado > situacoes.cancelado && situacoes.agendado > situacoes.aguardando)
                {
                    return {enabled: true, classes: 'text-primary'};
                }

                if (situacoes.confirmado > situacoes.livre && situacoes.confirmado > situacoes.atendido && situacoes.confirmado > situacoes.bloqueado &&
                    situacoes.confirmado > situacoes.cancelado && situacoes.confirmado > situacoes.aguardando)
                {
                    return {enabled: true, classes: 'text-info'};
                }

                if (situacoes.atendido > situacoes.agendado && situacoes.atendido > situacoes.confirmado && situacoes.atendido > situacoes.livre &&
                    situacoes.atendido > situacoes.bloqueado && situacoes.atendido > situacoes.cancelado && situacoes.atendido > situacoes.aguardando)
                {
                    return {enabled: true, classes: 'text-warning'};
                }

                if (situacoes.bloqueado > situacoes.agendado && situacoes.bloqueado > situacoes.confirmado && situacoes.bloqueado > situacoes.atendido &&
                    situacoes.bloqueado > situacoes.livre && situacoes.bloqueado > situacoes.cancelado && situacoes.bloqueado > situacoes.aguardando)
                {
                    return {enabled: true, classes: 'text-danger'};
                }

                if (situacoes.cancelado > situacoes.agendado && situacoes.cancelado > situacoes.confirmado && situacoes.cancelado > situacoes.atendido &&
                    situacoes.cancelado > situacoes.bloqueado && situacoes.cancelado > situacoes.livre && situacoes.cancelado > situacoes.aguardando)
                {
                    return {enabled: true, classes: 'text-danger'};
                }

                if (situacoes.aguardando > situacoes.agendado && situacoes.aguardando > situacoes.confirmado && situacoes.aguardando > situacoes.atendido &&
                    situacoes.aguardando > situacoes.bloqueado && situacoes.aguardando > situacoes.livre)
                {
                    return {enabled: true, classes: 'text-aguardando'};
                }
            }

            return {enabled: true, classes: ''};

        },

    });
    /*
    $('#calendar').datepicker().on('changeDate', (e) => {
        let data = formatDate(e.date, 'YYYY-MM-DD');

        if (agendamentoDadosMesControll === e.date.getMonth()) return;

        $('#loading-dados-mes').show();

        axios.get(`/rpclinica/json/agendamento-dados-mes/${data}`)
            .then((res) => {
                agendamentoDadosMes = res.data;
                agendamentoDadosDiaControll = data;
                agendamentoDadosMesControll = e.date.getMonth();

                $('#data-input').val(data);
                $('#calendar').datepicker('setDate', data);
            })
            .finally(() => $('#loading-dados-mes').hide());
    });
    */
    $('#agendamento-paciente').select2({
        ajax: {
            url: '/rpclinica/json/pacientes',
            dataType: 'json',
            processResults: (data) => {
                let search = $('#agendamento-paciente').data('select2').results.lastParams?.term;

                if (search) {
                    data.unshift({
                        id: search,
                        text: `"${search}" Novo paciente`
                    });

                }

                return {
                    results: data
                };
            }
        }
    });

    $('#agendamento-manual-paciente').select2({
        ajax: {
            url: '/rpclinica/json/pacientes',
            dataType: 'json',
            processResults: (data) => {
                let search = $('#agendamento-manual-paciente').data('select2').results.lastParams?.term;

                if (search) {
                    data.unshift({
                        id: search,
                        text: `"${search}" Novo paciente`
                    });

                }

                return {
                    results: data
                };
            }
        }
    });

    $('#agendamento-paciente').on('select2:select', (evt) => {
        let cdPaciente = evt.params.data.id;
        console.log(evt.params.data);
        if (typeof cdPaciente == 'number') {
            this.loadingPaciente = true;

            axios.get(`/rpclinica/json/paciente`, { params: { cd_paciente: cdPaciente } })
                .then((response) => {
                    $('#nome-mae-paciente').val(response.data.nm_mae);
                    $('#nome-pai-paciente').val(response.data.nm_pai);
                    $('#agendamento-convenio').val(response.data.cd_categoria).trigger('change');
                    $('#agendamento-cartao').val(response.data.cartao);
                    $('#data-de-nasc').val(response.data.dt_nasc);
                    $('#agendamento-celular').val(response.data.celular);
                    $('#nome-mae-paciente').prop('readonly', true);
                    $('#nome-pai-paciente').prop('readonly', true);
                    $('#data-de-nasc').prop('readonly', true);
                })
                .catch((error) => toastr['error'](error.response.data.message))
                .finally(() => this.loadingPaciente = false);
        }else{
            $('#nome-mae-paciente').prop('readonly', false);
            $('#nome-mae-paciente').val('');
            $('#nome-pai-paciente').prop('readonly', false);
            $('#nome-pai-paciente').val('');
            $('#data-de-nasc').prop('readonly', false);
            $('#data-de-nasc').val('');
        }

    });

    $('#agendamento-manual-paciente').on('select2:select', (evt) => {
        let cdPaciente = evt.params.data.id;

        if (typeof cdPaciente == 'number') {
            this.loadingPaciente = true;

            axios.get(`/rpclinica/json/paciente`, { params: { cd_paciente: cdPaciente } })
                .then((response) => {
                    $('#agendamento-manual-nome-mae-paciente').val(response.data.nm_mae);
                    $('#agendamento-manual-nome-pai-paciente').val(response.data.nm_pai);
                    $('#agendamento-manual-convenio').val(response.data.cd_categoria).trigger('change');
                    $('#agendamento-manual-cartao').val(response.data.cartao);
                    $('#data-de-nasc-manual').val(response.data.dt_nasc);
                    $('#agendamento-manual-celular').val(response.data.celular);
                })
                .catch((error) => toastr['error'](error.response.data.message))
                .finally(() => this.loadingPaciente = false);
        }else{
            $('#agendamento-manual-nome-pai-paciente').prop('readonly', false);
            $('#agendamento-manual-nome-pai-paciente').val('');
            $('#agendamento-manual-nome-mae-paciente').prop('readonly', false);
            $('#agendamento-manual-nome-mae-paciente').val('');
            $('#data-de-nasc-manual').prop('readonly', false);
            $('#data-de-nasc-manual').val('');
        }

    });

    $('#cadastro-consulta').modal({
        backdrop: 'static',
        show: false
    });

    $('#agendamento-manual').modal({
        backdrop: 'static',
        show: false
    });
});
