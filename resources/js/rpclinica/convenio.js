Alpine.data('app', () => ({
    formProcedimento: {
        codigo: null,
        cd_procedimento: null,
        cod_procedimento: null,
        nm_procedimento: null,
        data: null,
        valor: null,
    },
    procedimentos: [],
    entradasProcedimentos: [],
    deletingEntradaProcedimento: false,

    init() {
        this.procedimentos = procedimentos 
        $('#procedimento').on('select2:select', (evt) => {
            //this.formProcedimento.cd_procedimento = evt.params.data.id;
            this.formProcedimento.codigo = evt.params.data.id;
            let el = evt.params.data.element; 
            this.formProcedimento.cod_procedimento = el.dataset.codigo;
            this.formProcedimento.cd_procedimento = el.dataset.codigo;
            this.formProcedimento.nm_procedimento = el.dataset.nome; 
        });

        if (typeof entradasProcedimentos !== 'undefined') {
            this.entradasProcedimentos = entradasProcedimentos;
        }

        console.log(this.entradasProcedimentos);
    },

    submitConvenio() {
        document.querySelector('#formConvenio button[type=submit]').click();
    },

    limpar() {
        document.querySelector('#formConvenio').reset();

        this.clearFormProcedimento();
    },

    clearFormProcedimento() {
        this.formProcedimento = {
            cd_procedimento: null,
            dt_vigencia: null,
            valor: null,
        };

        $('#procedimento').val(null).trigger('change');
    },

    addEntradaProcedimento() {
        this.entradasProcedimentos.push(Object.assign({}, this.formProcedimento)) 
        this.clearFormProcedimento()
        console.log(this.entradasProcedimentos);
    },

    nomeProcedimento(cdProcedimento) {
        return this.procedimentos.find((procedimento) => cdProcedimento == procedimento.cod_proc)?.nm_proc
    },

    codProc(cdProcedimento) { 
         return this.procedimentos.find((procedimento) => cdProcedimento == procedimento.cd_proc)?.cod_proc;
    },

    deleteRepasse(codigo) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse repasse?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22baa0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            location.href = `/rpclinica/convenio-delete-repasse/${codigo}`; 
        }); 
    },
 
    excluirEntradaProcedimento(indice) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir esse procedimento?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                if (this.entradasProcedimentos[indice].cd_procedimento_convenio) {
                    this.deletingEntradaProcedimento = true

                    axios.post(`/rpclinica/procedimento-convenio-delete/${ this.entradasProcedimentos[indice].cd_procedimento_convenio }`)
                        .then(() => {
                            this.entradasProcedimentos.splice(indice, 1);

                            toastr['success']('Procedimento excluído com sucesso!');
                        })
                        .catch((err) => toastr['error']('Houve um error ao excluir o procedimento.'))
                        .finally(() => this.deletingEntradaProcedimento = false)

                    return;
                }

                this.entradasProcedimentos.splice(indice, 1);
            }
        }); 
    },
    
    formatValor(valor) {
        return Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(valor);
    }

}))
