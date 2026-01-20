Alpine.data('app', () => ({
    loading: false,
    retornoLista: null,
    paginatedData: [],
    currentPage: 1,
    itemsPerPage: 10,
    totalPages: 0,
    index: 0,

    loadingPesq: false,
    buttonSalvar: "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ",
    infoModal: {},
    modalTitulo: '',
    buttonSalvarHist: ' <i  class="fa fa-check"></i> Salvar ',
    buttonSalvarForm: ' <i  class="fa fa-check"></i> Salvar ',
    tempSalvarHist: ' <i  class="fa fa-check"></i> Salvar ',

    classSituacaoReserva: {
        A: 'btn-aberto',
        S: 'btn-rss',
        C: 'btn-danger',
        N: 'btn-danger',
        F: 'btn-success',
        U: 'btn-info'
    },

    situacaoReserva: {
        A: '<i class="fa fa-chain " style="padding-left:2px;"></i> Aberta',
        S: '<i class="fa fa-mail-forward" style="padding-left:2px;"></i> Solicitada',
        C: '<i class="fa fa-ban" style="padding-left:2px;"></i> Cancelada',
        N: '<i class="fa fa-close " style="padding-left:2px;"></i> Negada',
        U: '<i class="fa fa-check-circle-o" style="padding-left:2px;"></i> Autorizada',
        F: '<i class="fa fa-check" style="padding-left:2px;"></i> Finalizada',
    },

    formsData:{
        cd_reserva: null,
        cd_convenio: null,
        valor: null,
        sn_negociado: null,
        dt_autorizacao: null,
        dt_solicitacao: null,
        guia: null,
        situacao: null,
        opme: null,
        obs: null, 
        agendamento: null,
    },
    
    init() {
        this.getPage();
    },

    paginateData() {
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        this.paginatedData = this.retornoLista.slice(startIndex, endIndex);
    },

    goToPage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
            this.paginateData();
        }
    },

    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.currentPage++;
            this.paginateData();
        }
    },

    previousPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.paginateData();
        }
    },

    getPage() {

        this.loadingPesq = true;
        this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i>  ";
        let form = new FormData(document.querySelector('#form-parametros'));
        axios.post(`/rpclinica/json/reserva-cirurgia`, form)
            .then((res) => {
                this.retornoLista = res.data.query;
                this.totalPages = Math.ceil(this.retornoLista.length / this.itemsPerPage);
                this.paginateData();
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.buttonSalvar = "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ";
                this.loadingPesq = false;
            });
    },

    addHistory() {
 
        this.buttonSalvarHist = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando  ";
        let form = new FormData(document.querySelector('#form_RESERVA_CIRURGIA'));
        form.append('cd_reserva_cirurgia', this.infoModal.cd_reserva_cirurgia)
        axios.post(`/rpclinica/json/reserva-cirurgia/addHist`, form)
            .then((res) => {   
                this.infoModal.notes = res.data.hist;   
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => { 
                this.buttonSalvarHist = this.tempSalvarHist;
            });
    },

    clickModal(dados) {
        
        $('#form_FORMULARIO #idConvenio').on('select2:select', () => {
           this.formsData.cd_convenio = $('#form_FORMULARIO select#idConvenio').val(); 
        });
        $('#form_FORMULARIO #idOpme').on('select2:select', () => {
            this.formsData.opme = $('#form_FORMULARIO select#idOpme').val(); 
        });
        $('#form_FORMULARIO #idSituacao').on('select2:select', () => {
            this.formsData.situacao = $('#form_FORMULARIO select#idSituacao').val(); 
        });
        $('#form_FORMULARIO #idNegociado').on('select2:select', () => {
            this.formsData.sn_negociado = $('#form_FORMULARIO select#idNegociado').val(); 
        });

        this.infoModal = dados;
        this.modalTitulo = dados.agendamento.paciente.nm_paciente + ' { '+ dados.cd_agendamento + ' } ' ;
        axios.get(`/rpclinica/json/reserva-cirurgia/getHist/${dados.cd_reserva_cirurgia}`)
            .then((res) => {
                this.infoModal.notes = res.data;
                console.log(this.infoModal); 
                $('.modalDetalhes').modal('toggle');
                $('#form_FORMULARIO #idConvenio').val(this.infoModal.cd_convenio).trigger('change');
                $('#form_FORMULARIO #idOpme').val(this.infoModal.cd_opme).trigger('change');
                $('#form_FORMULARIO #idSituacao').val(this.infoModal.situacao).trigger('change');
                $('#form_FORMULARIO #idNegociado').val(this.infoModal.sn_negociado).trigger('change');
                this.formsData.dt_autorizacao = this.infoModal.dt_autorizacao;
                this.formsData.dt_solicitacao = this.infoModal.dt_solicitacao;
                this.formsData.guia = this.infoModal.guia;
                this.formsData.valor = (this.infoModal.valor) ? this.formatValor(this.infoModal.valor) : null;
                this.formsData.agendamento = this.infoModal.agendamento_reserva;
                this.formsData.obs = this.infoModal.comentarios_form;
                this.formsData.cd_convenio = this.infoModal.cd_convenio;
                this.formsData.opme = this.infoModal.cd_opme;
                this.formsData.situacao = this.infoModal.situacao;
                this.formsData.sn_negociado = this.infoModal.sn_negociado;
                console.log();
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            });
    },
 
    saveFormulario() { 
        this.formsData.cd_reserva=this.infoModal.cd_reserva_cirurgia;
        this.buttonSalvarForm = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando  ";  
        axios.post(`/rpclinica/json/reserva-cirurgia/addForm`, this.formsData)
            .then((res) => {  
                toastr['success']('Formulario atualizado com sucesso!');
                this.getPage();
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => { 
                this.buttonSalvarForm = this.tempSalvarHist;
            });
    },

    FormatData(data) {
        var dt = data.split(" ")[0];
        var dia  = dt.split("-")[2];
        var mes  = dt.split("-")[1];
        var ano  = dt.split("-")[0];
      
        return ("0"+dia).slice(-2) + '/' + ("0"+mes).slice(-2) + '/' + ano;
      
    },

    formatValor(valor) {
        return Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(valor).replaceAll("R$ ", "");
    },

    nl2br (str, is_xhtml) { 
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display
      
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

}));
