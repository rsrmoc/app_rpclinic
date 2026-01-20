/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************************!*\
  !*** ./resources/js/rpclinica/financeiro-cartao.js ***!
  \*****************************************************/
Alpine.data('app', function () {
  return {
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
      VENCIDA: 'label-danger'
    },
    templateButtonSalvar: '<i class="fa fa-check"></i> Salvar',
    templateButtonSalvando: " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando ",
    buttonSalvar: '<i class="fa fa-check"></i> Salvar',
    saldoCartao: null,
    init: function init() {
      var _this = this;

      $('#filtersCartao').on('select2:select', function (evt) {
        _this.inputsFilter.cd_cartao = evt.params.data.id;
        _this.cartaoFiltroSelecionado = cartoesParaSelecao.find(function (cartao) {
          return cartao.cd_conta == evt.params.data.id;
        });

        if (evt.params.data.id) {
          _this.saldoCartao = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> ";
          axios.get('/rpclinica/json/financeiro-info-cartao/' + evt.params.data.id).then(function (res) {
            _this.saldoCartao = _this.formatValor(res.data.saldo);
          })["catch"](function (err) {
            toastr['error'](err.response.data.message, 'Erro');
            _this.saldoCartao = null;
          });
        }
      });
      $('#filtersForma').on('select2:select', function (evt) {
        return _this.inputsFilter.cd_forma = evt.params.data.id;
      });
      $('#empresaModalCartao').on('select2:select', function (evt) {
        return _this.inputsModalCartao.cd_empresa = evt.params.data.id;
      });
      $('#contaModalCartao').on('select2:select', function (evt) {
        return _this.inputsModalCartao.cd_conta_pag = evt.params.data.id;
      });
      $('#formaModalCartao').on('select2:select', function (evt) {
        return _this.inputsModalCartao.cd_forma = evt.params.data.id;
      });
      $('#modal-detalhes').on('hidden.bs.modal', function () {
        return _this.clearInputsModalCartao();
      });
      this.submitFilters();
    },
    prevYear: function prevYear() {
      this.inputsFilter.ano = this.inputsFilter.ano - 1;
      this.submitFilters();
    },
    nextYear: function nextYear() {
      this.inputsFilter.ano = this.inputsFilter.ano + 1;
      this.submitFilters();
    },
    selectMonth: function selectMonth(month) {
      this.inputsFilter.mes = month;
      this.submitFilters();
    },
    prevMonth: function prevMonth() {
      this.inputsFilter.mes = this.inputsFilter.mes > 1 ? this.inputsFilter.mes - 1 : this.inputsFilter.mes;
      this.submitFilters();
    },
    nextMonth: function nextMonth() {
      this.inputsFilter.mes = this.inputsFilter.mes < 12 ? this.inputsFilter.mes + 1 : this.inputsFilter.mes;
      this.submitFilters();
    },
    submitFilters: function submitFilters() {
      var _this2 = this;

      if ($('#checkboxAberto').is(":checked")) {
        this.inputsFilter.aberto = true;
      } else {
        this.inputsFilter.aberto = false;
      }

      if ($('#checkboxFechado').is(":checked")) {
        this.inputsFilter.fechado = true;
      } else {
        this.inputsFilter.fechado = false;
      }

      if ($('#checkboxQuitado').is(":checked")) {
        this.inputsFilter.quitado = true;
      } else {
        this.inputsFilter.quitado = false;
      }

      axios.get("/rpclinica/json/financeiro-relacao-cartao", {
        params: this.inputsFilter
      }).then(function (res) {
        _this2.cartoes = res.data.query;
        console.log(res.data);
      })["catch"](function (err) {
        return parseErrorsAPI(err);
      });
    },
    sumValueItemsCartoes: function sumValueItemsCartoes(items) {
      if (!items) return 0;
      var val = items.reduce(function (accumulator, item) {
        return item.vl_boleto + accumulator;
      }, 0);
      return val;
    },
    selectCartao: function selectCartao(cartao) {
      this.cartaoSelecionado = cartao;
      console.log(cartao);
      $('#empresaModalCartao').val(this.cartaoSelecionado.cd_empresa).trigger('change');
      $('#contaModalCartao').val(this.cartaoSelecionado.cd_conta_pag).trigger('change');
      $('#formaModalCartao').val(this.cartaoSelecionado.cd_forma).trigger('change');
      $('#modal-detalhes').modal('show');
    },
    formatValor: function formatValor(valor) {
      return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
      }).format(valor);
    },
    fecharFatura: function fecharFatura() {
      var _this3 = this;

      this.loadingModalCartao = true;
      axios.post('/rpclinica/json/financeiro-fechar-cartao', this.cartaoSelecionado).then(function (res) {
        _this3.submitFilters();

        toastr['success']('Fatura fechada!');
        $('#modal-detalhes').modal('toggle');
      })["catch"](function (err) {
        return parseErrorsAPI(err);
      })["finally"](function () {
        return _this3.loadingModalCartao = false;
      });
    },
    atualizarFatura: function atualizarFatura() {
      var _this4 = this;

      this.buttonSalvar = this.templateButtonSalvando;
      var form = new FormData(document.querySelector('#atualizarFatura'));
      form.set('cd_fatura', this.cartaoSelecionado.cd_fatura);
      axios.post('/rpclinica/json/financeiro-atualizar-cartao', form).then(function (res) {
        _this4.submitFilters();

        toastr['success']('Fatura atualizada!');
        $('#modal-detalhes').modal('toggle');
      })["catch"](function (err) {
        toastr['error'](err.response.data.message, 'Erro');
      })["finally"](function () {
        _this4.buttonSalvar = _this4.templateButtonSalvar;
        _this4.loadingModalCartao = false;
      });
    },
    abrirFatura: function abrirFatura(cdFatura) {
      var _this5 = this;

      this.loadingModalCartao = true;
      axios.post('/rpclinica/json/financeiro-abrir-cartao', {
        cd_fatura: cdFatura
      }).then(function (res) {
        _this5.cartaoSelecionado.fatura = res.data;

        _this5.submitFilters();

        toastr['success']('Fatura aberta!');
        $('#modal-detalhes').modal('toggle');
      })["catch"](function (err) {
        return parseErrorsAPI(err);
      })["finally"](function () {
        return _this5.loadingModalCartao = false;
      });
    },
    estornarFatura: function estornarFatura(cdFatura) {
      var _this6 = this;

      this.loadingModalCartao = true;
      axios.post('/rpclinica/json/financeiro-estornar-cartao', {
        cd_fatura: cdFatura
      }).then(function (res) {
        _this6.submitFilters();

        toastr['success']('Fatura Estornada!');
        $('#modal-detalhes').modal('toggle');
      })["catch"](function (err) {
        toastr['error'](err.response.data.message, 'Erro');
      })["finally"](function () {
        return _this6.loadingModalCartao = false;
      });
    },
    clearInputsModalCartao: function clearInputsModalCartao() {
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
  };
});
$(document).ready(function () {
  $('#modal-detalhes').modal({
    backdrop: 'static',
    show: false
  });
});
/******/ })()
;