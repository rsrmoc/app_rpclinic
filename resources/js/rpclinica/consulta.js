Alpine.data('app', () => ({
    saveLoadingTriagem: false,
    saveLoadingAnamnese: false,
    saveLoadingProblemas: false,
    saveLoadingExames: false,
    saveLoadingDoc: false,
    tipo_doc: null,

    deleteDocIndice: null,

    formularios: [],
    paciente: [],

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
    viewVip: false,

    swalWithBootstrapButtons : Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success swal-button",
          cancelButton: "btn btn-danger swal-button",
          input: "form-control"
        },
        buttonsStyling: false
    }),

    assinatura_digital:{
        situacao : docAssinado,
        conteudo : null,
    },

    historicoPaciente : null,

    init() {
        this.formularios = formularios;
        this.documentos = agendamentoDocumentos;
        this.historicoAgendametos = historicoAgentamentos;
        this.paciente = paciente;
        if(this.paciente.vip=='S'){ this.viewVip=true; }else{ this.viewVip=false; }

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
            .then((res) => { console.log(res); toastr['success'](res.data.message); })
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
            .finally(() => {

                this.saveLoadingDoc = false;
                this.cdDocumentoEdicao = null;
                this.editorDocumentos.setData(null);
                $('#tabDocumentos select#formularios-documentos').val(null).trigger('change');
                $('#editor-formularios-documentos').code(null);

            });

            return;
        }

        form.append('formulario', ( $('#formularios-documentos').val() ? $('#formularios-documentos').val() : '0' ) );
        form.append('conteudo', this.editorDocumentos?.getData());

        axios.post(`/rpclinica/json/consulta/documento/${idAgendamento}`, form)
            .then((res) => {

                if(!res.data.documento){
                    toastr['error'](res.data.message);
                }else{
                    toastr['success'](res.data.message);
                    this.documentos.push(res.data.documento);
                }

                this.limbarDocs();
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.saveLoadingDoc = false);
    },

    editDocumento(documento) {
        this.cdDocumentoEdicao = documento.cd_documento;
        $('#tabDocumentos select#formularios-documentos').val(documento.cd_formulario).trigger('change');
        $('#editor-formularios-documentos').code(documento.conteudo);
        this.editorDocumentos.setData(documento.conteudo)
       // this.cdDocumentoEdicao = documento.cd_documento;
    },

    excluirDocumento(idDocumento, indice) {
        this.swalWithBootstrapButtons.fire({
            title: 'Confirmação',
            html: "<h4 style='font-weight: 400;font-style: italic;'>Deseja Excluir esse Documento?</h4>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
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

    nl2br(str, replaceMode, isXhtml) {

        var breakTag = (isXhtml) ? '<br />' : '<br>';
        var replaceStr = (replaceMode) ? '$1'+ breakTag : '$1'+ breakTag +'$2';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
    },

    encerrarConsulta(evt) {
        this.swalWithBootstrapButtons.fire({
            title: 'Confirmação',
            html: "<h4 style='font-weight: 400;font-style: italic;'>Deseja encerrar esse atendimento?</h4>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) location.href = `/rpclinica/consulta-finalizar/${idAgendamento}`;
        });
    },

    imprimirAnamneseHist(cdAgenda){
        console.log(cdAgenda);

        let url = `/rpclinica/consulta/anamnese/download-pdf/${cdAgenda}`;

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

    saveLoadingdocPadrao(tipo) {


        this.swalWithBootstrapButtons.fire({
            title: 'Salvar Documento',
            icon: 'warning',
            html: "<h4 style='font-weight: 500;font-style: italic;'>Insira o nome do Documento!</h4>",
            input: "text",
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {


            var titulo = result.value;
            if(tipo=='CON'){
                let form = new FormData(document.querySelector('#tabAnamnese'));
                form.set('conduta', this.editorConduta?.getData());
                var conteudo = this.editorConduta?.getData();
            }
            if(tipo=='ATE'){
                let form = new FormData(document.querySelector('#tabAnamnese'));
                form.set('anamnese', this.editorAnamnese?.getData());
                var conteudo = this.editorAnamnese?.getData();
            }
            if(tipo=='EXA'){
                let form = new FormData(document.querySelector('#tabAnamnese'));
                form.set('exame_fisico', this.editorExame?.getData());
                var conteudo = this.editorExame?.getData();
            }
            if(tipo=='HIP'){
                let form = new FormData(document.querySelector('#tabAnamnese'));
                form.set('hipotese_diagnostica', this.editorHipotese?.getData());
                var conteudo = this.editorHipotese?.getData();
            }
            if(tipo=='DOC'){
                let form = new FormData(document.querySelector('#formDocumento'));
                form.set('hipotese_diagnostica', this.editorDocumentos?.getData());
                var conteudo = this.editorDocumentos?.getData();
            }

            axios.post(`/rpclinica/consulta/doc_padrao`, { conteudo: conteudo,titulo: titulo,  tipo: tipo })
            .then((res) => {
                toastr['success'](res.data.message);
            })
            .catch((err) => toastr['error']('Houve um erro ao imprimir o documento!'));
        });



    },

    imprimirAnamnese() {

        if($("#sn_header").is(':checked')){
            var sn_header = 'S';
        } else {
            var sn_header = 'N';
        }

        if($("#sn_footer").is(':checked')){
            var sn_footer = 'S';
        } else {
            var sn_footer = 'N';
        }

        if($("#sn_logo").is(':checked')){
            var sn_logo = 'S';
        } else {
            var sn_logo = 'N';
        }

        if($("#sn_data").is(':checked')){
            var sn_data = 'S';
        } else {
            var sn_data = 'N';
        }

        let url = `/rpclinica/consulta/anamnese/download-pdf/${idAgendamento}`;

        axios.all([
            axios.get(url, { params: { tipo: 'anamnese', header: sn_header,  logo: sn_logo, footer: sn_footer, data: sn_data, assinado: this.assinatura_digital.situacao }, responseType: 'blob' })
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

        if($("#sn_doc_header").is(':checked')){
            var sn_header = 'S';
        } else {
            var sn_header = 'N';
        }

        if($("#sn_doc_footer").is(':checked')){
            var sn_footer = 'S';
        } else {
            var sn_footer = 'N';
        }

        if($("#sn_doc_logo").is(':checked')){
            var sn_logo = 'S';
        } else {
            var sn_logo = 'N';
        }

        if($("#sn_doc_data").is(':checked')){
            var sn_data = 'S';
        } else {
            var sn_data = 'N';
        }

        if($("#sn_rec_especial").is(':checked')){
            var sn_especial = 'S';
        } else {
            var sn_especial = 'N';
        }

        if($("#sn_assinatura").is(':checked')){
            var sn_assinatura = 'S';
        } else {
            var sn_assinatura = 'N';
        }

        axios.get(`/rpclinica/consulta/anamnese/download-pdf/${idAgendamento}`, {
            params: { tipo: 'documento', cdDocumento, header: sn_header,  logo: sn_logo,
            footer: sn_footer, data: sn_data, especial: sn_especial, assinatura: sn_assinatura },
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
            .then((res) => {

                this.anexos = res.data.anexos;
                this.assinatura_digital.situacao=res.data.doc_assinado;
                //this.assinatura_digital.conteudo=res.data.doc_conteudo;

            })
            .catch((err) => toastr['error']('Erro ao buscar os anexos!'))
            .finally(() => this.loadingAnexos = false);
    },

    excluirAnexos(cdAnexo, indice) {
        this.swalWithBootstrapButtons.fire({
            title: 'Confirmação',
            html: "<h4 style='font-weight: 400;font-style: italic;'>Deseja excluir esse anexo?</h4>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
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
    },

    vip(status) {
        console.log(this.paciente.cd_paciente);
        axios.post(`/rpclinica/json/paciente-vip`, { status: status,paciente: this.paciente.cd_paciente } )
        .then((res) => {
            this.paciente = res.data;
            if(res.data.vip=='S'){ this.viewVip=true; }else{ this.viewVip=false; }


        })
        .catch((err) => toastr['error']('Erro ao vincular Paciente como VIP!'));
    },

    exluirDocAssinado(){

        this.swalWithBootstrapButtons.fire({
            title: 'Atenção!',
            html: "<h4 style='font-weight: 500;font-style: italic;'>Deseja excluir esse documento?</h4>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: ' Sim',
        })

        .then((result) => {
            if (result.isConfirmed) {

                axios.delete(`/rpclinica/json/assinatura-delete/${idAgendamento}/anamnese/1 `)
                .then((res) => {
                    console.log(res);

                    if(res.data.retorno==true){
                        this.assinatura_digital.situacao=false;

                        var Title = "Sucesso!";
                        var Html = "<h4 style='font-weight: 400;font-style: italic;'>Documento excluido com sucesso!</h4>";
                        var Icon = "success";
                    }else{

                        var Title = "Atenção!";
                        var Html = "<h4 style='font-weight: 400;font-style: italic;'>Erro ao excluir o documento!</h4>";
                        var Icon = "error";

                    }

                    this.swalWithBootstrapButtons.fire({
                        title: Title,
                        html: Html,
                        icon: Icon,
                        showCancelButton: false,
                        confirmButtonColor: '#22baa0',
                        confirmButtonText: ' Ok ',
                    });

                })
                .catch((err) => {
                    toastr['error'](err.response.data.message)
                })

            }
        });

    },

    assinarDoc(tipo,codDoc){
        var DsHtml = "<h4 style='font-weight: 400;font-style: italic;'>Para assinar esse documento é necessario entrar com a senha do certificado!</h4> ";
        if(tipo=='D'){
            DsHtml = "<h4 style='font-weight: 400;font-style: italic;'>Para assinar esse documento é necessario entrar com a senha do certificado!</h4> <label style='font-size: 1.3em; font-weight: 600;' > <input type='checkbox' id='AssRecEsp'  name='vehicle3' value='S'>   Receituário Especial?   </label> ";
        }

        this.swalWithBootstrapButtons.fire({
            title: 'Assinatura Digital',
            html: DsHtml,
            icon: 'warning',
            input: "password",
            showCancelButton: false,
            confirmButtonColor: '#22baa0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: '  <i class="fa fa-thumbs-up"></i> Assinar Documento',
        })

        .then((result) => {

            if (result.isConfirmed) {

                if(!result.value){
                    this.swalWithBootstrapButtons.fire("Atenção!", "Senha não informada", "error");
                    return false;
                }

                var RecEsp = null;
                var tp_doc= null;
                if(tipo=='A'){ tp_doc='anamnese'; codDoc = null; }
                if(tipo=='D'){
                    tp_doc='documento';
                    if ($('#AssRecEsp').is(":checked"))
                    {
                        RecEsp = 'S';
                    }else{
                        RecEsp = 'N';
                    }
                }

                /*
                axios.post(`/rpclinica/json/assinatura-digital`,{ tipo: tp_doc, senha: result.value, agendamento: idAgendamento, codigo: codDoc, RecEsp : RecEsp })
                */

                axios.get(`/rpclinica/consulta/anamnese/download-pdf/${idAgendamento}`, {
                    params: { tipo: tp_doc, cdDocumento: codDoc, header: 'N', logo: 'N',
                    footer: 'N', data: 'N',especial: RecEsp, assinatura: 'N',
                    assinar_digital:'S',senha: result.value } })

                .then((res) => {
                    console.log(res);
                    if(tipo=='A'){
                        this.assinatura_digital.situacao=res.data.sn_assinado;
                        this.assinatura_digital.conteudo=res.data.conteudo;
                    }


                    if(res.data.retorno==true){
                        if(res.data.sn_assinado==true){
                            var Title = "Sucesso!";
                            var Html = "<h4 style='font-weight: 400;font-style: italic;'>Documento assinado com sucesso!</h4>";
                            var Icon = "success";
                        }else{
                            var Title = "Atenção!";
                            var Html = "<h4 style='font-weight: 400;font-style: italic;'>Erro ao assinar o documento!</h4>";
                            var Icon = "error";
                        }
                        if(tipo=='D'){
                            this.documentos = res.data.documentos;
                        }
                    }else{
                        var Title = "Atenção!";
                        var Html = "<h4 style='font-weight: 400;font-style: italic;'>Erro ao salvar informação!</h4>";
                        var Icon = "error";
                        toastr['error'](res.data.msg)
                    }
                    this.swalWithBootstrapButtons.fire({
                        title: Title,
                        html: Html,
                        icon: Icon,
                        showCancelButton: false,
                        confirmButtonColor: '#22baa0',
                        confirmButtonText: ' Ok ',
                    });
                })
                .catch((err) => {
                    toastr['error'](err.response.data.message)
                })
                //.finally(() => this.indiceDeleteAnexo = null);

            }
        });
    },

    historicoDoc(){
        //alert(idAgendamento);

        axios.get(`/rpclinica/json/historico-documento?agendamento=`+idAgendamento )
        .then((res) => {
            console.log(res);
            this.historicoPaciente = res.data.retorno;
        })
        .catch((err) => {
            toastr['error'](err.response.data.message)
        })
        .finally(() => this.indiceDeleteAnexo = null);

        $('#historico-documentos').modal('toggle');
    },

    importarDoc(doc){

        this.cdDocumentoEdicao = null;
        $('#tabDocumentos select#formularios-documentos').val(doc.cd_formulario).trigger('change');
        $('#editor-formularios-documentos').code(doc.conteudo);
        this.editorDocumentos.setData(doc.conteudo);
        $('#historico-documentos').modal('hide');

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
