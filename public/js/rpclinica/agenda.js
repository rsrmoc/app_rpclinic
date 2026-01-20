/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************!*\
  !*** ./resources/js/rpclinica/agenda.js ***!
  \******************************************/
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

Alpine.data('app', function () {
  return {
    ooo: null,
    agendaEspecialidades: [],
    agendaProcedimentos: [],
    agendaProfissionais: [],
    agendaLocais: [],
    agendaConvenios: [],
    loadingEspecialidade: false,
    loadingDelEspecialidade: null,
    loadingProcedimento: false,
    loadingDelProcedimento: null,
    loadingProfissional: false,
    loadingDelProfissional: null,
    loadingLocal: false,
    loadingDelLocal: null,
    loadingConvenio: false,
    loadingDelConvenio: null,
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
    textIcoSalvar: '<i class="fa fa-check"></i> Salvar',
    listaEscalas: null,
    modalEdicao: null,
    showDivEdicao: true,
    codEscalaEdicao: null,
    msgEdicao: null,
    agendaEncaixe: false,
    listaAgendaExaixe: [],
    dadosEscala: null,
    init: function init() {
      if (typeof agenda != 'undefined') {
        this.agendaEspecialidades = agenda.especialidades;
        this.agendaEspecialidades.forEach(function (espec) {
          var _especialidades$find;

          espec.nm_especialidade = (_especialidades$find = especialidades.find(function (especialidade) {
            return especialidade.cd_especialidade == espec.cd_especialidade;
          })) === null || _especialidades$find === void 0 ? void 0 : _especialidades$find.nm_especialidade;
        });
        this.agendaProcedimentos = agenda.procedimentos;
        this.agendaProcedimentos.forEach(function (proc) {
          var _procedimentos$find;

          proc.nm_proc = (_procedimentos$find = procedimentos.find(function (procedimento) {
            return procedimento.cd_proc == proc.cd_proc;
          })) === null || _procedimentos$find === void 0 ? void 0 : _procedimentos$find.nm_proc;
        });
        this.agendaProfissionais = agenda.profissionais;
        this.agendaProfissionais.forEach(function (prof) {
          var _profissionais$find;

          prof.nm_profissional = (_profissionais$find = profissionais.find(function (profissional) {
            return profissional.cd_profissional == prof.cd_profissional;
          })) === null || _profissionais$find === void 0 ? void 0 : _profissionais$find.nm_profissional;
        });
        this.agendaLocais = agenda.locais;
        this.agendaLocais.forEach(function (localI) {
          var _locais$find;

          localI.nm_local = (_locais$find = locais.find(function (local) {
            return local.cd_local == localI.cd_local;
          })) === null || _locais$find === void 0 ? void 0 : _locais$find.nm_local;
        });
        this.agendaConvenios = agenda.convenios;
        this.agendaConvenios.forEach(function (conv) {
          var _convenios$find;

          conv.nm_convenio = (_convenios$find = convenios.find(function (convenio) {
            return convenio.cd_convenio == conv.cd_convenio;
          })) === null || _convenios$find === void 0 ? void 0 : _convenios$find.nm_convenio;
        });
      }

      if (typeof escala != 'undefined') {
        this.listaEscalas = escala;
      }
    },
    submitAgenda: function submitAgenda() {
      $('#formAgenda input[type=submit]').click();
    },
    getAgendamentoEncaixe: function getAgendamentoEncaixe(dados) {
      var _this = this;

      this.dadosEscala = dados;
      this.agendaEncaixe = true;
      console.log(dados);
      axios.get("/rpclinica/json/agendamento-encaixe/".concat(dados.cd_escala_agenda)).then(function (res) {
        _this.listaAgendaExaixe = res.data.retorno;

        if (!_this.listaAgendaExaixe) {
          _this.agendaEncaixe = false;
        }
      })["catch"](function (err) {
        toastr['error'](err.response.data.message);
      });
    },
    storeAgendamentoEncaixe: function storeAgendamentoEncaixe(atend) {
      var _this2 = this;

      var horario = $('#enc_horario' + atend).val();
      axios.post("/rpclinica/json/agenda-escala-encaixe/".concat(atend, "/").concat(horario)).then(function (res) {
        $('#enc_horario' + atend).val(null).trigger('change');
        _this2.listaAgendaExaixe = res.data.retorno;

        if (!_this2.listaAgendaExaixe) {
          _this2.agendaEncaixe = false;
        }

        toastr['success'](res.data.message);
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      });
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
      var _this3 = this;

      this.param.agenda = cdAgenda;
      this.param.escala = cdEscala;
      axios.post('/rpclinica/json/agenda/horarios', this.param).then(function (res) {
        _this3.agendaHorarios = res.data;
        _this3.inputsGeracao.feriados = _this3.agendaHorarios.feriados.map(function (feriado) {
          return feriado.dt_feriado;
        });

        if (_this3.agendaHorarios.escalas.bloqueios_gerados) {
          _this3.inputsGeracao.horarios_bloqueados = _this3.agendaHorarios.escalas.bloqueios_gerados.lista_horarios.split(',');
        }

        if (_this3.agendaHorarios.escalas.feriados_gerados) {
          _this3.inputsGeracao.feriados = _this3.agendaHorarios.escalas.feriados_gerados.lista_datas.split(',');
        }
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this3.loadingGetHorarios = false;
      });
    },
    timeCut: function timeCut(time) {
      return time === null || time === void 0 ? void 0 : time.substr(0, 5);
    },
    calcIntervalo: function calcIntervalo(minutes) {
      return "".concat(parseInt(minutes / 60).toString().padStart(2, 0), ":").concat(parseInt(minutes % 60).toString().padStart(2, 0));
    },
    limparEscala: function limparEscala() {
      this.msgEdicao = '';
      this.showDivEdicao = true;
      this.codEscalaEdicao = null;
      $('#check-particular span').removeClass('checked');
      $('#check-particular input').prop('checked', false);
      $('#check-convenio span').removeClass('checked');
      $('#check-convenio input').prop('checked', false);
      $('#check-sus span').removeClass('checked');
      $('#check-sus input').prop('checked', false);
      $('#check-segunda span').removeClass('checked');
      $('#check-segunda input').prop('checked', false);
      $('#check-terca span').removeClass('checked');
      $('#check-terca input').prop('checked', false);
      $('#check-quarta span').removeClass('checked');
      $('#check-quarta input').prop('checked', false);
      $('#check-quinta span').removeClass('checked');
      $('#check-quinta input').prop('checked', false);
      $('#check-sexta span').removeClass('checked');
      $('#check-sexta input').prop('checked', false);
      $('#check-sabado span').removeClass('checked');
      $('#check-sabado input').prop('checked', false);
      $('#check-domingo span').removeClass('checked');
      $('#check-domingo input').prop('checked', false);
      $('#check-sessao span').removeClass('checked');
      $('#check-sessao input').prop('checked', false);
      $('#hora_inicial').val(null);
      $('#hora_final').val(null);
      $('#qtde_encaixe').val(null).trigger('change');
      $('#qtde_sessao').val(null).trigger('change');
      $('#intervalo').val(null).trigger('change');
    },
    editarEscala: function editarEscala(dados) {
      this.msgEdicao = '<br><code><b>Atenção!!!</b> Você esta editando a escala [ ' + dados.cd_escala_agenda + ' ], Alterando os campos de Hora Inicial, Hora Final ou Intervalo, os agendamentos serão desvinculado da escala atual. </code>';
      this.showDivEdicao = false;
      this.codEscalaEdicao = dados.cd_escala_agenda;
      $('#check-particular span').removeClass('checked');
      $('#check-particular input').prop('checked', false);

      if (dados.sn_particular == '1') {
        $('#check-particular span').addClass('checked');
        $('#check-particular input').prop('checked', true);
      }

      $('#check-convenio span').removeClass('checked');
      $('#check-convenio input').prop('checked', false);

      if (dados.sn_convenio == '1') {
        $('#check-convenio span').addClass('checked');
        $('#check-convenio input').prop('checked', true);
      }

      $('#check-sus span').removeClass('checked');
      $('#check-sus input').prop('checked', false);

      if (dados.sn_sus == '1') {
        $('#check-sus span').addClass('checked');
        $('#check-sus input').prop('checked', true);
      }

      $('#check-segunda span').removeClass('checked');
      $('#check-segunda input').prop('checked', false);

      if (dados.cd_dia == 'segunda') {
        $('#check-segunda span').addClass('checked');
        $('#check-segunda input').prop('checked', true);
      }

      $('#check-terca span').removeClass('checked');
      $('#check-terca input').prop('checked', false);

      if (dados.cd_dia == 'terca') {
        $('#check-terca span').addClass('checked');
        $('#check-terca input').prop('checked', true);
      }

      $('#check-quarta span').removeClass('checked');
      $('#check-quarta input').prop('checked', false);

      if (dados.cd_dia == 'quarta') {
        $('#check-quarta span').addClass('checked');
        $('#check-quarta input').prop('checked', true);
      }

      $('#check-quinta span').removeClass('checked');
      $('#check-quinta input').prop('checked', false);

      if (dados.cd_dia == 'quinta') {
        $('#check-quinta span').addClass('checked');
        $('#check-quinta input').prop('checked', true);
      }

      $('#check-sexta span').removeClass('checked');
      $('#check-sexta input').prop('checked', false);

      if (dados.cd_dia == 'sexta') {
        $('#check-sexta span').addClass('checked');
        $('#check-sexta input').prop('checked', true);
      }

      $('#check-sabado span').removeClass('checked');
      $('#check-sabado input').prop('checked', false);

      if (dados.cd_dia == 'sabado') {
        $('#check-sabado span').addClass('checked');
        $('#check-sabado input').prop('checked', true);
      }

      $('#check-domingo span').removeClass('checked');
      $('#check-domingo input').prop('checked', false);

      if (dados.cd_dia == 'domingo') {
        $('#check-domingo span').addClass('checked');
        $('#check-domingo input').prop('checked', true);
      }

      $('#check-sessao span').removeClass('checked');
      $('#check-sessao input').prop('checked', false);

      if (dados.sn_sessao == '1') {
        $('#check-sessao span').addClass('checked');
        $('#check-sessao input').prop('checked', true);
      }

      $('#data_inicial').val(dados.dt_inicial);
      $('#data_final').val(dados.dt_fim);
      $('#hora_inicial').val(dados.hr_inicial);
      $('#hora_final').val(dados.hr_final);
      $('#qtde_encaixe').val(null).trigger('change');
      $('#qtde_encaixe').val(dados.qtde_encaixe).trigger('change');
      $('#qtde_sessao').val(null).trigger('change');
      $('#qtde_sessao').val(dados.qtde_sessao).trigger('change');
      $('#tipo_agenda').val(null).trigger('change');
      $('#tipo_agenda').val(dados.tp_agenda).trigger('change');
      $('#intervalo').val(null).trigger('change');
      $('#intervalo').val(dados.intervalo).trigger('change');
      $('#cd_escala').val(dados.cd_escala_agenda);
      /*
      var arraytipo=[];
      for (var i in dados.escala_tipo_atend) {
          arraytipo[i] = dados.escala_tipo_atend[i].cd_tipo_atendimento
      }
      if(arraytipo){
          $('#agenda_tipos_atend').val(arraytipo).select2();
      }
        var arrayprof=[];
      for (var i in dados.escala_prof) {
          arrayprof[i] = dados.escala_prof[i].cd_profissional
      }
      if(arrayEspec){
          $('#agenda_profissional').val(arrayprof).select2();
      }
        var arrayEspec=[];
      for (var i in dados.escala_espec) {
          arrayEspec[i] = dados.escala_espec[i].cd_especialidade
      }
      if(arrayEspec){
          $('#agenda_especialidade').val(arrayEspec).select2();
      }
        var arrayLocal=[];
      for (var i in dados.escala_local) {
          arrayLocal[i] = dados.escala_local[i].cd_local
      }
      if(arrayLocal){
          $('#agenda_local').val(arrayLocal).select2();
      }
        var arrayConv=[];
      for (var i in dados.escala_conv) {
          arrayConv[i] = dados.escala_conv[i].cd_convenio
      }
      if(arrayConv){
          $('#agenda_convenio').val(arrayConv).select2();
      }
      */
    },
    saveEscala: function saveEscala() {
      var _this4 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja confirma essa Alteração?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22BAA0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          _this4.textIcoSalvar = '<i class="fa fa-spinner fa-spin" ></i> Salvando';
          document.getElementById('formAgendaEscala').submit();
        }
      });
    },
    execluirEscala: function execluirEscala(Cod) {
      var _this5 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir essa Escala?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22BAA0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          axios["delete"]("/rpclinica/json/delete-escala/".concat(Cod)).then(function (res) {
            toastr['success'](res.data.message);
            _this5.listaEscalas = res.data.escalas;
          })["catch"](function (err) {
            return toastr['error'](err.response.data.message);
          });
        }
      });
    },
    LetraMaiuscula: function LetraMaiuscula(text) {
      var words = text.toLowerCase().split(" ");

      for (var a = 0; a < words.length; a++) {
        var w = words[a];
        words[a] = w[0].toUpperCase() + w.slice(1);
      }

      return words.join(" ");
    },
    gerarAgendamentos: function gerarAgendamentos() {
      var _this6 = this;

      var escala = this.agendaHorarios.escalas;

      if (!escala.cd_dia) {
        toastr['error']('Não há dias da semana marcados na genda!');
        return;
      }

      this.loadingGeracao = true;
      axios.post('/rpclinica/json/agenda/horarios/gerar-agendamentos', this.inputsGeracao).then(function (res) {
        toastr['success'](res.data.message);

        _this6.getHorarios(_this6.inputsGeracao.cd_agenda, _this6.inputsGeracao.cd_escala); //console.log(res.data.escalas);


        _this6.listaEscalas = res.data.escalas;
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this6.loadingGeracao = false;
      });
    },
    pesquisaExclusao: function pesquisaExclusao() {
      var _this7 = this;

      this.loadingExclusao = true;
      var form = new FormData(document.querySelector('#form-exclusao'));
      form.append('cd_agenda', this.agendaHorarios.agenda.cd_agenda);
      form.append('cd_escala', this.agendaHorarios.escalas.cd_escala_agenda);
      axios.post('/rpclinica/json/agenda/horarios/pesquisa-exclusao', form).then(function (res) {
        return _this7.dadosExclusao = res.data;
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this7.loadingExclusao = false;
      });
    },
    excluirDatas: function excluirDatas() {
      var _this8 = this;

      this.loadingExclusaoToggle = true;
      axios.post('/rpclinica/json/agenda/horarios/excluir-datas', {
        cd_agenda: this.inputsGeracao.cd_agenda,
        cd_escala: this.inputsGeracao.cd_escala,
        datas: this.datasParaExluir
      }).then(function (res) {
        toastr['success'](res.data.message);

        _this8.datasParaExluir.forEach(function (data) {
          delete _this8.dadosExclusao[data];
        });

        _this8.datasParaExluir = [];
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this8.loadingExclusaoToggle = false;
      });
    },
    addEspecialidade: function addEspecialidade() {
      var _this9 = this,
          _especialidades$find3;

      if ($('#agenda-especialidade').val().trim() == '' || this.agendaEspecialidades.find(function (especialidade) {
        return especialidade.cd_especialidade == $('#agenda-especialidade').val();
      })) return;

      if (typeof agenda != 'undefined') {
        this.loadingEspecialidade = true;
        axios.post("/rpclinica/json/agenda-especialidade", {
          cd_agenda: agenda.cd_agenda,
          cd_especialidade: $('#agenda-especialidade').val()
        }).then(function (res) {
          var _especialidades$find2;

          _this9.agendaEspecialidades.push(_objectSpread(_objectSpread({}, res.data), {}, {
            nm_especialidade: (_especialidades$find2 = especialidades.find(function (especialidade) {
              return especialidade.cd_especialidade == $('#agenda-especialidade').val();
            })) === null || _especialidades$find2 === void 0 ? void 0 : _especialidades$find2.nm_especialidade
          }));

          $('#agenda-especialidade').val(null).trigger('change');
        })["finally"](function () {
          return _this9.loadingEspecialidade = false;
        });
        return;
      }

      this.agendaEspecialidades.push({
        cd_especialidade: $('#agenda-especialidade').val(),
        nm_especialidade: (_especialidades$find3 = especialidades.find(function (especialidade) {
          return especialidade.cd_especialidade == $('#agenda-especialidade').val();
        })) === null || _especialidades$find3 === void 0 ? void 0 : _especialidades$find3.nm_especialidade
      });
      $('#agenda-especialidade').val(null).trigger('change');
    },
    deleteEspecialidade: function deleteEspecialidade(indice) {
      var _this10 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir esse cadastro?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          if (typeof agenda != 'undefined') {
            _this10.loadingDelEspecialidade = indice;
            axios["delete"]("/rpclinica/json/agenda-especialidade/".concat(_this10.agendaEspecialidades[indice].cd_agenda_espec)).then(function (res) {
              return _this10.agendaEspecialidades.splice(indice, 1);
            })["finally"](function () {
              return _this10.loadingDelEspecialidade = null;
            });
            return;
          }

          _this10.agendaEspecialidades.splice(indice, 1);
        }
      });
    },
    addProcedimento: function addProcedimento() {
      var _this11 = this,
          _procedimentos$find3;

      if ($('#agenda-procedimento').val().trim() == '' || this.agendaProcedimentos.find(function (procedimento) {
        return procedimento.cd_proc == $('#agenda-procedimento').val();
      })) return;

      if (typeof agenda != 'undefined') {
        this.loadingProcedimento = true;
        axios.post("/rpclinica/json/agenda-procedimento", {
          cd_agenda: agenda.cd_agenda,
          cd_proc: $('#agenda-procedimento').val()
        }).then(function (res) {
          var _procedimentos$find2;

          _this11.agendaProcedimentos.push(_objectSpread(_objectSpread({}, res.data), {}, {
            nm_proc: (_procedimentos$find2 = procedimentos.find(function (procedimento) {
              return procedimento.cd_proc == $('#agenda-procedimento').val();
            })) === null || _procedimentos$find2 === void 0 ? void 0 : _procedimentos$find2.nm_proc
          }));

          $('#agenda-procedimento').val(null).trigger('change');
        })["finally"](function () {
          return _this11.loadingProcedimento = false;
        });
        return;
      }

      this.agendaProcedimentos.push({
        cd_proc: $('#agenda-procedimento').val(),
        nm_proc: (_procedimentos$find3 = procedimentos.find(function (procedimento) {
          return procedimento.cd_proc == $('#agenda-procedimento').val();
        })) === null || _procedimentos$find3 === void 0 ? void 0 : _procedimentos$find3.nm_proc
      });
      $('#agenda-procedimento').val(null).trigger('change');
    },
    deleteProcedimento: function deleteProcedimento(indice) {
      var _this12 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir esse cadastro?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          if (typeof agenda != 'undefined') {
            _this12.loadingDelProcedimento = indice;
            axios["delete"]("/rpclinica/json/agenda-procedimento/".concat(_this12.agendaProcedimentos[indice].cd_agenda_proc)).then(function (res) {
              return _this12.agendaProcedimentos.splice(indice, 1);
            })["finally"](function () {
              return _this12.loadingDelProcedimento = null;
            });
            return;
          }

          _this12.agendaProcedimentos.splice(indice, 1);
        }
      });
    },
    addProfissional: function addProfissional() {
      var _this13 = this,
          _profissionais$find3;

      if ($('#agenda-profissional').val().trim() == '' || this.agendaProfissionais.find(function (profissional) {
        return profissional.cd_profissional == $('#agenda-profissional').val();
      })) return;

      if (typeof agenda != 'undefined') {
        this.loadingProfissional = true;
        axios.post("/rpclinica/json/agenda-profissional", {
          cd_agenda: agenda.cd_agenda,
          cd_profissional: $('#agenda-profissional').val()
        }).then(function (res) {
          var _profissionais$find2;

          _this13.agendaProfissionais.push(_objectSpread(_objectSpread({}, res.data), {}, {
            nm_profissional: (_profissionais$find2 = profissionais.find(function (profissional) {
              return profissional.cd_profissional == $('#agenda-profissional').val();
            })) === null || _profissionais$find2 === void 0 ? void 0 : _profissionais$find2.nm_profissional
          }));

          $('#agenda-profissional').val(null).trigger('change');
        })["finally"](function () {
          return _this13.loadingProfissional = false;
        });
        return;
      }

      this.agendaProfissionais.push({
        cd_profissional: $('#agenda-profissional').val(),
        nm_profissional: (_profissionais$find3 = profissionais.find(function (profissional) {
          return profissional.cd_profissional == $('#agenda-profissional').val();
        })) === null || _profissionais$find3 === void 0 ? void 0 : _profissionais$find3.nm_profissional
      });
      $('#agenda-profissional').val(null).trigger('change');
    },
    deleteProfissional: function deleteProfissional(indice) {
      var _this14 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir esse cadastro?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          if (typeof agenda != 'undefined') {
            _this14.loadingDelProfissional = indice;
            axios["delete"]("/rpclinica/json/agenda-profissional/".concat(_this14.agendaProfissionais[indice].cd_agenda_prof)).then(function (res) {
              return _this14.agendaProfissionais.splice(indice, 1);
            })["finally"](function () {
              return _this14.loadingDelProfissional = null;
            });
            return;
          }

          _this14.agendaProfissionais.splice(indice, 1);
        }
      });
    },
    addLocal: function addLocal() {
      var _this15 = this,
          _locais$find3;

      if ($('#agenda-local').val().trim() == '' || this.agendaLocais.find(function (local) {
        return local.cd_local == $('#agenda-local').val();
      })) return;

      if (typeof agenda != 'undefined') {
        this.loadingLocal = true;
        axios.post("/rpclinica/json/agenda-local", {
          cd_agenda: agenda.cd_agenda,
          cd_local: $('#agenda-local').val()
        }).then(function (res) {
          var _locais$find2;

          _this15.agendaLocais.push(_objectSpread(_objectSpread({}, res.data), {}, {
            nm_local: (_locais$find2 = locais.find(function (local) {
              return local.cd_local == $('#agenda-local').val();
            })) === null || _locais$find2 === void 0 ? void 0 : _locais$find2.nm_local
          }));

          $('#agenda-local').val(null).trigger('change');
        })["finally"](function () {
          return _this15.loadingLocal = false;
        });
        return;
      }

      this.agendaLocais.push({
        cd_local: $('#agenda-local').val(),
        nm_local: (_locais$find3 = locais.find(function (local) {
          return local.cd_local == $('#agenda-local').val();
        })) === null || _locais$find3 === void 0 ? void 0 : _locais$find3.nm_local
      });
      $('#agenda-local').val(null).trigger('change');
    },
    deleteLocal: function deleteLocal(indice) {
      var _this16 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir esse cadastro?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          if (typeof agenda != 'undefined') {
            _this16.loadingDelLocal = indice;
            axios["delete"]("/rpclinica/json/agenda-local/".concat(_this16.agendaLocais[indice].cd_agenda_local)).then(function (res) {
              return _this16.agendaLocais.splice(indice, 1);
            })["finally"](function () {
              return _this16.loadingDelLocal = null;
            });
            return;
          }

          _this16.agendaLocais.splice(indice, 1);
        }
      });
    },
    addConvenio: function addConvenio() {
      var _this17 = this,
          _convenios$find3;

      if ($('#agenda-convenio').val().trim() == '' || this.agendaConvenios.find(function (convenio) {
        return convenio.cd_convenio == $('#agenda-convenio').val();
      })) return;

      if (typeof agenda != 'undefined') {
        this.loadingConvenio = true;
        axios.post("/rpclinica/json/agenda-convenio", {
          cd_agenda: agenda.cd_agenda,
          cd_convenio: $('#agenda-convenio').val()
        }).then(function (res) {
          var _convenios$find2;

          _this17.agendaConvenios.push(_objectSpread(_objectSpread({}, res.data), {}, {
            nm_convenio: (_convenios$find2 = convenios.find(function (convenio) {
              return convenio.cd_convenio == $('#agenda-convenio').val();
            })) === null || _convenios$find2 === void 0 ? void 0 : _convenios$find2.nm_convenio
          }));

          $('#agenda-convenio').val(null).trigger('change');
        })["finally"](function () {
          return _this17.loadingConvenio = false;
        });
        return;
      }

      this.agendaConvenios.push({
        cd_convenio: $('#agenda-convenio').val(),
        nm_convenio: (_convenios$find3 = convenios.find(function (convenio) {
          return convenio.cd_convenio == $('#agenda-convenio').val();
        })) === null || _convenios$find3 === void 0 ? void 0 : _convenios$find3.nm_convenio
      });
      $('#agenda-convenio').val(null).trigger('change');
    },
    deleteConvenio: function deleteConvenio(indice) {
      var _this18 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir esse cadastro?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22BAA0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          if (typeof agenda != 'undefined') {
            _this18.loadingDelConvenio = indice;
            axios["delete"]("/rpclinica/json/agenda-convenio/".concat(_this18.agendaConvenios[indice].cd_agenda_conv)).then(function (res) {
              return _this18.agendaConvenios.splice(indice, 1);
            })["finally"](function () {
              return _this18.loadingDelConvenio = null;
            });
            return;
          }

          _this18.agendaConvenios.splice(indice, 1);
        }
      });
    }
  };
});
$(document).ready(function () {
  $('#agenda-procedimento').select2({
    ajax: {
      url: '/rpclinica/json/search-procedimento',
      dataType: 'json',
      processResults: function processResults(data) {
        var _$$data$results$lastP;

        var search = (_$$data$results$lastP = $('#agenda-procedimento').data('select2').results.lastParams) === null || _$$data$results$lastP === void 0 ? void 0 : _$$data$results$lastP.term;
        return {
          results: data
        };
      }
    }
  });
});
/******/ })()
;