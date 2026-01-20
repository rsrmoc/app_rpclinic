import axios from 'axios';
import moment from 'moment';

Alpine.data('appOftalmologia', () => ({
    
    telaAtiva: null,

    iconHistry: "<i class='fa fa-list'></i>",
    conteudo: '<div class="col-md-7"><div style="text-align: center;"> <img src="/assets/images/oftalmo.png" > </div> </div>',
    conteudoCarregando: '<div class="col-md-7"><div style="text-align: center;"> <img style="height: 80px;margin-top: 100px;" src="/assets/images/carregandoFormulario.gif" > </div> </div>',
    conteudoCarregandoModal: '<div class="col-md-12"><div style="text-align: center;"> <img style="height: 80px;margin-top: 100px;margin-bottom: 60px;" src="/assets/images/carregandoFormulario.gif" > </div> </div>',
    loading: false,
    buttonSalvar: " <i class='fa fa-check'></i>  Salvar ",
    buttonDisabled: false,
    modalTitulo: "",
    modalConteudo: "",

    AUTO_REFRACAO:{
        formData: null,
        history: []
    },
    CERATOMETRIA:{
        formData: null,
        history: []
    },
    CERATOMETRIA_COMP:{
        formData: null,
        history: []
    },
    ANAMNESE:{
        formData: null,
        history: []
    },
    FUNDOSCOPIA:{
        formData: null,
        formDataImg: null,
        history: []
    }, 
    TONOMETRIA_APLANACAO:{
        formData: null,
        formDataImg: null,
        history: []
    },
    REFRACAO:{
        formData: null,
        formDataImg: null,
        history: []
    },
    RECEITA_OCULOS:{
        formData: null,
        formDataImg: null,
        history: []
    },
    RECEITA:{
        formData: null, 
        history: []
    },
    ATESTADO:{
        formData: null, 
        history: []
    },
    RESERVA:{
        formData: null, 
        opme: null,
        history: []
    },
    HISTORICO:{
        formData: null, 
        opme: null,
        history: []
    },
    init() {

    },
    /* CARREGA FORMULARIOS */
    forms(form) {
        this.telaAtiva = form
        if (!idAgendamento) {
            toastr['Atenção']('Atendimento não Informado');
        } else {
            this.conteudo = this.conteudoCarregando;
            axios.get(`/rpclinica/consultorio-formularios-oftalmo/${idAgendamento}/${form}`)
                .then((response) => {
                    if( form == 'AUTO_REFRACAO') { this.indexAutoRefracao(response.data) } 
                    if( form == 'CERATOMETRIA') { this.indexCeratometria(response.data) }
                    if( form == 'CERATOSCOPIA_COMP') { this.indexCeratometriaComp(response.data) }
                    if( form == 'ANAMNESE') { this.indexAnamnese(response.data) }
                    if( form == 'FUNDOSCOPIA') { this.indexFundoscopia(response.data) }
                    if( form == 'TONOMETRIA_APLANACAO') { this.indexTonometriaAplanacao(response.data) }
                    if( form == 'REFRACAO') { this.indexRefracao(response.data) }
                    if( form == 'RECEITA_OCULOS') { this.indexReceitaOculos(response.data) }
                    if( form == 'RECEITAS') { this.indexReceitas(response.data) }
                    if( form == 'ATESTADOS') { this.indexAtestado(response.data) }
                    if( form == 'RESERVA_CIRURGIA') { this.indexReservaCirurgia(response.data) }
                    if( form == 'EXAME') { this.indexHistoricoExame(response.data) }
                    
                    
                })
                .catch((err) => {
                    toastr['error'](err.response.data.message, 'Erro');
                })
                .finally(() => this.loading = false);
        }
    },

    /*###########################################Pre-Exame Oftalmologia#####################################################*/

    /* AutoRefracao */
    indexAutoRefracao(data) {
        this.AUTO_REFRACAO.formData = data.retorno
        this.AUTO_REFRACAO.history = data.historico
        if(this.AUTO_REFRACAO.formData.receita_dinamica == '1'){
            $('#label_receita_dinamica span').addClass('checked'); 
            $('#label_receita_dinamica input').prop('checked', true);
        }else{ 
            $('#label_receita_dinamica span').removeClass('checked');
            $('#label_receita_dinamica input').prop('checked', false);
        }
        if(this.AUTO_REFRACAO.formData.receita_estatica == '1'){
            $('#label_receita_estatica span').addClass('checked'); 
            $('#label_receita_estatica input').prop('checked', true);
        }else{ 
            $('#label_receita_estatica span').removeClass('checked');
            $('#label_receita_estatica input').prop('checked', false);
        }

    }, 
    storeAutoRefracao() { 

        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_AUTO_REFRACAO'));
        console.log("test: " + form.values())

        // Validation rules
        let errors = [];

        // Normalize numeric fields (replace commas with dots)
        const numericFields = [
            'od_dc_dinamica', 'od_dc_estatica', 'od_de_dinamica', 'od_de_estatica',
            'od_eixo_dinamica', 'od_eixo_estatica', 'od_reflexo_dinamica', 'od_reflexo_estatica',
            'oe_dc_estatica', 'oe_de_dinamica', 'oe_de_estatica', 'oe_eixo_dinamica', 'oe_eixo_estatica',
            'oe_reflexo_dinamica', 'oe_reflexo_estatica', 'oe_dc_dinamica', 'dp',
        ];

        numericFields.forEach(field => {
            let value = form.get(field);
            if (value) {
                // Replace commas with dots to normalize float values
                value = value.replace('.', '');
                value = value.replace(',', '.');
                form.set(field, value);  // Update the value in the form data
                console.log(field + ' +++ ' + value)
                
            }
        });
        
        // Check if 'cd_profissional' is a valid number
        if (!form.get('cd_profissional') || isNaN(form.get('cd_profissional'))) {
            errors.push('Profissional code is invalid.');
        }

     

        // Validate date fields
        const dateFields = ['dt_exame', 'dt_liberacao'];
        dateFields.forEach(field => {
            const dateValue = form.get(field);
            if (dateValue && isNaN(Date.parse(dateValue))) {
                errors.push(`Invalid date for ${field}.`);
            }
        });
 
        // Proceed with form submission if no validation errors
        axios.post(`/rpclinica/store-oftalmo-auto-refracao/${idAgendamento}`, form)
            .then((res) => {
                console.log(res.data);
                this.forms('AUTO_REFRACAO');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    },

    deleteAutoRefracao(cd_agendamento) {

        if(!cd_agendamento){
            toastr['error']('Codigo não informado!', 'Erro');
        }

        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja EXCLUIR esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {
                    console.log(cd_agendamento);
                    document.getElementById('reset-button').click();

                    axios.delete(`/rpclinica/delete-auto-refracao/${cd_agendamento}`)
                        .then((res) => {
                            $('#label_receita_dinamica span').removeClass('checked');
                            $('#label_receita_dinamica input').prop('checked', false);

                            $('#label_receita_estatica span').removeClass('checked');
                            $('#label_receita_estatica input').prop('checked', false);

                            document.getElementById('reset-button').click();
                            this.forms('AUTO_REFRACAO');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });
                }
            });
    }, 
    modalAutoRefracao(CodForm, Form) {
 
        var parametros = {'cd_formulario': Form};
        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-auto-refracao/${CodForm}`, {
            params:  parametros
        })
            .then((res) => {
                this.modalConteudo = res.data;  
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },

    /* CERATOMETRIA */
    indexCeratometria(data) {
        this.CERATOMETRIA.formData = data.retorno
        this.CERATOMETRIA.history = data.historico  
    }, 
    storeCeratometria() { 
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_CERATOMETRIA'));
        console.log("test: " + form.values())

        // Validation rules
        let errors = [];

        // Normalize numeric fields (replace commas with dots)
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
                // Replace commas with dots to normalize float values
                value = value.replace('.', '');
                value = value.replace(',', '.');
                form.set(field, value);  // Update the value in the form data
            }
        });

        // Check if 'cd_profissional' is a valid number
        if (!form.get('cd_profissional') || isNaN(form.get('cd_profissional'))) {
            errors.push('Profissional code is invalid.');
        }

        // Check if 'comentario' is not empty
        // if (!form.get('obs')) {
        //     errors.push('Comentário is required.');
        // }

        // Validate date fields
        const dateFields = ['dt_exame']; // i removed dt_liberacao
        dateFields.forEach(field => {
            const dateValue = form.get(field);
            if (dateValue && isNaN(Date.parse(dateValue))) {
                errors.push(`Invalid date for ${field}.`);
            }
        });

        // Validate numeric fields
        

        // Check for errors before submission
        if (errors.length > 0) {
            toastr['error'](errors.join('<br>'), 'Validation Error');
            this.buttonDisabled = false;
            this.buttonSalvar = " Salvar ";
            return;
        }

        console.log(form)

        // Proceed with form submission if no validation errors
        axios.post(`/rpclinica/store-oftalmo-ceratometria/${idAgendamento}`, form)
            .then((res) => {
                console.log(res.data);
                this.forms('CERATOMETRIA');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    },

    deleteCeratometria(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.delete(`/rpclinica/delete-ceratometria/${CodForm}`)
                        .then((res) => {
                            document.getElementById('reset-button').click();
                            this.forms('CERATOMETRIA');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });
                }
            });
    }, 
    modalCeratometria(CodForm, Form) {

        var parametros = {'cd_formulario': Form};
        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-ceratometria/${CodForm}`, {
            params:  parametros
        })
            .then((res) => {
                this.modalConteudo = res.data; 
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },

    /* CERATOMETRIA COMPUTADORIZADA */
    indexCeratometriaComp(data) {
        this.CERATOMETRIA_COMP.formData = data.retorno
        this.CERATOMETRIA_COMP.history = data.historico   
    }, 
    storeCeratometriaComp() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_CERATOMETRIA_COMP'));
        axios.post(`/rpclinica/store-oftalmo-ceratometria-comp/${idAgendamento}`, form)
            .then((res) => {
                document.getElementById("form_CERATOMETRIA_COMP").reset();
                this.forms('CERATOSCOPIA_COMP');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    }, 
    deleteCeratometriaComp(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {
                    console.log(CodForm);

                    axios.delete(`/rpclinica/delete-ceratometria-comp/${CodForm}`)
                        .then((res) => { 
                            this.forms('CERATOSCOPIA_COMP');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });
                }
            });
    },   
    modalCompletoCeratometriaComp(CodForm, Form) {
        var parametros = {'cd_formulario': Form};
        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-completo-ceratometria-comp/${CodForm}`, {
            params:  parametros
        })
            .then((res) => {
                this.modalConteudo = res.data;
                this.modalTitulo = "Nome do Paciente";
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },

 
    /*###########################################Consulta Oftalmologia#####################################################*/
  
    /* Anamnese Inicial */
    indexAnamnese(data) {
        this.ANAMNESE.formData = data.retorno
        this.ANAMNESE.history = data.historico   
    }, 
    storeAnamnese() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_ANAMNESE'));
        axios.post(`/rpclinica/store-oftalmo-anamnese/${idAgendamento}`, form)
            .then((res) => {
                this.forms('ANAMNESE');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    }, 
    deleteAnamnese(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.delete(`/rpclinica/delete-anamnese/${CodForm}`)
                        .then((res) => {
                            document.getElementById('reset-button').click();
                            this.forms('ANAMNESE');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });
                }
            });
    }, 
    modalAnamnese(CodForm, Form) {
        var parametros = {'cd_formulario': Form};
        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-anamnese/${CodForm}`, {
            params:  parametros
        })
            .then((res) => {
                this.modalConteudo = res.data; 
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },
 
    /* Fundoscopia */
    indexFundoscopia(data) {
        this.FUNDOSCOPIA.formData = data.retorno.oft_fundoscopia
        this.FUNDOSCOPIA.history = data.historico   
        this.FUNDOSCOPIA.formDataImg = data.retorno.array_img
        
        if(this.FUNDOSCOPIA.formData.midriase_od == '1'){
            $('#label_midriase_od span').addClass('checked'); 
            $('#label_midriase_od input').prop('checked', true);
        }else{ 
            $('#label_midriase_od span').removeClass('checked');
            $('#label_midriase_od input').prop('checked', false);
        }
        
        if(this.FUNDOSCOPIA.formData.normal_od == '1'){
            $('#label_normal_od span').addClass('checked'); 
            $('#label_normal_od input').prop('checked', true);
        }else{ 
            $('#label_normal_od span').removeClass('checked');
            $('#label_normal_od input').prop('checked', false);
        }
          
        if(this.FUNDOSCOPIA.formData.midriase_oe == '1'){
            $('#label_midriase_oe span').addClass('checked'); 
            $('#label_midriase_oe input').prop('checked', true);
        }else{ 
            $('#label_midriase_oe span').removeClass('checked');
            $('#label_midriase_oe input').prop('checked', false);
        }
        
        if(this.FUNDOSCOPIA.formData.normal_oe == '1'){
            $('#label_normal_oe span').addClass('checked'); 
            $('#label_normal_oe input').prop('checked', true);
        }else{ 
            $('#label_normal_oe span').removeClass('checked');
            $('#label_normal_oe input').prop('checked', false);
        }

        console.log(this.FUNDOSCOPIA);
    }, 
    storeFundoscopia() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_FUNDOSCOPIA'));
        axios.post(`/rpclinica/store-oftalmo-fundoscopia/${idAgendamento}`, form)
            .then((res) => {
                this.forms('FUNDOSCOPIA');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    }, 
    storeFundoscopiaImg() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_FUNDOSCOPIA_IMG'));
        axios.post(`/rpclinica/store-oftalmo-fundoscopia-img/${idAgendamento}`, form)
            .then((res) => {
                document.getElementById("form_FUNDOSCOPIA_IMG").reset();
                this.forms('FUNDOSCOPIA');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    }, 
    deleteFundoscopia(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reset-button').click();

                    axios.delete(`/rpclinica/delete-fundoscopia/${CodForm}`)
                        .then((res) => {
                            document.getElementById('reset-button').click();

                            $('#label_midriase_od span').removeClass('checked');
                            $('#label_midriase_od input').prop('checked', false);
                        
                            $('#label_normal_od span').removeClass('checked');
                            $('#label_normal_od input').prop('checked', false);
                         
                            $('#label_midriase_oe span').removeClass('checked');
                            $('#label_midriase_oe input').prop('checked', false);
                        
                            $('#label_normal_oe span').removeClass('checked');
                            $('#label_normal_oe input').prop('checked', false);

                            this.forms('FUNDOSCOPIA');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });
                }
            });
    }, 
    deleteFundoscopiaImg(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse ddformulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) { 

                    axios.delete(`/rpclinica/delete-fundoscopia-img/${CodForm}`)
                        .then((res) => { 
                            this.forms('FUNDOSCOPIA');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });

                }
            });
    }, 
    modalFundoscopia(CodForm, Form) {
        var parametros = {'cd_formulario': Form}; 
        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-fundoscopia/${CodForm}`, {
            params:  parametros
        })
            .then((res) => {
                this.modalConteudo = res.data; 
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },


    /* Tonometria Aplanacao */
    indexTonometriaAplanacao(data) {
        console.log(data.retorno);
        this.TONOMETRIA_APLANACAO.formData = data.retorno
        this.TONOMETRIA_APLANACAO.history = data.historico  
        if(this.TONOMETRIA_APLANACAO.formData.cd_equipamento){
            $('select#cd_equipamento').val(this.TONOMETRIA_APLANACAO.formData.cd_equipamento).trigger('change');
        }
        console.log(this.TONOMETRIA_APLANACAO);
    }, 
    storeTonometriaAplanacao() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_TONOMETRIA_APLANACAO')); 

        // Validation rules
        let errors = [];

        // Normalize numeric fields (replace commas with dots)
        const numericFields = [
            'pressao_od', 'pressao_oe'
        ];

        numericFields.forEach(field => {
            let value = form.get(field);
            if (value) {
                // Replace commas with dots to normalize float values
                value = value.replace('.', '');
                value = value.replace(',', '.');
                form.set(field, value);  // Update the value in the form data
            }
        });

        // Check if 'cd_profissional' is a valid number
        if (!form.get('cd_profissional') || isNaN(form.get('cd_profissional'))) {
            errors.push('Profissional code is invalid.');
        }


        // Validate date fields
        const dateFields = ['dt_exame'];
        dateFields.forEach(field => {
            const dateValue = form.get(field);
            if (dateValue && isNaN(Date.parse(dateValue))) {
                errors.push(`Invalid date for ${field}.`);
            }
        });

         

        console.log(form)
        axios.post(`/rpclinica/store-oftalmo-tonomeria-aplanacao/${idAgendamento}`, form)
            .then((res) => {
                document.getElementById('reset-button').click();
                this.forms('TONOMETRIA_APLANACAO');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    }, 
    deleteTonometriaAplanacao(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.delete(`/rpclinica/delete-tonomeria-aplanacao/${CodForm}`)
                        .then((res) => {
                            document.getElementById('reset-button').click(); 
                            $('select#cd_equipamento').val(null).trigger('change');
                            this.forms('TONOMETRIA_APLANACAO');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });
                }
            });
    }, 
    modalTonometriaAplanacao(CodForm, Form) {
        var parametros = {'cd_formulario': Form};  
        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-tonomeria-aplanacao/${CodForm}`, {
            params:  parametros
        })
            .then((res) => {
                this.modalConteudo = res.data;
                this.modalTitulo = "Nome do Paciente";
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },


    /* Refracao */ 
    indexRefracao(data) {
        console.log(data.retorno);
        this.REFRACAO.formData = data.retorno
        this.REFRACAO.history = data.historico  

        if(this.REFRACAO.formData.re_receita == '1'){
            $('#label_re_receita span').addClass('checked'); 
            $('#label_re_receita input').prop('checked', true);
        }else{ 
            $('#label_re_receita span').removeClass('checked');
            $('#label_re_receita input').prop('checked', false);
        }
        if(this.REFRACAO.formData.rd_receita == '1'){
            $('#label_rd_receita span').addClass('checked'); 
            $('#label_rd_receita input').prop('checked', true);
        }else{ 
            $('#label_rd_receita span').removeClass('checked');
            $('#label_rd_receita input').prop('checked', false);
        }

        $('select#are_oe_av').val(this.REFRACAO.formData.are_oe_av).trigger('change');
        $('select#are_od_av').val(this.REFRACAO.formData.are_od_av).trigger('change');
        $('select#ard_oe_add_av').val(this.REFRACAO.formData.ard_oe_add_av).trigger('change');
        $('select#ard_oe_av').val(this.REFRACAO.formData.ard_oe_av).trigger('change'); 
        $('select#ard_od_add_av').val(this.REFRACAO.formData.ard_od_add_av).trigger('change');
        $('select#ard_od_av').val(this.REFRACAO.formData.ard_od_av).trigger('change'); 

        console.log(this.TONOMETRIA_APLANACAO);
    }, 
    storeRefracao() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_REFRACAO'));


        let errors = [];

        // Normalize numeric fields (replace commas with dots)
        const numericFields = [
            'ard_od_de',
            'ard_od_dc',
            'ard_od_eixo',
            'ard_od_add',
            'ard_oe_de',
            'ard_oe_dc',
            'ard_oe_eixo',
            'ard_oe_add',
            'are_od_de',
            'are_od_dc',
            'are_od_eixo',
            'are_oe_de',
            'are_oe_dc',
            'are_oe_eixo',
            'dp'
        ];

        numericFields.forEach(field => {
            let value = form.get(field);
            if (value) {
                // Replace commas with dots to normalize float values
                value = value.replace('.', '');
                value = value.replace(',', '.');
                form.set(field, value);  // Update the value in the form data
            }
        });

        // Check if 'cd_profissional' is a valid number
        if (!form.get('cd_profissional') || isNaN(form.get('cd_profissional'))) {
            errors.push('Profissional code is invalid.');
        }

        // Check if 'comentario' is not empty
        // if (!form.get('comentario')) {
        //     errors.push('Comentário is required.');
        // }

        // Validate date fields
        const dateFields = ['dt_exame'];
        dateFields.forEach(field => {
            const dateValue = form.get(field);
            if (dateValue && isNaN(Date.parse(dateValue))) {
                errors.push(`Invalid date for ${field}.`);
            }
        });

        // Validate numeric fields
        

        // Check for errors before submission
        if (errors.length > 0) {
            toastr['error'](errors.join('<br>'), 'Validation Error');
            this.buttonDisabled = false;
            this.buttonSalvar = " Salvar ";
            return;
        }

        axios.post(`/rpclinica/store-oftalmo-refracao/${idAgendamento}`, form)
            .then((res) => {
                console.log(res.data);
                document.getElementById('reset-button').click();
                this.forms('REFRACAO');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    }, 
    deleteRefracao(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.delete(`/rpclinica/delete-refracao/${CodForm}`)
                        .then((res) => {
                            document.getElementById('reset-button').click();

                        $('#label_re_receita span').removeClass('checked');
                        $('#label_re_receita input').prop('checked', false);
                        $('#label_rd_receita span').removeClass('checked');
                        $('#label_rd_receita input').prop('checked', false);
                
                        $('select#are_oe_av').val(null).trigger('change');
                        $('select#are_od_av').val(null).trigger('change');
                        $('select#ard_oe_add_av').val(null).trigger('change');
                        $('select#ard_oe_av').val(null).trigger('change'); 
                        $('select#ard_od_add_av').val(null).trigger('change');
                        $('select#ard_od_av').val(null).trigger('change'); 

                            this.forms('REFRACAO');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });
                }
            });
    }, 
    modalRefracao(CodForm, Form) {
        var parametros = {'cd_formulario': Form};   
        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-refracao/${CodForm}`, {
            params:  parametros
        })
            .then((res) => {
                this.modalConteudo = res.data;
                this.modalTitulo = "Nome do Paciente";
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },
 
    /* receita */
    indexReceitas(data) {
        console.log(data.retorno);
        this.RECEITA.formData = data.retorno
        this.RECEITA.history = data.historico  
    },   
    storeReceita() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_RECEITA'));
        axios.post(`/rpclinica/store-oftalmo-receita/${idAgendamento}`, form)
            .then((res) => {
                document.getElementById('reset-button').click();
                this.forms('RECEITAS');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {

                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    }, 
    deleteReceita(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.delete(`/rpclinica/delete-receita/${CodForm}`)
                        .then((res) => {
                            this.forms('RECEITAS');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });
                }
            });
    }, 
    modalReceita(CodForm, Form) {
        var parametros = {'cd_formulario': Form};   
        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-receita/${CodForm}`,{
            params:  parametros
        })
            .then((res) => {
                this.modalConteudo = res.data;
                this.modalTitulo = "Nome do Paciente";
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },
 
    /* Atestado */
    indexAtestado(data) { 
        this.ATESTADO.formData = data.retorno
        this.ATESTADO.history = data.historico  
    }, 
    storeAtestado() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_ATESTADO'));
        axios.post(`/rpclinica/store-oftalmo-atestado/${idAgendamento}`, form)
            .then((res) => {
                this.forms('ATESTADOS');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    }, 
    deleteAtestado(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.delete(`/rpclinica/delete-atestado/${CodForm}`)
                        .then((res) => {
                            document.getElementById('reset-button').click();
                            this.forms('ATESTADOS');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });
                }
            });
    }, 
    modalAtestado(CodForm, Form) {
        var parametros = {'cd_formulario': Form};   
        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-atestado/${CodForm}`,{
            params:  parametros
        })
            .then((res) => {
                this.modalConteudo = res.data; 
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },


    /* Receita Oculos */
    indexReceitaOculos(data) {
        console.log(data.retorno);
        this.RECEITA_OCULOS.formData = data.retorno
        this.RECEITA_OCULOS.history = data.historico   
        $('select#tipo_lente').val(this.RECEITA_OCULOS.formData.tipo_lente).trigger('change'); 
 
    }, 
    storeReceitaOculos() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_RECEITA_OCULOS'));
        let errors = [];

        // Normalize numeric fields (replace commas with dots)
        const numericFields = [
            'longe_od_de',
            'longe_od_dc',
            'longe_od_eixo',
            'longe_od_add',
            'longe_oe_de',
            'longe_oe_dc',
            'longe_oe_eixo',
            'longe_oe_add',
            'perto_od_de',
            'perto_od_dc',
            'perto_od_eixo',
            // 'perto_od_add',
            'perto_oe_de',
            'perto_oe_dc',
            'perto_oe_eixo',
            // 'perto_oe_add',
            'inter_od_de',
            'inter_od_dc',
            'inter_od_eixo',
            'inter_oe_de',
            'inter_oe_dc',
            'inter_oe_eixo'
        ];

        numericFields.forEach(field => {
            let value = form.get(field);
            if (value) {
                // Replace commas with dots to normalize float values
                value = value.replace('.', '');
                value = value.replace(',', '.');
                form.set(field, value);  // Update the value in the form data
            }
        });

        // Check if 'cd_profissional' is a valid number
        if (!form.get('cd_profissional') || isNaN(form.get('cd_profissional'))) {
            errors.push('Profissional code is invalid.');
        }

        // Check if 'comentario' is not empty
        // if (!form.get('comentario')) {
        //     errors.push('Comentário is required.');
        // }

        // Validate date fields
        const dateFields = ['dt_exame'];
        dateFields.forEach(field => {
            const dateValue = form.get(field);
            if (dateValue && isNaN(Date.parse(dateValue))) {
                errors.push(`Invalid date for ${field}.`);
            }
        });

        // Validate numeric fields
        

        // Check for errors before submission
        if (errors.length > 0) {
            toastr['error'](errors.join('<br>'), 'Validation Error');
            this.buttonDisabled = false;
            this.buttonSalvar = " Salvar ";
            return;
        }

        axios.post(`/rpclinica/store-oftalmo-receita_oculos/${idAgendamento}`, form)
            .then((res) => {
                this.forms('RECEITA_OCULOS');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    },

    deleteReceitaOculos(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/rpclinica/delete-receita_oculos/${CodForm}`)
                        .then((res) => {
                            document.getElementById('reset-button').click(); 
                            $('select#tipo_lente').val(null).trigger('change'); 
                            this.forms('RECEITA_OCULOS');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });
                }
            });
    }, 
    modalReceitaOculos(CodForm, Form) {
        var parametros = {'cd_formulario': Form};   
        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-receita_oculos/${CodForm}`, {
            params:  parametros
        })
            .then((res) => {
                this.modalConteudo = res.data; 
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },


    indexReservaCirurgia(data) { 
        this.RESERVA.formData = data.retorno.query
        this.RESERVA.opme = data.retorno.opme 
        this.RESERVA.history = data.historico   
        $('select#cd_cirurgiao').val(this.RESERVA.formData.cd_cirurgiao).trigger('change');
        $('select#cd_cirurgia').val(this.RESERVA.formData.cd_cirurgia).trigger('change'); 
        $('select#opme').val(this.RESERVA.opme).trigger('change'); 
        console.log(this.RESERVA);

    },  
    storeReservaCirurgia() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_RESERVA_CIRURGIA'));
        axios.post(`/rpclinica/store-reserva-cirurgia/${idAgendamento}`, form)
            .then((res) => { 
                this.forms('RESERVA_CIRURGIA');
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
            });
    }, 
    deleteReservaCirurgia(CodForm) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.delete(`/rpclinica/delete-reserva-cirurgia/${CodForm}`)
                        .then((res) => { 
                            document.getElementById('reset-button').click();
                            $('select#cd_cirurgiao').val(null).trigger('change'); 
                            $('select#cd_cirurgia').val(null).trigger('change'); 
                            $('select#opme').val(null).trigger('change'); 
                            this.forms('RESERVA_CIRURGIA');
                            toastr['success']('Formulario deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {

                        });
                }
            });
    }, 
    modalReservaCirurgia(CodForm, Form) {
        var parametros = {'cd_formulario': Form};   
        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-reserva-cirurgia/${CodForm}`,{
            params:  parametros
        })
            .then((res) => {
                this.modalConteudo = res.data; 
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },


    /*  Historico de Exame */ 
    indexHistoricoExame(data) {  
        this.HISTORICO.history = data.historico    
        console.log(this.HISTORICO);

    },  
    FormatData(data) {
        var dt = data.split(" ")[0];
        var dia  = dt.split("-")[2];
        var mes  = dt.split("-")[1];
        var ano  = dt.split("-")[0];
      
        return ("0"+dia).slice(-2) + '/' + ("0"+mes).slice(-2) + '/' + ano;
      
      }


}));


