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
    currentMonth: new Date().getMonth(),
    currentYear: new Date().getFullYear(),
    showClearModal: false,

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

        this.datepicker = new AirDatepicker('#dataAgendamento', {
            classes: 'datePickerAgendamento',
            locale: LocalePTBR,
            selectedDates: [],
            dateFormat: 'yyyy-MM-dd',
            multipleDates: this.multipleSelect, // Baseado no toggle
            onSelect: ({ formattedDate }) => {
                console.log(formattedDate);
                if (!formattedDate) return;
                
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
                console.log('inicio');
                this.currentMonth = month;
                this.currentYear = year; 
                this.loadSavedDates();
            }
        });

        // Carrega as datas salvas
        this.loadSavedDates();

        // Inicializar selectedDates
        this.selectedDates = this.datepicker.selectedDates || [];
    },

    loadSavedDates() {
        axios.get(routeDisponibilidadeGet, {
            params: {
                month: this.currentMonth,
                year: this.currentYear
            }
        })
            .then((res) => {
                if (res.data.success && res.data.dates) {
                    // Converter strings de data em objetos Date
                    const datesToSelect = res.data.dates.map(dateStr => {
                        return new Date(dateStr + 'T00:00:00');
                    });
                    // Selecionar as datas no datepicker
                    if (this.datepicker) {
                        this.datepicker.selectDate(datesToSelect);
                        this.selectedDates = datesToSelect;
                    }
                }
            })
            .catch((err) => {
                console.error('Erro ao carregar datas salvas:', err);
            });
    }, 

    formatDateDisplay(date) {
        return moment(date).format('DD/MM/YYYY');
    },

      
    // New function to save availability
    saveDisponibilidade() {
        const selectedDates = this.datepicker.selectedDates;

        if (!selectedDates || selectedDates.length === 0) {
            toastr.warning('Selecione pelo menos uma data.', 'Aviso', {
                timeOut: 7000,
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-center",
                showMethod: "slideDown",
                hideMethod: "slideUp"
            });
            return;
        }

        const formattedDates = selectedDates.map(date => moment(date).format('YYYY-MM-DD'));

        this.loading = true;

        axios.post(routeDisponibilidadeSave, {
            cd_profissional: cdProfissional,
            dates: formattedDates,
            month: this.currentMonth,
            year: this.currentYear
        })
            .then((res) => {
                if (res.data.success) {
                 
                    // Mostrar mensagem de sucesso com mais destaque
                    toastr.success(res.data.message, 'Sucesso', {
                        timeOut: 7000,
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-center",
                        showMethod: "slideDown",
                        hideMethod: "slideUp"
                    }); 
                }
            })
            .catch((err) => {
                console.error(err);
                toastr.error(err.response?.data?.message || 'Erro ao salvar disponibilidade.', 'Erro', {
                    timeOut: 7000,
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-center",
                    showMethod: "slideDown",
                    hideMethod: "slideUp"
                });
            })
            .finally(() => this.loading = false);
    },

    clearDates() {
        this.loading = true;
        
        axios.delete(routeDisponibilidadeDelete, {
            params: {
                month: this.currentMonth,
                year: this.currentYear
            }
        })
        .then((res) => {
            if (res.data.success) {
                // Limpar o datepicker
                if (this.datepicker) {
                    this.datepicker.clear();
                    this.selectedDates = [];
                }
                
                this.showClearModal = false;
                
                toastr.success(res.data.message || 'Datas limpas com sucesso!', 'Sucesso', {
                    timeOut: 7000,
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-center",
                    showMethod: "slideDown",
                    hideMethod: "slideUp"
                });
            }
        })
        .catch((err) => {
            console.error(err);
            toastr.error(err.response?.data?.message || 'Erro ao limpar datas.', 'Erro', {
                timeOut: 7000,
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-center",
                showMethod: "slideDown",
                hideMethod: "slideUp"
            });
        })
        .finally(() => {
            this.loading = false;
        });
    },
 
    formatDate(date) {
        return moment(date).lang('pt-BR').format('LLL');
    }
}));