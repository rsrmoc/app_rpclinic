/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************!*\
  !*** ./resources/js/rpclinica/convenio.js ***!
  \********************************************/
Alpine.data('app', function () {
  return {
    formProcedimento: {
      codigo: null,
      cd_procedimento: null,
      cod_procedimento: null,
      nm_procedimento: null,
      data: null,
      valor: null
    },
    procedimentos: [],
    entradasProcedimentos: [],
    deletingEntradaProcedimento: false,
    init: function init() {
      var _this = this;

      this.procedimentos = procedimentos;
      $('#procedimento').on('select2:select', function (evt) {
        //this.formProcedimento.cd_procedimento = evt.params.data.id;
        _this.formProcedimento.codigo = evt.params.data.id;
        var el = evt.params.data.element;
        _this.formProcedimento.cod_procedimento = el.dataset.codigo;
        _this.formProcedimento.cd_procedimento = el.dataset.codigo;
        _this.formProcedimento.nm_procedimento = el.dataset.nome;
      });

      if (typeof entradasProcedimentos !== 'undefined') {
        this.entradasProcedimentos = entradasProcedimentos;
      }

      console.log(this.entradasProcedimentos);
    },
    submitConvenio: function submitConvenio() {
      document.querySelector('#formConvenio button[type=submit]').click();
    },
    limpar: function limpar() {
      document.querySelector('#formConvenio').reset();
      this.clearFormProcedimento();
    },
    clearFormProcedimento: function clearFormProcedimento() {
      this.formProcedimento = {
        cd_procedimento: null,
        dt_vigencia: null,
        valor: null
      };
      $('#procedimento').val(null).trigger('change');
    },
    addEntradaProcedimento: function addEntradaProcedimento() {
      this.entradasProcedimentos.push(Object.assign({}, this.formProcedimento));
      this.clearFormProcedimento();
      console.log(this.entradasProcedimentos);
    },
    nomeProcedimento: function nomeProcedimento(cdProcedimento) {
      var _this$procedimentos$f;

      return (_this$procedimentos$f = this.procedimentos.find(function (procedimento) {
        return cdProcedimento == procedimento.cod_proc;
      })) === null || _this$procedimentos$f === void 0 ? void 0 : _this$procedimentos$f.nm_proc;
    },
    codProc: function codProc(cdProcedimento) {
      var _this$procedimentos$f2;

      return (_this$procedimentos$f2 = this.procedimentos.find(function (procedimento) {
        return cdProcedimento == procedimento.cd_proc;
      })) === null || _this$procedimentos$f2 === void 0 ? void 0 : _this$procedimentos$f2.cod_proc;
    },
    deleteRepasse: function deleteRepasse(codigo) {
      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir esse repasse?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22baa0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        location.href = "/rpclinica/convenio-delete-repasse/".concat(codigo);
      });
    },
    excluirEntradaProcedimento: function excluirEntradaProcedimento(indice) {
      var _this2 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir esse procedimento?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          if (_this2.entradasProcedimentos[indice].cd_procedimento_convenio) {
            _this2.deletingEntradaProcedimento = true;
            axios.post("/rpclinica/procedimento-convenio-delete/".concat(_this2.entradasProcedimentos[indice].cd_procedimento_convenio)).then(function () {
              _this2.entradasProcedimentos.splice(indice, 1);

              toastr['success']('Procedimento excluído com sucesso!');
            })["catch"](function (err) {
              return toastr['error']('Houve um error ao excluir o procedimento.');
            })["finally"](function () {
              return _this2.deletingEntradaProcedimento = false;
            });
            return;
          }

          _this2.entradasProcedimentos.splice(indice, 1);
        }
      });
    },
    formatValor: function formatValor(valor) {
      return Intl.NumberFormat('pt-br', {
        style: 'currency',
        currency: 'BRL'
      }).format(valor);
    }
  };
});
/******/ })()
;