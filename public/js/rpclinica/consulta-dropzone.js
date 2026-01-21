/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************************!*\
  !*** ./resources/js/rpclinica/consulta-dropzone.js ***!
  \*****************************************************/
Dropzone.options.dzAnexos = {
  paramName: 'files',
  uploadMultiple: true,
  acceptedFiles: 'image/*,application/pdf',
  autoProcessQueue: false,
  addRemoveLinks: true,
  init: function init() {
    var _this = this;

    $('#dz-anexos-submit').on('click', function () {
      return _this.processQueue();
    });
    this.on('addedfile', function () {
      if ($('#dz-anexos-submit').is(':disabled')) {
        $('#dz-anexos-submit').prop('disabled', false);
      }
    });
    this.on('removedfile', function () {
      if (_this.files.length == 0 && !$('#dz-anexos-submit').is(':disabled')) {
        $('#dz-anexos-submit').prop('disabled', true);
      }
    });
    this.on('successmultiple', function () {
      _this.removeAllFiles(true);

      window.postMessage('added-anexos');
    });
    this.on('error', function (file) {
      toastr['error']('Houve um erro ao enviar os arquivos!');
    });
  }
};
/******/ })()
;