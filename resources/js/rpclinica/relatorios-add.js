import axios from 'axios';
import moment from 'moment';

Alpine.data('add_relatorios', function () {
    return {
        relatorios: [],
        relatorio_campos: [],
        relatorio_parametros: [],
        relatorio_calculos: [],
        relatorio_ordem: [],
        campos_tabela: [],
        tbody_campos: $('#tbody-campos'),
        init: function init() {
            $('select[name="conteudo"]').on('select2:select', function (e) {
                const conteudo = e.params.data.id;
                const selectCampos = $('select[name="c_campos"]');
                const selectCalculos = $('select[name="ca_campos"]');
                const selectParametros = $('select[name="p_campos"]');
                const selectOrdenar = $('select[name="o_campos"]');

                if(conteudo != ''){
                    axios.get('/rpclinica/json/relatorios/conteudo-view', {
                        params: {
                            conteudo: conteudo
                        }
                    }).then(response => {
                        this.campos_tabela = response.data.colunas;

                        this.campos_tabela.forEach(campo => {
                            const optionCampos = new Option(campo, campo, false, false);
                            const optionCalculos = new Option(campo, campo, false, false);
                            const optionParametros = new Option(campo, campo, false, false);
                            const optionOrdenar = new Option(campo, campo, false, false);

                            selectCampos.append(optionCampos).trigger('change');
                            selectCalculos.append(optionCalculos).trigger('change');
                            selectParametros.append(optionParametros).trigger('change');
                            selectOrdenar.append(optionOrdenar).trigger('change');
                        });
                    });
                }

            });
        },
        insertCampos: function insertCampos(){
            const nome_coluna = $('select[name="c_campos"]').val();
            const alinhamento = $('select[name="c_alinhamento"]').val();
            const mascara = $('select[name="c_mascara"]').val();
            const limite = $('input[name="c_limite"]').val();
            const tipo_relatorio = $('select[name="tipo_relatorio"]').val();

            if ((tipo_relatorio == 'GCOLC' || tipo_relatorio == 'GCOL' || tipo_relatorio == 'GPIZ') && this.relatorio_campos.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'O tipo de relatório selecionado só permite um campo!',
                });
                return;
            }

            if(nome_coluna == '' || alinhamento == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Preencha todos os campos!',
                });
                return;
            }

            this.relatorio_campos.push({
                nome_coluna,
                alinhamento,
                mascara,
                limite: limite ?? 0
            });

            let mask = '';

            if(mascara == 'dt'){
                mask = 'Data';
            } else if(mascara == 'co'){
                mask = 'Competência';
            } else if(mascara == 'dthr'){
                mask = 'Data e Hora';
            } else if(mascara == 'hr'){
                mask = 'Hora';
            } else if(mascara == 'mo'){
                mask = 'Moeda';
            } else if(mascara == 'int'){
                mask = 'Inteiro';
            }

            this.tbody_campos.append(`
                <tr>
                    <td>${nome_coluna}</td>
                    <td>${alinhamento}</td>
                    <td>${mask}</td>
                    <td>${limite ?? 0}</td>
                    <td>
                        <button @click="removeCampo(${this.relatorio_campos.length - 1})" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        },
        removeCampo: function removeCampo(index){
            this.relatorio_campos.splice(index, 1);
            this.tbody_campos.find('tr').eq(index).remove();
        },
        insertParametros: function insertParametros(){
            const nome_coluna = $('select[name="p_campos"]').val();
            const operador = $('select[name="p_operador"]').val();
            const obrigatorio = $('select[name="p_obrigatorio"]').val();
            const p_padrao = $('select[name="p_padrao"]').val();

            if(nome_coluna == '' || operador == '' || obrigatorio == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Preencha todos os campos!',
                });
                return;
            }

            this.relatorio_parametros.push({
                nome_coluna,
                operador,
                obrigatorio,
                p_padrao
            });

            $('#tbody-parametros').append(`
                <tr>
                    <td>${nome_coluna}</td>
                    <td>${this.findOptionLabelByValue('p_operador', operador)}</td>
                    <td>${obrigatorio == 'S' ? 'Sim' : 'Não'}</td>
                    <td>${p_padrao}</td>
                    <td class="text-center">
                        <button @click="removeParametro(${this.relatorio_parametros.length - 1})" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        },
        removeParametro: function removeParametro(index){
            this.relatorio_parametros.splice(index, 1);
            $('#tbody-parametros').find('tr').eq(index).remove();
        },
        insertCalculos: function insertCalculos(){
            const nome_coluna = $('select[name="ca_campos"]').val();
            const funcao = $('select[name="ca_funcao"]').val();

            if(nome_coluna == '' || funcao == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Preencha todos os campos!',
                });
                return;
            }

            const tipo_relatorio = $('select[name="tipo_relatorio"]').val();

            if ((tipo_relatorio == 'GCOL' || tipo_relatorio == 'GPIZ') && this.relatorio_calculos.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'O tipo de relatório selecionado só permite um campo!',
                });
                return;
            }

            if (tipo_relatorio == 'GCOLC' && this.relatorio_calculos.length > 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'O tipo de relatório selecionado só permite dois campos!',
                });
                return;
            }

            this.relatorio_calculos.push({
                nome_coluna,
                funcao
            });

            $('#tbody-calculos').append(`
                <tr>
                    <td>${nome_coluna}</td>
                    <td>${this.findOptionLabelByValue('ca_funcao', funcao)}</td>
                    <td>
                        <button @click="removeParametro(${this.relatorio_calculos.length - 1})" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        },

        removeCalculo: function removeCalculo(index){
            this.relatorio_calculos.splice(index, 1);
            $('#tbody-calculos').find('tr').eq(index).remove();
        },

        insertOrdenar: function insertOrdenar(){
            const nome_coluna = $('select[name="o_campos"]').val();
            const tipo = $('select[name="o_tipo"]').val();

            if(nome_coluna == '' || tipo == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Preencha todos os campos!',
                });
                return;
            }

            this.relatorio_ordem.push({
                nome_coluna,
                tipo
            });

            $('#tbody-ordenar').append(`
                <tr>
                    <td>${nome_coluna}</td>
                    <td>${tipo == 'asc' ? 'Crescente' : 'Descendente'}</td> 
                    <td>
                        <button @click="removeOrdem(${this.relatorio_ordem.length - 1})" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td> 
                </tr>
            `);
        },

        removeOrdem: function removeOrdem(index){
            this.relatorio_ordem.splice(index, 1);
            $('#tbody-ordenar').find('tr').eq(index).remove();
        },

        createRelatorio: function createRelatorio(){
            const titulo = $('input[name="titulo"]').val();
            const area = $('select[name="area"]').val();
            const conteudo = $('select[name="conteudo"]').val();
            const tipo_relatorio = $('select[name="tipo_relatorio"]').val();
            const layout = $('select[name="layout"]').val();
            const restricao = $('select[name="restricao"]').val();
            
            if(titulo == '' || area == '' || conteudo == '' || tipo_relatorio == '' || layout == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Preencha todos os campos!',
                });
                return;
            }

            if ((tipo_relatorio == 'GCOLC' || tipo_relatorio == 'GCOL' || tipo_relatorio == 'GPIZ') && this.relatorio_campos.length > 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'O tipo de relatório selecionado só permite um campo!',
                });
                return;
            }

            if ((tipo_relatorio == 'GCOL' || tipo_relatorio == 'GPIZ') && this.relatorio_calculos.length > 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'O tipo de relatório selecionado só permite um campo de calculo!',
                });
                return;
            }

            if (tipo_relatorio == 'GCOLC' && this.relatorio_calculos.length > 2) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'O tipo de relatório selecionado só permite dois campos de calculo!',
                });
                return;
            }

            if(this.relatorio_campos.length == 0){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Adicione pelo menos um campo!',
                });
                return;
            }

            if(this.relatorio_parametros.length == 0){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Adicione pelo menos um parâmetro!',
                });
                return;
            }

            if(this.relatorio_ordem.length == 0){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Adicione pelo menos um campo para ordenar!',
                });
                return;
            }

            axios.post('/rpclinica/json/relatorios', {
                titulo,
                area,
                conteudo,
                tipo_relatorio,
                layout,
                restricao, 
                relatorio_campos: this.relatorio_campos,
                relatorio_parametros: this.relatorio_parametros,
                relatorio_calculos: this.relatorio_calculos,
                relatorio_ordem: this.relatorio_ordem
            }).then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Relatório criado com sucesso!',
                });
            }).catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Erro ao criar relatório!',
                });
            });
        },

        findOptionLabelByValue: function findOptionLabelByValue(selectName, value) {
            // Seleciona todos os elementos option dentro do select com o nome fornecido
            var options = document.querySelectorAll(`select[name="${selectName}"] option`);

            // Itera sobre os elementos option
            for (var i = 0; i < options.length; i++) {
                if (options[i].value === value) {
                    // Retorna o texto do option correspondente
                    return options[i].text;
                }
            }

            // Se não encontrar, retorna null ou uma mensagem indicando que não encontrou
            return null;
        },
        formatValor: function formatValor(valor) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(valor);
        },
        formatData: function formatData(data) {
            return new Intl.DateTimeFormat('pt-BR').format(new Date(data));
        }
    };
});
$(document).ready(function () {
    $('#modal-parcela').modal({
        backdrop: 'static',
        show: false
    });
});
/******/
 
