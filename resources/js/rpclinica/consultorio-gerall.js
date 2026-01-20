import axios from 'axios';
import moment from 'moment';

Alpine.data('appConsultorioGeral', () => ({
    
    telaAtiva: null,
    snEdicaoDoc: false,
    iconHistry: "<i class='fa fa-list'></i>",
    conteudo: '<div class="col-md-7"><div style="text-align: center;"> <img src="/assets/images/oftalmo.png" > </div> </div>',
    conteudoCarregando: '<div class="col-md-7"><div style="text-align: center;"> <img style="height: 80px;margin-top: 100px;" src="/assets/images/carregandoFormulario.gif" > </div> </div>',
    conteudoCarregandoModal: '<div class="col-md-12"><div style="text-align: center;"> <img style="height: 80px;margin-top: 100px;margin-bottom: 60px;" src="/assets/images/carregandoFormulario.gif" > </div> </div>',
    loading: false,
    loadingDoc: false,
    buttonSalvar: " <i class='fa fa-check'></i>  Salvar ",
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

    history: {
        Anamnese: [],
        Documento: [],
        Geral: []
    },
    nrHistory: 0,
    Modal: [],
    
    init() {

        this.getPagina();
 
        $('#modeloAnamnese').on('select2:select', (evt) => { 
            var idForm = evt.params.data.id;  
            var filtrado = this.modeloAnamnese.filter(function(obj) { return obj.cd_formulario == idForm; });
            if(filtrado[0]){ 
                this.Anamnese.Anamnese=filtrado[0].conteudo;
                this.Anamnese.Exame=filtrado[0].exame;
                this.Anamnese.Hipotese=filtrado[0].hipotese;
                this.Anamnese.Conduta=filtrado[0].conduta;
            }
        });

        $('#modeloDocumento').on('select2:select', (evt) => { 
            var idForm = evt.params.data.id;   
            var filtrado = this.modeloDocumento.filter(function(obj) { return obj.cd_formulario == idForm; });
            if(filtrado[0]){ 
                this.Anamnese.Documento=filtrado[0].conteudo; 
                this.Anamnese.Titulo=filtrado[0].nm_formulario; 
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
            this.Anamnese.Exame = res.data.agendamento.exame_fisico
            this.Anamnese.Hipotese = res.data.agendamento.hipotese_diagnostica
            this.Anamnese.Conduta = res.data.agendamento.conduta
            this.Anamnese.Historia=res.data.historia_pregressa;
            this.dadosAgendamento = res.data.agendamento;
            this.dadosDocumentos = res.data.agendamento.documentos;
            this.Anamnese.historico_exames=res.data.historico.exame;
            this.Anamnese.historico_arquivos=res.data.historico.arquivo;
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
            console.log(res.data); 

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
 
    /* Anamnese */
    storeAnamnese() {
        console.log(idAgendamento);
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        this.buttonDisabled = true;
        let form = new FormData(document.querySelector('#form_ANAMNESE'));
        axios.post(`/rpclinica/json/storeAnamneseGeral/${idAgendamento}`, form)
            .then((res) => { 
                this.Anamnese.Anamnese = res.data.agendamento.anamnese
                this.Anamnese.Exame = res.data.agendamento.exame_fisico
                this.Anamnese.Hipotese = res.data.agendamento.hipotese_diagnostica
                this.Anamnese.Conduta = res.data.agendamento.conduta
                this.dadosAgendamento = res.data.agendamento;
                toastr['success']('Formulario atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false;
                this.buttonDisabled = false;
                this.buttonSalvar = " Salvar ";
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
        console.log(dados);
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
                            console.log(res.data); 
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
        $('#editor-formularios-documentos').code(documento.conteudo);
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
        console.log(dados);
        this.Modal=[];
        if(tipo == 'U'){
            this.Modal[0] = dados;
            console.log('000');
        }else{
            this.Modal = dados;
        }
        console.log(this.Modal);
        
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

    imprimirDocumento(cdDocumento) {
 
        
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

        

        axios.get(`/rpclinica/json/imprimirDocumentoGeral/${idAgendamento}/${cdDocumento}`, {
            params: { tipo: 'documento', header: sn_header,  logo: sn_logo, footer: sn_footer, data: sn_data, assinatura: sn_assinatura, rec_especial: sn_rec_esp },
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
                axios.post(`/rpclinica/json/storeModelo/${Tipo}`, this.Anamnese)
                .then((res) => { 

                    console.log(res.data) 
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
                console.log(res.data);
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
 

}));


