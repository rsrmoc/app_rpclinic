import axios from 'axios';
import { concat } from 'lodash';
import moment from 'moment';

Alpine.data('app', () => ({ 
    loadingModal: false,
    relacaoAtend: null,
    messageDanger: null,
    INDEX: null,
    INDEX2: null,
    CD_ITEM: null, 
    DADOS_EXAME:null,
    DADOS_ATEND:null,
    AGENDAMENTO: null,
    PROFISSIONAL: null,
    NOME_MODAL: null,
    EXAME_MODAL: null,
    loading: false,
    retornoLista: null,
    paginatedData: [],
    currentPage: 1,
    itemsPerPage: 50,
    totalPages: 0,
    totalLinhas: 0,
    index: 0,
    loadingPesq: false,
    textoPadrao: null,
    buttonPesquisar: "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ",
    buttonSalvarLaudo: '<i class="fa fa-check"></i> Salvar',
    buttonSalvarAnot: '<i class="fa fa-check"></i> Salvar',
    buttonSalvarTemp: '<i class="fa fa-check"></i> Salvar', 
    buttonSalvarExaAnot: ' <i  class="fa fa-check"></i> Salvar ',
    buttonSalvarExaImg: ' <i  class="fa fa-check"></i> Salvar ',
    buttonSalvarAutoRef: ' <i  class="fa fa-check"></i> Salvar ',
    buttonSalvarCerat: ' <i  class="fa fa-check"></i> Salvar ',
    buttonSalvarCeratComp: ' <i  class="fa fa-check"></i> Salvar ',
    tempSalvar: ' <i  class="fa fa-check"></i> Salvar ',
    iconeWhasOk: '<i class="fa fa-whatsapp text-success"></i>',
    iconeWhasERRO: '<i class="fa fa-whatsapp text-danger"></i>',

    infoModal: {},
    indexModal: null,
    editor: null,
    selectedTextoPadrao: '',  // New variable for the selected value
    isChecked: true, 

    classSituacaoExame: {
        A: 'btn-rss', 
        E: 'btn-info',
        R: 'btn-success',
        '': ''
    },

    EXAME:{
        array_img:[]
    },

    situacaoExame: {
        A: '<i class="fa fa-ban" style="padding-left:2px;"></i> Aguardando',
        E: '<i class="fa fa-check" style="padding-left:2px;"></i> Executado',
        R: '<i class="fa fa-check-square-o" style="padding-left:2px;"></i> Realizado', 
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
      
    },
 
    goToPage(page) {
        if (page >= 1) {
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

    getPage() {
         
        this.loadingPesq = true;
        this.buttonPesquisar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i>  ";
        let form = new FormData(document.querySelector('#form-parametros'));
        axios.post(`/rpclinica/json/atendimento?page=${ this.currentPage }&itemsPerPage=${ this.itemsPerPage }`, form)
            .then((res) => {
                console.log(res.data);
                this.relacaoAtend = res.data.query.data; 
                this.retornoLista = res.data.query.data;
                this.paginatedData = res.data.query.data;
                this.totalPages = res.data.query.last_page; 
                this.totalLinhas =res.data.query.total; 
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
        this.indexModal = index;
        this.infoModal = dados;  
   
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
 
        
        $('.modalDetalhes').modal('toggle');
  
    },
 
    getExames(dadosExame,idx2,dadosAtend,idx){ 
        this.AGENDAMENTO = dadosAtend.cd_agendamento;
        this.DADOS_EXAME = dadosExame;
        this.DADOS_ATEND = dadosAtend;
        this.PROFISSIONAL = dadosAtend.cd_profissional; 
        this.CD_ITEM = dadosExame.cd_agendamento_item;  
        this.INDEX = idx; 
        this.INDEX2 = idx2; 
        this.NOME_MODAL = (dadosAtend.paciente.nm_paciente) ? dadosAtend.paciente.nm_paciente : ' -- ';
        this.EXAME_MODAL= (dadosExame.exame.nm_exame) ? dadosExame.exame.nm_exame : ' -- ';
        this.loadingModal = true;  
        this.EXAME.array_img = null;  
        axios.get(`/rpclinica/json/central-laudos/imgs/${this.CD_ITEM}`)
        .then((res) => {     
             this.EXAME.array_img=res.data.retorno
             console.log(this.EXAME);
        })
        .catch((err) => {
            toastr['error'](err.response.data.message, 'Erro');
        })
        .finally(() => {
            this.loadingModal = false;
        }); 
         
    },

    storeExaAnot(){
        this.buttonSalvarExaAnot = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... "; 
        let form = new FormData(document.querySelector('#form_ANOT_EXAME')); 
        form.append('cd_agendamento_item', this.CD_ITEM) 
        axios.post(`/rpclinica/json/central-laudos/addHist`, form)
        .then((res) => {     
           this.retornoLista[this.INDEX]['itens'][this.INDEX2]['historico'] = res.data.retorno  
           this.DADOS_EXAME['historico'] = res.data.retorno; 
           document.getElementById("form_ANOT_EXAME").reset(); 
           toastr['success']('Formulario atualizado com sucesso!');
        })
        .catch((err) => {
            
            toastr['error'](err, 'Erro');
        })
        .finally(() => { 
            this.buttonSalvarExaAnot= this.tempSalvar;
        });
    },

    storeExaImg(){ 
        this.buttonSalvarExaImg = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... "; 
        let form = new FormData(document.querySelector('#form_EXAME_IMG'));
        form.append('cd_agendamento_item', this.CD_ITEM)
        form.append('cd_agendamento', this.AGENDAMENTO)
        axios.post(`/rpclinica/json/central-laudos/img/${this.CD_ITEM}`, form)
            .then((res) => {
                document.getElementById("form_EXAME_IMG").reset();
                toastr['success']('Imagem importada com sucesso!');
                this.getExames(this.DADOS_EXAME,this.INDEX2,this.DADOS_ATEND,this.INDEX); 
            })
            .catch((err) => {
                   
                if(err.response.data.message==''){
                    if(err.response.status == 413){
                        toastr['error']('O(s) arquivo(s) ultrapassou a capacidade servidor!', 'Erro');
                    }else{
                        toastr['error']('Erro ao importar arquivos!', 'Erro');
                    }
                    
                }else{
                    toastr['error'](err.response.data.message, 'Erro');
                }
                
            })
            .finally(() => {
                this.loading = false; 
                this.buttonSalvarExaImg = this.tempSalvar;
            });
    }, 

    deleteAtend(cod) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse atendimento?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        })
            .then((result) => {
                if (result.isConfirmed) {
                    this.buttonSalvarExaImg = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Atualizando... "; 
                    this.loadingModal = true;
                    this.loading = true; 
                    axios.delete(`/rpclinica/json/atendimento-delete/${cod}`)
                        .then((res) => {  
                            toastr['success']('Atendimento deletado com sucesso!'); 
                            this.getExames(this.DADOS_EXAME,this.INDEX2,this.DADOS_ATEND,this.INDEX); 
                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                            this.loadingModal = false;
                            this.buttonSalvarExaImg = this.tempSalvar;
                            this.loading = false; 
                        });

                }
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

                    this.loading = true; 
                    this.loadingModal = true;
                    axios.delete(`/rpclinica/json/central-laudos-delete/img/${CodForm}`)
                        .then((res) => {  
                            toastr['success']('Aquivo deletado com sucesso!'); 
                            this.getExames(this.DADOS_EXAME,this.INDEX2,this.DADOS_ATEND,this.INDEX); 
                            this.loadingModal = true;

                        })
                        .catch((err) => {
                            toastr['error'](err.response.data.message, 'Erro');
                        })
                        .finally(() => {
                            this.loadingModal = false;
                            this.loading = false; 
                        });
                }
            });
    }, 

 

    nl2br(str, replaceMode, isXhtml) { 
        var breakTag = (isXhtml) ? '<br />' : '<br>';
        var replaceStr = (replaceMode) ? '$1'+ breakTag : '$1'+ breakTag +'$2';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
    },
    formatValor(valor) {
        if(!valor) { return NULL; }
        return Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(valor).replaceAll("R$ ", "");
    },
    FormatData(data) {
        var dt = data.split(" ")[0];
        var dia  = dt.split("-")[2];
        var mes  = dt.split("-")[1];
        var ano  = dt.split("-")[0];
      
        return ("0"+dia).slice(-2) + '/' + ("0"+mes).slice(-2) + '/' + ano;
      
    }
 
}));
