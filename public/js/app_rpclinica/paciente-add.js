/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************************!*\
  !*** ./resources/js/app_rpclinica/paciente-add.js ***!
  \****************************************************/
Alpine.data('appPacienteAdd', function () {
  return {
    loading: false,
    pacienteData: {
      nm_paciente: null,
      dt_nasc: null,
      nm_mae: null,
      nm_pai: null,
      sexo: null,
      cpf: null,
      rg: null
    },
    createPaciente: function createPaciente() {
      var _this = this;

      this.loading = true;
      axios.post(routePacienteAdd, this.pacienteData).then(function (res) {
        toastr.success(res.data.message);
        _this.pacienteData = {
          nm_paciente: null,
          dt_nasc: null,
          nm_mae: null,
          nm_pai: null,
          sexo: null,
          cpf: null,
          rg: null
        };
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