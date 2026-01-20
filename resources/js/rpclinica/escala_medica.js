import axios from 'axios';
import moment from 'moment';
var agendamentoDadosMes = 'POPO';

Alpine.data('app', () => ({ 
    //dadosSituacao:[],
    loading: false,
    inicioIcone: true,
    loadingModal: false,
    messageDanger: null, 
    loadingAcao: null,
    //bloqueado: false,
    //ds_bloqueado: '',
    lista: [],
    modalData: { errors: [] },
    carregando: '<i class="fa fa-spinner fa-spin" style="color:#B0B0B0 "></i>',
    //loadCarregandoAgenda: '',
    //headerLivres: ' -- ',
    headerAgendados: ' -- ',
    headerConfirmados: ' -- ',
    headerFinalizados: ' -- ',
    headerPagos: ' -- ', 
     
    iconeLivre: '<span class="glyphicon glyphicon-list-alt" aria-hidden="true"  style="padding-left:2px; padding-right: 10px;  "></span> ',
    modalEscala: {
        cd_escala: null,
        dt_agenda: '',
        hr_agenda: null,
        hr_final: null,
        prof: null,
        qtde: null,
        qtde_inst: null,
        qtde_prof: null,
        qtde_final: null,
        obs: null,
        informativo: null,
        dados: null, 
    },
    modalKey: null,
    //horaFinal: null,
    modalEnvios:{
        lista: [],
        titulo: null,
    }, 
    camposModal: {
        profissional: null,  
        tipo: null,
        local: null
    },
    dados_profissional:{
        profissional: false,
        nome: null,
        whats: null,
        email: null,
        escala: null,
        disponibilidade: null
    },
    data_agenda: null,
    snBloquea: false,
    snExcluir: false,
    snDesbloquea: false,
    checkEscala: [],
 
    buttonSalvarAgendamneto: ' <i  class="fa fa-check"></i> Salvar ',
    tempSalvando: " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ",
    tempSalvar: ' <i  class="fa fa-check"></i> Salvar ',

    init() {
        /*
        $('#calendar').datepicker({
            format: 'dd/mm/yyyy', // Customize the date format
            multidate: true,      // Enable multi-date selection (true for no limit)
            multidateSeparator: ", ", // Separator for the dates in the input field
            setDates: [new Date(2026, 1, 5), new Date(2026, 1, 5)]
        }); 

        $('#calendar').on('click', function() {
            var selectedDates = $('#calendar').datepicker('getDates');
            console.log(selectedDates); // This will be an array of Date objects
        });
        */
             
        $('#data-input').val(moment().format('YYYY-MM-DD'));
        $('#calendar').datepicker('setDate', 'YYYY-MM-DD');

        $('#calendar').on('changeDate', () => {
            
            $('#data-input').val(
                $('#calendar').datepicker('getFormattedDate') 
            );
            this.getAgendamentos()
            
        });
         

        $('#form-Agenda #escala-profissional').on('select2:select', (evt) => { 
            
            if(evt.params.data.id){
                this.modalEscala.prof = evt.params.data.id;
                this.dadosProfissional(evt.params.data.id);  
            }else{
                this.dados_profissional.profissional = false;
                this.dados_profissional.nome = null;
                this.dados_profissional.whats = null;
                this.dados_profissional.email = null;
                this.dados_profissional.escala = '';
                this.dados_profissional.disponibilidade = ''; 
            }
                    
        });
  
        $('#form-horario select').change(() => {
            this.getAgendamentos()
        });

        this.getAgendamentos()
        this.camposModal.profissional = profissionais;
        this.camposModal.local = localidades;
        this.camposModal.tipo = tipo_escala;
    },

    clearModal() {

        this.modalEscala.cd_escala = null
        this.modalEscala.dt_agenda = ''; 
        this.modalEscala.prof = null 
        this.modalEscala.hr_agenda = null
        this.modalEscala.hr_final = null
        this.modalEscala.qtde = null
        this.modalEscala.qtde_inst = null
        this.modalEscala.qtde_prof = null
        this.modalEscala.qtde_final = null
        this.modalEscala.obs = null 
        this.modalEscala.informativo = null 
        this.modalEscala.dados = null 
        this.dados_profissional.profissional = false;
        this.dados_profissional.nome = '';
        this.dados_profissional.whats = '';
        this.dados_profissional.email = '';  
        this.dados_profissional.escala = null; 
        this.dados_profissional.disponibilidade = null;    
       
    },
 
    getAgendamentos() {
        //this.headerLivres = this.carregando;
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
        axios.post(`/rpclinica/json-escala-medica`, form) 
            .then((res) => {

                console.log(res.data);
           
                this.lista = res.data.request.query; 
                //this.headerLivres = res.data.request.header.total;
                this.headerAgendados = res.data.request.header.agendado;
                this.headerConfirmados = res.data.request.header.confirmado;
                this.headerFinalizados = res.data.request.header.finalizado;
                this.headerPagos = res.data.request.header.pago; 
                this.data_agenda = res.data.request.data; 
                if(this.lista==''){
                    this.inicioIcone = true;
                }
                
 
            })
            .catch((err) => {
                this.messageDanger = err.response.data.message;
            })
            .finally(() => {
                this.loading = false;
                console.log('this.inicioIcone '+this.inicioIcone);

            });
    },
     
    getModalManual() { 
        this.snExcluir = false;
        this.modalEscala.dados = null;
        this.clearModal();
        $('#cadastro-agendamento select#escala-profissional').val(null).trigger('change');
        $('#cadastro-agendamento select#escala-local').val(null).trigger('change');
        $('#cadastro-agendamento select#escala-tipo').val(null).trigger('change');
        $('#cadastro-agendamento select#escala-situacao').val(null).trigger('change');
        
        $('#cadastro-agendamento').modal('toggle'); 
 
    },

    getModal(dados, idx) {
         
        this.clearModal();
        $('#cadastro-agendamento').modal('toggle');
        this.loadingModal = true;
        this.modalEscala.dados = dados;
        this.modalKey = idx; 
 
        this.modalEscala.cd_escala = dados.cd_escala_medica;
        this.modalEscala.dt_agenda = dados.dt_escala;
        this.modalEscala.hr_agenda = dados.hri;
        this.modalEscala.hr_final = dados.hrf;
        this.modalEscala.qtde = dados.qtde_escala;
        this.modalEscala.qtde_inst = dados.qtde_localidade;
        this.modalEscala.qtde_prof = dados.qtde_profissional;
        this.modalEscala.qtde_final = dados.qtde_final;
        this.modalEscala.informativo = dados.informativo; 
        this.modalEscala.obs = dados.obs;
        this.modalEscala.prof = dados.cd_profissional;
        this.dadosProfissional(dados.cd_profissional); 
        $('#cadastro-agendamento select#escala-profissional').val(dados.cd_profissional).trigger('change');
        $('#cadastro-agendamento select#escala-local').val(dados.cd_escala_localidade).trigger('change');
        $('#cadastro-agendamento select#escala-tipo').val(dados.cd_escala_tipo).trigger('change');
        $('#cadastro-agendamento select#escala-situacao').val(dados.situacao).trigger('change'); 
       
        this.loadingModal = false;      
             
    },

    dadosProfissional(codigo=null) { 

        this.dados_profissional.escala = '';
        this.dados_profissional.disponibilidade = ''; 

        if(!codigo)
            codigo=this.modalEscala.prof;
        if(!codigo){
            return false 
        }
             
        axios.get(`/rpclinica/json-escala-medica-prof/${codigo}?data=${this.modalEscala.dt_agenda}`) 
            .then((res) => {

                console.log(res.data); 
                if(res.data.profissional.cd_profissional){
                    this.dados_profissional.profissional = true;
                    this.dados_profissional.nome = res.data.profissional.nm_profissional;
                    this.dados_profissional.whats = res.data.profissional.whatsapp;
                    this.dados_profissional.email = res.data.profissional.email; 
                    this.dados_profissional.escala = res.data.request.escalas;
                    this.dados_profissional.disponibilidade = res.data.request.disponibilidade; 
                }else{
                    this.dados_profissional.profissional = false;
                    this.dados_profissional.nome = null;
                    this.dados_profissional.whats = null;
                    this.dados_profissional.email = null;
                    this.dados_profissional.escala = null;
                    this.dados_profissional.disponibilidade = null; 
                }

                 
            })
            .catch((err) => {
                this.messageDanger = err.response.data.message;
            })
            .finally(() => {
                this.loading = false;  
            });
    },
    
    storeEscala() {
        this.checkEscala=[];
        this.modalData.errors = [];
        this.loadingAcao = "Agendando...";
        $('#cadastro-agendamento .absolute-loading').show(); 
        this.buttonSalvarAgendamneto = this.tempSalvando; 
          
        let form = new FormData(document.querySelector('#form-Agenda'));
        //form.append('cd_agendamento', this.modalAgenda.cd_agendamento);
        if(this.modalEscala.cd_escala){
            var url = `/rpclinica/json-escala-medica-update/${this.modalEscala.cd_escala}`;
        }else{
            var url = `/rpclinica/json-escala-medica-store`;
        }
        axios.post(url, form)
            .then((res) => {
                $('#cadastro-agendamento select#escala-profissional').val(null).trigger('change');
                $('#cadastro-agendamento select#escala-local').val(null).trigger('change');
                $('#cadastro-agendamento select#escala-tipo').val(null).trigger('change');
                $('#cadastro-agendamento select#escala-situacao').val(null).trigger('change');
                this.clearModal();
                console.log(res.data)  
                $('#data-input').val(res.data.dt_agenda);
                $('#calendar').datepicker('setDate', res.data.dt_agenda); 
                $('#cadastro-agendamento').modal('hide');
                this.getAgendamentos();

            })
            .catch((err) => {
                Object.values(err.response.data.errors).forEach((errors) => {
                    this.modalData.errors = this.modalData.errors.concat(errors);
                    if(err.response.data.dados[0]){
                        this.checkEscala=err.response.data.dados[0];
                        console.log(this.checkEscala);
                        console.log(this.checkEscala.dados.length);
                    }
                })
            })
            .finally(() => {

                this.buttonSalvarAgendamneto = this.tempSalvar;
                $('#cadastro-agendamento .absolute-loading').hide();
                 
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

       /*
        $('#calendar').datepicker({
            format: 'dd/mm/yyyy', // Customize the date format
            multidate: true,      // Enable multi-date selection (true for no limit)
            multidateSeparator: ", ", // Separator for the dates in the input field
            setDates: [new Date(2026, 1, 5), new Date(2026, 1, 5)]
        }); 

        $('#calendar').on('click', function() {
            var selectedDates = $('#calendar').datepicker('getDates');
            console.log(selectedDates); // This will be an array of Date objects
        });
        */

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

