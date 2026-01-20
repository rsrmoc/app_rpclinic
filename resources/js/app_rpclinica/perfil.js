Alpine.data('appPerfil', () => ({
    loading: false,

    saveProfile() {
        this.loading = true;

        const form = new FormData(document.querySelector('#formProfile'));

        axios.post('/app_rpclinic/api/perfil-update', form)
            .then((res) => toastr.success(res.data.message))
            .catch((err) => toastr.success(err.response.data.message))
            .finally(() => this.loading = false);
    }
}));