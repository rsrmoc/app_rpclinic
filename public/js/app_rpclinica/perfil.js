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
        return toastr.success(res.data.message);
      })["catch"](function (err) {
        return toastr.success(err.response.data.message);
      })["finally"](function () {
        return _this.loading = false;
      });
    }
  };
});
/******/ })()
;