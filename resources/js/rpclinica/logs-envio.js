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
    HISTORICO: null,
    loading: false,
    retornoLista: null,
    paginatedData: [],
    paginatedDataAgend: [],
    currentPage: 1,
    currentPageAgend: 1,
    itemsPerPage: 20,
    totalPages: 0,
    totalPagesAgend: 0,
    totalLinhas: 0,
    totalLinhasAgend: 0,
    index: 0,
    qtdeEnviado: 0,
    qtdeEnviadoAgend: 0,
    qtdeNaoEnviado: 0,
    qtdeErro: 0,
    qtdeErroAgend: 0,
    loadingPesq: false,
    loadingPesqAgenda: false,
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

    /*
    paginateData() {

        if (page >= 1) {
            this.currentPage = page; 
            this.getPage();
        }
        
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        this.paginatedData = this.retornoLista.slice(startIndex, endIndex);
    },
    */

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
 
    
    getPage() {
        this.loadingPesq = true;
        this.buttonPesquisar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i>  ";
        let form = new FormData(document.querySelector('#form-parametros'));
        axios.post(`/rpclinica/json/logs-envio-rotina-whast?page=${ this.currentPage }&itemsPerPage=${ this.itemsPerPage }`, form)
            .then((res) => {
              
                console.log(res.data)
                this.paginatedData = res.data.query.data;
                this.relacaoAtend = res.data.query.data; 
                this.retornoLista = res.data.query.data;   
                this.totalPages = res.data.query.last_page; 
                this.totalLinhas = res.data.query.total; 
                this.qtdeEnviado =res.data.enviada;
                this.qtdeNaoEnviado =res.data.aberta;
                this.qtdeErro =res.data.erro;
 
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })

            .finally(() => {
                this.buttonPesquisar = "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ";
                this.loadingPesq = false;
            });

    },


    goToPageAgendamento(page) {
        if (page >= 1 && page <= this.totalPagesAgend) {
            this.currentPageAgend = page;
            this.getPageAgendamento();
        }
    },

    nextPageAgendamento() {
        if (this.currentPageAgend < this.totalPagesAgend) {
            this.currentPageAgend++;
            this.getPageAgendamento();
        }
    },

    previousPageAgendamento() {
        if (this.currentPageAgend > 1) {
            this.currentPageAgend--;
            this.getPageAgendamento();
        }
    },

    getPageAgendamento() {
        this.loadingPesqAgenda = true;
        this.buttonPesquisar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i>  ";
        let form = new FormData(document.querySelector('#form-param-agend'));
        axios.post(`/rpclinica/json/logs-envio-rotina-whast-agendamentos?page=${ this.currentPage }&itemsPerPage=${ this.itemsPerPage }`, form)
            .then((res) => { 
                console.log(res.data) 
                this.paginatedDataAgend = res.data.query.data;   
                this.totalPagesAgend = res.data.query.last_page; 
                this.totalLinhasAgend = res.data.query.total;  
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })

            .finally(() => {
                this.buttonPesquisar = "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ";
                this.loadingPesqAgenda = false;
            }); 
    },

    clickModal(dados,index) { 
        this.indexModal = index;
        this.infoModal = dados;   
        this.loadingModal = true;
        axios.get(`/rpclinica/json/log-envio/historico/${dados.cd_agendamento_item}`)
        .then((res) => {     
            console.log(res.data);
             this.HISTORICO=res.data.historico
             console.log(this.HISTORICO);
        })
        .catch((err) => {
            toastr['error'](err.response.data.message, 'Erro');
        })
        .finally(() => {
            this.loadingModal = false;
        }); 

        $('.modalDetalhes').modal('toggle');
  
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
