/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************!*\
  !*** ./resources/js/rpclinica/motivo.js ***!
  \******************************************/
Alpine.data('app', function () {
  return {
    editionMotivo: null,
    loadingModal: false,
    inputMotivo: null,
    motivos: [],
    init: function init() {
      var _this = this;

      this.motivos = motivos;
      $('#modal-form-motivo').on('hidden.bs.modal', function () {
        _this.inputMotivo = null;
        _this.editionMotivo = null;
      });
    },
    openModalMotivo: function openModalMotivo() {
      var motivo = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

      if (motivo) {
        this.editionMotivo = motivo.cd_motivo;
        this.inputMotivo = motivo.motivo;
      }

      $('#modal-form-motivo').modal('show');
    },
    saveMotivo: function saveMotivo() {
      var _this2 = this;

      this.loadingModal = true;

      if (this.editionMotivo) {
        axios.put('/rpclinica/json/motivos', {
          cd_motivo: this.editionMotivo,
          motivo: this.inputMotivo
        }).then(function (res) {
          var indiceMotivo = _this2.motivos.findIndex(function (motivo) {
            return motivo.cd_motivo == _this2.editionMotivo;
          });

          _this2.motivos[indiceMotivo] = res.data.motivo;
          toastr['success'](res.data.message);
          $('#modal-form-motivo').modal('hide');
        })["catch"](function (err) {
          return toastr['error'](err.response.data.message);
        })["finally"](function () {
          return _this2.loadingModal = false;
        });
        return;
      }

      axios.post('/rpclinica/json/motivos', {
        motivo: this.inputMotivo
      }).then(function (res) {
        _this2.motivos.push(res.data.motivo);

        toastr['success'](res.data.message);
        $('#modal-form-motivo').modal('hide');
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this2.loadingModal = false;
      });
    }
  };
});
$(document).ready(function () {
  $('#modal-form-motivo').modal({
    backdrop: 'static',
    show: false
  });
});
/******/ })()
;