/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************************!*\
  !*** ./resources/js/rpclinica/fluxo-caixa.js ***!
  \***********************************************/
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

Alpine.data('app', function () {
  var _inputsFilter, _ref;

  return _ref = {
    loading: true,
    inputsFilter: (_inputsFilter = {
      fluxo_caixa: 'M',
      ano: new Date().getFullYear(),
      ano_final: new Date().getFullYear() + 1,
      mes_inicial: null,
      mes_final: null,
      visao: 'REAL',
      detalhe: 'cd_categoria',
      categoria: null,
      conta: null,
      fornecedor: null,
      setor: null
    }, _defineProperty(_inputsFilter, "fornecedor", null), _defineProperty(_inputsFilter, "turma", null), _defineProperty(_inputsFilter, "tpPesquisa", null), _defineProperty(_inputsFilter, "dt", null), _defineProperty(_inputsFilter, "filtro", null), _defineProperty(_inputsFilter, "nm_filtro", null), _inputsFilter),
    buttonPesquisar: '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Pesquisar',
    labelSaldo: 'R$ 0,00',
    templateButtonPesquisar: '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Pesquisar',
    templateButtonPesquisando: " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Pesquisando "
  }, _defineProperty(_ref, "buttonPesquisar", '<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Pesquisar'), _defineProperty(_ref, "tituloFluxo", 'Fluxo de Caixa - MENSAL'), _defineProperty(_ref, "saldoInicial", null), _defineProperty(_ref, "Receitas", null), _defineProperty(_ref, "Despesas", null), _defineProperty(_ref, "saldoOpe", null), _defineProperty(_ref, "Transferencia", null), _defineProperty(_ref, "saldoFinal", null), _defineProperty(_ref, "labelTable", null), _defineProperty(_ref, "detalhestable", {
    iconDetalhes: "<i class='fa fa-hand-o-right'></i>",
    iconSaldos: "<i class='fa fa-share'></i>",
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
    iconSaldoFinal: '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>'
  }), _defineProperty(_ref, "loading", true), _defineProperty(_ref, "loadingModal", false), _defineProperty(_ref, "relacaoModal", null), _defineProperty(_ref, "tituloModal", null), _defineProperty(_ref, "init", function init() {
    var _this = this;

    $('#IdFluxoCaixa').on('select2:select', function (evt) {
      return _this.inputsFilter.fluxo_caixa = evt.params.data.id;
    });
    $('#mesInicial').on('select2:select', function (evt) {
      return _this.inputsFilter.mes_inicial = evt.params.data.id;
    });
    $('#mesFinal').on('select2:select', function (evt) {
      return _this.inputsFilter.mes_final = evt.params.data.id;
    });
    $('#idDetalhe').on('select2:select', function (evt) {
      return _this.inputsFilter.detalhe = evt.params.data.id;
    });
    $('#cdCatgoria').on('select2:select', function (evt) {
      return _this.inputsFilter.categoria = evt.params.data.id;
    });
    $('#cdConta').on('select2:select', function (evt) {
      return _this.inputsFilter.conta = evt.params.data.id;
    });
    $('#cdSetor').on('select2:select', function (evt) {
      return _this.inputsFilter.setor = evt.params.data.id;
    });
    $('#cdFornecedor').on('select2:select', function (evt) {
      return _this.inputsFilter.fornecedor = evt.params.data.id;
    });
    $('#cdTurma').on('select2:select', function (evt) {
      return _this.inputsFilter.turma = evt.params.data.id;
    });
    $('#IdVisao').on('select2:select', function (evt) {
      return _this.inputsFilter.visao = evt.params.data.id;
    });
    this.submitFilters();
  }), _defineProperty(_ref, "submitFilters", function submitFilters() {
    var _this2 = this;

    this.buttonPesquisar = this.templateButtonPesquisando;
    this.clear();
    console.log(this.inputsFilter);
    this.loading = true;
    this.inputsFilter.tpPesquisa = 'principal';

    if (this.inputsFilter.fluxo_caixa == 'M') {
      this.tituloFluxo = 'Fluxo de Caixa - MENSAL';
    }

    if (this.inputsFilter.fluxo_caixa == 'D') {
      this.tituloFluxo = 'Fluxo de Caixa - DIÁRIO';
    }

    if (this.inputsFilter.fluxo_caixa == 'A') {
      this.tituloFluxo = 'Fluxo de Caixa - ANUAL';
    }

    axios.get('/rpclinica/json/relacao-fluxo-caixa', {
      params: this.inputsFilter
    }).then(function (res) {
      $('select#mesInicial').val(res.data.request.mesInicial * 1).trigger('change');
      _this2.inputsFilter.mes_inicial = res.data.request.mesInicial * 1;
      $('select#mesFinal').val(res.data.request.mesFinal * 1).trigger('change');
      _this2.inputsFilter.mes_final = res.data.request.mesFinal * 1;
      _this2.saldoInicial = res.data.retorno.saldo_inicial;
      _this2.Receitas = res.data.retorno.receita;
      _this2.Despesas = res.data.retorno.despesa;
      _this2.saldoOpe = res.data.retorno.saldo_ope;
      _this2.Transferencia = res.data.retorno.transferencia;
      _this2.saldoFinal = res.data.retorno.saldo_final;
      _this2.labelTable = res.data.retorno.label;
      console.log(res.data);
      console.log(res.data.retorno.saldo_inicial);
    })["catch"](function (err) {
      toastr['error'](err.response.data.message, 'Erro');
      _this2.labelSaldo = 'R$ --';
    })["finally"](function () {
      _this2.loading = false;
      _this2.buttonPesquisar = _this2.templateButtonPesquisar;
    });
  }), _defineProperty(_ref, "detalhesFilters", function detalhesFilters(tipo) {
    var _this3 = this;

    if (tipo == 'saldo_inicial') {
      if (this.detalhestable.SNsaldoInicial == true) {
        this.detalhestable.SNsaldoInicial = false;
        this.detalhestable.iconSaldoInicial = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
        return false;
      }

      this.detalhestable.loadingSaldoInicial = true;
      this.detalhestable.iconSaldoInicial = '<i class="fa fa-refresh fa-spin"></i>';
    }

    if (tipo == 'receita') {
      if (this.detalhestable.SNreceita == true) {
        this.detalhestable.SNreceita = false;
        this.detalhestable.iconReceita = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
        return false;
      }

      this.detalhestable.loadingReceita = true;
      this.detalhestable.iconReceita = '<i class="fa fa-refresh fa-spin"></i>';
    }

    if (tipo == 'despesa') {
      if (this.detalhestable.SNdespesa == true) {
        this.detalhestable.SNdespesa = false;
        this.detalhestable.iconDespesa = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
        return false;
      }

      this.detalhestable.loadingDespesa = true;
      this.detalhestable.iconDespesa = '<i class="fa fa-refresh fa-spin"></i>';
    }

    if (tipo == 'saldo_ope') {
      if (this.detalhestable.SNsaldoOpe == true) {
        this.detalhestable.SNsaldoOpe = false;
        this.detalhestable.iconSaldoOpe = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
        return false;
      }

      this.detalhestable.loadingSaldoOpe = true;
      this.detalhestable.iconSaldoOpe = '<i class="fa fa-refresh fa-spin"></i>';
    }

    if (tipo == 'transferencia') {
      if (this.detalhestable.SNtransf == true) {
        this.detalhestable.SNtransf = false;
        this.detalhestable.iconTrans = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
        return false;
      }

      this.detalhestable.loadingTrans = true;
      this.detalhestable.iconTrans = '<i class="fa fa-refresh fa-spin"></i>';
    }

    if (tipo == 'saldo_final') {
      if (this.detalhestable.SNsaldoFinal == true) {
        this.detalhestable.SNsaldoFinal = false;
        this.detalhestable.iconSaldoFinal = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
        return false;
      }

      this.detalhestable.loadingsaldoFinal = true;
      this.detalhestable.iconSaldoFinal = '<i class="fa fa-refresh fa-spin"></i>';
    }

    this.inputsFilter.tpPesquisa = tipo;
    axios.get('/rpclinica/json/relacao-fluxo-caixa', {
      params: this.inputsFilter
    }).then(function (res) {
      console.log(res.data);

      if (tipo == 'saldo_inicial') {
        _this3.detalhestable.saldo_inicial = res.data.retorno;
        _this3.detalhestable.SNsaldoInicial = true;
        _this3.detalhestable.iconSaldoInicial = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
      }

      if (tipo == 'receita') {
        _this3.detalhestable.receita = res.data.retorno;
        _this3.detalhestable.SNreceita = true;
        _this3.detalhestable.iconReceita = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
      }

      if (tipo == 'despesa') {
        _this3.detalhestable.despesa = res.data.retorno;
        _this3.detalhestable.SNdespesa = true;
        _this3.detalhestable.iconDespesa = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
      }

      if (tipo == 'saldo_ope') {
        _this3.detalhestable.saldo_ope = res.data.retorno;
        _this3.detalhestable.SNsaldoOpe = true;
        _this3.detalhestable.iconSaldoOpe = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
      }

      if (tipo == 'transferencia') {
        _this3.detalhestable.transf = res.data.retorno;
        _this3.detalhestable.SNtransf = true;
        _this3.detalhestable.iconTrans = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
      }

      if (tipo == 'saldo_final') {
        _this3.detalhestable.saldo_final = res.data.retorno;
        _this3.detalhestable.SNsaldoFinal = true;
        _this3.detalhestable.iconSaldoFinal = '<i class="fa fa-minus-square-o" style="font-size: 16px;"></i>';
      }
    })["catch"](function (err) {
      toastr['error'](err.response.data.message, 'Erro');
    })["finally"](function () {
      if (tipo == 'saldo_inicial') {
        _this3.detalhestable.loadingSaldoInicial = false;
      }

      if (tipo == 'receita') {
        _this3.detalhestable.loadingReceita = false;
      }

      if (tipo == 'despesa') {
        _this3.detalhestable.loadingDespesa = false;
      }

      if (tipo == 'saldo_ope') {
        _this3.detalhestable.loadingSaldoOpe = false;
      }

      if (tipo == 'transferencia') {
        _this3.detalhestable.loadingTrans = false;
      }

      if (tipo == 'saldo_final') {
        _this3.detalhestable.loadingsaldoFinal = false;
      }
    });
  }), _defineProperty(_ref, "movimentosFilters", function movimentosFilters(tipo, dt, filtro, nm_filtro) {
    var _this4 = this;

    this.inputsFilter.tpPesquisa = tipo;
    this.inputsFilter.dt = dt;
    this.inputsFilter.filtro = filtro;
    this.inputsFilter.nm_filtro = nm_filtro;
    $('.modalMovimento').modal('show');
    this.loadingModal = true;
    console.log(this.inputsFilter);
    axios.get('/rpclinica/json/relacao-fluxo-caixa-movimento', {
      params: this.inputsFilter
    }).then(function (res) {
      _this4.relacaoModal = res.data.retorno;
      _this4.tituloModal = res.data.titulo;
      console.log(_this4.relacaoModal);
    })["catch"](function (err) {
      toastr['error'](err.response.data.message, 'Erro');
    })["finally"](function () {
      _this4.loading = false;
      _this4.loadingModal = false;
    });
  }), _defineProperty(_ref, "prevYear", function prevYear(tp) {
    if (tp == 'I') {
      this.inputsFilter.ano = this.inputsFilter.ano - 1;
    } else {
      this.inputsFilter.ano_final = this.inputsFilter.ano_final - 1;
    }
  }), _defineProperty(_ref, "nextYear", function nextYear(tp) {
    if (tp == 'I') {
      this.inputsFilter.ano = this.inputsFilter.ano + 1;
    } else {
      this.inputsFilter.ano_final = this.inputsFilter.ano_final + 1;
    }
  }), _defineProperty(_ref, "formatValor", function formatValor(valor) {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    }).format(valor);
  }), _defineProperty(_ref, "clear", function clear() {
    this.saldoInicial = null;
    this.Receitas = null;
    this.Despesas = null;
    this.saldoOpe = null;
    this.Transferencia = null;
    this.saldoFinal = null;
    this.labelTable = null;
    this.detalhestable.iconDetalhes = "<i class='fa fa-hand-o-right'></i>";
    this.detalhestable.iconSaldos = "<i class='fa fa-share'></i>";
    this.detalhestable.saldo_inicial = null;
    this.detalhestable.loadingSaldoInicial = false;
    this.detalhestable.SNsaldoInicial = false, this.detalhestable.iconSaldoInicial = '<i class="fa fa-square" style="font-size: 16px;"></i>';
    this.detalhestable.receita = null;
    this.detalhestable.SNreceita = false;
    this.detalhestable.loadingReceita = false;
    this.detalhestable.iconReceita = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
    this.detalhestable.despesanull;
    this.detalhestable.SNdespesa = false, this.detalhestable.loadingDespesa = false;
    this.detalhestable.iconDespesa = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
    this.detalhestable.saldo_ope = null;
    this.detalhestable.SNsaldoOpe = false;
    this.detalhestable.loadingSaldoOpe = false;
    this.detalhestable.iconSaldoOpe = '<i class="fa fa-square" style="font-size: 16px;"></i>';
    this.detalhestable.transf = null;
    this.detalhestable.SNtransf = false;
    this.detalhestable.loadingTrans = false;
    this.detalhestable.iconTrans = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
    this.detalhestable.saldo_final = null;
    this.detalhestable.SNsaldoFinal = false;
    this.detalhestable.loadingsaldoFinal = false;
    this.detalhestable.iconSaldoFinal = '<i class="fa fa-plus-square-o" style="font-size: 16px;"></i>';
  }), _ref;
});
/******/ })()
;