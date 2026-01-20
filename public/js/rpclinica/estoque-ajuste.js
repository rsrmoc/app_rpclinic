/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************************************!*\
  !*** ./resources/js/rpclinica/estoque-ajuste.js ***!
  \**************************************************/
Alpine.data('app', function () {
  return {
    lotes: [],
    inputsEntrada: {
      cd_produto: null,
      cd_lote_produto: null,
      qtde: null
    },
    produtosEntrada: [],
    inputsLoteRequired: false,
    inputsLote: {
      nm_lote: null,
      cd_produto: null,
      validade: null
    },
    loadingModalLote: false,
    init: function init() {
      var _this = this;

      this.lotes = lotes;
      $('#select-formulario-produto').on('select2:select', function (evt) {
        _this.inputsEntrada.cd_produto = evt.params.data.id;
        _this.inputsLoteRequired = evt.params.data.element.dataset.lote == 'S';

        _this.changeOptionsInputLotes();
      });
      $('#entradaLotes').on('select2:select', function (evt) {
        _this.inputsEntrada.cd_lote_produto = evt.params.data.id;
      }); // modal cadastro de lotes

      $(this.$refs.modalLote).on('hidden.bs.modal', function () {
        _this.inputsLote = {
          nm_lote: null,
          cd_produto: null,
          validade: null
        };
        $(_this.$refs.inputProdutoModalLotes).val(null).trigger('change');
      });
      $(this.$refs.inputProdutoModalLotes).on('select2:select', function (evt) {
        return _this.inputsLote.cd_produto = evt.params.data.id;
      });

      if (typeof ajusteProdutos !== 'undefined') {
        this.produtosEntrada = ajusteProdutos;
      }
    },
    submit: function submit() {
      if (this.produtosEntrada.length == 0) {
        toastr['error']('Adicione no mínimo 1 produto!');
        return;
      }

      this.$refs.formAjuste.submit();
    },
    changeOptionsInputLotes: function changeOptionsInputLotes() {
      var _this2 = this;

      var lotesOptions = this.lotes.filter(function (lote) {
        return lote.cd_produto == _this2.inputsEntrada.cd_produto;
      });
      lotesOptions = lotesOptions.map(function (lote) {
        return {
          id: lote.cd_lote,
          text: lote.nm_lote
        };
      });
      lotesOptions.unshift({
        id: '',
        text: 'SELECIONE'
      });
      $('#entradaLotes').empty().trigger('change');
      $('#entradaLotes').select2({
        data: lotesOptions
      });
    },
    openModalLote: function openModalLote() {
      return $('#modal-lote').modal('show');
    },
    addLoteProduto: function addLoteProduto() {
      var _this3 = this;

      this.loadingModalLote = true;
      axios.post('/rpclinica/json/produto-lote', this.inputsLote).then(function (res) {
        _this3.lotes.push(res.data.lote);

        _this3.changeOptionsInputLotes();

        toastr['success'](res.data.message);
        $(_this3.$refs.modalLote).modal('hide');
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this3.loadingModalLote = false;
      });
    },
    addEntradaProduto: function addEntradaProduto() {
      if (this.inputsLoteRequired && !this.inputsEntrada.cd_lote_produto) {
        toastr['error']('Escolha um lote!');
        return;
      }

      this.produtosEntrada.push(Object.assign({}, this.inputsEntrada));
      this.inputsEntrada = {
        cd_produto: null,
        cd_lote_produto: null,
        qtde: null,
        valor: null
      };
      $('#select-formulario-produto').val(null).trigger('change');
      $('#entradaLotes').empty().trigger('change');
      $('#entradaLotes').select2({
        data: [{
          id: '',
          text: 'SELECIONE'
        }]
      });
    },
    nomeDoProduto: function nomeDoProduto(cdProduto) {
      var _produtos$find;

      return (_produtos$find = produtos.find(function (produto) {
        return produto.cd_produto == cdProduto;
      })) === null || _produtos$find === void 0 ? void 0 : _produtos$find.nm_produto;
    },
    nomeDoLote: function nomeDoLote(cdLote) {
      var _lotes$find;

      if (!cdLote) return '<span class="text-warning">Nenhum</span>';
      return (_lotes$find = lotes.find(function (lote) {
        return lote.cd_lote == cdLote;
      })) === null || _lotes$find === void 0 ? void 0 : _lotes$find.nm_lote;
    },
    deleteEntradaProduto: function deleteEntradaProduto(indice) {
      var _this4 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir esse agendamento?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          if (_this4.produtosEntrada[indice].cd_ajuste_prod) {
            axios.post("/rpclinica/estoque-ajuste-prod-delete/".concat(_this4.produtosEntrada[indice].cd_ajuste_prod)).then(function (res) {
              toastr['success']('Ajuste do produto excluída com sucesso!');

              _this4.produtosEntrada.splice(indice, 1);
            })["catch"](function (err) {
              return toastr['error']('Houve um erro ao excluir a ajuste do produto.');
            });
            return;
          }

          _this4.produtosEntrada.splice(indice, 1);
        }
      });
    }
  };
});
$(document).ready(function () {
  $('#modal-lote').modal({
    backdrop: 'static',
    show: false
  });
});
/******/ })()
;