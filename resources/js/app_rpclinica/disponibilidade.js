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
            onSelect: ({ formattedDate }) => {
                if (!formattedDate) return;
                this.getAgendamentos(formattedDate);
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

        let dt = new Date();
        let formattedDate = `${dt.getFullYear()}-${(dt.getMonth() + 1).toString().padStart(2, 0)}-${dt.getDate().toString().padStart(2, 0)}`;
        this.getAgendamentos(formattedDate);
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