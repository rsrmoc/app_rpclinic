
Alpine.data('app', () => ({
    loading:true,
    inputsFilter: {
        fluxo_caixa: 'M',
        ano: new Date().getFullYear(),
        ano_final: new Date().getFullYear() + 1,
        mes_inicial:  null,
        mes_final:  null,
        visao: 'REAL',
        detalhe: 'cd_categoria',
        categoria: null,
        conta: null,
        fornecedor: null, 
        setor: null, 
        fornecedor: null, 
        turma: null,   
        tpPesquisa: null,
        dt: null,
        filtro: null,
        nm_filtro: null
    },
   
    buttonPesquisar: '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Pesquisar',
    labelSaldo:'R$ 0,00',

    templateButtonPesquisar: '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Pesquisar',
    templateButtonPesquisando: " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Pesquisando ",
    buttonPesquisar: '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Pesquisar',
  
    tituloFluxo:'Fluxo de Caixa - MENSAL',
    saldoInicial: null,
    Receitas: null,
    Despesas: null,
    saldoOpe: null,
    Transferencia: null,
    saldoFinal: null,
    labelTable: null,
    detalhestable: {
       iconDetalhes:"<i class='fa fa-hand-o-right'></i>", 
       iconSaldos:"<i class='fa fa-share'></i>", 
       saldo_inicial: null,
       loadingSaldoInicial: false,
       SNsaldoInicial: false,
       iconSaldoInicial: '<i class="fa fa-square" style="font-size: 16px;"></i>',

       receita: null,
       SNreceita: false,
       loadingReceita: false, 
       iconReceita: '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>',

       despesa: null,
       SNdespesa: false,
       loadingDespesa: false,
       iconDespesa: '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>',

       saldo_ope: null, 
       SNsaldoOpe: false,
       loadingSaldoOpe: false,
       iconSaldoOpe: '<i class="fa fa-square" style="font-size: 16px;"></i>',

       transf: null, 
       SNtransf: false, 
       loadingTrans: false,
       iconTrans: '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>',
       
       saldo_final: null, 
       SNsaldoFinal: false, 
       loadingsaldoFinal: false,
       iconSaldoFinal: '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>',
    },
    loading: true,
    loadingModal: false,
    relacaoModal: null,
    tituloModal: null,

    init() {
        $('#IdFluxoCaixa').on('select2:select', (evt) => this.inputsFilter.fluxo_caixa = evt.params.data.id);
        $('#mesInicial').on('select2:select', (evt) => this.inputsFilter.mes_inicial = evt.params.data.id);
        $('#mesFinal').on('select2:select', (evt) => this.inputsFilter.mes_final = evt.params.data.id); 
        $('#idDetalhe').on('select2:select', (evt) => this.inputsFilter.detalhe = evt.params.data.id); 
        $('#cdCatgoria').on('select2:select', (evt) => this.inputsFilter.categoria = evt.params.data.id); 
        $('#cdConta').on('select2:select', (evt) => this.inputsFilter.conta = evt.params.data.id);
        $('#cdSetor').on('select2:select', (evt) => this.inputsFilter.setor = evt.params.data.id);
        $('#cdFornecedor').on('select2:select', (evt) => this.inputsFilter.fornecedor = evt.params.data.id);
        $('#cdTurma').on('select2:select', (evt) => this.inputsFilter.turma = evt.params.data.id);
        $('#IdVisao').on('select2:select', (evt) => this.inputsFilter.visao = evt.params.data.id);
        
        this.submitFilters();
    },
  
    submitFilters() { 
        this.buttonPesquisar = this.templateButtonPesquisando
        this.clear();
        console.log(this.inputsFilter);
        this.loading=true;
        this.inputsFilter.tpPesquisa='principal';
        if(this.inputsFilter.fluxo_caixa=='M'){ this.tituloFluxo='Fluxo de Caixa - MENSAL' }
        if(this.inputsFilter.fluxo_caixa=='D'){ this.tituloFluxo='Fluxo de Caixa - DIÁRIO' }
        if(this.inputsFilter.fluxo_caixa=='A'){ this.tituloFluxo='Fluxo de Caixa - ANUAL' }
        axios.get('/rpclinica/json/relacao-fluxo-caixa', {
            params: this.inputsFilter
        })
            .then((res) => {   
                
                $('select#mesInicial').val((res.data.request.mesInicial*1)).trigger('change');
                this.inputsFilter.mes_inicial=(res.data.request.mesInicial*1);
                $('select#mesFinal').val((res.data.request.mesFinal*1)).trigger('change');
                this.inputsFilter.mes_final=(res.data.request.mesFinal*1);

                this.saldoInicial=res.data.retorno.saldo_inicial;
                this.Receitas=res.data.retorno.receita
                this.Despesas=res.data.retorno.despesa
                this.saldoOpe=res.data.retorno.saldo_ope
                this.Transferencia=res.data.retorno.transferencia
                this.saldoFinal=res.data.retorno.saldo_final
                this.labelTable =res.data.retorno.label
                console.log(res.data);
                console.log(res.data.retorno.saldo_inicial);
               
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro');
                this.labelSaldo = 'R$ --';
            })
            .finally(() => { 
                this.loading=false;
                this.buttonPesquisar = this.templateButtonPesquisar;
            });    
 
    },

    detalhesFilters(tipo) {
        if(tipo == 'saldo_inicial'){
            if(this.detalhestable.SNsaldoInicial==true){
                this.detalhestable.SNsaldoInicial=false;
                this.detalhestable.iconSaldoInicial = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
                return false;
            }
            this.detalhestable.loadingSaldoInicial=true;
            this.detalhestable.iconSaldoInicial ='<i class="fa fa-refresh fa-spin"></i>';
        }

        if(tipo == 'receita'){
            if(this.detalhestable.SNreceita==true){
                this.detalhestable.SNreceita=false;
                this.detalhestable.iconReceita = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
                return false;
            }
            this.detalhestable.loadingReceita=true;
            this.detalhestable.iconReceita ='<i class="fa fa-refresh fa-spin"></i>';
        }

        if(tipo == 'despesa'){
            if(this.detalhestable.SNdespesa==true){
                this.detalhestable.SNdespesa=false;
                this.detalhestable.iconDespesa = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
                return false;
            }
            this.detalhestable.loadingDespesa=true;
            this.detalhestable.iconDespesa ='<i class="fa fa-refresh fa-spin"></i>';
        }

        if(tipo == 'saldo_ope'){
            if(this.detalhestable.SNsaldoOpe==true){
                this.detalhestable.SNsaldoOpe=false;
                this.detalhestable.iconSaldoOpe = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
                return false;
            }
            this.detalhestable.loadingSaldoOpe=true;
            this.detalhestable.iconSaldoOpe ='<i class="fa fa-refresh fa-spin"></i>';
        }
        
        if(tipo == 'transferencia'){
                if(this.detalhestable.SNtransf==true){
                    this.detalhestable.SNtransf=false;
                    this.detalhestable.iconTrans = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
                    return false;
                }
                this.detalhestable.loadingTrans=true;
                this.detalhestable.iconTrans ='<i class="fa fa-refresh fa-spin"></i>';
        } 

        if(tipo == 'saldo_final'){
            if(this.detalhestable.SNsaldoFinal==true){
                this.detalhestable.SNsaldoFinal=false;
                this.detalhestable.iconSaldoFinal = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
                return false;
            }
            this.detalhestable.loadingsaldoFinal=true;
            this.detalhestable.iconSaldoFinal ='<i class="fa fa-refresh fa-spin"></i>';
        } 

        this.inputsFilter.tpPesquisa=tipo;
         
        axios.get('/rpclinica/json/relacao-fluxo-caixa', {
            params: this.inputsFilter
        })
        .then((res) => {  
            console.log(res.data); 
            if(tipo == 'saldo_inicial'){
                this.detalhestable.saldo_inicial= res.data.retorno;
                this.detalhestable.SNsaldoInicial= true;
                this.detalhestable.iconSaldoInicial = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
            }
            if(tipo == 'receita'){
                this.detalhestable.receita= res.data.retorno;
                this.detalhestable.SNreceita= true;
                this.detalhestable.iconReceita = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
            }
            if(tipo == 'despesa'){
                this.detalhestable.despesa= res.data.retorno;
                this.detalhestable.SNdespesa= true;
                this.detalhestable.iconDespesa = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
            }
            if(tipo == 'saldo_ope'){
                this.detalhestable.saldo_ope= res.data.retorno;
                this.detalhestable.SNsaldoOpe= true;
                this.detalhestable.iconSaldoOpe = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
            }
            if(tipo == 'transferencia'){
                this.detalhestable.transf= res.data.retorno;
                this.detalhestable.SNtransf= true;
                this.detalhestable.iconTrans = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
            }
            if(tipo == 'saldo_final'){
                this.detalhestable.saldo_final= res.data.retorno;
                this.detalhestable.SNsaldoFinal= true;
                this.detalhestable.iconSaldoFinal = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
            }
        })
        .catch((err) => {
            toastr['error'](err.response.data.message, 'Erro');
        })
        .finally(() => { 
            if(tipo == 'saldo_inicial'){
                this.detalhestable.loadingSaldoInicial=false; 
            } 
            if(tipo == 'receita'){
                this.detalhestable.loadingReceita=false; 
            }
            if(tipo == 'despesa'){
                this.detalhestable.loadingDespesa=false; 
            }
            if(tipo == 'saldo_ope'){
                this.detalhestable.loadingSaldoOpe=false; 
            }
            if(tipo == 'transferencia'){
                this.detalhestable.loadingTrans=false; 
            }
            if(tipo == 'saldo_final'){
                this.detalhestable.loadingsaldoFinal=false; 
            }
        }); 
    },

    movimentosFilters(tipo,dt,filtro,nm_filtro) { 
        this.inputsFilter.tpPesquisa = tipo
        this.inputsFilter.dt = dt
        this.inputsFilter.filtro = filtro
        this.inputsFilter.nm_filtro = nm_filtro 
        $('.modalMovimento').modal('show');
        this.loadingModal =true;
        
        console.log(this.inputsFilter);
        axios.get('/rpclinica/json/relacao-fluxo-caixa-movimento', {
            params: this.inputsFilter
        })
            .then((res) => {  
          
                this.relacaoModal= res.data.retorno;
                this.tituloModal= res.data.titulo;
                console.log(this.relacaoModal);
               
            })
            .catch((err) => {
                toastr['error'](err.response.data.message, 'Erro'); 
            })
            .finally(() => { 
                this.loading=false;
                this.loadingModal =false;
            }); 

    },

    prevYear: function prevYear(tp) {
        if(tp=='I'){
            this.inputsFilter.ano = this.inputsFilter.ano - 1;
        }else{
            this.inputsFilter.ano_final = this.inputsFilter.ano_final - 1;
        }  
    },
    nextYear: function nextYear(tp) {
        if(tp=='I'){
            this.inputsFilter.ano = this.inputsFilter.ano + 1;
        }else{
            this.inputsFilter.ano_final = this.inputsFilter.ano_final + 1;
        }  
    },

    formatValor: function formatValor(valor) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor);
    },

    clear(){
        this.saldoInicial= null
        this.Receitas= null 
        this.Despesas= null
        this.saldoOpe= null
        this.Transferencia= null
        this.saldoFinal= null
        this.labelTable= null
        this.detalhestable.iconDetalhes="<i class='fa fa-hand-o-right'></i>"
        this.detalhestable.iconSaldos="<i class='fa fa-share'></i>" 
        this.detalhestable.saldo_inicial= null
        this.detalhestable.loadingSaldoInicial=false
        this.detalhestable.SNsaldoInicial= false,
        this.detalhestable.iconSaldoInicial= '<i class="fa fa-square" style="font-size: 16px;"></i>'
        this.detalhestable.receita= null
        this.detalhestable.SNreceita= false
        this.detalhestable.loadingReceita= false
        this.detalhestable.iconReceita= '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>'
        this.detalhestable.despesanull
        this.detalhestable.SNdespesa= false,
        this.detalhestable.loadingDespesa= false
        this.detalhestable.iconDespesa='<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>'
        this.detalhestable.saldo_ope=null
        this.detalhestable.SNsaldoOpe=false
        this.detalhestable.loadingSaldoOpe=false
        this.detalhestable.iconSaldoOpe='<i class="fa fa-square" style="font-size: 16px;"></i>'
        this.detalhestable.transf=null 
        this.detalhestable.SNtransf=false
        this.detalhestable.loadingTrans=false
        this.detalhestable.iconTrans='<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>'
        this.detalhestable.saldo_final=null 
        this.detalhestable.SNsaldoFinal= false 
        this.detalhestable.loadingsaldoFinal= false
        this.detalhestable.iconSaldoFinal='<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>'
 
    },

}));
 
