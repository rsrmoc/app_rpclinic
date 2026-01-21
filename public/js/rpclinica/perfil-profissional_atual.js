/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************************************************!*\
  !*** ./resources/js/rpclinica/perfil-profissional_atual.js ***!
  \*************************************************************/
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return exports; }; var exports = {}, Op = Object.prototype, hasOwn = Op.hasOwnProperty, $Symbol = "function" == typeof Symbol ? Symbol : {}, iteratorSymbol = $Symbol.iterator || "@@iterator", asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator", toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag"; function define(obj, key, value) { return Object.defineProperty(obj, key, { value: value, enumerable: !0, configurable: !0, writable: !0 }), obj[key]; } try { define({}, ""); } catch (err) { define = function define(obj, key, value) { return obj[key] = value; }; } function wrap(innerFn, outerFn, self, tryLocsList) { var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator, generator = Object.create(protoGenerator.prototype), context = new Context(tryLocsList || []); return generator._invoke = function (innerFn, self, context) { var state = "suspendedStart"; return function (method, arg) { if ("executing" === state) throw new Error("Generator is already running"); if ("completed" === state) { if ("throw" === method) throw arg; return doneResult(); } for (context.method = method, context.arg = arg;;) { var delegate = context.delegate; if (delegate) { var delegateResult = maybeInvokeDelegate(delegate, context); if (delegateResult) { if (delegateResult === ContinueSentinel) continue; return delegateResult; } } if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) { if ("suspendedStart" === state) throw state = "completed", context.arg; context.dispatchException(context.arg); } else "return" === context.method && context.abrupt("return", context.arg); state = "executing"; var record = tryCatch(innerFn, self, context); if ("normal" === record.type) { if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue; return { value: record.arg, done: context.done }; } "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg); } }; }(innerFn, self, context), generator; } function tryCatch(fn, obj, arg) { try { return { type: "normal", arg: fn.call(obj, arg) }; } catch (err) { return { type: "throw", arg: err }; } } exports.wrap = wrap; var ContinueSentinel = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var IteratorPrototype = {}; define(IteratorPrototype, iteratorSymbol, function () { return this; }); var getProto = Object.getPrototypeOf, NativeIteratorPrototype = getProto && getProto(getProto(values([]))); NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype); var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype); function defineIteratorMethods(prototype) { ["next", "throw", "return"].forEach(function (method) { define(prototype, method, function (arg) { return this._invoke(method, arg); }); }); } function AsyncIterator(generator, PromiseImpl) { function invoke(method, arg, resolve, reject) { var record = tryCatch(generator[method], generator, arg); if ("throw" !== record.type) { var result = record.arg, value = result.value; return value && "object" == _typeof(value) && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) { invoke("next", value, resolve, reject); }, function (err) { invoke("throw", err, resolve, reject); }) : PromiseImpl.resolve(value).then(function (unwrapped) { result.value = unwrapped, resolve(result); }, function (error) { return invoke("throw", error, resolve, reject); }); } reject(record.arg); } var previousPromise; this._invoke = function (method, arg) { function callInvokeWithMethodAndArg() { return new PromiseImpl(function (resolve, reject) { invoke(method, arg, resolve, reject); }); } return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); }; } function maybeInvokeDelegate(delegate, context) { var method = delegate.iterator[context.method]; if (undefined === method) { if (context.delegate = null, "throw" === context.method) { if (delegate.iterator["return"] && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method)) return ContinueSentinel; context.method = "throw", context.arg = new TypeError("The iterator does not provide a 'throw' method"); } return ContinueSentinel; } var record = tryCatch(method, delegate.iterator, context.arg); if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel; var info = record.arg; return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel); } function pushTryEntry(locs) { var entry = { tryLoc: locs[0] }; 1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry); } function resetTryEntry(entry) { var record = entry.completion || {}; record.type = "normal", delete record.arg, entry.completion = record; } function Context(tryLocsList) { this.tryEntries = [{ tryLoc: "root" }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0); } function values(iterable) { if (iterable) { var iteratorMethod = iterable[iteratorSymbol]; if (iteratorMethod) return iteratorMethod.call(iterable); if ("function" == typeof iterable.next) return iterable; if (!isNaN(iterable.length)) { var i = -1, next = function next() { for (; ++i < iterable.length;) { if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next; } return next.value = undefined, next.done = !0, next; }; return next.next = next; } } return { next: doneResult }; } function doneResult() { return { value: undefined, done: !0 }; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, define(Gp, "constructor", GeneratorFunctionPrototype), define(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) { var ctor = "function" == typeof genFun && genFun.constructor; return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name)); }, exports.mark = function (genFun) { return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun; }, exports.awrap = function (arg) { return { __await: arg }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () { return this; }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) { void 0 === PromiseImpl && (PromiseImpl = Promise); var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl); return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) { return result.done ? result.value : iter.next(); }); }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () { return this; }), define(Gp, "toString", function () { return "[object Generator]"; }), exports.keys = function (object) { var keys = []; for (var key in object) { keys.push(key); } return keys.reverse(), function next() { for (; keys.length;) { var key = keys.pop(); if (key in object) return next.value = key, next.done = !1, next; } return next.done = !0, next; }; }, exports.values = values, Context.prototype = { constructor: Context, reset: function reset(skipTempReset) { if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) { "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined); } }, stop: function stop() { this.done = !0; var rootRecord = this.tryEntries[0].completion; if ("throw" === rootRecord.type) throw rootRecord.arg; return this.rval; }, dispatchException: function dispatchException(exception) { if (this.done) throw exception; var context = this; function handle(loc, caught) { return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught; } for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i], record = entry.completion; if ("root" === entry.tryLoc) return handle("end"); if (entry.tryLoc <= this.prev) { var hasCatch = hasOwn.call(entry, "catchLoc"), hasFinally = hasOwn.call(entry, "finallyLoc"); if (hasCatch && hasFinally) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } else if (hasCatch) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); } else { if (!hasFinally) throw new Error("try statement without catch or finally"); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } } } }, abrupt: function abrupt(type, arg) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) { var finallyEntry = entry; break; } } finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null); var record = finallyEntry ? finallyEntry.completion : {}; return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record); }, complete: function complete(record, afterLoc) { if ("throw" === record.type) throw record.arg; return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel; }, finish: function finish(finallyLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel; } }, "catch": function _catch(tryLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc === tryLoc) { var record = entry.completion; if ("throw" === record.type) { var thrown = record.arg; resetTryEntry(entry); } return thrown; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(iterable, resultName, nextLoc) { return this.delegate = { iterator: values(iterable), resultName: resultName, nextLoc: nextLoc }, "next" === this.method && (this.arg = undefined), ContinueSentinel; } }, exports; }

function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

Alpine.data('appConfiguracao', function () {
  return {
    loadingConfig: false,
    cdProfissional: cdProfissional,
    tipoDoc: 'DOC',
    deleteAssinatura: function deleteAssinatura() {
      var _this = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir essa assinatura?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          axios["delete"]("/rpclinica/profissional-assinatura/".concat(_this.cdProfissional)).then(function (res) {
            $("#img_assinatura").remove();
            toastr["success"]('Formulario excluido com sucesso!');
          })["catch"](function (err) {
            return toastr["error"]('Não foi possivel excluir a imagem!');
          });
        }
      });
    }
  };
});
Alpine.data('appFormulario', function () {
  return {
    inputsFormulario: {
      nome: null,
      tipo_formulario: null,
      especialidade: null,
      conteudo: null,
      exame: null,
      hipotese: null,
      conduta: null
    },
    loadingSubmitFormulario: false,
    tiposFormulario: {
      ATE: 'Atendimentos/Anammnese',
      CON: 'Conduta',
      DOC: 'Documentos',
      EXA: 'Exame Fisico',
      RIP: 'Hipótese Diagnóstica'
    },
    formularios: [],
    indiceFormularioDelete: null,
    documentosPadrao: [],
    tipo_formulario: 'DOC',
    editor: null,
    editorExame: null,
    editorHipotese: null,
    editorConduta: null,
    cdProfissional: cdProfissional,
    init: function init() {
      var _this2 = this;

      this.editor = CKEDITOR.replace('conteudo_formulario', {
        toolbarGroups: [{
          "name": "basicstyles",
          "groups": ["basicstyles"]
        }, {
          "name": "undo",
          "groups": ["Undo", "Redo"]
        }, {
          "name": "paragraph",
          "groups": ["list", "blocks"]
        }, {
          "name": "insert",
          "groups": ["insert"]
        }, {
          "name": "styles",
          "groups": ["styles"]
        }],
        // Remove the redundant buttons from toolbar groups defined above.
        removeButtons: 'Subscript,Superscript,',
        resize_enabled: false,
        removePlugins: 'elementspath',
        height: ['300px'],
        enterMode: CKEDITOR.ENTER_BR,
        // Define Enter como <br>
        shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>

      });
      this.editorExame = CKEDITOR.replace('conteudo_exame', {
        toolbarGroups: [{
          "name": "basicstyles",
          "groups": ["basicstyles"]
        }, {
          "name": "undo",
          "groups": ["Undo", "Redo"]
        }, {
          "name": "paragraph",
          "groups": ["list", "blocks"]
        }, {
          "name": "insert",
          "groups": ["insert"]
        }, {
          "name": "styles",
          "groups": ["styles"]
        }],
        // Remove the redundant buttons from toolbar groups defined above.
        removeButtons: 'Subscript,Superscript,',
        resize_enabled: false,
        removePlugins: 'elementspath',
        height: ['200px'],
        enterMode: CKEDITOR.ENTER_BR,
        // Define Enter como <br>
        shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>

      });
      this.editorHipotese = CKEDITOR.replace('conteudo_hipotese', {
        toolbarGroups: [{
          "name": "basicstyles",
          "groups": ["basicstyles"]
        }, {
          "name": "undo",
          "groups": ["Undo", "Redo"]
        }, {
          "name": "paragraph",
          "groups": ["list", "blocks"]
        }, {
          "name": "insert",
          "groups": ["insert"]
        }, {
          "name": "styles",
          "groups": ["styles"]
        }],
        // Remove the redundant buttons from toolbar groups defined above.
        removeButtons: 'Subscript,Superscript,',
        resize_enabled: false,
        removePlugins: 'elementspath',
        height: ['200px'],
        enterMode: CKEDITOR.ENTER_BR,
        // Define Enter como <br>
        shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>

      });
      this.editorConduta = CKEDITOR.replace('conteudo_conduta', {
        toolbarGroups: [{
          "name": "basicstyles",
          "groups": ["basicstyles"]
        }, {
          "name": "undo",
          "groups": ["Undo", "Redo"]
        }, {
          "name": "paragraph",
          "groups": ["list", "blocks"]
        }, {
          "name": "insert",
          "groups": ["insert"]
        }, {
          "name": "styles",
          "groups": ["styles"]
        }],
        // Remove the redundant buttons from toolbar groups defined above.
        removeButtons: 'Subscript,Superscript,',
        resize_enabled: false,
        removePlugins: 'elementspath',
        height: ['200px'],
        enterMode: CKEDITOR.ENTER_BR,
        // Define Enter como <br>
        shiftEnterMode: CKEDITOR.ENTER_BR // Define Shift+Enter como <br>

      });
      this.formularios = formularios;
      this.documentosPadrao = documentosPadrao;
      $('#tipo_formulario').on('select2:select', function (evt) {
        _this2.inputsFormulario.tipo_formulario = evt.params.data.id;
        _this2.tipo_formulario = evt.params.data.id;
      });
      $('#especialidade-formulario').on('select2:select', function (evt) {
        _this2.inputsFormulario.especialidade = evt.params.data.id;
      });
    },
    deleteAssinatura: function deleteAssinatura() {
      var _this3 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir essa assinatura?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          location.href = "https://www.w3schools.com";
          axios["delete"]("/rpclinica/profissional-assinatura/".concat(_this3.cdProfissional)).then(function (res) {
            $("#img_assinatura").remove();
            toastr["success"]('Formulario excluido com sucesso!');
          })["catch"](function (err) {
            return toastr["error"]('Não foi possivel excluir a imagem!');
          });
        }
      });
    },
    clearFormulario: function clearFormulario() {
      var _this$editor;

      this.inputsFormulario = {
        nome: null,
        tipo_formulario: null,
        especialidade: null,
        conteudo: null,
        sn_header: null
      };
      $('#tipo_formulario').val(null).trigger('change');
      $('#especialidade-formulario').val(null).trigger('change');
      (_this$editor = this.editor) === null || _this$editor === void 0 ? void 0 : _this$editor.setData(null);
    },
    setEditFormulario: function setEditFormulario(formulario) {
      var _this$editor2, _this$editorExame, _this$editorHipotese, _this$editorConduta;

      this.inputsFormulario.cd_formulario = formulario.cd_formulario;
      this.inputsFormulario.nome = formulario.nm_formulario;
      $('#tipo_formulario').val(formulario.tp_formulario).trigger('change');
      this.inputsFormulario.tipo_formulario = formulario.tp_formulario;
      this.tipo_formulario = formulario.tp_formulario;
      (_this$editor2 = this.editor) === null || _this$editor2 === void 0 ? void 0 : _this$editor2.setData(formulario.conteudo);
      this.inputsFormulario.conteudo = formulario.conteudo;
      (_this$editorExame = this.editorExame) === null || _this$editorExame === void 0 ? void 0 : _this$editorExame.setData(formulario.exame);
      this.inputsFormulario.exame = formulario.exame;
      (_this$editorHipotese = this.editorHipotese) === null || _this$editorHipotese === void 0 ? void 0 : _this$editorHipotese.setData(formulario.hipotese);
      this.inputsFormulario.hipotese = formulario.hipotese;
      (_this$editorConduta = this.editorConduta) === null || _this$editorConduta === void 0 ? void 0 : _this$editorConduta.setData(formulario.conduta);
      this.inputsFormulario.conduta = formulario.conduta;
      window.scrollTo(0, 0);
    },
    formularioSubmit: function formularioSubmit() {
      var _this$editor3,
          _this$editorExame2,
          _this$editorConduta2,
          _this$editorHipotese2,
          _this$editor4,
          _this$editorExame3,
          _this$editorConduta3,
          _this$editorHipotese3,
          _this4 = this;

      this.inputsFormulario.conteudo = (_this$editor3 = this.editor) === null || _this$editor3 === void 0 ? void 0 : _this$editor3.getData();
      this.inputsFormulario.exame = (_this$editorExame2 = this.editorExame) === null || _this$editorExame2 === void 0 ? void 0 : _this$editorExame2.getData();
      this.inputsFormulario.conduta = (_this$editorConduta2 = this.editorConduta) === null || _this$editorConduta2 === void 0 ? void 0 : _this$editorConduta2.getData();
      this.inputsFormulario.hipotese = (_this$editorHipotese2 = this.editorHipotese) === null || _this$editorHipotese2 === void 0 ? void 0 : _this$editorHipotese2.getData();
      var form = new FormData(document.querySelector('#formulario-texto'));
      var conteudoExame = (_this$editor4 = this.editor) === null || _this$editor4 === void 0 ? void 0 : _this$editor4.getData();
      form.set('conteudo', conteudoExame);
      var Exame = (_this$editorExame3 = this.editorExame) === null || _this$editorExame3 === void 0 ? void 0 : _this$editorExame3.getData();
      form.set('exame', Exame);
      var Conduta = (_this$editorConduta3 = this.editorConduta) === null || _this$editorConduta3 === void 0 ? void 0 : _this$editorConduta3.getData();
      form.set('conduta', Conduta);
      var Hipotese = (_this$editorHipotese3 = this.editorHipotese) === null || _this$editorHipotese3 === void 0 ? void 0 : _this$editorHipotese3.getData();
      form.set('hipotese', Hipotese);
      this.loadingSubmitFormulario = true;
      console.log(this.inputsFormulario);

      if (this.inputsFormulario.cd_formulario) {
        //form.set('cd_formulario', this.inputsFormulario.cd_formulario); 
        axios.put("/rpclinica/json/perfil-profissional-formulario-update", this.inputsFormulario).then(function (res) {
          var indexFormulario = _this4.formularios.findIndex(function (formulario) {
            return formulario.cd_formulario == _this4.inputsFormulario.cd_formulario;
          });

          _this4.formularios[indexFormulario] = res.data.formulario;
          document.getElementById("formulario-texto").reset();
          $('#tipo_formulario').val(null).trigger('change');
          _this4.tipo_formulario = 'DOC';
          toastr['success'](res.data.message);

          _this4.clearFormulario();
        })["catch"](function (err) {
          return toastr['error'](err.response.data.message);
        })["finally"](function () {
          return _this4.loadingSubmitFormulario = false;
        });
        return;
      }

      axios.post('/rpclinica/json/perfil-profissional-formulario-create', form).then(function (res) {
        //this.formularios = res.data.formularios;
        _this4.formularios.push(res.data.formulario);

        document.getElementById("formulario-texto").reset();
        $('#tipo_formulario').val(null).trigger('change');
        _this4.tipo_formulario = 'DOC';
        toastr['success'](res.data.message);

        _this4.clearFormulario();
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this4.loadingSubmitFormulario = false;
      });
    },
    deleteFormulario: function deleteFormulario(cdFormulario, indice) {
      var _this5 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir esse formulario?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22BAA0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          _this5.indiceFormularioDelete = indice;
          axios["delete"]("/rpclinica/json/perfil-profissional-formulario-delete/".concat(cdFormulario)).then(function (res) {
            _this5.formularios.splice(indice, 1);

            toastr["success"]('Formulario excluido com sucesso!');
            document.getElementById("formulario-texto").reset();
            $('#tipo_formulario').val(null).trigger('change');
            _this5.tipo_formulario = 'DOC';
          })["catch"](function (err) {
            return toastr["error"]('Não foi possivel excluir o formulario!');
          })["finally"](function () {
            return _this5.indiceFormularioDelete = null;
          });
        }
      });
    },
    copyText: function copyText(text) {
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.prev = 0;
                _context.next = 3;
                return navigator.clipboard.writeText(text);

              case 3:
                toastr['success']('Copiado!');
                _context.next = 9;
                break;

              case 6:
                _context.prev = 6;
                _context.t0 = _context["catch"](0);
                toastr['error']("N\xE3o foi possivel copiar. Erro: ".concat(_context.t0));

              case 9:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, null, [[0, 6]]);
      }))();
    },
    copyDocumento: function copyDocumento(documento) {
      var _this$editor5;

      this.inputsFormulario.nome = documento.nm_documento;
      (_this$editor5 = this.editor) === null || _this$editor5 === void 0 ? void 0 : _this$editor5.setData(documento.conteudo);
      window.scrollTo(0, 0);
    }
  };
});
Alpine.data('appProcedimetos', function () {
  return {
    inputsProcedimento: {
      procedimento: null,
      convenio: null,
      valor: null
    },
    loadingSubmitProcedimento: false,
    procedimentosProfissional: [],
    indiceProcedimentoDelete: null,
    init: function init() {
      var _this6 = this;

      this.procedimentosProfissional = procedimentosProfissional;
      $('#procedimento').on('select2:select', function (evt) {
        return _this6.inputsProcedimento.procedimento = evt.params.data.id;
      });
      $('#convenio').on('select2:select', function (evt) {
        return _this6.inputsProcedimento.convenio = evt.params.data.id;
      });
    },
    clear: function clear() {
      this.inputsProcedimento = {
        procedimento: null,
        convenio: null,
        valor: null
      };
      $('#procedimento').val(null).trigger('change');
      $('#convenio').val(null).trigger('change');
    },
    setProcedimentoEdit: function setProcedimentoEdit(procedimento) {
      this.inputsProcedimento.cd_proc_prof = procedimento.cd_proc_prof;
      this.inputsProcedimento.procedimento = procedimento.cd_proc;
      this.inputsProcedimento.convenio = procedimento.cd_convenio;
      this.inputsProcedimento.valor = procedimento.vl_proc;
      $('#procedimento').val(procedimento.cd_proc).trigger('change');
      $('#convenio').val(procedimento.cd_convenio).trigger('change');
    },
    procedimentoSubmit: function procedimentoSubmit() {
      var _this7 = this;

      this.loadingSubmitProcedimento = true;

      if (this.inputsProcedimento.cd_proc_prof) {
        axios.put('/rpclinica/json/perfil-profissional-procedimento-update', this.inputsProcedimento).then(function (res) {
          var indexProcedimento = _this7.procedimentosProfissional.findIndex(function (procedimento) {
            return procedimento.cd_proc_prof == _this7.inputsProcedimento.cd_proc_prof;
          });

          _this7.procedimentosProfissional[indexProcedimento] = res.data.procedimento;
          toastr['success'](res.data.message);

          _this7.clear();
        })["catch"](function (err) {
          return toastr['error'](err.response.data.message);
        })["finally"](function () {
          return _this7.loadingSubmitProcedimento = false;
        });
        return;
      }

      axios.post('/rpclinica/json/perfil-profissional-procedimento-create', this.inputsProcedimento).then(function (res) {
        _this7.procedimentosProfissional.push(res.data.procedimento);

        toastr['success'](res.data.message);

        _this7.clear();
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this7.loadingSubmitProcedimento = false;
      });
    },
    deleteProcedimento: function deleteProcedimento(cdProcProf, indice) {
      var _this8 = this;

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
          _this8.indiceProcedimentoDelete = indice;
          axios.post("/rpclinica/profissional-delete-procedimento/".concat(cdProcProf)).then(function (res) {
            _this8.procedimentosProfissional.splice(indice, 1);

            toastr["success"]('Procedimento excluido com sucesso!');
          })["catch"](function (err) {
            return toastr["error"]('Não foi possivel excluir o procedimento!');
          })["finally"](function () {
            return _this8.indiceProcedimentoDelete = null;
          });
        }
      });
    }
  };
});
Alpine.data('appEspecialidades', function () {
  return {
    inputsEspecialidade: {
      especialidade: null,
      compartilha: null
    },
    loadingSubmitEspecialidade: false,
    especialidadesProfissional: [],
    indiceEspecialidadeDelete: null,
    init: function init() {
      var _this9 = this;

      this.especialidadesProfissional = especialidadesProfissional;
      $('#especialidade').on('select2:select', function (evt) {
        return _this9.inputsEspecialidade.especialidade = evt.params.data.id;
      });
      $('#compartilha').on('select2:select', function (evt) {
        return _this9.inputsEspecialidade.compartilha = evt.params.data.id;
      });
    },
    clear: function clear() {
      this.inputsEspecialidade = {
        especialidade: null,
        compartilha: null
      };
      $('#especialidade').val(null).trigger('change');
      $('#compartilha').val(null).trigger('change');
    },
    setEspecialidadeEdit: function setEspecialidadeEdit(especialidade) {
      this.inputsEspecialidade.cd_prof_espec = especialidade.cd_prof_espec;
      this.inputsEspecialidade.especialidade = especialidade.cd_especialidade;
      this.inputsEspecialidade.compartilha = especialidade.sn_compartilha;
      $('#especialidade').val(especialidade.cd_especialidade).trigger('change');
      $('#compartilha').val(especialidade.sn_compartilha).trigger('change');
    },
    submitEspecialidade: function submitEspecialidade() {
      var _this10 = this;

      this.loadingSubmitEspecialidade = true;

      if (this.inputsEspecialidade.cd_prof_espec) {
        axios.put('/rpclinica/json/perfil-profissional-especialidade-update', this.inputsEspecialidade).then(function (res) {
          var indexEspecialidade = _this10.especialidadesProfissional.findIndex(function (especialidade) {
            return especialidade.cd_prof_espec == _this10.inputsEspecialidade.cd_prof_espec;
          });

          _this10.especialidadesProfissional[indexEspecialidade] = res.data.especialidade;
          toastr['success'](res.data.message);

          _this10.clear();
        })["catch"](function (err) {
          return toastr['error'](err.response.data.message);
        })["finally"](function () {
          return _this10.loadingSubmitEspecialidade = false;
        });
        return;
      }

      axios.post('/rpclinica/json/perfil-profissional-especialidade-create', this.inputsEspecialidade).then(function (res) {
        _this10.especialidadesProfissional.push(res.data.especialidade);

        toastr['success'](res.data.message);

        _this10.clear();
      })["catch"](function (err) {
        return toastr['error'](err.response.data.message);
      })["finally"](function () {
        return _this10.loadingSubmitEspecialidade = false;
      });
    },
    deleteEspecialidade: function deleteEspecialidade(cdProcEspec, indice) {
      var _this11 = this;

      Swal.fire({
        title: 'Confirmação',
        text: "Tem certeza que deseja excluir esse especialidade?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          _this11.indiceEspecialidadeDelete = indice;
          axios.post("/rpclinica/profissional-delete-especialidade/".concat(cdProcEspec)).then(function (res) {
            _this11.especialidadesProfissional.splice(indice, 1);

            toastr["success"]('Especialidade excluido com sucesso!');
          })["catch"](function (err) {
            return toastr["error"]('Não foi possivel excluir o especialidade!');
          })["finally"](function () {
            return _this11.indiceEspecialidadeDelete = null;
          });
        }
      });
    }
  };
});
Alpine.data('appCertificado', function () {
  return {
    inputsForms: {
      tipo: null
    },
    init: function init() {},
    deleteCertificado: function deleteCertificado() {
      Swal.fire({
        title: 'Confirmação',
        text: "Deseja excluir o certificado?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22baa0',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim'
      }).then(function (result) {
        if (result.isConfirmed) {
          location.href = "perfil-prof-del-certificado";
        }
      });
    }
  };
});
/******/ })()
;