Alpine.data('app', () => ({
    lotes: [],
    inputsEntrada: {
        cd_produto: null,
        cd_lote_produto: null,
        qtde: null,
        valor: null
    },
    produtosEntrada: [],
    inputsLoteRequired: false,
    inputsLote: {
        nm_lote: null,
        cd_produto: null,
        validade: null
    },
    loadingModalLote: false,

    init() {
        this.lotes = lotes;

        $('#select-formulario-produto').on('select2:select', (evt) => {
            this.inputsEntrada.cd_produto = evt.params.data.id
            this.inputsLoteRequired = (evt.params.data.element.dataset.lote == 'S')
            this.changeOptionsInputLotes()
        })

        $('#entradaLotes').on('select2:select', (evt) => {
            this.inputsEntrada.cd_lote_produto = evt.params.data.id
        })

        // modal cadastro de lotes
        $(this.$refs.modalLote).on('hidden.bs.modal', () => {
            this.inputsLote = {
                nm_lote: null,
                cd_produto: null,
                validade: null
            };
            $(this.$refs.inputProdutoModalLotes).val(null).trigger('change');
        })

        $(this.$refs.inputProdutoModalLotes).on('select2:select', (evt) => this.inputsLote.cd_produto = evt.params.data.id)

        if (typeof entradaProdutos !== 'undefined') {
            this.produtosEntrada = entradaProdutos
        }
    },

    submit() {
        if (this.produtosEntrada.length == 0) {
            toastr['error']('Adicione no mínimo 1 produto!')
            return;
        }

        this.$refs.formEntrada.submit();
    },
    changeOptionsInputLotes() {
        let lotesOptions = this.lotes.filter((lote) => lote.cd_produto == this.inputsEntrada.cd_produto);
        lotesOptions = lotesOptions.map((lote) => ({id: lote.cd_lote, text: lote.nm_lote}));
        lotesOptions.unshift({ id: '', text: 'SELECIONE'});

        $('#entradaLotes').empty().trigger('change');
        $('#entradaLotes').select2({
            data: lotesOptions
        });
    },

    openModalLote: () => $('#modal-lote').modal('show'),

    addLoteProduto() {
        this.loadingModalLote = true

        axios.post('/rpclinica/json/produto-lote', this.inputsLote)
            .then((res) => {
                this.lotes.push(res.data.lote);
                this.changeOptionsInputLotes()
                toastr['success'](res.data.message);
                $(this.$refs.modalLote).modal('hide')
            })
            .catch((err) => toastr['error'](err.response.data.message))
            .finally(() => this.loadingModalLote = false)
    },

    addEntradaProduto() {
        if (this.inputsLoteRequired && !this.inputsEntrada.cd_lote_produto) {
            toastr['error']('Escolha um lote!');
            return;
        }

        this.produtosEntrada.push(Object.assign({}, this.inputsEntrada))
        this.inputsEntrada = {
            cd_produto: null,
            cd_lote_produto: null,
            qtde: null,
            valor: null
        }

        $('#select-formulario-produto').val(null).trigger('change');

        $('#entradaLotes').empty().trigger('change');
        $('#entradaLotes').select2({ data: [{id: '', text:'SELECIONE'}] });
    },

    nomeDoProduto(cdProduto) {
        return produtos.find((produto) => produto.cd_produto == cdProduto)?.nm_produto
    },
    nomeDoLote(cdLote) {
        if (!cdLote) return '<span class="text-warning">Nenhum</span>';

        return lotes.find((lote) => lote.cd_lote == cdLote)?.nm_lote
    },
    deleteEntradaProduto(indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse agendamento?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                if (this.produtosEntrada[indice].cd_entrada_produto) {
                    axios.post(`/rpclinica/estoque-entrada-prod-delete/${this.produtosEntrada[indice].cd_entrada_produto}`)
                        .then((res) => {
                            toastr['success']('Entrada do produto excluída com sucesso!')
                            this.produtosEntrada.splice(indice, 1)
                        })
                        .catch((err) => toastr['error']('Houve um erro ao excluir a entrada do produto.'))
                    return;
                }

                this.produtosEntrada.splice(indice, 1)
            }
        });
    }
}))

$(document).ready(() => {
    $('#modal-lote').modal({
        backdrop: 'static',
        show: false
    })
})