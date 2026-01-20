/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************************************!*\
  !*** ./resources/js/rpclinica/inicial-dashboard-exames.js ***!
  \************************************************************/
Alpine.data('app', function () {
  return {
    panel: null,
    panelComp: [],
    panelHist: [],
    loadingPanel: false,
    data: data,
    graficoAtendimento: '<br><br><br><i class="fa fa-spinner fa-spin" style="font-size:5em; "></i><br>Carregando...',
    graficoHeader: ' <i style="color: #B0B0B0;" class="fa fa-spinner fa-spin"></i> ',
    headerAtendimento: null,
    headerExame: null,
    headerPendente: null,
    headerLaudado: null,
    exameLaudado: [],
    examePendente: [],
    loadListaExame: false,
    dt: '',
    dt_extenso: '',
    classLabelSituacao: {
      livre: 'label-success',
      agendado: 'label-primary',
      confirmado: 'label-info',
      atendido: 'label-warning',
      bloqueado: 'label-danger',
      cancelado: 'label-danger',
      aguardando: 'label-aguardando',
      atendimento: 'label-aguardando'
    },
    chart1: null,
    chart2: null,
    chart3: null,
    cardUm: 'Atendimentos',
    cardDois: 'Total de Exames',
    cardTres: 'Exames Pendentes',
    cardQuatro: 'Exames Laudados',
    loadingChart1: false,
    nmProfissional: '',
    optionsChart: {
      legend: {
        display: false
      },
      title: {
        display: true,
        text: "World Wine Production 2018"
      }
    },
    init: function init() {
      if (msgWhast) {
        Swal.fire({
          title: "Atenção!",
          html: msgWhast,
          icon: "error",
          confirmButtonColor: '#22BAA0'
        });
      }

      console.log(msgWhast);
      this.getCompromisso();
      this.getDataPanel();
    },
    getCompromisso: function getCompromisso() {
      var _this = this;

      this.loadingPanel = true;
      axios.get('/rpclinica/json/panel-dashboard-compromisso').then(function (res) {
        console.log(res.data);
        _this.panelComp = res.data.retorno;
        _this.panelHist = res.data.historico;
      })["catch"](function (err) {
        return toastr['error']('Houve um erro ao obter os dados do painel');
      })["finally"](function () {
        return _this.loadingPanel = false;
      });
    },
    getDataPanel: function getDataPanel() {
      var _this2 = this;

      document.getElementById('flot1').innerHTML = this.graficoAtendimento;
      document.getElementById('flot3').innerHTML = this.graficoAtendimento;
      this.headerAtendimento = this.graficoHeader;
      this.headerExame = this.graficoHeader;
      this.headerPendente = this.graficoHeader;
      this.headerLaudado = this.graficoHeader;
      this.loadingPanel = true;
      this.loadListaExame = true;
      axios.get("/rpclinica/json/panel-dashboard/".concat(this.data)).then(function (res) {
        _this2.panel = res.data;
        _this2.dt = res.data.request.dt;
        _this2.dt_extenso = res.data.request.dt_extenso;
        console.log(res.data);
        _this2.headerAtendimento = res.data.header.atendimento;
        _this2.nmProfissional = res.data.request.profissional;
        _this2.headerExame = res.data.header.exame;
        _this2.headerPendente = res.data.header.pendente;
        _this2.headerLaudado = res.data.header.laudado;
        _this2.exameLaudado = res.data.exameLaudado;
        _this2.examePendente = res.data.examePendente;

        _this2.getChartAtendimentos(res.data.grafico);

        _this2.getChartExames(res.data.pizza);
      })["catch"](function (err) {
        return toastr['error']('Houve um erro ao obter os dados do painel');
      })["finally"](function () {
        _this2.loadingPanel = false;
        _this2.loadListaExame = false;
      });
    },
    getDataChart1: function getDataChart1() {
      var _this3 = this;

      this.loadingChart1 = true;
      setTimeout(function () {
        _this3.chart1.data.labels = [55, 49, 44, 24, 15];
        _this3.chart1.data.datasets = [{
          backgroundColor: '#22BAA0',
          borderWidth: 1,
          data: [55, 49, 44, 24, 15]
        }];

        _this3.chart1.update();

        _this3.loadingChart1 = false;
      }, 5000);
    },
    getChartAtendimentos: function getChartAtendimentos(dados) {
      document.getElementById('flot1').innerHTML = null;
      var data = dados.atend;
      var dataset = [{
        data: data,
        color: "#22BAA0"
      }];
      var ticks = dados.dt_atend;
      var options = {
        series: {
          bars: {
            show: true
          }
        },
        bars: {
          align: "center",
          barWidth: 0.5
        },
        xaxis: {
          ticks: ticks
        },
        legend: {
          show: false
        },
        grid: {
          color: "#AFAFAF",
          hoverable: true,
          borderWidth: 0,
          backgroundColor: '#FFF'
        },
        tooltip: true,
        tooltipOpts: {
          content: "Dia: <b> %x</b>, <br> Atendimento(s): <b> %y</b>",
          defaultTheme: false
        }
      };
      $.plot($("#flot1"), dataset, options);
    },
    getChartExames: function getChartExames(dados) {
      document.getElementById('flot3').innerHTML = null;
      /*
      var data = [{
          label: "Pendentes",
          data: dados.pendente,
          color: "#f25656",
      }, {
          label: "Realizados",
          data: dados.laudado,
          color: "#22baa0",
      }];
      */

      var data = dados;
      var options = {
        series: {
          pie: {
            show: true
          }
        },
        legend: {
          labelFormatter: function labelFormatter(label, series) {
            return '<span class="pie-chart-legend">' + label + '</span>';
          }
        },
        grid: {
          hoverable: true
        },
        tooltip: true,
        tooltipOpts: {
          content: "%p.0%, %s",
          shifts: {
            x: 20,
            y: 0
          },
          defaultTheme: false
        }
      };
      $.plot($("#flot3"), data, options);
    }
  };
});
/******/ })()
;