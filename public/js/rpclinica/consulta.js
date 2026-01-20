/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************!*\
  !*** ./resources/js/rpclinica/consulta.js ***!
  \********************************************/
Alpine.data('app', function () {
  return {
    saveLoadingTriagem: false,
    saveLoadingAnamnese: false,
    saveLoadingProblemas: false,
    saveLoadingExames: false,
    saveLoadingDoc: false,
    tipo_doc: null,
    deleteDocIndice: null,
    formularios: [],
    paciente: [],
    documentos: [],
    cdDocumentoEdicao: null,
    // historico
    historicoAgendametos: [],
    historicoAgendamentoSelected: null,
    historicoPrintAgendamento: false,
    anexos: [],
    loadingAnexos: false,
    indiceDeleteAnexo: null,
    editorAnamnese: null,
    editorExame: null,
    editorHipotese: null,
    editorConduta: null,
    editorDocumentos: null,
    editorHistoricoExames: null,
    editorProblemas: null,
    viewVip: false,
    swalWithBootstrapButtons: Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success swal-button",
        cancelButton: "btn btn-danger swal-button",
        input: "form-control"
      },
      buttonsStyling: false
    }),
    assinatura_digital: {
      situacao: docAssinado,
      conteudo: null
    },
    historicoPaciente: null,
    init: function init() {
      var _this = this;

      this.formularios = formularios;
      this.documentos = agendamentoDocumentos;
      this.historicoAgendametos = historicoAgentamentos;
      this.paciente = paciente;

      if (this.paciente.vip == 'S') {
        this.viewVip = true;
      } else {
        this.viewVip = false;
      }

      this.editorAnamnese = CKEDITOR.replace('editor-formulario-anamnese');
      this.editorExame = CKEDITOR.replace('editor-formulario-exame-fisico');
      this.editorHipotese = CKEDITOR.replace('editor-formulario-hipotese-diagnostica');
      this.editorConduta = CKEDITOR.replace('editor-formularios-conduta');
      this.editorDocumentos = CKEDITOR.replace('editor-formularios-documentos');
      this.editorHistoricoExames = CKEDITOR.replace('editor-exames-tabexames');
      this.editorProblemas = CKEDITOR.replace('editor-problemas');
      $('#formularios-anamnese').on('select2:select', function (evt) {
        var val = evt.params.data.id;

        var formulario = _this.formularios.find(function (formulario) {
          return val == formulario.cd_formulario;
        });

        _this.editorAnamnese.setData(formulario === null || formulario === void 0 ? void 0 : formulario.conteudo);
      });
      $('#formularios-exame-fisico').on('select2:select', function (evt) {
        var val = evt.params.data.id;

        var formulario = _this.formularios.find(function (formulario) {
          return val == formulario.cd_formulario;
        });

        _this.editorExame.setData(formulario === null || formulario === void 0 ? void 0 : formulario.conteudo);
      });
      $('#formularios-hipostese-diagnostica').on('select2:select', function (evt) {
        var val = evt.params.data.id;

        var formulario = _this.formularios.find(function (formulario) {
          return val == formulario.cd_formulario;
        });

        _this.editorHipotese.setData(formulario === null || formulario === void 0 ? void 0 : formulario.conteudo);
      });
      $('#formularios-conduta').on('select2:select', function (evt) {
        var val = evt.params.data.id;

        var formulario = _this.formularios.find(function (formulario) {
          return val == formulario.cd_formulario;
        });

        _this.editorConduta.setData(formulario === null || formulario === void 0 ? void 0 : formulario.conteudo);
      });
      $('#formularios-documentos').on('select2:select', function (evt) {
        var val = evt.params.data.id;

        var formulario = _this.formularios.find(function (formulario) {
          return val == formulario.cd_formulario;
        });

        _this.editorDocumentos.setData(formulario === null || formulario === void 0 ? void 0 : formulario.conteudo);
      });
      window.addEventListener('message', function (evt) {
        if (evt.data !== 'added-anexos') return;

        _this.getAnexos();
      });
      this.getAnexos();
    },
    submitTriagem: function submitTriagem() {
      var _this2 = this;

      this.saveLoadingTriagem = true;
      var form = new FormData(document.querySelector('#tabTriagem'));
      axios.post("/rpclinica/json/consulta/triagem/".concat(idAgendamento), form).then(function (res) {
        return toastr['success'](res.data.message);
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this2.saveLoadingTriagem = false;
      });
    },
    submitAnamnese: function submitAnamnese() {
      var _this$editorAnamnese,
          _this$editorExame,
          _this$editorHipotese,
          _this$editorConduta,
          _this3 = this;

      this.saveLoadingAnamnese = true;
      var form = new FormData(document.querySelector('#tabAnamnese'));
      form.set('anamnese', (_this$editorAnamnese = this.editorAnamnese) === null || _this$editorAnamnese === void 0 ? void 0 : _this$editorAnamnese.getData());
      form.set('exame_fisico', (_this$editorExame = this.editorExame) === null || _this$editorExame === void 0 ? void 0 : _this$editorExame.getData());
      form.set('hipotese_diagnostica', (_this$editorHipotese = this.editorHipotese) === null || _this$editorHipotese === void 0 ? void 0 : _this$editorHipotese.getData());
      form.set('conduta', (_this$editorConduta = this.editorConduta) === null || _this$editorConduta === void 0 ? void 0 : _this$editorConduta.getData());
      axios.post("/rpclinica/json/consulta/anamnese/".concat(idAgendamento), form).then(function (res) {
        console.log(res);
        toastr['success'](res.data.message);
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this3.saveLoadingAnamnese = false;
      });
    },
    submitListaProblemas: function submitListaProblemas() {
      var _this$editorProblemas,
          _this4 = this;

      this.saveLoadingProblemas = true;
      var form = new FormData(document.querySelector('#tabProblemas'));
      form.append('problemas', (_this$editorProblemas = this.editorProblemas) === null || _this$editorProblemas === void 0 ? void 0 : _this$editorProblemas.getData());
      axios.post("/rpclinica/json/consulta/problemas/".concat(idAgendamento), form).then(function (res) {
        return toastr['success'](res.data.message);
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this4.saveLoadingProblemas = false;
      });
    },
    submitHistoricoExames: function submitHistoricoExames() {
      var _this$editorHistorico,
          _this5 = this;

      this.saveLoadingExames = true;
      var form = new FormData(document.querySelector('#tabExames'));
      form.append('exames', (_this$editorHistorico = this.editorHistoricoExames) === null || _this$editorHistorico === void 0 ? void 0 : _this$editorHistorico.getData());
      axios.post("/rpclinica/json/consulta/exames/".concat(idAgendamento), form).then(function (res) {
        return toastr['success'](res.data.message);
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this5.saveLoadingExames = false;
      });
    },
    submitDocumentos: function submitDocumentos() {
      var _this6 = this,
          _this$editorDocumento2;

      this.saveLoadingDoc = true;
      var form = new FormData(document.querySelector('#formDocumento'));

      if (this.cdDocumentoEdicao !== null) {
        var _this$editorDocumento;

        axios.put("/rpclinica/json/consulta/documento/".concat(this.cdDocumentoEdicao), {
          conteudo: (_this$editorDocumento = this.editorDocumentos) === null || _this$editorDocumento === void 0 ? void 0 : _this$editorDocumento.getData()
        }).then(function (res) {
          toastr['success'](res.data.message);

          var indexDoc = _this6.documentos.findIndex(function (doc) {
            return doc.cd_documento == _this6.cdDocumentoEdicao;
          });

          _this6.documentos[indexDoc] = res.data.documento;

          _this6.limbarDocs();
        })["catch"](function (err) {
          return toastr['error'](err.response.data.message);
        })["finally"](function () {
          _this6.saveLoadingDoc = false;
          _this6.cdDocumentoEdicao = null;

          _this6.editorDocumentos.setData(null);

          $('#tabDocumentos select#formularios-documentos').val(null).trigger('change');
          $('#editor-formularios-documentos').code(null);
        });
        return;
      }

      form.append('formulario', $('#formularios-documentos').val() ? $('#formularios-documentos').val() : '0');
      form.append('conteudo', (_this$editorDocumento2 = this.editorDocumentos) === null || _this$editorDocumento2 === void 0 ? void 0 : _this$editorDocumento2.getData());
      axios.post("/rpclinica/json/consulta/documento/".concat(idAgendamento), form).then(function (res) {
        if (!res.data.documento) {
          toastr['error'](res.data.message);
        } else {
          toastr['success'](res.data.message);

          _this6.documentos.push(res.data.documento);
        }

        _this6.limbarDocs();
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this6.saveLoadingDoc = false;
      });
    },
    editDocumento: function editDocumento(documento) {
      this.cdDocumentoEdicao = documento.cd_documento;
      $('#tabDocumentos select#formularios-documentos').val(documento.cd_formulario).trigger('change');
      $('#editor-formularios-documentos').code(documento.conteudo);
      this.editorDocumentos.setData(documento.conteudo); // this.cdDocumentoEdicao = documento.cd_documento;
    },
    excluirDocumento: function excluirDocumento(idDocumento, indice) {
      var _this7 = this;

      this.swalWithBootstrapButtons.fire({
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
          _this7.deleteDocIndice = indice;
          axios["delete"]("/rpclinica/json/consulta/documento/delete/".concat(idDocumento)).then(function (res) {
            toastr['success'](res.data.message);

            _this7.documentos.splice(indice, 1);
          })["catch"](function (err) {
            return toastr['error'](err.response.data.message);
          })["finally"](function () {
            return _this7.deleteDocIndice = null;
          });
        }
      });
    },
    limbarDocs: function limbarDocs() {
      this.cdDocumentoEdicao = null;
      $('#editor-formularios-documentos').code(null);
      $('#formularios-documentos').val(null).trigger('change');
    },
    openModalHistoricoAgendamento: function openModalHistoricoAgendamento(cdAgendamento) {
      this.historicoAgendamentoSelected = this.historicoAgendametos.find(function (agendamento) {
        return agendamento.cd_agendamento == cdAgendamento;
      });
      this.historicoPrintAgendamento = true;
    },
    nl2br: function nl2br(str, replaceMode, isXhtml) {
      var breakTag = isXhtml ? '<br />' : '<br>';
      var replaceStr = replaceMode ? '$1' + breakTag : '$1' + breakTag + '$2';
      return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
    },
    encerrarConsulta: function encerrarConsulta(evt) {
      this.swalWithBootstrapButtons.fire({
        title: 'Confirmação',
        html: "<h4 style='font-weight: 400;font-style: italic;'>Deseja encerrar esse atendimento?</h4>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22baa0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) location.href = "/rpclinica/consulta-finalizar/".concat(idAgendamento);
      });
    },
    imprimirAnamneseHist: function imprimirAnamneseHist(cdAgenda) {
      console.log(cdAgenda);
      var url = "/rpclinica/consulta/anamnese/download-pdf/".concat(cdAgenda);
      axios.all([axios.get(url, {
        params: {
          tipo: 'anamnese'
        },
        responseType: 'blob'
      })
      /*,
      axios.get(url, { params: { tipo: 'exame' }, responseType: 'blob' }),
      axios.get(url, { params: { tipo: 'hipotese' }, responseType: 'blob' }),
      axios.get(url, { params: { tipo: 'conduta' }, responseType: 'blob' })
      */
      ]).then(axios.spread(function (anamnese
      /*, exame, hipotese, conduta*/
      ) {
        window.open(URL.createObjectURL(anamnese.data), 'anamnese.pdf');
        /*window.open(URL.createObjectURL(exame.data), 'exame_fisico.pdf');
        window.open(URL.createObjectURL(hipotese.data), 'hipotese_diagnostica.pdf');
        window.open(URL.createObjectURL(conduta.data), 'conduta.pdf');
        */
      }))["catch"](function (err) {
        return toastr['error']('Houve um erro ao imprimir.');
      });
    },
    saveLoadingdocPadrao: function saveLoadingdocPadrao(tipo) {
      var _this8 = this;

      this.swalWithBootstrapButtons.fire({
        title: 'Salvar Documento',
        icon: 'warning',
        html: "<h4 style='font-weight: 500;font-style: italic;'>Insira o nome do Documento!</h4>",
        input: "text",
        showCancelButton: true,
        confirmButtonColor: '#22BAA0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        var titulo = result.value;

        if (tipo == 'CON') {
          var _this8$editorConduta, _this8$editorConduta2;

          var form = new FormData(document.querySelector('#tabAnamnese'));
          form.set('conduta', (_this8$editorConduta = _this8.editorConduta) === null || _this8$editorConduta === void 0 ? void 0 : _this8$editorConduta.getData());
          var conteudo = (_this8$editorConduta2 = _this8.editorConduta) === null || _this8$editorConduta2 === void 0 ? void 0 : _this8$editorConduta2.getData();
        }

        if (tipo == 'ATE') {
          var _this8$editorAnamnese, _this8$editorAnamnese2;

          var _form = new FormData(document.querySelector('#tabAnamnese'));

          _form.set('anamnese', (_this8$editorAnamnese = _this8.editorAnamnese) === null || _this8$editorAnamnese === void 0 ? void 0 : _this8$editorAnamnese.getData());

          var conteudo = (_this8$editorAnamnese2 = _this8.editorAnamnese) === null || _this8$editorAnamnese2 === void 0 ? void 0 : _this8$editorAnamnese2.getData();
        }

        if (tipo == 'EXA') {
          var _this8$editorExame, _this8$editorExame2;

          var _form2 = new FormData(document.querySelector('#tabAnamnese'));

          _form2.set('exame_fisico', (_this8$editorExame = _this8.editorExame) === null || _this8$editorExame === void 0 ? void 0 : _this8$editorExame.getData());

          var conteudo = (_this8$editorExame2 = _this8.editorExame) === null || _this8$editorExame2 === void 0 ? void 0 : _this8$editorExame2.getData();
        }

        if (tipo == 'HIP') {
          var _this8$editorHipotese, _this8$editorHipotese2;

          var _form3 = new FormData(document.querySelector('#tabAnamnese'));

          _form3.set('hipotese_diagnostica', (_this8$editorHipotese = _this8.editorHipotese) === null || _this8$editorHipotese === void 0 ? void 0 : _this8$editorHipotese.getData());

          var conteudo = (_this8$editorHipotese2 = _this8.editorHipotese) === null || _this8$editorHipotese2 === void 0 ? void 0 : _this8$editorHipotese2.getData();
        }

        if (tipo == 'DOC') {
          var _this8$editorDocument, _this8$editorDocument2;

          var _form4 = new FormData(document.querySelector('#formDocumento'));

          _form4.set('hipotese_diagnostica', (_this8$editorDocument = _this8.editorDocumentos) === null || _this8$editorDocument === void 0 ? void 0 : _this8$editorDocument.getData());

          var conteudo = (_this8$editorDocument2 = _this8.editorDocumentos) === null || _this8$editorDocument2 === void 0 ? void 0 : _this8$editorDocument2.getData();
        }

        axios.post("/rpclinica/consulta/doc_padrao", {
          conteudo: conteudo,
          titulo: titulo,
          tipo: tipo
        }).then(function (res) {
          toastr['success'](res.data.message);
        })["catch"](function (err) {
          return toastr['error']('Houve um erro ao imprimir o documento!');
        });
      });
    },
    imprimirAnamnese: function imprimirAnamnese() {
      if ($("#sn_header").is(':checked')) {
        var sn_header = 'S';
      } else {
        var sn_header = 'N';
      }

      if ($("#sn_footer").is(':checked')) {
        var sn_footer = 'S';
      } else {
        var sn_footer = 'N';
      }

      if ($("#sn_logo").is(':checked')) {
        var sn_logo = 'S';
      } else {
        var sn_logo = 'N';
      }

      if ($("#sn_data").is(':checked')) {
        var sn_data = 'S';
      } else {
        var sn_data = 'N';
      }

      var url = "/rpclinica/consulta/anamnese/download-pdf/".concat(idAgendamento);
      axios.all([axios.get(url, {
        params: {
          tipo: 'anamnese',
          header: sn_header,
          logo: sn_logo,
          footer: sn_footer,
          data: sn_data,
          assinado: this.assinatura_digital.situacao
        },
        responseType: 'blob'
      })
      /*,
      axios.get(url, { params: { tipo: 'exame' }, responseType: 'blob' }),
      axios.get(url, { params: { tipo: 'hipotese' }, responseType: 'blob' }),
      axios.get(url, { params: { tipo: 'conduta' }, responseType: 'blob' })
      */
      ]).then(axios.spread(function (anamnese
      /*, exame, hipotese, conduta*/
      ) {
        window.open(URL.createObjectURL(anamnese.data), 'anamnese.pdf');
        /*window.open(URL.createObjectURL(exame.data), 'exame_fisico.pdf');
        window.open(URL.createObjectURL(hipotese.data), 'hipotese_diagnostica.pdf');
        window.open(URL.createObjectURL(conduta.data), 'conduta.pdf');
        */
      }))["catch"](function (err) {
        return toastr['error']('Houve um erro ao imprimir.');
      });
    },
    imprimirDocumento: function imprimirDocumento(cdDocumento) {
      if ($("#sn_doc_header").is(':checked')) {
        var sn_header = 'S';
      } else {
        var sn_header = 'N';
      }

      if ($("#sn_doc_footer").is(':checked')) {
        var sn_footer = 'S';
      } else {
        var sn_footer = 'N';
      }

      if ($("#sn_doc_logo").is(':checked')) {
        var sn_logo = 'S';
      } else {
        var sn_logo = 'N';
      }

      if ($("#sn_doc_data").is(':checked')) {
        var sn_data = 'S';
      } else {
        var sn_data = 'N';
      }

      if ($("#sn_rec_especial").is(':checked')) {
        var sn_especial = 'S';
      } else {
        var sn_especial = 'N';
      }

      if ($("#sn_assinatura").is(':checked')) {
        var sn_assinatura = 'S';
      } else {
        var sn_assinatura = 'N';
      }

      axios.get("/rpclinica/consulta/anamnese/download-pdf/".concat(idAgendamento), {
        params: {
          tipo: 'documento',
          cdDocumento: cdDocumento,
          header: sn_header,
          logo: sn_logo,
          footer: sn_footer,
          data: sn_data,
          especial: sn_especial,
          assinatura: sn_assinatura
        },
        responseType: 'blob'
      }).then(function (res) {
        window.open(URL.createObjectURL(res.data), 'documento.pdf');
      })["catch"](function (err) {
        return toastr['error']('Houve um erro ao imprimir o documento!');
      });
    },
    getAnexos: function getAnexos() {
      var _this9 = this;

      this.loadingAnexos = true;
      axios.get("/rpclinica/json/agendamento-anexos/".concat(idAgendamento)).then(function (res) {
        _this9.anexos = res.data.anexos;
        _this9.assinatura_digital.situacao = res.data.doc_assinado; //this.assinatura_digital.conteudo=res.data.doc_conteudo;
      })["catch"](function (err) {
        return toastr['error']('Erro ao buscar os anexos!');
      })["finally"](function () {
        return _this9.loadingAnexos = false;
      });
    },
    excluirAnexos: function excluirAnexos(cdAnexo, indice) {
      var _this10 = this;

      this.swalWithBootstrapButtons.fire({
        title: 'Confirmação',
        html: "<h4 style='font-weight: 400;font-style: italic;'>Deseja excluir esse anexo?</h4>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22baa0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          _this10.indiceDeleteAnexo = indice;
          axios["delete"]("/rpclinica/json/agendamento-anexos/".concat(cdAnexo)).then(function (res) {
            toastr['success'](res.data.message);

            _this10.anexos.splice(indice, 1);
          })["catch"](function (err) {
            return toastr['error'](err.response.data.message);
          })["finally"](function () {
            return _this10.indiceDeleteAnexo = null;
          });
        }
      });
    },
    viewAnexo: function viewAnexo(anexo) {
      if (anexo.tipo == 'pdf') return;
      $('#modal-viewer-anexo h4').text(anexo.nome);
      $('#modal-viewer-anexo img').attr('src', anexo.url_arquivo);
      $('#modal-viewer-anexo').modal('toggle');
    },
    vip: function vip(status) {
      var _this11 = this;

      console.log(this.paciente.cd_paciente);
      axios.post("/rpclinica/json/paciente-vip", {
        status: status,
        paciente: this.paciente.cd_paciente
      }).then(function (res) {
        _this11.paciente = res.data;

        if (res.data.vip == 'S') {
          _this11.viewVip = true;
        } else {
          _this11.viewVip = false;
        }
      })["catch"](function (err) {
        return toastr['error']('Erro ao vincular Paciente como VIP!');
      });
    },
    exluirDocAssinado: function exluirDocAssinado() {
      var _this12 = this;

      this.swalWithBootstrapButtons.fire({
        title: 'Atenção!',
        html: "<h4 style='font-weight: 500;font-style: italic;'>Deseja excluir esse documento?</h4>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22baa0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: ' Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          axios["delete"]("/rpclinica/json/assinatura-delete/".concat(idAgendamento, "/anamnese/1 ")).then(function (res) {
            console.log(res);

            if (res.data.retorno == true) {
              _this12.assinatura_digital.situacao = false;
              var Title = "Sucesso!";
              var Html = "<h4 style='font-weight: 400;font-style: italic;'>Documento excluido com sucesso!</h4>";
              var Icon = "success";
            } else {
              var Title = "Atenção!";
              var Html = "<h4 style='font-weight: 400;font-style: italic;'>Erro ao excluir o documento!</h4>";
              var Icon = "error";
            }

            _this12.swalWithBootstrapButtons.fire({
              title: Title,
              html: Html,
              icon: Icon,
              showCancelButton: false,
              confirmButtonColor: '#22baa0',
              confirmButtonText: ' Ok '
            });
          })["catch"](function (err) {
            toastr['error'](err.response.data.message);
          });
        }
      });
    },
    assinarDoc: function assinarDoc(tipo, codDoc) {
      var _this13 = this;

      var DsHtml = "<h4 style='font-weight: 400;font-style: italic;'>Para assinar esse documento é necessario entrar com a senha do certificado!</h4> ";

      if (tipo == 'D') {
        DsHtml = "<h4 style='font-weight: 400;font-style: italic;'>Para assinar esse documento é necessario entrar com a senha do certificado!</h4> <label style='font-size: 1.3em; font-weight: 600;' > <input type='checkbox' id='AssRecEsp'  name='vehicle3' value='S'>   Receituário Especial?   </label> ";
      }

      this.swalWithBootstrapButtons.fire({
        title: 'Assinatura Digital',
        html: DsHtml,
        icon: 'warning',
        input: "password",
        showCancelButton: false,
        confirmButtonColor: '#22baa0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: '  <i class="fa fa-thumbs-up"></i> Assinar Documento'
      }).then(function (result) {
        if (result.isConfirmed) {
          if (!result.value) {
            _this13.swalWithBootstrapButtons.fire("Atenção!", "Senha não informada", "error");

            return false;
          }

          var RecEsp = null;
          var tp_doc = null;

          if (tipo == 'A') {
            tp_doc = 'anamnese';
            codDoc = null;
          }

          if (tipo == 'D') {
            tp_doc = 'documento';

            if ($('#AssRecEsp').is(":checked")) {
              RecEsp = 'S';
            } else {
              RecEsp = 'N';
            }
          }
          /*
          axios.post(`/rpclinica/json/assinatura-digital`,{ tipo: tp_doc, senha: result.value, agendamento: idAgendamento, codigo: codDoc, RecEsp : RecEsp })
          */


          axios.get("/rpclinica/consulta/anamnese/download-pdf/".concat(idAgendamento), {
            params: {
              tipo: tp_doc,
              cdDocumento: codDoc,
              header: 'N',
              logo: 'N',
              footer: 'N',
              data: 'N',
              especial: RecEsp,
              assinatura: 'N',
              assinar_digital: 'S',
              senha: result.value
            }
          }).then(function (res) {
            console.log(res);

            if (tipo == 'A') {
              _this13.assinatura_digital.situacao = res.data.sn_assinado;
              _this13.assinatura_digital.conteudo = res.data.conteudo;
            }

            if (res.data.retorno == true) {
              if (res.data.sn_assinado == true) {
                var Title = "Sucesso!";
                var Html = "<h4 style='font-weight: 400;font-style: italic;'>Documento assinado com sucesso!</h4>";
                var Icon = "success";
              } else {
                var Title = "Atenção!";
                var Html = "<h4 style='font-weight: 400;font-style: italic;'>Erro ao assinar o documento!</h4>";
                var Icon = "error";
              }

              if (tipo == 'D') {
                _this13.documentos = res.data.documentos;
              }
            } else {
              var Title = "Atenção!";
              var Html = "<h4 style='font-weight: 400;font-style: italic;'>Erro ao salvar informação!</h4>";
              var Icon = "error";
              toastr['error'](res.data.msg);
            }

            _this13.swalWithBootstrapButtons.fire({
              title: Title,
              html: Html,
              icon: Icon,
              showCancelButton: false,
              confirmButtonColor: '#22baa0',
              confirmButtonText: ' Ok '
            });
          })["catch"](function (err) {
            toastr['error'](err.response.data.message);
          }); //.finally(() => this.indiceDeleteAnexo = null);
        }
      });
    },
    historicoDoc: function historicoDoc() {
      var _this14 = this;

      //alert(idAgendamento);
      axios.get("/rpclinica/json/historico-documento?agendamento=" + idAgendamento).then(function (res) {
        console.log(res);
        _this14.historicoPaciente = res.data.retorno;
      })["catch"](function (err) {
        toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this14.indiceDeleteAnexo = null;
      });
      $('#historico-documentos').modal('toggle');
    },
    importarDoc: function importarDoc(doc) {
      this.cdDocumentoEdicao = null;
      $('#tabDocumentos select#formularios-documentos').val(doc.cd_formulario).trigger('change');
      $('#editor-formularios-documentos').code(doc.conteudo);
      this.editorDocumentos.setData(doc.conteudo);
      $('#historico-documentos').modal('hide');
    }
  };
});
$(document).ready(function () {
  $('#cid-triagem').select2({
    ajax: {
      url: '/rpclinica/json/select2/cid',
      dataType: 'json',
      processResults: function processResults(data) {
        return {
          results: data,
          pagination: {
            more: true
          }
        };
      }
    }
  });
  $('#modal-viewer-anexo').modal({
    backdrop: 'static',
    show: false
  });
});
/******/ })()
;