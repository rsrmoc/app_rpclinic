import axios from 'axios';
import moment from 'moment';

Alpine.data('appPEP', () => ({
  
    telaAtiva: null,

    
    conteudo: '<div class="col-md-7"><div style="text-align: center;"> <img src="/assets/images/oftalmo.png" > </div> </div>',
    conteudoCarregando: '<div class="col-md-7"><div style="text-align: center;"> <img style="height: 80px;margin-top: 100px;" src="/assets/images/carregandoFormulario.gif" > </div> </div>',
    conteudoCarregandoModal: '<div class="col-md-12"><div style="text-align: center;"> <img style="height: 80px;margin-top: 100px;margin-bottom: 60px;" src="/assets/images/carregandoFormulario.gif" > </div> </div>',
    loading: false,
    buttonSalvar: " <i class='fa fa-check'></i>  Salvar ",
    buttonDisabled: false,
    modalTitulo: "",
    modalConteudo: "",

   
    forms(form) {
        this.telaAtiva= form
        /*
        if (!idAgendamento) {
            toastr['Atenção']('Atendimento não Informado');
        } else {
            this.conteudo = this.conteudoCarregando;
            axios.get(`/rpclinica/consultorio-formularios-oftalmo/${idAgendamento}/${form}`)
                .then((response) => {
                    this.conteudo = response.data;
                })
                .catch((err) => {
                    toastr['error'](err.response.data.message, 'Erro');
                })
                .finally(() => this.loading = false);
        }
        */
    },

    /*###########################################Pre-Exame Oftalmologia#####################################################*/

    /* AutoRefracao */

    storeAutoRefracao() {
        console.log("here90");
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
        const dateFields = ['dt_exame', 'dt_liberacao'];
        dateFields.forEach(field => {
            const dateValue = form.get(field);
            if (dateValue && isNaN(Date.parse(dateValue))) {
                errors.push(`Invalid date for ${field}.`);
            }
        });

        // Validate numeric fields
        

        // Check for errors before submission
        // if (errors.length > 0) {
        //     toastr['error'](errors.join('<br>'), 'Validation Error');
        //     this.buttonDisabled = false;
        //     this.buttonSalvar = " Salvar ";
        //     return;
        // }

        console.log(form)

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
                    console.log(cd_agendamento);
                    document.getElementById('reset-button').click();

                    axios.delete(`/rpclinica/delete-auto-refracao/${cd_agendamento}`)
                        .then((res) => {
                            console.log(res.data);
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

    modalAutoRefracao(CodForm, historico) {
        console.log("historico: " + historico)
        console.log("test")

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-auto-refracao/${CodForm}`, {
            data: { teste: 10 }
        })
            .then((res) => {
                this.modalConteudo = res.data;
                console.log('teste' + res.data)
                this.modalTitulo = "Nome do Paciente";
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {

            });

    },

    /* CERATOMETRIA */

    storeCeratometria() {
        console.log("here90");
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

    modalCeratometria(CodForm) {

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-ceratometria/${CodForm}`)
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

    /* CERATOMETRIA COMPUTADORIZADA */
    storeCeratometriaComp() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_CERATOMETRIA_COMP'));
        axios.post(`/rpclinica/store-oftalmo-ceratometria-comp/${idAgendamento}`, form)
            .then((res) => {
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
                    console.log("test")

                    axios.delete(`/rpclinica/delete-ceratometria-comp/${CodForm}`)
                        .then((res) => {
                            console.log('testtt');
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

    modalCeratometriaComp(CodForm) {

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-ceratometria-comp/${CodForm}`)
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

    modalCompletoCeratometriaComp(CodForm) {

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-completo-ceratometria-comp/${CodForm}`)
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

    modalAnamnese(CodForm) {

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-anamnese/${CodForm}`)
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


    /* Fundoscopia */

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
                    console.log("test")

                    axios.delete(`/rpclinica/delete-fundoscopia-img/${CodForm}`)
                        .then((res) => {
                            console.log('testtt');
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

    modalFundoscopia(CodForm) {

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-fundoscopia/${CodForm}`)
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


    /* Tonometria Aplanacao */

    storeTonometriaAplanacao() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_TONOMETRIA_APLANACAO'));
        console.log("here90");

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

        // Validate numeric fields
        

        // Check for errors before submission
        // if (errors.length > 0) {
        //     toastr['error'](errors.join('<br>'), 'Validation Error');
        //     this.buttonDisabled = false;
        //     this.buttonSalvar = " Salvar ";
        //     return;
        // }

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

    modalTonometriaAplanacao(CodForm) {

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-tonomeria-aplanacao/${CodForm}`)
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

    modalRefracao(CodForm) {

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-refracao/${CodForm}`)
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

    modalReceita(CodForm) {

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-receita/${CodForm}`)
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

    modalAtestado(CodForm) {

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-atestado/${CodForm}`)
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


    /* Receita Oculos */

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

    modalReceitaOculos(CodForm) {

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-receita_oculos/${CodForm}`)
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


    /*  Reserva de Cirúrgica */

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
                            console.log("reserva")
                            document.getElementById('reset-button').click();
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

    modalReservaCirurgia(CodForm) {

        $('.modalHistFormularios').modal('toggle');
        this.modalConteudo = this.conteudoCarregandoModal;
        axios.get(`/rpclinica/modal-reserva-cirurgia/${CodForm}`)
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


}));


