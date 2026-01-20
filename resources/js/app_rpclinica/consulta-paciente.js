import moment from 'moment';
import { jsPDF } from 'jspdf';

const modal = new bootstrap.Modal('#modalFinalizar', {
    backdrop: 'static',
    keyboard: false
});

Alpine.data('appConsultaPaciente', () => ({
    cdAgendamento,
    cdPaciente,
    tab: 0,

    historico: [],
    loadingHistorico: false,

    loadingSaveAnamnese: false,
    loadingSaveAlertas: false,
    loadingSaveDoc: false,

    docs: [],
    loadingDocs: false,

    loadingFinalizar: false,

    classStatusAgendamento: {
        livre: 'text-bg-success',
        agendado: 'text-bg-primary',
        confirmado: 'text-bg-info',
        atendido: 'text-bg-warning',
        bloqueado: 'text-bg-danger',
        cancelado: 'text-bg-danger',
        aguardando: 'text-bg-secondary',
        atendimento: 'text-bg-secondary'
    },
    atendido: false,
    editor: null,
    editor_alertas: null,
    editor_anamnese: null,
    docsFormularioSelected: null,

    init() {
        this.getDocs();
        this.getHistorico();
        
        this.editor = CKEDITOR.replace('editor', {
            toolbar: [
                { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList'] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'tools', items: [ 'Maximize' ] },
            ],
            removeButtons: 'Subscript,Superscript,',
            resize_enabled : false,
            height:['200px'],
            removePlugins: 'elementspath',
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });
        
        this.editor_alertas = CKEDITOR.replace('editor-alertas', {
            toolbar: [
                { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList'] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'tools', items: [ 'Maximize' ] },
            ],
            removeButtons: 'Subscript,Superscript,',
            resize_enabled : false,
            height:['200px'],
            removePlugins: 'elementspath',
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });
         
        this.editor_anamnese = CKEDITOR.replace('editor-anamnese', {
            toolbar: [
                { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList'] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'tools', items: [ 'Maximize' ] },
            ],
            removeButtons: 'Subscript,Superscript,',
            resize_enabled : false,
            height:['200px'],
            removePlugins: 'elementspath',
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });

        this.$watch('docsFormularioSelected', () => {
            this.editor.setData(formularios.find((formulario) => formulario.cd_formulario == this.docsFormularioSelected)?.conteudo ?? '');
        });
    },

    modalFinalizar() {
        modal.show();
    },

    finalizarConsulta() {
        this.loadingFinalizar = true;

        axios.post(`/app_rpclinic/api/consulta-finalizar/${this.cdAgendamento}`)
            .then((res) => {
                toastr.success(res.data.message);
                modal.hide();
                this.atendido = true;
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loadingFinalizar = false);
    },

    getDocs() {
        this.loadingDocs = true;

        axios.get(`/app_rpclinic/api/consulta-docs/${this.cdAgendamento}`)
            .then((res) => {
                this.docs = res.data.documentos;
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loadingDocs = false);
    },

    getHistorico() {
        this.loadingHistorico = true;

        axios.post('/app_rpclinic/api/consulta-paciente-historico', {
            cd_paciente: this.cdPaciente
        })
            .then((res) => {
                this.historico = res.data.historico;
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loadingHistorico = false);
    },

    saveAnamnese() {
        this.loadingSaveAnamnese = true;

        let form = new FormData(document.querySelector('#formAnamnese'));
        form.set('conteudo', this.editor_anamnese.getData()); 
        axios.post('/app_rpclinic/api/consulta-paciente-anamnese', form)
            .then((res) => {
                toastr.success(res.data.message)
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loadingSaveAnamnese = false);
    },

    saveAlertas() {
        this.loadingSaveAlertas = true;

        let form = new FormData(document.querySelector('#formAlertas')); 
        form.set('conteudo', this.editor_alertas.getData());
        axios.post('/app_rpclinic/api/consulta-paciente-alertas', form)
            .then((res) => {
                toastr.success(res.data.message)
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loadingSaveAlertas = false);
    },

    saveDoc() {
        this.loadingSaveDoc = true;

        let formElement = document.querySelector('#formDoc');
        let form = new FormData(formElement);
        form.set('conteudo', this.editor.getData());

        axios.post('/app_rpclinic/api/consulta-paciente-doc', form)
            .then((res) => {
                this.docs.unshift(res.data.doc);

                toastr.success(res.data.message);

                formElement.reset();
                this.editor.setData('');
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loadingSaveDoc = false);
    },

    formatDate(date) {
        return moment(date).lang('pt-BR').format('LLL');
    },

    downloadPDF(name, content) {
        let doc = new jsPDF();

        doc.html(
            content, 
            {
                callback: function (doc) {
                    doc.save(name);
                },
                margin: [10, 10, 10, 10],
                autoPaging: 'text',
                x: 0,
                y: 0,
                width: 190,
                windowWidth: 675
            }
        );
    }
}));