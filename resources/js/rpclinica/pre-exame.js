import axios from 'axios';
import moment from 'moment';

Alpine.data('app', () => ({ 
    loading: false,
    loadingModal: false,
    relacaoAtend: null,
    messageDanger: null,
    INDEX: null,
    INDEX2: null,
    CD_ITEM: null,
    DADOS_EXAME:null,
    DADOS_ATEND:null,
    AGENDAMENTO: null,
    PROFISSIONAL: null,
    NOME_MODAL: null,
    EXAME_MODAL: null,
    AUTO_REFRACAO:{
        dt_exame: null,
        dt_liberacao: null,
        dp : null,
        receita_dinamica: null,
        od_de_dinamica: null,
        od_dc_dinamica: null,
        od_eixo_dinamica: null,
        od_reflexo_dinamica: null,
        oe_de_dinamica: null,
        oe_dc_dinamica: null,
        oe_eixo_dinamica: null,
        oe_reflexo_dinamica : null,
        receita_estatica: null,
        od_de_estatica: null,
        od_dc_estatica: null,
        od_eixo_estatica: null,
        od_reflexo_estatica: null,
        oe_de_estatica: null,
        oe_dc_estatica: null,
        oe_eixo_estatica: null,
        oe_reflexo_estatica: null,
        comentario: null,
    }, 
    CERATOMETRIA:{
        dt_exame: null,
        dt_liberacao: null,
        od_curva1_ceratometria : null,
        od_curva1_milimetros: null,
        od_eixo1_ceratometria: null,
        od_curva2_ceratometria: null,
        od_curva2_milimetros: null,
        od_eixo2_ceratometria: null,
        od_media_ceratometria: null,
        od_media_milimetros: null,
        od_cilindro_neg: null,
        od_eixo_neg: null,
        od_cilindro_pos: null,
        od_eixo_pos: null,
        oe_curva1_ceratometria: null,
        oe_curva1_milimetros: null,
        oe_eixo1_ceratometria: null,
        oe_curva2_ceratometria: null,
        oe_curva2_milimetros: null,
        oe_eixo2_ceratometria: null,
        oe_media_ceratometria: null,
        oe_media_milimetros: null,
        oe_cilindro_neg: null,
        oe_eixo_neg: null,
        oe_cilindro_pos: null,
        oe_eixo_pos: null,
        obs: null,
    },
    CERATOMETRIA_COMP:{
        array_img:[]
    },
    EXAME:{
        array_img:[]
    },
    swalWithBootstrapButtons : Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success swal-button",
          cancelButton: "btn btn-danger swal-button",
          input: "form-control"
        },
        buttonsStyling: false
    }),

    buttonSalvarExaAnot: ' <i  class="fa fa-check"></i> Salvar ',
    buttonSalvarExaImg: ' <i  class="fa fa-check"></i> Salvar ',
    buttonSalvarAutoRef: ' <i  class="fa fa-check"></i> Salvar ',
    buttonSalvarCerat: ' <i  class="fa fa-check"></i> Salvar ',
    buttonSalvarCeratComp: ' <i  class="fa fa-check"></i> Salvar ',
    tempSalvar: ' <i  class="fa fa-check"></i> Salvar ',

    init() {

        $('#data-input').val(moment().format('YYYY-MM-DD'));
        $('#calendar').datepicker('setDate', 'YYYY-MM-DD');
        this.getAtendimentos();

        $('#calendar').on('changeDate', () => {
            $('#data-input').val(
                $('#calendar').datepicker('getFormattedDate')
            );

            this.getAtendimentos()
        });

        $('#form-horario select').on('select2:select', () => {
            this.getAtendimentos()
        });
 
    },

    getAtendimentos() {

        this.loading = true;
        let form = new FormData(document.querySelector('#form-horario'));
        axios.post('/rpclinica/json/pre-exame-lista', form)
        .then((res) => { 
            this.relacaoAtend = res.data.retorno; 
            console.log(res.data.retorno); 
            })
        .catch((err) => {
            this.messageDanger = err.response.data.message; 
        })
        .finally(() => this.loading = false);

    }, 
    clickAtendimento(horario) {
        if (horario.situacao == 'cancelado' || horario.situacao == 'bloqueado' || horario.situacao == 'livre') return;
        if(horario.sn_prontuario=='N'){
            horario.nm_paciente = horario.paciente.nm_paciente;
            console.log(horario);
            this.clickPesquisaPac(horario);
        }else{
            this.modalData.horario = horario;
            if(horario.paciente?.vip=='S'){ this.viewVip=true; }else{ this.viewVip=false; }
            $('#cadastro-consulta').modal('toggle');
        } 
    },

    getAutoRefracao(dados,idx,IdAgendamento,IdProfissional) {
        this.AGENDAMENTO = IdAgendamento;
        this.PROFISSIONAL = IdProfissional;
        this.INDEX = idx;
        this.AUTO_REFRACAO = dados.auto_refracao
        

        if(this.AUTO_REFRACAO.receita_dinamica == '1'){
            $('#label_receita_dinamica span').addClass('checked'); 
            $('#label_receita_dinamica input').prop('checked', true);
        }else{  
            $('#label_receita_dinamica span').removeClass('checked');
            $('#label_receita_dinamica input').prop('checked', false);
        }
        if(this.AUTO_REFRACAO.receita_estatica == '1'){
            $('#label_receita_estatica span').addClass('checked'); 
            $('#label_receita_estatica input').prop('checked', true);
        }else{  
            $('#label_receita_estatica span').removeClass('checked');
            $('#label_receita_estatica input').prop('checked', false);
        }
    }, 
    storeAutoRefracao() { 

        this.buttonSalvarAutoRef = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... "; 
        let form = new FormData(document.querySelector('#form_AUTO_REFRACAO'));  
        let errors = []; 
        const numericFields = [
            'od_dc_dinamica', 'od_dc_estatica', 'od_de_dinamica', 'od_de_estatica',
            'od_eixo_dinamica', 'od_eixo_estatica', 'od_reflexo_dinamica', 'od_reflexo_estatica',
            'oe_dc_estatica', 'oe_de_dinamica', 'oe_de_estatica', 'oe_eixo_dinamica', 'oe_eixo_estatica',
            'oe_reflexo_dinamica', 'oe_reflexo_estatica', 'oe_dc_dinamica', 'dp',
        ];

        numericFields.forEach(field => {
            let value = form.get(field);
            if (value) { 
                value = value.replace('.', '');
                value = value.replace(',', '.');
                form.set(field, value);    
                
            }
        });
         
        if (!form.get('cd_profissional') || isNaN(form.get('cd_profissional'))) {
            errors.push('Profissional code is invalid.');
        }
  
        const dateFields = ['dt_exame', 'dt_liberacao'];
        dateFields.forEach(field => {
            const dateValue = form.get(field);
            if (dateValue && isNaN(Date.parse(dateValue))) {
                errors.push(`Invalid date for ${field}.`);
            }
        }); 
        form.append('cd_profissional', this.PROFISSIONAL)
        axios.post(`/rpclinica/store-oftalmo-auto-refracao/${this.AGENDAMENTO}`, form)
            .then((res) => {  
                this.relacaoAtend[this.INDEX]['auto_refracao'] = res.data.retorno 
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {  
                this.buttonSalvarAutoRef = this.tempSalvar;
            });
    },
  
    getCeratometria(dados,idx,IdAgendamento,IdProfissional) {
        this.AGENDAMENTO = IdAgendamento;
        this.PROFISSIONAL = IdProfissional;
        this.INDEX = idx;
        this.CERATOMETRIA = dados.ceratometria
    },
    storeCeratometria() { 
        this.buttonSalvarCerat = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_CERATOMETRIA'));  
        form.append('cd_profissional', this.PROFISSIONAL);
        let errors = [];
 
        const numericFields = [
            'od_curva1_ceratometria',
            'od_curva1_milimetros',
            'od_eixo1_ceratometria',
            'od_curva2_ceratometria',
            'od_curva2_milimetros',
            'od_eixo2_ceratometria',
            'od_media_ceratometria',
            'od_media_milimetros',
            'od_cilindro_neg',
            'od_eixo_neg',
            'od_cilindro_pos',
            'od_eixo_pos',
            'oe_curva1_ceratometria',
            'oe_curva1_milimetros',
            'oe_eixo1_ceratometria',
            'oe_curva2_ceratometria',
            'oe_curva2_milimetros',
            'oe_eixo2_ceratometria',
            'oe_media_ceratometria',
            'oe_media_milimetros',
            'oe_cilindro_neg',
            'oe_eixo_neg',
            'oe_cilindro_pos',
            'oe_eixo_pos',
        ];

        numericFields.forEach(field => {
            let value = form.get(field);
            if (value) { 
                value = value.replace('.', '');
                value = value.replace(',', '.');
                form.set(field, value);  // Update the value in the form data
            }
        });
 
        if (!form.get('cd_profissional') || isNaN(form.get('cd_profissional'))) {
            errors.push('Profissional code is invalid.');
        }
 
        const dateFields = ['dt_exame']; // i removed dt_liberacao
        dateFields.forEach(field => {
            const dateValue = form.get(field);
            if (dateValue && isNaN(Date.parse(dateValue))) {
                errors.push(`Invalid date for ${field}.`);
            }
        });
 
        if (errors.length > 0) {
            toastr['error'](errors.join('<br>'), 'Validation Error');
            this.buttonDisabled = false;
            this.buttonSalvar = " Salvar ";
            return;
        }
 
 
        axios.post(`/rpclinica/store-oftalmo-ceratometria/${this.AGENDAMENTO}`, form)
            .then((res) => { 
                this.relacaoAtend[this.INDEX]['ceratometria'] = res.data.retorno
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvarCerat = this.tempSalvar;
            });
    },

    getCeratoscopiaComp(idx,IdAgendamento,IdProfissional) { 
        this.AGENDAMENTO = IdAgendamento;
        this.PROFISSIONAL = IdProfissional; 
        this.INDEX = idx;
        this.loadingModal = true;
        axios.get(`/rpclinica/relacao-ceratometria-comp/${IdAgendamento}`)
            .then((res) => { 
                this.CERATOMETRIA_COMP.array_img = res.data.retorno; 
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
               this.loadingModal = false;
            });
    }, 
    storeCeratometriaComp() {
        this.buttonSalvarCeratComp = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... "; 
        let form = new FormData(document.querySelector('#form_CERATOMETRIA_COMP'));
        form.append('cd_profissional', this.PROFISSIONAL)
        axios.post(`/rpclinica/store-oftalmo-ceratometria-comp/${this.AGENDAMENTO}`, form)
            .then((res) => {
                document.getElementById("form_CERATOMETRIA_COMP").reset();
                toastr['success']('Formulario atualizado com sucesso!');
                this.getCeratoscopiaComp(this.INDEX,this.AGENDAMENTO,this.PROFISSIONAL);
                this.relacaoAtend[this.INDEX]['formularios_imagens_ceratometria_comp'] = res.data.retorno;
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false; 
                this.buttonSalvarCeratComp = this.tempSalvar;
            });
    }, 
    deleteCeratometriaComp(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse arquivo?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) { 

                    this.loadingModal = true;
                    axios.delete(`/rpclinica/delete-ceratometria-comp/${CodForm}`)
                        .then((res) => {  
                            toastr['success']('Formulario deletado com sucesso!');
                            this.relacaoAtend[this.INDEX]['formularios_imagens_ceratometria_comp'] = res.data.query
                            this.getCeratoscopiaComp(this.INDEX,this.AGENDAMENTO,this.PROFISSIONAL);
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                            this.loadingModal = false;
                        });
                }
            });
    },   

    getExames(dadosExame,idx2,dadosAtend,idx){
        this.AGENDAMENTO = dadosAtend.cd_agendamento;
        this.DADOS_EXAME = dadosExame;
        this.DADOS_ATEND = dadosAtend;
        this.PROFISSIONAL = dadosAtend.cd_profissional;
        this.CD_ITEM = dadosExame.cd_agendamento_item;
        this.INDEX = idx; 
        this.INDEX2 = idx2; 
        this.NOME_MODAL = (dadosAtend.paciente.nm_paciente) ? dadosAtend.paciente.nm_paciente : ' -- ';
        this.EXAME_MODAL= (dadosExame.exame.nm_exame) ? dadosExame.exame.nm_exame : ' -- ';
        this.loadingModal = true;
        axios.get(`/rpclinica/json/central-laudos/imgs/${this.CD_ITEM}`)
        .then((res) => { 
             this.EXAME.array_img=res.data.retorno
        })
        .catch((err) => {
            toastr['error'](err.response.data.message, 'Erro');
        })
        .finally(() => {
            this.loadingModal = false;
        });

    },

    storeExaAnot(){
        this.buttonSalvarExaAnot = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... "; 
        let form = new FormData(document.querySelector('#form_ANOT_EXAME')); 
        form.append('cd_agendamento_item', this.CD_ITEM) 
        axios.post(`/rpclinica/json/central-laudos/addHist`, form)
        .then((res) => {    
           console.log(this.relacaoAtend[this.INDEX]['itens'][this.INDEX2]['historico']); 
           this.relacaoAtend[this.INDEX]['itens'][this.INDEX2]['historico'] = res.data.retorno
           console.log(this.relacaoAtend[this.INDEX]['itens'][this.INDEX2]['historico']);
           console.log(res.data);
           this.DADOS_EXAME['historico'] = res.data.retorno; 
           document.getElementById("form_ANOT_EXAME").reset();

           toastr['success']('Formulario atualizado com sucesso!');
        })
        .catch((err) => {
            toastr['error'](err.response.data.message, 'Erro');
        })
        .finally(() => { 
            this.buttonSalvarExaAnot= this.tempSalvar;
        });
    }, 
    storeExaImg(){ 
        this.buttonSalvarExaImg = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... "; 
        let form = new FormData(document.querySelector('#form_EXAME_IMG'));
        form.append('cd_agendamento_item', this.CD_ITEM)
        form.append('cd_agendamento', this.AGENDAMENTO)
        axios.post(`/rpclinica/json/central-laudos/img/${this.CD_ITEM}`, form)
            .then((res) => {
                document.getElementById("form_EXAME_IMG").reset();
                toastr['success']('Imagem importada com sucesso!');
                this.getExames(this.DADOS_EXAME,this.INDEX2,this.DADOS_ATEND,this.INDEX); 
            })
            .catch((err) => {
                console.log(err.response.data);
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false; 
                this.buttonSalvarExaImg = this.tempSalvar;
            });
    }, 
    deleteExaImg(CodForm){
 
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse arquivo?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) { 

                    this.loadingModal = true;
                    axios.delete(`/rpclinica/json/central-laudos-delete/img/${CodForm}`)
                        .then((res) => {  
                            toastr['success']('Aquivo deletado com sucesso!'); 
                            this.getExames(this.DADOS_EXAME,this.INDEX2,this.DADOS_ATEND,this.INDEX); 
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                            this.loadingModal = false;
                        });
                }
            });
    }, 

    finalizarPreExame(dados,index){
 
        if(dados.sn_atendimento == 'N'){
           return toastr['error']("Não é possível finalizar um atendimento do tipo agendamento!", 'Erro');
        }
        var texto='';
        if(dados.sn_pre_exame==true){
            texto = "Tem certeza que deseja retornar o paciente para o pré exame?";
        }else{
            texto = "Tem certeza que deseja encaminhar o Paciente para o Consultório?";
        }
        Swal.fire({
            title: 'Confirmação',
            text: texto,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) { 
                    console.log(dados);
                     var IdAgendamento = dados.cd_agendamento
                    this.loadingModal = true;
                    axios.post(`/rpclinica/json/pre-exame-finalizar/${IdAgendamento}`)
                        .then((res) => {  
                            toastr['success']('Atendimento encaminhado com sucesso!'); 
                            this.getAtendimentos(); 
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                            this.loadingModal = false;
                        });
                         
                }
            });
    }, 

    nl2br(str, replaceMode, isXhtml) { 
        var breakTag = (isXhtml) ? '<br />' : '<br>';
        var replaceStr = (replaceMode) ? '$1'+ breakTag : '$1'+ breakTag +'$2';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
    },

    formatValor(valor) {
        if(!valor) { return NULL; }
        return Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(valor).replaceAll("R$ ", "");
    },
    FormatData(data) {
        var dt = data.split(" ")[0];
        var dia  = dt.split("-")[2];
        var mes  = dt.split("-")[1];
        var ano  = dt.split("-")[0];
      
        return ("0"+dia).slice(-2) + '/' + ("0"+mes).slice(-2) + '/' + ano;
      
    }

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
 
 
});

