Alpine.data('app', () => ({
    inputsFilter: {
        dt_inicial: null,
        dt_final: null,
        cd_cartao: null,
        cd_forma: null,
        aberto: false,
        fechado: false,
        quitado: false, 
        ano: new Date().getFullYear(),
        mes: new Date().getMonth() + 1
    },
    cartaoFiltroSelecionado: null,
    cartoes: [],
    cartaoSelecionado: null,
    loadingModalCartao: false,
    inputsModalCartao: {
        cd_empresa: null,
        cd_conta_pag: null,
        cd_forma: null,
        descricao: null,
        documento: null,
        dt_pagamento: null,
        vl_pago: null
    },
    classStyleStatusCartao: {
        ABERTA: 'label-primary',
        QUITADA: 'label-success',
        FECHADA: 'label-danger',
        VENCIDA: 'label-danger', 
    },
    templateButtonSalvar: '<i class="fa fa-check"></i> Salvar',
    templateButtonSalvando: " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando ",
    buttonSalvar: '<i class="fa fa-check"></i> Salvar',
    saldoCartao: null, 

    init() {

        $('#filtersCartao').on('select2:select', (evt) => {
            this.inputsFilter.cd_cartao = evt.params.data.id; 
            this.cartaoFiltroSelecionado = cartoesParaSelecao.find((cartao) => cartao.cd_conta == evt.params.data.id);
            if(evt.params.data.id){
                this.saldoCartao = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> ";
                axios.get('/rpclinica/json/financeiro-info-cartao/'+evt.params.data.id)
                .then((res) => {
                    this.saldoCartao = this.formatValor(res.data.saldo);
                })
                .catch((err) => {
                    toastr['error'](err.response.data.message, 'Erro');
                    this.saldoCartao= null;
                }) 
            }

            
        });

        $('#filtersForma').on('select2:select', (evt) => this.inputsFilter.cd_forma = evt.params.data.id);

        $('#empresaModalCartao').on('select2:select', (evt) => this.inputsModalCartao.cd_empresa = evt.params.data.id);

        $('#contaModalCartao').on('select2:select', (evt) => this.inputsModalCartao.cd_conta_pag = evt.params.data.id);

        $('#formaModalCartao').on('select2:select', (evt) => this.inputsModalCartao.cd_forma = evt.params.data.id);

        $('#modal-detalhes').on('hidden.bs.modal', () => this.clearInputsModalCartao());

        this.submitFilters();

    },

    prevYear() {
        this.inputsFilter.ano = this.inputsFilter.ano - 1;
        this.submitFilters();
    },
    nextYear() {
        this.inputsFilter.ano = this.inputsFilter.ano + 1;
        this.submitFilters();
    },

    selectMonth(month) {
        this.inputsFilter.mes = month;
        this.submitFilters();
    },
    prevMonth() {
        this.inputsFilter.mes = this.inputsFilter.mes > 1 ? this.inputsFilter.mes - 1: this.inputsFilter.mes;
        this.submitFilters();
    },
    nextMonth() {
        this.inputsFilter.mes = this.inputsFilter.mes < 12 ? this.inputsFilter.mes + 1: this.inputsFilter.mes;
        this.submitFilters();
    },

    submitFilters() {

        
        if ( $('#checkboxAberto').is(":checked") ) { this.inputsFilter.aberto = true; } else { this.inputsFilter.aberto = false; }
        if ( $('#checkboxFechado').is(":checked") ) { this.inputsFilter.fechado = true; } else { this.inputsFilter.fechado = false; }
        if ( $('#checkboxQuitado').is(":checked") ) { this.inputsFilter.quitado = true; } else { this.inputsFilter.quitado = false; } 

        axios.get(`/rpclinica/json/financeiro-relacao-cartao`, {
            params: this.inputsFilter
        })
            .then((res) => {
                this.cartoes = res.data.query;
                console.log(res.data);
            })
            .catch((err) => parseErrorsAPI(err));
    },

    sumValueItemsCartoes(items) {
        if (!items) return 0;

        let val = items.reduce((accumulator, item) => item.vl_boleto + accumulator, 0);

        return val;
    },

    selectCartao(cartao) { 
        this.cartaoSelecionado = cartao;
        console.log(cartao);
        $('#empresaModalCartao').val(this.cartaoSelecionado.cd_empresa).trigger('change');
        $('#contaModalCartao').val(this.cartaoSelecionado.cd_conta_pag).trigger('change');
        $('#formaModalCartao').val(this.cartaoSelecionado.cd_forma).trigger('change');


        $('#modal-detalhes').modal('show');
    },

    formatValor(valor) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valor);
    },

    fecharFatura() {
        this.loadingModalCartao = true;

        axios.post('/rpclinica/json/financeiro-fechar-cartao',this.cartaoSelecionado)
            .then((res) => { 

                this.submitFilters();
                toastr['success']('Fatura fechada!');
                $('#modal-detalhes').modal('toggle')
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loadingModalCartao = false);
    },

    atualizarFatura() {
          
        this.buttonSalvar= this.templateButtonSalvando;
        let form = new FormData(document.querySelector('#atualizarFatura'));
        form.set('cd_fatura', this.cartaoSelecionado.cd_fatura);
        axios.post('/rpclinica/json/financeiro-atualizar-cartao', form)
            .then((res) => {
             
               this.submitFilters();
               toastr['success']('Fatura atualizada!');
               $('#modal-detalhes').modal('toggle');
               
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => {
                this.buttonSalvar= this.templateButtonSalvar;
                this.loadingModalCartao = false
            });
    },

    abrirFatura(cdFatura) {
        this.loadingModalCartao = true;

        axios.post('/rpclinica/json/financeiro-abrir-cartao', { cd_fatura: cdFatura })
            .then((res) => {
                this.cartaoSelecionado.fatura = res.data; 
                this.submitFilters();
                toastr['success']('Fatura aberta!');
                $('#modal-detalhes').modal('toggle')
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loadingModalCartao = false);
    },

    estornarFatura(cdFatura){
        this.loadingModalCartao = true;

        axios.post('/rpclinica/json/financeiro-estornar-cartao', { cd_fatura: cdFatura })
            .then((res) => { 

                this.submitFilters();
                toastr['success']('Fatura Estornada!');
                $('#modal-detalhes').modal('toggle');

            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => this.loadingModalCartao = false);
    
    },

    clearInputsModalCartao() {
        this.inputsModalCartao = {
            cd_empresa: null,
            cd_conta_pag: null,
            cd_forma: null,
            descricao: null,
            documento: null,
            dt_pagamento: null,
            vl_pago: null
        };

        $('#empresaModalCartao').val(null).trigger('change');

        $('#contaModalCartao').val(null).trigger('change');

        $('#formaModalCartao').val(null).trigger('change');
    }
}));

$(document).ready(() => {
    $('#modal-detalhes').modal({
        backdrop: 'static',
        show: false
    })
});
