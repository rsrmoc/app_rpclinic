import AirDatepicker from 'air-datepicker';
import LocalePTBR from 'air-datepicker/locale/pt-BR';
import 'air-datepicker/air-datepicker.css';
import moment from 'moment';
import { jsPDF } from 'jspdf';

console.log('✅ ARQUIVO DOCUMENTOS.JS CARREGADO - BUILDE: ' + new Date().toLocaleTimeString());

Alpine.data('appDocumentos', () => ({
    loading: false,
    documentos: [],
    datesWithEvents: [],
    datepicker: null,

    init() {
        console.log('🚀 appDocumentos init() chamado');

        // Timeout pequeno para garantir renderização do DOM
        setTimeout(() => {
            this.iniciarCalendario();
        }, 100);
    },

    iniciarCalendario() {
        // Zera horas da data atual para evitar problemas de comparação
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        this.getDatesWithEvents(today.getMonth(), today.getFullYear());

        // Se já existe, destrói
        if (this.datepicker) {
            try {
                this.datepicker.destroy();
            } catch (e) {
                console.warn('⚠️ Erro não-crítico ao destruir datepicker:', e);
            }
            this.datepicker = null;
        }

        const el = document.getElementById('documentosDatePicker');
        if (el) el.innerHTML = ''; // Limpa container



        this.datepicker = new AirDatepicker('#documentosDatePicker', {
            classes: 'datePickerAgendamento',
            locale: LocalePTBR,
            selectedDates: [today],
            dateFormat: 'yyyy-MM-dd',
            multipleDates: false, // Força seleção única
            range: false,
            toggleSelected: false, // Impede desmarcar ao clicar no mesmo dia

            onSelect: ({ date, formattedDate }) => {
                console.log('📅 Evento onSelect disparado');
                console.log('👉 FormattedDate:', formattedDate);

                // Força pegar a data do objeto Date se estiver disponível (mais seguro)
                let selectedDate = formattedDate;
                if (date) {
                    // Se date for array (caso bugado de multiple), pega o último
                    const rawDate = Array.isArray(date) ? date[date.length - 1] : date;
                    if (rawDate) {
                        selectedDate = moment(rawDate).format('YYYY-MM-DD');
                        console.log('🎯 Data extraída do objeto Date:', selectedDate);
                    }
                }

                if (!selectedDate) {
                    console.warn('⚠️ Nenhuma data válida selecionada!');
                    return;
                }

                this.getDocumentos(selectedDate);
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

        // Busca inicial
        const formattedDate = moment(today).format('YYYY-MM-DD');
        console.log('📅 Buscando documentos da data inicial:', formattedDate);
        this.getDocumentos(formattedDate);
    },

    getDatesWithEvents(month, year) {
        axios.post(routeAgendamentosDatas, {
            cd_profissional: cdProfissional,
            month: month,
            year: year,
            tipo: 'documentos' // ATENÇÃO: Pede datas de documentos, não agendamentos
        })
            .then((res) => {
                this.datesWithEvents = res.data.dates;

                // Proteção contra erro de atualização em instância instável
                if (this.datepicker) {
                    try {
                        this.datepicker.update();
                    } catch (e) {
                        console.warn('⚠️ Erro ao atualizar visual do datepicker (ignorado):', e);
                    }
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