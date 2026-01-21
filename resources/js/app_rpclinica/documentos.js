import AirDatepicker from 'air-datepicker';
import LocalePTBR from 'air-datepicker/locale/pt-BR';
import 'air-datepicker/air-datepicker.css';
import moment from 'moment';
import { jsPDF } from 'jspdf';

console.log('✅ ARQUIVO DOCUMENTOS.JS CARREGADO - v2.2 (Credentials + Log Clean): ' + new Date().toLocaleTimeString());

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
        // Ajuste de rota caso precise de uma rota específica para download de PDF
        // Por enquanto usa a mesma de visualização
        const url = `/rpclinica/json/imprimirDocumentoGeral/${documento.agendamento.cd_agendamento}/${documento.cd_documento}`;
        const fullUrl = window.location.origin + url;

        // Tentar compartilhar via Web Share API
        if (navigator.share && navigator.canShare) {
            try {
                // Tenta buscar o conteúdo para ver se é PDF real
                console.log('🔄 Buscando documento para verificar tipo...');
                // Adicionado credentials: 'include' para garantir envio de cookies de sessão
                const response = await fetch(fullUrl, { credentials: 'include' });

                if (!response.ok) {
                    console.error(`❌ Erro HTTP na requisição: ${response.status} ${response.statusText}`);
                    throw new Error(`Erro no servidor: ${response.status}`);
                }

                const contentType = response.headers.get('content-type');
                console.log('🔍 Tipo de conteúdo recebido:', contentType);

                // SÓ compartilha como arquivo se for realmente PDF E tiver conteúdo válido
                if (contentType && contentType.includes('application/pdf')) {
                    let blob = await response.blob();

                    // Validação e Reparo de Magic Bytes do PDF (%PDF-)
                    // Lê os primeiros 1KB para garantir que pega o header
                    let headerCheck = await blob.slice(0, 1024).text();
                    console.log('🧐 Magic Bytes Iniciais:', headerCheck.substring(0, 20));

                    if (!headerCheck.startsWith('%PDF-')) {
                        console.warn('⚠️ O arquivo recebido não inicia com %PDF-. Tentando localizar o header correto...');

                        const pdfIndex = headerCheck.indexOf('%PDF-');
                        if (pdfIndex > 0) {
                            console.log(`🔧 REPARANDO PDF: Header encontrado no índice ${pdfIndex}. Removendo lixo inicial.`);
                            blob = blob.slice(pdfIndex, blob.size, 'application/pdf');
                            // Re-validar após corte
                            headerCheck = await blob.slice(0, 5).text();
                            if (headerCheck !== '%PDF-') {
                                throw new Error('Falha ao reparar PDF. Arquivo continua inválido.');
                            }
                            console.log('✅ PDF Reparado com sucesso!');
                        } else {
                            console.warn('❌ Header %PDF- não encontrado no início do arquivo. Provável erro ou HTML retornado.');
                            alert('O servidor retornou um arquivo incorreto. Compartilhando link.');
                            throw new Error('Conteúdo não é um PDF válido e não pôde ser reparado');
                        }
                    }

                    const fileName = `${documento.nm_formulario}_${documento.agendamento.paciente.nm_paciente}.pdf`.replace(/[^a-z0-9]/gi, '_'); // Sanitizar nome
                    const file = new File([blob], fileName, { type: 'application/pdf' });

                    if (navigator.canShare({ files: [file] })) {
                        await navigator.share({
                            title: documento.nm_formulario,
                            text: `Documento: ${documento.nm_formulario}\nPaciente: ${documento.agendamento.paciente.nm_paciente}`,
                            files: [file]
                        });
                        console.log('✅ PDF compartilhado com sucesso');
                        return; // Sucesso, encerra
                    }
                } else {
                    console.warn(`⚠️ Content-Type não é PDF: ${contentType}`);
                }
                console.warn('❌ Header %PDF- não encontrado no início do arquivo. Provável erro ou HTML retornado.');
                throw new Error('Conteúdo não é um PDF válido e não pôde ser reparado');
            }
                    }

        const fileName = `${documento.nm_formulario}_${documento.agendamento.paciente.nm_paciente}.pdf`.replace(/[^a-z0-9]/gi, '_'); // Sanitizar nome
        const file = new File([blob], fileName, { type: 'application/pdf' });

        if (navigator.canShare({ files: [file] })) {
            await navigator.share({
                title: documento.nm_formulario,
                text: `Documento: ${documento.nm_formulario}\nPaciente: ${documento.agendamento.paciente.nm_paciente}`,
                files: [file]
            });
            console.log('✅ PDF compartilhado com sucesso');
            return; // Sucesso, encerra
        }
    }

                // SE não for PDF ou não suportar arquivos, compartilha o LINK
                console.log('⚠️ Conteúdo não é PDF ou envio de arquivo não suportado. Compartilhando link.');
    await navigator.share({
        title: documento.nm_formulario,
        text: `Acesse o documento digital:\n${documento.nm_formulario} - ${documento.agendamento.paciente.nm_paciente}`,
        url: fullUrl
    });
    console.log('🔗 Link compartilhado com sucesso');

} catch (error) {
    console.error('❌ Erro ao compartilhar:', error);

    // Último recurso: Copiar link
    this.fallbackCopyLink(fullUrl);
}
        } else {
    // Se navegador não suporta share API
    this.fallbackCopyLink(fullUrl);
}
    },

fallbackCopyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        // Usar toastr ou alert amigável se possível
        // Como estou sem acesso fácil ao toastr aqui, vai alert mesmo ou nada (feedback visual é ideal)
        // Mas o alert interrompe fluxo, melhor deixar quieto ou usar log se não for crítico
        alert('Link copiado para área de transferência!');
    }).catch(err => {
        console.error('Erro ao copiar link', err);
        prompt('Copie o link:', url); // Fallback manual
    });
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