import axios from 'axios';
import moment from 'moment';

var agendamentoDadosMes = null;
var agendamentoDadosDiaControll = null;
var agendamentoDadosMesControll = null;

Alpine.data('app', () => ({

    Empresa: empresa,
    botaoEscala: false,
    Intervalo: intervalo,
    Titulo: null,
    dataInput: null,
    dataCelular: null,
    SituacaoWhast: null,
    foneWhast: null,
    classWhast: 'fa fa-whatsapp whastNeutro',
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
    qtde_sessao: null,
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

    modalRapido:{
        cd_agenda: null,
        cd_escala: null,
        tp_escala: null,
        cd_profissional: null,
        nm_profissional: null,
        edita_profissional: null,
        dt_agenda: null,
        hr_inicio: null,
        hr_fim: null,
        edita_tipo: null,
        tipo: null,
        nm_tipo: null,
        edita_local: null,
        cd_local_atendimento: null,
        nm_local: null,
        edita_especialidade: null,
        cd_especialidade: null,
        nm_especialidade: null,
        cd_paciente: null,
        dt_nasc: null,
        rg: null,
        cpf: null,
        celular: null,
        email: null,
        cd_convenio: null,
        cartao: null,
        validade: null,
    },

    modalAgenda: {
        cd_agendamento: null,
        agenda: null,
        nm_agenda: null,
        cd_escala: null,
        cd_escala_manual: null,
        edita_agenda: null,
        profissional: null,
        nm_profissional: null,
        edita_profissional: null,
        dt_agenda: null,
        hr_inicio: null,
        hr_fim: null,
        tp_atendimento: null,
        edita_local: null,
        local: null,
        nm_local: null,
        edita_especialidade: null,
        especialidade: null,
        nm_especialidade: null,
        paciente: null,
        dt_nasc: null,
        rg: null,
        cpf: null,
        celular: null,
        email: null,
        convenio: null,
        tp_convenio: null,
        cartao: null,
        validade: null,
        observacao: null,
        dadosPaciente: [],
        dadosConta: [],
        procConv: null,
        sn_sessao: null,
        situacao: null,
        dt_agenda: null,
        dt_receb: null,
        recebido: null,
        usuario_receb: null,
        vl_acrescimo: null,
        vl_desconto: null,
        valor: null,
        historico: null,
        dt_presenca: null,
        retorno_presenca: null,
    },
    dadosModal: null,

    loadingPaciente: false,
    loadingAgendamentoSessao: false,
    errorsAgendamentoManual: [],
    dadosPaciente: null,

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

    relacaoAgendas: [],

    filtros: {
        agendas: null,
    },
    addConta:{
        qtde: null,
        proc: null,
        agendamento: null
    },
    totalConta: 0,
    totalContaConf: 0,
    totalContaNConf: 0,

    Hours: businessHours,
    resour: resources,

    recAgendamento: {
        cd_agendamento: null,
        cd_forma: null,
        cd_conta: null,
        dt_transacao: null,
        valor: null,
        parcela: null,
        vl_parcela: [],
        dt_venc: []
    },

    pesquisaPac:{
        nome: null,
        dti: null,
        dtf: null
    },
    queryPesquisaPacFuturo: [],
    queryPesquisaPacHistorico: [],
    escalaManual: null,
    queryAgendamentoRapido: null,
    rapidoAgendamento:{
        data: null,
        escala_manual: null,
        hr_inicio: null,
        hr_fim: null,
        total: 0
    },

    swalWithBootstrapButtons : Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success swal-button",
          cancelButton: "btn btn-danger swal-button",
          input: "form-control"
        },
        buttonsStyling: false
    }),

    init() {

        $('#form-rapido #rapido-pac').on('select2:select', () => {
            var cdPaciente = $('#form-rapido select#rapido-pac').val();

                axios.get(`/rpclinica/json/paciente`, { params: { cd_paciente: cdPaciente } })
                    .then((response) => {
                        this.dadosPaciente = response.data;
                        console.log(response.data);

                        $('#rapido-conv').val(response.data.cd_categoria).trigger('change');
                        this.modalRapido.cd_convenio = response.data.cd_categoria;
                        this.modalRapido.dt_nasc = response.data.dt_nasc;
                        this.modalRapido.cpf = response.data.cpf;
                        this.modalRapido.rg = response.data.rg;
                        this.modalRapido.email = response.data.email;
                        this.modalRapido.celular = response.data.celular;
                        this.modalRapido.cartao = response.data.cartao;
                        this.modalRapido.validade = response.data.dt_validade;

                    })
                    .catch((error) => toastr['error'](error.response.data.message))

        });

        $('#form-rapido #rapido-agenda').on('select2:select', () => {
            var cdAgenda = $('#form-rapido select#rapido-agenda').val();
            this.modalRapido.cd_agenda = cdAgenda;
            this.getAgendaRapido(cdAgenda);
            this.getEscalaRapido();
        });

        $('#form-Pesq #pesqPaciente').on('select2:select', () => {
            var cdPac = $('#form-Pesq select#pesqPaciente').val();
            axios.get('/rpclinica/json/pesquisa/pesquisa-pac?cod=' + cdPac)
                .then((response) => {
                    this.queryPesquisaPacFuturo= response.data.futuro;
                    this.queryPesquisaPacHistorico = response.data.historico;
                    console.log(response.data);

                })
                .catch((error) => toastr['error'](error.response.data.message))
        });

        $('#form-Agenda #agendamento-tipo').on('select2:select', () => {

            if(!this.modalAgenda.dt_agenda){
                toastr['error']("Data da Agenda não informada!");
                return false;
            }
            if(!this.modalAgenda.hr_inicio){
                toastr['error']("Hora incial não informada!");
                return false;
            }

            var tipo = $('#form-Agenda select#agendamento-tipo').val();

            axios.get('/rpclinica/json/agendamento/conteudo-tp-atend?codigo='+$('#form-Agenda select#agendamento-tipo').val()+"&data="+this.modalAgenda.dt_agenda+"&hora="+this.modalAgenda.hr_inicio)
            .then((res) => {
               if(res.data.termino){
                    this.modalAgenda.hr_fim = res.data.termino;
               }
            })
        });

        this.FullCalendar();

    },

    cadastroAgendaRapido(){

        if(!this.modalRapido.tipo){
            this.modalRapido.tipo = $('#form-rapido select#rapido-tipo').val();
        }
        if(!this.modalRapido.cd_profissional){
            this.modalRapido.cd_profissional = $('#form-rapido select#rapido-prof').val();
        }
        if(!this.modalRapido.cd_especialidade){
            this.modalRapido.cd_especialidade = $('#form-rapido select#rapido-espec').val();
        }
        if(!this.modalRapido.cd_local_atendimento){
            this.modalRapido.cd_local_atendimento = $('#form-rapido select#rapido-local').val();
        }

        this.modalRapido.cd_convenio = $('#form-rapido select#rapido-conv').val();
        this.modalRapido.cd_paciente = $('#form-rapido select#rapido-pac').val();

        //axios.post('/rpclinica/json/agendamento/gravar-agenda-rapido', this.modalRapido)
        axios.post('/rpclinica/json/agendamento', this.modalRapido)
        .then((res) => {
            console.log(res);
            if(res.data.cd_agendamento){
                toastr['success']("Agendamento Salvo com sucesso!");
                var data = this.modalRapido.dt_agenda;
                this.limpaModalRapido();
                $('#form-rapido select#rapido-conv').val(null).trigger('change');
                $('#form-rapido select#rapido-pac').val(null).trigger('change');
                $('#form-rapido select#rapido-local').val(null).trigger('change');
                $('#form-rapido select#rapido-espec').val(null).trigger('change');
                $('#form-rapido select#rapido-prof').val(null).trigger('change');
                $('#form-rapido select#rapido-tipo').val(null).trigger('change');
                $('#form-rapido select#rapido-agenda').val(null).trigger('change');
                $('#form-rapido select#rapido-inicio').val(null).trigger('change');
                $('#form-rapido select#rapido-data').val(null).trigger('change');

                $('#modal-agenda-rapida').modal('hide');
                this.queryAgendamentoRapido = null;
                this.rapidoAgendamento.data = null;
                this.rapidoAgendamento.escala_manual = null;
                this.rapidoAgendamento.hr_inicio = null;
                this.rapidoAgendamento.hr_fim = null;
                this.rapidoAgendamento.total= 0;
                document.getElementById("form-rapido").reset();
                /*
                this.init();
                $("#calendar").fullCalendar("gotoDate", (data));
                */

            }
        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => $('#cadastro-consulta .absolute-loading').hide());
    },

    getEscalaRapido(){

        if(!this.modalRapido.cd_agenda){ return false }
        if(!this.modalRapido.dt_agenda){ return false }
        this.rapidoAgendamento.total = 0;
        axios.get('/rpclinica/json/dados-table?tipo=agenda&codigo=' + this.modalRapido.cd_agenda + '&data=' + this.modalRapido.dt_agenda + '&info=rapido')

        .then((res) => {

            if(res.data.error == true){
                this.modalRapido.cd_escala = null;
                toastr['error'](res.data.message);
                return false;
            }

            this.queryAgendamentoRapido = res.data;
            this.rapidoAgendamento.data = res.data.agendamento[0]?.data_agenda;
            this.rapidoAgendamento.hr_inicio = res.data.agendamento[0]?.hr_agenda;
            this.rapidoAgendamento.hr_fim = res.data.agendamento[0]?.hr_final;
            this.modalRapido.cd_escala = res.data.escala?.cd_escala_agenda;

            if(res.data.escala?.escala_manual=='S'){
                this.rapidoAgendamento.escala_manual = ' Escala Avulsa ';
            }else{
                this.rapidoAgendamento.escala_manual = null;
            }

            this.rapidoAgendamento.total = res.data.agendamento.length;

        })
        .catch((err) => {
            toastr['error']('Erro ao consultar escala!')
        });

    },

    getAgendaRapido(agenda){

        axios.get('/rpclinica/json/dados-table?tipo=cad_agenda&codigo=' + agenda)
        .then((res) => {

            console.log(res);
            this.modalRapido.cd_agenda = res.data.cd_agenda;
            this.modalRapido.cd_profissional = res.data.cd_profissional;
            this.modalRapido.nm_profissional = res.data.profissional?.nm_profissional.toUpperCase();
            this.modalRapido.edita_profissional = res.data.profissional_editavel;

            this.modalRapido.edita_tipo = res.data.tipo_atend_editavel;
            this.modalRapido.tipo = res.data.tipo_agendamento;
            this.modalRapido.nm_tipo = res.data.tp_agendamento?.nm_tipo_atendimento.toUpperCase();

            this.modalRapido.edita_local = res.data.local_atendimento_editavel;
            this.modalRapido.cd_local_atendimento = res.data.cd_local_atendimento;
            this.modalRapido.nm_local = res.data.local?.nm_local.toUpperCase();

            this.modalRapido.edita_especialidade = res.data.especialidade_editavel;
            this.modalRapido.cd_especialidade = res.data.cd_especialidade;
            this.modalRapido.nm_especialidade = res.data.especialidade?.nm_especialidade.toUpperCase();

            console.log(this.modalRapido);


        });
    },

    confereProcConta(conta){

        axios.put(`/rpclinica/json/agendamento/confere-conta-proc`,conta)
        .then((res) => {

            console.log(res);

            this.modalAgenda.dadosConta = res.data.query;

            this.modalAgenda.dadosConta.forEach((elem) => {
                this.totalContaConf=0;
                this.totalContaNConf =0;
                this.totalConta = 0;
                if(elem.sn_confere == 'S'){
                    this.totalContaConf = this.totalContaConf + (elem.vl_total ? elem.vl_total : 0);
                }else{
                    this.totalContaNConf = this.totalContaNConf + (elem.vl_total ? elem.vl_total : 0);
                }

                this.totalConta = this.totalConta + (elem.vl_total ? elem.vl_total : 0);
            });

            toastr['success'](res.data.message);


        })
        .catch((err) => toastr['error'](err.response))
        .finally(() => $('#cadastro-consulta .absolute-loading').hide());

    },

    deleteProcConta(conta){

        this.swalWithBootstrapButtons.fire({
            title: 'Confirmação',
            html: "<h4 style='font-weight: 500;font-style: italic;'>Deseja excluir esse procedimento?</h4>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.totalContaConf=0;
                this.totalContaNConf =0;
                this.totalConta = 0;
                axios.delete(`/rpclinica/json/agendamento/delete-conta-proc/${conta.id_conta}`)
                .then((res) => {

                    this.modalAgenda.dadosConta = res.data.query;
                    this.modalAgenda.dadosConta.forEach((elem) => {

                        if(elem.sn_confere == 'S'){
                            this.totalContaConf = this.totalContaConf + (elem.vl_total ? elem.vl_total : 0);
                        }else{
                            this.totalContaNConf = this.totalContaNConf + (elem.vl_total ? elem.vl_total : 0);
                        }

                        this.totalConta = this.totalConta + (elem.vl_total ? elem.vl_total : 0);
                    });

                    toastr['success'](res.data.message);
                })
                .catch((err) => toastr['error'](err.response))
                .finally(() => $('#cadastro-consulta .absolute-loading').hide());

            }
        });


    },

    addProcConta() {

        this.addConta.proc =$('#GetFinanceiro select#procConta').val();
        this.addConta.agendamento = this.modalAgenda.cd_agendamento;
        axios.post('/rpclinica/json/agendamento/add-conta-proc', this.addConta)
        .then((res) => {
            this.modalAgenda.dadosConta = res.data.query;
            toastr['success'](res.data.message);
            this.totalContaConf=0;
            this.totalContaNConf =0;
            this.totalConta = 0;
            console.log(this.modalAgenda.dadosConta);
            this.modalAgenda.dadosConta.forEach((elem) => {

                if(elem.sn_confere == 'S'){
                    this.totalContaConf = this.totalContaConf + (elem.vl_total ? elem.vl_total : 0);
                }else{
                    this.totalContaNConf = this.totalContaNConf + (elem.vl_total ? elem.vl_total : 0);
                }

                this.totalConta = this.totalConta + (elem.vl_total ? elem.vl_total : 0);
            });
            $('#GetFinanceiro select#procConta').val(null);
            this.addConta.qtde=null;
        })
        .catch((err) => toastr['error'](err.response.data.message))

    },

    FullCalendar() {


        $('#calendar').fullCalendar('destroy');
        $('#calendar').fullCalendar({

            customButtons: {
                modal: {
                    click: function () {
                        $('.bs-example-modal-sm').modal('toggle');
                    }
                },
                gotoDate: {
                    text: 'Go to date',
                    click: function () {
                       $(this).datepicker({
                           autoclose: true
                       });
                       $(this).datepicker().on('changeDate', function (e) {
                           $("#calendar").fullCalendar("gotoDate", e.date);
                       });
                       $(this).datepicker('show');

                    }
                },
                paciente: {
                    click: function () {
                        $('.PesqAtendimento').modal('toggle');
                    }
                },
                agendamento: {
                    click: function () {
                        $('.modal-agenda-rapida').modal('toggle');
                    }
                },
                envio_whast: {
                    click: function () {
                        $('#modal-envio-whast').modal('toggle');
                    }
                },
            },
            buttonIcons: {
                modal: 'fa fa fa-filter btn-vermelho',
                gotoDate: 'fa fa fa-calendar btn-roxo',
                paciente: 'fa fa fa-users btn-laranja',
                agendamento: 'fa fa fa-bolt btn-azul',
                envio_whast: 'fa fa fa-whatsapp btn-verde',
            },

            header: {
               left: 'modal gotoDate paciente agendamento envio_whast',
                center: 'prev title next ',
                right: 'today agendaDay,agendaWeek,month'
            },

            defaultView: 'agendaDay',
            editable: true,
            droppable: true,
            eventLimit: true,
            selectable: true,
            selectHelper: true,
            minTime: (this.Empresa.hr_inicial) ? this.Empresa.hr_inicial : '07:00:00',
            maxTime: (this.Empresa.hr_final) ? this.Empresa.hr_final : '20:00:00',


            slotDuration: (this.Intervalo) ? this.Intervalo : '00:30:00',
            slotLabelInterval: (this.Intervalo) ? this.Intervalo : '00:30:00',
            selectMirror: true,
            dayMaxEvents: true,
            allDaySlot: true,
            weekends: true,
            locale: 'pt-br',
            groupByResource: false,
            timeFormat: 'HH:mm',
            slotLabelFormat: 'HH:mm',

            allDayText: '24 horas',
            //columnFormat: 'dddd',
            defaultDate: Date(),
            hiddenDays: [0],

            businessHours:this.Hours,

            resources: this.resour,


            select: (start, end, jsEvent, view, resource) => {
                this.limpaModalAgenda();
                var dadosHorario = {
                    "start": $.fullCalendar.formatDate(start, 'YYYY-MM-DD HH:mm'),
                    "dt_start": $.fullCalendar.formatDate(start, 'DD/MM/YYYY HH:mm'),
                    "data_start": $.fullCalendar.formatDate(start, 'YYYY-MM-DD'),
                    "hr_start": $.fullCalendar.formatDate(start, 'HH:mm'),
                    "end": $.fullCalendar.formatDate(end, 'YYYY-MM-DD HH:mm'),
                    "dt_end": $.fullCalendar.formatDate(end, 'DD/MM/YYYY HH:mm'),
                    "data_end": $.fullCalendar.formatDate(end, 'YYYY-MM-DD'),
                    "hr_end": $.fullCalendar.formatDate(end, 'HH:mm'),
                    "resource": (resource) ? resource.id : null,
                    "id": null
                };
                this.limpaModalAgenda.dt_agenda = dadosHorario.data_start;
                this.select(dadosHorario);

            },

            drop: function (arg) {
                console.log(arg);
            },

            eventClick: (dados) => {
                this.modalAgenda.cd_agendamento = null;
                var dadosHorario = {
                    "id": dados.id,
                    "resource": null,
                };
                this.clickEvent(dadosHorario);
            },

            eventDidMount: function (info) {
                alert(info.event.extendedProps);
                alert('eventDidMount');

            },

            eventDrop: (event) => {
                this.modalAgenda.cd_agendamento = null;
                var dadosHorario = {
                    "start": $.fullCalendar.formatDate(event.start, 'YYYY-MM-DD HH:mm'),
                    "dt_start": $.fullCalendar.formatDate(event.start, 'DD/MM/YYYY HH:mm'),
                    "data_start": $.fullCalendar.formatDate(event.start, 'YYYY-MM-DD'),
                    "hr_start": $.fullCalendar.formatDate(event.start, 'HH:mm'),
                    "end": $.fullCalendar.formatDate(event.end, 'YYYY-MM-DD HH:mm'),
                    "dt_end": $.fullCalendar.formatDate(event.end, 'DD/MM/YYYY HH:mm'),
                    "data_end": $.fullCalendar.formatDate(event.end, 'YYYY-MM-DD'),
                    "hr_end": $.fullCalendar.formatDate(event.end, 'HH:mm'),
                    "id": event.id,
                    "resource": null,
                };
                this.eventResize(dadosHorario);
            },


            eventRender: function eventRender(event, element, view) {

                $.ajax({
                url: "/rpclinica/json/agendamento/conteudo-evento?titulo="+event.titulo+"&icone="+event.icone+"&nm_paciente="+event.nm_paciente+"&dt_nasc="+event.dt_nasc+"&situacao="+event.situacao+"&usuario_geracao="+event.usuario_geracao+"&profissional="+event.nm_profissional+"&tipo="+event.tipo+"&obs="+encodeURI(event.obs)+"&user="+encodeURI(event.usuario_geracao),
                method: "GET",
                data: null,

                success: function (data) {
                    element.find('.fc-title').append(data);
                    if(event.tipo=='E'){
                        if(event.situacao=='bloqueado'){
                            element.find('.fc-time').append('  &nbsp;&nbsp;<b>Bloqueado</b> ');
                        }else{
                            element.find('.fc-time').append(' '+event.titulo);
                        }
                    }

                    element.popover({
                        animation:true,
                        delay: 300,
                        html: true,
                        placement: 'top',
                        content:data,
                        trigger: 'hover'
                    });

                }
                });
                element.find('.fc-title').append("");
            },
 
            eventResize: (dados) => {

                var dadosHorario = {
                    "start": $.fullCalendar.formatDate(dados.start, 'YYYY-MM-DD HH:mm'),
                    "dt_start": $.fullCalendar.formatDate(dados.start, 'DD/MM/YYYY HH:mm'),
                    "data_start": $.fullCalendar.formatDate(dados.start, 'YYYY-MM-DD'),
                    "hr_start": $.fullCalendar.formatDate(dados.start, 'HH:mm'),
                    "end": $.fullCalendar.formatDate(dados.end, 'YYYY-MM-DD HH:mm'),
                    "dt_end": $.fullCalendar.formatDate(dados.end, 'DD/MM/YYYY HH:mm'),
                    "data_end": $.fullCalendar.formatDate(dados.end, 'YYYY-MM-DD'),
                    "hr_end": $.fullCalendar.formatDate(dados.end, 'HH:mm'),
                    "id": dados.id
                };
                this.eventResize(dadosHorario);


            },


            events:  (start, end,  timezone, callback) => {
                   
                    $.ajax({
                        url: url_eventos,
                        data: {
                            start: start.unix(),
                            end: end.unix(),
                            agendas: AgendaItens
                        },
                        success: function (doc) {
                            callback(doc);
                        }
                    });
            }

        });
        
        /*
        $(".fc-left").append(this.button);

        $('#popo').change(function (ev) {
           $("#calendar").fullCalendar("gotoDate", ev.target.value);
        });
        */
    },

    eventResize(dadosHorario) {
        if (dadosHorario.data_start == dadosHorario.data_end) {
            axios.post('/rpclinica/json/agendamento/alterar-horario', dadosHorario)
                .then((res) => {

                    this.FullCalendar();
                    $("#calendar").fullCalendar("gotoDate", dadosHorario.data_start );
                    toastr['success'](res.data.message);

                })
                .catch((err) => toastr['error'](err.response.data.message))
        } else {
            toastr['error']('O agendamento não pode ter intervalo entre dias!');
        }


    },

    select_ant(dadosHorario) {
        this.CodAgenda = dadosHorario.resource;
        if(!this.CodAgenda){
            var retorno = AgendaItens.split(",");
            this.CodAgenda=retorno[0];
        }
        if(!this.CodAgenda){
            toastr['error']("Agenda não informada!");
            return false;
        }


        $('#cadastro-consulta select#cod_agenda').val(this.CodAgenda).select2();

        this.modalAgenda.dt_agenda = dadosHorario.data_start;
        this.modalAgenda.hr_inicio = dadosHorario.hr_start;
        this.modalAgenda.hr_fim = dadosHorario.hr_end;

        $('#cadastro-consulta select#cod_agenda').val(this.CodAgenda).select2();
        axios.get('/rpclinica/json/dados-table?tipo=agenda&codigo=' + this.CodAgenda + '&data=' + dadosHorario.data_start + '&hr_start=' + dadosHorario.dt_start)

        .then((res) => {
            console.log(res);
            if(res.data.error == true){
                if(res.data.tp_error=='escala'){
                    this.botaoEscala=true;
                }
                toastr['error'](res.data.message);
            }else{

            }

        });

        $('#cadastro-consulta').modal('toggle');

    },

    select(dadosHorario) {
        this.escalaManual = null;
        $('#form-Agenda select#agendamento-paciente').val(null).trigger('change');
        $('#form-Agenda select#agendamento-convenio').val(null).trigger('change');

        this.modalAgenda.dt_agenda = dadosHorario.data_start;
        this.modalAgenda.hr_inicio = dadosHorario.hr_start;
        this.modalAgenda.hr_fim = dadosHorario.hr_end;
        this.CodAgenda = dadosHorario.resource;

        if(!this.CodAgenda){
            var retorno = AgendaItens.split(",");
            this.CodAgenda=retorno[0];
        }

        if(!this.CodAgenda){
            toastr['error']("Agenda não informada!");
            return false;
        }

        if(this.CodAgenda){

            $('#cadastro-consulta select#cod_agenda').val(this.CodAgenda).select2();
            axios.get('/rpclinica/json/dados-table?tipo=agenda&codigo=' + this.CodAgenda + '&data=' + dadosHorario.data_start + '&hr_start=' + dadosHorario.dt_start)

            .then((res) => {

                if(res.data.error == true){
                    if(res.data.tp_error=='escala'){
                        this.botaoEscala=true;
                    }
                    toastr['error'](res.data.message);
                }else{


                    if(res.data.escala?.escala_manual=='S'){
                        this.escalaManual =  res.data.escala.escala_manual;
                        this.modalAgenda.cd_escala_manual = res.data.escalas_manual[0].cd_escala_agenda;
                    }

                    this.botaoEscala=false;
                    this.modalAgenda.cd_escala = res.data.escala?.cd_escala_agenda;

                    this.modalAgenda.edita_profissional = res.data.profissional_editavel;
                    this.modalAgenda.nm_profissional = res.data.profissional?.nm_profissional;
                    this.modalAgenda.profissional = res.data.cd_profissional;
                    this.modalAgenda.edita_local = res.data.local_atendimento_editavel;
                    this.modalAgenda.nm_local = res.data.local?.nm_local;
                    this.modalAgenda.local = res.data.cd_local_atendimento;
                    this.modalAgenda.edita_especialidade = res.data.especialidade_editavel;
                    this.modalAgenda.nm_especialidade = res.data.especialidade?.nm_especialidade;
                    this.modalAgenda.especialidade = res.data.cd_especialidade;
                    this.modalAgenda.dadosPaciente = null;
                    this.modalAgenda.situacao = null;
                }

            })
            .catch((err) => {
                this.messageDanger = err.response.data.message
            })

        }
        this.modalAgenda.dt_nasc=null;
        $('#form-Agenda #data-de-nasc').val('');
        this.modalAgenda.rg=null;
        $('#form-Agenda #rg').val('');
        this.modalAgenda.cpf=null;
        $('#form-Agenda #cpf').val('');
        this.modalAgenda.celular=null;
        $('#form-Agenda #agendamento-celular').val('');
        this.modalAgenda.email=null;
        $('#form-Agenda #email').val('');
        this.modalAgenda.cartao=null;
        $('#form-Agenda #agendamento-cartao').val('');
        this.modalAgenda.validade=null;
        $('#form-Agenda #cartao-validade').val('');
        this.modalAgenda.observacao=null;
        $('#form-Agenda #obs-agendamento').val('');

        $('#form-Agenda #cod_agenda').on('select2:select', () => {

            this.CodAgenda =  $('#cadastro-consulta select#cod_agenda').val();

            axios.get('/rpclinica/json/dados-table?tipo=agenda&codigo=' + this.CodAgenda + '&data=' + dadosHorario.data_start)
            .then((res) => {

                if(res.data.error == true){
                    toastr['error'](res.data.message);
                    this.modalAgenda.cd_escala = null;
                    this.modalAgenda.edita_profissional = null;
                    this.modalAgenda.nm_profissional = null;
                    this.modalAgenda.profissional = null;
                    this.modalAgenda.edita_local = null;
                    this.modalAgenda.nm_local = null;
                    this.modalAgenda.local = null;
                    this.modalAgenda.edita_especialidade = null;
                    this.modalAgenda.nm_especialidade = null;
                    this.modalAgenda.especialidade = null;
                    this.modalAgenda.dadosPaciente = null;
                    this.modalAgenda.situacao = null;

                }else{

                    this.escalaManual =  res.data.escalas_manual.escala_manual;
                    this.modalAgenda.cd_escala = (res.data.escalas) ? res.data.escalas[0].cd_escala_agenda : null;
                    this.modalAgenda.cd_escala_manual = res.data.escalas_manual.cd_escala_agenda;
                    this.modalAgenda.edita_profissional = res.data.profissional_editavel;
                    this.modalAgenda.nm_profissional = res.data.profissional?.nm_profissional;
                    this.modalAgenda.profissional = res.data.cd_profissional;
                    this.modalAgenda.edita_local = res.data.local_atendimento_editavel;
                    this.modalAgenda.nm_local = res.data.local?.nm_local;
                    this.modalAgenda.local = res.data.cd_local_atendimento;
                    this.modalAgenda.edita_especialidade = res.data.especialidade_editavel;
                    this.modalAgenda.nm_especialidade = res.data.especialidade?.nm_especialidade;
                    this.modalAgenda.especialidade = res.data.cd_especialidade;
                    this.modalAgenda.dadosPaciente = null;
                    this.modalAgenda.situacao = null;
                    //this.modalAgenda.hr_fim = res.data.termino;
                }


            })
            .catch((err) => {
                this.messageDanger = err.response.data.message
            })
            .finally(() => this.loading = false);

        });

        if (dadosHorario.data_start == dadosHorario.data_end) {

            this.Titulo = 'Novo Agendamento';
            this.SituacaoWhast = null;
            this.foneWhast = null;
            this.foneWhast = null;
            this.classWhast = 'fa fa-whatsapp whastNeutro';
            const element = document.querySelector('#Zap');
            element.classList.remove('whastValido');
            element.classList.remove('whastInvalido');
            $('#cadastro-consulta').modal('toggle');


        } else {
            toastr['error']('O agendamento não pode ter intervalo entre dias!');
        }

    },

    atualizarPaciente() {

        $('#cadastro-consulta .absolute-loading').show();
        let form = new FormData(document.querySelector('#form-pac'));

        axios.post('/rpclinica/json/pacientes-update-join', form)
            .then((res) => {
                toastr['success']('Paciente atualizado com sucesso!');
                this.FullCalendar();
                $("#calendar").fullCalendar("gotoDate", (this.modalAgenda.dt_agenda) ? this.modalAgenda.dt_agenda : null );
                // $('#cadastro-consulta').modal('hide');
            })
            .catch((err) => toastr['error']('Erro na atualização do Paciente! '))
            .finally(() => $('#cadastro-consulta .absolute-loading').hide());
    },

    atualizarAgendamento() {

        this.modalData.errors = [];
        $('#cadastro-consulta .absolute-loading').show();
        let form = new FormData(document.querySelector('#form-Agenda'));
        //let sn_presenca = $("#sn_presenca").is(':checked');

        //axios.post('/rpclinica/json/agendamento?confir='+sn_presenca, form)
        axios.post('/rpclinica/json/agendamento', form)
            .then((res) => {
                toastr['success']('O agendamento foi realizado com sucesso!');
                this.FullCalendar();
                $("#calendar").fullCalendar("gotoDate", this.modalAgenda.dt_agenda );
                this.limpaModalAgenda();
                $('#cadastro-consulta').modal('hide');
                console.log(res);

            })
            .catch((err) => {
                Object.values(err.response.data.errors).forEach((errors) => {
                    this.modalData.errors = this.modalData.errors.concat(errors);
                })
            })
            .finally(() => $('#cadastro-consulta .absolute-loading').hide());

    },

    buttonAgendamento(situacao) {

        switch (situacao) {
            case "agendado":
                return '<span class="btnAgendado"> <i class="fa fa-calendar-o" style="padding-left:2px; "></i>  &nbsp;&nbsp; Agendado&nbsp;&nbsp;&nbsp;<span class="caret"></span></span>';
                break;
            case "confirmado":
                return '<span class="btnConfirmado"><i class="fa fa-check" style="padding-left:2px;  "></i> &nbsp;&nbsp; Confirmado&nbsp;&nbsp;&nbsp;<span class="caret"></span></span>';
                break;
            case "aguardando":
                return '<span class="btnAguardando"> <span class="glyphicon glyphicon-time" aria-hidden="true"  style="padding-left:2px; padding-right: 10px;  "></span>  &nbsp;&nbsp; Aguardando&nbsp;&nbsp;&nbsp;<span class="caret"></span></span>';
                break;
            case "atendido":
                return '<span class="btnAtendido"> <i class="fa fa-stethoscope" style="padding-left:2px;  "></i>  &nbsp;&nbsp; Atendido&nbsp;&nbsp;&nbsp;<span class="caret"></span></span>';
                break;
            case "atendimento":
                return '<span class="btnAtendimento"> <i class="fa fa-user-md" style="padding-left:2px;  "></i>  &nbsp;&nbsp; Em Atendimento&nbsp;&nbsp;&nbsp;<span class="caret"></span></span>';
                break;
            case "cancelado":
                return '<span class="btnCancelado"> <span aria-hidden="true" class="icon-close" style="padding-right: 10px;"></span> &nbsp;&nbsp;  Cancelado&nbsp;&nbsp;&nbsp;<span class="caret"></span></span>';
                break;
            case "faltou":
                return '<span class="btnFaltou"> <span aria-hidden="true" class="icon-user-unfollow" style="padding-right: 10px; color:#ea0033; "></span>  &nbsp;&nbsp; Faltou&nbsp;&nbsp;&nbsp;<span class="caret"></span></span>';
                break;
            default:
                return "<span><span aria-hidden='true' class='icon-share-alt'></span> &nbsp;&nbsp;Alterar Situações &nbsp;&nbsp;&nbsp;<span class='caret'></span> </span>";
        }
    },

    atualizaStatus(situacao) {


        let form = new FormData(document.querySelector('#form-Agenda'));

        console.log(form);

        axios.put('/rpclinica/json/atualiza-status-agendamento',{ cd_agendamento: this.modalAgenda.cd_agendamento, situacao: situacao  })
        .then((res) => {

            if(res.data.retorno.sn_presenca==true){
                this.modalAgenda.dt_presenca= (res.data.retorno.dt_presenca) ? res.data.retorno.dt_presenca : null;
                this.modalAgenda.retorno_presenca= (res.data.retorno.retorno_presenca) ? res.data.retorno.retorno_presenca : null;
                /*
                $('#check-agendamento-presenca span').addClass('checked');
                $('#check-agendamento-presenca input').prop('checked', true);
                */
            }else{
                this.modalAgenda.dt_presenca=null;
                this.modalAgenda.retorno_presenca=null;
                /*
                $('#check-agendamento-presenca  span').removeClass('checked');
                $('#check-agendamento-presenca input').prop('checked', false);
                */
            }




            this.FullCalendar();
            $("#calendar").fullCalendar("gotoDate", (this.modalAgenda.dt_agenda) ? this.modalAgenda.dt_agenda : null );
            toastr['success'](res.data.message);
            $("#situacaoButton").html(this.buttonAgendamento(situacao));

        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => $('#cadastro-consulta .absolute-loading').hide());

    },

    clickEvent(dadosHorario) {

        //this.limpaModalAgenda();
        this.escalaManual = null;

        axios.get('/rpclinica/json/dados-table?tipo=agendamento&codigo=' + dadosHorario.id)
        .then((res) => {
            console.log(res.data);
                this.dadosModal = res.data;
                $('#cadastro-consulta select#cod_agenda').val(res.data.cd_agenda).select2();
                this.modalAgenda.cd_agendamento = res.data.cd_agendamento;
                this.modalAgenda.agenda = res.data.cd_agenda;
                this.modalAgenda.cd_escala = (res.data.escalas?.cd_escala_agenda) ? res.data.escalas?.cd_escala_agenda : null;
                this.modalAgenda.sn_sessao = (res.data.escalas?.sn_sessao) ? res.data.escalas?.sn_sessao : null;
                this.modalAgenda.nm_agenda = res.data.agenda?.nm_agenda;
                this.modalAgenda.edita_agenda = null;
                this.modalAgenda.profissional = res.data.cd_profissional;
                this.modalAgenda.nm_profissional = res.data.profissional?.nm_profissional;
                this.modalAgenda.edita_profissional = res.data.agenda?.profissional_editavel;
                this.modalAgenda.dt_agenda = res.data.dt_agenda;
                this.modalAgenda.hr_inicio = res.data.hr_agenda;
                this.modalAgenda.hr_fim = res.data.hr_final;
                this.modalAgenda.tp_atendimento = res.data.tipo;
                this.modalAgenda.edita_local = res.data.agenda?.local_atendimento_editavel;
                this.modalAgenda.local = res.data.cd_local_atendimento;
                this.modalAgenda.nm_local = res.data.local?.nm_local;
                this.modalAgenda.edita_especialidade = res.data.agenda?.especialidade_editavel;
                this.modalAgenda.especialidade = res.data.cd_especialidade;
                this.modalAgenda.nm_especialidade = res.data.especialidade?.nm_especialidade;
                this.modalAgenda.paciente = res.data.cd_paciente;
                this.modalAgenda.dt_nasc = res.data.paciente?.dt_nasc;
                this.modalAgenda.rg = res.data.paciente?.rg;
                this.modalAgenda.cpf = res.data.paciente?.cpf;
                this.modalAgenda.celular = res.data.paciente?.celular;
                this.modalAgenda.email = res.data.paciente?.email;
                this.modalAgenda.convenio = res.data.cd_convenio;
                this.modalAgenda.tp_convenio = res.data.convenio.tp_convenio;
                this.modalAgenda.cartao = res.data.cartao;
                this.modalAgenda.validade = res.data.validade;
                this.modalAgenda.observacao = res.data.obs;
                this.modalAgenda.dadosPaciente = res.data.paciente;
                this.modalAgenda.dadosConta = res.data.contas;
                this.modalAgenda.procConv = res.data.convenio?.procedimentos_convenio;
                this.sn_sessao = res.data.agenda?.nm_agenda;
                this.modalAgenda.situacao = res.data.situacao;
                this.modalAgenda.dt_agenda = res.data.dt_agenda;
                this.totalContaConf=0;
                this.totalContaNConf =0;
                this.totalConta = 0;
                this.modalAgenda.recebido = res.data.recebido;
                this.modalAgenda.usuario_receb = res.data.usuario_receb;
                this.modalAgenda.vl_acrescimo = res.data.vl_acrescimo;
                this.modalAgenda.vl_desconto = res.data.vl_desconto;
                this.modalAgenda.valor = res.data.valor;
                this.modalAgenda.dt_receb = res.data.dt_receb;
                this.modalAgenda.historico = res.data.historico;
                this.Titulo = res.data.paciente?.nm_paciente+ ' [ '+ res.data.cd_agendamento + ' ]';
                this.modalAgenda.dt_presenca= (res.data.dt_presenca) ? res.data.dt_presenca : null;
                this.modalAgenda.retorno_presenca= (res.data.retorno_presenca) ? res.data.retorno_presenca : null;
                /*
                if (res.data.sn_presenca==1) {
                    $('#check-agendamento-presenca span').addClass('checked');
                    $('#check-agendamento-presenca input').prop('checked', true);
                }else{
                    $('#check-agendamento-presenca  span').removeClass('checked');
                    $('#check-agendamento-presenca input').prop('checked', false);
                }
                */


                this.modalAgenda.dadosConta.forEach((elem) => {
                    if(elem.sn_confere == 'S'){
                        this.totalContaConf = this.totalContaConf + (elem.vl_total ? elem.vl_total : 0);
                    }else{
                        this.totalContaNConf = this.totalContaNConf + (elem.vl_total ? elem.vl_total : 0);
                    }

                    this.totalConta = this.totalConta + (elem.vl_total ? elem.vl_total : 0);
                });

                this.recAgendamento.cd_agendamento = this.modalAgenda.cd_agendamento;
                this.recAgendamento.valor = this.totalConta;
                this.recAgendamento.dt_transacao = this.modalAgenda.dt_agenda;


                $('#cadastro-consulta select#pac-uf').val(res.data.paciente?.uf).trigger('change');

                $('#cadastro-consulta select#pac-sexo').val(res.data.paciente?.sexo).trigger('change');
                $('#cadastro-consulta select#pac-estado_civil').val(res.data.paciente?.estado_civil).trigger('change');
                $('#cadastro-consulta select#pac-convenio').val(res.data.paciente?.cd_categoria).trigger('change');
                $('#cadastro-consulta select#pac-vip').val(res.data.paciente?.vip).trigger('change');

                $('#cadastro-consulta select#agendamento-profissional').val(res.data.cd_profissional).trigger('change');
                $('#cadastro-consulta select#co_agenda').val(res.data.cd_agenda).trigger('change');
                $('#cadastro-consulta select#agendamento-local').val(res.data.cd_local_atendimento).trigger('change');
                $('#cadastro-consulta select#agendamento-tipo').val(res.data.tipo).trigger('change');
                $('#cadastro-consulta select#agendamento-especialidade').val(res.data.cd_especialidade).trigger('change');
                $('#cadastro-consulta select#agendamento-convenio').val(res.data.cd_convenio).trigger('change');

                if (res.data.cd_paciente) {

                    let newOption = new Option(res.data.paciente?.nm_paciente, res.data.cd_paciente, false, false);
                    $('#cadastro-consulta select#agendamento-paciente').append(newOption).trigger('change');
                    $('#cadastro-consulta select#agendamento-paciente').val(res.data.cd_paciente).trigger('change');

                }

        });

        $('#cadastro-consulta').modal('toggle');
    },

    parcelamento(parcela){

        console.log(parcela);
        if(parcela){
            this.recAgendamento.parcela = Number(parcela) ;
        }

    },

    bloquearHorario() {
        if (this.modalAgenda.cd_agendamento == null) {
            this.CodAgenda = $('#form-Agenda select#cod_agenda').val();
            if (this.CodAgenda) {
                this.swalWithBootstrapButtons.fire({
                    title: 'Confirmação',
                    html: "<h4 style='font-weight: 500;font-style: italic;'>Deseja bloquear esse horário?</h4>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#22BAA0',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Não',
                    confirmButtonText: 'Sim'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#cadastro-consulta .absolute-loading').show();
                        axios.post('/rpclinica/json/agendamento/bloquear-horario', { cd_agenda: this.CodAgenda, data: this.modalAgenda.dt_agenda, hr_inicio: this.modalAgenda.hr_inicio, hr_fim: this.modalAgenda.hr_fim,obs: this.modalAgenda.observacao })
                            .then((res) => {
                                this.FullCalendar();
                                $("#calendar").fullCalendar("gotoDate", (this.modalAgenda.dt_agenda) ? this.modalAgenda.dt_agenda : null );
                                toastr['success'](res.data.message);
                                $('#cadastro-consulta').modal('hide');
                            })
                            .catch((err) => toastr['error'](err.response.data.message))
                            .finally(() => $('#cadastro-consulta .absolute-loading').hide());
                    }
                });
            } else {
                toastr['warning']("Favor informar a agenda para realizar o bloqueio!");
            }
        } else {
            toastr['warning']("Existe um evento para esse horario, favor exluir para realizar o bloqueio!");
        }

    },

    gerarEscala() {

        this.modalAgenda.agenda = this.CodAgenda;

        if (this.modalAgenda.cd_agendamento == null) {
            this.CodAgenda = $('#form-Agenda select#cod_agenda').val();
            if (this.CodAgenda) {
                if(!this.modalAgenda.hr_inicio){
                    toastr['warning']("Horario Inicial não informado!");
                    return false;
                }
                if(!this.modalAgenda.hr_fim){
                    toastr['warning']("Horario Final não informado!");
                    return false;
                }
                if(!this.modalAgenda.dt_agenda){
                    toastr['warning']("Data da agenda não informado!");
                    return false;
                }

                this.swalWithBootstrapButtons.fire({
                    title: 'Confirmação',
                    html: "<h4 style='font-weight: 500;font-style: italic;'>Deseja gerar escala Avulsa? <br><b>Data:</b>   "+this.modalAgenda.dt_agenda+"<br><b>Horario Inicial:</b> "+this.modalAgenda.hr_inicio+"<br><b>Horario Final:</b> "+this.modalAgenda.hr_fim+"</h4>",

                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#22BAA0',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Não',
                    confirmButtonText: 'Sim'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#cadastro-consulta .absolute-loading').show();
                        axios.post('/rpclinica/json/escala_manual',this.modalAgenda )
                            .then((res) => {
                                this.FullCalendar();
                                $("#calendar").fullCalendar("gotoDate", (this.modalAgenda.dt_agenda) ? this.modalAgenda.dt_agenda : null );
                                toastr['success']('Escala criada com sucesso!');
                                $('#cadastro-consulta').modal('hide');
                            })
                            .catch((err) => toastr['error'](err.response.data.message))
                            .finally(() => $('#cadastro-consulta .absolute-loading').hide());
                    }
                });
            } else {
                toastr['warning']("Favor informar a agenda para realizar o bloqueio!");
            }
        } else {
            toastr['warning']("Existe um evento para esse horario, favor exluir para realizar o bloqueio!");
        }

    },

    recebimento(){
        this.swalWithBootstrapButtons.fire({
            title: 'Dados do Recebido',
            icon: 'warning',
            html:`<table width="100%" cellspacing="5" > <tr> <td width="35%"> <label>Vl. Recebido: <span class="red normal">*</span></label><input type="text" x-mask:dynamic="$money($input, ',')"  class="form-control" id="receb_valor" > <td> <td width="27%"> <label>Desconto: </label><input type="text" class="form-control" x-mask:dynamic="$money($input, ',')"  id="receb_desc" > <td>  <td width="27%"> <label>Acrescimo:  </label><input type="text" class="form-control" id="receb_acres" x-mask:dynamic="$money($input, ',')" > <td>  </tr></table>`,
            showCancelButton: false,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar'
        }).then((result) => {
            console.log(result);
            var valor = $("#receb_valor").val();
            var desconto = $("#receb_desc").val();
            var acrescimo = $("#receb_acres").val();
            axios.post(`/rpclinica/json/agendamento/recebimento`, { agendamento: this.modalAgenda.cd_agendamento,valor: valor,  desconto: desconto, acrescimo: acrescimo })
            .then((res) => {

                this.modalAgenda.dt_receb = res.data.query.dt_receb;
                this.modalAgenda.recebido = res.data.query.recebido;
                this.modalAgenda.usuario_receb = res.data.query.usuario_receb;
                this.modalAgenda.vl_acrescimo = res.data.query.vl_acrescimo;
                this.modalAgenda.vl_desconto = res.data.query.vl_desconto;
                this.modalAgenda.valor = res.data.query.valor;

                toastr['success'](res.data.message);
            })
            .catch((err) => toastr['error'](err.response.data.message));

        });
    },

    excluirAgendameto() {
        this.swalWithBootstrapButtons.fire({
            title: 'Confirmação',
            html: "<h4 style='font-weight: 500;font-style: italic;'>Tem certeza que deseja excluir esse agendamento?</h4>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.modalData.errors = [];
                $('#cadastro-consulta .absolute-loading').show();
                axios.delete(`/rpclinica/json/agendamento/${this.modalAgenda.cd_agendamento}`)
                    .then((res) => {
                        this.FullCalendar();
                        $("#calendar").fullCalendar("gotoDate", (this.modalAgenda.dt_agenda) ? this.modalAgenda.dt_agenda : null );
                        toastr['success']('Agendamento excluido!');
                        $('#cadastro-consulta').modal('hide');
                        this.limpaModalAgenda();

                    })
                    .catch((err) => toastr['error'](err.response.data.message))
                    .finally(() => $('#cadastro-consulta .absolute-loading').hide());
            }
        });
    },

    limpaModalAgenda() {

        this.modalAgenda.cd_escala = null;
        this.modalAgenda.cd_escala_manual = null;
        this.modalAgenda.cd_agendamento = null;
        this.modalAgenda.agenda = null;
        this.modalAgenda.nm_agenda = null;
        this.modalAgenda.edita_agenda = null;
        this.modalAgenda.profissional = null;
        this.modalAgenda.nm_profissional = null;
        this.modalAgenda.edita_profissional = null;
        /*this.modalAgenda.dt_agenda = null;
        this.modalAgenda.hr_inicio = null;
        this.modalAgenda.hr_fim = null;*/
        this.modalAgenda.tp_atendimento = null;
        this.modalAgenda.edita_local = null;
        this.modalAgenda.local = null;
        this.modalAgenda.nm_local = null;
        this.modalAgenda.edita_especialidade = null;
        this.modalAgenda.especialidade = null;
        this.modalAgenda.nm_especialidade = null;
        this.modalAgenda.paciente = null;
        this.modalAgenda.dt_nasc = null;
        this.modalAgenda.rg = null;
        this.modalAgenda.cpf = null;
        this.modalAgenda.celular = null;
        this.modalAgenda.email = null;
        this.modalAgenda.convenio = null;
        this.modalAgenda.cartao = null;
        this.modalAgenda.validade = null;
        this.modalAgenda.observacao = null;
        //this.modalAgenda.dadosPaciente = null;
        this.modalAgenda.dadosConta = null;
        this.modalAgenda.procConv = null;
        this.modalAgenda.tp_convenio = null;
        this.modalAgenda.situacao = null;
        this.modalAgenda.dt_agenda= null;
        this.modalAgenda.recebido = null;
        this.modalAgenda.usuario_receb = null;
        this.modalAgenda.vl_acrescimo = null;
        this.modalAgenda.vl_desconto = null;
        this.modalAgenda.valor = null;
        this.modalAgenda.dt_receb = null;
        this.modalAgenda.historico = null;
        /*
        if(this.modalAgenda.dadosPaciente){
            document.getElementById("form-pac").reset();
        }
        */
        document.getElementById("form-Agenda").reset();
        $('#cadastro-consulta select#agendamento-profissional').val(null).trigger('change');
        $('#cadastro-consulta select#cod_agenda').val(null).trigger('change');
        $('#cadastro-consulta select#agendamento-local').val(null).trigger('change');
        $('#cadastro-consulta select#agendamento-tipo').val(null).trigger('change');
        $('#cadastro-consulta select#agendamento-especialidade').val(null).trigger('change');
        $('#cadastro-consulta select#agendamento-paciente').val(null).trigger('change');
        $('#cadastro-consulta select#agendamento-convenio').val(null).trigger('change');

    },

    excluirEscalaManual(){
        console.log(this.modalAgenda);

        this.swalWithBootstrapButtons.fire({
            title: 'Confirmação',
            html: "<h4 style='font-weight: 500;font-style: italic;'>Tem certeza que deseja excluir essa escala? ("+this.modalAgenda.cd_escala+") </h4>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {

                $('#cadastro-consulta .absolute-loading').show();
                axios.delete(`/rpclinica/json/escala_manual/${this.modalAgenda.cd_escala}`)
                .then((res) => {

                    this.FullCalendar();
                    $("#calendar").fullCalendar("gotoDate", (this.modalAgenda.dt_agenda) ? this.modalAgenda.dt_agenda : null );
                    toastr['success'](res.data.message);
                    $('#cadastro-consulta').modal('hide');

                })

                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => $('#cadastro-consulta .absolute-loading').hide());

            }
        });

    },

    buscarCep(){
        axios.get('https://viacep.com.br/ws/'+this.modalAgenda.dadosPaciente.cep.replace(/[^0-9]/g,'')+'/json')

        .then((res) => {
            this.modalAgenda.dadosPaciente.logradouro = res.data.logradouro;
            this.modalAgenda.dadosPaciente.nm_bairro = res.data.bairro;
            this.modalAgenda.dadosPaciente.cidade = res.data.localidade;
            $('#cadastro-consulta select#pac-uf').val(res.data.uf).trigger('change');
        })
        .catch((err) => {
            this.messageDanger = 'Erro@=!';
        })

    },

    limpaModalRapido(){

            this.modalRapido.cd_agenda = null;
            this.modalRapido.cd_escala = null;
            this.modalRapido.tp_escala = null;
            this.modalRapido.cd_profissional = null;
            this.modalRapido.nm_profissional = null;
            this.modalRapido.edita_profissional = null;
            this.modalRapido.dt_agenda = null;
            this.modalRapido.hr_inicio = null;
            this.modalRapido.hr_fim = null;
            this.modalRapido.edita_tipo = null;
            this.modalRapido.tipo = null;
            this.modalRapido.nm_tipo = null;
            this.modalRapido.edita_local = null;
            this.modalRapido.cd_local_atendimento = null;
            this.modalRapido.nm_local = null;
            this.modalRapido.edita_especialidade = null;
            this.modalRapido.cd_especialidade = null;
            this.modalRapido.nm_especialidade = null;
            this.modalRapido.cd_paciente = null;
            this.modalRapido.dt_nasc = null;
            this.modalRapido.rg = null;
            this.modalRapido.cpf = null;
            this.modalRapido.celular = null;
            this.modalRapido.email = null;
            this.modalRapido.cd_convenio = null;
            this.modalRapido.cartao = null;
            this.modalRapido.validade = null;

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
                toastr['success']('Mensagem enviada com sucesso!');
            })
            .catch((err) => this.messageDangerConfirm = err.response.data.message)
            .finally(() => this.loading_confir = false);
    },

}));

$(document).ready(function () {

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

    $('#agendamento-paciente').on('select2:select', (evt) => {
        let cdPaciente = evt.params.data.id;
        console.log(evt.params.data);
        if (typeof cdPaciente == 'number') {
            this.loadingPaciente = true;
            axios.get(`/rpclinica/json/paciente`, { params: { cd_paciente: cdPaciente } })
                .then((response) => {
                    this.dadosPaciente = response.data;
                    console.log(this.dadosPaciente);
                    $('#nome-mae-paciente').val(response.data.nm_mae);
                    $('#nome-pai-paciente').val(response.data.nm_pai);
                    $('#agendamento-convenio').val(response.data.cd_categoria).trigger('change');
                    $('#agendamento-cartao').val(response.data.cartao);
                    $('#cartao-validade').val(response.data.dt_validade);
                    $('#data-de-nasc').val(response.data.dt_nasc);
                    $('#cpf').val(response.data.cpf);
                    $('#rg').val(response.data.rg);
                    $('#email').val(response.data.email);
                    $('#agendamento-celular').val(response.data.celular);
                    //$('#nome-mae-paciente').prop('readonly', true);
                    //$('#nome-pai-paciente').prop('readonly', true);
                    //$('#data-de-nasc').prop('readonly', true);
                })
                .catch((error) => toastr['error'](error.response.data.message))
                .finally(() => this.loadingPaciente = false);
        } else {
            // $('#nome-mae-paciente').prop('readonly', false);
            $('#nome-mae-paciente').val('');
            //$('#nome-pai-paciente').prop('readonly', false);
            $('#nome-pai-paciente').val('');
            //$('#data-de-nasc').prop('readonly', false);
            $('#data-de-nasc').val('');
            $('#cpf').val('');
            $('#rg').val('');
            $('#agendamento-celular').val('');
            $('#agendamento-email').val('');
            $('#agendamento-cartao').val('');
            $('#cartao-validade').val('');

        }

    });


    $('#pesqPaciente').select2({
        ajax: {
            url: '/rpclinica/json/pacientes',
            dataType: 'json',
            processResults: (data) => {

                let search = $('#pesqPaciente').data('select2').results.lastParams?.term;

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

    $('#rapido-pac').select2({
        ajax: {
            url: '/rpclinica/json/pacientes',
            dataType: 'json',
            processResults: (data) => {

                let search = $('#rapido-pac').data('select2').results.lastParams?.term;

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

    /*
    $('#rapido-pac').on('select2:select', (evt) => {
        let cdPaciente = evt.params.data.id;
        console.log(evt.params.data);
        if (typeof cdPaciente == 'number') {
            axios.get(`/rpclinica/json/paciente`, { params: { cd_paciente: cdPaciente } })
                .then((response) => {
                    this.dadosPaciente = response.data;
                    console.log(response.data);
                    $('#rapido_nasc').val(response.data.dt_nasc);
                    $('#rapido-cpf').val(response.data.cpf);
                    $('#rapido-rg').val(response.data.rg);
                    $('#email').val(response.data.email);
                    $('#rapido-celular').val(response.data.celular);
                    $('#rapido-conv').val(response.data.cd_categoria).trigger('change');
                    $('#rapido-cartao').val(response.data.cartao);
                    $('#rapido-validade').val(response.data.dt_validade);
                })
                .catch((error) => toastr['error'](error.response.data.message))
        } else {
            $('#rapido_nasc').val(null);
            $('#rapido-cpf').val(null);
            $('#rapido-rg').val(null);
            $('#email').val(null);
            $('#rapido-celular').val(null);
            $('#rapido-conv').val(null).trigger('change');
            $('#rapido-cartao').val(null);
            $('#rapido-validade').val(null);
        }

    });
    */

});






