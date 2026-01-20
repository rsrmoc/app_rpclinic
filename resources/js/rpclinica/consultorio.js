import axios from 'axios';
import moment from 'moment';

Alpine.data('app', () => ({
    PROF_LOGADO: profLogado,
    dataInput: null,
    codDoc: null,
    loading: false,
    loadingModal: false,
    atendModal: null,
    loadingPesq: false,
    loadingPesqPac: true,

    loadingPesqAtend: false,
    loading_btn: false,
    messageDanger: null,
    horarios: [],
    viewVip: false,
    modalData: {
        horario: {},
        logs: {}
    },
    modalPac: {
        atend: {},
        anexos: {},
        doc: {},
        agendamento: {}
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
    valuesEstadoCivil: {
        S: 'Solteiro',
        C: 'Casado',
        D: 'Divorciado',
        V: 'Viúvo'
    },
    QueryPesqPac: [],
    QueryPesqPacAtend: [],
    infoPac: null,

    swalWithBootstrapButtons : Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success swal-button",
          cancelButton: "btn btn-danger swal-button",
          input: "form-control"
        },
        buttonsStyling: false
    }),

    init() {
        
        this.loadingModal = false;
        this.loading = false;
        $('#data-input').val(moment().format('YYYY-MM-DD'));
        $('#calendar').datepicker('setDate', 'YYYY-MM-DD');
        this.getHorarios();

        $('#calendar').on('changeDate', () => {
            $('#data-input').val(
                $('#calendar').datepicker('getFormattedDate')
            );

            this.getHorarios()
        });

        $('#form-horario select').on('select2:select', () => {
            this.getHorarios()
        });

        $('#cadastro-consulta').on('hidden.bs.modal', () => {
            this.modalData.horario = {};
            $('#cadastro-consulta form').trigger('reset');
        })
        
    },

    getHorarios() {

        this.loading = true;
        let form = new FormData(document.querySelector('#form-horario'));
        axios.post('/rpclinica/json/horarios-consultorio', form)
        .then((res) => {
            this.horarios = res.data.retorno;
            console.log(this.horarios);
            })
        .catch((err) => {
            this.messageDanger = err.response.data.message;
            console.log(err.response.data); 
        })
        .finally(() => this.loading = false);

    },

    clickHorario(horario,idx) {
        console.log(this.modalData);
        if(horario.permite_atender=='N'){
            Swal.fire({
                title: 'Atenção', 
                text: "Conforme configuração do Sistema, Esse prontuario não será permitido abertura/Edição!",
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#22BAA0', 
                confirmButtonText: 'Ok'      
            });
            return false;
        }

        //this.loading = true;
        if(horario.situacao.em_atend == 'S'){ 
            if( this.PROF_LOGADO == horario.cd_profissional ){ 
                //this.loadingModal = false;
                //this.atendModal = horario.cd_agendamento;
                //$('.modalContinuarAtend').modal('toggle'); 
                location.href = "/rpclinica/consultorio-formularios/"+horario.cd_agendamento;  
            }else{
                toastr['error']('O Profissional logado não permissão para esse atendimento!');  
            } 
            return
        }

        if(horario.sn_prontuario=='N'){
            horario.nm_paciente = horario.paciente.nm_paciente;
            console.log(horario);
            //this.loading = false;
            this.clickPesquisaPac(horario);
        }else{
            this.modalData.horario = horario;
            if(horario.paciente?.vip=='S'){ this.viewVip=true; }else{ this.viewVip=false; }
            $('#cadastro-consulta').modal('toggle');
            //this.loading = false;
        }

    },

 

    clickLog(atendimento,idx) {
        console.log(this.PROF_LOGADO);
        this.modalData.horario = atendimento;
        this.modalData.logs = atendimento.situacao_log;
        console.log(this.modalData.logs);
        $('#modalHistoricoLog').modal('toggle');
    },

    openAgendamentoManual() {
        /*
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
        */
        $('#agendamento-manual input[type=date]').val($('#data-input').val());
        $('#data-de-nasc-manual[type=date]').val(null);
        alert($('#data-input').val());
        $('#agendamento-manual').modal('show');
    },

    getPesquisaAtend() {

        this.loadingPesqAtend = true;
        let form = new FormData(document.querySelector('#form-pesquisa-atend'));
        axios.post('/rpclinica/json/pesquisa/paciente/atendimento', form)
            .then((res) => {
                this.QueryPesqPacAtend = res.data;
                console.log(this.QueryPesqPacAtend);
            })
            .catch((err) => this.messageDanger = err.response.data.message)
            .finally(() => this.loadingPesqAtend = false);

    },

    getPacAtend(info){
        this.infoPac = info;
    },

    getPesquisaPac() {

        this.loadingPesq = true;
        let form = new FormData(document.querySelector('#form-pesquisa-pac'));
        axios.post('/rpclinica/json/pesquisa/paciente', form)
            .then((res) => {
                this.QueryPesqPac = res.data;
                console.log(res.data);
            })
            .catch((err) => this.messageDanger = err.response.data.message)
            .finally(() => this.loadingPesq = false);

    },

    clickPesquisaPac(agenda) {
        this.modalPac.atend = agenda;
        this.loadingPesqPac = true;
        this.loadingPesq = true;
        $('#agendamento-pesq-pac').modal('toggle');
        axios.get(`/rpclinica/json/pesquisa/atendimento?cod=${agenda.cd_agendamento}`)
        .then((res) => {
            this.modalPac.agendamento = res.data.agendamento;
            this.modalPac.anexos = res.data.anexos;
            this.modalPac.doc = res.data.doc;
        })
        .catch((err) => {
            toastr['error'](err.response.data.message);
        })
        .finally(() => {
            this.loadingPesq = false;
            this.loadingPesqPac = false;
        });

        
    },

    imprimirAnamnese(idAgendamento) {
        let url = `/rpclinica/consulta/anamnese/download-pdf/${idAgendamento}`;

        axios.all([
            axios.get(url, { params: { tipo: 'anamnese' }, responseType: 'blob' }),
            axios.get(url, { params: { tipo: 'exame' }, responseType: 'blob' }),
            axios.get(url, { params: { tipo: 'hipotese' }, responseType: 'blob' }),
            axios.get(url, { params: { tipo: 'conduta' }, responseType: 'blob' })
        ])
            .then(axios.spread((anamnese, exame, hipotese, conduta) => {
                window.open(URL.createObjectURL(anamnese.data), 'anamnese.pdf');
                window.open(URL.createObjectURL(exame.data), 'exame_fisico.pdf');
                window.open(URL.createObjectURL(hipotese.data), 'hipotese_diagnostica.pdf');
                window.open(URL.createObjectURL(conduta.data), 'conduta.pdf');
            }))
            .catch((err) => toastr['error']('Houve um erro ao imprimir.'))
    },

    imprimirDocumentos(cdDocumento,idAgendamento) {
        this.loading_btn=true;
        this.codDoc = cdDocumento;
        axios.get(`/rpclinica/json/imprimirDocumentoGeral/${idAgendamento}/${cdDocumento}`, {
            params: { tipo: 'documento', cdDocumento },
            responseType: 'blob'
        })
            .then((res) => {
                window.open(URL.createObjectURL(res.data), 'documento.pdf');
            })
            .catch((err) => toastr['error']('Houve um erro ao imprimir o documento!'))
            .finally(() => this.loading_btn = false);
    },

    imprimirDocumento(cdDocumento,idAgendamento) {

        this.loading_btn=true; 
        this.codDoc = cdDocumento;
        var sn_assinatura = 'N';
        var sn_ocultar_titulo = 'N';
        var sn_rec_esp = 'N';
        var sn_data = 'N';
        var sn_logo = 'N';
        var sn_footer = 'N';
        var sn_header = 'N';
         
        axios.get(`/rpclinica/json/imprimirDocumentoGeral/${idAgendamento}/${cdDocumento}`, {
            params: { tipo: 'documento', header: sn_header,  logo: sn_logo, footer: sn_footer, data: sn_data, assinatura: sn_assinatura, rec_especial: sn_rec_esp, sn_ocultar_titulo: sn_ocultar_titulo },
            responseType: 'blob'
        })
            .then((res) => {
                window.open(URL.createObjectURL(res.data), 'documento.pdf');
            })
            .catch((err) => {
                toastr['error']('Houve um erro ao imprimir o documento!'); 
            })
            .finally(() => this.loading_btn = false);
    },

    titleize(text) {
        text = text.toLowerCase().replace(/(?:^|\s)\S/g, function(a) {
            return a.toUpperCase();
          });

          return text;
    },

    vip(status) {
        console.log(this.modalData.horario.paciente?.cd_paciente);
        axios.post(`/rpclinica/json/paciente-vip`, { status: status,paciente: this.modalData.horario.paciente?.cd_paciente } )
        .then((res) => {

            if(res.data.vip=='S'){ this.viewVip=true; }else{ this.viewVip=false; }
 
        })
        .catch((err) => toastr['error']('Erro ao vincular Paciente como VIP!'));
    },

    reabrirAtend(Atendimento){

        this.swalWithBootstrapButtons.fire({
            title: 'Confirmação',
            html: "<h4 style='font-weight: 400;font-style: italic;'>Deseja reabrir esse atendimento?<h4>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {

                axios.post('/rpclinica/json/consulta/reabrir', { atendimento: Atendimento })
                .then((res) => {
                    console.log(res);
                    if(res.data==true){
                        location.href = 'consulta/'+Atendimento;
                    }
                })
                .catch((err) => toastr['error']('Erro ao reabrir atendimento!'));

            }
        });
 
    },

    imprimirAnamnese(idAgendamento) {
 
       
        var sn_header = 'N';
        var sn_footer = 'N';
        var sn_logo = 'N';
        var sn_data = 'N';
        var sn_assinatura = 'N';
 
        this.loading_btn=true;

        axios.get(`/rpclinica/json/imprimirAnamneseGeral/${idAgendamento}?tipo=anamnese&header=N&logo=N&footer=N&data=N&assinatura=N`, {
            params: { tipo: 'anamnese', header: sn_header,  logo: sn_logo, footer: sn_footer, data: sn_data, assinatura: sn_assinatura },
            responseType: 'blob'
        })
            .then((res) => {
                window.open(URL.createObjectURL(res.data), 'anamnese.pdf');
            })
            .catch((err) => toastr['error']('Houve um erro ao imprimir o documento!'))
            .finally(() => this.loading_btn = false);

    },

    nl2br(str, replaceMode, isXhtml) {

        var breakTag = (isXhtml) ? '<br />' : '<br>';
        var replaceStr = (replaceMode) ? '$1'+ breakTag : '$1'+ breakTag +'$2';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);

    },

    replace(str) {

        let result = str.replace("<p>", "");
        result = result.replace("</p>", "");
        return result;

    },

   
}));

$(document).ready(function() {
    $.fn.datepicker.dates['en'] = {
        days: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabadao'],
        daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        daysMin: ["Do", "Se", "Te", "Qa", "Qi", "Se", "Sa"],
        months: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho', 'Julho', 'Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthsShort: ['Jan','Fev','Mar','Abr','Mai','Jun', 'Jul', 'Ago','Set','Out','Nov','Dez'],
    };
    $('#calendar').datepicker({
        language: 'pt',
        format: 'yyyy-mm-dd'
    });

    $('#agendamento-paciente').select2({
        ajax: {
            url: '/rpclinica/json/pacientes',
            dataType: 'json',
            processResults: (data) => {
                return {
                    results: data
                };
            }
        }
    });

    $('#cadastro-consulta').modal({
        backdrop: 'static',
        show: false
    });
});

