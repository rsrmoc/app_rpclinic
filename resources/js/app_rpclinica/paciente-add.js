Alpine.data('appPacienteAdd', () => ({
    loading: false,
    pacienteData: {
        nm_paciente: null,
        dt_nasc: null,
        nm_mae: null,
        nm_pai: null,
        sexo: null,
        cpf: null,
        rg: null
    },

    createPaciente() {
        this.loading = true;

        axios.post(routePacienteAdd, this.pacienteData)
            .then((res) => {
                toastr.success(res.data.message);

                this.pacienteData = {
                    nm_paciente: null,
                    dt_nasc: null,
                    nm_mae: null,
                    nm_pai: null,
                    sexo: null,
                    cpf: null,
                    rg: null
                };
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loading = false);
    }
}));