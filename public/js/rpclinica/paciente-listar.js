/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************************!*\
  !*** ./resources/js/rpclinica/paciente-listar.js ***!
  \***************************************************/
Alpine.data('app', function () {
  return {
    buttonDisabled: false,
    loading: false,
    loadingTela: 'doc',
    snEdicaoDoc: false,
    loadingDoc: false,
    pacientes: [],
    pacienteSelected: null,
    valuesEstadoCivil: {
      S: 'Solteiro',
      C: 'Casado',
      D: 'Divorciado',
      V: 'Viúvo'
    },
    msg: {
      titulo: '',
      file: '',
      msg: '',
      doc: '',
      celular: '',
      compartilhar: 'ZAP'
    },
    classLabelSituacao: {
      livre: 'label-success',
      agendado: 'label-primary',
      confirmado: 'label-info',
      atendido: 'label-warning',
      bloqueado: 'label-danger'
    },
    loadingConsulta: false,
    // historico
    historicoAgendamentoSelected: null,
    historicoPrintAgendamento: false,
    Anamnese: {
      Documento: '',
      Titulo: '',
      cdDocumento: null
    },
    dadosDocumentos: [],
    dadosEnvios: [],
    buttonSalvar: " <i class='fa fa-check'></i>  Salvar ",
    buttonEnviar: "<i class='fa fa-send-o'></i> Enviar ",
    editorFormulario: null,
    init: function init() {
      var _this = this;

      this.pacientes = pacientes;
      this.editorFormulario = CKEDITOR.replace('editor-formulario', {
        // Define the toolbar groups as it is a more accessible solution.
        toolbarGroups: [{
          "name": "basicstyles",
          "groups": ["basicstyles"]
        }, {
          "name": "undo",
          "groups": ["Undo", "Redo"]
        }, {
          "name": "paragraph",
          "groups": ["list", "blocks"]
        }, {
          "name": "insert",
          "groups": ["insert"]
        }, {
          "name": "styles",
          "groups": ["styles"]
        }],
        // Remove the redundant buttons from toolbar groups defined above.
        removeButtons: 'Subscript,Superscript,',
        resize_enabled: false,
        removePlugins: 'elementspath',
        height: ['300px'],
        enterMode: CKEDITOR.ENTER_BR,
        // Define Enter como <br>
        shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>

      });
      $('#modeloDocumento').on('select2:select', function (evt) {
        var idForm = evt.params.data.id;
        /*
        var filtrado = documentos.filter(function(obj) { return obj.cd_formulario == idForm; });
        if(filtrado[0]){ 
            this.Anamnese.Documento=filtrado[0].conteudo_text; 
            this.Anamnese.Titulo=filtrado[0].nm_formulario; 
        } 
        */

        axios.get("/rpclinica/json/paciente-doc/".concat(idForm, "/").concat(_this.pacienteSelected.cd_paciente)).then(function (res) {
          _this.editorFormulario.setData(res.data.retorno);

          _this.Anamnese.Documento = res.data.retorno;
          _this.Anamnese.Titulo = res.data.nm_formulario;
        })["catch"](function (err) {
          toastr['error'](err.response.data.message, 'Erro');
        });
      });
      $('#modal-resumo-paciente').on('hidden.bs.modal', function () {
        $('#procedimento').val(null).trigger('change');
        $('#convenio').val(null).trigger('change');
        $('#especialidade').val(null).trigger('change');
      });
    },
    openModal: function openModal(cdPaciente) {
      this.pacienteSelected = this.pacientes.find(function (paciente) {
        return paciente.cd_paciente == cdPaciente;
      });
      $('#convenio').val(this.pacienteSelected.cd_categoria).trigger('change');
      $('#cartao').val(this.pacienteSelected.cartao);
      $('#modal-resumo-paciente').modal('show');
    },
    submitConsulta: function submitConsulta() {
      var _this2 = this;

      this.loadingConsulta = true;
      var formConsulta = new FormData(this.$refs.formIniciarConsulta);
      axios.post('/rpclinica/json/paciente-iniciar-consulta', formConsulta).then(function (res) {
        return location.href = res.data.consulta;
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this2.loadingConsulta = false;
      });
    },
    openModalHistorico: function openModalHistorico(cdPaciente) {
      this.pacienteSelected = this.pacientes.find(function (paciente) {
        return paciente.cd_paciente == cdPaciente;
      });
      $('#modal-historico-paciente').modal('show');
    },
    openModalHistoricoAgendamento: function openModalHistoricoAgendamento(cdAgendamento) {
      this.historicoAgendamentoSelected = this.pacienteSelected.agendamentos.find(function (agendamento) {
        return agendamento.cd_agendamento == cdAgendamento;
      });
      this.historicoPrintAgendamento = true;
    },
    imprimirAnamnese: function imprimirAnamnese() {
      var url = "/rpclinica/consulta/anamnese/download-pdf/".concat(this.historicoAgendamentoSelected.cd_agendamento);
      axios.all([axios.get(url, {
        params: {
          tipo: 'anamnese'
        },
        responseType: 'blob'
      }), axios.get(url, {
        params: {
          tipo: 'exame'
        },
        responseType: 'blob'
      }), axios.get(url, {
        params: {
          tipo: 'hipotese'
        },
        responseType: 'blob'
      }), axios.get(url, {
        params: {
          tipo: 'conduta'
        },
        responseType: 'blob'
      })]).then(axios.spread(function (anamnese, exame, hipotese, conduta) {
        window.open(URL.createObjectURL(anamnese.data), 'anamnese.pdf');
        window.open(URL.createObjectURL(exame.data), 'exame_fisico.pdf');
        window.open(URL.createObjectURL(hipotese.data), 'hipotese_diagnostica.pdf');
        window.open(URL.createObjectURL(conduta.data), 'conduta.pdf');
      }))["catch"](function (err) {
        return toastr['error']('Houve um erro ao imprimir.');
      });
    },
    imprimirDocumento: function imprimirDocumento(cdDocumento) {
      axios.get("/rpclinica/consulta/anamnese/download-pdf/".concat(this.historicoAgendamentoSelected.cd_agendamento), {
        params: {
          tipo: 'documento',
          cdDocumento: cdDocumento
        },
        responseType: 'blob'
      }).then(function (res) {
        window.open(URL.createObjectURL(res.data), 'documento.pdf');
      })["catch"](function (err) {
        return toastr['error']('Houve um erro ao imprimir o documento!');
      });
    },

    /* Documento */
    openModalDocumento: function openModalDocumento(dadosPaciente) {
      var _this3 = this;

      this.loadingTela = 'doc';
      this.pacienteSelected = dadosPaciente;
      this.Anamnese.Documento = null;
      this.Anamnese.Titulo = null;
      this.snEdicaoDoc = false;
      $('#modeloDocumento').val(null).trigger('change'); // $('#documentoPaciente').modal('show');

      console.log(dadosPaciente);
      axios.get("/rpclinica/json/getDocumentoPacinete/".concat(dadosPaciente.cd_paciente)).then(function (res) {
        _this3.editorFormulario.setData('');

        _this3.dadosDocumentos = res.data.documento;
        _this3.dadosEnvios = res.data.envio;
      })["catch"](function (err) {
        return toastr['error']('Houve um erro ao carregar o documento!');
      });
    },
    storeDocumento: function storeDocumento() {
      var _this$editorFormulari,
          _this$editorFormulari2,
          _this4 = this;

      this.loadingDoc = true;
      this.loading = true;
      this.buttonSalvar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ";
      this.buttonDisabled = true;
      var form = new FormData(document.querySelector('#form_DOCUMENTO'));
      var conteudoAnamnese = (_this$editorFormulari = this.editorFormulario) !== null && _this$editorFormulari !== void 0 && _this$editorFormulari.getData() ? (_this$editorFormulari2 = this.editorFormulario) === null || _this$editorFormulari2 === void 0 ? void 0 : _this$editorFormulari2.getData() : '';
      form.set('documento', conteudoAnamnese);
      this.Anamnese.Documento = conteudoAnamnese;
      axios.post("/rpclinica/json/storeDocumentoPacinete/".concat(this.pacienteSelected.cd_paciente), form).then(function (res) {
        var _this4$editorFormular;

        console.log(res.data);
        _this4.dadosDocumentos = res.data.documento;
        $('#modeloDocumento').val(null).trigger('change');
        (_this4$editorFormular = _this4.editorFormulario) === null || _this4$editorFormular === void 0 ? void 0 : _this4$editorFormular.setData("");
        _this4.Anamnese.Titulo = "";
        _this4.Anamnese.cdDocumento = "";
        _this4.Anamnese.Documento = "";
        toastr['success'](res.data.message);
      })["catch"](function (err) {
        toastr['error'](err.response.data.message, 'Erro');
      })["finally"](function () {
        _this4.loading = false;
        _this4.loadingDoc = false;
        _this4.buttonDisabled = false;
        _this4.Anamnese.Documento = null;
        _this4.Anamnese.cdDocumento = null;
        _this4.snEdicaoDoc = false;
        _this4.buttonSalvar = " <i class='fa fa-check'></i>  Salvar ";
      });
    },
    storeMsg: function storeMsg() {
      var _this5 = this;

      this.loadingDoc = true;
      this.loading = true;
      this.buttonEnviar = " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Enviando... ";
      this.buttonDisabled = true;
      axios.post("/rpclinica/json/imprimirDocumentoPaciente/".concat(this.msg.doc), this.msg).then(function (res) {
        console.log(res.data);

        if (res.data.dados.status == '200') {
          toastr['success']('Mensagem enviada com sucesso!');
          _this5.loadingTela = 'doc';
          _this5.msg.doc = '';
          _this5.msg.titulo = '';
          _this5.msg.file = '';
          _this5.msg.msg = '';
          _this5.msg.celular = '';
          _this5.dadosEnvios = res.data.envio;
        } else {
          toastr['error'](res.data.dados.message, 'Erro');
        }
      })["catch"](function (err) {
        toastr['error'](err.response.data.message, 'Erro');
      })["finally"](function () {
        _this5.loading = false;
        _this5.loadingDoc = false;
        _this5.buttonDisabled = false;
        _this5.Anamnese.Documento = null;
        _this5.Anamnese.cdDocumento = null;
        _this5.snEdicaoDoc = false;
        _this5.buttonEnviar = " <i class='fa fa-send-o'></i> Enviar ";
      });
    },
    editDocumento: function editDocumento(documento) {
      var _this$editorFormulari3;

      this.snEdicaoDoc = true;
      console.log(documento);
      $('#modeloDocumento').val(documento.cd_formulario).trigger('change');
      this.Anamnese.Documento = documento.conteudo;
      this.Anamnese.cdDocumento = documento.cd_documento_paciente;
      this.Anamnese.Titulo = documento.titulo;
      (_this$editorFormulari3 = this.editorFormulario) === null || _this$editorFormulari3 === void 0 ? void 0 : _this$editorFormulari3.setData(documento.conteudo);
    },
    cancelarEdicao: function cancelarEdicao() {
      $('#modeloDocumento').val(null).trigger('change');
      this.Anamnese.Documento = null;
      this.Anamnese.cdDocumento = null;
      this.Anamnese.Titulo = null;
      this.snEdicaoDoc = false;
    },
    excluirDocumento: function excluirDocumento(idDocumento) {
      var _this6 = this;

      Swal.fire({
        title: 'Confirmação',
        html: "<h4 style='font-weight: 400;font-style: italic;'>Deseja Excluir esse Documento?</h4>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22baa0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          _this6.loading = true;
          _this6.loadingDoc = true;
          axios["delete"]("/rpclinica/json/deleteDocumentoPac/".concat(_this6.pacienteSelected.cd_paciente, "/").concat(idDocumento)).then(function (res) {
            _this6.dadosDocumentos = res.data.documento;
            toastr['success']('Documento deletado com sucesso!');
          })["catch"](function (err) {
            return toastr['error'](err.response.data.message);
          })["finally"](function () {
            _this6.loading = false;
            _this6.loadingDoc = false;
            _this6.deleteDocIndice = null;
          });
        }
      });
    },
    teste: function teste(dados) {
      console.log(dados);
      this.loadingTela = 'msg';
      this.msg.titulo = dados.titulo;
      this.msg.msg = '';
      this.msg.celular = this.pacienteSelected.celular;
      this.msg.doc = dados.cd_documento_paciente;
      /*
      Swal.fire({
          title: "<strong>HTML <u>example</u></strong>",
          icon: "info",
          html: `
            You can use <b>bold text</b>,
            <a href="#" autofocus>links</a>,
            and other HTML tags
          `,
          showCloseButton: true,
          showCancelButton: true,
          focusConfirm: false,
          confirmButtonText: `
            <i class="fa fa-thumbs-up"></i> Great!
          `,
          confirmButtonAriaLabel: "Thumbs up, great!",
          cancelButtonText: `
            <i class="fa fa-thumbs-down"></i>
          `,
          cancelButtonAriaLabel: "Thumbs down"
      });
      */
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