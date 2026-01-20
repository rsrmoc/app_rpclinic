
Alpine.data('app', () => ({ 
    receitasArray:null,
    

    inputsFilter: {
        ano: new Date().getFullYear(),
        mesInicial: null,
        mesFinal: null,
        tipoAgrupamento: 'CAT',
        tipoPesquisa: 'REAL',
        conta: null
    },

    buttonPesquisar: '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Pesquisar',
    labelSaldo:'R$ 0,00',

    templateButtonSalvar: '<i class="fa fa-check"></i> Salvar',
    templateButtonSalvando: " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando ",
    buttonSalvar: '<i class="fa fa-check"></i> Salvar',
    objArr: null,

    init() {
        
        $('#mesInicial').on('select2:select', () => { 
            this.inputsFilter.mesInicial =  evt.params.data.id;
        });
        $('#mesFinal').on('select2:select', () => { 
            this.inputsFilter.mesFinal =  evt.params.data.id;
        });
        $('#tipoPesquisa').on('select2:select', () => { 
            this.inputsFilter.tipoPesquisa =  evt.params.data.id;
        });
        $('#tipoAgrupamento').on('select2:select', () => { 
            this.inputsFilter.tipoAgrupamento =  evt.params.data.id;
        });
        $('#tipoConta').on('select2:select', () => { 
            this.inputsFilter.conta =  evt.params.data.id;
        });

        this.submitFilters();
    },

    submitFilters() {
   
        
        axios.get('/rpclinica/json/relacao-fluxo-caixa', {
            params: this.inputsFilter
        }).then(function (res) {
            this.receitasArray = res.data.teste;   
            console.log(res.data); 

        })
        .catch((err) => {
            console.log('Erro');
            console.log(err.response.data.messag);
        })
       
        
          
    },

    prevYear: function prevYear() {
        this.inputsFilter.ano = this.inputsFilter.ano - 1;
        this.submitFilters();
    },
    nextYear: function nextYear() {
        this.inputsFilter.ano = this.inputsFilter.ano + 1;
        this.submitFilters();
    }, 
    selectMonth: function selectMonth(month, type) {
        if(type == 'inicial'){
            this.inputsFilter.mesInicial = month.target.value;
        } else {
            this.inputsFilter.mesFinal = month.target.value;
        }

        this.submitFilters();
    },
    formatValor: function formatValor(valor) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor);
    },

}));

 
