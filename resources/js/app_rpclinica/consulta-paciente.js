import moment from 'moment';
import { jsPDF } from 'jspdf';

const modalElement = document.getElementById('modalFinalizar');
// Removed manual appendChild to keep Alpine scope context
// if (modalElement) { document.body.appendChild(modalElement); }

const modalModelosElement = document.getElementById('modalMeusModelos');
// Removed manual appendChild to keep Alpine scope context
// if (modalModelosElement) { document.body.appendChild(modalModelosElement); }

const modal = new bootstrap.Modal(modalElement, {
    backdrop: 'static',
    keyboard: false
});

let modalModelosInstance = null;
if (modalModelosElement) {
    modalModelosInstance = new bootstrap.Modal(modalModelosElement, {
        backdrop: true,
        keyboard: true
    });
}

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

    // Flag para evitar duplo clique no seletor de modelos
    isSelectingModelo: false,

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
    atendido: window.initialAtendido || false,
    editor: null,
    editor_alertas: null,
    editor_anamnese: null,
    docsFormularioSelected: null,
    docsFormularioName: null,

    openModalModelos() {
        if (modalModelosInstance) {
            this.isSelectingModelo = false; // Reset flag ao abrir
            modalModelosInstance.show();
        }
    },

    selectModelo(id, name) {
        // Evita travamentos causados por duplo clique/touch
        if (this.isSelectingModelo) {
            console.log('⏳ Seleção de modelo já em andamento, ignorando click duplicado');
            return;
        }

        this.isSelectingModelo = true;
        console.log('📄 Selecionando modelo:', name, '| ID:', id);

        // Atualiza os valores imediatamente
        this.docsFormularioSelected = id;
        this.docsFormularioName = name;

        // Fecha o modal com um pequeno delay para evitar conflitos de eventos em dispositivos touch/PWA
        if (modalModelosInstance) {
            // Usa requestAnimationFrame para garantir que a UI atualize antes de fechar
            requestAnimationFrame(() => {
                setTimeout(() => {
                    try {
                        modalModelosInstance.hide();
                    } catch (e) {
                        console.warn('⚠️ Erro ao fechar modal de modelos:', e);
                    }
                    // Reset flag após o modal fechar
                    setTimeout(() => {
                        this.isSelectingModelo = false;
                    }, 300);
                }, 50);
            });
        } else {
            this.isSelectingModelo = false;
        }
    },

    init() {
        this.getDocs();
        this.getHistorico();

        this.editor = CKEDITOR.replace('editor', {
            toolbar: [
                { name: 'clipboard', items: ['Undo', 'Redo'] },
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize'] },
            ],
            removeButtons: 'Subscript,Superscript,',
            resize_enabled: false,
            height: ['200px'],
            removePlugins: 'elementspath',
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });

        this.editor_alertas = CKEDITOR.replace('editor-alertas', {
            toolbar: [
                { name: 'clipboard', items: ['Undo', 'Redo'] },
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize'] },
            ],
            removeButtons: 'Subscript,Superscript,',
            resize_enabled: false,
            height: ['200px'],
            removePlugins: 'elementspath',
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });

        this.editor_anamnese = CKEDITOR.replace('editor-anamnese', {
            toolbar: [
                { name: 'clipboard', items: ['Undo', 'Redo'] },
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize'] },
            ],
            removeButtons: 'Subscript,Superscript,',
            resize_enabled: false,
            height: ['200px'],
            removePlugins: 'elementspath',
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });

        this.$watch('docsFormularioSelected', () => {
            this.editor.setData(formularios.find((formulario) => formulario.cd_formulario == this.docsFormularioSelected)?.conteudo ?? '');
        });

        // Robust delegation for Modal Button (since Bootstrap moves it out of Alpine scope)
        document.body.addEventListener('click', (e) => {
            const btn = e.target.closest('#btnConfirmFinalizar');
            if (btn) {
                e.preventDefault();
                e.stopPropagation();

                // Avoid double clicks if already processing
                if (btn.hasAttribute('disabled')) return;

                this.finalizarConsulta(btn);
            }
        });
    },

    modalFinalizar() {
        modal.show();
    },

    finalizarConsulta(btnElement = null) {
        // Find button if not passed directly
        const btn = btnElement || document.getElementById('btnConfirmFinalizar');
        let originalContent = '';

        if (btn) {
            btn.disabled = true;
            // Store original content to restore later if needed
            originalContent = btn.innerHTML;
            // Manually show spinner
            btn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span>Finalizando...</span>
            `;
        }

        this.loadingFinalizar = true;

        // Use global constant cdAgendamento as fallback if this.cdAgendamento is missing
        const id = this.cdAgendamento || (typeof cdAgendamento !== 'undefined' ? cdAgendamento : 0);

        axios.post(`${routeConsultaFinalizar}/${id}`)
            .then((res) => {
                toastr.success(res.data.message);
                modal.hide();
                this.atendido = true;
                // Don't restore button immediately, modal is hiding
            })
            .catch((err) => {
                parseErrorsAPI(err);
                // Restore button state on error
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalContent || 'Sim, finalizar!';
                }
            })
            .finally(() => {
                this.loadingFinalizar = false;
            });
    },

    getDocs() {
        this.loadingDocs = true;

        axios.get(`${routeConsultaDocs}/${this.cdAgendamento}`)
            .then((res) => {
                this.docs = res.data.documentos;
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loadingDocs = false);
    },

    getHistorico() {
        this.loadingHistorico = true;

        axios.post(routeConsultaHistorico, {
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
        axios.post(routeConsultaAnamnese, form)
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
        axios.post(routeConsultaAlertas, form)
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

        axios.post(routeConsultaDoc, form)
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