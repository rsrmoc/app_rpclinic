Alpine.data('appPerfil', () => ({
    loading: false,

    saveProfile() {
        this.loading = true;

        const form = new FormData(document.querySelector('#formProfile'));

        axios.post(routePerfilUpdate, form)
            .then((res) => { 
                toastr.success(res.data.message, 'Sucesso', {
                    timeOut: 7000,
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-center",
                    showMethod: "slideDown",
                    hideMethod: "slideUp"
                });  
            })
            .catch((err) => {
            toastr.error(err.response.data.message, 'Erro', {
                timeOut: 7000,
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-center",
                showMethod: "slideDown",
                hideMethod: "slideUp"
            });
            })
            .finally(() => this.loading = false);
    }
}));