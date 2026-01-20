import axios from 'axios';
import moment from 'moment';

Alpine.data('appPaciente', () => ({
    
    loadHistorico: false,
    buttonSalvar: "<i class='fa fa-check-square-o'></i> Salvar ",
    buttonSalvarObs: "<i class='fa fa-check'></i> Salvar ",
    pacienteObs: null,
    dadosPaciente: {
        idPaciente: null,
        nome: null,
        data_de_nascimento: null,
        rg: null,
        cpf: null,
        nome_social: null,
        sexo: null,
        estado_civil: null,
        cartao_sus: null,
        nome_da_mae: null,
        nome_do_pai: null,
        nm_responsavel: null,
        cpf_responsavel: null,
        vip: null, 
        convenio: null,
        cartao: null,
        cep: null,
        logradouro: null,
        numero: null,
        complemento: null,
        bairro: null,
        cidade: null,
        uf: null,
        telefone: null,
        celular: null,
        email: null, 
        profissao:null,
    },
    historico :{
        Motivo : '--',
        Historia : '--',
        Medicamentos : '--',
        Alergias : '--',
        Conduta : '--',
        Data : '--',
        Prof : '--',
    },

    init() {
        this.getPaciente();
    },

    getPaciente(){
        this.loadHistorico = true; 
        axios.get(`/rpclinica/json/paciente/${idAgendamento}`)
        .then((res) => { 
            this.dadosPaciente.idPaciente = res.data.agendamento?.paciente?.cd_paciente;
            this.dadosPaciente.nome = res.data.agendamento?.paciente?.nm_paciente;
            this.dadosPaciente.data_de_nascimento = res.data.agendamento?.paciente?.dt_nasc;
            this.dadosPaciente.rg = res.data.agendamento?.paciente?.rg;
            this.dadosPaciente.cpf = res.data.agendamento?.paciente?.cpf;
            this.dadosPaciente.nome_social = res.data.agendamento?.paciente?.nome_social;
            this.dadosPaciente.sexo = res.data.agendamento?.paciente?.sexo;
            this.dadosPaciente.estado_civil = res.data.agendamento?.paciente?.estado_civil;
            this.dadosPaciente.cartao_sus = res.data.agendamento?.paciente?.cartao_sus;
            this.dadosPaciente.nome_da_mae = res.data.agendamento?.paciente?.nm_mae;
            this.dadosPaciente.nome_do_pai = res.data.agendamento?.paciente?.nm_pai;
            this.dadosPaciente.nm_responsavel = res.data.agendamento?.paciente?.nm_responsavel;
            this.dadosPaciente.cpf_responsavel = res.data.agendamento?.paciente?.cpf_responsavel;
            this.dadosPaciente.vip = res.data.agendamento?.paciente?.vip; 
            this.dadosPaciente.convenio = res.data.agendamento?.paciente?.cd_categoria;
            this.dadosPaciente.cartao = res.data.agendamento?.paciente?.cartao;
            this.dadosPaciente.cep = res.data.agendamento?.paciente?.cep;
            this.dadosPaciente.logradouro = res.data.agendamento?.paciente?.logradouro;
            this.dadosPaciente.numero = res.data.agendamento?.paciente?.numero;
            this.dadosPaciente.complemento = res.data.agendamento?.paciente?.complemento;
            this.dadosPaciente.bairro = res.data.agendamento?.paciente?.nm_bairro;
            this.dadosPaciente.cidade = res.data.agendamento?.paciente?.cidade;
            this.dadosPaciente.uf = res.data.agendamento?.paciente?.uf;
            this.dadosPaciente.telefone = res.data.agendamento?.paciente?.fone;
            this.dadosPaciente.celular = res.data.agendamento?.paciente?.celular;
            this.dadosPaciente.email = res.data.agendamento?.paciente?.email; 
            this.dadosPaciente.profissao = res.data.agendamento?.paciente?.profissao; 
            this.pacienteObs = res.data.agendamento?.paciente?.historico_problemas.replace(/<[^>]*>?/gm, ''); 
 
            $('#PacSexo').val(res.data.agendamento.paciente.sexo).trigger('change');
            $('#PacEstado_civil').val(res.data.agendamento.paciente.estado_civil).trigger('change');
            $('#PacVip').val(res.data.agendamento.paciente.vip).trigger('change');
            $('#PacVip').val(res.data.agendamento.paciente.vip).trigger('change');
            $('#PacUf').val(res.data.agendamento.paciente.uf).trigger('change'); 

            /* Historico */

            this.historico.Motivo = (res.data.hist.motivo_consulta) ? res.data.hist.motivo_consulta : ' -- ';
            this.historico.Historia = (res.data.hist.hist_oft) ? res.data.hist.hist_oft : ' -- ';
            this.historico.Medicamentos = (res.data.hist.medicamentos) ? res.data.hist.medicamentos : ' -- ';
            this.historico.Alergias = (res.data.hist.alergias) ?  res.data.hist.alergias : ' -- ';
            this.historico.Conduta = (res.data.hist.conduta) ? res.data.hist.conduta : ' -- ';
            this.historico.Data = (res.data.hist.data_horario) ? res.data.hist.data_horario : ' -- ';
            this.historico.Prof = (res.data.hist.nm_profissional) ? res.data.hist.nm_profissional : ' -- ';
            
        })
        .catch((err) => { 
            toastr['error'](err.response.data.message);
        })
        .finally(() => {
            this.loadHistorico = false
        });
    },

    
    buscarCep(){
        if(!this.dadosPaciente.cep){
            toastr['error']('CEP não Informado!','Erro');
            return false;
        }
        axios.get('https://viacep.com.br/ws/'+this.dadosPaciente.cep.replace(/[^0-9]/g,'')+'/json')

        .then((res) => {
             
            this.dadosPaciente.logradouro = res.data.logradouro;
            this.dadosPaciente.bairro = res.data.bairro;
            this.dadosPaciente.cidade = res.data.localidade;
            $('#PacUf').val(res.data.uf).trigger('change');
        })

    },

    getSalvar(){
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";  
        let form = new FormData(document.querySelector('#form-pac')); 
        axios.post(`/rpclinica/json/paciente/${this.dadosPaciente.idPaciente}`, form)
        .then((res) => {
            toastr['success']('Paciente atualizado com sucesso!');  
        })
        .catch((err) => { 
            toastr['error'](err.response.data.message,'Erro'); 
        })

        .finally(() => {
            this.buttonSalvar = "<i class='fa fa-check-square-o'></i>  Salvar "; 
        });
    },

    getObs(){
        var form = {'obs' : this.pacienteObs};

        console.log(form+' *** '+this.dadosPaciente.idPaciente);
    
   
        axios.post(`/rpclinica/json/paciente-obs/${this.dadosPaciente.idPaciente}`, form)
        .then((res) => {
            
            console.log(res.data);
            toastr['success']('Paciente atualizado com sucesso!');  
        })
        .catch((err) => { 
            toastr['error'](err.response.data.message,'Erro'); 
        })

        .finally(() => {
            this.buttonSalvarObs = "<i class='fa fa-check'></i>  Salvar "; 
        });
        
    }
 
 
}));
 

