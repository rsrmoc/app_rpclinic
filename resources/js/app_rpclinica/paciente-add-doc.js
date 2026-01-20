Alpine.data('appPacienteDoc', () => ({
    loading: false,
    dataDoc: {
        cd_pac: cdPaciente,
        cd_formulario: null,
        conteudo: null
    },
    editor: null,

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

    saveDoc() {
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
}));