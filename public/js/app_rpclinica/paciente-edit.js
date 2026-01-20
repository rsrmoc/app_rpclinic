/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************************!*\
  !*** ./resources/js/app_rpclinica/paciente-edit.js ***!
  \*****************************************************/
Alpine.data('appPacienteEdit', function () {
  return {
    loading: false,
    savePaciente: function savePaciente() {
      var _this = this;

      this.loading = true;
      var form = new FormData(document.querySelector('#formPaciente'));
      axios.post("/app_rpclinic/api/paciente-edit/".concat(cdPaciente), form).then(function (res) {
        toastr.success(res.data.message);
      })["catch"](function (err) {
        return parseErrorsAPI(err);
      })["finally"](function () {
        return _this.loading = false;
      });
    }
  };
});
/******/ })()
;