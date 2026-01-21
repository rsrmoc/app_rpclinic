import AirDatepicker from 'air-datepicker';
import LocalePTBR from 'air-datepicker/locale/pt-BR';
import 'air-datepicker/air-datepicker.css';
import moment from 'moment';

Alpine.data('appAgendamento', () => ({
    loading: false,
    agendamentos: [],
    datesWithEvents: [],
    multipleSelect: true, // Ativado por padrão
    selectedDates: [], // Array de datas selecionadas para exibição

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
            multipleDates: this.multipleSelect, // Baseado no toggle
            onSelect: ({ formattedDate, date }) => {
                // Atualizar a lista de datas selecionadas
                this.selectedDates = this.datepicker.selectedDates || [];
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

        // Inicializar selectedDates
        this.selectedDates = this.datepicker.selectedDates || [];
    },

    toggleMultipleSelect() {
        // Destruir e recriar o datepicker com a nova configuração
        const currentDates = this.datepicker.selectedDates || [];
        this.datepicker.destroy();

        this.datepicker = new AirDatepicker('#dataAgendamento', {
            classes: 'datePickerAgendamento',
            locale: LocalePTBR,
            selectedDates: this.multipleSelect ? currentDates : (currentDates[0] ? [currentDates[0]] : []),
            dateFormat: 'yyyy-MM-dd',
            multipleDates: this.multipleSelect,
            onSelect: ({ formattedDate, date }) => {
                this.selectedDates = this.datepicker.selectedDates || [];
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

        this.selectedDates = this.datepicker.selectedDates || [];
    },

    formatDateDisplay(date) {
        return moment(date).format('DD/MM/YYYY');
    },

    removeDate(index) {
        const dates = this.datepicker.selectedDates || [];
        dates.splice(index, 1);
        this.datepicker.selectDate(dates);
        this.selectedDates = dates;
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