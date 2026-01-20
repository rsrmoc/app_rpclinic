/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************************!*\
  !*** ./resources/js/rpclinica/estoque-devolucao.js ***!
  \*****************************************************/
Alpine.data('app', function () {
  return {
    loadingSaida: false,
    codigoSaida: null,
    data: null,
    estoque: null,
    setor: null,
    numeroDoc: null,
    produtos: [],
    devolucoes: [],
    init: function init() {
      var _this = this;

      if (typeof devolucao !== 'undefined') {
        this.codigoSaida = devolucao.cd_solicitacao_saida;
        this.data = devolucao.solicitacao_saida.dt_saida;
        this.estoque = devolucao.solicitacao_saida.estoque.nm_estoque;
        this.setor = devolucao.solicitacao_saida.setor.nm_setor;
        this.numeroDoc = devolucao.solicitacao_saida.nr_doc;
        this.devolucoes = Object.assign([], devolucao.devolucoes_produtos);
        this.produtos = devolucao.solicitacao_saida.saida_produtos.filter(function (saida) {
          var exist = _this.devolucoes.find(function (d) {
            return d.cd_produto == saida.cd_produto && d.cd_lote_produto == saida.cd_lote_produto;
          });

          return !!exist;
        });
      }

      this.$watch('codigoSaida', function () {
        if (!_this.codigoSaida) return;
        _this.loadingSaida = true;
        axios.get("/rpclinica/json/estoque-saida/".concat(_this.codigoSaida)).then(function (res) {
          _this.data = res.data.dt_saida;
          _this.estoque = res.data.estoque.nm_estoque;
          _this.setor = res.data.setor.nm_setor;
          _this.numeroDoc = res.data.nr_doc;
          _this.produtos = res.data.saida_produtos;
          _this.devolucoes = _this.produtos.map(function (produto) {
            return {
              cd_produto: produto.cd_produto,
              cd_lote_produto: produto.cd_lote_produto,
              qtde: 0
            };
          });
        })["finally"](function () {
          return _this.loadingSaida = false;
        });
      });
    }
  };
});
/******/ })()
;