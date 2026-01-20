/******/ (() => { // webpackBootstrap
    var __webpack_exports__ = {};
    /*!*****************************************************!*\
      !*** ./resources/js/rpclinica/financeiro-edicao.js ***!
      \*****************************************************/
    function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }
    
    function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
    
    function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
    
    function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }
    
    function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }
    
    function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }
    
    Alpine.data('appLancamentos', function () {
      return {
        boleto: boleto,
        parcelas: parcelas,
        categorias: categorias,
        setores: setores,
        eventos: eventos,
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
          tp_lancamento: null,
          cd_turma: null,
          cd_evento: null,
          parcelas: []
        },
        loading: false,
        classStatus: {
          
          'Recebido': 'label-recebido',
          'A receber': 'label-warning',
          'Pago': 'label-info',
          'Vencido': 'label-danger',
          'A vencer': 'label-aguardando'
        },

        init: function init() {
          var _this = this;
          /*
          $('#lancamentosEmpresas').on('select2:select', function (evt) {
            return _this.selectedEmpresa(evt.params.data.id);
          });
          $('#lancamentosCategorias').on('select2:select', function (evt) {
            return _this.selectedCategoria(evt.params.data.id);
          });
          $('#lancamentosConta').on('select2:select', function (evt) {
            var el = evt.params.data.element;
            var contaId = evt.params.data.id;
            _this.inputsLancamento.cd_conta = contaId;
            _this.disabledInputsPagamento = contaId && el.dataset.tp == 'CA';
          });
          $('#lancamentosFormaPagamento').on('select2:select', function (evt) {
            return _this.inputsLancamento.cd_forma = evt.params.data.id;
          });
          $('#lancamentosMarcas').on('select2:select', function (evt) {
            return _this.inputsLancamento.cd_marca = evt.params.data.id;
          });
          $('#lancamentosSetores').on('select2:select', function (evt) {
            return _this.inputsLancamento.cd_setor = evt.params.data.id;
          });
          $('#lancamentosFornecedor').on('select2:select', function (evt) {
            return _this.inputsLancamento.cd_fornecedor = evt.params.data.id;
          });

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

          this.selectedEmpresa(this.boleto.cd_empresa);
          //this.selectedCategoria(this.boleto.cd_categoria);
          */
          this.setValues(); 
           
        },

 
        selectedEmpresa: function selectedEmpresa(val) {
          var empresaId = val;
          this.inputsLancamento.cd_empresa = empresaId;
          var turmaId = this.boleto.cd_turma;
          console.log(this.eventos);
          console.log('Turma -> '+turmaId);

          $('#lancamentosCategorias').empty();
          $('#lancamentosCategorias').select2({
            data: [{
              id: '',
              text: 'Selecione'
            }].concat(_toConsumableArray(this.categorias.filter(function (cat) {
              return cat.cd_empresa == empresaId;
            }).map(function (cat) {
              return { 
                id: cat.cd_categoria,
                text: cat.cod_estrutural+' - '+cat.nm_categoria,
                disabled: cat.lanc,
                element: HTMLOptionElement
              };
            })))
          });

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

    


          $('#lancamentosSetores').empty();
          $('#lancamentosSetores').select2({
            data: [{
              id: '',
              text: 'Selecione'
            }].concat(_toConsumableArray(this.setores.filter(function (set) {
              return set.cd_empresa == empresaId;
            }).map(function (set) {
              return {
                id: set.cd_setor,
                text: set.nm_setor
              };
            })))
          });

          
        },

        selectedCategoria: function selectedCategoria(val) {
          this.inputsLancamento.cd_categoria = val;
          var categoria = this.categorias.find(function (cat) {
            return cat.cd_categoria == val;
          });
          if (categoria !== null && categoria !== void 0 && categoria.descricao) this.inputsLancamento.descricao = categoria.descricao;
    
          if (categoria !== null && categoria !== void 0 && categoria.cd_forma) {
            $('#lancamentosFormaPagamento').val(categoria.cd_forma).trigger('change');
            this.inputsLancamento.cd_forma = categoria.cd_forma;
          }
    
          if (categoria !== null && categoria !== void 0 && categoria.cd_marca) {
            $('#lancamentosMarcas').val(categoria.cd_marca).trigger('change');
            this.inputsLancamento.cd_marca = categoria.cd_marca;
          }
    
          if (categoria !== null && categoria !== void 0 && categoria.cd_setor) {
            $('#lancamentosSetores').val(categoria.cd_setor).trigger('change');
            this.inputsLancamento.cd_setor = categoria.cd_setor;
          }
    
          if (categoria !== null && categoria !== void 0 && categoria.cd_conta) {
            $('#lancamentosConta').val(categoria.cd_conta).trigger('change');
            this.inputsLancamento.cd_conta = categoria.cd_conta;
          }
    
          if (categoria !== null && categoria !== void 0 && categoria.cd_fornecedor) {
            $('#lancamentosFornecedor').val(categoria.cd_fornecedor).trigger('change');
            this.inputsLancamento.cd_fornecedor = categoria.cd_fornecedor;
          }
    
          if ((categoria === null || categoria === void 0 ? void 0 : categoria.tp_lancamento) == 'DESPESA') document.querySelector('#labelLancamentosDespesa').click();
          if ((categoria === null || categoria === void 0 ? void 0 : categoria.tp_lancamento) == 'RECEITA') document.querySelector('#labelLancamentosReceita').click();
        },

        setValues: function setValues() {

          var _this$boleto = this.boleto,
              cd_documento_boleto = _this$boleto.cd_documento_boleto,
              cd_empresa = _this$boleto.cd_empresa,
              cd_categoria = _this$boleto.cd_categoria,
              cd_conta = _this$boleto.cd_conta,
              cd_forma = _this$boleto.cd_forma,
              cd_fornecedor = _this$boleto.cd_fornecedor,
              cd_setor = _this$boleto.cd_setor,
              cd_marca = _this$boleto.cd_marca,
              ds_boleto = _this$boleto.ds_boleto,
              doc_boleto = _this$boleto.doc_boleto,
              dt_emissao = _this$boleto.dt_emissao, 
              tipo = _this$boleto.tipo;
          this.inputsLancamento = {
            cd_documento_boleto: cd_documento_boleto,
            cd_empresa: cd_empresa,
            cd_categoria: cd_categoria,
            cd_conta: cd_conta,
            cd_forma: cd_forma,
            cd_fornecedor: cd_fornecedor,
            cd_setor: cd_setor,
            cd_marca: cd_marca,
            descricao: ds_boleto,
            documento: doc_boleto,
            tp_lancamento: tipo,
            dt_emissao: dt_emissao,
            parcelas: this.parcelas.map(function (boleto) {
              return {
                situacao: boleto.situacao,
                statuss: boleto.statuss,
                cd_documento_boleto: boleto.cd_documento_boleto,
                ds_boleto: boleto.ds_boleto,
                doc_boleto: boleto.doc_boleto,
                dt_vencimento: boleto.dt_vencimento,
                vl_boleto: boleto.valor_boleto , 
                dt_pagrec: boleto.dt_pagrec,
                vl_pagrec:  boleto.valor_pagrec,
                data_compra:  boleto.data_compra,
                tipo:  boleto.tipo,
              };
            })
          };
          
          //$('#lancamentosCategorias').val(this.boleto.cd_categoria).trigger('change');
         // $('#lancamentosEventos').val(this.boleto.cd_evento).trigger('change');
          //$('#lancamentosSetores').val(this.boleto.cd_setor).trigger('change');

         
 
        },

        submitUpdateParcela(){
          this.loading = true;
          console.log(this.inputsLancamento.parcelas);
          console.log(this.inputsLancamento);
        
          axios.put('/rpclinica/json/financeiro-update-lancamento-parcelas', this.inputsLancamento)
          .then((res) => {
            console.log(res.data.request);
            toastr['success'](res.data.message);
          })
          .catch((err) => {
            parseErrorsAPI(err); 
          })
          .finally(() => this.loading = false);

      
        },

   
        getRandomIntInclusive(min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min + 1)) + min;
        },

        formatValor(valor) {
            return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valor);
        },

      };
    });
    $('#modalOpcoesValorPago').modal({
      backdrop: 'static',
      show: false
    });
    /******/ })()
    ;