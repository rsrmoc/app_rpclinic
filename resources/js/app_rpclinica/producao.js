import AirDatepicker from 'air-datepicker';
import LocalePTBR from 'air-datepicker/locale/pt-BR';
import 'air-datepicker/air-datepicker.css';
import moment from 'moment';

Alpine.data('appProducao', () => ({
    loading: false,
    producoes: [],
    datesWithEvents: [],
    currentDate: new Date(),

    capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.substring(1);
    },
 

    init() { 

        this.datepicker = new AirDatepicker('#dataAgendamento', {
            classes: 'datePickerAgendamento',
            locale: LocalePTBR,
            selectedDates: [new Date()],
            view: 'months',      // Start in months view
            minView: 'months',   // Only allow selecting months
            dateFormat: 'MMMM yyyy', // Format: "Janeiro 2025"
            onSelect: ({ date }) => {
                console.log('asddasd33');
                if (!date) return;
                // Parse date to start of month 'YYYY-MM-01' for backend compatibility
                const formattedDate = moment(date).startOf('month').format('YYYY-MM-DD');
                this.getProducoes(formattedDate);
            },
            // We can add dots for months with events if API supports it, 
            // but for now let's just make the month picker work.
            onChangeViewDate: ({ year }) => {
                // Potentially fetch yearly stats here
            }
        });

         
        // Initialize with current month
        let dt = new Date();
        let formattedDate = moment(dt).startOf('month').format('YYYY-MM-DD');
        this.getProducoes(formattedDate);
    },

    // Not strictly used for month/year view indicators yet, but kept structure
    getDatesWithEvents(month, year) {
        // This API returns specific DAYS. It might not be useful for Month view dots
        // unless we want to show dots on months within a year view.
        // For now, keeping it minimal to avoid errors if called.
    },

    getProducoes(date) {
        this.loading = true;

        axios.post(routeProducoes, {
            cd_profissional: cdProfissional,
            data: date
        })
            .then((res) => {
                console.log(res.data);
                this.producoes = res.data.producoes || []; 
            })
            .catch((err) => {
                console.error('Erro ao carregar produções:', err);
                toastr.error('Erro ao carregar produções.', 'Erro', {
                    timeOut: 5000,
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-center"
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