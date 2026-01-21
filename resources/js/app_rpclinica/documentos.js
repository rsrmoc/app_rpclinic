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
        console.log('🚀 appDocumentos inicializado');
        this.getDatesWithEvents(new Date().getMonth(), new Date().getFullYear());

        this.datepicker = new AirDatepicker('#documentosDatePicker', {
            classes: 'datePickerAgendamento',
            locale: LocalePTBR,
            selectedDates: [new Date()],
            dateFormat: 'yyyy-MM-dd',
            onSelect: ({ formattedDate }) => {
                console.log('📅 Data selecionada:', formattedDate);
                if (!formattedDate) {
                    console.warn('⚠️ formattedDate está vazio!');
                    return;
                }
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
        let formattedDate = `${dt.getFullYear()}-${(dt.getMonth() + 1).toString().padStart(2, '0')}-${dt.getDate().toString().padStart(2, '0')}`;
        console.log('📅 Buscando documentos da data inicial:', formattedDate);
        this.getDocumentos(formattedDate);
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

    getDocumentos(data) {
        console.log('🔍 Buscando documentos para data:', data);
        console.log('👤 cd_profissional:', cdProfissional);

        this.loading = true;
        this.documentos = []; // Limpar documentos anteriores

        axios.post(routeDocumentos, {
            cd_profissional: cdProfissional,
            data
        })
            .then((res) => {
                console.log('✅ Resposta da API documentos:', res.data);

                // Verificar se a resposta tem documentos
                if (res.data && res.data.documentos) {
                    this.documentos = res.data.documentos;
                    console.log('📄 Documentos carregados:', this.documentos.length);
                    console.log('📋 Documentos:', this.documentos);
                } else {
                    this.documentos = [];
                    console.log('⚠️ Nenhum documento encontrado na resposta');
                }
            })
            .catch((err) => {
                console.error('❌ Erro ao buscar documentos:', err);
                console.error('📄 Detalhes do erro:', err.response?.data);
                parseErrorsAPI(err);
            })
            .finally(() => {
                this.loading = false;
                console.log('🏁 Loading finalizado. Total documentos:', this.documentos.length);
            });
    },

    formatDate(date) {
        return moment(date).lang('pt-BR').format('LLL');
    },

    async compartilharDoc(documento) {
        const url = `/rpclinica/json/imprimirDocumentoGeral/${documento.agendamento.cd_agendamento}/${documento.cd_documento}`;
        const fullUrl = window.location.origin + url;

        if (navigator.share && navigator.canShare) {
            try {
                // Buscar o PDF
                const response = await fetch(fullUrl);
                const blob = await response.blob();

                // Criar arquivo do blob
                const fileName = `${documento.nm_formulario}_${documento.agendamento.paciente.nm_paciente}.pdf`;
                const file = new File([blob], fileName, { type: 'application/pdf' });

                // Verificar se pode compartilhar arquivos
                if (navigator.canShare({ files: [file] })) {
                    await navigator.share({
                        title: documento.nm_formulario,
                        text: `Documento: ${documento.nm_formulario} - Paciente: ${documento.agendamento.paciente.nm_paciente}`,
                        files: [file]
                    });
                    console.log('PDF compartilhado com sucesso');
                } else {
                    // Fallback para URL se não puder compartilhar arquivo
                    await navigator.share({
                        title: documento.nm_formulario,
                        text: `Documento: ${documento.nm_formulario} - Paciente: ${documento.agendamento.paciente.nm_paciente}`,
                        url: fullUrl
                    });
                    console.log('Link compartilhado com sucesso');
                }
            } catch (error) {
                console.log('Erro ao compartilhar', error);
                alert('Erro ao compartilhar documento');
            }
        } else {
            // Fallback: copiar link
            navigator.clipboard.writeText(fullUrl).then(() => {
                alert('Link do documento copiado para a área de transferência!');
            }).catch(err => {
                console.error('Erro ao copiar link: ', err);
            });
        }
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