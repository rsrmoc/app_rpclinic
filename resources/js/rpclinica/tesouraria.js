import axios from 'axios';
import moment from 'moment';

 

Alpine.data('app', () => ({ 
    INDEX: null,
    loading: false,
    loadingAcao: null,
    horarios: null,
    tituloModal: null,   
    modalConta: null,
    modalRecebimento: null, 
    valorConta: 0,
    modalCompleto: null,
    resumo: null,
 
    buttonGerarAtend: ' <i  class="fa fa-check"></i> Gerar Atendimento ',
    tempGerarAtend: ' <i  class="fa fa-check"></i> Gerar Atendimento ',
    buttonDisabled: false,
    buttonSalvarGuia: ' <span class="glyphicon glyphicon-check" aria-hidden="true"></span> Salvar ',
    buttonSalvarItem: ' <span class="glyphicon glyphicon-check" aria-hidden="true"></span> Salvar ',
    tempSalvar: '<span class="glyphicon glyphicon-check" aria-hidden="true"></span> Salvar ',
    buttonSalvando : " <i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Salvando... ",

    classStatus: {
        'Recebido': 'label-recebido',
        'A receber': 'label-warning',
        'Pago': 'label-info',
        'Vencido': 'label-danger',
        'A vencer': 'label-aguardando'
    },
    inputsConta: [],
    inputsLancamento: {
        cd_agendamento: null,
        cd_convenio: null,
        cd_empresa: null,
        cd_categoria: null,
        cd_conta: null,
        cd_forma: null,
        cd_fornecedor: null,
        cd_setor: null,
        cd_marca: null,
        descricao: null,
        documento: null,
        dt_emissao: null,
        tp_lancamento: 'receita',
        data_vencimento: null,
        data_compra: null,
        valor: null,
        data_pagamento: null,
        valor_pago: null,
        parcelar: false,
        dividir_parcelas: null,
        periodicidade: null,
        qtde_parcelas: null,
        cd_turma: null,
        cd_evento: null,
        data: null,
        profissional: null,
        parcelas: []
    },

    init() {
 
        $('#data-input').val(moment().format('YYYY-MM-DD'));
        $('#calendar').datepicker('setDate', moment().format('YYYY-MM-DD'));
   
        $('#form-horario select').on('select2:select', () => {
            this.getAtendimentos()
        });

        $('#calendar').on('changeDate', () => {
            $('#data-input').val(
                $('#calendar').datepicker('getFormattedDate')
            ); 
            this.getAtendimentos()
        }); 

        //$('#lancamentosSetores').on('select2:select', (evt) => this.inputsLancamento.cd_setor = evt.params.data.id);

        $('#lancamentosFormaPagamento').on('select2:select', (evt) => this.inputsLancamento.cd_forma = evt.params.data.id);

        //$('#lancamentosPeriodicidade').on('select2:select', (evt) => this.inputsLancamento.periodicidade = evt.params.data.id);
  
       // $('#lancamentosMarcas').on('select2:select', (evt) => this.inputsLancamento.cd_marca = evt.params.data.id);
        
        $('#lancamentosConta').on('select2:select', (evt) => this.inputsLancamento.cd_conta = evt.params.data.id);
      
        $('select#numeroParcelas').on('select2:select', (evt) => {
  
            if (!this.inputsLancamento.descricao?.trim()) {
                $('#numeroParcelas').val(null).trigger('change');
                toastr['error']('Preencha o campo de descrição!');
                return;
            }

            if (!this.inputsLancamento.documento) {
                 this.inputsLancamento.documento = this.getRandomIntInclusive('1000','99999'); 
            }
            
            if (!this.inputsLancamento.data_pagamento?.trim()) {
                $('#lancamentosParcelas').val(null).trigger('change');
                toastr['error']('Preencha o campo de data de Recebimento!');
                return;
            }

            if (!this.inputsLancamento.valor_pago?.trim()) {
                $('#lancamentosParcelas').val(null).trigger('change');
                toastr['error']('Preencha o campo de valor Recebido!');
                return;
            }
            this.inputsLancamento.periodicidade= 'mensal';
            /*
            if (!this.inputsLancamento.periodicidade?.trim()) {
                $('#lancamentosParcelas').val(null).trigger('change');
                toastr['error']('Preencha o campo de periodicidade!');
                return;
            }
            */

            this.inputsLancamento.qtde_parcelas = parseInt(evt.params.data.id);
            if(this.inputsLancamento.qtde_parcelas > 1 ){
                  
                let valor = this.inputsLancamento.valor_pago
                ? parseFloat(this.inputsLancamento.valor_pago.replace('.', '').replace(',', '.')) / this.inputsLancamento.qtde_parcelas
                : parseFloat(this.inputsLancamento.valor_pago.replace('.', '').replace(',', '.'));

                let dtVencimento = moment(this.inputsLancamento.data_vencimento ?? new Date().getTime());
                let dtCompra = dtVencimento;
                // let dtPagamento = moment(this.inputsLancamento.data_pagamento ?? new Date().getTime());
                let optionsDiasPeriodicidade = { 'mensal': 31, 'quinzenal': 15, 'semanal': 7 };
                let dias = optionsDiasPeriodicidade[this.inputsLancamento.periodicidade] ?? 31;

                if (this.inputsLancamento.qtde_parcelas) this.inputsLancamento.parcelas = new Array(this.inputsLancamento.qtde_parcelas).fill().map((val, index) => {
                    let parcela = {
                        descricao: `${this.inputsLancamento.descricao} ${index + 1}/${this.inputsLancamento.qtde_parcelas}`,
                        documento: this.inputsLancamento.documento + ' - ' + (index + 1) + '/' + this.inputsLancamento.qtde_parcelas,
                        data_vencimento: dtVencimento.format('YYYY-MM-DD'),
                        // data_pagamento: dtPagamento.format('YYYY-MM-DD'),
                        data_pagamento: null,
                        valor: this.formatValor(valor).toString().replace('R$ ', ''), 
                        valor_pago: null,
                        //data_compra: (dtCompra) ? dtCompra.add(0, 'days')._i : null
                        data_compra:   this.inputsLancamento.data_vencimento  
                        
                    };
                    
                    dtVencimento = dtVencimento.add(dias, 'days');
                    if(this.tp_conta == 'CA'){ 
                        dtCompra = dtCompra; 
                    }else{
                        
                        dtCompra = null;
                        
                    }
                    
                    // dtPagamento = dtPagamento.add(dias, 'days'); 
                    return parcela;
                }); 

            }else{ 
                this.inputsLancamento.parcelas=[];
            }

         
            
        });
      
        this.getAtendimentos()
        
  
    },

    getAtendimentos() {
 
        this.loading = true;  
        let form = new FormData(document.querySelector('#form-horario'));
        axios.post('/rpclinica/json/show-tesouraria', form)
            .then((res) => { 
                console.log(res.data);
                this.horarios=res.data.retorno;
                this.resumo = res.data.resumo; 
                
                this.inputsLancamento.data= res.data.request.data;
                this.inputsLancamento.profissional= res.data.request.profissional;
 
            })
            .catch((err) => this.messageDanger = err.response.data.message)
            .finally(() => this.loading = false);

    },

    clickAtendimento(dados,idx) {   

    
        this.INDEX = idx;
        this.modalConta = dados.itens;
        this.modalCompleto =dados;
        this.modalRecebimento = dados.boleto;
        
        this.tituloModal= ' [' + dados.cd_agendamento + ' ] ' + dados.paciente.nm_paciente  
        //$('select#lancamentosSetores').val(dados.local?.cd_setor).trigger('change');  
        //$('select#lancamentosSetores').val(dados.local.cd_setor).trigger('change');
        this.inputsLancamento.cd_setor=dados.local?.cd_setor;
        this.inputsLancamento.cd_agendamento = dados.cd_agendamento;
        this.inputsLancamento.cd_convenio = dados.cd_convenio;
        this.inputsLancamento.cd_categoria = dados.convenio?.cd_categoria;
        this.inputsLancamento.cd_fornecedor = dados.convenio?.cd_fornecedor;
        this.inputsLancamento.documento = dados.cd_agendamento;
        this.inputsLancamento.descricao = 'Rec. Conta Paciente Atend.: ' + dados.cd_agendamento; 

        axios.post('/rpclinica/json/show-tesouraria-item', dados)
            .then((res) => { 
                console.log(res.data.request);
                this.valorConta = res.data.request.valorConta;
                this.inputsConta=res.data.request.dadosConta; 
                this.inputsLancamento.valor_pago = this.inputsConta.vRestante;
                $('#cadastro-consulta').modal('toggle');
            })
            .catch((err) => this.messageDanger = err.response.data.message)
            .finally(() => this.loading = false);
         
    },

    liberarAtendimento(){
        console.log(this.modalCompleto);
        if(this.modalCompleto.recebido == 'S'){ var texto = 'Tem certeza que deseja bloquear esse atendimento?'}
        if(this.modalCompleto.recebido == 'N'){ var texto = 'Tem certeza que deseja liberar esse atendimento?'}
        Swal.fire({ 
            title: 'Confirmação',
            text: texto,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) { 

                this.loading = true;
                axios.post(`/rpclinica/json/liberar-tesouraria-financeiro/${this.modalCompleto.cd_agendamento}`)
                    .then((res) => {
                        console.log(res.data);
                        
                        this.modalCompleto.recebido=res.data.agendamento.recebido;
                        this.modalCompleto.dt_receb=res.data.agendamento.dt_receb;
                        this.modalCompleto.usuario_receb=res.data.agendamento.usuario_receb;

                        this.horarios[this.INDEX].recebido=res.data.agendamento.recebido;
                        this.horarios[this.INDEX].dt_receb=res.data.agendamento.dt_receb;
                        this.horarios[this.INDEX].usuario_receb=res.data.agendamento.usuario_receb;
 
                        toastr["success"]("Conta liberada com sucesso!"); 
                    })
                    .catch((err) => parseErrorsAPI(err))
                    .finally(() => this.loading = false);
            }
        });
    },

    salvarLancamento() {
        console.log(this.inputsLancamento.valor_pago);
        let valor_pago = parseFloat(this.inputsLancamento.valor_pago?.replace('.', '')?.replace(',', '.')); 
        console.log(valor_pago);
        this.loading = true;
        
        this.inputsLancamento.data_vencimento = this.inputsLancamento.data_pagamento; 
        let data = Object.assign({}, this.inputsLancamento);
        data.valor = valor_pago;
        data.valor_pago = valor_pago; 
        data.parcelas.forEach((parcela) => {
            parcela.valor = parcela.valor.replace('.', '').replace(',', '.'); 
            parcela.valor_pago = (parcela.valor_pago) ? parcela.valor_pago?.replace('.', '')?.replace(',', '.') : null;
    
        });

        axios.post('/rpclinica/json/store-tesouraria-financeiro', data)
            .then((res) => {
                 this.clear(); 
                toastr["success"](res.data.message);
                this.modalRecebimento = res.data.retorno.boleto;
                this.horarios[this.INDEX] = res.data.retorno_atend;
                this.resumo = res.data.resumo;  
                this.inputsConta=res.data.request.dadosConta; 
                this.inputsLancamento.valor_pago = this.inputsConta.vRestante; 
                //$('#cadastro-consulta').modal('hide');
            })
            .catch((err) => parseErrorsAPI(err))
            .finally(() => {
                this.loading = false;
                this.inputsLancamento.cd_setor=this.modalCompleto.local?.cd_setor;
                this.inputsLancamento.cd_agendamento = this.modalCompleto.cd_agendamento;
                this.inputsLancamento.cd_convenio = this.modalCompleto.cd_convenio;
                this.inputsLancamento.cd_categoria = this.modalCompleto.convenio?.cd_categoria;
                this.inputsLancamento.cd_fornecedor = this.modalCompleto.convenio?.cd_fornecedor;
                this.inputsLancamento.descricao = 'Rec. Conta Paciente Atend.: ' + this.modalCompleto.cd_agendamento;
            });
    },

    excluirLancamentoFinanceiro(dados) {
         
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir essa parcela?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
 
            if (result.isConfirmed) { 

                this.loading = true;
                axios.delete(`/rpclinica/json/tesouraria-excluir-parcela/${dados.cd_documento_boleto}/${dados.cd_agendamento}`)
                    .then((res) => {
                        toastr["success"]("Parcela excluida com sucesso!");
                        this.modalRecebimento = res.data.retorno.boleto;  
                        this.inputsConta=res.data.request.dadosConta; 
                        this.inputsLancamento.valor_pago = this.inputsConta.vRestante;  
                    })
                    .catch((err) => parseErrorsAPI(err))
                    .finally(() => this.loading = false);
            }

        });
    },

    excluirDesconto() {
         
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir o Desconto?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
 
            if (result.isConfirmed) { 

                this.loading = true;
                axios.delete(`/rpclinica/json/tesouraria-excluir-desconto/${this.inputsLancamento.cd_agendamento}`)
                    .then((res) => {
                        toastr["success"]("Desconto excluido com sucesso!");  
                        this.inputsConta=res.data.request.dadosConta; 
                        this.inputsLancamento.valor_pago = this.inputsConta.vRestante;  
                    })
                    .catch((err) => parseErrorsAPI(err))
                    .finally(() => this.loading = false);
            }

        });
    },
 
    lancarDesconto() {
         
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja conceder desconto de R$ " + this.inputsConta.vRestante + " ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
 
            if (result.isConfirmed) { 
               
                this.loading = true;
                axios.post(`/rpclinica/json/tesouraria-desconto/${this.inputsLancamento.cd_agendamento}`)
                    .then((res) => {
                        toastr["success"]("Desconto concedido com sucesso!");  
                        this.inputsConta=res.data.request.dadosConta; 
                        this.inputsLancamento.valor_pago = this.inputsConta.vRestante;  
                    })
                    .catch((err) => parseErrorsAPI(err))
                    .finally(() => this.loading = false);
                  
            }

        });
    },
 
    getRandomIntInclusive(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    },

    excluirParcela(index) {
        Swal.fire({
            title: 'Confirmação',
            text: "Tem certeza que deseja excluir essa parcela?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                this.inputsLancamento.parcelas.splice(index, 1);
            }
        });
    },

    clear() {  
        $('#lancamentosConta').val(null).trigger('change');
        $('#lancamentosFormaPagamento').val(null).trigger('change');
        //$('#lancamentosMarcas').val(null).trigger('change');
        //$('#lancamentosSetores').val(null).trigger('change'); 
        $('#numeroParcelas').val(1).trigger('change');
        //$('#lancamentosPeriodicidade').val(null).trigger('change'); 

        this.disabledInputsPagamento = false;
        this.inputsLancamento = {
            cd_empresa: null,
            cd_categoria: null,
            cd_conta: null,
            cd_forma: null,
            cd_fornecedor: null,
            cd_setor: null,
            cd_marca: null,
            descricao: null,
            documento: null,
            tp_lancamento: 'receita',
            data_vencimento: null,
            data_compra: null,
            valor: null,
            data_pagamento: null,
            valor_pago: null,
            parcelar: false,
            dividir_parcelas: null,
            periodicidade: null,
            qtde_parcelas: null,
            parcelas: []
        };
  
    },

    FormatData(data) {
        var dt = data.split(" ")[0];
        var dia  = dt.split("-")[2];
        var mes  = dt.split("-")[1];
        var ano  = dt.split("-")[0];
      
        return ("0"+dia).slice(-2) + '/' + ("0"+mes).slice(-2) + '/' + ano;
      
    }, 

    formatValor(valor) {
        return Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(valor);
    }, 

    vlConta(Array,tipo){ 
         
        var vlTotal=0;
        Array.forEach((item) => {
            vlTotal = (parseFloat( ( (item.vl_item) ? item.vl_item : 0 ) ) + parseFloat(vlTotal) );   
        }); 
        if(tipo == 'F'){
            return this.formatValor(vlTotal);
        }else{
            return vlTotal;
        }
    },

    situacaoConta(Array){ 
         
        var situacao='<code style="  font-style: italic; font-weight: 600; color: #0062a5;background-color: #f2f5f9;"> Ok</code>';
        Array.forEach((item) => {
            if(item.vl_item == ''){
                situacao='<code style="  font-style: italic; font-weight: 600"> Pendências</code>';
            }  
        }); 
         
        return situacao;
        
    }, 

    valorFinanceiro(Array,tipo){ 
         
        var vlRec=0; 
        Array.forEach((item) => {
            vlRec = ( parseFloat(item.vl_pagrec) + parseFloat(vlRec) )
        });  
        if(tipo == 'F'){
            return this.formatValor(vlRec);
        }else{
            return vlRec;
        } 
        
    }, 

    difFinanceiro(vlFinan,vlConta){
       if(((vlConta) ? vlConta : 0) > ((vlFinan) ? vlFinan : 0)){
         return '<code style="  font-style: italic; font-weight: 600; "> Pendente</code>';
       }else{
        return '<code style="  font-style: italic; font-weight: 600; color: #0062a5;background-color: #f2f5f9;"> Quitado</code>';
       }
    },

    recalcularConta(dados){
        this.loadingAcao="Recalculando Conta";
        console.log(dados); 
        this.loading = true; 
        axios.post(`/rpclinica/json/tesouraria-recalcular/${dados.cd_agendamento}`)
            .then((res) => {
                console.log(res.data);  
                this.horarios[this.INDEX] = res.data.retorno 
                this.modalConta = res.data.retorno.itens;
                this.modalCompleto =res.data.retorno;
                this.modalRecebimento = res.data.retorno.boleto; 
                this.inputsConta=res.data.request.dadosConta; 
                this.inputsLancamento.valor_pago = this.inputsConta.vRestante; 
                toastr["success"]("Conta recalculada com sucesso");
            })
            .catch((err) => parseErrorsAPI(err)) 
            .finally(() => this.loading = false);
    },
 
    fecharAbrirConta(dados,tipo){ 
        if(tipo=='F'){
            var texto="Tem certeza que deseja fechar essa Conta?";
            var textoAcao = "Fechando a Conta";
            var textoFinal = "Conta fechada com sucesso";
        }
        if(tipo=='A'){
            var texto="Tem certeza que deseja reabrir essa Conta?";
            var textoAcao = "Reabrindo a Conta";
            var textoFinal = "Conta reaberta com sucesso";
        }
        Swal.fire({
            title: 'Confirmação',
            text: texto,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22BAA0',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Não',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
               
                this.loadingAcao=textoAcao;
                console.log(dados); 
                this.loading = true; 
                axios.post(`/rpclinica/json/tesouraria-fechar-conta/${dados.cd_agendamento}/${tipo}`)
                    .then((res) => {
                        console.log(res.data);   
                        this.horarios[this.INDEX] = res.data.retorno 
                        this.modalConta = res.data.retorno.itens;
                        this.modalCompleto =res.data.retorno;
                        this.modalRecebimento = res.data.retorno.boleto; 
                        this.inputsConta=res.data.request.dadosConta; 
                        this.inputsLancamento.valor_pago = this.inputsConta.vRestante; 
                        toastr["success"](textoFinal);
                    })
                    .catch((err) => parseErrorsAPI(err)) 
                    .finally(() => this.loading = false);

            }
        });


    },
 
}));

$(document).ready(function() {
    $.fn.datepicker.dates['en'] = {
        days: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabadao'],
        daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        daysMin: ["Do", "Se", "Te", "Qa", "Qi", "Se", "Sa"],
        months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
    };

    $('#calendar').datepicker({
        language: 'pt',
        format: 'yyyy-mm-dd',


     

    });
   
    $('#cadastro-consulta').modal({
        backdrop: 'static',
        show: false
    });

    $('#agendamento-manual').modal({
        backdrop: 'static',
        show: false
    });
});
