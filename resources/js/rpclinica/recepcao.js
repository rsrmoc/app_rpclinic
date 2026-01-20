import axios from 'axios';
import moment from 'moment';

var agendamentoDadosMes = null;
var agendamentoDadosDiaControll = null;
var agendamentoDadosMesControll = null;

Alpine.data('app', () => ({
    classWhast : 'fa fa-whatsapp whastNeutro',
    INDEX: null,
    loading: false,
    horarios: null,
    loadingAcao: null,
    messageDanger: null,
    dadosAtend: null,
    tituloModal: null,
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

    modalData: {errors: [],agenda: null,prof: null,local: null, tipo:null, espec: null, conv: null,itens: null,itens_pendente: null}, 

    modalAgenda: {cd_agendamento: null, itensAgendamento: null, itensGuia: null },

    modalAtendimentos:{ cd_agendamento: null, profissional: null,data: null,cd_local: null,cd_tipo: null,cd_especialidade: null,
        carater: null,paciente: null,dt_nasc: null,rg: null,cpf: null,celular: null,email: null,cd_origem: null,
        cd_prof_solicitante: null,cd_convenio: null,cartao: null,dt_validade: null,obs: null,situacao: null,   
        link_convenio: null, usuario_convenio: null, senha_convenio: null,cd_paciente: null,user_atend: null, profissao: null,
        user_agenda: null,user_pre: null, sn_atendimento: null,data_agendamento: null,data_atendimento: null,tp_convenio: null,
        sn_finalizado: null
    },

    modalGuias:{ itens: null, guias: null},

    modalPaciente:{ 
        cd_paciente: null, cd_agendamento: null, nm_paciente: null, dt_nasc: null, rg: null, cpf: null, cartao_sus: null, nome_social: null, 
        nm_responsavel: null, cpf_responsavel: null, nm_mae: null, nm_pai: null, cartao: null, dt_validade: null, fone: null, celular: null, vip:null,profissao: null,
        email: null, cep: null, logradouro: null, numero: null, complemento: null, nm_bairro: null, cidade: null,sexo:null,estado_civil:null,cd_categoria:null,uf:null
        
    },
   
    buttonGerarAtend: ' <i  class="fa fa-check"></i> Gerar Atendimento ',
    tempGerarAtend: ' <i  class="fa fa-check"></i> Gerar Atendimento ',
    buttonDisabled: false,
    buttonSalvarGuia: ' <span class="glyphicon glyphicon-check" aria-hidden="true"></span> Salvar ',
    buttonSalvarItem: ' <span class="glyphicon glyphicon-check" aria-hidden="true"></span> Salvar ',
    tempSalvar: '<span class="glyphicon glyphicon-check" aria-hidden="true"></span> Salvar ',
    buttonSalvando : " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ",

    init() {

        $('#data-input').val(moment().format('YYYY-MM-DD'));
        $('#calendar').datepicker('setDate', moment().format('YYYY-MM-DD'));
   
        $('#form-horario select').on('select2:select', () => {
            this.getHorarios()
        });

        $('#calendar').on('changeDate', () => {
            $('#data-input').val(
                $('#calendar').datepicker('getFormattedDate')
            ); 
            this.getHorarios()
        });
 
        $('#agendamento-origem').on('select2:select', (evt) => { 
            this.modalAtendimentos.cd_origem = evt.params.data.id;
        }); 
        $('#agendamento-local').on('select2:select', (evt) => { 
            this.modalAtendimentos.cd_local = evt.params.data.id;  
        });
        $('#agendamento-tipo').on('select2:select', (evt) => { 
            this.modalAtendimentos.cd_tipo = evt.params.data.id;  
        });
        $('#agendamento-especialidade').on('select2:select', (evt) => { 
            this.modalAtendimentos.cd_especialidade = evt.params.data.id;  
        });
        $('#agendamento-carater').on('select2:select', (evt) => { 
            this.modalAtendimentos.carater = evt.params.data.id;  
        });
        $('#agendamento-prof-ext').on('select2:select', (evt) => {  
            this.modalAtendimentos.cd_prof_solicitante = evt.params.data.id;  
        });
        $('#agendamento-convenio').on('select2:select', (evt) => { 
            this.modalAtendimentos.cd_convenio = evt.params.data.id; 
            let el = evt.params.data.element; 
            this.modalAtendimentos.link_convenio = el.dataset.link
            this.modalAtendimentos.usuario_convenio = el.dataset.user
            this.modalAtendimentos.senha_convenio = el.dataset.senha 
        });
        
        this.getHorarios()
  
    },

    getHorarios() {
 
        this.loading = true;  
        let form = new FormData(document.querySelector('#form-horario'));
        axios.post('/rpclinica/json/show-recepcao', form)
            .then((res) => { 
                this.horarios=res.data.retorno;
            })
            .catch((err) => this.messageDanger = err.response.data.message)
            .finally(() => this.loading = false);

    },

    dadosAtendimento(dados) {
        console.log(dados)
        //dados do atendimento  
        this.modalAtendimentos.cd_agendamento = dados.cd_agendamento
        if(dados.profissional){
            this.modalAtendimentos.profissional =dados.profissional?.nm_profissional
        }
        
        this.modalAtendimentos.data =dados.dt_agenda
        this.modalAtendimentos.cd_local=dados.cd_local_atendimento
        this.modalAtendimentos.cd_tipo=dados.tipo
        this.modalAtendimentos.cd_especialidade=dados.cd_especialidade
        this.modalAtendimentos.carater=dados.carater
        this.modalAtendimentos.cd_paciente=dados.paciente?.cd_paciente
        this.modalAtendimentos.paciente=dados.paciente?.nm_paciente
        this.modalAtendimentos.profissao=dados.paciente?.profissao
        this.modalAtendimentos.dt_nasc=dados.paciente?.dt_nasc
        this.modalAtendimentos.rg=dados.paciente?.rg
        this.modalAtendimentos.cpf=dados.paciente?.cpf
        this.modalAtendimentos.celular=dados.paciente?.celular
        this.modalAtendimentos.email=dados.paciente?.email
        this.modalAtendimentos.cd_origem=dados.cd_origem
        this.modalAtendimentos.cd_prof_solicitante=dados.cd_prof_solicitante
        this.modalAtendimentos.cd_convenio=dados.cd_convenio
        this.modalAtendimentos.cartao=dados.cartao
        this.modalAtendimentos.dt_validade=dados.dt_validade
        this.modalAtendimentos.obs=dados.obs
        this.modalAtendimentos.situacao=dados.situacao 
        this.modalAtendimentos.link_convenio = dados.convenio?.link_autorizacao
        this.modalAtendimentos.usuario_convenio = dados.convenio?.user_autorizacao
        this.modalAtendimentos.senha_convenio = dados.convenio?.senha_autorizacao
        this.modalAtendimentos.user_atend = dados.user_atendimento
        this.modalAtendimentos.user_agenda = dados.user_agendamento
        this.modalAtendimentos.user_pre = dados.user_pre_exame
        this.modalAtendimentos.sn_atendimento=dados.sn_atendimento
        this.modalAtendimentos.data_agendamento=dados.data_agendamento
        this.modalAtendimentos.data_atendimento=dados.data_atendimento
        this.modalAtendimentos.sn_finalizado=dados.sn_finalizado
        this.modalAtendimentos.tp_convenio = ( dados.convenio?.tp_convenio ) ? dados.convenio.tp_convenio : 'CO'
         
    },
    
    dadosGuia(dados){
        modalGuias.itens = dados.itens
    },

    dadosGuiaItens(id){
        axios.get(`/rpclinica/json/carrega-guia-itens/${id}`)
        .then((res) => {   
            this.modalAgenda.itensAgendamento=res.data.retorno.itens; 
            this.modalAgenda.itensGuia = res.data.retorno.guia; 
            this.modalData.itens_pendente = res.data.itens;  

        })
        .catch((err) => toastr['error'](err.response.data.message)) 
        
    },

    clickHorario(dados,idx) { 
 
        console.log(dados);

        $('#cadastro-consulta').modal('toggle');
        this.loadingAcao = "Carregando Informações...";
        $('#cadastro-consulta .absolute-loading').show();

        this.dadosAtend = dados;  
        this.INDEX = idx; 
        this.dadosAtendimento(dados);
        if( ( (dados.sn_finalizado) ? dados.sn_finalizado : 'N' ) == 'S' ){
            this.buttonDisabled = true;
        }
        

        this.tituloModal = dados.paciente?.nm_paciente+' [ '+dados.cd_agendamento+' ]';
        var dadosForm = {
            "start": dados.start,
            "dt_start": dados.dt_start,
            "data_start": dados.data_start_end,
            "hr_start":dados.hr_agenda,
            "end": dados.end,
            "dt_end": dados.dt_end,
            "data_end": dados.data_start_end,
            "hr_end": dados.hr_final,
            "resource": dados.cd_agenda,
            "nm_resource": dados.agenda?.nm_agenda,
            "escala": dados.cd_escala,
            "cd_dia": dados.dia_semana,
            "nm_intervalo": dados.intervalo,
            "intervalo": dados.escalas?.intervalo,
            "id": dados.cd_agendamento,
            "situacao": dados.situacao?.cd_situacao,
            "cd_horario": dados.cd_agenda_escala_horario, 
            "tp": (dados.sn_atend_avulso=='S') ? "AVULSO" : "1", 
        }; 
 
        this.clear();
        this.clearPaciente();
        axios.post('/rpclinica/json/recepcao-agendamento', dadosForm)
            .then((res) => {    
                
                console.log(res.data);

                //relação de Tabelas
                this.modalData.conv = res.data.convenios;
                this.modalData.local = res.data.locais;
                this.modalData.tipo = res.data.tipo_atend;
                this.modalData.espec = res.data.especialidades;
                this.modalData.origem = res.data.origem;
                this.modalData.prof_ext = res.data.prof_ext;
                this.modalData.itens =res.data.itens_agenda;
                this.modalData.itens_pendente=res.data.itens_pendente; 
                 
                //Itens Agendamento  
                this.modalAgenda.itensAgendamento =res.data.agendamento?.itens;
                this.modalAgenda.itensGuia = res.data.agendamento?.guia;
                this.modalAgenda.cd_agendamento = res.data.agendamento?.cd_agendamento; 
                this.modalAgenda.sn_finalizado = res.data.agendamento?.sn_finalizado; 

                //Paciente
                this.modalPaciente.cd_paciente= res.data.agendamento?.cd_paciente; 
                this.modalPaciente.cd_agendamento= res.data.agendamento?.paciente.cd_agendamento;  
                this.modalPaciente.nm_paciente= res.data.agendamento?.paciente?.nm_paciente; 
                this.modalPaciente.profissao= res.data.agendamento?.paciente.profissao; 
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
                this.modalPaciente.sexo= res.data.agendamento?.paciente.sexo; 
                this.modalPaciente.estado_civil= res.data.agendamento?.paciente.estado_civil; 
                this.modalPaciente.cd_categoria= res.data.agendamento?.paciente.cd_categoria; 
                this.modalPaciente.uf= res.data.agendamento?.paciente.uf; 
                this.modalPaciente.vip = res.data.agendamento?.paciente.vip; 
                if(res.data.agendamento?.paciente.idade){
                    $('#dadosIdade').html('&nbsp;&nbsp;((&nbsp;&nbsp;'+res.data.agendamento?.paciente.idade+'&nbsp;&nbsp;))');
                    console.log('Passou aqui1');
                }else{
                    $('#dadosIdade').html('');
                    console.log('Passou aqui2');
                }
                
                
            })
            .catch((err) => {  
                toastr['error'](err.response.data.message)
            })
            .finally(() => {  
                //Atendimento
                $('#agendamento-local').val(this.modalAtendimentos.cd_local).trigger('change');
                $('#agendamento-tipo').val(this.modalAtendimentos.cd_tipo).trigger('change');
                $('#agendamento-especialidade').val(this.modalAtendimentos.cd_especialidade).trigger('change');
                $('#agendamento-carater').val(this.modalAtendimentos.carater).trigger('change');
                $('#agendamento-origem').val(this.modalAtendimentos.cd_origem).trigger('change');
                $('#agendamento-prof-ext').val(this.modalAtendimentos.cd_prof_solicitante).trigger('change');
                $('#agendamento-convenio').val(this.modalAtendimentos.cd_convenio).trigger('change');
                //Paciente
                $('#pac-sexo').val(this.modalPaciente.sexo).trigger('change');
                $('#pac-estado_civil').val(this.modalPaciente.estado_civil).trigger('change');
                $('#pac-convenio').val(this.modalPaciente.cd_categoria).trigger('change');
                $('#pac-uf').val(this.modalPaciente.uf).trigger('change');

                if(this.modalPaciente.vip == 'S'){
                    $('#check-pac-vip span').addClass('checked'); 
                    $('#check-pac-vip input').prop('checked', true);
                }

                $('#cadastro-consulta .absolute-loading').hide(); 
 
            });
 
    },

    atualizarPaciente() {
 
        this.loadingAcao = "Atualizando Paciente..."; 
        $('#cadastro-consulta .absolute-loading').show();
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

            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => $('#cadastro-consulta .absolute-loading').hide());
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

    cadastrarGuia(){

        this.buttonSalvarGuia = this.buttonSalvando;
        this.loadingAcao = "Cadastrando Guia...";
        $('#cadastro-consulta .absolute-loading').show();
        var form = new FormData(document.querySelector('#form-recepcao-guia'));   
        axios.post(`/rpclinica/json/recepcao-store-guia/${this.dadosAtend.cd_agendamento}`, form)
        .then((res) => {   
            this.modalData.itens_pendente = null;
            this.modalAgenda.itensGuia = null;
            this.modalAgenda.itensAgendamento = null;
            document.getElementById("form-recepcao-guia").reset();  
            $('#guia-tipo').val(null).trigger('change');
            $('#guia-situacao').val(null).trigger('change');
            $('#guia-item').val([]).trigger('change');
            this.dadosGuiaItens(this.dadosAtend.cd_agendamento); 

        })
        .catch((err) => {  
            toastr['error'](err.response.data.message)
        })
        .finally(() => {
            $('#cadastro-consulta .absolute-loading').hide(); 
            this.buttonSalvarGuia = this.tempSalvar; 
        }); 
    },

    statusGuia(situacao,dados){
        if(situacao=='excluir'){
            this.loadingAcao = "Excluindo Guia...";
        }else{
            this.loadingAcao = "Alterando status Guia...";
        } 
        $('#cadastro-consulta .absolute-loading').show();
        dados.situacao=situacao;
        axios.post(`/rpclinica/json/recepcao-update-guia`, dados)
        .then((res) => {    
            if(situacao=='excluir'){
                toastr['success']("Excluido com sucesso"); 
            }else{
                toastr['success']("Alterado com sucesso"); 
            }   
            this.dadosGuiaItens(dados.cd_agendamento);
  
        })
        .catch((err) =>  toastr['error'](err.response.data.message))
        .finally(() => {
            $('#cadastro-consulta .absolute-loading').hide(); 
        }); 
 
    },

    cadastrarItem(){
        this.buttonSalvarItem = this.buttonSalvando;
        this.loadingAcao = "Cadastrando Item...";
        $('#cadastro-consulta .absolute-loading').show();
        let form = new FormData(document.querySelector('#form-item')); 
        axios.post(`/rpclinica/json/agendamento-item/${this.modalAgenda.cd_agendamento}`, form)
        .then((res) => {  
            console.log(res.data);
            this.modalData.itens_pendente = null;
            this.modalAgenda.itensGuia = null;
            this.modalAgenda.itensAgendamento = null;

            $('#cadastro-consulta select#cod_exme_item').val(null).trigger('change');
            document.getElementById("form-item").reset();
            this.dadosGuiaItens(this.modalAgenda.cd_agendamento);
            toastr['success'](res.data.message); 

        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => {
            $('#cadastro-consulta .absolute-loading').hide();
            this.buttonPesqAvanc = false; 
            this.buttonSalvarItem = this.tempSalvar;
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
                    this.dadosGuiaItens(this.modalAgenda.cd_agendamento);
                })
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => {  
                    $('#cadastro-consulta .absolute-loading').hide();
                    
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
            default:
                return "<span><span aria-hidden='true' class='icon-share-alt'></span> &nbsp;&nbsp;Alterar Situações &nbsp;&nbsp;&nbsp;<span class='caret'></span> </span>";
        }
    },
 
    storeAgendamento() {
        
        this.buttonGerarAtend = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Gerando Atendimento... "; 
        this.buttonDisabled = true; 
        axios.post(`/rpclinica/recepcao-store-atendimento/${this.modalAtendimentos.cd_agendamento}/${this.modalAtendimentos.cd_paciente}`, this.modalAtendimentos)
        .then((res) => {    
            toastr['success']("Atendimento gerado com sucesso!",'Codigo: '+this.modalAtendimentos.cd_agendamento);  
            this.horarios[this.INDEX] = res.data.retorno; 
            this.dadosAtendimento(this.horarios[this.INDEX]);

        })
        .catch((err) => {   
            toastr['error'](err.response.data.errors)
        })
        .finally(() => {   
            this.buttonGerarAtend = this.tempGerarAtend; 
            this.buttonDisabled = false;
            
        });
    },  

    storeOrigem() {
        let id = null;  
        var form = new FormData(document.querySelector('#form-Origem'));   
        axios.post(`/rpclinica/store-origem`, form)
        .then((res) => {    
            this.modalData.origem =  res.data.retorno; 
            id = res.data.item.cd_origem;  
            toastr['success']("Origem cadastrada com sucesso!");  
            document.getElementById("form-Origem").reset();  
        })
        .catch((err) => {  
            toastr['error'](err.response.data.errors)
        })
        .finally(() => {   
            $('#form-Agenda select#atendimento-origem').val(id).trigger('change');
            
        });
    },

    storeProfExt() {
        let id = null;  
        var form = new FormData(document.querySelector('#form-ProfExt'));   
        axios.post(`/rpclinica/store-profissional-externo`, form)
        .then((res) => {    
            this.modalData.prof_ext =  res.data.retorno; 
            id = res.data.item.cd_profissional_externo;  
            toastr['success']("Profissional cadastrado com sucesso!");  
            document.getElementById("form-ProfExt").reset();  
        })
        .catch((err) => {  
            toastr['error'](err.response.data.errors)
        })
        .finally(() => {   
            $('#form-Agenda select#agenda-prof-ext').val(id).trigger('change');
            
        });
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
        this.itensAgendamento = null;
        this.itensGuia = null;
    },

    clearPaciente(){
        this.modalPaciente.cd_paciente= null; this.modalPaciente.cd_agendamento= null; this.modalPaciente.nm_paciente= null; this.modalPaciente.dt_nasc= null; 
        this.modalPaciente.rg= null;this.modalPaciente.cpf= null; this.modalPaciente.cartao_sus= null; this.modalPaciente.nome_social= null; 
        this.modalPaciente.nm_responsavel= null; this.modalPaciente.cpf_responsavel= null; this.modalPaciente.nm_mae= null; this.modalPaciente.nm_pai= null; 
        this.modalPaciente.cartao= null; this.modalPaciente.dt_validade= null; this.modalPaciente.fone= null; this.modalPaciente.celular= null; 
        this.modalPaciente.email= null; this.modalPaciente.cep= null; this.modalPaciente.logradouro= null; this.modalPaciente.numero= null;
        this.modalPaciente.complemento= null; this.modalPaciente.nm_bairro= null; this.modalPaciente.cidade= null; 
        this.modalPaciente.sexo= null; this.modalPaciente.estado_civil= null; this.modalPaciente.cd_categoria= null; this.modalPaciente.uf= null; 
    },


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
