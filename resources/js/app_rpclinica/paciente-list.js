import moment from 'moment';

Alpine.data('appPacienteList', () => ({
    search: '',
    loading: false,
    pacientes: [],
    nextPage: null,

    init() {
         
        this.$watch('search', (value) => {
            if (!value || value.trim() == '') return;

            this.nextPage = null;
            this.getPacientes();
        });
    },

    getPacientes() {
        this.loading = true;

        let url = this.nextPage ? this.nextPage : `/app_rpclinic/api/paciente-list?search=${this.search}`;

        axios.get(url)
            .then((res) => {
                this.pacientes = this.nextPage ? this.pacientes.concat(res.data.data): res.data.data;
                this.nextPage = res.data.next_page_url;
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loading = false);
    },

    formatDate(date) {
        if (!date) return;
        return moment(date).lang('pt-BR').format('L');
    }
}));