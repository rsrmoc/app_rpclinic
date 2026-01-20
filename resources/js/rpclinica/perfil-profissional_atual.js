Alpine.data('appConfiguracao', () => ({
    loadingConfig: false,

    cdProfissional: cdProfissional,
    tipoDoc:'DOC',
    deleteAssinatura() {

        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir essa assinatura?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) { 
                
                axios.delete(`/rpclinica/profissional-assinatura/${this.cdProfissional}`)
                .then((res) => {
                    $("#img_assinatura").remove();
                    toastr["success"]('Formulario excluido com sucesso!');
                })
                .catch((err) => toastr["error"]('Não foi possivel excluir a imagem!')) 
            }
        });


    },
    

}));

Alpine.data('appFormulario', () => ({
    inputsFormulario: {
        nome: null,
        tipo_formulario: null,
        especialidade: null,
        conteudo: null,
        exame: null,
        hipotese: null,
        conduta: null,
    },
    loadingSubmitFormulario: false,
    tiposFormulario: {
        ATE: 'Atendimentos/Anammnese',
        CON: 'Conduta',
        DOC: 'Documentos',
        EXA: 'Exame Fisico',
        RIP: 'Hipótese Diagnóstica'
    },
    formularios: [],
    indiceFormularioDelete: null,
    documentosPadrao: [],
    tipo_formulario: 'DOC',
    editor: null,
    editorExame: null,
    editorHipotese: null,
    editorConduta: null,

    cdProfissional: cdProfissional, 

    init() {
       

        this.editor = CKEDITOR.replace('conteudo_formulario', { 
            toolbarGroups: [
                {
                    "name": "basicstyles",
                    "groups": ["basicstyles"]
                }, 
                {
                    "name": "undo",
                    "groups": ["Undo","Redo"]
                }, 
                {
                    "name": "paragraph",
                    "groups": ["list", "blocks" ]
                },  
                {
                    "name": "insert",
                    "groups": ["insert"]
                },
                {
                    "name": "styles",
                    "groups": ["styles"]
                } 
            ],
            
            // Remove the redundant buttons from toolbar groups defined above.
            removeButtons: 'Subscript,Superscript,',
            resize_enabled : false,
            removePlugins: 'elementspath',
            height:['300px'],
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });
        this.editorExame = CKEDITOR.replace('conteudo_exame', { 
            toolbarGroups: [
                {
                    "name": "basicstyles",
                    "groups": ["basicstyles"]
                }, 
                {
                    "name": "undo",
                    "groups": ["Undo","Redo"]
                }, 
                {
                    "name": "paragraph",
                    "groups": ["list", "blocks" ]
                },  
                {
                    "name": "insert",
                    "groups": ["insert"]
                },
                {
                    "name": "styles",
                    "groups": ["styles"]
                } 
            ],
            
            // Remove the redundant buttons from toolbar groups defined above.
            removeButtons: 'Subscript,Superscript,',
            resize_enabled : false,
            removePlugins: 'elementspath',
            height:['200px'],
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });
        this.editorHipotese = CKEDITOR.replace('conteudo_hipotese', { 
            toolbarGroups: [
                {
                    "name": "basicstyles",
                    "groups": ["basicstyles"]
                }, 
                {
                    "name": "undo",
                    "groups": ["Undo","Redo"]
                }, 
                {
                    "name": "paragraph",
                    "groups": ["list", "blocks" ]
                },  
                {
                    "name": "insert",
                    "groups": ["insert"]
                },
                {
                    "name": "styles",
                    "groups": ["styles"]
                } 
            ],
            
            // Remove the redundant buttons from toolbar groups defined above.
            removeButtons: 'Subscript,Superscript,',
            resize_enabled : false,
            removePlugins: 'elementspath',
            height:['200px'],
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });
        this.editorConduta = CKEDITOR.replace('conteudo_conduta', { 
            toolbarGroups: [
                {
                    "name": "basicstyles",
                    "groups": ["basicstyles"]
                }, 
                {
                    "name": "undo",
                    "groups": ["Undo","Redo"]
                }, 
                {
                    "name": "paragraph",
                    "groups": ["list", "blocks" ]
                },  
                {
                    "name": "insert",
                    "groups": ["insert"]
                },
                {
                    "name": "styles",
                    "groups": ["styles"]
                } 
            ],
            
            // Remove the redundant buttons from toolbar groups defined above.
            removeButtons: 'Subscript,Superscript,',
            resize_enabled : false,
            removePlugins: 'elementspath',
            height:['200px'],
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });

        this.formularios = formularios;
        this.documentosPadrao = documentosPadrao;
 
        $('#tipo_formulario').on('select2:select', (evt) => {
            this.inputsFormulario.tipo_formulario = evt.params.data.id;
            this.tipo_formulario = evt.params.data.id; 
        })

        $('#especialidade-formulario').on('select2:select', (evt) => {
            this.inputsFormulario.especialidade = evt.params.data.id
        })
 
        
    },
  
    deleteAssinatura() {

        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir essa assinatura?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                location.href = "https://www.w3schools.com";
                axios.delete(`/rpclinica/profissional-assinatura/${this.cdProfissional}`)
                .then((res) => {
                    $("#img_assinatura").remove();
                    toastr["success"]('Formulario excluido com sucesso!');
                })
                .catch((err) => toastr["error"]('Não foi possivel excluir a imagem!')) 
            }
        });


    },
 
    clearFormulario() {
        this.inputsFormulario = {
            nome: null,
            tipo_formulario: null,
            especialidade: null,
            conteudo: null,
            sn_header: null
        };

        $('#tipo_formulario').val(null).trigger('change');
        $('#especialidade-formulario').val(null).trigger('change');

        this.editor?.setData(null);
    },

    setEditFormulario(formulario) {

        this.inputsFormulario.cd_formulario = formulario.cd_formulario; 
        this.inputsFormulario.nome = formulario.nm_formulario; 

        $('#tipo_formulario').val(formulario.tp_formulario).trigger('change'); 
        this.inputsFormulario.tipo_formulario = formulario.tp_formulario;  

        this.tipo_formulario = formulario.tp_formulario; 

        this.editor?.setData(formulario.conteudo); 
        this.inputsFormulario.conteudo = formulario.conteudo; 

        this.editorExame?.setData(formulario.exame); 
        this.inputsFormulario.exame = formulario.exame; 
        
        this.editorHipotese?.setData(formulario.hipotese); 
        this.inputsFormulario.hipotese = formulario.hipotese; 
        
        this.editorConduta?.setData(formulario.conduta); 
        this.inputsFormulario.conduta = formulario.conduta; 

        window.scrollTo(0, 0);
    },

    formularioSubmit() {
        this.inputsFormulario.conteudo = this.editor?.getData(); 
        this.inputsFormulario.exame = this.editorExame?.getData(); 
        this.inputsFormulario.conduta = this.editorConduta?.getData(); 
        this.inputsFormulario.hipotese = this.editorHipotese?.getData(); 
        let form = new FormData(document.querySelector('#formulario-texto'));
        var conteudoExame =  this.editor?.getData();
        form.set('conteudo', conteudoExame);  
        var Exame =  this.editorExame?.getData();
        form.set('exame', Exame); 
        var Conduta =  this.editorConduta?.getData();
        form.set('conduta', Conduta); 
        var Hipotese =  this.editorHipotese?.getData();
        form.set('hipotese', Hipotese); 

        this.loadingSubmitFormulario = true;
        console.log(this.inputsFormulario);
           
        if (this.inputsFormulario.cd_formulario) {
            //form.set('cd_formulario', this.inputsFormulario.cd_formulario); 
            axios.put(`/rpclinica/json/perfil-profissional-formulario-update`, this.inputsFormulario)
                .then((res) => { 
                    
                    let indexFormulario = this.formularios.findIndex((formulario) => formulario.cd_formulario == this.inputsFormulario.cd_formulario);
                    this.formularios[indexFormulario] = res.data.formulario;
                    document.getElementById("formulario-texto").reset();
                    $('#tipo_formulario').val(null).trigger('change'); 
                    this.tipo_formulario='DOC';
                    toastr['success'](res.data.message);
                    this.clearFormulario();
                })
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => this.loadingSubmitFormulario = false)

            return;
        }
        
      
   
        axios.post('/rpclinica/json/perfil-profissional-formulario-create', form)
            .then((res) => {
                
                //this.formularios = res.data.formularios;
                this.formularios.push(res.data.formulario);
                document.getElementById("formulario-texto").reset();
                $('#tipo_formulario').val(null).trigger('change'); 
                this.tipo_formulario='DOC';
                toastr['success'](res.data.message);
                this.clearFormulario();
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingSubmitFormulario = false)
    },

    deleteFormulario(cdFormulario, indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse formulario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.indiceFormularioDelete = indice;

                axios.delete(`/rpclinica/json/perfil-profissional-formulario-delete/${cdFormulario}`)
                    .then((res) => {
                        this.formularios.splice(indice, 1);
                        toastr["success"]('Formulario excluido com sucesso!');
                        document.getElementById("formulario-texto").reset();
                        $('#tipo_formulario').val(null).trigger('change'); 
                        this.tipo_formulario='DOC';
                    })
                    .catch((err) => toastr["error"]('Não foi possivel excluir o formulario!'))
                    .finally(() => this.indiceFormularioDelete = null);
            }
        });
    },

    async copyText(text) {
        try {
            await navigator.clipboard.writeText(text);
            toastr['success']('Copiado!');
        }
        catch (err) {
            toastr['error'](`Não foi possivel copiar. Erro: ${ err }`);
        }
    },

    copyDocumento(documento) {
        this.inputsFormulario.nome = documento.nm_documento;
        this.editor?.setData(documento.conteudo);

        window.scrollTo(0, 0);
    }

}));
 
Alpine.data('appProcedimetos', () => ({
    inputsProcedimento: {
        procedimento: null,
        convenio: null,
        valor: null
    },
    loadingSubmitProcedimento: false,
    procedimentosProfissional: [],
    indiceProcedimentoDelete: null,

    init() {
        this.procedimentosProfissional = procedimentosProfissional;

        $('#procedimento').on('select2:select', (evt) => this.inputsProcedimento.procedimento = evt.params.data.id)
        $('#convenio').on('select2:select', (evt) => this.inputsProcedimento.convenio = evt.params.data.id)
    },

    clear() {
        this.inputsProcedimento = {
            procedimento: null,
            convenio: null,
            valor: null
        };

        $('#procedimento').val(null).trigger('change');
        $('#convenio').val(null).trigger('change');
    },

    setProcedimentoEdit(procedimento) {
        this.inputsProcedimento.cd_proc_prof = procedimento.cd_proc_prof;
        this.inputsProcedimento.procedimento = procedimento.cd_proc;
        this.inputsProcedimento.convenio = procedimento.cd_convenio;
        this.inputsProcedimento.valor = procedimento.vl_proc;

        $('#procedimento').val(procedimento.cd_proc).trigger('change');
        $('#convenio').val(procedimento.cd_convenio).trigger('change');
    },

    procedimentoSubmit() {
        this.loadingSubmitProcedimento = true;

        if (this.inputsProcedimento.cd_proc_prof) {
            axios.put('/rpclinica/json/perfil-profissional-procedimento-update', this.inputsProcedimento)
                .then((res) => {
                    let indexProcedimento = this.procedimentosProfissional.findIndex((procedimento) => procedimento.cd_proc_prof == this.inputsProcedimento.cd_proc_prof);
                    this.procedimentosProfissional[indexProcedimento] = res.data.procedimento;

                    toastr['success'](res.data.message);
                    this.clear();
                })
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => this.loadingSubmitProcedimento = false)

            return;
        }

        axios.post('/rpclinica/json/perfil-profissional-procedimento-create', this.inputsProcedimento)
            .then((res) => {
                this.procedimentosProfissional.push(res.data.procedimento);
                toastr['success'](res.data.message);
                this.clear();
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingSubmitProcedimento = false)
    },

    deleteProcedimento(cdProcProf, indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse procedimento?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.indiceProcedimentoDelete = indice;

                axios.post(`/rpclinica/profissional-delete-procedimento/${cdProcProf}`)
                    .then((res) => {
                        this.procedimentosProfissional.splice(indice, 1);
                        toastr["success"]('Procedimento excluido com sucesso!');
                    })
                    .catch((err) => toastr["error"]('Não foi possivel excluir o procedimento!'))
                    .finally(() => this.indiceProcedimentoDelete = null);
            }
        });
    }
}));

Alpine.data('appEspecialidades', () => ({
    inputsEspecialidade: {
        especialidade: null,
        compartilha: null
    },
    loadingSubmitEspecialidade: false,
    especialidadesProfissional: [],
    indiceEspecialidadeDelete: null,

    init() {
        this.especialidadesProfissional = especialidadesProfissional;

        $('#especialidade').on('select2:select', (evt) => this.inputsEspecialidade.especialidade = evt.params.data.id)
        $('#compartilha').on('select2:select', (evt) => this.inputsEspecialidade.compartilha = evt.params.data.id)
    },

    clear() {
        this.inputsEspecialidade = {
            especialidade: null,
            compartilha: null
        };

        $('#especialidade').val(null).trigger('change');
        $('#compartilha').val(null).trigger('change');
    },

    setEspecialidadeEdit(especialidade) {
        this.inputsEspecialidade.cd_prof_espec = especialidade.cd_prof_espec;
        this.inputsEspecialidade.especialidade = especialidade.cd_especialidade;
        this.inputsEspecialidade.compartilha = especialidade.sn_compartilha;

        $('#especialidade').val(especialidade.cd_especialidade).trigger('change');
        $('#compartilha').val(especialidade.sn_compartilha).trigger('change');
    },

    submitEspecialidade()  {
        this.loadingSubmitEspecialidade = true;

        if (this.inputsEspecialidade.cd_prof_espec) {
            axios.put('/rpclinica/json/perfil-profissional-especialidade-update', this.inputsEspecialidade)
                .then((res) => {
                    let indexEspecialidade = this.especialidadesProfissional.findIndex((especialidade) => especialidade.cd_prof_espec == this.inputsEspecialidade.cd_prof_espec);
                    this.especialidadesProfissional[indexEspecialidade] = res.data.especialidade;

                    toastr['success'](res.data.message);
                    this.clear();
                })
                .catch((err) => toastr['error'](err.response.data.message))
                .finally(() => this.loadingSubmitEspecialidade = false)

            return;
        }
        axios.post('/rpclinica/json/perfil-profissional-especialidade-create', this.inputsEspecialidade)
            .then((res) => {
                this.especialidadesProfissional.push(res.data.especialidade);
                toastr['success'](res.data.message);
                this.clear();
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingSubmitEspecialidade = false)
    },

    deleteEspecialidade(cdProcEspec, indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse especialidade?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.indiceEspecialidadeDelete = indice;

                axios.post(`/rpclinica/profissional-delete-especialidade/${cdProcEspec}`)
                    .then((res) => {
                        this.especialidadesProfissional.splice(indice, 1);
                        toastr["success"]('Especialidade excluido com sucesso!');
                    })
                    .catch((err) => toastr["error"]('Não foi possivel excluir o especialidade!'))
                    .finally(() => this.indiceEspecialidadeDelete = null);
            }
        });
    }
}));
 
Alpine.data('appCertificado', () => ({

    inputsForms: {
        tipo: null,
    },

    init() {

    },

    deleteCertificado(){
        Swal.fire({
            title: 'Confirmação',
            text: "Deseja excluir o certificado?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {

                location.href = "perfil-prof-del-certificado";

            }
        });
    },

}));
