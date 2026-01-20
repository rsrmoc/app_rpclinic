import moment from "moment";

Alpine.data('appLancamentos', () => ({
    categorias,
    eventos,
    formas,
    setores,
    empresaUsuario,
    loadingAcao: null,
    disabledInputsPagamento: false,
    tp_conta: null,
    inputsLancamento: {
        cd_empresa: null,
        cd_categoria: null,
        cd_conta: null,
        cd_forma: null,
        cd_fornecedor: null,
        cd_setor: null,
        cd_marca: null,
        descricao: null,
        documento: null,
        dt_emissao: null,
        tp_lancamento: null,
        data_vencimento: null,
        data_compra: null,
        valor: null,
        data_pagamento: null,
        valor_pago: null,
        parcelar: false,
        dividir_parcelas: null,
        periodicidade: null,
        qtde_parcelas: null,
        cd_turma: null,
        cd_evento: null,
        parcelas: []
    },
    opcaoValorRestante: 'confirmar',
    inputsLancamentoValorRestante: {
        data_vencimento: null,
        parcela_descricao: false,
        valor_restante: null
    },
    contas: contasBancaria,
    loading: false,
    loadingModal: false,
    campoCadastro: {
        titulo: null,
        nm_campo: null,
        descricao: null,
        tipo_pessoa: null,
        tipo: null,
        documento: null,
        tp_cadastro: null, 
        grupo: null,
    },

    init() {

        $('#lancamentosEmpresas').val(this.empresaUsuario).trigger('change');
        this.inputsLancamento.cd_empresa = this.empresaUsuario;
        $('#lancamentosCategorias').empty();
        $('#lancamentosCategorias').select2({
            data: [
                { id: '', text: 'Selecione' },
                ...this.categorias.filter((cat) => cat.cd_empresa == this.empresaUsuario).map((cat) => ({
                    id: cat.cd_categoria,
                    text: cat.cod_estrutural+' - '+cat.nm_categoria,
                    disabled: cat.lanc,
                    element: HTMLOptionElement
                }))
            ]
        }); 

        $('#lancamentosSetores').empty();
        $('#lancamentosSetores').select2({
            data: [
                { id: '', text: 'Selecione' },
                ...this.setores.filter((set) => set.cd_empresa == this.inputsLancamento.cd_empresa).map((set) => ({
                    id: set.cd_setor,
                    text: set.nm_setor
                }))
            ]
        })

        $('#lancamentosEmpresas').on('select2:select', (evt) => {
            let empresaId = evt.params.data.id;
            this.inputsLancamento.cd_empresa = empresaId;
            
            $('#lancamentosCategorias').empty();
            $('#lancamentosCategorias').select2({
                data: [
                    { id: '', text: 'Selecione' },
                    ...this.categorias.filter((cat) => cat.cd_empresa == empresaId).map((cat) => ({
                        id: cat.cd_categoria,
                        text: cat.cod_estrutural+' - '+cat.nm_categoria,
                        disabled: cat.lanc,
                        element: HTMLOptionElement
                    }))
                ]
            }); 


        });

        $('#lancamentosCategorias').on('select2:select', (evt) => {
           
            this.inputsLancamento.cd_categoria = evt.params.data.id;
     
            let categoria = this.categorias.find((cat) => cat.cd_categoria == evt.params.data.id);

            if (categoria?.descricao) this.inputsLancamento.descricao = categoria.descricao;
           
            if (categoria?.cd_forma) {
                $('#lancamentosFormaPagamento').val(categoria.cd_forma).trigger('change');
                this.inputsLancamento.cd_forma = categoria.cd_forma; 
            }

            if (categoria?.cd_marca) {
                $('#lancamentosMarcas').val(categoria.cd_marca).trigger('change');
                this.inputsLancamento.cd_marca = categoria.cd_marca;
            }

            if (categoria?.cd_setor) {
                $('#lancamentosSetores').val(categoria.cd_setor).trigger('change');
                this.inputsLancamento.cd_setor = categoria.cd_setor;
            }

            if (categoria?.cd_conta) {
                $('#lancamentosConta').val(categoria.cd_conta).trigger('change');
                this.inputsLancamento.cd_conta = categoria.cd_conta;
                let conta = this.contas.find((con) => con.cd_conta == categoria.cd_conta);
                if(conta.tp_conta=='CA'){
                    this.disabledInputsPagamento = true;
                    this.tp_conta = 'CA';
                    
                    
                    $('#lancamentosFormaPagamento').empty();
                    $('#lancamentosFormaPagamento').select2({
                        data: [
                            { id: '', text: 'Selecione' },
                            ...this.formas.filter((fo) => fo.tipo == 'CA').map((arr) => ({
                                id: arr.cd_forma_pag,
                                text: arr.nm_forma_pag,  
                            }))
                        ]
                    }); 
                } else {
                    this.tp_conta = 'CC';

                    this.disabledInputsPagamento = false;
                    $('#lancamentosFormaPagamento').empty();
                    $('#lancamentosFormaPagamento').select2({
                        data: [
                            { id: '', text: 'Selecione' },
                            ...this.formas.filter((fo) => fo.tipo != 'CA').map((arr) => ({
                                id: arr.cd_forma_pag,
                                text: arr.nm_forma_pag,  
                            }))
                        ]
                    }); 
                }
            }

            if (categoria?.cd_fornecedor) {
                $('#lancamentosFornecedor').val(categoria.cd_fornecedor).trigger('change');
                this.inputsLancamento.cd_fornecedor = categoria.cd_fornecedor;
            }
            if (categoria?.tp_lancamento == 'DESPESA') document.querySelector('#labelLancamentosDespesa').click();

            if (categoria?.tp_lancamento == 'RECEITA') document.querySelector('#labelLancamentosReceita').click();

        

        });

        $('#lancamentosConta').on('select2:select', (evt) => {
            let el = evt.params.data.element;
            let contaId = evt.params.data.id;
            this.inputsLancamento.cd_conta = contaId; 
            this.disabledInputsPagamento = (contaId && el.dataset.tp == 'CA');
            this.tp_conta = el.dataset.tp;

            if(el.dataset.tp == 'CA'){
                this.tp_conta = 'CA';
                $('#lancamentosFormaPagamento').empty();
                $('#lancamentosFormaPagamento').select2({
                    data: [
                        { id: '', text: 'Selecione' },
                        ...this.formas.filter((fo) => fo.tipo == 'CA').map((arr) => ({
                            id: arr.cd_forma_pag,
                            text: arr.nm_forma_pag,  
                        }))
                    ]
                });  
            }else{
                
                this.tp_conta = 'CC';
                $('#lancamentosFormaPagamento').empty();
                $('#lancamentosFormaPagamento').select2({
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

        $('#lancamentosFormaPagamento').on('select2:select', (evt) => this.inputsLancamento.cd_forma = evt.params.data.id);

       // $('#lancamentosMarcas').on('select2:select', (evt) => this.inputsLancamento.cd_marca = evt.params.data.id);

        
        $('#lancamentosTurmas').on('select2:select', (evt) =>{
            this.inputsLancamento.cd_turma = evt.params.data.id;   
            let turmaId = evt.params.data.id;
            $('#lancamentosEventos').empty();
            $('#lancamentosEventos').select2({
                data: [
                    { id: '', text: 'Selecione' },
                    ...this.eventos.filter((cat) => cat.cd_turma == turmaId).map((cat) => ({
                        id: cat.cd_evento,
                        text: cat.nm_evento,  
                    }))
                ]
            }); 
        }); 
        $('#lancamentosEventos').on('select2:select', (evt) => this.inputsLancamento.cd_evento = evt.params.data.id );


        $('#lancamentosSetores').on('select2:select', (evt) => this.inputsLancamento.cd_setor = evt.params.data.id);

        $('#lancamentosFornecedor').on('select2:select', (evt) => this.inputsLancamento.cd_fornecedor = evt.params.data.id);

        $('#lancamentosParcelas').on('select2:select', (evt) => {
            
            if (!this.inputsLancamento.descricao?.trim()) {
                $('#lancamentosParcelas').val(null).trigger('change');
                toastr['error']('Preencha o campo de descrição!');
                return;
            }

            if (!this.inputsLancamento.documento) {
                 this.inputsLancamento.documento = this.getRandomIntInclusive('1000','99999'); 
            }
            
            if (!this.inputsLancamento.data_vencimento?.trim()) {
                $('#lancamentosParcelas').val(null).trigger('change');
                toastr['error']('Preencha o campo de vencimento!');
                return;
            }

            if (!this.inputsLancamento.valor?.trim()) {
                $('#lancamentosParcelas').val(null).trigger('change');
                toastr['error']('Preencha o campo de valor!');
                return;
            }

            if (!this.inputsLancamento.periodicidade?.trim()) {
                $('#lancamentosParcelas').val(null).trigger('change');
                toastr['error']('Preencha o campo de periodicidade!');
                return;
            }

            this.inputsLancamento.qtde_parcelas = parseInt(evt.params.data.id);
            let valor = this.inputsLancamento.dividir_parcelas
                ? parseFloat(this.inputsLancamento.valor.replace('.', '').replace(',', '.')) / this.inputsLancamento.qtde_parcelas
                : parseFloat(this.inputsLancamento.valor.replace('.', '').replace(',', '.'));

            let dtVencimento = moment(this.inputsLancamento.data_vencimento ?? new Date().getTime());
            let dtCompra = dtVencimento;
            // let dtPagamento = moment(this.inputsLancamento.data_pagamento ?? new Date().getTime());
            let optionsDiasPeriodicidade = { 'mensal': 31, 'quinzenal': 15, 'semanal': 7 };
            let dias = optionsDiasPeriodicidade[this.inputsLancamento.periodicidade] ?? 31;

            if (this.inputsLancamento.qtde_parcelas) this.inputsLancamento.parcelas = new Array(this.inputsLancamento.qtde_parcelas).fill().map((val, index) => {
                let parcela = {
                    descricao: `${this.inputsLancamento.descricao} ${index + 1}/${this.inputsLancamento.qtde_parcelas}`,
                    documento: this.inputsLancamento.documento + ' - ' + (index + 1) + '/' + this.inputsLancamento.qtde_parcelas,
                    data_vencimento: dtVencimento.format('YYYY-MM-DD'),
                    // data_pagamento: dtPagamento.format('YYYY-MM-DD'),
                    data_pagamento: null,
                    valor: this.formatValor(valor).toString().replace('R$ ', ''), 
                    valor_pago: null,
                    //data_compra: (dtCompra) ? dtCompra.add(0, 'days')._i : null
                    data_compra:   this.inputsLancamento.data_vencimento  
                    
                };
                 
                dtVencimento = dtVencimento.add(dias, 'days');
                if(this.tp_conta == 'CA'){ 
                    dtCompra = dtCompra; 
                }else{
                    
                    dtCompra = null;
                    
                }
                
                // dtPagamento = dtPagamento.add(dias, 'days');
                console.log(parcela);
                return parcela;
            });
            console.log(this.inputsLancamento.parcelas);
        });

        $('#lancamentosPeriodicidade').on('select2:select', (evt) => this.inputsLancamento.periodicidade = evt.params.data.id);

        $('#opcoesDispiniveisValorPago').on('select2:select', (evt) => this.opcaoValorRestante = evt.params.data.id);

 
    },

    formatValor(valor) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valor);
    },

    excluirParcela(index) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir essa parcela?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.inputsLancamento.parcelas.splice(index, 1);
            }
        });
    },

 

    getRandomIntInclusive(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    },
    
    clear() {
        $('#lancamentosEmpresas').val(null).trigger('change');
        $('#lancamentosCategorias').val(null).trigger('change');
        $('#lancamentosConta').val(null).trigger('change');
        $('#lancamentosFormaPagamento').val(null).trigger('change');
        $('#lancamentosMarcas').val(null).trigger('change');
        $('#lancamentosSetores').val(null).trigger('change');
        $('#lancamentosFornecedor').val(null).trigger('change');
        $('#lancamentosParcelas').val(null).trigger('change');
        $('#lancamentosPeriodicidade').val(null).trigger('change');
        $('#opcoesDispiniveisValorPago').val(null).trigger('change');
        $('#lancamentosTurmas').val(null).trigger('change');
        $('#lancamentosEventos').val(null).trigger('change');

        this.disabledInputsPagamento = false;
        this.inputsLancamento = {
            cd_empresa: null,
            cd_categoria: null,
            cd_conta: null,
            cd_forma: null,
            cd_fornecedor: null,
            cd_setor: null,
            cd_marca: null,
            descricao: null,
            documento: null,
            tp_lancamento: null,
            data_vencimento: null,
            data_compra: null,
            valor: null,
            data_pagamento: null,
            valor_pago: null,
            parcelar: false,
            dividir_parcelas: null,
            periodicidade: null,
            qtde_parcelas: null,
            parcelas: []
        };

        this.opcaoValorRestante = 'confirmar',
        this.inputsLancamentoValorRestante = {
            data_vencimento: null,
            parcela_descricao: false,
            valor_restante: null
        };
    },

    salvarLancamento(testValor = true) {
 
        $('.absolute-loading').show();
        this.loadingAcao = "Cadastrando Lançamento..."; 

        let valor = parseFloat(this.inputsLancamento.valor.replace('.', '').replace(',', '.'));
        let valor_pago = parseFloat(this.inputsLancamento.valor_pago?.replace('.', '')?.replace(',', '.'));

        if (testValor && this.inputsLancamento.valor_pago && valor_pago < valor) {
            this.inputsLancamentoValorRestante.valor_restante = valor - valor_pago;
            $('#modalOpcoesValorPago').modal('show');
            return;
        }
        this.loading = true;
        let data = Object.assign({}, this.inputsLancamento);
        data.valor = this.inputsLancamento.valor;
        data.valor_pago = this.inputsLancamento.valor_pago;
        
        /*
        data.parcelas.forEach((parcela) => {
            parcela.valor = parcela.valor.replace('.', '').replace(',', '.'); 
            parcela.valor_pago = (parcela.valor_pago) ? parcela.valor_pago?.replace('.', '')?.replace(',', '.') : null;
    
        });
        */
       
        axios.post('/rpclinica/json/financeiro-store-lancamento', data)
            .then((res) => {
                this.clear();
                window.location.href = '/rpclinica/financeiro-listar?';
                toastr["success"](res.data.message);
            }) 
            .catch((err) => {
                parseErrorsAPI(err);
                $('#lancamentosParcelas').val(null).trigger('change');
            })
            .finally(() => {
                this.loading = false
                $('.absolute-loading').hide();
            });
    },

    submitModalValorRestante() {
        $('#modalOpcoesValorPago').modal('toggle');
        if (this.opcaoValorRestante == 'confirmar') return this.salvarLancamento(false);

        this.salvarLancamentoGerarValorRestante();
    },

    salvarLancamentoGerarValorRestante() {
        let valor = parseFloat(this.inputsLancamento.valor.replace('.', '').replace(',', '.'));
        let valor_pago = parseFloat(this.inputsLancamento.valor_pago.replace('.', '').replace(',', '.'));

        this.loading = true;
        let data = Object.assign({}, this.inputsLancamento);
        data.valor = valor;
        data.valor_pago = valor_pago;

        data.parcelas.forEach((parcela) => {
            parcela.valor = parcela.valor.replace('.', '').replace(',', '.');
            parcela.valor_pago = parcela.valor_pago?.replace('.', '')?.replace(',', '.');
        });

        data.valor_restante = Object.assign({}, this.inputsLancamentoValorRestante);

        axios.post('/rpclinica/json/financeiro-store-lancamento', data)
            .then((res) => {
                toastr["success"](res.data.message);
                this.clear();
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loading = false);
    },

    modal(tipo) {

        this.campoCadastro.tp_cadastro = tipo;
        if(tipo=='FORN'){
            this.campoCadastro.titulo = 'Cadastro de Fornecedor e Cliente';
            this.campoCadastro.nm_campo = 'Nome Fornecedor/Cliente'; 
        }
        if(tipo=='FORMA'){
            this.campoCadastro.titulo = 'Cadastro de Forma de Pagamento';
            this.campoCadastro.nm_campo = 'Descrição'; 
        }
        if(tipo=='SETOR'){
            this.campoCadastro.titulo = 'Cadastro de Setor';
            this.campoCadastro.nm_campo = 'Nome do Setor'; 
        }
        if(tipo=='MARCA'){
            this.campoCadastro.titulo = 'Cadastro de Marca';
            this.campoCadastro.nm_campo = 'Nome da Marca'; 
        } 
        $('#modalCadastro').modal('show');

    },

    salvarCadastro(){
 
        axios.post('/rpclinica/json/financeiro-cadastro', this.campoCadastro)
        .then((res) => {
            toastr["success"](res.data.message); 
            $("#"+res.data.id_campo).append('<option value="'+res.data.codigo+'">'+res.data.nome+'</option>');
            //$("#"+res.data.id_campo).val(res.data.codigo);
            //$("#"+res.data.id_campo).trigger('change');
            $('#modalCadastro').modal('hide'); 

        })
        .catch((err) => parseErrorsAPI(err))
        .finally(() =>{
            this.loadingModal = false;
        } );
    } 

}));


Alpine.data('appTransferencias', () => ({
    contasBancaria : contasBancariaTransf,
    contasBancariaTransf,
    empresaUsuario,
    ds_origem : '',
    ds_destino : '',
    inputsTransferencia: {
        cd_empresa_origem: null,
        cd_conta_origem: null,
        cd_empresa_destino: null,
        cd_conta_destino: null,
        descricao: null,
        data: null,
        valor: null
    },

    loading: false,

    init() {

        $('#empresasOrigemTransferencia').val(this.empresaUsuario).trigger('change');
        this.inputsTransferencia.cd_empresa_origem = this.empresaUsuario;
        $('#contasOrigemTransferencia').empty();
        $('#contasOrigemTransferencia').select2({
            data: [
                { id: '', text: 'Selecione' },
                ...this.contasBancaria.map((cont) => ({
                    id: cont.cd_conta,
                    text: cont.nm_conta
                }))
            ]
        }); 
        
        $('#empresasDestinoTransferencia').val(this.empresaUsuario).trigger('change'); 
        this.inputsTransferencia.cd_empresa_destino = this.empresaUsuario;
        $('#contasDestinoTransferencia').empty();
        $('#contasDestinoTransferencia').select2({ 
            data: [
                { id: '', text: 'Selecione' },
                ...this.contasBancaria.map((cont) => ({
                    id: cont.cd_conta,
                    text: cont.nm_conta
                }))
            ]
        });


        $('#empresasOrigemTransferencia').on('select2:select', (evt) => {
            let empresaId = evt.params.data.id;
            this.inputsTransferencia.cd_empresa_origem = empresaId;

            $('#contasOrigemTransferencia').empty();
            $('#contasOrigemTransferencia').select2({
                // data: [
                //     { id: '', text: 'Selecione' },
                //     ...this.contasBancaria.filter((cont) => cont.cd_empresa == empresaId).map((cont) => ({
                //         id: cont.cd_conta,
                //         text: cont.nm_conta
                //     }))
                // ],
                data: [
                    { id: '', text: 'Selecione' },
                    ...this.contasBancaria.map((cont) => ({
                        id: cont.cd_conta,
                        text: cont.nm_conta
                    }))
                ]
            });
        });

        $('#contasOrigemTransferencia').on('select2:select', (evt) => {
            this.inputsTransferencia.cd_conta_origem = evt.params.data.id;
            this.ds_origem = evt.params.data.text;
            this.inputsTransferencia.descricao = 'Transf. da conta ' + this.ds_origem + ' para ' + this.ds_destino + '. ' ;
        });

        $('#empresasDestinoTransferencia').on('select2:select', (evt) => {
            let empresaId = evt.params.data.id;
            this.inputsTransferencia.cd_empresa_destino = empresaId;

            $('#contasDestinoTransferencia').empty();
            $('#contasDestinoTransferencia').select2({
                
                data: [
                    { id: '', text: 'Selecione' },
                    ...this.contasBancaria.map((cont) => ({
                        id: cont.cd_conta,
                        text: cont.nm_conta
                    }))
                ]
            });
        });

        $('#contasDestinoTransferencia').on('select2:select', (evt) => {
            this.inputsTransferencia.cd_conta_destino = evt.params.data.id
            this.ds_destino = evt.params.data.text; 
            this.inputsTransferencia.descricao = 'Transf. da conta ' + this.ds_origem + ' para ' + this.ds_destino + '. ' ;
        });
    },

    clear() {
        $('#empresasOrigemTransferencia').val(null).trigger('change');
        $('#contasOrigemTransferencia').val(null).trigger('change');
        $('#empresasDestinoTransferencia').val(null).trigger('change');
        $('#contasDestinoTransferencia').val(null).trigger('change');

        this.inputsTransferencia = {
            cd_empresa_origem: null,
            cd_conta_origem: null,
            cd_empresa_destino: null,
            cd_conta_destino: null,
            descricao: null,
            data: null,
            valor: null
        };
    },

    submit() {
        this.loading = true;

        let data = Object.assign({}, this.inputsTransferencia);
        data.valor = data.valor.replace('.', '').replace(',', '.');

        axios.post('/rpclinica/json/financeiro-store-lancamento-transferencia', data)
            .then((res) => {
                toastr["success"](res.data.message);
                this.clear();
                window.location.href = '/rpclinica/financeiro-listar?';
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loading = false);
    },

    xx(tipo){
        console.log(tipo);
        return false;
    }
 
}));

$('#modalOpcoesValorPago').modal({
    backdrop: 'static',
    show: false
});

 
 