/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************************!*\
  !*** ./resources/js/rpclinica/faturamento-listar.js ***!
  \******************************************************/
Alpine.data('app', function () {
  return {
    query: [],
    dataFaturamento: [],
    dataConta: [],
    idxConta: null,
    loadingModal: false,
    paginatedData: [],
    loadingPesq: false,
    buttonSalvar: " <i class='fa fa-check'></i>  Salvar ",
    buttonPesquisar: "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ",
    buttonTempleatePesquisar: "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ",
    currentPage: 1,
    totalPages: 0,
    totalLinhas: 0,
    itemsPerPage: 50,
    loadingAcao: null,
    init: function init() {
      this.getPage();
    },
    goToPage: function goToPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
        this.getPage();
      }
    },
    nextPage: function nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.getPage();
      }
    },
    previousPage: function previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.getPage();
      }
    },
    getPage: function getPage() {
      var _this = this;

      this.loadingPesq = true;
      this.buttonPesquisar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i>  ";
      var form = new FormData(document.querySelector('#form-parametros'));
      axios.post("/rpclinica/json/faturamento-conta-json?page=".concat(this.currentPage, "&itemsPerPage=").concat(this.itemsPerPage), form).then(function (res) {
        console.log(res.data);
        _this.retornoLista = res.data.query.data;
        _this.paginatedData = res.data.query.data;
        _this.totalPages = res.data.query.last_page;
        _this.totalLinhas = res.data.query.total;
        console.log(_this.paginatedData);
      })["catch"](function (err) {
        toastr['error'](err.response.data.message, 'Erro');
      })["finally"](function () {
        _this.buttonPesquisar = _this.buttonTempleatePesquisar;
        _this.loadingPesq = false;
      });
    },
    clickModal: function clickModal(Conta, index) {
      $('#situacaoFat').val(Conta.status_faturamento.cd_situacao_itens).trigger('change');
      this.loadingModal = true;
      this.dataConta = Conta;
      this.idxConta = index;
      console.log(Conta);
    },
    storefaturamento: function storefaturamento() {
      var _this2 = this;

      this.loadingAcao = "Salvando Informação...";
      $('.absolute-loading').show();
      var form = new FormData(document.querySelector('#form-faturamento'));
      axios.post("/rpclinica/json/faturamento-store/".concat(this.dataConta.cd_agendamento_item), form).then(function (res) {
        console.log(res.data);
        _this2.retornoLista[_this2.idxConta] = res.data.query;
        _this2.paginatedData[_this2.idxConta] = res.data.query;
        toastr['success'](res.data.message);
      })["catch"](function (err) {
        toastr['error'](err.response.data.message, 'Erro');
      })["finally"](function () {
        $('.absolute-loading').hide();
      });
    }
  };
});
$(document).ready(function () {
  $('#modal-resumo-paciente,#modal-historico-paciente').modal({
    backdrop: 'static',
    show: false
  });
});
/******/ })()
;