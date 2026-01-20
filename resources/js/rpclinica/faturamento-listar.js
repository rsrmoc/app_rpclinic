Alpine.data('app', () => ({
    query: [],
    dataFaturamento: [],
    dataConta: [],
    idxConta: null,
    loadingModal: false,
    paginatedData: [],
    loadingPesq: false,
    buttonSalvar: " <i class='fa fa-check'></i>  Salvar ",
    buttonPesquisar: "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ",
    buttonTempleatePesquisar: "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ",
    currentPage: 1,
    totalPages: 0,
    totalLinhas:0,
    itemsPerPage: 50,
    loadingAcao: null,

    init() {
        this.getPage(); 
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

    getPage(){
        this.loadingPesq = true;
        this.buttonPesquisar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i>  ";
        let form = new FormData(document.querySelector('#form-parametros'));
        axios.post(`/rpclinica/json/faturamento-conta-json?page=${ this.currentPage }&itemsPerPage=${ this.itemsPerPage }`, form)
            .then((res) => {
                console.log(res.data);
                this.retornoLista = res.data.query.data 
                this.paginatedData = res.data.query.data 
                this.totalPages = res.data.query.last_page; 
                this.totalLinhas = res.data.query.total;  
                console.log(this.paginatedData);
                            
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })

            .finally(() => {
                this.buttonPesquisar = this.buttonTempleatePesquisar;
                this.loadingPesq = false;
            });
    },

    clickModal(Conta,index) { 
        
        $('#situacaoFat').val(Conta.status_faturamento.cd_situacao_itens).trigger('change');
        this.loadingModal = true;
        this.dataConta = Conta;
        this.idxConta = index; 
        console.log(Conta);
    },

    storefaturamento(){
        this.loadingAcao = "Salvando Informação...";
        $('.absolute-loading').show();
 
        let form = new FormData(document.querySelector('#form-faturamento'));
        axios.post(`/rpclinica/json/faturamento-store/${this.dataConta.cd_agendamento_item}`, form)
            .then((res) => {
                console.log(res.data); 
                
                this.retornoLista[this.idxConta] = res.data.query 
                this.paginatedData[this.idxConta] = res.data.query
                
                toastr['success'](res.data.message);   
                            
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })

            .finally(() => {
                $('.absolute-loading').hide();
            });
    }

 
}));

$(document).ready(() => {
    $('#modal-resumo-paciente,#modal-historico-paciente').modal({
        backdrop: 'static',
        show: false
    })
})
