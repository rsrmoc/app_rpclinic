Dropzone.options.dzAnexos = {
    paramName: 'files',
    uploadMultiple: true,
    acceptedFiles: 'image/*,application/pdf',
    autoProcessQueue: false,
    addRemoveLinks: true,

    init() {
        $('#dz-anexos-submit').on('click', () => this.processQueue());

        this.on('addedfile', () => {
            if ($('#dz-anexos-submit').is(':disabled')) {
                $('#dz-anexos-submit').prop('disabled', false);
            }
        });

        this.on('removedfile', () => {
            if (this.files.length == 0 && !$('#dz-anexos-submit').is(':disabled')) {
                $('#dz-anexos-submit').prop('disabled', true);
            }
        });

        this.on('successmultiple', () => {
            this.removeAllFiles(true);
            window.postMessage('added-anexos');
        });

        this.on('error', (file) => {
            toastr['error']('Houve um erro ao enviar os arquivos!');
        });
    }
}
