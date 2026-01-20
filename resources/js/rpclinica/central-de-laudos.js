import axios from 'axios';
import moment from 'moment';

Alpine.data('app', () => ({
    iconeKey: '<i class="fa fa-key"></i>',
    icondeCalendar: '<i class="fa fa-calendar"></i>',
    icondeFile:  '<i class="fa fa-file-o"></i>',
    iconeOlho: '<i class="fa fa-eye" aria-hidden="true"></i>',
    iconeProf: '<i class="fa fa-user-md"></i>',
    iconeSolic: '<i class="fa fa-stethoscope"></i>',
    iconeIdade: '<i class="fa fa-asterisk" aria-hidden="true"></i>',
    loadingModal: false,
    loadingAcao:'',
    order: 'dt',
    orderOUT:'',
    profLogado: profLogado,
    loading: false, 
    empresa: empresa,
    retornoLista: null,
    paginatedData: [],
    currentPage: 1,
    itemsPerPage: 50,
    totalPages: 0,
    totalLinhas:0,
    index: 0,
    snLaudo: false, 
    snTodosLaudo: snTodosLaudo,
    loadingPesq: false,
    textoPadrao: null,
    buttonSalvar: " <i class='fa fa-check'></i>  Salvar ",
    buttonPesquisar: "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ",
    buttonSalvarLaudo: '<i class="fa fa-check"></i> Salvar',
    buttonSalvarAnot: '<i class="fa fa-check"></i> Salvar',
    buttonSalvarTemp: '<i class="fa fa-check"></i> Salvar',
    buttonSalvarExaImg: ' <i  class="fa fa-check"></i> Salvar ',
    tempSalvar: ' <i  class="fa fa-check"></i> Salvar ',
    infoModal: {},
    indexModal: null,
    editor: null,
    selectedTextoPadrao: '',  // New variable for the selected value
    isChecked: true,
    historico_exames: null,
    nrImg: 0, 
    nrAnotacao: 0,
    modeloDocumento:{
        descricao: null, 
        conteudo_laudo: null,
        atendimento: null
    },
    classSituacaoExame: {
        A: 'btn-rss', 
        E: 'btn-info',
        R: 'btn-success',
        '': ''
    },

    situacaoExame: {
        A: '<i class="fa fa-ban" style="padding-left:2px;"></i> Aguardando',
        E: '<i class="fa fa-check" style="padding-left:2px;"></i> Executado',
        R: '<i class="fa fa-check-square-o" style="padding-left:2px;"></i> Liberado', 
        '': ''
    },


    swalWithBootstrapButtons: Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success swal-button",
            cancelButton: "btn btn-danger swal-button",
            input: "form-control"
        },
        buttonsStyling: false
    }),

    init() {
 
        this.getPage();

        const editor = CKEDITOR.replace('conteudo', {
            toolbar: [{
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', '-', 'Blockquote']
            },
            {
                name: 'styles',
                items: ['Format']
            }]
        });

        this.editor = editor;

        this.editor.setData(this.infoModal.conteudo_laudo);

        this.editor.on('change', () => {
            const data = editor.getData(); 
        });
        
        $('#texto_padrao').on('select2:select', (evt) => { 
            //this.editor.setData(evt.params.data.id);
 
            if(evt.params.data.id){
                this.getTextoPadrao(evt.params.data.id);
            }
            
        });
 
    },

    getTextoPadrao(codigo){

        
        this.loadingAcao = "Carregando Texto Padrão...";
        $('.absolute-loading').show();

        this.editor.setData((this.infoModal.conteudo_laudo) ? this.infoModal.conteudo_laudo : '') 
        axios.get(`/rpclinica/json/central-laudos-carrega-texto-padrao/${codigo}/${this.infoModal.cd_agendamento}`)
            .then((res) => {   
                console.log(res.data);
                this.editor.setData(res.data.conteudo);
            })

            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })

            .finally(() => { 
                $('.absolute-loading').hide(); 
            });
    },

    paginateData() {

        if (page >= 1) {
            this.currentPage = page; 
            this.getPage();
        }
        
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        this.paginatedData = this.retornoLista.slice(startIndex, endIndex);
    },

    goToPage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
            this.getPage();
        }
    },

    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.currentPage++;
            this.getPage();
        }
    },

    previousPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.getPage();
        }
    },

    orderBy(tipo) {
        this.order= tipo;
        this.getPage();
    },

    getSearch() {
        this.currentPage= 1,
        this.itemsPerPage=50;
        this.getPage();
    },


    getPage() {
        this.loadingPesq = true;
        this.buttonPesquisar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i>  ";
        let form = new FormData(document.querySelector('#form-parametros'));
        axios.post(`/rpclinica/json/central-laudos?page=${ this.currentPage }&itemsPerPage=${ this.itemsPerPage }`, form)
            .then((res) => {
                console.log(res.data);
                this.retornoLista = res.data.query.data 
                this.paginatedData = res.data.query.data 
                this.totalPages = res.data.query.last_page; 
                this.totalLinhas = res.data.query.total; 
                this.orderOUT = res.data.orderBy;
            
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })

            .finally(() => {
                this.buttonPesquisar = "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ";
                this.loadingPesq = false;
            });

    },

    clickModal(dados,index) { 

        this.textoPadrao=[]; 
        $('#texto_padrao').val(null).trigger('change');
        this.indexModal = index;
        this.infoModal = dados;  
        this.historico_exames = null; 
        $('.modalDetalhes').modal('toggle'); 
        this.infoModal.array_img = null;
        this.infoModal.notes = null;
  
        if(this.snTodosLaudo==true){
            this.snLaudo=true;
        }else{
            if(this.infoModal.atendimento?.cd_profissional == this.profLogado){
                this.snLaudo=true;
            }else{
                this.snLaudo=false;
            }
        }
         
        
        if(dados.sn_laudo == true){
            this.infoModal.sn_laudo=true
        }else{
            this.infoModal.sn_laudo=false
        } 

        if(dados.sn_laudo==true){
            $('#label_laudo_checkbox span').addClass('checked'); 
            $('#label_laudo_checkbox input').prop('checked', true); 
        }else{ 
            $('#label_laudo_checkbox span').removeClass('checked');
            $('#label_laudo_checkbox input').prop('checked', false);
        }

        this.loadingAcao = "Carregando Informações...";
        $('.absolute-loading').show();

        this.editor.setData((this.infoModal.conteudo_laudo) ? this.infoModal.conteudo_laudo : '')
        axios.post(`/rpclinica/json/modal-central-laudos`, dados)
            .then((res) => {   
                this.historico_exames = res.data.tab_historico_exame;
                this.textoPadrao = res.data.texto_padrao;
                this.infoModal.array_img = res.data.array_img 
                this.infoModal.notes = res.data.hist;    
                if(this.infoModal.array_img){
                    this.nrImg = this.infoModal.array_img.length;
                }else{
                    this.nrImg = 0;
                } 

                if(this.infoModal.notes){
                    this.nrAnotacao = this.infoModal.notes.length;
                }else{
                    this.nrAnotacao = 0;
                }

            })

            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
                $('.modalDetalhes').modal('hide'); 
            })

            .finally(() => { 
                $('.absolute-loading').hide(); 
            });
 
    },

    addHistory() {
 
        this.buttonSalvarAnot= " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        let form = new FormData(document.querySelector('#form_RESERVA_CIRURGIA'));
        form.append('cd_agendamento_item', this.infoModal.cd_agendamento_item)
        axios.post(`/rpclinica/json/central-laudos/addHist`, form)
            .then((res) => {
                axios.get(`/rpclinica/json/central-laudos/getHist/${this.infoModal.cd_agendamento_item}`)
                    .then((res) => {
                        this.infoModal.notes = res.data;
                        if(this.infoModal.notes){
                            this.nrAnotacao = this.infoModal.notes.length;
                        }else{
                            this.nrAnotacao = 0;
                        }
                        document.getElementById("form_RESERVA_CIRURGIA").reset();
                    })
                    .catch((err) => {
                        toastr['error'](err.response.data.message, 'Erro');
                    });
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => { 
                this.buttonSalvarAnot= this.buttonSalvarTemp;
            });
    },

    submitLaudo() {
        this.loadingAcao = "Salvando Informações...";
        $('.absolute-loading').show();
        this.buttonSalvarLaudo = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
        const dados = { 'conteudo_laudo': this.editor.getData() } 
        axios.post(`/rpclinica/json/central-laudos/saveLaudo/${this.infoModal.cd_agendamento_item}`, dados)
            .then((res) => {
                 
                this.infoModal.conteudo_laudo = this.editor.getData(); 
                this.getPage();
                this.infoModal.situacao = 'E';
                toastr['success']('Laudo atualizado com sucesso!');
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })

            .finally(() => {
                this.buttonSalvarLaudo = this.buttonSalvarTemp;
                $('.absolute-loading').hide(); 
            });
    },

    liberarLaudo(status) { 



        var texto = "<h4 style='font-weight: 500;font-style: italic;'>Deseja liberar o laudo?</h4>";
        if(status==false){
            var texto = "<h4 style='font-weight: 500;font-style: italic;'>Deseja cancelar o laudo?</h4>";
        }
        this.swalWithBootstrapButtons.fire({
            title: 'Confirmação',
            html: texto,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                /*
                if ($('#sn_laudo_checkbox').is(":checked")) {
                    this.infoModal.sn_laudo = true;
                }else{
                    this.infoModal.sn_laudo = false;
                }
                */
                this.infoModal.sn_laudo = status;
                if(this.infoModal.sn_laudo == true){
                    this.loadingAcao = "Liberando Laudo...";
                }else{
                    this.loadingAcao = "Cancelando Laudo...";
                } 
                $('.absolute-loading').show();
                
                axios.post(`/rpclinica/json/central-laudos/liberarLaudo/${this.infoModal.cd_agendamento_item}`, this.infoModal)
                    .then((res) => {  
                        this.getPage();  
                        this.infoModal.notes = res.data.hist.original;

                        if(this.infoModal.sn_laudo == true){
                            this.infoModal.key = res.data.key;
                            toastr['success']('Laudo liberado com sucesso!');
                            this.infoModal.situacao = 'R';
                        }else{
                            toastr['success']('Laudo cancelado com sucesso!'); 
                            this.infoModal.situacao = 'E';
                            this.infoModal.key = null;
                        }
                        
                    })
                    .catch((err) => {
                        console.log(err.response.data);
                        toastr['error'](err.response.data.message, 'Erro');
                        if(this.infoModal.sn_laudo == true){
                            this.infoModal.sn_laudo = false;
                        }else{
                            this.infoModal.sn_laudo = true;
                        }
                    })
                    .finally(() => { 
                        $('.absolute-loading').hide(); 
                    });

            }
        });


    },
 
    async copyText() {
       
            var textToCopy = window.location.protocol + '//' + window.location.host + '/rpclinica/laudo-paciente/' + this.infoModal.cd_agendamento_item + '/' + this.infoModal.key;

            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(textToCopy);
            } else { 
                const textArea = document.createElement("textarea");
                textArea.value = textToCopy; 
                textArea.style.position = "absolute";
                textArea.style.left = "-999999px"; 
                document.body.prepend(textArea);
                textArea.select(); 
                try {
                    document.execCommand('copy');
                    toastr['success']('Copiado!');
                } catch (error) {
                    toastr['error'](`Não foi possivel copiar. Erro: ${ error }`);
                } finally {
                    textArea.remove();
                }
            }
        
    },
 
    async copyTextZap() {
       
            console.log(this.infoModal);

            Swal.fire({
                title: 'Confirmação',
                text: "Tem certeza que deseja enviar mensagem para esse cliente?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#22BAA0',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Não',
                confirmButtonText: 'Sim'
            })
            .then((result) => {
                if (result.isConfirmed) { 

                    axios.get(`/rpclinica/json/comunicacao-send-laudo/${this.infoModal.cd_agendamento_item}`)
                    .then((res) => {

                        if(res.data.retorno == true){
                            this.infoModal.notes = res.data.hist;
                            toastr['success']('mensagem enviada com sucesso!'); 
                        }else{
                            var msg = (res.data.msg) ? res.data.msg : 'Erro ao enviar mensagem!';
                            toastr['error'](msg, 'Erro');
                        }
                        
                    })
                    .catch((err) => {
                        toastr['error'](err.response.data.message, 'Erro');
                    });


                    
                }
            });
 
    },
    
    nl2br (str, is_xhtml) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    },

    storeExaImg(){ 

        this.buttonSalvarExaImg = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... "; 
        let form = new FormData(document.querySelector('#form_EXAME_IMG'));
        form.append('cd_agendamento_item', this.infoModal.cd_agendamento_item)
        form.append('cd_agendamento', this.infoModal.cd_agendamento)
        axios.post(`/rpclinica/json/central-laudos/img/${this.infoModal.cd_agendamento_item}`, form)
            .then((res) => { 
                this.infoModal.array_img = res.data.request.array_img;
                if(this.infoModal.array_img){
                    this.nrImg = this.infoModal.array_img.length;
                }else{
                    this.nrImg = 0;
                } 
                document.getElementById("form_EXAME_IMG").reset();
                toastr['success']('Imagem importada com sucesso!'); 
            })
            .catch((err) => { 
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.loading = false; 
                this.buttonSalvarExaImg = this.tempSalvar;
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

                    this.loadingModal = true;
                    axios.delete(`/rpclinica/json/central-laudos-delete/img/${CodForm}`)
                        .then((res) => {  
                            console.log(res.data);
                            this.infoModal.array_img = res.data.dados.array_img;
                            if(this.infoModal.array_img){
                                this.nrImg = this.infoModal.array_img.length;
                            }else{
                                this.nrImg = 0;
                            } 
                            toastr['success']('Aquivo deletado com sucesso!');  
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                            this.loadingModal = false;
                        });
                }
            });
    },
    
    storeModelo() {
         
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
                 
                this.modeloDocumento.descricao = result.value
                this.modeloDocumento.atendimento = this.infoModal.cd_agendamento
                this.modeloDocumento.conteudo_laudo = this.editor.getData() 
               
                axios.post(`/rpclinica/exame-modelo-store/${this.infoModal.cd_exame}`, this.modeloDocumento)
                .then((res) => { 

                    console.log(res.data)  
                    this.textoPadrao = res.data.texto_padrao;
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

    popup(codigo){
        window.close();
        window.open("/rpclinica/central-laudos-painel-imagens/"+codigo, "_blank", "scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=768,height=1280");     
    },

  
}));

 
