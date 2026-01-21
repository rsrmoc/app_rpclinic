import AirDatepicker from 'air-datepicker';
import LocalePTBR from 'air-datepicker/locale/pt-BR';
import 'air-datepicker/air-datepicker.css';
import moment from 'moment';

Alpine.data('appAgendamento', () => ({
    loading: false,
    agendamentos: [],
    datesWithEvents: [],

    capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.substring(1);
    },

    classLabelSituacao: {
        livre: 'label-success',
        agendado: 'label-primary',
        confirmado: 'label-info',
        atendido: 'label-warning',
        bloqueado: 'label-danger',
        cancelado: 'label-danger',
        aguardando: 'label-aguardando',
        atendimento: 'label-aguardando'
    },

    init() {
        this.getDatesWithEvents(new Date().getMonth(), new Date().getFullYear());

        this.datepicker = new AirDatepicker('#dataAgendamento', {
            classes: 'datePickerAgendamento',
            locale: LocalePTBR,
            selectedDates: [new Date()],
            dateFormat: 'yyyy-MM-dd',
            multipleDates: true, // Enable multiple dates selection
            // onSelect removed/simplified as we are saving via button
            onSelect: ({ formattedDate, date }) => {
                // Optional: If you want to fetch details for the *last* selected date, you can.
                // But for "setting availability", maybe we don't fetch list. 
                // We just let user pick dates. 
                // If single date is picked, maybe we fetch? 
                // For now, let's keep it simple: Select dates -> Save.
                // If user clicks a date, we update selection. nothing else.
            },
            onRenderCell: ({ date, cellType }) => {
                if (cellType === 'day') {
                    const formattedCellDate = moment(date).format('YYYY-MM-DD');
                    const hasEvent = this.datesWithEvents.includes(formattedCellDate);

                    if (hasEvent) {
                        return {
                            classes: 'has-event-dot'
                        };
                    }
                }
            },
            onChangeViewDate: ({ month, year }) => {
                this.getDatesWithEvents(month, year);
            }
        });

        // Initial fetch not strictly needed if we are just selecting dates to save
    },

    getDatesWithEvents(month, year) {
        axios.post(routeAgendamentosDatas, {
            cd_profissional: cdProfissional,
            month: month,
            year: year
        })
            .then((res) => {
                this.datesWithEvents = res.data.dates;
                if (this.datepicker) {
                    this.datepicker.update();
                }
            });
    },

    // New function to save availability
    saveDisponibilidade() {
        const selectedDates = this.datepicker.selectedDates;

        if (!selectedDates || selectedDates.length === 0) {
            alert('Selecione pelo menos uma data.');
            return;
        }

        const formattedDates = selectedDates.map(date => moment(date).format('YYYY-MM-DD'));

        this.loading = true;

        axios.post(routeDisponibilidadeSave, {
            cd_profissional: cdProfissional,
            dates: formattedDates
        })
            .then((res) => {
                if (res.data.success) {
                    // alert('Disponibilidade salva com sucesso!');
                    // Maybe refresh events?
                    this.getDatesWithEvents(new Date().getMonth(), new Date().getFullYear());

                    // Clear selection? Or keep it? keeping it is fine.
                    // this.datepicker.clear();

                    // Show a toast or something? Browser alert for now is consistent with legacy apps
                    alert('Salvo com sucesso!');
                }
            })
            .catch((err) => {
                console.error(err);
                alert('Erro ao salvar disponibilidade.');
            })
            .finally(() => this.loading = false);
    },

    getAgendamentos(date) {
        this.loading = true;

        axios.post(routeAgendamentos, {
            cd_profissional: cdProfissional,
            data: date
        })
            .then((res) => this.agendamentos = res.data.agendamentos)
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loading = false);
    },
    formatDate(date) {
        return moment(date).lang('pt-BR').format('LLL');
    }
}));