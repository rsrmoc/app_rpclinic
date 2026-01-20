Alpine.data('app', () => ({
    loadingUsuario: false,
    inputsUsuario: {
        email: null,
        nome: null,
        perfil: null,
        admin: 'N',
        ativo: 'S',
        celular: null,
        empresa: null,
        senha: null,
        resetar_senha: false,
        enviar_email: false,
        profissional: null, 
        sn_todos_agendamentos: false,
    },
    isUsuario: null,
  

    init() {

        $('#perfil-usuario').on('select2:select', (evt) => this.inputsUsuario.perfil = evt.params.data.id);

        $('#admin-usuario').on('select2:select', (evt) => this.inputsUsuario.admin = evt.params.data.id);

        $('#ativo-usuario').on('select2:select', (evt) => this.inputsUsuario.ativo = evt.params.data.id);

        $('#ativo-profissional').on('select2:select', (evt) => this.inputsProfissional.ativo = evt.params.data.id);

        $('#profissional-procedimento').on('select2:select', (evt) => this.inputsProcedimento.cd_procedimento = evt.params.data.id);

        $('#profissional-convenio').on('select2:select', (evt) => this.inputsProcedimento.cd_convenio = evt.params.data.id);

        $('#profissional-especialidade').on('select2:select', (evt) => this.inputEspecialidade = evt.params.data.id);

        this.setUser(usuario);
    },

    setUser(usuario) {

  
    },

    submitSave() {
        $('#formUsuario input[type="submit"]').click();
    },

    submitSaveUsuario() {

        this.inputsUsuario.empresa = $('#empresa-usuario').val();
        this.inputsUsuario.profissional = $('#prof-usuario').val();
        this.inputsUsuario.ativo = $('#ativo-usuario').val();
        this.inputsUsuario.perfil = $('#perfil-usuario').val();
 

        let data = Object.assign({}, this.inputsUsuario);
        if (this.inputsUsuario.sn_profissional) data.profissional = Object.assign({}, this.inputsProfissional);

        this.loadingUsuario = true;

        if (this.isUsuario) {
            axios.post(`/rpclinica/json/usuario-update/${usuario.cd_usuario}`, data)
                .then((res) => toastr['success'](res.data.message))
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => this.loadingUsuario = false);
            return;
        }

        axios.post(`/rpclinica/json/usuario-store`, data)
            .then((res) => {
                toastr['success'](res.data.message);
                console.log(res.data);
                setTimeout(() => {
                    location.href='/rpclinica/usuario-listar'
                }, 100000);
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingUsuario = false);
    },

    // medotos procedimentos
    submitAddProcedimento() {
        if (this.isUsuario && usuario?.profissional) {
            let data = Object.assign({}, this.inputsProcedimento);
            data.cd_profissional = usuario.cd_profissional;

            axios.post(`/rpclinica/json/profissional-store-procedimento`, data)
                .then((res) => {
                    data.cd_proc_prof = res.data.procedimento.cd_proc_prof;
                    this.inputsProfissional.procedimentos.push(data);
                    toastr['success'](res.data.message);
                    this.clearFormProcedimentos();
                })
                .catch((err) => toastr['error'](err.response.data.message));
            return;
        }

        this.inputsProfissional.procedimentos.push(Object.assign({}, this.inputsProcedimento));
        this.clearFormProcedimentos();
    },
    deleteProcedimento(indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Deseja excluir esse procedimento?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                if (this.inputsProfissional.procedimentos[indice].cd_proc_prof) {
                    axios.delete(`/rpclinica/json/profissional-delete-procedimento/${this.inputsProfissional.procedimentos[indice].cd_proc_prof}`)
                        .then((res) => {
                            this.inputsProfissional.procedimentos.splice(indice, 1);
                            toastr['success']('Procedimento excluido com sucesso!');
                        })
                        .catch((err) => toastr['error'](err.response.data.message));
                    return;
                }

                this.inputsProfissional.procedimentos.splice(indice, 1);
            }
        });
    },
    clearFormProcedimentos() {
        this.inputsProcedimento = {
            cd_procedimento: null,
            cd_convenio: null,
            valor: null,
            repasse: null
        };
        $('#profissional-procedimento').val(null).trigger('change');
        $('#profissional-convenio').val(null).trigger('change');
    },

    // metodos especialidades
    submitAddEspecialidade() {
        if (this.isUsuario && usuario?.profissional) {
            let data = {
                cd_especialidade: this.inputEspecialidade,
                cd_profissional: usuario.cd_profissional
            };

            axios.post(`/rpclinica/json/profissional-store-especialidade`, data)
                .then((res) => {
                    data.cd_prof_espec = res.data.especialidade.cd_prof_espec;
                    this.inputsProfissional.especialidades.push(data);
                    toastr['success'](res.data.message);
                    $('#profissional-especialidade').val(null).trigger('change');
                })
                .catch((err) => toastr['error'](err.response.data.message));
            return;
        }

        this.inputsProfissional.especialidades.push({ cd_especialidade: this.inputEspecialidade });
        $('#profissional-especialidade').val(null).trigger('change');
    },
    deleteEspecialidade(indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Deseja excluir esse especialidade?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                if (this.inputsProfissional.especialidades[indice].cd_prof_espec) {
                    axios.delete(`/rpclinica/json/profissional-delete-especialidade/${this.inputsProfissional.especialidades[indice].cd_prof_espec}`)
                        .then((res) => {
                            this.inputsProfissional.especialidades.splice(indice, 1);
                            toastr['success']('Especialidade excluida com sucesso!');
                        })
                        .catch((err) => toastr['error'](err.response.data.message));
                    return;
                }

                this.inputsProfissional.especialidades.splice(indice, 1);
            }
        });
    },
}));
