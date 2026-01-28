const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        postCss: [
            require('tailwindcss'),
        ]
    });

mix.js('resources/js/rpclinica/usuarios.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/agendamentos.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/agendamento-lista.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/recepcao.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/agendamentos-callendar.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/agendamento-agenda.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/consultorio.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/consulta.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/motivo.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/estoque-entrada.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/estoque-saida.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/estoque-devolucao.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/estoque-ajuste.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/convenio.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/agenda.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/perfil-profissional_atual.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/paciente-listar.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/agenda-listar.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/consulta-dropzone.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/inicial-dashboard-exames.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/inicial-dashboard-consultorio.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/comunicacao.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/financeiro-cadastro.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/financeiro-edicao.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/financeiro-listar.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/financeiro-cartao.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/fluxo-caixa.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/forma-pag-cadastro.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/faturamento-listar.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/consultorio-paciente.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/consultorio-documento.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/consultorio-anamnese.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/consultorio-receita.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/consultorio-oftalmologia.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/reserva-cirurgia.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/central-de-laudos.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/pre-exame.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/tesouraria.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/relatorios-add.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/relatorios-edit.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/profissional.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/atendimento-add.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/atendimento.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/cirurgias.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/exame.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/logs-envio.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/consultorio-geral_novo.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/empresa.js', 'public/js/rpclinica');
mix.js('resources/js/rpclinica/escala_medica.js', 'public/js/rpclinica');


mix.sass('resources/sass/login.scss', 'public/css');

// App
mix.js('resources/js/app_rpclinica/perfil.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/agendamento.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/documentos.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/paciente-add.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/paciente-list.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/paciente-edit.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/paciente-add-doc.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/consulta-list.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/consulta-paciente.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/disponibilidade.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/producao.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/indicadores.js', 'public/js/app_rpclinica');
mix.js('resources/js/app_rpclinica/escala.js', 'public/js/app_rpclinica');
