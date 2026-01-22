/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************!*\
  !*** ./resources/js/rpclinica/usuarios.js ***!
  \********************************************/
Alpine.data('app', function () {
  return {
    loadingUsuario: false,
    inputsUsuario: {
      email: null,
      nome: null,
      perfil: null,
      admin: 'N',
      ativo: 'S',
      celular: null,
      empresa: null,
      senha: null,
      resetar_senha: false,
      enviar_email: false,
      profissional: null,
      sn_todos_agendamentos: false
    },
    isUsuario: null,
    init: function init() {
      var _this = this;

      $('#perfil-usuario').on('select2:select', function (evt) {
        return _this.inputsUsuario.perfil = evt.params.data.id;
      });
      $('#admin-usuario').on('select2:select', function (evt) {
        return _this.inputsUsuario.admin = evt.params.data.id;
      });
      $('#ativo-usuario').on('select2:select', function (evt) {
        return _this.inputsUsuario.ativo = evt.params.data.id;
      });
      $('#ativo-profissional').on('select2:select', function (evt) {
        return _this.inputsProfissional.ativo = evt.params.data.id;
      });
      $('#profissional-procedimento').on('select2:select', function (evt) {
        return _this.inputsProcedimento.cd_procedimento = evt.params.data.id;
      });
      $('#profissional-convenio').on('select2:select', function (evt) {
        return _this.inputsProcedimento.cd_convenio = evt.params.data.id;
      });
      $('#profissional-especialidade').on('select2:select', function (evt) {
        return _this.inputEspecialidade = evt.params.data.id;
      });
      this.setUser(usuario);
    },
    setUser: function setUser(usuario) {},
    submitSave: function submitSave() {
      $('#formUsuario input[type="submit"]').click();
    },
    submitSaveUsuario: function submitSaveUsuario() {
      var _this2 = this;

      this.inputsUsuario.empresa = $('#empresa-usuario').val();
      this.inputsUsuario.profissional = $('#prof-usuario').val();
      this.inputsUsuario.ativo = $('#ativo-usuario').val();
      this.inputsUsuario.perfil = $('#perfil-usuario').val();
      var data = Object.assign({}, this.inputsUsuario);
      if (this.inputsUsuario.sn_profissional) data.profissional = Object.assign({}, this.inputsProfissional);
      this.loadingUsuario = true;

      if (this.isUsuario) {
        axios.post("/rpclinica/json/usuario-update/".concat(usuario.cd_usuario), data).then(function (res) {
          return toastr['success'](res.data.message);
        })["catch"](function (err) {
          return toastr['error'](err.response.data.message);
        })["finally"](function () {
          return _this2.loadingUsuario = false;
        });
        return;
      }

      axios.post("/rpclinica/json/usuario-store", data).then(function (res) {
        toastr['success'](res.data.message);
        console.log(res.data);
        setTimeout(function () {
          location.href = '/rpclinica/usuario-listar';
        }, 100000);
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this2.loadingUsuario = false;
      });
    },
    // medotos procedimentos
    submitAddProcedimento: function submitAddProcedimento() {
      var _usuario,
          _this3 = this;

      if (this.isUsuario && (_usuario = usuario) !== null && _usuario !== void 0 && _usuario.profissional) {
        var data = Object.assign({}, this.inputsProcedimento);
        data.cd_profissional = usuario.cd_profissional;
        axios.post("/rpclinica/json/profissional-store-procedimento", data).then(function (res) {
          data.cd_proc_prof = res.data.procedimento.cd_proc_prof;

          _this3.inputsProfissional.procedimentos.push(data);

          toastr['success'](res.data.message);

          _this3.clearFormProcedimentos();
        })["catch"](function (err) {
          return toastr['error'](err.response.data.message);
        });
        return;
      }

      this.inputsProfissional.procedimentos.push(Object.assign({}, this.inputsProcedimento));
      this.clearFormProcedimentos();
    },
    deleteProcedimento: function deleteProcedimento(indice) {
      var _this4 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Deseja excluir esse procedimento?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          if (_this4.inputsProfissional.procedimentos[indice].cd_proc_prof) {
            axios["delete"]("/rpclinica/json/profissional-delete-procedimento/".concat(_this4.inputsProfissional.procedimentos[indice].cd_proc_prof)).then(function (res) {
              _this4.inputsProfissional.procedimentos.splice(indice, 1);

              toastr['success']('Procedimento excluido com sucesso!');
            })["catch"](function (err) {
              return toastr['error'](err.response.data.message);
            });
            return;
          }

          _this4.inputsProfissional.procedimentos.splice(indice, 1);
        }
      });
    },
    clearFormProcedimentos: function clearFormProcedimentos() {
      this.inputsProcedimento = {
        cd_procedimento: null,
        cd_convenio: null,
        valor: null,
        repasse: null
      };
      $('#profissional-procedimento').val(null).trigger('change');
      $('#profissional-convenio').val(null).trigger('change');
    },
    // metodos especialidades
    submitAddEspecialidade: function submitAddEspecialidade() {
      var _usuario2,
          _this5 = this;

      if (this.isUsuario && (_usuario2 = usuario) !== null && _usuario2 !== void 0 && _usuario2.profissional) {
        var data = {
          cd_especialidade: this.inputEspecialidade,
          cd_profissional: usuario.cd_profissional
        };
        axios.post("/rpclinica/json/profissional-store-especialidade", data).then(function (res) {
          data.cd_prof_espec = res.data.especialidade.cd_prof_espec;

          _this5.inputsProfissional.especialidades.push(data);

          toastr['success'](res.data.message);
          $('#profissional-especialidade').val(null).trigger('change');
        })["catch"](function (err) {
          return toastr['error'](err.response.data.message);
        });
        return;
      }

      this.inputsProfissional.especialidades.push({
        cd_especialidade: this.inputEspecialidade
      });
      $('#profissional-especialidade').val(null).trigger('change');
    },
    deleteEspecialidade: function deleteEspecialidade(indice) {
      var _this6 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Deseja excluir esse especialidade?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          if (_this6.inputsProfissional.especialidades[indice].cd_prof_espec) {
            axios["delete"]("/rpclinica/json/profissional-delete-especialidade/".concat(_this6.inputsProfissional.especialidades[indice].cd_prof_espec)).then(function (res) {
              _this6.inputsProfissional.especialidades.splice(indice, 1);

              toastr['success']('Especialidade excluida com sucesso!');
            })["catch"](function (err) {
              return toastr['error'](err.response.data.message);
            });
            return;
          }

          _this6.inputsProfissional.especialidades.splice(indice, 1);
        }
      });
    }
  };
});
/******/ })()
;