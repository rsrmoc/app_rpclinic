/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************************!*\
  !*** ./resources/js/app_rpclinica/perfil.js ***!
  \**********************************************/
Alpine.data('appPerfil', function () {
  return {
    loading: false,
    saveProfile: function saveProfile() {
      var _this = this;

      this.loading = true;
      var form = new FormData(document.querySelector('#formProfile'));
      axios.post(routePerfilUpdate, form).then(function (res) {
        toastr.success(res.data.message, 'Sucesso', {
          timeOut: 7000,
          closeButton: true,
          progressBar: true,
          positionClass: "toast-top-center",
          showMethod: "slideDown",
          hideMethod: "slideUp"
        });
      })["catch"](function (err) {
        toastr.error(err.response.data.message, 'Erro', {
          timeOut: 7000,
          closeButton: true,
          progressBar: true,
          positionClass: "toast-top-center",
          showMethod: "slideDown",
          hideMethod: "slideUp"
        });
      })["finally"](function () {
        return _this.loading = false;
      });
    }
  };
});
/******/ })()
;