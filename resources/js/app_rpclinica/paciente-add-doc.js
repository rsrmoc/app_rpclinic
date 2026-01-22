Alpine.data('appPacienteDoc', () => {
    // Ler dados do DOM injetados pelo Blade
    const formulariosEl = document.getElementById('data-formularios');
    const formularios = formulariosEl ? JSON.parse(formulariosEl.textContent) : [];

    const cdPacienteEl = document.getElementById('data-cd-paciente');
    const cdPaciente = cdPacienteEl ? cdPacienteEl.value : null;

    const routeEl = document.getElementById('data-route-add-doc');
    const routePacienteAddDoc = routeEl ? routeEl.value : '';

    return {
    loading: false,
    modeloDocumentoQuery: '',
    dataDoc: {
        cd_pac: cdPaciente,
        cd_formulario: null,
        conteudo: null
    },
    editor: null,

    get selectedModeloDocumentoLabel() {
        const selected = formularios.find((formulario) => formulario.cd_formulario == this.dataDoc.cd_formulario);
        return selected?.nm_formulario ?? 'Selecione';
    },

    get modeloDocumentoFiltrado() {
        const query = (this.modeloDocumentoQuery ?? '').toString().trim().toLowerCase();
        if (!query) return formularios;
        return formularios.filter((formulario) => (formulario.nm_formulario ?? '').toString().toLowerCase().includes(query));
    },

    init() {
        this.editor = CKEDITOR.replace('editor', {
            toolbar: [
                { name: 'clipboard', items: ['Undo', 'Redo'] },
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize'] },
            ]
        });

        this.$watch('dataDoc.cd_formulario', () => {
            this.editor.setData(formularios.find((formulario) => formulario.cd_formulario == this.dataDoc.cd_formulario)?.conteudo_atual ?? '');
        });
    },

    openModeloDocumentoPicker() {
        const modalEl = document.getElementById('modalModeloDocumento');
        if (!modalEl || typeof bootstrap === 'undefined' || !bootstrap?.Modal) return;

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

        setTimeout(() => {
            if (this.$refs?.modeloDocumentoSearch) {
                this.$refs.modeloDocumentoSearch.focus();
            }
        }, 150);
    },

    selectModeloDocumento(cdFormulario) {
        this.dataDoc.cd_formulario = cdFormulario;
        this.modeloDocumentoQuery = '';

        const modalEl = document.getElementById('modalModeloDocumento');
        if (!modalEl || typeof bootstrap === 'undefined' || !bootstrap?.Modal) return;
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();
    },

    saveDoc() {
        if (!this.dataDoc.cd_formulario) {
            toastr.error('Selecione um modelo de documento.');
            return;
        }

        this.loading = true;

        let data = Object.assign({}, this.dataDoc);
        data.conteudo = this.editor.getData();
        data.nm_formulario = formularios.find((formulario) => formulario.cd_formulario == data.cd_formulario)?.nm_formulario;

        axios.post(routePacienteAddDoc, data)
            .then((res) => {
                toastr.success(res.data.message);

                this.dataDoc.cd_formulario = null;
                this.dataDoc.conteudo = null;
                this.editor.setData('');
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loading = false);
    }
    };
});
