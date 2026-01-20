import axios from 'axios';
import moment from 'moment';
var agendamentoDadosMes = 'POPO';

Alpine.data('app', () => ({
    dadosAgenda: agendas,
    tablePesqConfir: null,
    tablePesqBloq: null,
    dadosConvenios: convenios,
    buttonPesqAvanc: false, 
    dadosSituacao:[],
    loading: false,
    inicioIcone: true,
    loadingModal: false,
    messageDanger: null,
    loadingAcao: null,
    bloqueado: false,
    ds_bloqueado: '',
    lista: [],
    modalData: { errors: [] },
    carregando: '<i class="fa fa-spinner fa-spin" style="color:#B0B0B0 "></i>',
    loadCarregandoAgenda: '',
    headerLivres: ' -- ',
    headerAgendados: ' -- ',
    headerConfirmados: ' -- ',
    headerCancelados: ' -- ',
    headerAtendidos: ' -- ',
    headerAguardando: ' -- ',
    iconeLivre: '<span class="glyphicon glyphicon-list-alt" aria-hidden="true"  style="padding-left:2px; padding-right: 10px;  "></span> ',
    modalAgenda: null,
    modalKey: null,
    horaFinal: null,
    modalEnvios:{
        lista: [],
        titulo: null,
    },
    camposModal: {
        profissional: null,
        especialidade: null,
        convenio: null,
        tipo_atendimento: null,
        local: null,
        itens: null,
        itens_agendamento: null
    },
    data_agenda: null,
    snBloquea: false,
    snExcluir: false,
    snDesbloquea: false,

    dataBloqueio: {
        cd_agenda: null,
        cd_escala: null,
        cd_horario: null,
        dt_agenda: null,
        hr_inicio: null,
        hr_fim: null,
        nm_intervalo: null,
        cd_dia: null,
    },

    modalPaciente: {
        cd_paciente: null, cd_agendamento: null, nm_paciente: null, dt_nasc: null, rg: null, cpf: null, cartao_sus: null, nome_social: null,
        nm_responsavel: null, cpf_responsavel: null, nm_mae: null, nm_pai: null, cartao: null, dt_validade: null, fone: null, celular: null,
        email: null, cep: null, logradouro: null, numero: null, complemento: null, nm_bairro: null, cidade: null, profissao: null,
        dt_nasc_mae: null, celular_mae: null,dt_nasc_pai: null,celular_pai: null 
    },
    dataSituacao: { status: null, cd_agendamento: null},

    buttonSalvarAgendamneto: ' <i  class="fa fa-check"></i> Salvar ',
    tempSalvando: " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ",
    tempSalvar: ' <i  class="fa fa-check"></i> Salvar ',

    init() {

        $('#data-input').val(moment().format('YYYY-MM-DD'));
        $('#calendar').datepicker('setDate', 'YYYY-MM-DD');

        $('#calendar').on('changeDate', () => {

            $('#data-input').val(
                $('#calendar').datepicker('getFormattedDate')
                
            );
            this.getAgendamentos()
        });

        $('#cod_agenda').on('select2:select', (evt) => { 

            $('#cadastro-agendamento select#agendamento-profissional').val(null).trigger('change'); 
            $('#cadastro-agendamento select#agendamento-local').val(null).trigger('change');
            $('#cadastro-agendamento select#agendamento-tipo').val(null).trigger('change');
            $('#cadastro-agendamento select#agendamento-especialidade').val(null).trigger('change');
            this.camposModal.profissional = []
            this.camposModal.especialidade = [] 
            this.camposModal.tipo_atendimento = []
            this.camposModal.local = []

            this.loadCarregandoAgenda = '<i class="fa fa-spinner fa-spin" style="color:#22BAA0; margin-left: 10px; "></i>';
            axios.get('/rpclinica/json/agendamento/add-modal?agenda='+evt.params.data.id)
            .then((res) => {
                console.log(res.data)
                this.camposModal.profissional = res.data.retorno.profissional
                this.camposModal.especialidade = res.data.retorno.especialidade
                this.camposModal.convenio = res.data.retorno.convenio
                this.camposModal.tipo_atendimento = res.data.retorno.tipo_atendimento
                this.camposModal.local = res.data.retorno.local
            })
            .catch((err) => { 
                console.log('Erro')
            })
            .finally(() => { 
                this.loadCarregandoAgenda = '';
            });

        });
 
        $('#bloqueio-profissional').on('select2:select', (evt) => { 
            console.log(evt.params.data.id);      
            $('#cadastro-bloqueio .absolute-loading').show(); 
            this.buttonPesqAvanc = true;
            this.loadingAcao = "Pesquisando...";       
            
            axios.get('/rpclinica/json/agendamento/list-bloqueio?profissional='+evt.params.data.id)
            .then((res) => {
                console.log(res.data) 
                this.tablePesqBloq = res.data.retorno;
            })
            .catch((err) => { 
                console.log('Erro');
                this.tablePesqBloq = null;
            })
            .finally(() => { 
                $('#cadastro-bloqueio .absolute-loading').hide();
                this.buttonPesqAvanc = false; 
            });
        });
        
        /*
        $('#form-horario select').on('select2:change', () => {
            this.getAgendamentos()
        });
        */
        $('#form-horario select').change(() => {
            this.getAgendamentos()
        });
        this.getAgendamentos()
    },

    getAtendimentos() {
        this.getAgendamentos();
        $("#calendar").datepicker("refresh");
    },

    clearPaciente(){
        this.modalPaciente.cd_paciente= null; this.modalPaciente.cd_agendamento= null; this.modalPaciente.nm_paciente= null; this.modalPaciente.dt_nasc= null; 
        this.modalPaciente.rg= null;this.modalPaciente.cpf= null; this.modalPaciente.cartao_sus= null; this.modalPaciente.nome_social= null; 
        this.modalPaciente.nm_responsavel= null; this.modalPaciente.cpf_responsavel= null; this.modalPaciente.nm_mae= null; this.modalPaciente.nm_pai= null; 
        this.modalPaciente.cartao= null; this.modalPaciente.dt_validade= null; this.modalPaciente.fone= null; this.modalPaciente.celular= null; 
        this.modalPaciente.email= null; this.modalPaciente.cep= null; this.modalPaciente.logradouro= null; this.modalPaciente.numero= null;
        this.modalPaciente.complemento= null; this.modalPaciente.nm_bairro= null; this.modalPaciente.cidade= null; this.profissao= null;
        this.modalPaciente.dt_nasc_mae= null;this.modalPaciente.celular_mae= null; this.modalPaciente.dt_nasc_pai= null; this.modalPaciente.celular_pai= null;
    },

    clearModal() {

        this.camposModal.profissional = null
        this.camposModal.especialidade = null
        this.camposModal.convenio = null
        this.camposModal.tipo_atendimento = null
        this.camposModal.local = null
        this.camposModal.itens = null
        this.camposModal.itens_agendamento = null
        $('#cadastro-agendamento select#item_agendamento').val([]).trigger('change');
        $('#cadastro-agendamento select#agendamento-paciente').val(null).trigger('change');
        
        $('#cadastro-agendamento select#agendamento-profissional').val(null).trigger('change'); 
        $('#cadastro-agendamento select#agendamento-local').val(null).trigger('change');
        $('#cadastro-agendamento select#agendamento-tipo').val(null).trigger('change');
        $('#cadastro-agendamento select#agendamento-especialidade').val(null).trigger('change');
        $('#cadastro-agendamento select#cod_agenda').val(null).trigger('change');
        $('#cadastro-agendamento select#agendamento-paciente').val(null).trigger('change'); 
        $('#cadastro-agendamento select#agendamento-convenio').val(null).trigger('change'); 

        $('#check-agend-receb span').removeClass('checked');
        $('#check-agend-receb input').prop('checked', false);
 
        $('#dadosIdade').html('');
        this.snBloquea = false;
        this.snExcluir = false;
        this.modalData.errors = [] 
        this.dataSituacao.status= null;
        this.dataSituacao.cd_agendamento= null;
        this.modalAgenda = null;
        
        //Paciente
        this.modalPaciente.cd_paciente = null;
        this.modalPaciente.cd_agendamento = null;
        this.modalPaciente.nm_paciente = null;
        this.modalPaciente.dt_nasc = null;
        this.modalPaciente.rg = null;
        this.modalPaciente.cpf = null;
        this.modalPaciente.cartao_sus = null;
        this.modalPaciente.nome_social = null;
        this.modalPaciente.nm_responsavel = null;
        this.modalPaciente.cpf_responsavel = null;
        this.modalPaciente.nm_mae = null;
        this.modalPaciente.nm_pai = null;
        this.modalPaciente.cartao = null;
        this.modalPaciente.dt_validade = null;
        this.modalPaciente.fone = null;
        this.modalPaciente.celular = null;
        this.modalPaciente.email = null;
        this.modalPaciente.cep = null;
        this.modalPaciente.logradouro = null;
        this.modalPaciente.numero = null;
        this.modalPaciente.complemento = null;
        this.modalPaciente.nm_bairro = null;
        this.modalPaciente.cidade = null;
        this.modalPaciente.profissao = null;
        this.modalPaciente.dt_nasc_mae = null;
        this.modalPaciente.celular_mae = null;
        this.modalPaciente.dt_nasc_pai = null;
        this.modalPaciente.celular_pai = null;
        this.dadosSituacao=[];

    },

    getAgendamentos() {
        this.headerLivres = this.carregando;
        this.headerAgendados = this.carregando;
        this.headerConfirmados = this.carregando;
        this.headerCancelados = this.carregando;
        this.headerAguardando = this.carregando;
        this.headerAtendidos = this.carregando;
  
        this.data_agenda = null;
        this.inicioIcone = false;
        this.loading = true;
        this.messageDanger = null;
        this.lista = [];
        let form = new FormData(document.querySelector('#form-horario')); 
        axios.post('/rpclinica/agendamentos-lista-show', form) 
            .then((res) => {

                console.log(res.data);
                if(res.data.feriado){
                    this.bloqueado=true;
                    this.lista = [];
                    this.headerLivres = [];
                    this.headerAgendados = [];
                    this.headerConfirmados = [];
                    this.headerCancelados = [];
                    this.ds_bloqueado = res.data.feriado.nm_feriado;
                }else{
                    this.bloqueado=false;
                    this.lista = res.data.query;
                    this.headerLivres = res.data.header.total;
                    this.headerAgendados = res.data.header.agendado;
                    this.headerConfirmados = res.data.header.confirmado;
                    this.headerCancelados = res.data.header.cancelado;
                }
                this.lista = res.data.query;
                agendamentoDadosMes = res.data.calendario;
                this.headerLivres = res.data.header.total;
                this.headerAgendados = res.data.header.agendado;
                this.headerConfirmados = res.data.header.confirmado;
                this.headerCancelados = res.data.header.cancelado;
                this.headerAguardando = res.data.header.aguardando;
                this.headerAtendidos = res.data.header.atendido;
                this.data_agenda = res.data.data; 

 
            })
            .catch((err) => {
                this.messageDanger = err.response.data.message;
            })
            .finally(() => {
                this.loading = false;
 

            });
    },
    
    getModalConfirmacao() {  
        $('#cadastro-confirmacao').modal('toggle'); 
    },

    getModalBloqueio() {  
        $('#cadastro-bloqueio').modal('toggle'); 
    },
  
    getModalManual() {
        this.clearModal();
        this.snExcluir = false;
        this.modalAgenda = null;
  
        $('#cadastro-agendamento').modal('toggle'); 
 
    },

    getModal(dados, idx) {
        this.clearModal();
        $('#cadastro-agendamento').modal('toggle');
        this.loadingModal = true;
        this.modalAgenda = dados;
        this.modalKey = idx;
        this.horaFinal = moment(dados.cd_horario, 'HH:mm').add(dados.intervalo, 'minutes').format('HH:mm');
        console.log(this.modalAgenda);

        if(this.modalAgenda.recebido=='1'){
            $('#check-agend-receb span').addClass('checked');
            $('#check-agend-receb input').prop('checked', true);
        }
 
        $('#cadastro-agendamento select#agendamento-profissional').val(null).trigger('change'); 
        $('#cadastro-agendamento select#agendamento-local').val(null).trigger('change');
        $('#cadastro-agendamento select#agendamento-tipo').val(null).trigger('change');
        $('#cadastro-agendamento select#agendamento-especialidade').val(null).trigger('change');
        $('#cadastro-agendamento select#cod_agenda').val(null).trigger('change');
        $('#cadastro-agendamento select#agendamento-paciente').val(null).trigger('change'); 
        $('#cadastro-agendamento select#agendamento-convenio').val(null).trigger('change'); 
  
        //Paciente
        this.modalPaciente.cd_paciente = dados.cd_paciente;
        this.modalPaciente.cd_agendamento = dados.paciente?.cd_agendamento;
        this.modalPaciente.nm_paciente = dados.paciente?.nm_paciente;
        this.modalPaciente.dt_nasc = dados.paciente?.dt_nasc;
        this.modalPaciente.rg = dados.paciente?.rg;
        this.modalPaciente.cpf = dados.paciente?.cpf;
        this.modalPaciente.cartao_sus = dados.paciente?.cartao_sus;
        this.modalPaciente.nome_social = dados.paciente?.nome_social;
        this.modalPaciente.nm_responsavel = dados.paciente?.nm_responsavel;
        this.modalPaciente.cpf_responsavel = dados.paciente?.cpf_responsavel;
        this.modalPaciente.nm_mae = dados.paciente?.nm_mae;
        this.modalPaciente.nm_pai = dados.paciente?.nm_pai;
        this.modalPaciente.cartao = dados.paciente?.cartao;
        this.modalPaciente.dt_validade = dados.paciente?.dt_validade;
        this.modalPaciente.fone = dados.paciente?.fone;
        this.modalPaciente.celular = dados.paciente?.celular;
        this.modalPaciente.email = dados.paciente?.email;
        this.modalPaciente.cep = dados.paciente?.cep;
        this.modalPaciente.logradouro = dados.paciente?.logradouro;
        this.modalPaciente.numero = dados.paciente?.numero;
        this.modalPaciente.complemento = dados.paciente?.complemento;
        this.modalPaciente.nm_bairro = dados.paciente?.nm_bairro;
        this.modalPaciente.cidade = dados.paciente?.cidade;
        this.modalPaciente.profissao = dados.paciente?.profissao;
        this.modalPaciente.dt_nasc_mae= dados.paciente?.dt_nasc_mae;
        this.modalPaciente.celular_mae= dados.paciente?.celular_mae;
        this.modalPaciente.dt_nasc_pai= dados.paciente?.dt_nasc_pai;
        this.modalPaciente.celular_pai= dados.paciente?.celular_pai;
 
        axios.post('/rpclinica/json/agendamento/dados-modal', dados)
            .then((res) => {
                
                console.log(res.data)
                this.camposModal.profissional = res.data.profissional
                this.camposModal.especialidade = res.data.especialidade
                this.camposModal.convenio = res.data.convenio
                this.camposModal.tipo_atendimento = res.data.tipo_atendimento
                this.camposModal.local = res.data.local
                this.camposModal.itens = res.data.itens;
                this.camposModal.itens_agendamento = res.data.itens_agendamento;
                this.dadosSituacao=res.data.situacao_recep;
 
            })

            .catch((err) => {
                toastr['error'](err.response.data.message);
                $('#cadastro-agendamento').modal('hide');
            })

            .finally(() => { 
                
                this.snExcluir = true;
                $('#cadastro-agendamento select#agendamento-profissional').val(this.modalAgenda.cd_profissional).trigger('change');
                $('#cadastro-agendamento select#cod_agenda').val(this.modalAgenda.cd_agenda).trigger('change');
                $('#cadastro-agendamento select#agendamento-local').val(this.modalAgenda.cd_local_atendimento).trigger('change');
                $('#cadastro-agendamento select#agendamento-tipo').val(this.modalAgenda.tipo).trigger('change');
                $('#cadastro-agendamento select#agendamento-especialidade').val(this.modalAgenda.cd_especialidade).trigger('change');

                if (this.modalAgenda.cd_paciente) {
                    let newOption = new Option(this.modalAgenda.paciente?.nm_paciente, this.modalAgenda.cd_paciente, false, false);
                    $('#cadastro-agendamento select#agendamento-paciente').append(newOption).trigger('change');
                    $('#cadastro-agendamento select#agendamento-paciente').val(this.modalAgenda.cd_paciente).trigger('change');
                }
                
                if (this.camposModal.itens_agendamento) {
                    $('#cadastro-agendamento select#item_agendamento').val(this.camposModal.itens_agendamento).trigger('change');
                } else {
                    $('#cadastro-agendamento select#item_agendamento').val([]).trigger('change');
                }
                
                $('#cadastro-agendamento select#agendamento-profissional').val(this.modalAgenda.cd_profissional).trigger('change');
                $('#cadastro-agendamento select#cod_agenda').val(this.modalAgenda.cd_agenda).trigger('change');
                $('#cadastro-agendamento select#agendamento-local').val(this.modalAgenda.cd_local_atendimento).trigger('change');
                $('#cadastro-agendamento select#agendamento-tipo').val(this.modalAgenda.tipo).trigger('change');
                $('#cadastro-agendamento select#agendamento-especialidade').val(this.modalAgenda.cd_especialidade).trigger('change');
                $('#cadastro-agendamento select#agendamento-convenio').val(this.modalAgenda.cd_convenio).trigger('change');
                this.camposModal.itens_agendamento = null; 

                this.loadingModal = false;
  
            });

    },

    storeAgendamento() {

        this.modalData.errors = [];
        this.loadingAcao = "Agendando...";
        $('#cadastro-agendamento .absolute-loading').show(); 
        this.buttonSalvarAgendamneto = this.tempSalvando; 
          
        let form = new FormData(document.querySelector('#form-Agenda'));
        //form.append('cd_agendamento', this.modalAgenda.cd_agendamento);
        axios.post('/rpclinica/json/agendamento-lista/store', form)

            .then((res) => {

                $('#cadastro-consulta select#agendamento-profissional').val(null).trigger('change');
                $('#cadastro-consulta select#cod_agenda').val(null).trigger('change');
                $('#cadastro-consulta select#agendamento-local').val(null).trigger('change');
                $('#cadastro-consulta select#agendamento-tipo').val(null).trigger('change');
                $('#cadastro-consulta select#agendamento-especialidade').val(null).trigger('change');
                $('#cadastro-consulta select#agendamento-paciente').val(null).trigger('change');
                $('#cadastro-consulta select#agendamento-convenio').val(null).trigger('change');
                $('#cadastro-consulta select#item_agendamento').val(null).trigger('change');

                if (res.data.retorno == true) {
                    this.getAgendamentos();
                    toastr['success'](res.data.ds_retorno);
                } else {
                    toastr['error']('Erro no cadastro do agendamento!');
                }

                $('#cadastro-agendamento').modal('hide');

            })
            .catch((err) => {
                Object.values(err.response.data.errors).forEach((errors) => {
                    this.modalData.errors = this.modalData.errors.concat(errors);
                })
            })
            .finally(() => {

                this.buttonSalvarAgendamneto = this.tempSalvar;
                $('#cadastro-agendamento .absolute-loading').hide();
                

            });

    },

    ExcluirAgenda() {

        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse Agendamento?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {

                if (result.isConfirmed) {
                    this.loadingAcao = "Excluindo...";
                    $('#cadastro-agendamento .absolute-loading').show();
                    axios.delete(`/rpclinica/json/agendamento/${this.modalAgenda.cd_agendamento}`)
                        .then((res) => {
                            this.getAgendamentos();
                            toastr['success'](res.data.message);
                            $('#cadastro-agendamento').modal('hide');
                        })
                        .catch((err) => toastr['error'](err.response.data.message))
                        .finally(() => {
                            $('#cadastro-agendamento .absolute-loading').hide();
                        });
                }

            });
    },

    bloquearHorario() {
        this.loadingAcao = "Bloqueando...";
        $('#cadastro-agendamento .absolute-loading').show();
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja bloquear esse hoarario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {

                    this.dataBloqueio.cd_agenda = this.modalAgenda.cd_agenda;
                    this.dataBloqueio.cd_escala = this.modalAgenda.cd_escala_agenda;
                    this.dataBloqueio.cd_horario = this.modalAgenda.cd_agenda_escala_horario;
                    this.dataBloqueio.dt_agenda = this.data_agenda;
                    this.dataBloqueio.hr_inicio = this.modalAgenda.cd_horario;
                    this.dataBloqueio.hr_fim = this.horaFinal;
                    this.dataBloqueio.nm_intervalo = this.modalAgenda.nm_intervalo;
                    this.dataBloqueio.cd_dia = this.modalAgenda.nr_dia;

                    axios.post('/rpclinica/json/agendamento/bloquear-horario', this.dataBloqueio)
                        .then((res) => {
                            toastr['success'](res.data.message);
                            this.getAgendamentos(); 
                        })
                        .catch((err) => {
                            Object.values(err.response.data.errors).forEach((errors) => {
                                this.modalData.errors = this.modalData.errors.concat(errors);
                            })
                        })
                        .finally(() => {
                            $('#cadastro-agendamento .absolute-loading').hide();
                        });
                }
            });
    },

    atualizarPaciente() {
        this.loadingAcao = "Atualizando Paciente..."; 
        $('#cadastro-agendamento .absolute-loading').show();
        let form = new FormData(document.querySelector('#form-pac'));

        axios.post('/rpclinica/json/pacientes-update-join', form)
            .then((res) => {
                this.clearPaciente();
                toastr['success']('Paciente atualizado com sucesso!');   
                //Paciente
                this.modalPaciente.cd_paciente= res.data.cd_paciente; 
                this.modalPaciente.nm_paciente= res.data.nm_paciente; 
                this.modalPaciente.dt_nasc= res.data.dt_nasc; 
                this.modalPaciente.rg= res.data.rg; 
                this.modalPaciente.cpf= res.data.cpf; 
                this.modalPaciente.cartao_sus= res.data.cartao_sus; 
                this.modalPaciente.nome_social= res.data.nome_social; 
                this.modalPaciente.nm_responsavel= res.data.nm_responsavel;
                this.modalPaciente.cpf_responsavel= res.data.cpf_responsavel;
                this.modalPaciente.nm_mae= res.data.nm_mae; 
                this.modalPaciente.nm_pai= res.data.nm_pai; 
                this.modalPaciente.cartao= res.data.cartao; 
                this.modalPaciente.dt_validade= res.data.dt_validade;
                this.modalPaciente.fone= res.data.fone; 
                this.modalPaciente.celular= res.data.celular; 
                this.modalPaciente.email= res.data.email; 
                this.modalPaciente.cep= res.data.cep; 
                this.modalPaciente.logradouro= res.data.logradouro; 
                this.modalPaciente.numero= res.data.numero; 
                this.modalPaciente.complemento= res.data.complemento;  
                this.modalPaciente.nm_bairro= res.data.nm_bairro; 
                this.modalPaciente.cidade= res.data.cidade;  
                this.modalPaciente.profissao= res.data.profissao;
                this.modalPaciente.celular_mae= res.data.celular_mae;
                this.modalPaciente.dt_nasc_mae= res.data.dt_nasc_mae;
                this.modalPaciente.dt_nasc_pai= res.data.dt_nasc_pai;
                this.modalPaciente.celular_pai= res.data.celular_pai;

            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => $('#cadastro-agendamento .absolute-loading').hide());
    },

    situacaoAgendamento(status,agendamento) {
        
        if(status=='CO'){ var texto ='confirmar' }
        if(status=='CA'){ var texto ='cancelar' }
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja " + texto + " esse hoarario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {
 
                    this.dataSituacao.status= status;
                    this.dataSituacao.cd_agendamento= agendamento;
                    axios.post('/rpclinica/json/agendamento-lista/situacao', this.dataSituacao)
                        .then((res) => {
                            toastr['success'](res.data.message);
                            this.getAgendamentos(); 
                        })
                        .catch((err) => {
                       
                            toastr['error'](err.response.data.message)
                        })
                        .finally(() => {
                        
                        });
                }
            });
    },
 
    atualizaStatus(situacao) {
        $('#cadastro-agendamento .absolute-loading').show();
        this.loadingAcao = "Atualizando...";
        axios.put('/rpclinica/json/atualiza-status-agendamento',{ cd_agendamento: this.modalAgenda.cd_agendamento, situacao: situacao  })
        .then((res) => {
 
            toastr['success'](res.data.message);
            this.getAgendamentos();

        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => {
            $('#cadastro-agendamento .absolute-loading').hide();   
            $('#cadastro-agendamento').modal('hide');
        });

    },

    pesquisaConfirmacao() {
 
        $('#cadastro-confirmacao .absolute-loading').show(); 
        this.buttonPesqAvanc = true;
        this.loadingAcao = "Pesquisando..."; 
        this.formConfirmacao = new FormData(document.querySelector('#form-pesquisa-confirmacao')); 
        axios.post('/rpclinica/json/agendamento-confirmacao', this.formConfirmacao)
        .then((res) => {    
            console.log(res.data);
            this.tablePesqConfir = res.data;
        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => {
            $('#cadastro-confirmacao .absolute-loading').hide();
            this.buttonPesqAvanc = false; 
        }); 
    },

    EnvioMensagem() {
        Swal.fire({
            title: 'Envio de Mensagem',
            text: "Tem certeza que deseja enviar os agendamentos selecionados?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
        .then((result) => {
            if (result.isConfirmed) { 
                this.loadingAcao = "Enviando...";
                $('#loadingPesqAvanc .absolute-loading').show();
                let form = new FormData(document.querySelector('#form-envio-confirmacao')); 
                axios.post(`/rpclinica/json/agendamento-envio-confirmacao`, form )
                .then((res) => {  
                    toastr['success'](res.data.message); 
                    this.pesquisaConfirmacao();
                    document.getElementById("form-envio-confirmacao").reset();
                })
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => {
                    $('#loadingPesqAvanc .absolute-loading').hide(); 
                }); 
            }
        });
    },

    storeConfirmacao(dados,situacao) {
      
        Swal.fire({
            title: dados.paciente.nm_paciente+' [ '+dados.cd_agendamento+' ]',
            text: "Tem certeza que deseja alterar o status do Agendamento?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
        .then((result) => {
 
            if (result.isConfirmed) { 
                this.loadingAcao = "Atualizando...";
                $('#loadingPesqAvanc .absolute-loading').show();
                axios.post(`/rpclinica/json/agendamento-store-confirmacao/${dados.cd_agendamento}/${situacao}`,this.formConfirmacao)
                .then((res) => {  
                    toastr['success'](res.data.message);  
                    this.tablePesqConfir =res.data.retorno;
                })
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => {  
                    $('#loadingPesqAvanc .absolute-loading').hide(); 
                });
            }

        }); 
 
    },

    AtualizaRetorno() {

        Swal.fire({
            title: 'Envio de Mensagem',
            text: "Tem certeza que deseja atualizar os agendamentos selecionados?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
        .then((result) => {
            if (result.isConfirmed) { 
                this.loadingAcao = "Atualizando os Agendamentos...";
                $('#loadingPesqAvanc .absolute-loading').show();
                let form = new FormData(document.querySelector('#form-envio-confirmacao')); 
                axios.post(`/rpclinica/json/agendamento-atualizar-retorno`, form )
                .then((res) => {  
                    toastr['success'](res.data.message); 
                    this.pesquisaConfirmacao();
                    document.getElementById("form-envio-confirmacao").reset();
                })
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => {
                    $('#loadingPesqAvanc .absolute-loading').hide(); 
                }); 
            }
        });

    },

    getEnvios(dados){
        console.log(dados);
        this.modalEnvios.lista=dados.tab_whast_send;
        this.modalEnvios.titulo=  dados.paciente.nm_paciente + ' <b>{ ' + dados.cd_agendamento + ' }</b>';
    },

    validarZap(){

        const element = document.querySelector('#Zap');
        element.classList.remove('whastValido');
        element.classList.remove('whastInvalido');
        element.classList.remove('whastNeutro');
        this.dataCelular= $('#agendamento-celular').val();
        this.classWhast= 'fa fa-whatsapp  ';
        this.SituacaoWhast = null;
        this.foneWhast = null;
        if(!this.dataCelular) {
            return toastr['error']("Informação Incompleta, Numero não Informado!");
        } 
        axios.get(`/rpclinica/comunicacao-check/${this.dataCelular}`)
        .then((res) => {
            console.log(res.data);
            if(res.data.retorno==false) {
                
                toastr['error'](res.data.msg); 
                if(res.data.dados==false) {
                     
                    this.classWhast = "fa fa-whatsapp whastInvalido";
                    this.SituacaoWhast=false;
                    this.foneWhast = this.dataCelular;
                }
            }
            if(res.data.retorno==true) {
                toastr['success'](res.data.msg); 
                this.classWhast = "fa fa-whatsapp whastValido";
                this.SituacaoWhast=true;
                this.foneWhast = this.dataCelular;
            } 
          
     
        })
        .catch((err) => {
            toastr['error'](err.response.data.message);
        });
        

    },
 
    storeBloqueio() {
 
        $('#cadastro-bloqueio .absolute-loading').show(); 
        this.buttonPesqAvanc = true;
        this.loadingAcao = "Cadastrando..."; 
        this.formConfirmacao = new FormData(document.querySelector('#form-store-bloqueio')); 
        axios.post('/rpclinica/agendamentos-lista-bloqueio-store', this.formConfirmacao)
        .then((res) => {    
            console.log(res.data);
            document.getElementById("form-store-bloqueio").reset();
            $('#cadastro-bloqueio select#bloqueio-profissional').val(null).trigger('change');
            toastr['success']("Cadastro com Sucesso!"); 
            this.tablePesqBloq = res.data.retorno;
        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => {
            $('#cadastro-bloqueio .absolute-loading').hide();
            this.buttonPesqAvanc = false; 
        }); 
    },

    ExcluirBloqueio(codigo) {

        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse Bloqueio?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {

                if (result.isConfirmed) {
                    this.loadingAcao = "Excluindo...";
                    $('#cadastro-bloqueio .absolute-loading').show();
                    axios.delete(`/rpclinica/json/agendamento/delete-bloqueio/${codigo}`)
                        .then((res) => {
                            toastr['success']("Cadastro com Sucesso!"); 
                            this.tablePesqBloq = res.data.retorno; 
                        })
                        .catch((err) => toastr['error'](err.response.data.message))
                        .finally(() => {
                            $('#cadastro-bloqueio .absolute-loading').hide();
                        });
                }

            });
    },
     
}));

$(document).ready(function () {
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

            let day = e.getDate().toString().padStart(2, 0);
            if (agendamentoDadosMes?.[day]) {
                return { enabled: true, classes: agendamentoDadosMes?.[day] };
            }

        }


    });


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
                    //$('#dadosIdade').val(response.data.idade);
                    $('#dadosIdade').html('&nbsp;&nbsp;((&nbsp;&nbsp;' + response.data.idade + '&nbsp;&nbsp;))')
                    $('#ds_profissao').val(response.data.profissao);

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
            $('#dadosIdade').html('')
            $('#ds_profissao').val("");

        }

    });


});

