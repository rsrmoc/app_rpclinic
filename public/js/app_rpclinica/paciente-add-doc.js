/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************************!*\
  !*** ./resources/js/app_rpclinica/paciente-add-doc.js ***!
  \********************************************************/
Alpine.data('appPacienteDoc', function () {
  return {
    loading: false,
    dataDoc: {
      cd_pac: cdPaciente,
      cd_formulario: null,
      conteudo: null
    },
    editor: null,
    init: function init() {
      var _this = this;

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

        _this.editor.setData((_formularios$find$con = (_formularios$find = formularios.find(function (formulario) {
          return formulario.cd_formulario == _this.dataDoc.cd_formulario;
        })) === null || _formularios$find === void 0 ? void 0 : _formularios$find.conteudo_atual) !== null && _formularios$find$con !== void 0 ? _formularios$find$con : '');
      });
    },
    saveDoc: function saveDoc() {
      var _formularios$find2,
          _this2 = this;

      this.loading = true;
      var data = Object.assign({}, this.dataDoc);
      data.conteudo = this.editor.getData();
      data.nm_formulario = (_formularios$find2 = formularios.find(function (formulario) {
        return formulario.cd_formulario == data.cd_formulario;
      })) === null || _formularios$find2 === void 0 ? void 0 : _formularios$find2.nm_formulario;
      axios.post('/app_rpclinic/api/paciente-add-doc', data).then(function (res) {
        toastr.success(res.data.message);
        _this2.dataDoc.cd_formulario = null;
        _this2.dataDoc.conteudo = null;

        _this2.editor.setData('');
      })["catch"](function (err) {
        return parseErrorsAPI(err);
      })["finally"](function () {
        return _this2.loading = false;
      });
    }
  };
});
/******/ })()
;