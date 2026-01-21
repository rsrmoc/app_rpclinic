/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************************************!*\
  !*** ./resources/js/rpclinica/agenda-listar.js ***!
  \*************************************************/
Alpine.data('app', function () {
  return {
    loadingGetHorarios: false,
    agendaHorarios: null,
    inputsGeracao: {
      cd_agenda: null,
      cd_escala: null,
      horarios_bloqueados: [],
      feriados: []
    },
    loadingGeracao: false,
    loadingExclusao: false,
    dadosExclusao: [],
    datasParaExluir: [],
    loadingExclusaoToggle: false,
    param: {
      agenda: null,
      escala: null
    },
    openModalGeracao: function openModalGeracao(cdAgenda, cdEscala) {
      this.agendaHorarios = null;
      this.loadingGetHorarios = true;
      this.inputsGeracao.cd_agenda = cdAgenda;
      this.inputsGeracao.cd_escala = cdEscala;
      this.inputsGeracao.horarios_bloqueados = [];
      this.inputsGeracao.feriados = [];
      this.dadosExclusao = [];
      this.datasParaExluir = [];
      this.getHorarios(cdAgenda, cdEscala);
      $('#cadastro-geracao').modal('toggle');
    },
    getHorarios: function getHorarios(cdAgenda, cdEscala) {
      var _this = this;

      this.param.agenda = cdAgenda;
      this.param.escala = cdEscala;
      axios.post('/rpclinica/json/agenda/horarios', this.param).then(function (res) {
        _this.agendaHorarios = res.data;
        console.log(res.data);
        _this.inputsGeracao.feriados = _this.agendaHorarios.feriados.map(function (feriado) {
          return feriado.dt_feriado;
        });

        if (_this.agendaHorarios.escalas.bloqueios_gerados) {
          _this.inputsGeracao.horarios_bloqueados = _this.agendaHorarios.escalas.bloqueios_gerados.lista_horarios.split(',');
        }

        if (_this.agendaHorarios.escalas.feriados_gerados) {
          _this.inputsGeracao.feriados = _this.agendaHorarios.escalas.feriados_gerados.lista_datas.split(',');
        }
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this.loadingGetHorarios = false;
      });
    },
    timeCut: function timeCut(time) {
      return time === null || time === void 0 ? void 0 : time.substr(0, 5);
    },
    calcIntervalo: function calcIntervalo(minutes) {
      return "".concat(parseInt(minutes / 60).toString().padStart(2, 0), ":").concat(parseInt(minutes % 60).toString().padStart(2, 0));
    },
    gerarAgendamentos: function gerarAgendamentos() {
      var _this2 = this;

      var escala = this.agendaHorarios.escalas;

      if (!escala.cd_dia) {
        toastr['error']('Não há dias da semana marcados na genda!');
        return;
      }

      console.log(this.inputsGeracao);
      this.loadingGeracao = true;
      axios.post('/rpclinica/json/agenda/horarios/gerar-agendamentos', this.inputsGeracao).then(function (res) {
        toastr['success'](res.data.message);

        _this2.getHorarios(_this2.inputsGeracao.cd_agenda, _this2.inputsGeracao.cd_escala);
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this2.loadingGeracao = false;
      });
    },
    pesquisaExclusao: function pesquisaExclusao() {
      var _this3 = this;

      this.loadingExclusao = true;
      console.log(this.agendaHorarios.agenda.cd_agenda);
      var form = new FormData(document.querySelector('#form-exclusao'));
      form.append('cd_agenda', this.agendaHorarios.agenda.cd_agenda);
      axios.post('/rpclinica/json/agenda/horarios/pesquisa-exclusao', form).then(function (res) {
        return _this3.dadosExclusao = res.data;
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this3.loadingExclusao = false;
      });
    },
    excluirDatas: function excluirDatas() {
      var _this4 = this;

      this.loadingExclusaoToggle = true;
      axios.post('/rpclinica/json/agenda/horarios/excluir-datas', {
        cd_agenda: this.inputsGeracao.cd_agenda,
        datas: this.datasParaExluir
      }).then(function (res) {
        toastr['success'](res.data.message);

        _this4.datasParaExluir.forEach(function (data) {
          delete _this4.dadosExclusao[data];
        });

        _this4.datasParaExluir = [];
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this4.loadingExclusaoToggle = false;
      });
    }
  };
});
$(document).ready(function () {
  $('#cadastro-geracao').modal({
    backdrop: 'static',
    show: false
  });
});
/******/ })()
;