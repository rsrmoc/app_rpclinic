Alpine.data('appPacienteEdit', () => ({
    loading: false,

    savePaciente() {
        this.loading = true;

        let form = new FormData(document.querySelector('#formPaciente'));

        axios.post(`/app_rpclinic/api/paciente-edit/${cdPaciente}`, form)
            .then((res) => {
                toastr.success(res.data.message);
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loading = false);
    }
}));