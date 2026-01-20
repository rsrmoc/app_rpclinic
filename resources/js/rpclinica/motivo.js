Alpine.data('app', () => ({
    editionMotivo: null,
    loadingModal: false,
    inputMotivo: null,
    motivos: [],

    init() {
        this.motivos = motivos;

        $('#modal-form-motivo').on('hidden.bs.modal', () => {
            this.inputMotivo = null;
            this.editionMotivo = null;
        })
    },

    openModalMotivo(motivo = null) {
        if (motivo) {
            this.editionMotivo = motivo.cd_motivo;
            this.inputMotivo = motivo.motivo;
        }

        $('#modal-form-motivo').modal('show')
    },
    saveMotivo() {
        this.loadingModal = true

        if (this.editionMotivo) {
            axios.put('/rpclinica/json/motivos', {
                cd_motivo: this.editionMotivo,
                motivo: this.inputMotivo
            })
            .then((res) => {
                let indiceMotivo = this.motivos.findIndex((motivo) => motivo.cd_motivo == this.editionMotivo)
                this.motivos[indiceMotivo] = res.data.motivo;
                toastr['success'](res.data.message);
                $('#modal-form-motivo').modal('hide')
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingModal = false)

            return;
        }

        axios.post('/rpclinica/json/motivos', {
            motivo: this.inputMotivo
        })
        .then((res) => {
            this.motivos.push(res.data.motivo);
            toastr['success'](res.data.message);
            $('#modal-form-motivo').modal('hide')
        })
        .catch((err) => toastr['error'](err.response.data.message))
        .finally(() => this.loadingModal = false)
    }
}))

$(document).ready(() => {
    $('#modal-form-motivo').modal({
        backdrop: 'static',
        show: false
    });
})