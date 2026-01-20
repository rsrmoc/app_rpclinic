import axios from 'axios';
import moment from 'moment';

Alpine.data('appConsultorioGeral', () => ({
    
    telaAtiva: null,
    snEdicaoDoc: false,
    iconHistry: "<i class='fa fa-list'></i>",
    iconAssinaturaDigital: '&nbsp;&nbsp;  <i class="fa fa-key" data-placement="top" data-toggle="tooltip" data-original-title="Assinatura Digital" style="color:#33BBF3; font-weight: bold;"></i> ',
    conteudo: '<div class="col-md-7"><div style="text-align: center;"> <img src="/assets/images/oftalmo.png" > </div> </div>',
    conteudoCarregando: '<div class="col-md-7"><div style="text-align: center;"> <img style="height: 80px;margin-top: 100px;" src="/assets/images/carregandoFormulario.gif" > </div> </div>',
    conteudoCarregandoModal: '<div class="col-md-12"><div style="text-align: center;"> <img style="height: 80px;margin-top: 100px;margin-bottom: 60px;" src="/assets/images/carregandoFormulario.gif" > </div> </div>',
    loading: false,
    loadingCertificado: false,
    dadosCertificado: [],
    loadingDoc: false,
    loadingForm: false,
    buttonSalvar: " <i class='fa fa-check'></i>  Salvar ",
    buttonUpload: '<i class="fa fa-upload" style="margin-right: 6px;"></i> Salvar',
    buttonDelete: '<i class="fa fa-trash"></i> Exluir Certificado',
    buttonDisabled: false,
    buttonModelo: '&nbsp;<span aria-hidden="true" class="icon-tag"></span>&nbsp;&nbsp; Salvar Modelo&nbsp;',
    buttonModeloDisabled: false,
    modalTitulo: "",
    modalConteudo: "",
    modeloAnamnese: [],
    modeloDocumento: [],
    dadosAgendamento: null,
    dadosDocumentos: null,
    nrDocumentos: 0,
    dadosAnotacao: null,
    nrAnotacao: 0,
    Anamnese:{
        Historia: null,
        Anamnese: null,
        Exame: null,
        Hipotese: null,
        Conduta: null, 
        Titulo: null, 
        Documento: null,
        cdDocumento: null,
        tituloDoc: null,
        Anotacao: null,
        historico_exames: [],
        historico_arquivos: [],
        modal_arquivo: null,
        loadFile: false,
        loadExame: false,
        loadHist: false, 
    },

    view: {
        historiaPregressa: (viewHistoriaPregressa=='S') ? false : true,
        anamnese: (viewAnamnese=='S') ? false : true,
        exameFisico: (viewExameFisico=='S') ? false : true,
        hipoteseDiag: (viewHipoteseDiag=='S') ? false : true,
        conduta: (viewConduta=='S') ? false : true,
    },
 
    history: {
        Anamnese: [],
        Documento: [],
        Geral: []
    },
    nrHistory: 0,
    Modal: [],
    ConfigPerfil:[],
    texto_padrao:[],
    texto_padrao_tipo: null,
    texto_padrao_titulo: '...',
    header_padrao_titulo: '',
    form_texto_padrao:{
        codigo: null,
        conteudo: null,
        titulo: ''
    },
    editorDocumento: null,
    editorModeloDocumento: null,
    editorModeloAnamnese: null,
    editorModeloPregressa: null, 
    editorModeloExame: null,
    editorModeloHipotese : null,
    editorModeloConduta : null,

    swalWithBootstrapButtons : Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success swal-button",
          cancelButton: "btn btn-danger swal-button",
          input: "form-control"
        },
        buttonsStyling: false
    }),

    init() {

        this.editorDocumento = CKEDITOR.replace('editor-formulario-documento', {
            // Define the toolbar groups as it is a more accessible solution.
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
            height:['350px'],
            resize_enabled : false,
            removePlugins: 'elementspath',
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });
         
        this.editorModeloDocumento = CKEDITOR.replace('editor-modelo-documento', {
            // Define the toolbar groups as it is a more accessible solution.
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
                /*
                {
                    "name": "document",
                    "groups": ["mode"]
                },
                */
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
  
        this.editorModeloAnamnese = CKEDITOR.replace('editor-formulario-anamnese', {
            // Define the toolbar groups as it is a more accessible solution.
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
            height:['400px'],
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
        });
      
        this.editorModeloPregressa = CKEDITOR.replace('editor-formulario-pregressa', {
            // Define the toolbar groups as it is a more accessible solution.
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

        this.editorModeloExame = CKEDITOR.replace('editor-formulario-exame', {
            // Define the toolbar groups as it is a more accessible solution.
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
            height:['150px'],
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
     
        });
 
        
        this.editorModeloHipotese = CKEDITOR.replace('editor-formulario-hipotese', {
            // Define the toolbar groups as it is a more accessible solution.
           
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
                "groups":  ['list', 'indent', 'blocks', 'align', 'bidi']
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
            height:['150px'],
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
     
        });
        
        this.editorModeloConduta = CKEDITOR.replace('editor-formulario-conduta', {
            // Define the toolbar groups as it is a more accessible solution.
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
            height:['150px'],
            enterMode: CKEDITOR.ENTER_BR, // Define Enter como <br>
            shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>
     
        });


        this.getPagina();
  
        $('#modeloAnamnese').on('select2:select', (evt) => { 
            var idForm = evt.params.data.id;  
            var filtrado = this.modeloAnamnese.filter(function(obj) { return obj.cd_formulario == idForm; });
            if(filtrado[0]){  
                this.Anamnese.Anamnese=filtrado[0].conteudo;
                this.Anamnese.Exame=filtrado[0].exame;
                this.Anamnese.Hipotese=filtrado[0].hipotese;
                this.Anamnese.Conduta=filtrado[0].conduta;
                this.editorModeloAnamnese.setData(filtrado[0].conteudo); 
                this.editorModeloExame.setData(filtrado[0].exame); 
                this.editorModeloHipotese.setData(filtrado[0].hipotese); 
                this.editorModeloConduta.setData(filtrado[0].conduta); 
            }
        });

        $('#modeloDocumento').on('select2:select', (evt) => { 
            var idForm = evt.params.data.id;   
            var filtrado = this.modeloDocumento.filter(function(obj) { return obj.cd_formulario == idForm; });
            if(filtrado[0]){ 
                this.Anamnese.Documento=filtrado[0].conteudo; 
                this.Anamnese.Titulo=filtrado[0].nm_formulario; 
                this.editorDocumento.setData(filtrado[0].conteudo); 
            } 
            
            
        }); 
 
    },
 
    getPagina() {
        this.loadingDoc=true; 
        this.Anamnese.loadFile=true;
        this.Anamnese.loadExame=true;
        this.Anamnese.loadHist=true;
        axios.get(`/rpclinica/json/getDados/${idAgendamento}`)
        .then((res) => {

            this.Anamnese.Anamnese = res.data.agendamento.anamnese 
            this.editorModeloAnamnese?.setData(this.Anamnese.Anamnese); 
            this.Anamnese.Exame = res.data.agendamento.exame_fisico
            this.editorModeloExame?.setData(this.Anamnese.Exame);  
            this.Anamnese.Hipotese = res.data.agendamento.hipotese_diagnostica;
            this.editorModeloHipotese.setData(this.Anamnese.Hipotese); 
            this.Anamnese.Conduta = res.data.agendamento.conduta;
            this.editorModeloConduta.setData(this.Anamnese.Conduta); 
            this.Anamnese.Historia=res.data.historia_pregressa;
            this.editorModeloPregressa?.setData(this.Anamnese.Historia);  
            this.dadosAgendamento = res.data.agendamento;
            this.dadosDocumentos = res.data.agendamento.documentos;
            this.Anamnese.historico_exames=res.data.historico.exame;
            this.Anamnese.historico_arquivos=res.data.historico.arquivo;
            this.dadosCertificado= res.data.certificado;
            if(this.dadosCertificado){
                this.loadingCertificado=true;
            }else{
                this.loadingCertificado=false;
            }

            if(this.dadosDocumentos){
                this.nrDocumentos = this.dadosDocumentos.length;
            }else{
                this.nrDocumentos = 0;
            } 
            this.dadosAnotacao = res.data.anotacao;
            if(this.dadosAnotacao){
                this.nrAnotacao = this.dadosAnotacao.length;
            }else{
                this.nrAnotacao = 0;
            } 
            this.modeloAnamnese = res.data.request.modeloAnam;
            this.modeloDocumento = res.data.request.modeloDoc;
            this.history.Anamnese = res.data.historico.anamnese;
            this.history.Documento = res.data.historico.documento;
            this.history.Geral = res.data.historico.geral; 
            if(this.history.Geral){
                this.nrHistory = this.history.Geral.length;
            }else{
                this.nrHistory = 0;
            }

            this.ConfigPerfil=res.data.config; 
            this.PerfilProf();  

        })
        .catch((err) => {
            toastr['error'](err.response.data.message, 'Erro');
        }) 
        .finally(() => {
            this.loadingDoc=false; 
            this.Anamnese.loadFile=false;
            this.Anamnese.loadExame=false;
            this.Anamnese.loadHist=false;
        });
    },

    PerfilProf(){

        if(this.ConfigPerfil?.sn_carregar_historia_pregressa){
            $('#carregar_historia_pregressa span').addClass('checked'); 
            $('#carregar_historia_pregressa input').prop('checked', true);
        }else{
            $('#carregar_historia_pregressa span').removeClass('checked'); 
            $('#carregar_historia_pregressa input').prop('checked', false);
        }

        if(this.ConfigPerfil?.sn_historia_pregressa){
            $('#sn_historia_pregressa span').addClass('checked'); 
            $('#sn_historia_pregressa input').prop('checked', true);
        }else{
            $('#sn_historia_pregressa span').removeClass('checked'); 
            $('#sn_historia_pregressa input').prop('checked', false);
        }

        if(this.ConfigPerfil?.sn_anamnese){
            $('#sn_anamnese span').addClass('checked'); 
            $('#sn_anamnese input').prop('checked', true);
        }else{
            $('#sn_anamnese span').removeClass('checked'); 
            $('#sn_anamnese input').prop('checked', false);
        }

        if(this.ConfigPerfil?.sn_exame_fisico){
            $('#sn_exame_fisico span').addClass('checked'); 
            $('#sn_exame_fisico input').prop('checked', true);
        }else{
            $('#sn_exame_fisico span').removeClass('checked'); 
            $('#sn_exame_fisico input').prop('checked', false);
        }

        if(this.ConfigPerfil?.sn_hipotese_diag){
            $('#sn_hipotese_diag span').addClass('checked'); 
            $('#sn_hipotese_diag input').prop('checked', true);
        }else{
            $('#sn_hipotese_diag span').removeClass('checked'); 
            $('#sn_hipotese_diag input').prop('checked', false);
        }
         
        if(this.ConfigPerfil?.sn_conduta){
            $('#sn_conduta span').addClass('checked'); 
            $('#sn_conduta input').prop('checked', true);
        }else{
            $('#sn_conduta span').removeClass('checked'); 
            $('#sn_conduta input').prop('checked', false);
        }

        if(this.ConfigPerfil?.sn_conduta){
            $('#sn_conduta span').addClass('checked'); 
            $('#sn_conduta input').prop('checked', true);
        }else{
            $('#sn_conduta span').removeClass('checked'); 
            $('#sn_conduta input').prop('checked', false);
        }

        if(this.ConfigPerfil?.sn_data_header_doc){
            $('#sn_data_header_doc span').addClass('checked'); 
            $('#sn_data_header_doc input').prop('checked', true);
        }else{
            $('#sn_data_header_doc span').removeClass('checked'); 
            $('#sn_data_header_doc input').prop('checked', false);
        }

        if(this.ConfigPerfil?.sn_header_doc){
            $('#sn_header_doc span').addClass('checked'); 
            $('#sn_header_doc input').prop('checked', true);
        }else{
            $('#sn_header_doc span').removeClass('checked'); 
            $('#sn_header_doc input').prop('checked', false);
        }

        if(this.ConfigPerfil?.sn_logo_header_doc){
            $('#sn_logo_header_doc span').addClass('checked'); 
            $('#sn_logo_header_doc input').prop('checked', true);
        }else{
            $('#sn_logo_header_doc span').removeClass('checked'); 
            $('#sn_logo_header_doc input').prop('checked', false);
        }
        
        if(this.ConfigPerfil?.sn_footer_header_doc){
            $('#sn_footer_header_doc span').addClass('checked'); 
            $('#sn_footer_header_doc input').prop('checked', true);
        }else{
            $('#sn_footer_header_doc span').removeClass('checked'); 
            $('#sn_footer_header_doc input').prop('checked', false);
        }
        
        if(this.ConfigPerfil?.sn_assina_header_doc){
            $('#sn_assina_header_doc span').addClass('checked'); 
            $('#sn_assina_header_doc input').prop('checked', true);
        }else{
            $('#sn_assina_header_doc span').removeClass('checked'); 
            $('#sn_assina_header_doc input').prop('checked', false);
        }

        if(this.ConfigPerfil?.sn_titulo_header_doc){
            $('#sn_titulo_header_doc span').addClass('checked'); 
            $('#sn_titulo_header_doc input').prop('checked', true);
        }else{
            $('#sn_titulo_header_doc span').removeClass('checked'); 
            $('#sn_titulo_header_doc input').prop('checked', false);
        }
    },
 
    /* Anamnese */
    storeAnamnese() {
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_ANAMNESE')); 

        var conteudoAnamnese = (this.view.anamnese) ? this.editorModeloAnamnese?.getData() : '';
        form.set('anamnese', conteudoAnamnese); 
        this.Anamnese.Anamnese= conteudoAnamnese;

        var conteudoPregressa = (this.view.historiaPregressa) ? this.editorModeloPregressa?.getData() : '';
        form.set('historia_pregressa', conteudoPregressa); 
        this.Anamnese.Historia= conteudoPregressa;
        
        var conteudoExame = (this.view.exameFisico) ? this.editorModeloExame?.getData() : '';
        form.set('exame_fisico', conteudoExame); 
        this.Anamnese.Exame= conteudoExame;

        var conteudoExame = (this.view.hipoteseDiag) ? this.editorModeloHipotese?.getData() : '';
        form.set('hipotese_diagnostica', conteudoExame); 
        this.Anamnese.Hipotese= conteudoExame;

        var conteudoConduta= (this.view.conduta) ? this.editorModeloConduta?.getData() : '';
        form.set('conduta', conteudoConduta); 
        this.Anamnese.Conduta= conteudoConduta;

        axios.post(`/rpclinica/json/storeAnamneseGeral/${idAgendamento}`, form)
            .then((res) => { 
                this.Anamnese.Anamnese = res.data.agendamento.anamnese
                this.Anamnese.Exame = res.data.agendamento.exame_fisico
                this.Anamnese.Hipotese = res.data.agendamento.hipotese_diagnostica
                this.Anamnese.Conduta = res.data.agendamento.conduta
                this.dadosAgendamento = res.data.agendamento;
                this.editorModeloAnamnese?.setData(this.Anamnese.Anamnese); 
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " <i class='fa fa-check'></i>  Salvar ";
            });
    }, 

    /* Config Perfil */
    storeConfigPerfil() {

        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_CONFIG_PERFIL'));
        axios.post(`/rpclinica/perfil-profissional-config`, form)
            .then((res) => {  
                this.ConfigPerfil=res.data.config; 
                this.PerfilProf();
                
                this.view.historiaPregressa = res.data.view.sn_historia_pregressa;
                this.view.anamnese = res.data.view.sn_anamnese;
                this.view.exameFisico = res.data.view.sn_exame_fisico;
                this.view.hipoteseDiag = res.data.view.sn_hipotese_diag;
                this.view.conduta = res.data.view.sn_conduta;
               
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " <i class='fa fa-check'></i>  Salvar ";
            });
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
                axios.delete(`/rpclinica/profissional-assinatura/${this.ConfigPerfil.cd_profissional}`)
                .then((res) => {
                    $("#img_assinatura").remove();
                    this.ConfigPerfil.tp_assinatura = '';
                    toastr["success"]('Formulario excluido com sucesso!');
                })
                .catch((err) => toastr["error"]('Não foi possivel excluir a imagem!')) 
            }
        });


    },
     
    deleteAnamnese() {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar a Anamnese?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {

                    axios.post(`/rpclinica/json/deleteAnamneseGeral/${idAgendamento}`)
                        .then((res) => {  
                            this.Anamnese.Anamnese = res.data.agendamento.anamnese
                            this.Anamnese.Exame = res.data.agendamento.exame_fisico
                            this.Anamnese.Hipotese = res.data.agendamento.hipotese_diagnostica
                            this.Anamnese.Conduta = res.data.agendamento.conduta
                            this.dadosAgendamento = res.data.agendamento;
                            toastr['success']('Anamnese deletado com sucesso!');
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                        });
                }
            });
    }, 

    modalAnamnese(dados,tipo) {  
        this.Modal=[];
        if(tipo == 'U'){
            this.Modal[0] = dados;
        }else{
            this.Modal = dados;
        }
        
        $('.modalHistFormularios').modal('toggle');
    },

    storeFile(){

        this.Anamnese.loadFile=true;
        let form = new FormData(document.querySelector('#form_ARQUIVO_ANAM'));
        axios.post(`/rpclinica/json/storeAnamneseArquivo/${idAgendamento}`, form)
        .then((res) => {  

            this.Anamnese.historico_arquivos = res.data.request.array_img; 
            
            document.getElementById("form_ARQUIVO_ANAM").reset();
            toastr['success'](res.data.message);

        })
        .catch((err) => {
            toastr['error'](err.response.data.message, 'Erro');
        })
        .finally(() => { 
            this.Anamnese.loadFile=false;
        });
    },
 
    deleteExaImg(CodForm){
 
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja deletar esse arquivo?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) { 

                    this.Anamnese.loadFile=true; 
                    axios.delete(`/rpclinica/json/anamnese-delete/img/${CodForm}`)
                        .then((res) => {    
                            this.Anamnese.historico_arquivos = res.data.dados.array_img;
                            toastr['success']('Aquivo deletado com sucesso!');  
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                            this.Anamnese.loadFile=false; 
                        });
                }
            });
    },

    modalFile(dados){ 
        this.Anamnese.modal_arquivo=dados;
    },

    /* Documento */
    storeDocumento() { 
        this.loadingDoc=true;
        this.loading = true;
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_DOCUMENTO')); 
        form.set('documento', this.editorDocumento?.getData()); 
        axios.post(`/rpclinica/json/storeDocumentoGeral/${idAgendamento}`, form)
            .then((res) => {  
                 
                this.dadosDocumentos = res.data.agendamento.documentos;
                if(this.dadosDocumentos){
                    this.nrDocumentos = this.dadosDocumentos.length;
                }else{
                    this.nrDocumentos = 0;
                } 
                $('#modeloDocumento').val(null).trigger('change');
                this.Anamnese.Titulo = "";
                this.Anamnese.cdDocumento="";
                this.editorDocumento?.setData(''); 
                this.Anamnese.Documento="";
                $('#editor-formularios-documentos').code(null); 
                toastr['success'](res.data.message);

            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.loadingDoc=false;
                this.buttonDisabled = false;
                this.Anamnese.Documento = null; 
                this.Anamnese.cdDocumento = null; 
                this.snEdicaoDoc=false;
                this.buttonSalvar = " <i class='fa fa-check'></i>  Salvar ";
            });
    }, 

    editDocumento(documento) {  
        this.snEdicaoDoc=true; 
        $('#modeloDocumento').val(documento.cd_formulario).trigger('change');
        this.editorDocumento?.setData(documento.conteudo); 
        this.Anamnese.Documento =documento.conteudo;
        this.Anamnese.cdDocumento =documento.cd_documento; 
        this.Anamnese.Titulo =documento.titulo; 

    },

    cancelarEdicao(){
        $('#modeloDocumento').val(null).trigger('change'); 
        this.Anamnese.Documento =null;
        this.Anamnese.cdDocumento =null; 
        this.Anamnese.Titulo =null; 
        this.snEdicaoDoc=false;
    },

    excluirDocumento(idDocumento) {

         Swal.fire({
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
                this.loading = true;
                this.loadingDoc=true;
                axios.delete(`/rpclinica/json/deleteDocumentoGeral/${idAgendamento}/${idDocumento}`)
                    .then((res) => {
                        this.dadosDocumentos = res.data.agendamento.documentos;
                        if(this.dadosDocumentos){
                            this.nrDocumentos = this.dadosDocumentos.length;
                        }else{
                            this.nrDocumentos = 0;
                        } 
                        toastr['success']('Documento deletado com sucesso!'); 
                    })
                    .catch((err) => toastr['error'](err.response.data.message))
                    .finally(() => {
                        this.loading = false;
                        this.loadingDoc=false;
                        this.deleteDocIndice = null
                    });
            }
        });
    },
 
    modalDocumentos(dados,tipo) { 
        this.Modal=[];
        if(tipo == 'U'){
            this.Modal[0] = dados; 
        }else{
            this.Modal = dados;
        } 
        
        $('.modalHistDocumentos').modal('toggle');
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

        if($("#sn_assinatura").is(':checked')){
            var sn_assinatura = 'S';
        } else {
            var sn_assinatura = 'N';
        }

        axios.get(`/rpclinica/json/imprimirAnamneseGeral/${idAgendamento}`, {
            params: { tipo: 'anamnese', header: sn_header,  logo: sn_logo, footer: sn_footer, data: sn_data, assinatura: sn_assinatura },
            responseType: 'blob'
        })
            .then((res) => {
                window.open(URL.createObjectURL(res.data), 'anamnese.pdf');
            })
            .catch((err) => toastr['error']('Houve um erro ao imprimir o documento!'));
    },

    imprimirDocumento(cdDocumento,asssinarDoc=null) {
 
        
        if($("#sn_header_doc").is(':checked')){
            var sn_header = 'S';
        } else {
            var sn_header = 'N';
        }

        if($("#sn_footer_doc").is(':checked')){
            var sn_footer = 'S';
        } else {
            var sn_footer = 'N';
        }

        if($("#sn_logo_doc").is(':checked')){
            var sn_logo = 'S';
        } else {
            var sn_logo = 'N';
        }

        if($("#sn_data_doc").is(':checked')){
            var sn_data = 'S';
        } else {
            var sn_data = 'N';
        }

        if($("#sn_assinatura_doc").is(':checked')){
            var sn_assinatura = 'S';
        } else {
            var sn_assinatura = 'N';
        }

        if($("#sn_rec_especial").is(':checked')){
            var sn_rec_esp = 'S';
        } else {
            var sn_rec_esp = 'N';
        }

        if($("#sn_ocultar_titulo").is(':checked')){
            var sn_ocultar_titulo = 'S';
        } else {
            var sn_ocultar_titulo = 'N';
        }
        

        axios.get(`/rpclinica/json/imprimirDocumentoGeral/${idAgendamento}/${cdDocumento}`, {
            params: { tipo: 'documento', header: sn_header,  logo: sn_logo, footer: sn_footer, data: sn_data, assinatura: sn_assinatura, rec_especial: sn_rec_esp, sn_ocultar_titulo: sn_ocultar_titulo },
            responseType: 'blob'
        })
            .then((res) => {
                window.open(URL.createObjectURL(res.data), 'documento.pdf');
            })
            .catch((err) => toastr['error']('Houve um erro ao imprimir o documento!'));
    },
 
    storeModelo(Tipo) {
     
        Swal.fire({
            title: 'Confirmação',
            input: "text",
            text: "Informe o nome do Titulo!",
            icon: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#22BAA0', 
            confirmButtonText: this.buttonSalvar      
        }).then((result) => {
            
            if (result.value) { 
                this.buttonModelo = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... "; 
                this.Anamnese.Titulo = result.value
                this.Anamnese.Documento = this.editorDocumento?.getData();
                axios.post(`/rpclinica/json/storeModelo/${Tipo}`, this.Anamnese)
                .then((res) => { 
 
                    if(Tipo=='ATE'){
                        this.modeloAnamnese = res.data.request.modelo; 
                    }
                    if(Tipo=='DOC'){
                        this.modeloDocumento = res.data.request.modelo; 
                    }
                    toastr['success']('Formulario atualizado com sucesso!');
                    
                })
                .catch((err) => {
                    toastr['error'](err.response.data.message, 'Erro');
                })
                .finally(() => {
                    this.loading = false;
                    this.buttonDisabled = false;
                    this.buttonModelo= '&nbsp;<span aria-hidden="true" class="icon-tag"></span>&nbsp;&nbsp; Salvar Modelo&nbsp;'
                });
   
                
            }else{
                toastr['error']("Titulo não informado!", 'Erro');  
            }
        });

    },
 
    /* Anotação */
    storeAnotacao() { 
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_ANOTACAO'));
        axios.post(`/rpclinica/json/storeAnotacaoGeral/${idAgendamento}`, form)
            .then((res) => {  
                this.dadosAnotacao = res.data.anotacao; 
                if(this.dadosAnotacao){
                    this.nrAnotacao = this.dadosAnotacao.length;
                }else{
                    this.nrAnotacao = 0;
                }  
                this.Anamnese.Anotacao="";
                document.getElementById("form_ANOTACAO").reset()
                toastr['success'](res.data.message);
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.Anamnese.Documento = null; 
                this.buttonSalvar = " Salvar ";
                $("#imageFile").val(null);
            });
    }, 

    encerrarConsulta() {

        Swal.fire({
            title: 'Confirmação',
            html: "<h4 style='font-weight: 400;font-style: italic;'>Deseja encerrar esse atendimento?</h4>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
        .then((result) => {
            if (result.isConfirmed) location.href = `/rpclinica/json/finalizarConsultaGeral/${idAgendamento}`;
        })
        .catch((err) => {
            toastr['error'](err.response.data.message, 'Erro');
        });
  
    },

    FormatData(data) {
        var dt = data.split(" ")[0];
        var dia  = dt.split("-")[2];
        var mes  = dt.split("-")[1];
        var ano  = dt.split("-")[0];
      
        return ("0"+dia).slice(-2) + '/' + ("0"+mes).slice(-2) + '/' + ano;
      
    },

    titleize(text) {
        var words = text.toLowerCase().split(" ");
        for (var a = 0; a < words.length; a++) {
            var w = words[a];
            words[a] = w[0].toUpperCase() + w.slice(1);
        }
        return words.join(" ");
    },
 
    TextoPadrao(tipo) {
        this.texto_padrao_tipo=tipo;
        if(tipo=='ATE'){ this.header_padrao_titulo='Anamnese';}
        if(tipo=='DOC'){ this.header_padrao_titulo='Documentos';}
        $('.modaltextoPadrao').modal('toggle');
        this.loadingForm=true;
        this.texto_padrao=[];
        axios.get(`/rpclinica/json/texto-padrao/${tipo}`)
            .then((res) => { 
                this.texto_padrao=res.data.texto;
            })
            .catch((err) => toastr['error']('Houve um erro ao imprimir o documento!'))
            .finally(() => {
                this.loading_btn = false;
                this.loadingForm= false;

            });
    },

    editTextoPadrao(dados){
        this.form_texto_padrao.titulo=dados.nm_formulario;
        this.form_texto_padrao.codigo=dados.cd_formulario;
        this.form_texto_padrao.conteudo=dados.conteudo; 
        this.editorModeloDocumento?.setData(this.form_texto_padrao.conteudo);
    },

    clearTextoPadrao(){
        this.editorModeloDocumento?.setData(''); 
        this.form_texto_padrao.conteudo = ''; 
        this.form_texto_padrao.codigo = ''; 
        this.form_texto_padrao.titulo= '';
    },

    storeTextoPadrao(){
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_TEXTO_PADRAO'));
        form.set('conteudo', this.editorModeloDocumento?.getData()); 
        this.form_texto_padrao.conteudo = this.editorModeloDocumento?.getData();
        axios.post(`/rpclinica/json/store-texto-padrao/${this.texto_padrao_tipo}`, this.form_texto_padrao)
            .then((res) => {  

                this.texto_padrao=res.data.texto;
                this.modeloAnamnese = res.data.anamnese;
                this.modeloDocumento = res.data.documentos;
                toastr['success']('Documento Salvo com sucesso!');
                this.editorModeloDocumento?.setData(''); 
                this.form_texto_padrao.conteudo = ''; 
                this.form_texto_padrao.codigo = ''; 
                this.form_texto_padrao.titulo= '';

            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => { 
                this.buttonDisabled = false;
                this.buttonSalvar = " <i class='fa fa-check'></i>  Salvar ";
            });
    },
 
    deleteTextoPadrao(dados) {

        Swal.fire({ 
           title: 'Confirmação',
           html: "<h4 style='font-weight: 400;font-style: italic;'>Deseja Excluir esse Documento?</h4>",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#22baa0',
           cancelButtonColor: '#d33',
           cancelButtonText: 'Não',
           confirmButtonText: 'Sim',
           customClass: {
                container: 'customClassName'
           }
       }).then((result) => {

           if (result.isConfirmed) {  
               this.loading = true; 
               axios.delete(`/rpclinica/json/delete-texto-padrao/${this.texto_padrao_tipo}/${dados.cd_formulario}`)
                   .then((res) => {
                        
                       this.texto_padrao=res.data.texto;
                       this.modeloAnamnese = res.data.anamnese;
                       this.modeloDocumento = res.data.documentos;
                       toastr['success']('Documento deletado com sucesso!'); 
                   })
                   .catch((err) => toastr['error'](err.response.data.message))
                   .finally(() => {
                       this.loading = false;  
                   });
           }
       });
    },

    storeCertificado(){
        this.buttonUpload = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... "; 
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#formCertificado'));
        axios.post(`/rpclinica/perfil-profissional-certificado`, form)
            .then((res) => {   
                this.dadosCertificado=res.data.retorno.certificado;
                this.loadingCertificado=true;
                document.getElementById("formCertificado").reset();
                toastr['success']('Certificado Salvo com sucesso!'); 

            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => { 
                this.buttonDisabled = false;
                this.buttonUpload = ' <i class="fa fa-upload" style="margin-right: 6px;"></i> Salvar ';
            });
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
                this.buttonDelete = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Excluindo... ";  
                this.buttonDisabled = true;
                axios.get(`/rpclinica/perfil-prof-del-certificado?tipo=prontuario`)
                .then((res) => { 
                    this.loadingCertificado=false;
                    this.dadosCertificado=null;  
                    toastr['success']('Certificado deletado com sucesso!'); 

                })
                .catch((err) => {
                    toastr['error']('Houve um erro ao deletar o certificado!')
                })
                .finally(() => { 
                    this.buttonDelete= '<i class="fa fa-trash"></i> Exluir Certificado';
                    this.buttonDisabled = false;
                }); 
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
  
                if($("#sn_header_doc").is(':checked')){
                    var sn_header = 'S';
                } else {
                    var sn_header = 'N';
                }

                if($("#sn_footer_doc").is(':checked')){
                    var sn_footer = 'S';
                } else {
                    var sn_footer = 'N';
                }

                if($("#sn_logo_doc").is(':checked')){
                    var sn_logo = 'S';
                } else {
                    var sn_logo = 'N';
                }

                if($("#sn_data_doc").is(':checked')){
                    var sn_data = 'S';
                } else {
                    var sn_data = 'N';
                }

                if($("#sn_assinatura_doc").is(':checked')){
                    var sn_assinatura = 'S';
                } else {
                    var sn_assinatura = 'N';
                }

                if($("#sn_rec_especial").is(':checked')){
                    var sn_rec_esp = 'S';
                } else {
                    var sn_rec_esp = 'N';
                }

                if($("#sn_ocultar_titulo").is(':checked')){
                    var sn_ocultar_titulo = 'S';
                } else {
                    var sn_ocultar_titulo = 'N';
                }
                 
                axios.get(`/rpclinica/json/assinarDocumentoGeral/${idAgendamento}/${codDoc}`, {
                    params: { tipo: 'documento', header: sn_header,  logo: sn_logo, footer: sn_footer, data: sn_data, assinatura: sn_assinatura, 
                              rec_especial: RecEsp, sn_ocultar_titulo: sn_ocultar_titulo, assinar_digital:'S', senha: result.value }
                })
 
                .then((res) => {
                      
                    if(tipo=='A'){
                        this.assinatura_digital.situacao=res.data.sn_assinado;
                        this.assinatura_digital.conteudo=res.data.conteudo;
                    }
 
                    if(res.data.retorno==true){
                        var Title = "Sucesso!";
                        var Html = "<h4 style='font-weight: 400;font-style: italic;'>Documento assinado com sucesso!</h4>";
                        var Icon = "success";
                        if(tipo=='D'){
                            this.dadosDocumentos = res.data.documento.documentos;
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

    async copyText(text) {
        try {
            await navigator.clipboard.writeText(text);
            toastr['success']('Copiado!');
        }
        catch (err) {
            toastr['error'](`Não foi possivel copiar. Erro: ${ err }`);
        }
    },

}));


