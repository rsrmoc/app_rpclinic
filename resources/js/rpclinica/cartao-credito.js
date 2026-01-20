Alpine.data('app', () => ({
    saveLoadingTriagem: false,
    saveLoadingAnamnese: false,
    saveLoadingProblemas: false,
    saveLoadingExames: false,
    saveLoadingDoc: false,

    deleteDocIndice: null,

    formularios: [],

    documentos: [],
    cdDocumentoEdicao: null,

    // historico
    historicoAgendametos: [],
    historicoAgendamentoSelected: null,
    historicoPrintAgendamento: false,

    anexos: [],
    loadingAnexos: false,
    indiceDeleteAnexo: null,

    editorAnamnese: null,
    editorExame: null,
    editorHipotese: null,
    editorConduta: null,

    editorDocumentos: null,
    editorHistoricoExames: null,
    editorProblemas: null,

    init() {
        this.formularios = formularios;
        this.documentos = agendamentoDocumentos;
        this.historicoAgendametos = historicoAgentamentos;

        this.editorAnamnese = CKEDITOR.replace('editor-formulario-anamnese');
        this.editorExame = CKEDITOR.replace('editor-formulario-exame-fisico');
        this.editorHipotese = CKEDITOR.replace('editor-formulario-hipotese-diagnostica');
        this.editorConduta = CKEDITOR.replace('editor-formularios-conduta');

        this.editorDocumentos = CKEDITOR.replace('editor-formularios-documentos');
        this.editorHistoricoExames = CKEDITOR.replace('editor-exames-tabexames');
        this.editorProblemas = CKEDITOR.replace('editor-problemas');

        $('#formularios-anamnese').on('select2:select', (evt) => {
            let val = evt.params.data.id;
            let formulario = this.formularios.find((formulario) => val == formulario.cd_formulario)

            this.editorAnamnese.setData(formulario?.conteudo)
        })

        $('#formularios-exame-fisico').on('select2:select', (evt) => {
            let val = evt.params.data.id;
            let formulario = this.formularios.find((formulario) => val == formulario.cd_formulario)

            this.editorExame.setData(formulario?.conteudo)
        })

        $('#formularios-hipostese-diagnostica').on('select2:select', (evt) => {
            let val = evt.params.data.id;
            let formulario = this.formularios.find((formulario) => val == formulario.cd_formulario)

            this.editorHipotese.setData(formulario?.conteudo)
        })

        $('#formularios-conduta').on('select2:select', (evt) => {
            let val = evt.params.data.id;
            let formulario = this.formularios.find((formulario) => val == formulario.cd_formulario)

            this.editorConduta.setData(formulario?.conteudo)
        })

        $('#formularios-documentos').on('select2:select', (evt) => {
            let val = evt.params.data.id;
            let formulario = this.formularios.find((formulario) => val == formulario.cd_formulario)

            this.editorDocumentos.setData(formulario?.conteudo)
        })

        window.addEventListener('message', (evt) => {
            if (evt.data !== 'added-anexos') return;

            this.getAnexos();
        });

        this.getAnexos();
    },

    submitTriagem() {
        this.saveLoadingTriagem = true;

        let form = new FormData(document.querySelector('#tabTriagem'));

        axios.post(`/rpclinica/json/consulta/triagem/${idAgendamento}`, form)
            .then((res) => toastr['success'](res.data.message) )
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.saveLoadingTriagem = false);
    },

    submitAnamnese() {
        this.saveLoadingAnamnese = true;

        let form = new FormData(document.querySelector('#tabAnamnese'));
        form.set('anamnese', this.editorAnamnese?.getData());
        form.set('exame_fisico', this.editorExame?.getData());
        form.set('hipotese_diagnostica', this.editorHipotese?.getData());
        form.set('conduta', this.editorConduta?.getData());

        axios.post(`/rpclinica/json/consulta/anamnese/${idAgendamento}`, form)
            .then((res) => toastr['success'](res.data.message))
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.saveLoadingAnamnese = false);
    },

    submitListaProblemas() {
        this.saveLoadingProblemas = true;

        let form = new FormData(document.querySelector('#tabProblemas'));
        form.append('problemas', this.editorProblemas?.getData());

        axios.post(`/rpclinica/json/consulta/problemas/${idAgendamento}`, form)
            .then((res) => toastr['success'](res.data.message))
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.saveLoadingProblemas = false);
    },

    submitHistoricoExames() {
        this.saveLoadingExames = true;

        let form = new FormData(document.querySelector('#tabExames'));
        form.append('exames', this.editorHistoricoExames?.getData());

        axios.post(`/rpclinica/json/consulta/exames/${idAgendamento}`, form)
            .then((res) => toastr['success'](res.data.message) )
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.saveLoadingExames = false);
    },

    submitDocumentos() {
        this.saveLoadingDoc = true;

        let form = new FormData(document.querySelector('#formDocumento'));

        if (this.cdDocumentoEdicao !== null) {
            axios.put(`/rpclinica/json/consulta/documento/${this.cdDocumentoEdicao}`, {
                conteudo: this.editorDocumentos?.getData()
            })
            .then((res) => {
                toastr['success'](res.data.message);

                let indexDoc = this.documentos.findIndex((doc) => doc.cd_documento == this.cdDocumentoEdicao);
                this.documentos[indexDoc] = res.data.documento;

                this.limbarDocs();
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.saveLoadingDoc = false);

            return;
        }

        form.append('formulario', $('#formularios-documentos').val());
        form.append('conteudo', this.editorDocumentos?.getData());

        axios.post(`/rpclinica/json/consulta/documento/${idAgendamento}`, form)
            .then((res) => {
                toastr['success'](res.data.message);
                this.documentos.push(res.data.documento);

                this.limbarDocs();
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.saveLoadingDoc = false);
    },

    editDocumento(documento) {
        $('#editor-formularios-documentos').code(documento.conteudo);
        this.cdDocumentoEdicao = documento.cd_documento;
    },

    excluirDocumento(idDocumento, indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Deseja desbloquear esse horário?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.deleteDocIndice = indice

                axios.delete(`/rpclinica/json/consulta/documento/delete/${idDocumento}`)
                    .then((res) => {
                        toastr['success'](res.data.message);
                        this.documentos.splice(indice, 1);
                    })
                    .catch((err) => toastr['error'](err.response.data.message))
                    .finally(() => this.deleteDocIndice = null);
            }
        });
    },

    limbarDocs() {
        this.cdDocumentoEdicao = null;
        $('#editor-formularios-documentos').code(null);
        $('#formularios-documentos').val(null).trigger('change');
    },

    openModalHistoricoAgendamento(cdAgendamento) {

        this.historicoAgendamentoSelected = this.historicoAgendametos.find((agendamento) => agendamento.cd_agendamento == cdAgendamento)
        this.historicoPrintAgendamento = true;
    },

    encerrarConsulta(evt) {
        Swal.fire({
            title: 'Confirmação',
            text: "Deseja encerrar esse atendimento?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) location.href = `/rpclinica/consulta-finalizar/${idAgendamento}`;
        });
    },

    imprimirAnamnese() {
        let url = `/rpclinica/consulta/anamnese/download-pdf/${idAgendamento}`;

        axios.all([
            axios.get(url, { params: { tipo: 'anamnese' }, responseType: 'blob' })
            /*,
            axios.get(url, { params: { tipo: 'exame' }, responseType: 'blob' }),
            axios.get(url, { params: { tipo: 'hipotese' }, responseType: 'blob' }),
            axios.get(url, { params: { tipo: 'conduta' }, responseType: 'blob' })
            */
        ])
            .then(axios.spread((anamnese/*, exame, hipotese, conduta*/) => {
                window.open(URL.createObjectURL(anamnese.data), 'anamnese.pdf');
                /*window.open(URL.createObjectURL(exame.data), 'exame_fisico.pdf');
                window.open(URL.createObjectURL(hipotese.data), 'hipotese_diagnostica.pdf');
                window.open(URL.createObjectURL(conduta.data), 'conduta.pdf');
                */
            }))
            .catch((err) => toastr['error']('Houve um erro ao imprimir.'))
    },

    imprimirDocumento(cdDocumento) {
        axios.get(`/rpclinica/consulta/anamnese/download-pdf/${idAgendamento}`, {
            params: { tipo: 'documento', cdDocumento },
            responseType: 'blob'
        })
            .then((res) => {
                window.open(URL.createObjectURL(res.data), 'documento.pdf');
            })
            .catch((err) => toastr['error']('Houve um erro ao imprimir o documento!'));
    },

    getAnexos() {
        this.loadingAnexos = true;

        axios.get(`/rpclinica/json/agendamento-anexos/${idAgendamento}`)
            .then((res) => this.anexos = res.data)
            .catch((err) => toastr['error']('Erro ao buscar os anexos!'))
            .finally(() => this.loadingAnexos = false);
    },

    excluirAnexos(cdAnexo, indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Deseja excluir esse anexo?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.indiceDeleteAnexo = indice;

                axios.delete(`/rpclinica/json/agendamento-anexos/${cdAnexo}`)
                    .then((res) => {
                        toastr['success'](res.data.message);
                        this.anexos.splice(indice, 1);
                    })
                    .catch((err) => toastr['error'](err.response.data.message))
                    .finally(() => this.indiceDeleteAnexo = null);
            }
        });
    },

    viewAnexo(anexo) {
        if (anexo.tipo == 'pdf') return;

        $('#modal-viewer-anexo h4').text(anexo.nome);
        $('#modal-viewer-anexo img').attr('src', anexo.url_arquivo);

        $('#modal-viewer-anexo').modal('toggle');
    }
}));

$(document).ready(() => {
    $('#cid-triagem').select2({
        ajax: {
            url: '/rpclinica/json/select2/cid',
            dataType: 'json',
            processResults: (data) => {
                return {
                    results: data,
                    pagination: {
                        more: true
                    }
                };
            }
        }
    });

    $('#modal-viewer-anexo').modal({
        backdrop: 'static',
        show: false
    });
})
