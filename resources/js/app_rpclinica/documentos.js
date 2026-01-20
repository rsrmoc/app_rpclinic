import AirDatepicker from 'air-datepicker';
import LocalePTBR from 'air-datepicker/locale/pt-BR';
import 'air-datepicker/air-datepicker.css';
import moment from 'moment';
import { jsPDF } from 'jspdf';

Alpine.data('appDocumentos', () => ({
    loading: false,
    documentos: [],
    datesWithEvents: [],

    init() {
        this.getDatesWithEvents(new Date().getMonth(), new Date().getFullYear());

        this.datepicker = new AirDatepicker('#documentosDatePicker', {
            classes: 'datePickerAgendamento',
            locale: LocalePTBR,
            selectedDates: [new Date()],
            dateFormat: 'yyyy-MM-dd',
            onSelect: ({ formattedDate }) => {
                if (!formattedDate) return;
                this.getDocumentos(formattedDate);
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
        this.getDocumentos(formattedDate);
    },

    getDatesWithEvents(month, year) {
        axios.post('/app_rpclinic/api/agendamentos-datas', {
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

    getDocumentos(data) {
        this.loading = true;

        axios.post('/app_rpclinic/api/documentos', {
            cd_profissional: cdProfissional,
            data
        })
            .then((res) => {
                this.documentos = res.data.documentos;

            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => this.loading = false);
    },

    formatDate(date) {
        return moment(date).lang('pt-BR').format('LLL');
    },

    downloadPDF(name, content) {
        let doc = new jsPDF();

        doc.html(
            content,
            {
                callback: function (doc) {
                    doc.save(name);
                },
                margin: [10, 10, 10, 10],
                autoPaging: 'text',
                x: 0,
                y: 0,
                width: 190,
                windowWidth: 675
            }
        );
    }
}));