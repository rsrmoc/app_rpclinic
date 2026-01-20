
Alpine.data('app', () => ({
    checkBoleto: [],
    categorias,
    setores,
    eventos,
    formas,
    BoletoCartao: false,
    disabledInputsPagamento: false,
    inputsFilter: {
        tp_pesquisa: false,
        dt_inicial: null,
        dt_final: null,
        cd_categoria: [],
        notCategoria: false,
        cd_setor: [],
        notSetor: false,
        cd_fornecedor: [],
        notFornecedor: false,
        cd_conta: [],
        notConta: false,
        cd_forma: [],
        notForma: false,
        cd_turma: [],
        notTurma: false,
        ds_boleto: null,
        nr_documento: null,

        credito: true,
        debito: true,
        realizado: true,
        n_realizado: true,
        vencido: true,
        a_vencer: true,
        transferencia: true,
        lancamentos: true,

        ano: new Date().getFullYear(),
        mes: new Date().getMonth() + 1,
        colunaOrdenada: null,
        direcaoOrdenacao: null,
        page: null,
        itemsPerPage: 50,
    },
    boletoSelecionado: null,
    boletos: [],
    boletosSaldoAnterior: 0,
    totalReceita: 0,
    totalDespesa: 0,
    classStatus: {
        'Recebido': 'label-recebido',
        'A receber': 'label-warning',
        'Pago': 'label-info',
        'Vencido': 'label-danger',
        'A vencer': 'label-aguardando'
    },
    loadingExclusao: false,
    loadingEstorno: false,
    loadingUpdate: false,
    inputsValorPagoShow: false,
    opcaoValorRestante: 'confirmar',
    inputsLancamentoValorRestante: {
        data_vencimento: null,
        parcela_descricao: false,
        valor_restante: null
    },
    labelResumo:{   
        vl_receita: 0,
        vl_despesa: 0,
        loadingSaldo: false,
    },

    buttonPesquisar: '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Pesquisar',
    labelSaldo:'R$ 0,00',

    templateButtonSalvar: '<i class="fa fa-check"></i> Salvar',
    templateButtonSalvando: " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando ",
    buttonSalvar: '<i class="fa fa-check"></i> Salvar',

    /* Paginação */
    paginatedData: [], 
    currentPage: 1, 
    itemsPerPage: 20,
    totalPages: 0,
    loadingPesq: false,
    retornoLista: null, 

    init() {
 
        this.getPage();
 
        /* Categoria */
         $('#filterCategoria').on('select2:select', () => { 
            this.inputsFilter.cd_categoria = $('#filterCategoria').val(); 
        });

        $('#filterCategoria').on('select2:unselect', () => { 
            this.inputsFilter.cd_categoria = $('#filterCategoria').val(); 
        });

         /* Setor */
         $('#filterSetor').on('select2:select', () => { 
            this.inputsFilter.cd_setor = $('#filterSetor').val(); 
        });

        $('#filterSetor').on('select2:unselect', () => { 
            this.inputsFilter.cd_setor = $('#filterSetor').val(); 
        });

        /* Fornecedor */
        $('#filterFornecedor').on('select2:select', () => { 
            this.inputsFilter.cd_fornecedor = $('#filterFornecedor').val(); 
        });

        $('#filterFornecedor').on('select2:unselect', () => { 
            this.inputsFilter.cd_fornecedor = $('#filterFornecedor').val(); 
        });

        /* Forma */
        $('#filterForma').on('select2:select', () => { 
            this.inputsFilter.cd_forma = $('#filterForma').val(); 
        });

        $('#filterForma').on('select2:unselect', () => { 
            this.inputsFilter.cd_forma = $('#filterForma').val(); 
        });
 
        /* Conta */
        $('#filterConta').on('select2:select', () => { 
            this.inputsFilter.cd_conta = $('#filterConta').val(); 
        });

        $('#filterConta').on('select2:unselect', () => { 
            this.inputsFilter.cd_conta = $('#filterConta').val(); 
        });
        
        /* Turma */
        $('#filterTurma').on('select2:select', () => { 
            this.inputsFilter.cd_turma = $('#filterTurma').val(); 
        });

        $('#filterTurma').on('select2:unselect', () => { 
            this.inputsFilter.cd_turma = $('#filterTurma').val(); 
        });
        

        $('#opcoesDispiniveisValorPago').on('select2:select', (evt) => this.opcaoValorRestante = evt.params.data.id);
        //this.submitFilters();
    },
 
    registroPagina(qtd) {
        this.inputsFilter.itemsPerPage= qtd; 
        this.getPage();
    },
 
    goToPage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.inputsFilter.page = page;
            this.currentPage = page;
            this.inputsFilter.page = this.currentPage;
            this.getPage();
        }
    },

    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.currentPage++;
            this.inputsFilter.page = this.currentPage;
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
        this.buttonPesquisar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Pesquisando ";
        axios.get('/rpclinica/json/financeiro-json-modal', {
            params: this.inputsFilter
        })
            .then((res) => {  
                console.log(res.data);
                this.labelSaldo =  (res.data.saldo_anterior) ? this.formatValor(res.data.saldo_anterior) : '0,00';
                this.retornoLista = res.data.boletos.data; 
                this.totalPages = res.data.boletos.last_page;  
                this.paginatedData = this.retornoLista; 
                this.labelResumo.vl_receita = res.data.vlReceita;
                this.labelResumo.vl_despesa = res.data.vlDespesa;
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
            })
            .finally(() => { 
                this.loadingPesq = false;
                this.buttonPesquisar = '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Pesquisar';
                this.inputsFilter.tp_pesquisa=false;
            });
    
    },
    
    submitFilters() {
        this.inputsFilter.tp_pesquisa=true;
        this.getPage();
    },
 
    ordenarPor: function ordenarPor(coluna) {
        this.inputsFilter.colunaOrdenada = coluna;

        $('.glyphicon-sort-by-attributes-alt, .glyphicon-sort-by-attributes').removeClass('glyphicon-sort-by-attributes-alt glyphicon-sort-by-attributes');

        $('.' + coluna).addClass(this.inputsFilter.direcaoOrdenacao === 'ASC' ? 'glyphicon glyphicon-sort-by-attributes-alt' : 'glyphicon glyphicon-sort-by-attributes');

        $('.' + coluna).css({
            'display': 'inline',
            'width': '5px'
        });

        this.inputsFilter.direcaoOrdenacao = this.inputsFilter.direcaoOrdenacao === 'ASC' ? 'DESC' : 'ASC';

       // this.submitFilters();
    },

    checkAll() {
        $('.checkOne').prop('checked', $('.checkAll').prop('checked'));
    },

    resetDatas() {
        this.inputsFilter.dt_final = null;
        this.inputsFilter.dt_inicial = null;
    },

    Quitar(){
  
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja quitar as parcela selecionadas?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                 
                let form = new FormData(document.querySelector('#form-boletos'));
                axios.post(`/rpclinica/json/financeiro-quitar-boleto/RELACAO`,form)
                    .then((res) => {
                        console.log(res.data);
                        this.getPage();
                        document.getElementById("form-boletos").reset();
                        toastr['success']('Parcelas quitadas com sucesso!');

                    })
                    .catch((err) => parseErrorsAPI(err))
                    .finally(() => this.loadingEstorno = false);
                

            }
        });

    },

    QuitarRapido(dados){
  
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja quitar a parcela selecionada?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                  
                axios.post(`/rpclinica/json/financeiro-quitar-boleto/RAPIDO`,dados)
                    .then((res) => { 
                        this.getPage(); 
                        $('#modal-parcela').modal('hide');
                        toastr['success']('Parcelas quitadas com sucesso!');

                    })
                    .catch((err) => parseErrorsAPI(err))
                    .finally(() => this.loadingEstorno = false);
                

            }
        });

    },

    Excluir(){
  
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir as parcelas selecionadas?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                 
                let form = new FormData(document.querySelector('#form-boletos'));
                axios.post(`/rpclinica/json/financeiro-quitar-boleto`,form)
                    .then((res) => {
                        console.log(res.data);
                        this.getPage();
                        document.getElementById("form-boletos").reset();
                        toastr['success']('Parcelas exluidas com sucesso!');

                    })
                    .catch((err) => parseErrorsAPI(err))
                    .finally(() => this.loadingEstorno = false);
                

            }
        });

    },

    selectMonth(month) {
        this.inputsFilter.mes = month;
        this.resetDatas();
        this.getPage();
    },
    prevMonth() {
        this.inputsFilter.mes = this.inputsFilter.mes > 0 ? this.inputsFilter.mes - 1 : this.inputsFilter.mes;
        this.resetDatas();
        this.getPage();
    },
    nextMonth() {
        this.inputsFilter.mes = this.inputsFilter.mes < 12 ? this.inputsFilter.mes + 1 : this.inputsFilter.mes;
        this.resetDatas();
        this.getPage();
    }, 
    prevYear() {
        this.inputsFilter.ano = this.inputsFilter.ano - 1;
        this.resetDatas();
        this.getPage();
    },
    nextYear() {
        this.inputsFilter.ano = this.inputsFilter.ano + 1;
        this.resetDatas();
        this.getPage();
    },


    /*
    submitFilters() {
    

        if ( $('#inputsFilterVencido').is(":checked") ) { this.inputsFilter.vencido = true; } else { this.inputsFilter.vencido = false; }
        if ( $('#inputsFilterAvencer').is(":checked") ) { this.inputsFilter.a_vencer = true; } else { this.inputsFilter.a_vencer = false; }

        if ( $('#inputsFilterTransferencia').is(":checked") ) { this.inputsFilter.transferencia = true; } else { this.inputsFilter.transferencia = false; }
        if ( $('#inputsFilterLancamentos').is(":checked") ) { this.inputsFilter.lancamentos = true; } else { this.inputsFilter.lancamentos = false; }
        
        if ( $('#inputsFilterNrealizado').is(":checked") ) { this.inputsFilter.n_realizado = true; } else { this.inputsFilter.n_realizado = false; }
        if ( $('#inputsFilterRealizado').is(":checked") ) { this.inputsFilter.realizado = true; } else { this.inputsFilter.realizado = false; }
        
        if ( $('#inputsFilterDebito').is(":checked") ) { this.inputsFilter.debito = true; } else { this.inputsFilter.debito = false; }
        if ( $('#inputsFilterCredito').is(":checked") ) { this.inputsFilter.credito = true; } else { this.inputsFilter.credito = false; }

        this.labelSaldo.loadingSaldo =true;
        this.loadingPesq = true;  
        this.buttonPesquisar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Pesquisando ";
        this.labelSaldo = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> ";
        this.paginatedData=[]; 
        axios.get('/rpclinica/json/financeiro-relacao', {
            params: this.inputsFilter
        })
            .then((res) => {  
                this.labelSaldo =  (res.data.saldo_anterior) ? this.formatValor(res.data.saldo_anterior) : '0,00';
                this.retornoLista = res.data.boletos;
                this.boletos = res.data.boletos;  
                this.totalPages = Math.ceil(this.retornoLista.length / this.itemsPerPage); 
                //this.paginateData();
 
                this.labelResumo.vl_receita = res.data.saldo_receita;
                this.labelResumo.vl_despesa = res.data.saldo_despesa;
               
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
                this.labelSaldo = 'R$ --';
            })
            .finally(() => { 
                this.loadingPesq = false;
                this.buttonPesquisar = '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Pesquisar';
                this.labelSaldo.loadingSaldo =false;
                
            });    

      
    },
    */

    selecionarBoleto(boleto) {
        this.clear();
        this.boletoSelecionado = Object.assign({}, boleto);
        this.disabledInputsPagamento = false;
        console.log(this.boletoSelecionado); 

        if(boleto.conta.tp_conta == 'CA'){
            this.disabledInputsPagamento = true;
            this.BoletoCartao =true;
            this.opcaoValorRestante = 'confirmar';
        }else{
            this.disabledInputsPagamento = false;
            this.BoletoCartao =false;
        }
 
        $('#modalParcelaCategoria').empty();
        $('#modalParcelaCategoria').select2({
            data: [
                { id: '', text: 'Selecione' },
                ...this.categorias.filter((cat) => cat.cd_empresa == this.boletoSelecionado.cd_empresa).map((cat) => ({
                    id: cat.cd_categoria,
                    text: cat.nm_categoria,
                    disabled: cat.lanc,
                    element: HTMLOptionElement
                }))
            ]
        });

  
        $('#lancamentosTurmas').on('select2:select', function (evt) { 
      
            let turmaId = evt.params.data.id;
            $('#lancamentosEventos').empty();
            $('#lancamentosEventos').select2({
                data: [
                    { id: '', text: 'Selecione' },
                    ...eventos.filter((cat) => cat.cd_turma == turmaId).map((cat) => ({
                        id: cat.cd_evento,
                        text: cat.nm_evento,  
                    }))
                ]
            }); 
        });


        $('#modalParcelaConta').on('select2:select', (evt) => {
            let el = evt.params.data.element;
            let contaId = evt.params.data.id; 
            this.disabledInputsPagamento = (contaId && el.dataset.tp == 'CA');
            this.tp_conta = el.dataset.tp;

            if(el.dataset.tp == 'CA'){
                $('#modalParcelaForma').empty();
                $('#modalParcelaForma').select2({
                    data: [
                        { id: '', text: 'Selecione' },
                        ...this.formas.filter((fo) => fo.tipo == 'CA').map((arr) => ({
                            id: arr.cd_forma_pag,
                            text: arr.nm_forma_pag,  
                        }))
                    ]
                }); 
                console.log(formas);
            }else{
                $('#modalParcelaForma').empty();
                $('#modalParcelaForma').select2({
                    data: [
                        { id: '', text: 'Selecione' },
                        ...this.formas.filter((fo) => fo.tipo != 'CA').map((arr) => ({
                            id: arr.cd_forma_pag,
                            text: arr.nm_forma_pag,  
                        }))
                    ]
                }); 
            }
            
        });


        $('#lancamentosEventos').empty();
        $('#lancamentosEventos').select2({
            data: [
                { id: '', text: 'Selecione' },
                ...this.eventos.filter((even) => even.cd_turma == this.boletoSelecionado.cd_turma).map((even) => ({
                    id: even.cd_evento,
                    text: even.nm_evento
                }))
            ]
        })

        $('#modalParcelaCategoria').val(this.boletoSelecionado.cd_categoria).trigger('change');
        $('#modalParcelaConta').val(this.boletoSelecionado.cd_conta).trigger('change');
        $('#modalParcelaForma').val(this.boletoSelecionado.cd_forma).trigger('change');
        $('#modalParcelaFornecedor').val(this.boletoSelecionado.cd_fornecedor).trigger('change');
        $('#modalParcelaSetor').val(this.boletoSelecionado.cd_setor).trigger('change');
        $('#modalParcelaMarca').val(this.boletoSelecionado.cd_marca).trigger('change');
        $('#lancamentosTurmas').val(this.boletoSelecionado.cd_turma).trigger('change');
        $('#lancamentosEventos').val(this.boletoSelecionado.cd_evento).trigger('change'); 
        //$('#modalParcelaDescricao').val(this.boletoSelecionado.ds_boleto);
       // $('#modalParcelaDocumento').val(this.boletoSelecionado.doc_boleto);
       // $('#modalParcelaEmissao').val(this.boletoSelecionado.dt_emissao);
        //$('#modalParcelaVencimento').val(this.boletoSelecionado.dt_vencimento);
        //$('#modalParcelaPagamento').val(this.boletoSelecionado.dt_pagrec);
        $('#modalParcelaCompra').val(this.boletoSelecionado.data_compra);  
 
        //$('#modalParcelaValor').val( this.formatValor(this.boletoSelecionado.vl_boleto).replace('R$ ', '') );
        //$('#modalParcelaValorPago').val( this.formatValor(this.boletoSelecionado.vl_pagrec).replace('R$ ', '') );

        if (this.boletoSelecionado.tipo == 'despesa') {
            document.querySelector('#modalParcelaDespesa').click(); 
        }
        if (this.boletoSelecionado.tipo == 'receita') {
            document.querySelector('#modalParcelaReceita').click(); 
        }
  
        $('#modal-parcela').modal('toggle');
    },

 

    calcSaldoTotalBoletos() {
        return this.retornoLista.reduce(
            (accumulator, boleto) => (boleto.tipo == 'receita' ? accumulator + boleto.vl_boleto : accumulator - boleto.vl_boleto), 0
        );
    },

    calcSaldoTotalBoletosReceita() {
        return this.retornoLista.reduce(
            (accumulator, boleto) => (boleto.tipo == 'receita' ? accumulator + boleto.vl_boleto : accumulator - 0), 0
        );
    },

    calcSaldoTotalBoletosDespesa() {
        return this.retornoLista.reduce(
            (accumulator, boleto) => (boleto.tipo == 'despesa' ? accumulator + boleto.vl_boleto : accumulator - 0), 0
        );
    },

    formatValor(valor) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valor);
    },

    excluirParcela() {

        if(this.boletoSelecionado.conta.tp_conta=='CA'){
            if(this.boletoSelecionado.situacao=='QUITADO'){
               return toastr['error']("Documento  do tipo cartão e quitado. <br>Não será possivel e exclusão!");
            }
        }

        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir essa parcela?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.loadingExclusao = true;

                axios.delete(`/rpclinica/json/financeiro-excluir-parcela/${this.boletoSelecionado.cd_documento_boleto}`)
                    .then((res) => {
                        let indexBoleto = this.boletos.findIndex((boleto) => boleto.cd_documento_boleto == this.boletoSelecionado.cd_documento_boleto);
                        this.boletos.splice(indexBoleto, 1); 
                        this.submitFilters();
                        toastr['success'](res.data.message);
                        $('#modal-parcela').modal('toggle');
                    })
                    .catch((err) => parseErrorsAPI(err))
                    .finally(() => this.loadingExclusao = false);
            }
        });
    },

    estornarParcela() {

        if(this.boletoSelecionado.conta.tp_conta=='CA'){
            if(this.boletoSelecionado.situacao=='QUITADO'){
               return toastr['error']("Documento  do tipo cartão e quitado. <br>Não será possivel e estornar!");
            }
        }

        if(this.boletoSelecionado.tp_mov=='TR'){
            return toastr['error']("Não permitido estornar documento  do tipo Transferencia.");
        }

        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir essa parcela?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.loadingEstorno = true;

                axios.put(`/rpclinica/json/financeiro-estornar-parcela/${this.boletoSelecionado.cd_documento_boleto}`)
                    .then((res) => {
                        let indexBoleto = this.boletos.findIndex((boleto) => boleto.cd_documento_boleto == this.boletoSelecionado.cd_documento_boleto);
                        this.boletos.splice(indexBoleto, 1);
                        this.submitFilters();
                        toastr['success'](res.data.message);
                        $('#modal-parcela').modal('toggle');
                    })
                    .catch((err) => parseErrorsAPI(err))
                    .finally(() => this.loadingEstorno = false);
            }
        });
    },

    clear() {
        this.inputsValorPagoShow = false;
        this.opcaoValorRestante = 'confirmar',
            $('#opcoesDispiniveisValorPago').val('confirmar').trigger('change');
        this.inputsLancamentoValorRestante = {
            data_vencimento: null,
            parcela_descricao: false,
            valor_restante: null
        };
        this.disabledInputsPagamento= false; 
    },

    updateParcela(testValor = true) {

        let valor_pago = 0;
        let valor = parseFloat($('#modalParcelaValor').val().replace('.', '').replace(',', '.'));
        if(this.BoletoCartao == false){ 
             valor_pago = parseFloat($('#modalParcelaValorPago').val().replace('.', '').replace(',', '.'));
    
            if (testValor && valor_pago < valor) {
                this.inputsLancamentoValorRestante.valor_restante = valor - valor_pago;
                this.inputsValorPagoShow = true;
                return;
            }
        }else{ 
            
        }
 
        
 
        let form = new FormData(document.querySelector('#modalParcelaForm'));
        form.set('id', this.boletoSelecionado.cd_documento_boleto);
        form.set('vl_boleto', valor);
        form.set('vl_pagrec', valor_pago);

        if (this.inputsValorPagoShow && this.opcaoValorRestante == 'gerar') {
            form.set('valor_restante[data_vencimento]', this.inputsLancamentoValorRestante.data_vencimento);
            form.set('valor_restante[parcela_descricao]', this.inputsLancamentoValorRestante.parcela_descricao);
            form.set('valor_restante[valor_restante]', this.inputsLancamentoValorRestante.valor_restante);
        } 
        this.buttonSalvar = this.templateButtonSalvando;
        axios.post(`/rpclinica/json/financeiro-update-lancamento`, form)
            .then((res) => {
                let indexBoleto = this.boletos.findIndex((boleto) => boleto.cd_documento_boleto == this.boletoSelecionado.cd_documento_boleto);
                this.boletos[indexBoleto] = res.data.boleto;

                if (this.inputsValorPagoShow) {
                    this.clear();
                }

                toastr['success'](res.data.message);
                this.submitFilters();
                $('#modal-parcela').modal('toggle')
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => {
                this.buttonSalvar = this.buttonSalvar = this.templateButtonSalvar;
                this.loadingUpdate = false;
            });
          
    }

}));

$(document).ready(() => {
    $('#modal-parcela').modal({
        backdrop: 'static',
        show: false
    })
});
