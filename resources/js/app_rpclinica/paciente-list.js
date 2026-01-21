import moment from 'moment';

Alpine.data('appPacienteList', () => ({
    search: '',
    loading: false,
    pacientes: [],
    nextPage: null,

    init() {
        this.getPacientes();

        this.$watch('search', (value) => {
            this.nextPage = null;
            this.getPacientes();
        });

        // Force refresh when returning from another page (Back button/BFCache)
        window.addEventListener('pageshow', (event) => {
            if (event.persisted || performance.getEntriesByType("navigation")[0].type === 'back_forward') {
                this.getPacientes();
            }
        });
    },

    getPacientes() {
        this.loading = true;

        let baseUrl = this.nextPage ? this.nextPage : `${routePacienteList}?search=${this.search}`;
        // Cache busting
        let url = baseUrl + (baseUrl.includes('?') ? '&' : '?') + `t=${new Date().getTime()}`;

        axios.get(url)
            .then((res) => {
                this.pacientes = this.nextPage ? this.pacientes.concat(res.data.data) : res.data.data;
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