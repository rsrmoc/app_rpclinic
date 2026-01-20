Alpine.data('app', () => ({
    loadingSaida: false,
    codigoSaida: null,
    data: null,
    estoque: null,
    setor: null,
    numeroDoc: null,
    produtos: [],
    devolucoes: [],

    init() {
        if (typeof devolucao !== 'undefined') {
            this.codigoSaida = devolucao.cd_solicitacao_saida;
            this.data = devolucao.solicitacao_saida.dt_saida;
            this.estoque = devolucao.solicitacao_saida.estoque.nm_estoque;
            this.setor = devolucao.solicitacao_saida.setor.nm_setor;
            this.numeroDoc = devolucao.solicitacao_saida.nr_doc;

            this.devolucoes = Object.assign([], devolucao.devolucoes_produtos);
            this.produtos = devolucao.solicitacao_saida.saida_produtos.filter((saida) => {
                let exist = this.devolucoes.find((d) => d.cd_produto == saida.cd_produto && d.cd_lote_produto == saida.cd_lote_produto );
                return !!exist;
            })
        }

        this.$watch('codigoSaida', () => {
            if (!this.codigoSaida) return;

            this.loadingSaida = true

            axios.get(`/rpclinica/json/estoque-saida/${this.codigoSaida}`)
                .then((res) => {
                    this.data = res.data.dt_saida;
                    this.estoque = res.data.estoque.nm_estoque;
                    this.setor = res.data.setor.nm_setor;
                    this.numeroDoc = res.data.nr_doc
                    this.produtos = res.data.saida_produtos

                    this.devolucoes = this.produtos.map((produto) => ({
                        cd_produto: produto.cd_produto,
                        cd_lote_produto: produto.cd_lote_produto,
                        qtde: 0
                    }))
                })
                .finally(() => this.loadingSaida = false)
        });
    }
}))