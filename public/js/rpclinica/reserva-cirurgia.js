/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************************!*\
  !*** ./resources/js/rpclinica/reserva-cirurgia.js ***!
  \****************************************************/
Alpine.data('app', function () {
  return {
    loading: false,
    retornoLista: null,
    paginatedData: [],
    currentPage: 1,
    itemsPerPage: 10,
    totalPages: 0,
    index: 0,
    loadingPesq: false,
    buttonSalvar: "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ",
    infoModal: {},
    modalTitulo: '',
    buttonSalvarHist: ' <i  class="fa fa-check"></i> Salvar ',
    buttonSalvarForm: ' <i  class="fa fa-check"></i> Salvar ',
    tempSalvarHist: ' <i  class="fa fa-check"></i> Salvar ',
    classSituacaoReserva: {
      A: 'btn-aberto',
      S: 'btn-rss',
      C: 'btn-danger',
      N: 'btn-danger',
      F: 'btn-success',
      U: 'btn-info'
    },
    situacaoReserva: {
      A: '<i class="fa fa-chain " style="padding-left:2px;"></i> Aberta',
      S: '<i class="fa fa-mail-forward" style="padding-left:2px;"></i> Solicitada',
      C: '<i class="fa fa-ban" style="padding-left:2px;"></i> Cancelada',
      N: '<i class="fa fa-close " style="padding-left:2px;"></i> Negada',
      U: '<i class="fa fa-check-circle-o" style="padding-left:2px;"></i> Autorizada',
      F: '<i class="fa fa-check" style="padding-left:2px;"></i> Finalizada'
    },
    formsData: {
      cd_reserva: null,
      cd_convenio: null,
      valor: null,
      sn_negociado: null,
      dt_autorizacao: null,
      dt_solicitacao: null,
      guia: null,
      situacao: null,
      opme: null,
      obs: null,
      agendamento: null
    },
    init: function init() {
      this.getPage();
    },
    paginateData: function paginateData() {
      var startIndex = (this.currentPage - 1) * this.itemsPerPage;
      var endIndex = startIndex + this.itemsPerPage;
      this.paginatedData = this.retornoLista.slice(startIndex, endIndex);
    },
    goToPage: function goToPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
        this.paginateData();
      }
    },
    nextPage: function nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.paginateData();
      }
    },
    previousPage: function previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.paginateData();
      }
    },
    getPage: function getPage() {
      var _this = this;

      this.loadingPesq = true;
      this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i>  ";
      var form = new FormData(document.querySelector('#form-parametros'));
      axios.post("/rpclinica/json/reserva-cirurgia", form).then(function (res) {
        _this.retornoLista = res.data.query;
        _this.totalPages = Math.ceil(_this.retornoLista.length / _this.itemsPerPage);

        _this.paginateData();
      })["catch"](function (err) {
        toastr['error'](err.response.data.message, 'Erro');
      })["finally"](function () {
        _this.buttonSalvar = "  <span class='glyphicon glyphicon-search' aria-hidden='true'></span> ";
        _this.loadingPesq = false;
      });
    },
    addHistory: function addHistory() {
      var _this2 = this;

      this.buttonSalvarHist = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando  ";
      var form = new FormData(document.querySelector('#form_RESERVA_CIRURGIA'));
      form.append('cd_reserva_cirurgia', this.infoModal.cd_reserva_cirurgia);
      axios.post("/rpclinica/json/reserva-cirurgia/addHist", form).then(function (res) {
        _this2.infoModal.notes = res.data.hist;
      })["catch"](function (err) {
        toastr['error'](err.response.data.message, 'Erro');
      })["finally"](function () {
        _this2.buttonSalvarHist = _this2.tempSalvarHist;
      });
    },
    clickModal: function clickModal(dados) {
      var _this3 = this;

      $('#form_FORMULARIO #idConvenio').on('select2:select', function () {
        _this3.formsData.cd_convenio = $('#form_FORMULARIO select#idConvenio').val();
      });
      $('#form_FORMULARIO #idOpme').on('select2:select', function () {
        _this3.formsData.opme = $('#form_FORMULARIO select#idOpme').val();
      });
      $('#form_FORMULARIO #idSituacao').on('select2:select', function () {
        _this3.formsData.situacao = $('#form_FORMULARIO select#idSituacao').val();
      });
      $('#form_FORMULARIO #idNegociado').on('select2:select', function () {
        _this3.formsData.sn_negociado = $('#form_FORMULARIO select#idNegociado').val();
      });
      this.infoModal = dados;
      this.modalTitulo = dados.agendamento.paciente.nm_paciente + ' { ' + dados.cd_agendamento + ' } ';
      axios.get("/rpclinica/json/reserva-cirurgia/getHist/".concat(dados.cd_reserva_cirurgia)).then(function (res) {
        _this3.infoModal.notes = res.data;
        console.log(_this3.infoModal);
        $('.modalDetalhes').modal('toggle');
        $('#form_FORMULARIO #idConvenio').val(_this3.infoModal.cd_convenio).trigger('change');
        $('#form_FORMULARIO #idOpme').val(_this3.infoModal.cd_opme).trigger('change');
        $('#form_FORMULARIO #idSituacao').val(_this3.infoModal.situacao).trigger('change');
        $('#form_FORMULARIO #idNegociado').val(_this3.infoModal.sn_negociado).trigger('change');
        _this3.formsData.dt_autorizacao = _this3.infoModal.dt_autorizacao;
        _this3.formsData.dt_solicitacao = _this3.infoModal.dt_solicitacao;
        _this3.formsData.guia = _this3.infoModal.guia;
        _this3.formsData.valor = _this3.infoModal.valor ? _this3.formatValor(_this3.infoModal.valor) : null;
        _this3.formsData.agendamento = _this3.infoModal.agendamento_reserva;
        _this3.formsData.obs = _this3.infoModal.comentarios_form;
        _this3.formsData.cd_convenio = _this3.infoModal.cd_convenio;
        _this3.formsData.opme = _this3.infoModal.cd_opme;
        _this3.formsData.situacao = _this3.infoModal.situacao;
        _this3.formsData.sn_negociado = _this3.infoModal.sn_negociado;
        console.log();
      })["catch"](function (err) {
        toastr['error'](err.response.data.message, 'Erro');
      });
    },
    saveFormulario: function saveFormulario() {
      var _this4 = this;

      this.formsData.cd_reserva = this.infoModal.cd_reserva_cirurgia;
      this.buttonSalvarForm = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando  ";
      axios.post("/rpclinica/json/reserva-cirurgia/addForm", this.formsData).then(function (res) {
        toastr['success']('Formulario atualizado com sucesso!');

        _this4.getPage();
      })["catch"](function (err) {
        toastr['error'](err.response.data.message, 'Erro');
      })["finally"](function () {
        _this4.buttonSalvarForm = _this4.tempSalvarHist;
      });
    },
    FormatData: function FormatData(data) {
      var dt = data.split(" ")[0];
      var dia = dt.split("-")[2];
      var mes = dt.split("-")[1];
      var ano = dt.split("-")[0];
      return ("0" + dia).slice(-2) + '/' + ("0" + mes).slice(-2) + '/' + ano;
    },
    formatValor: function formatValor(valor) {
      return Intl.NumberFormat('pt-br', {
        style: 'currency',
        currency: 'BRL'
      }).format(valor).replaceAll("R$ ", "");
    },
    nl2br: function nl2br(str, is_xhtml) {
      var breakTag = is_xhtml || typeof is_xhtml === 'undefined' ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display

      return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }
  };
});
/******/ })()
;