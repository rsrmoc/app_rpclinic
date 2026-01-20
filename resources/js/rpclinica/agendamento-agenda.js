import axios from 'axios';
import moment from 'moment';

Alpine.data('app', () => ({
    visaoIDADE: false,
    IDADE: null,
    horarioPadrao: horarioPadrao,
    tp_agendamento: null,
    loadingAcao: null, 
    buttonPesqAvanc: false, 
    conteudoModal:'',
    botaoEscala: false,
    loadingPaciente: false,
    loadingAgendamentoSessao: false,
    tablePesqAvanc: null,
    tablePesqConfir: null,
    modalData: {errors: [],agenda: null,prof: null,local: null, tipo:null, espec: null, conv: null, situacoes: null},

    modalAgenda: {cd_agendamento: null,cd_agenda: null,nm_agenda: null,cd_escala: null,dt_agenda:null,hr_inicio: null,
        hr_fim: null,dt_nasc : null,rg: null,cpf: null,celular: null,email: null,cartao: null,
        validade: null,observacao: null,dt_resp_whast: null,retorno_whast: null,dt_presenca: null,
        cd_dia: null, intervalo: null,nm_intervalo: null,data_horario: null,situacao : null,nm_paciente: null,
        cd_profissional: null, cd_local:null, cd_tipo:null, cd_especialidade:null, cd_paciente:null, cd_convenio:null, 
        cd_horario: null, itensAgendamento: null,itemNoAgendamento: null, profissao: null,sn_finalizado: null,historico: [],
        nm_mae: null, dt_nasc_mae: null, celular_mae: null, nm_pai: null, dt_nasc_pai: null, celular_pai: null
    },
    modalPaciente:{ 
        cd_paciente: null, cd_agendamento: null, nm_paciente: null, dt_nasc: null, rg: null, cpf: null, cartao_sus: null, nome_social: null, 
        nm_responsavel: null, cpf_responsavel: null, nm_mae: null, nm_pai: null, cartao: null, dt_validade: null, fone: null, celular: null, 
        email: null, cep: null, logradouro: null, numero: null, complemento: null, nm_bairro: null, cidade: null,profissao: null,
        dt_nasc_mae: null, celular_mae: null,dt_nasc_pai: null,celular_pai: null 
        
    },
    modalEnvios:{
        lista: [],
        titulo: null,
    },
    formConfirmacao: null,
    iconCalendar: '<i class="fa fa-calendar"></i> ',
    iconUser: '<i class="fa fa-user"></i> ',
    buttonSalvarAgendamneto: ' <i  class="fa fa-check"></i> Salvar ', 
    tempSalvando: " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ",
    tempSalvar: ' <i  class="fa fa-check"></i> Salvar ',
    dadosOpcoes: [],
    opcoesEscalaManual: null,
    SituacaoWhast: null,
    foneWhast: null,
    dataCelular: null,
    classWhast: 'fa fa-whatsapp  ',
    bloqueio:{
        load: false,
        lista: [], 
        agendas: null,
        data: null
    },
    dataAtendimento: null,
    userAtendimento: null,
    
    init() { 
        this.FullCalendar();  
        $('#relacaoAgendaBloqueio').on('select2:select', (evt) => this.bloqueio.agendas= evt.params.data.id);
    },
      
    clear(){
        this.modalData.agenda = null; 
        this.modalData.conv = null; 
        this.modalData.prof = null; 
        this.modalData.local = null; 
        this.modalData.tipo = null; 
        this.modalData.espec = null; 
        this.modalData.agenda = null; 
        this.modalAgenda.cd_agendamento = null; 
        this.modalAgenda.cd_agenda = null; 
        this.modalAgenda.nm_agenda = null; 
        this.modalAgenda.cd_escala = null; 
        this.modalAgenda.dt_agenda = null; 
        this.modalAgenda.hr_inicio = null; 
        this.modalAgenda.hr_fim = null; 
        this.modalAgenda.situacao = null;  
        this.modalAgenda.nm_paciente = null;  
        this.modalAgenda.dt_nasc = null; 
        this.modalAgenda.cd_horario = null; 
        this.modalAgenda.rg = null; 
        this.modalAgenda.cpf = null; 
        this.modalAgenda.celular = null; 
        this.modalAgenda.email = null; 
        this.modalAgenda.cartao = null; 
        this.modalAgenda.validade = null; 
        this.modalAgenda.observacao = null;  
        this.modalAgenda.dt_resp_whast =null; 
        this.modalAgenda.retorno_whast =null; 
        this.modalAgenda.dt_presenca =null; 
        this.modalAgenda.cd_dia = null; 
        this.modalAgenda.data_horario = null; 
        this.modalAgenda.intervalo = null; 
        this.modalAgenda.nm_intervalo = null; 
        this.modalAgenda.cd_profissional = null; 
        this.modalAgenda.cd_local = null; 
        this.modalAgenda.cd_tipo = null; 
        this.modalAgenda.cd_especialidade = null; 
        this.modalAgenda.cd_paciente = null; 
        this.modalAgenda.cd_convenio = null; 
        this.modalAgenda.itensAgendamento = null;
        this.modalAgenda.itemNoAgendamento = null;
        this.modalAgenda.profissao = null;
        this.modalAgenda.sn_finalizado = null;
        this.modalAgenda.historico= [];
        this.modalAgenda.nm_mae=null;
        this.modalAgenda.dt_nasc_mae=null;
        this.modalAgenda.celular_mae=null; 
        this.modalAgenda.nm_pai=null;
        this.modalAgenda.dt_nasc_pai=null;
        this.modalAgenda.celular_pai=null;
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

    opcoes(dados){
        this.dadosOpcoes=dados;
        console.log(dados);
        this.opcoesEscalaManual = dados.hr_start + ' - ' + dados.hr_end;
        $('.modalOpcoes').modal('toggle');
    },

    escalaManual(){
        this.loadingAcao = "Gerando Escala...";
        $('.absolute-loading').show();
        let form = new FormData(document.querySelector('#form-escala'));
        axios.post('/rpclinica/json/agenda-escala-manual', form)
        .then((res) => { 
            console.log(res.data);
            toastr['success'](res.data.message);
            $('#calendar').fullCalendar('refetchEvents');
             
        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => {  
            $('.absolute-loading').hide(); 
        });
       
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

    listaBloqueio(){
        this.bloqueio.load=true; 
        this.bloqueio.lista=[];
        axios.get(`/rpclinica/agendamentos-show?tipo=bloqueio&start=${this.bloqueio.data}&end=${this.bloqueio.data}&agendas=${this.bloqueio.agendas}`)
        .then((res) => { 
            console.log(res.data);  
            this.bloqueio.lista=res.data;
        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => {  
            this.bloqueio.load=false;
        });
    },

    storageBloqueio(){
        this.bloqueio.load=true;
        let form = new FormData(document.querySelector('#form-storage-bloqueio')); 
        axios.post('/rpclinica/json/agendamento-bloqueio-modal', form) 
        .then((res) => {  
            console.log(res.data);
            $('#calendar').fullCalendar('refetchEvents');
            toastr['success'](res.data.message);

        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => {  
            this.bloqueio.load=false; 
            $('.modalBloqueio').modal('toggle');
        });
    },

    Modal(dados) { 

        const element = document.querySelector('#Zap');
        element.classList.remove('whastValido');
        element.classList.remove('whastInvalido');
        element.classList.remove('whastNeutro'); 
        this.classWhast= 'fa fa-whatsapp  ';
        this.SituacaoWhast = null;
        this.foneWhast = null;
        
        $('#cadastro-consulta').modal('toggle');
        this.loadingAcao = "Carregando Informações...";
        $('#cadastro-consulta .absolute-loading').show();
        this.dadosAtendimento=null;
        this.userAtendimento=null,
        this.tp_agendamento = dados.tp;
        this.clear();
        this.clearPaciente();
        axios.post('/rpclinica/json/recepcao-agendamento', dados)
            .then((res) => {     
                console.log(res.data);
                this.modalData.agenda = res.data.agenda; 
                this.modalData.conv = res.data.convenios;
                this.modalData.prof = res.data.profissionais;
                this.modalData.local = res.data.locais;
                this.modalData.tipo = res.data.tipo_atend;
                this.modalData.espec = res.data.especialidades; 
                this.modalData.agenda = res.data.agenda;  
                this.modalData.situacoes = res.data.situacao_agendamento;
                
                this.modalData.errors = null;
                this.modalAgenda.cd_agendamento =dados.id;
                this.modalAgenda.cd_agenda =res.data.escala.cd_agenda; 
                this.modalAgenda.nm_agenda =dados.nm_resource; 
                this.modalAgenda.cd_escala =res.data.escala.cd_escala_agenda;
                this.modalAgenda.dt_agenda =res.data.data_end;
                this.modalAgenda.hr_inicio =res.data.hr_start;
                this.modalAgenda.hr_fim =res.data.hr_end;
                this.modalAgenda.situacao = (dados.situacao) ? dados.situacao : 'livre'; 
                this.modalAgenda.nm_paciente =res.data.agendamento?.paciente.nm_paciente;  
                this.modalAgenda.profissao =res.data.agendamento?.paciente.profissao;  
                if(res.data.agendamento?.paciente.idade){
                    $('#dadosIdade').html('&nbsp;&nbsp;((&nbsp;&nbsp;'+res.data.agendamento?.paciente.idade+'&nbsp;&nbsp;))');
                }else{
                    $('#dadosIdade').html('');

                }
                this.dataAtendimento=(res.data.agendamento?.data_atend) ? res.data.agendamento?.data_atend : null;   
                this.userAtendimento=(res.data.agendamento?.user_atendimento?.nm_usuario) ? res.data.agendamento?.user_atendimento?.nm_usuario : null;  
                this.modalAgenda.dt_nasc =res.data.agendamento?.paciente.dt_nasc;
                this.modalAgenda.cd_horario = dados.cd_horario;
                this.modalAgenda.rg =res.data.agendamento?.paciente.rg;
                this.modalAgenda.cpf =res.data.agendamento?.paciente.cpf;
                this.modalAgenda.celular =res.data.agendamento?.paciente.celular;
                this.modalAgenda.email =res.data.agendamento?.paciente.email;
                this.modalAgenda.cartao =res.data.agendamento?.paciente.cartao;
                this.modalAgenda.validade =res.data.agendamento?.paciente.dt_validade;
                this.modalAgenda.observacao =res.data.agendamento?.obs; 
                this.modalAgenda.dt_resp_whast =null; 
                this.modalAgenda.retorno_whast =null; 
                this.modalAgenda.dt_presenca =res.data.agendamento?.dt_atendimento;  
                this.modalAgenda.cd_dia =dados.cd_dia;
                this.modalAgenda.data_horario =dados.start;
                this.modalAgenda.intervalo =dados.intervalo;
                this.modalAgenda.nm_intervalo =dados.nm_intervalo;
                this.modalAgenda.cd_profissional = (res.data.agendamento?.cd_profissional) ? res.data.agendamento?.cd_profissional : res.data.agenda?.cd_profissional;
                this.modalAgenda.cd_local =(res.data.agendamento?.cd_local_atendimento) ? res.data.agendamento?.cd_local_atendimento : res.data.agenda?.cd_local_atendimento;
                this.modalAgenda.cd_tipo =(res.data.agendamento?.tipo) ? res.data.agendamento?.tipo : res.data.agenda?.cd_tipo_atend;
                this.modalAgenda.cd_especialidade =(res.data.agendamento?.cd_especialidade) ? res.data.agendamento?.cd_especialidade : res.data.agenda?.cd_especialidade;
                this.modalAgenda.cd_paciente =(res.data.agendamento?.cd_paciente) ? res.data.agendamento?.cd_paciente : null;
                this.modalAgenda.cd_convenio =res.data.agendamento?.cd_convenio;
                this.modalAgenda.itensAgendamento =res.data.itens_agenda;
                this.modalAgenda.itemNoAgendamento =res.data.itens_agendamento; 
                this.modalAgenda.sn_finalizado =res.data.agendamento?.tab_situacao?.finalizado; 
                this.modalAgenda.historico = res.data.historico;

                this.modalAgenda.nm_mae=(res.data.agendamento?.paciente.nm_mae) ? res.data.agendamento?.paciente.nm_mae : null;
                this.modalAgenda.dt_nasc_mae=(res.data.agendamento?.paciente.dt_nasc_mae) ? res.data.agendamento?.paciente.dt_nasc_mae : null;
                this.modalAgenda.celular_mae=(res.data.agendamento?.paciente.celular_mae) ? res.data.agendamento?.paciente.celular_mae : null;
                this.modalAgenda.nm_pai=(res.data.agendamento?.paciente.nm_pai) ? res.data.agendamento?.paciente.nm_pai : null;
                this.modalAgenda.dt_nasc_pai=(res.data.agendamento?.paciente.dt_nasc_pai) ? res.data.agendamento?.paciente.dt_nasc_pai : null;
                this.modalAgenda.celular_pai=(res.data.agendamento?.paciente.celular_pai) ? res.data.agendamento?.paciente.celular_pai : null;
                 
                //Paciente
                this.modalPaciente.cd_paciente= (res.data.agendamento?.cd_paciente) ? res.data.agendamento?.cd_paciente : null;
                this.modalPaciente.cd_agendamento= res.data.agendamento?.paciente.cd_agendamento;  
                this.modalPaciente.nm_paciente= res.data.agendamento?.paciente.nm_paciente; 
                this.modalPaciente.dt_nasc= res.data.agendamento?.paciente.dt_nasc; 
                this.modalPaciente.rg= res.data.agendamento?.paciente.rg; 
                this.modalPaciente.cpf= res.data.agendamento?.paciente.cpf; 
                this.modalPaciente.cartao_sus= res.data.agendamento?.paciente.cartao_sus; 
                this.modalPaciente.nome_social= res.data.agendamento?.paciente.nome_social; 
                this.modalPaciente.nm_responsavel= res.data.agendamento?.paciente.nm_responsavel;
                this.modalPaciente.cpf_responsavel= res.data.agendamento?.paciente.cpf_responsavel;
                this.modalPaciente.nm_mae= res.data.agendamento?.paciente.nm_mae; 
                this.modalPaciente.nm_pai= res.data.agendamento?.paciente.nm_pai; 
                this.modalPaciente.cartao= res.data.agendamento?.paciente.cartao; 
                this.modalPaciente.dt_validade= res.data.agendamento?.paciente.dt_validade;
                this.modalPaciente.fone= res.data.agendamento?.paciente.fone; 
                this.modalPaciente.celular= res.data.agendamento?.paciente.celular; 
                this.modalPaciente.email= res.data.agendamento?.paciente.email; 
                this.modalPaciente.cep= res.data.agendamento?.paciente.cep; 
                this.modalPaciente.logradouro= res.data.agendamento?.paciente.logradouro; 
                this.modalPaciente.numero= res.data.agendamento?.paciente.numero; 
                this.modalPaciente.complemento= res.data.agendamento?.paciente.complemento;  
                this.modalPaciente.nm_bairro= res.data.agendamento?.paciente.nm_bairro; 
                this.modalPaciente.cidade= res.data.agendamento?.paciente.cidade; 
                this.modalPaciente.profissao= res.data.agendamento?.paciente.profissao; 
                this.modalPaciente.dt_nasc_mae= res.data.agendamento?.paciente?.dt_nasc_mae;
                this.modalPaciente.celular_mae= res.data.agendamento?.paciente?.celular_mae;
                this.modalPaciente.dt_nasc_pai= res.data.agendamento?.paciente?.dt_nasc_pai;
                this.modalPaciente.celular_pai= res.data.agendamento?.paciente?.celular_pai;
  
                $('#cadastro-consulta select#pac-uf').val(res.data.agendamento?.paciente.uf).trigger('change'); 
                $('#cadastro-consulta select#pac-sexo').val(res.data.agendamento?.paciente.sexo).trigger('change');
                $('#cadastro-consulta select#pac-estado_civil').val(res.data.agendamento?.paciente.estado_civil).trigger('change');
                $('#cadastro-consulta select#pac-convenio').val(res.data.agendamento?.paciente.cd_categoria).trigger('change');
                $('#cadastro-consulta select#pac-vip').val(res.data.agendamento?.paciente.vip).trigger('change');
                $('#cadastro-consulta select#sexo_pac').val(res.data.agendamento?.paciente.sexo).trigger('change'); 
  
            })
            .catch((err) => { 
                 
                toastr['error'](err.response.data.message)
            })
            .finally(() => { 
 
                $('#cadastro-consulta select#agendamento-profissional').val(this.modalAgenda.cd_profissional).trigger('change');
                $('#cadastro-consulta select#cod_agenda').val(this.modalAgenda.cd_agenda).trigger('change');
                $('#cadastro-consulta select#agendamento-local').val(this.modalAgenda.cd_local).trigger('change');
                $('#cadastro-consulta select#agendamento-tipo').val(this.modalAgenda.cd_tipo).trigger('change');
                $('#cadastro-consulta select#agendamento-especialidade').val(this.modalAgenda.cd_especialidade).trigger('change');
                $('#cadastro-consulta select#agendamento-convenio').val(this.modalAgenda.cd_convenio).trigger('change');
                
                if (this.modalAgenda.cd_paciente) { 
                    let newOption = new Option(this.modalAgenda.nm_paciente, this.modalAgenda.cd_paciente, false, false);
                    $('#cadastro-consulta select#agendamento-paciente').append(newOption).trigger('change');
                    $('#cadastro-consulta select#agendamento-paciente').val(this.modalAgenda.cd_paciente).trigger('change');
                }else{ 
                    $('#cadastro-consulta select#agendamento-paciente').val(null).trigger('change');
                }
                if(this.modalAgenda.itemNoAgendamento){
                    $('#cadastro-consulta select#item_agendamento').val(this.modalAgenda.itemNoAgendamento).trigger('change');
                }else{
                    $('#cadastro-consulta select#item_agendamento').val([]).trigger('change');
                }
                $('#cadastro-consulta .absolute-loading').hide(); 
                  
        });
 
    },
 
    bloquearHorario() {
        this.loadingAcao = "Bloqueando...";
        $('#cadastro-consulta .absolute-loading').show();
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja bloquear esse horario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
        .then((result) => {
            if (result.isConfirmed) {
                axios.post('/rpclinica/json/agendamento/bloquear-horario', this.modalAgenda)
                .then((res) => { 
                    $('#calendar').fullCalendar('refetchEvents');
                    toastr['success'](res.data.message);
                    this.listaEscalas = res.data.escalas; 
                    $('#cadastro-consulta').modal('hide');
                })
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => {  
                    $('#cadastro-consulta .absolute-loading').hide(); 
                });
            }
        });
    },
 
    DesbloquearAgenda(dados) { 
        
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja desbloqueando esse horario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
        .then((result) => {
 
            if (result.isConfirmed) { 

                this.loadingAcao = "Desbloqueando...";
                $('#cadastro-consulta .absolute-loading').show();
                axios.post('/rpclinica/json/agendamento/desbloquear-horario', dados)
                .then((res) => { 
                    $('#calendar').fullCalendar('refetchEvents');
                    toastr['success'](res.data.message); 
                })
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => {  
                    $('#cadastro-consulta .absolute-loading').hide(); 
                });

            }

        });
    

    },
     
    ExcluirAgenda(){ 

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
                $('#cadastro-consulta .absolute-loading').show();
                axios.delete(`/rpclinica/json/agendamento/${this.modalAgenda.cd_agendamento}`)
                .then((res) => { 
                    $('#calendar').fullCalendar('refetchEvents');
                    toastr['success'](res.data.message);
                    this.listaEscalas = res.data.escalas; 
                    $('#cadastro-consulta').modal('hide');
                })
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => {  
                    $('#cadastro-consulta .absolute-loading').hide();
                    
                });
            }

        });
    },

    storeAgendamento() {

        this.modalData.errors = [];
        this.loadingAcao = "Agendando...";
        this.buttonSalvarAgendamneto = this.tempSalvando;
        $('#cadastro-consulta .absolute-loading').show();
        let form = new FormData(document.querySelector('#form-Agenda')); 
        axios.post('/rpclinica/json/recepcao-store-agendamento', form) 
        .then((res) => {   
            
            $('#calendar').fullCalendar('refetchEvents');
            $('#cadastro-consulta select#agendamento-profissional').val(null).trigger('change');
            $('#cadastro-consulta select#cod_agenda').val(null).trigger('change');
            $('#cadastro-consulta select#agendamento-local').val(null).trigger('change');
            $('#cadastro-consulta select#agendamento-tipo').val(null).trigger('change');
            $('#cadastro-consulta select#agendamento-especialidade').val(null).trigger('change');
            $('#cadastro-consulta select#agendamento-paciente').val(null).trigger('change');
            $('#cadastro-consulta select#agendamento-convenio').val(null).trigger('change'); 

            if(this.tp_agendamento == 2 ){
                this.pesquisaAvancada();
            }
            
            if(res.data.retorno == true){
                toastr['success'](res.data.ds_retorno);  
            }else{
                toastr['error']('Erro no cadastro do agendamento!');  
            }
            
            $('#cadastro-consulta').modal('hide');
 
        })
        .catch((err) => {
            Object.values(err.response.data.errors).forEach((errors) => {
                this.modalData.errors = this.modalData.errors.concat(errors);
            })
        })
        .finally(() => {  
            
            this.buttonSalvarAgendamneto = this.tempSalvar;
            $('#cadastro-consulta .absolute-loading').hide();
            
        });
        
    },
 

    FullCalendar: function() {
        var _this10 = this;

        // --- CÁLCULO DA ALTURA DA LINHA ---
        // Pega o intervalo (ex: 15 min) e define a altura em pixels (ex: 52.5px)
        // Isso evita que a agenda fique "esticada" ou "esmagada" demais
        var slotDurationMinutes = moment.duration(this.horarioPadrao).asMinutes();
        var slotHeightPx = slotDurationMinutes * 3;

        // Injeta a variável no CSS do calendário
        $('#calendar').get(0).style.setProperty('--slot-height', slotHeightPx + 'px');

        $('#calendar').fullCalendar({
            customButtons: {
                modal: {
                    click: function () {
                        $('.modalParametros').modal('toggle');
                    }
                },
                bloqueio: {
                    click: function () {
                        console.log($('#calendar').fullCalendar('getDate').format());
                        $('.modalBloqueio').modal('toggle');
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
                update_agenda: {
                    click: function () {
                        $('#calendar').fullCalendar('refetchEvents');
                    }
                },
                escala_manual: {
                    click: function () {
                        $('.modalEscalaDiario').modal('toggle');
                    }
                },
            },

            buttonIcons: {
                modal: 'fa fa fa-filter btn-verde',
                gotoDate: 'fa fa fa-calendar btn-roxo',
                update_agenda: 'fa fa fa-refresh btn-azul',
                envio_whast: 'fa fa fa-whatsapp btn-verde',
                bloqueio: 'fa fa fa-ban btn-red',
                escala_manual: 'fa fa  fa-check-square btn-roxo',
            },

            header: {
                left: 'gotoDate modal update_agenda bloqueio escala_manual',
                center: 'prev title next ',
                right: 'today agendaDay,agendaWeek,month'
            },

            defaultView: 'agendaDay',
            editable: true,
            droppable: false,
            eventLimit: true,
            selectable: true,
            selectHelper: true,
            minTime: (empresa.hr_inicial) ? empresa.hr_inicial : '07:00:00',
            maxTime: (empresa.hr_final) ? empresa.hr_final : '19:00:00',
            slotDuration: (this.horarioPadrao) ? this.horarioPadrao : '00:10:00',
            slotLabelInterval: (this.horarioPadrao) ? this.horarioPadrao : '00:10:00',
            selectMirror: false,
            dayMaxEvents: false,
            allDaySlot: false,
            weekends: false,
            locale: 'pt-br',
            groupByResource: false,
            timeFormat: 'HH:mm',
            slotLabelFormat: 'HH:mm',
            allDayText: '24 horas',
            defaultDate: Date(),
            hiddenDays: DiasAgenda,
            businessHours: businessHours,
            resources: resources,

            select: (start, end, jsEvent, view, resource) => {
                console.log(resource);
                console.log(start);
                console.log(end);

                if (!resource) {
                    toastr['error']("Essa ação não é permitido com essa Visão (Mensal ou Semanal)!");
                    $('#calendar').fullCalendar('refetchEvents');
                    return false;
                }

                var dadosHorario = {
                    "start": $.fullCalendar.formatDate(start, 'YYYY-MM-DD HH:mm'),
                    "dt_start": $.fullCalendar.formatDate(start, 'DD/MM/YYYY HH:mm'),
                    "data_start": $.fullCalendar.formatDate(start, 'YYYY-MM-DD'),
                    "hr_start": $.fullCalendar.formatDate(start, 'HH:mm'),
                    "end": $.fullCalendar.formatDate(end, 'YYYY-MM-DD HH:mm'),
                    "dt_end": $.fullCalendar.formatDate(end, 'DD/MM/YYYY HH:mm'),
                    "data_end": $.fullCalendar.formatDate(end, 'YYYY-MM-DD'),
                    "hr_end": $.fullCalendar.formatDate(end, 'HH:mm'),
                    "resource": resource.id,
                    "nm_resource": resource.title,
                    "agenda_aberta": resource.agenda_aberta,
                    "escala": null,
                    "cd_dia": null,
                    "nm_intervalo": null,
                    "intervalo": null,
                    "id": null,
                    "situacao": null,
                    "cd_horario": null,
                    "tp": "2",
                };
                this.opcoes(dadosHorario);
                console.log('select');
            },

            drop: function (arg) {
                console.log('drop');
            },

            eventClick: (dados, jsEvent, view) => {
                console.log(dados);
                console.log('eventClick');

                var dadosHorario = {
                    "start": $.fullCalendar.formatDate(dados.start, 'YYYY-MM-DD HH:mm'),
                    "dt_start": $.fullCalendar.formatDate(dados.start, 'DD/MM/YYYY HH:mm'),
                    "data_start": $.fullCalendar.formatDate(dados.start, 'YYYY-MM-DD'),
                    "hr_start": $.fullCalendar.formatDate(dados.start, 'HH:mm'),
                    "end": $.fullCalendar.formatDate(dados.end, 'YYYY-MM-DD HH:mm'),
                    "dt_end": $.fullCalendar.formatDate(dados.end, 'DD/MM/YYYY HH:mm'),
                    "data_end": $.fullCalendar.formatDate(dados.end, 'YYYY-MM-DD'),
                    "hr_end": $.fullCalendar.formatDate(dados.end, 'HH:mm'),
                    "resource": dados.resourceId,
                    "nm_resource": dados.description,
                    "escala": dados.escalaId,
                    "cd_dia": dados.cd_dia,
                    "nm_intervalo": dados.escalaNmIntervalo,
                    "intervalo": dados.escalaIntervalo,
                    "id": dados.id,
                    "situacao": dados.titulo,
                    "cd_horario": dados.cd_horario,
                    "tp": "1",
                };
                if (dados.titulo == "Bloqueado") {
                    this.DesbloquearAgenda(dadosHorario);
                } else {
                    this.Modal(dadosHorario);
                }
            },

            eventDidMount: function (info) {
                console.log('eventDidMount');
            },

            eventDrop: (event) => {
                if (event.situacao == 'livre') {
                    toastr['error']("Esse tipo de Objeto não é permitido alterar!");
                    $('#calendar').fullCalendar('refetchEvents');
                    return false;
                }
                if (event.situacao == 'bloqueado') {
                    toastr['error']("Esse tipo de Objeto não é permitido alterar!");
                    $('#calendar').fullCalendar('refetchEvents');
                    return false;
                }
                if (event.cd_agendamento) {
                    var dadosHorario = {
                        "start": $.fullCalendar.formatDate(event.start, 'YYYY-MM-DD HH:mm'),
                        "dt_start": $.fullCalendar.formatDate(event.start, 'DD/MM/YYYY HH:mm'),
                        "data_start": $.fullCalendar.formatDate(event.start, 'YYYY-MM-DD'),
                        "hr_start": $.fullCalendar.formatDate(event.start, 'HH:mm'),
                        "end": $.fullCalendar.formatDate(event.end, 'YYYY-MM-DD HH:mm'),
                        "dt_end": $.fullCalendar.formatDate(event.end, 'DD/MM/YYYY HH:mm'),
                        "data_end": $.fullCalendar.formatDate(event.end, 'YYYY-MM-DD'),
                        "hr_end": $.fullCalendar.formatDate(event.end, 'HH:mm'),
                        "id": event.cd_agendamento,
                        "resource": null,
                    };

                    axios.post('/rpclinica/json/agendamento/alterar-horario', dadosHorario)
                        .then((res) => {
                            toastr['success'](res.data.message);
                        })
                        .catch((err) => toastr['error'](err.response.data.message));

                } else {
                    toastr['error']("Esse tipo de Objeto não é permitido alterar!");
                    $('#calendar').fullCalendar('refetchEvents');
                }
            },

            // --- FUNÇÃO PRINCIPAL DE RENDERIZAÇÃO VISUAL ---
        eventRender: function eventRender(event, element, view) {
            var classeCor = event.className || event.event_bg || 'event-bg';
            if (Array.isArray(classeCor)) classeCor = classeCor[0];

            element.addClass(classeCor);
            element.attr('data-situacao', event.situacao);

            var horario = moment(event.start).format('HH:mm');
            var conteudoHtml = '';

            if (event.situacao == "livre") {
                conteudoHtml =
                    '<div style="text-align:center; font-weight:bold; font-size:11px; color:#555;">' +
                        horario + ' LIVRE' +
                    '</div>';
            }
            else if (event.situacao == "bloqueado") {
                conteudoHtml =
                    '<div style="font-size:11px; font-weight:bold; padding-top:2px;">' +
                        '<i class="fa fa-ban"></i> BLOQUEADO' +
                    '</div>' +
                    '<div style="font-size:9px; opacity:0.8;">' + (event.nm_resource || '') + '</div>';
            }
            else {
                var paciente = event.nm_paciente || 'Paciente';
                var tipoAtendimento = event.nm_tipo_atendimento || event.tipo_atend || event.tipo || '';
                var textoTipo = tipoAtendimento ? ' | <span style="text-transform:uppercase; font-weight:800;">' + tipoAtendimento + '</span>' : '';

                var iconeStatus = '';
                if(event.situacao == 'confirmado') iconeStatus = '<i class="fa fa-check-circle"></i> ';
                else if(event.situacao == 'atendido') iconeStatus = '<i class="fa fa-check"></i> ';

                var detalhes = (event.convenio || '') + (event.especialidade ? ' • ' + event.especialidade : '');

                conteudoHtml =
                    '<div class="event-time" style="font-size:11px; font-weight:bold; line-height:1.1; display:block;">' +
                        horario + ' ' + iconeStatus + textoTipo +
                    '</div>' +
                    '<div class="event-details" style="font-size:10px; margin-top:2px; line-height:1.1; display:block;">' +
                        '<span style="font-weight:600; display:block;">' + paciente + '</span>' +
                        '<span style="font-size:9px; opacity:0.9; display:block;">' + detalhes + '</span>' +
                    '</div>';
            }

            if (element.find('.fc-content').length) {
                element.find('.fc-content').html(conteudoHtml);
            } else {
                element.html(conteudoHtml);
            }

        },

            eventResize: (dados) => {
                console.log('eventResize');
            },

            events: (start, end, timezone, callback) => {
                $.ajax({
                    url: "/rpclinica/agendamentos-show",
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
    },

    
    FullCalendarANT() {
 
        $('#calendar').fullCalendar({

            customButtons: {
                modal: {
                    
                    click: function () {
                        $('.modalParametros').modal('toggle');
                    }
                },
                bloqueio: {
                    click: function () { 
                        console.log( $('#calendar').fullCalendar('getDate').format());
                        $('.modalBloqueio').modal('toggle');
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
                 
                update_agenda: {
                    click: function () {
                        $('#calendar').fullCalendar('refetchEvents');
                        //$('#calendar').fullCalendar('gotoDate', '2024-07-15');
                    }
                },

                escala_manual: {
                    click: function () { 
                        //console.log( $('#calendar').fullCalendar('getDate').format());
                        $('.modalEscalaDiario').modal('toggle');
                    }
                },
            },

            buttonIcons: {
                modal: 'fa fa fa-filter btn-verde',
                gotoDate: 'fa fa fa-calendar btn-roxo', 
                update_agenda: 'fa fa fa-refresh btn-azul',
                envio_whast: 'fa fa fa-whatsapp btn-verde',
                bloqueio: 'fa fa fa-ban btn-red',
                escala_manual: 'fa fa  fa-check-square btn-roxo',
            },

            header: {
               left: 'gotoDate modal update_agenda bloqueio escala_manual',
                center: 'prev title next ',
                right: 'today agendaDay,agendaWeek,month'
            },

            defaultView: 'agendaDay',
            editable: true,
            droppable: false,
            eventLimit: true,
            selectable: true,
            selectHelper: true,
            minTime: ( empresa.hr_inicial ) ? empresa.hr_inicial : '07:00:00',
            maxTime: ( empresa.hr_final ) ? empresa.hr_final : '19:00:00',  
            slotDuration: (this.horarioPadrao) ? this.horarioPadrao : '00:10:00',
            slotLabelInterval:  (this.horarioPadrao) ? this.horarioPadrao : '00:10:00',
            selectMirror: false,
            dayMaxEvents: false,
            allDaySlot: false,
            weekends: false,
            locale: 'pt-br',
            groupByResource: false,
            timeFormat: 'HH:mm',
            slotLabelFormat: 'HH:mm', 
            allDayText: '24 horas', 
            defaultDate: Date(),
            hiddenDays: DiasAgenda, 
            businessHours: businessHours, 
            resources: resources, 

            select: (start, end, jsEvent, view, resource) => { 
                 
                 console.log(resource);
                 console.log(start);
                 console.log(end);
                 if(!resource){
                    toastr['error']("Essa ação não é permitido com essa Visão (Mensal ou Semanal)!");  
                    $('#calendar').fullCalendar('refetchEvents');
                    return false;
                }


                var dadosHorario = {
                    "start": $.fullCalendar.formatDate(start, 'YYYY-MM-DD HH:mm'),
                    "dt_start": $.fullCalendar.formatDate(start, 'DD/MM/YYYY HH:mm'),
                    "data_start": $.fullCalendar.formatDate(start, 'YYYY-MM-DD'),
                    "hr_start": $.fullCalendar.formatDate(start, 'HH:mm'),
                    "end": $.fullCalendar.formatDate(end, 'YYYY-MM-DD HH:mm'),
                    "dt_end": $.fullCalendar.formatDate(end, 'DD/MM/YYYY HH:mm'),
                    "data_end": $.fullCalendar.formatDate(end, 'YYYY-MM-DD'),
                    "hr_end": $.fullCalendar.formatDate(end, 'HH:mm'),
                    "resource": resource.id,
                    "nm_resource": resource.title,
                    "agenda_aberta": resource.agenda_aberta,
                    "escala": null,
                    "cd_dia": null,
                    "nm_intervalo": null,
                    "intervalo": null,
                    "id": null,
                    "situacao": null,
                    "cd_horario": null, 
                    "tp": "2", 
                }; 
                this.opcoes(dadosHorario);
                console.log('select');

            },

            drop: function (arg) {  
                console.log('drop');
            },

            eventClick: (dados,  jsEvent, view ) => { 
                console.log(dados);
                console.log('eventClick');
 
                var dadosHorario = {
                    "start": $.fullCalendar.formatDate(dados.start, 'YYYY-MM-DD HH:mm'),
                    "dt_start": $.fullCalendar.formatDate(dados.start, 'DD/MM/YYYY HH:mm'),
                    "data_start": $.fullCalendar.formatDate(dados.start, 'YYYY-MM-DD'),
                    "hr_start": $.fullCalendar.formatDate(dados.start, 'HH:mm'),
                    "end": $.fullCalendar.formatDate(dados.end, 'YYYY-MM-DD HH:mm'),
                    "dt_end": $.fullCalendar.formatDate(dados.end, 'DD/MM/YYYY HH:mm'),
                    "data_end": $.fullCalendar.formatDate(dados.end, 'YYYY-MM-DD'),
                    "hr_end": $.fullCalendar.formatDate(dados.end, 'HH:mm'),
                    "resource": dados.resourceId,
                    "nm_resource": dados.description,
                    "escala": dados.escalaId,
                    "cd_dia": dados.cd_dia,
                    "nm_intervalo": dados.escalaNmIntervalo,
                    "intervalo": dados.escalaIntervalo,
                    "id": dados.id,
                    "situacao": dados.titulo,
                    "cd_horario": dados.cd_horario, 
                    "tp": "1", 
                }; 
                if(dados.titulo == "Bloqueado"){
                    this.DesbloquearAgenda(dadosHorario);
                } else {
                    this.Modal(dadosHorario);
                } 
                 
            },

            eventDidMount: function (info) {  
                console.log('eventDidMount');
            },

            eventDrop: (event ) => { 
                if(event.situacao=='livre'){
                    toastr['error']("Esse tipo de Objeto não é permitido alterar!");  
                    $('#calendar').fullCalendar('refetchEvents');
                    return false;
                }
                if(event.situacao=='bloqueado'){
                    toastr['error']("Esse tipo de Objeto não é permitido alterar!");  
                    $('#calendar').fullCalendar('refetchEvents');
                    return false;
                }
                if(event.cd_agendamento){
  
                    var dadosHorario = {
                        "start": $.fullCalendar.formatDate(event.start, 'YYYY-MM-DD HH:mm'),
                        "dt_start": $.fullCalendar.formatDate(event.start, 'DD/MM/YYYY HH:mm'),
                        "data_start": $.fullCalendar.formatDate(event.start, 'YYYY-MM-DD'),
                        "hr_start": $.fullCalendar.formatDate(event.start, 'HH:mm'),
                        "end": $.fullCalendar.formatDate(event.end, 'YYYY-MM-DD HH:mm'),
                        "dt_end": $.fullCalendar.formatDate(event.end, 'DD/MM/YYYY HH:mm'),
                        "data_end": $.fullCalendar.formatDate(event.end, 'YYYY-MM-DD'),
                        "hr_end": $.fullCalendar.formatDate(event.end, 'HH:mm'),
                        "id": event.cd_agendamento,
                        "resource": null,
                    };

                    axios.post('/rpclinica/json/agendamento/alterar-horario', dadosHorario)
                    .then((res) => {
                        toastr['success'](res.data.message);
                     })
                    .catch((err) => toastr['error'](err.response.data.message));

                }else{
                    toastr['error']("Esse tipo de Objeto não é permitido alterar!");  
                    $('#calendar').fullCalendar('refetchEvents');
                }

  
            },
         

            eventRenderr_antigo: function eventRender(event, element, view) {
                console.log(event);
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

            eventRender: function eventRender(event, element, view) {
                
                     
                if(event.situacao=="livre"){  

                    element.find('.fc-time').append('<div class="hr-line-solid-no-margin"></div> <div style="font-size: 10px;">' +
                       "<line style='font-size: 1.2em; color:#6d6b6b; font-style: italic;font-weight: 400;'> Horario Livre  </line></div> "); 
                         
                }else{

                    if(event.situacao=="bloqueado"){  
                        
                        element.find('.fc-time').append('<div class="hr-line-solid-no-margin"></div> <div style="font-size: 10px;">' +
                        "<line style='  color:#6d6b6b; font-style: italic;font-weight: 400;'>&nbsp;&nbsp;<span style='color:red;padding-top: 4px;' class='glyphicon glyphicon-ban-circle' aria-hidden='true'></span>&nbsp;&nbsp; Horario Bloqueado  </line></div> " +
                        "<line style='  color:#6d6b6b; font-style: italic;font-weight: 400;'>&nbsp;&nbsp;&nbsp;<i class='fa fa-user-md'></i> "+ ((event.nm_resource) ? (event.nm_resource) : '  ')  + " </line></div> "); 
                           
                    }else{ 
                        if(event.situacao){
                            element.find('.fc-title').append("  <div style='width:100%; '> " +
                                "<line ><b>[&nbsp; "+this.description+"&nbsp; ]</b>&nbsp;&nbsp; "+event.convenio+"&nbsp;|&nbsp; "+event.especialidade+"</line><br>" +
                                "</div>");
                                
                            element.find('.fc-time').append(" &nbsp; " + this.icone  +  event.titulo + "&nbsp;" +   ( (event.encaixe==null) ? '' : event.encaixe ) + " &nbsp; | " + ( (event.tipo_atend==null) ? '' : event.tipo_atend )   ) ;    
                            
                        }else{
                            element.html("<div style='width:100%; font-size: 1.5em; text-align: center; color: #ffffff;font-style: italic;'> <i class='fa fa-refresh fa-spin'></i></div>");
                        }

                    }

                }
                 
            },

            eventResize: (dados) => { 
                console.log('eventResize');
            },

            events:  (start, end,  timezone, callback) => {
                  
                $.ajax({
                    
                    url: "/rpclinica/agendamentos-show",
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
            case "bloqueado":
                return '<span class="btnCancelado"> <span aria-hidden="true" class="icon-close" style="padding-right: 10px;"></span> &nbsp;&nbsp;  Bloqueado&nbsp;&nbsp;&nbsp;<span class="caret"></span></span>';
                break;
            default:
                return "<span> &nbsp;&nbsp;"+situacao+" &nbsp;&nbsp;&nbsp;<span class='caret'></span> </span>";
        }
    },
 
    buscarCep(){
        axios.get('https://viacep.com.br/ws/'+this.modalPaciente.cep.replace(/[^0-9]/g,'')+'/json') 
        .then((res) => { 
            this.modalPaciente.logradouro = res.data.logradouro;
            this.modalPaciente.nm_bairro = res.data.bairro;
            this.modalPaciente.cidade = res.data.localidade;
            $('#cadastro-consulta select#pac-uf').val(res.data.uf).trigger('change');
        })
        .catch((err) => {
            this.messageDanger = 'Erro@=!';
        })

    },

    atualizarPaciente() {
        this.loadingAcao = "Atualizando Paciente..."; 
        $('#cadastro-consulta .absolute-loading').show();
        let form = new FormData(document.querySelector('#form-pac'));

        axios.post('/rpclinica/json/pacientes-update-join', form)
            .then((res) => {
                this.clearPaciente();
                toastr['success']('Paciente atualizado com sucesso!'); 
                $('#calendar').fullCalendar('refetchEvents');  
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
            .finally(() => $('#cadastro-consulta .absolute-loading').hide());
    },
   
    atualizaStatus(situacao) {
        this.dataAtendimento=null;
        this.userAtendimento=null;
        this.loadingAcao = "Atualizando...";
        axios.put('/rpclinica/json/atualiza-status-agendamento',{ cd_agendamento: this.modalAgenda.cd_agendamento, situacao: situacao  })
        .then((res) => {

            this.dataAtendimento=(res.data.presenca.data) ? res.data.presenca.data : null;
            this.userAtendimento=(res.data.presenca.user) ? res.data.presenca.user : null;

            $('#calendar').fullCalendar('refetchEvents'); 
            toastr['success'](res.data.message);
            $("#situacaoButton").html(this.buttonAgendamento(situacao));

        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => $('#cadastro-consulta .absolute-loading').hide());

    },

    pesquisaAvancada() {
        this.buttonPesqAvanc = true;
        this.loadingAcao = "Pesquisando...";
        $('#loadingPesqAvanc .absolute-loading').show();
        let form = new FormData(document.querySelector('#form-pesquisa-avanc')); 
        axios.post('/rpclinica/json/agendamento-avanc', form)
        .then((res) => {  
            this.tablePesqAvanc = res.data;
        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => {
            $('#loadingPesqAvanc .absolute-loading').hide();
            this.buttonPesqAvanc = false; 
        }); 
    },

    cadastrarItem(){
        this.loadingAcao = "Cadastrando...";
        $('#loadingPesqAvanc .absolute-loading').show();
        let form = new FormData(document.querySelector('#form-item')); 
        axios.post(`/rpclinica/json/agendamento-item/${this.modalAgenda.cd_agendamento}`, form)
        .then((res) => {  
            $('#cadastro-consulta select#cod_exme_item').val(null).trigger('change');
            this.modalAgenda.itensAgendamento =res.data.retorno.itens;
            toastr['success'](res.data.message); 
        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => {
            $('#loadingPesqAvanc .absolute-loading').hide();
            this.buttonPesqAvanc = false; 
        }); 
    },

    excluirItem(ITEM){ 

        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse Item?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
        .then((result) => {
 
            if (result.isConfirmed) {
                this.loadingAcao = "Exluindo...";
                $('#cadastro-consulta .absolute-loading').show();
                axios.delete(`/rpclinica/json/agendamento-item/${ITEM.cd_agendamento_item}`)
                .then((res) => {  
                    toastr['success'](res.data.message);  
                    this.modalAgenda.itensAgendamento =res.data.retorno.itens;
                })
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => {  
                    $('#cadastro-consulta .absolute-loading').hide();
                    
                });
            }

        });
    },
 
    pesquisaConfirmacao() {

        this.buttonPesqAvanc = true;
        this.loadingAcao = "Pesquisando...";
        $('#loadingPesqAvanc .absolute-loading').show();
        this.formConfirmacao = new FormData(document.querySelector('#form-pesquisa-confirmacao')); 
        axios.post('/rpclinica/json/agendamento-confirmacao', this.formConfirmacao)
        .then((res) => {    
            console.log(res.data);
            this.tablePesqConfir = res.data;
        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => {
            $('#loadingPesqAvanc .absolute-loading').hide();
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

    storeEscalaPersonalizada(){

        this.loadingAcao = "Gerando Escala...";
        $('.absolute-loading').show();
        let form = new FormData(document.querySelector('#storeEscalaPersonalizada')); 
        axios.post('/rpclinica/json/agenda-escala-manual', form)
        .then((res) => {  
            toastr['success'](res.data.message);
            $('#calendar').fullCalendar('refetchEvents'); 
            document.getElementById("storeEscalaPersonalizada").reset();
            $('#modalEscalaDiarioAGENDA').val(null).trigger('change');  
            $('#modalEscalaDiarioINTERVALO').val(null).trigger('change');  
        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => {  
            $('.absolute-loading').hide(); 
        });
    },

    titleize(text) {
        var words = text.toLowerCase().split(" ");
        for (var a = 0; a < words.length; a++) {
            var w = words[a];
            words[a] = w[0].toUpperCase() + w.slice(1);
        }
        return words.join(" ");
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
                    $('#nm_mae_pac').val(response.data.nm_mae);
                    $('#dt_nasc_mae_pac').val(response.data.dt_nasc_mae);
                    $('#celular_mae_pac').val(response.data.celular_mae);

                    $('#nm_pai_pac').val(response.data.nm_pai);
                    $('#dt_nasc_pai_pac').val(response.data.dt_nasc_pai);
                    $('#celular_pai_pac').val(response.data.celular_pai);

                    $('#agendamento-convenio').val(response.data.cd_categoria).trigger('change');
                    $('#agendamento-cartao').val(response.data.cartao);
                    $('#cartao-validade').val(response.data.dt_validade);
                    $('#data-de-nasc').val(response.data.dt_nasc);
                    $('#cpf').val(response.data.cpf);
                    $('#rg').val(response.data.rg);
                    $('#email').val(response.data.email);
                    $('#agendamento-celular').val(response.data.celular);
                    //$('#dadosIdade').val(response.data.idade);
                    $('#dadosIdade').html('&nbsp;&nbsp;((&nbsp;&nbsp;'+response.data.idade+'&nbsp;&nbsp;))');
                    $('#ds_profissao').val(response.data.profissao); 
                    $('#sexo_pac').val(response.data.sexo).trigger('change')
                    
                    //$('#nome-mae-paciente').prop('readonly', true);
                    //$('#nome-pai-paciente').prop('readonly', true);
                    //$('#data-de-nasc').prop('readonly', true);
                })
                .catch((error) => toastr['error'](error.response.data.message))
                .finally(() => this.loadingPaciente = false);
        } else {

            $('#nm_mae_pac').val("");
            $('#dt_nasc_mae_pac').val("");
            $('#celular_mae_pac').val("");
            $('#nm_pai_pac').val("");
            $('#dt_nasc_pai_pac').val("");
            $('#celular_pai_pac').val("");
            $('#data-de-nasc').val('');
            $('#cpf').val('');
            $('#rg').val('');
            $('#agendamento-celular').val('');
            $('#agendamento-email').val('');
            $('#agendamento-cartao').val('');
            $('#cartao-validade').val('');
            $('#dadosIdade').html('')
            $('#ds_profissao').val("");
            $('#sexo_pac').val("").trigger('change')

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

  
});





