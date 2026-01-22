/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************************!*\
  !*** ./resources/js/app_rpclinica/paciente-add-doc.js ***!
  \********************************************************/
Alpine.data('appPacienteDoc', function () {
  // Ler dados do DOM injetados pelo Blade
  var formulariosEl = document.getElementById('data-formularios');
  var formularios = formulariosEl ? JSON.parse(formulariosEl.textContent) : [];
  var cdPacienteEl = document.getElementById('data-cd-paciente');
  var cdPaciente = cdPacienteEl ? cdPacienteEl.value : null;
  var routeEl = document.getElementById('data-route-add-doc');
  var routePacienteAddDoc = routeEl ? routeEl.value : '';
  return {
    loading: false,
    modeloDocumentoQuery: '',
    dataDoc: {
      cd_pac: cdPaciente,
      cd_formulario: null,
      conteudo: null
    },
    editor: null,

    get selectedModeloDocumentoLabel() {
      var _this = this,
          _selected$nm_formular;

      var selected = formularios.find(function (formulario) {
        return formulario.cd_formulario == _this.dataDoc.cd_formulario;
      });
      return (_selected$nm_formular = selected === null || selected === void 0 ? void 0 : selected.nm_formulario) !== null && _selected$nm_formular !== void 0 ? _selected$nm_formular : 'Selecione';
    },

    get modeloDocumentoFiltrado() {
      var _this$modeloDocumento;

      var query = ((_this$modeloDocumento = this.modeloDocumentoQuery) !== null && _this$modeloDocumento !== void 0 ? _this$modeloDocumento : '').toString().trim().toLowerCase();
      if (!query) return formularios;
      return formularios.filter(function (formulario) {
        var _formulario$nm_formul;

        return ((_formulario$nm_formul = formulario.nm_formulario) !== null && _formulario$nm_formul !== void 0 ? _formulario$nm_formul : '').toString().toLowerCase().includes(query);
      });
    },

    init: function init() {
      var _this2 = this;

      this.editor = CKEDITOR.replace('editor', {
        toolbar: [{
          name: 'clipboard',
          items: ['Undo', 'Redo']
        }, {
          name: 'basicstyles',
          items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat']
        }, {
          name: 'paragraph',
          items: ['NumberedList', 'BulletedList']
        }, {
          name: 'colors',
          items: ['TextColor', 'BGColor']
        }, {
          name: 'tools',
          items: ['Maximize']
        }]
      });
      this.$watch('dataDoc.cd_formulario', function () {
        var _formularios$find$con, _formularios$find;

        _this2.editor.setData((_formularios$find$con = (_formularios$find = formularios.find(function (formulario) {
          return formulario.cd_formulario == _this2.dataDoc.cd_formulario;
        })) === null || _formularios$find === void 0 ? void 0 : _formularios$find.conteudo_atual) !== null && _formularios$find$con !== void 0 ? _formularios$find$con : '');
      });
    },
    openModeloDocumentoPicker: function openModeloDocumentoPicker() {
      var _bootstrap,
          _this3 = this;

      var modalEl = document.getElementById('modalModeloDocumento');
      if (!modalEl || typeof bootstrap === 'undefined' || !((_bootstrap = bootstrap) !== null && _bootstrap !== void 0 && _bootstrap.Modal)) return;
      var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
      setTimeout(function () {
        var _this3$$refs;

        if ((_this3$$refs = _this3.$refs) !== null && _this3$$refs !== void 0 && _this3$$refs.modeloDocumentoSearch) {
          _this3.$refs.modeloDocumentoSearch.focus();
        }
      }, 150);
    },
    selectModeloDocumento: function selectModeloDocumento(cdFormulario) {
      var _bootstrap2;

      this.dataDoc.cd_formulario = cdFormulario;
      this.modeloDocumentoQuery = '';
      var modalEl = document.getElementById('modalModeloDocumento');
      if (!modalEl || typeof bootstrap === 'undefined' || !((_bootstrap2 = bootstrap) !== null && _bootstrap2 !== void 0 && _bootstrap2.Modal)) return;
      var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.hide();
    },
    saveDoc: function saveDoc() {
      var _formularios$find2,
          _this4 = this;

      if (!this.dataDoc.cd_formulario) {
        toastr.error('Selecione um modelo de documento.');
        return;
      }

      this.loading = true;
      var data = Object.assign({}, this.dataDoc);
      data.conteudo = this.editor.getData();
      data.nm_formulario = (_formularios$find2 = formularios.find(function (formulario) {
        return formulario.cd_formulario == data.cd_formulario;
      })) === null || _formularios$find2 === void 0 ? void 0 : _formularios$find2.nm_formulario;
      axios.post(routePacienteAddDoc, data).then(function (res) {
        toastr.success(res.data.message);
        _this4.dataDoc.cd_formulario = null;
        _this4.dataDoc.conteudo = null;

        _this4.editor.setData('');
      })["catch"](function (err) {
        return parseErrorsAPI(err);
      })["finally"](function () {
        return _this4.loading = false;
      });
    }
  };
});
/******/ })()
;