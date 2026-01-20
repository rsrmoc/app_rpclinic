<?php

use App\Http\Controllers\rpclinica\Agendamento;
use App\Http\Controllers\rpclinica\AgendamentoDocumento;
use App\Http\Controllers\rpclinica\Agendas;
use App\Http\Controllers\rpclinica\Comunicacoes;
use App\Http\Controllers\rpclinica\Consulta;
use App\Http\Controllers\rpclinica\Consultorio; 
use App\Http\Controllers\rpclinica\Inicial;
use App\Http\Controllers\rpclinica\Motivo;
use App\Http\Controllers\rpclinica\Pacientes;
use App\Http\Controllers\rpclinica\ProdutoLote;
use App\Http\Controllers\rpclinica\Select2Controller;
use Illuminate\Support\Facades\Route;

Route::get('comunicacao-hook', 'Comunicacoes@hook')->name('comunicacao.hook');

Route::domain(config('app.url'))->group(function () {

    Route::get('login', 'Auth\LoginController@showLoginForm')->name('rpclinica.login');
    Route::post('login-valida', 'Auth\LoginController@valida')->name('rpclinica.valida');
    Route::post('login', 'Auth\LoginController@login')->name('login');
    Route::get('logout', 'Auth\LoginController@logout')->name('rpclinica.logout');
    Route::get('esqueci', 'Auth\LoginController@esqueci')->name('rpclinica.esqueci');
    Route::post('esqueci_email', 'Auth\LoginController@esqueci_email')->name('rpclinica.esqueci_email');

    //painel de chamada
    Route::get('painel-chamada', 'painel_chamada\Painel@index')->name('painel.chamada'); 
    Route::get('painel-toten', 'painel_chamada\toten@index')->name('painel.toten'); 

    Route::group([
        'middleware' => [
            'auth:rpclinica',
            'primeiro_acesso',
            'permissoes_usuario', 
        ],
    ], function () {

        Route::get('menu', 'Inicial@menu')->name('menu');
        Route::get('/', 'Inicial@index')->name('inicio');
        Route::get('inicio', 'Inicial@index')->name('inicio');

        Route::get('config/geral', 'Config@index')->name('config.geral');

        Route::get('sem-permissao', 'Inicial@semPermissao')->name('sem.permissao');

        // alterar senha
        Route::get('usuario-alterar', 'AlteracaoSenha@edit')->name('rpclinica.usuario.alterar');
        Route::post('usuario-alterar-acao', 'AlteracaoSenha@update')->name('rpclinica.usuario.alterar-acao');

        /* Procedimento */ // feito
        Route::post('procedimento-import', 'Procedimentos@import')->name('procedimento.import');
        Route::get('procedimento-listar', 'Procedimentos@index')->name('procedimento.listar');
        Route::get('procedimento-create', 'Procedimentos@create')->name('procedimento.create');
        Route::post('procedimento-store', 'Procedimentos@store')->name('procedimento.store');
        Route::get('procedimento-edit/{procedimento}', 'Procedimentos@edit')->name('procedimento.edit');
        Route::post('procedimento-update/{procedimento}', 'Procedimentos@update')->name('procedimento.update');
        Route::post('procedimento-delete/{procedimento}', 'Procedimentos@delete')->name('procedimento.delete');

        /* Especialidade */ // feito
        Route::get('especialidade-listar', 'Especialidades@index')->name('especialidade.listar');
        Route::get('especialidade-create', 'Especialidades@create')->name('especialidade.create');
        Route::post('especialidade-store', 'Especialidades@store')->name('especialidade.store');
        Route::get('especialidade-edit/{especialidade}', 'Especialidades@edit')->name('especialidade.edit');
        Route::post('especialidade-update/{especialidade}', 'Especialidades@update')->name('especialidade.update');
        Route::post('especialidade-delete/{especialidade}', 'Especialidades@delete')->name('especialidade.delete');

        /* Convenios */ // feito
        Route::post('procedimento-conv-import', 'Convenios@import')->name('procedimento.conv.import');
        Route::get('convenio-listar', 'Convenios@index')->name('convenio.listar');
        Route::get('convenio-create', 'Convenios@create')->name('convenio.create');
        Route::post('convenio-store', 'Convenios@store')->name('convenio.store');
        Route::get('convenio-edit/{convenio}', 'Convenios@edit')->name('convenio.edit');
        Route::post('convenio-update/{convenio}', 'Convenios@update')->name('convenio.update');
        Route::post('convenio-delete/{convenio}', 'Convenios@delete')->name('convenio.delete');

        Route::post('procedimento-convenio-delete/{procedimento}', 'ProcedimentoConvenio@destroy')->name('convenio.delete');

        /* Tipo Atendimento */
        Route::get('tipo-atend-listar', 'TiposAtendimento@index')->name('tipo.atend.listar');
        Route::get('tipo-atend-create', 'TiposAtendimento@create')->name('tipo.atend.create');
        Route::post('tipo-atend-store', 'TiposAtendimento@store')->name('tipo.atend.store');
        Route::get('tipo-atend-edit/{tipo}', 'TiposAtendimento@edit')->name('tipo.atend.edit');
        Route::post('tipo-atend-update/{tipo}', 'TiposAtendimento@update')->name('tipo.atend.update');
        Route::post('tipo-atend-delete/{tipo}', 'TiposAtendimento@delete')->name('tipo.atend.delete');

        /* Local Atendimento */
        Route::get('local-atend-listar', 'LocalAtendimento@index')->name('local.atend.listar');
        Route::get('local-atend-create', 'LocalAtendimento@create')->name('local.atend.create');
        Route::post('local-atend-store', 'LocalAtendimento@store')->name('local.atend.store');
        Route::get('local-atend-edit/{local}', 'LocalAtendimento@edit')->name('local.atend.edit');
        Route::post('local-atend-update/{local}', 'LocalAtendimento@update')->name('local.atend.update');
        Route::post('local-atend-delete/{local}', 'LocalAtendimento@delete')->name('local.atend.delete');

        /* Agenda */
        Route::get('agenda-listar', 'Agendas@index')->name('agenda.listar');
        Route::get('agenda-create', 'Agendas@create')->name('agenda.create');
        Route::post('agenda-store', 'Agendas@store')->name('agenda.store');
        Route::get('agenda-edit/{agenda}', 'Agendas@edit')->name('agenda.edit');
        Route::post('agenda-update/{agenda}', 'Agendas@update')->name('agenda.update');
        Route::post('agenda-delete/{agenda}', 'Agendas@delete')->name('agenda.delete');
        Route::post('agenda-escala-store', 'Agendas@storeEscala')->name('agenda.escala.store');

        /* Conta Bancaria */
        Route::get('conta-bancaria-listar', 'ContasBancarias@index')->name('conta.bancaria.listar');
        Route::get('conta-bancaria-create', 'ContasBancarias@create')->name('conta.bancaria.create');
        Route::post('conta-bancaria-store', 'ContasBancarias@store')->name('conta.bancaria.store');
        Route::get('conta-bancaria-edit/{conta}', 'ContasBancarias@edit')->name('conta.bancaria.edit');
        Route::post('conta-bancaria-update/{conta}', 'ContasBancarias@update')->name('conta.bancaria.update');
        Route::post('conta-bancaria-delete/{conta}', 'ContasBancarias@delete')->name('conta.bancaria.delete');

        /* Cartão de Credito */
        Route::get('cartao-credito-listar', 'CartoesCredito@index')->name('cartao.credito.listar');
        Route::get('cartao-credito-create', 'CartoesCredito@create')->name('cartao.credito.create');
        Route::post('cartao-credito-store', 'CartoesCredito@store')->name('cartao.credito.store');
        Route::get('cartao-credito-edit/{cartao}', 'CartoesCredito@edit')->name('cartao.credito.edit');
        Route::post('cartao-credito-update/{cartao}', 'CartoesCredito@update')->name('cartao.credito.update');
        Route::post('cartao-credito-delete/{cartao}', 'CartoesCredito@delete')->name('cartao.credito.delete');

        /* Forma de Pagamento */
        Route::get('forma-pag-listar', 'FormasPagamento@index')->name('forma.pag.listar');
        Route::get('forma-pag-create', 'FormasPagamento@create')->name('forma.pag.create');
        Route::post('forma-pag-store', 'FormasPagamento@store')->name('forma.pag.store');
        Route::get('forma-pag-edit/{forma}', 'FormasPagamento@edit')->name('forma.pag.edit');
        Route::post('forma-pag-update/{forma}', 'FormasPagamento@update')->name('forma.pag.update');
        Route::post('forma-pag-delete/{forma}', 'FormasPagamento@delete')->name('forma.pag.delete');

        /* Classificação */
        Route::get('classificacao-listar', 'Classificacoes@index')->name('classificacao.listar');
        Route::get('classificacao-create', 'Classificacoes@create')->name('classificacao.create');
        Route::post('classificacao-store', 'Classificacoes@store')->name('classificacao.store');
        Route::get('classificacao-edit/{classificacao}', 'Classificacoes@edit')->name('classificacao.edit');
        Route::post('classificacao-update/{classificacao}', 'Classificacoes@update')->name('classificacao.update');
        Route::post('classificacao-delete/{classificacao}', 'Classificacoes@delete')->name('classificacao.delete');

        /* Produtos */
        Route::get('produto-listar', 'Produtos@index')->name('produto.listar');
        Route::get('produto-create', 'Produtos@create')->name('produto.create');
        Route::post('produto-store', 'Produtos@store')->name('produto.store');
        Route::get('produto-edit/{produto}', 'Produtos@edit')->name('produto.edit');
        Route::post('produto-update/{produto}', 'Produtos@update')->name('produto.update');
        Route::post('produto-delete/{produto}', 'Produtos@delete')->name('produto.delete');

        /* Estoques */
        Route::get('estoque-listar', 'Estoques@index')->name('tab-estoque.listar');
        Route::get('estoque-create', 'Estoques@create')->name('tab-estoque.create');
        Route::post('estoque-store', 'Estoques@store')->name('tab-estoque.store');
        Route::get('estoque-edit/{estoque}', 'Estoques@edit')->name('tab-estoque.edit');
        Route::post('estoque-update/{estoque}', 'Estoques@update')->name('tab-estoque.update');
        Route::post('estoque-delete/{estoque}', 'Estoques@delete')->name('tab-estoque.delete');

        /* Tipo de Ajustes */
        Route::get('tipoaj-ajuste-listar', 'TiposAjuste@index')->name('tipoaj.ajuste.listar');
        Route::get('tipoaj-ajuste-create', 'TiposAjuste@create')->name('tipoaj.ajuste.create');
        Route::post('tipoaj-ajuste-store', 'TiposAjuste@store')->name('tipoaj.ajuste.store');
        Route::get('tipoaj-ajuste-edit/{tipo}', 'TiposAjuste@edit')->name('tipoaj.ajuste.edit');
        Route::post('tipoaj-ajuste-update/{tipo}', 'TiposAjuste@update')->name('tipoaj.ajuste.update');
        Route::post('tipoaj-ajuste-delete/{tipo}', 'TiposAjuste@delete')->name('tipoaj.ajuste.delete');

        /* Empresa */
        Route::get('empresa-listar', 'Empresas@index')->name('empresa.listar');
        Route::get('empresa-create', 'Empresas@create')->name('empresa.create');
        Route::post('empresa-store', 'Empresas@store')->name('empresa.store');
        Route::get('empresa-edit/{empresa}', 'Empresas@edit')->name('empresa.edit');
        Route::post('empresa-update/{empresa}', 'Empresas@update')->name('empresa.update');
        Route::post('empresa-delete/{empresa}', 'Empresas@delete')->name('empresa.delete');
        Route::get('empresa-teste', 'Empresas@teste')->name('empresa.teste');

        /* Comunicacao */
        Route::get('comunicacao-listar', 'Comunicacoes@index')->name('comunicacao.listar');
        Route::post('comunicacao-qr-code', 'Comunicacoes@qr_code')->name('comunicacao.qr');
        Route::post('comunicacao-group', 'Comunicacoes@group')->name('comunicacao.group');
        Route::post('comunicacao-desc', 'Comunicacoes@desconectar')->name('comunicacao.desc');
        Route::get('comunicacao-msg', 'Comunicacoes@msg')->name('comunicacao.msg');
        Route::get('comunicacao-valida', 'Comunicacoes@valida')->name('comunicacao.valida');

        /* Setor */
        Route::get('setor-listar', 'Setores@index')->name('setor.listar');
        Route::get('setor-create', 'Setores@create')->name('setor.create');
        Route::post('setor-store', 'Setores@store')->name('setor.store');
        Route::get('setor-edit/{setor}', 'Setores@edit')->name('setor.edit');
        Route::post('setor-update/{setor}', 'Setores@update')->name('setor.update');
        Route::post('setor-delete/{setor}', 'Setores@delete')->name('setor.delete');

        /* Categoria */
        Route::get('categoria-listar', 'Categorias@index')->name('categoria.listar');
        Route::get('categoria-create', 'Categorias@create')->name('categoria.create');
        Route::post('categoria-store', 'Categorias@store')->name('categoria.store');
        Route::get('categoria-edit/{categoria}', 'Categorias@edit')->name('categoria.edit');
        Route::post('categoria-update/{categoria}', 'Categorias@update')->name('categoria.update');
        Route::post('categoria-delete/{categoria}', 'Categorias@delete')->name('categoria.delete');

        /* Marca */
        Route::get('marca-listar', 'Marcas@index')->name('marca.listar');
        Route::get('marca-create', 'Marcas@create')->name('marca.create');
        Route::post('marca-store', 'Marcas@store')->name('marca.store');
        Route::get('marca-edit/{marca}', 'Marcas@edit')->name('marca.edit');
        Route::post('marca-update/{marca}', 'Marcas@update')->name('marca.update');
        Route::post('marca-delete/{marca}', 'Marcas@delete')->name('marca.delete');

        /* Marca */
        Route::get('financeiro-listar', 'Financeiro@index')->name('financeiro.listar');
        Route::get('financeiro-add', 'Financeiro@add')->name('financeiro.add');
        Route::get('financeiro-cartao', 'Financeiro@cartao')->name('financeiro.cartao');
        Route::get('financeiro-edit/{documentoBoleto}', 'Financeiro@edit')->name('financeiro.edit');

        /* Fornecedor */
        Route::get('fornecedor-listar', 'Fornecedores@index')->name('fornecedor.listar');
        Route::get('fornecedor-create', 'Fornecedores@create')->name('fornecedor.create');
        Route::post('fornecedor-store', 'Fornecedores@store')->name('fornecedor.store');
        Route::get('fornecedor-edit/{fornecedor}', 'Fornecedores@edit')->name('fornecedor.edit');
        Route::post('fornecedor-update/{fornecedor}', 'Fornecedores@update')->name('fornecedor.update');
        Route::post('fornecedor-delete/{fornecedor}', 'Fornecedores@delete')->name('fornecedor.delete');

        /* Usuario */
        Route::get('usuario-listar', 'Usuarios@index')->name('usuario.listar');
        Route::get('usuario-create', 'Usuarios@create')->name('usuario.create');
        Route::get('usuario-edit/{usuario}', 'Usuarios@edit')->name('usuario.edit');
        Route::post('usuario-delete/{usuario}', 'Usuarios@delete')->name('usuario.delete');

        /* Perfil */
        Route::get('perfil-listar', 'Perfis@index')->name('perfil.listar');
        Route::get('perfil-create', 'Perfis@create')->name('perfil.create');
        Route::post('perfil-store', 'Perfis@store')->name('perfil.store');
        Route::get('perfil-edit/{perfil}', 'Perfis@edit')->name('perfil.edit');
        Route::post('perfil-update/{perfil}', 'Perfis@update')->name('perfil.update');
        Route::post('perfil-delete/{perfil}', 'Perfis@delete')->name('perfil.delete');


        Route::get('/pacientes-listar', 'Pacientes@index')->name('paciente.listar');
        Route::get('/pacientes-create', 'Pacientes@create')->name('paciente.create');
        Route::post('/pacientes-store', 'Pacientes@store')->name('paciente.store');
        Route::get('/pacientes-edit/{paciente}', 'Pacientes@edit')->name('paciente.edit');
        Route::post('/pacientes-update/{paciente}', 'Pacientes@update')->name('paciente.update');
        Route::post('/pacientes-delete/{paciente}', 'Pacientes@destroy')->name('paciente.delete');
        Route::post('/pacientes-documento/{paciente}', 'Pacientes@documento')->name('paciente.documento');

        // agendamento
        Route::any('agendamento', 'Agendamento@index')->name('agendamento');
        Route::get('agendamento-show', 'Agendamento@ShowAgendamento')->name('show.agendamento');
        Route::get('agendamento-show-hor', 'Agendamento@horarioAgendamento')->name('show.agendamento.horarios');

        /* Configuração Financeiro  */
        Route::get('config-financeiro', 'ConfigFinan@create')->name('config.finan');
        Route::post('config-financeiro-store', 'ConfigFinan@store')->name('config.financeiro.store');

        // consultorio
        Route::any('consultorio', 'Consultorio@index')->name('consultorio');
        Route::get('consulta/{agendamento}', 'Consulta@show')->name('consulta.show');
        Route::any('consulta/anamnese/download-pdf/{agendamento}', 'Consulta@downloadPDF')->name('anamnese.download.pdf');
        Route::get('consulta-finalizar/{agendamento}', 'Consulta@finalizarConsulta');
        Route::post('consulta-atendimento', 'Consultorio@atendimento')->name('consulta.atendimento');
        Route::post('consulta/doc_padrao', 'Consulta@doc_padrao');
         
        // motivo
        Route::get('motivo-listar', 'Motivo@index')->name('motivo.listar');
        Route::post('motivo-delete/{motivo}', 'Motivo@destroy')->name('motivo.delete');

        // estoqe entrada
        Route::get('estoque-entrada-listar', 'EstoqueEntrada@index')->name('estoque.entrada.listar');
        Route::get('estoque-entrada-create', 'EstoqueEntrada@create')->name('estoque.entrada.create');
        Route::post('estoque-entrada-store', 'EstoqueEntrada@store')->name('estoque.entrada.store');
        Route::get('estoque-entrada-edit/{entrada}', 'EstoqueEntrada@edit')->name('estoque.entrada.edit');
        Route::put('estoque-entrada-update/{entrada}', 'EstoqueEntrada@update')->name('estoque.entrada.update');
        Route::post('estoque-entrada-delete/{entrada}', 'EstoqueEntrada@destroy')->name('estoque.entrada.destroy');

        Route::post('estoque-entrada-prod-delete/{entrada}', 'EstoqueEntProduto@destroy')->name('estoque.entrada.prod.destroy');

        // estoque saida
        Route::get('estoque-saida-listar', 'EstoqueSaida@index')->name('estoque.saida.listar');
        Route::get('estoque-saida-create', 'EstoqueSaida@create')->name('estoque.saida.create');
        Route::post('estoque-saida-store', 'EstoqueSaida@store')->name('estoque.saida.store');
        Route::get('estoque-saida-edit/{saida}', 'EstoqueSaida@edit')->name('estoque.saida.edit');
        Route::put('estoque-saida-update/{saida}', 'EstoqueSaida@update')->name('estoque.saida.update');
        Route::post('estoque-saida-delete/{saida}', 'EstoqueSaida@destroy')->name('estoque.saida.destroy');

        Route::post('estoque-saida-prod-delete/{saida}', 'EstoqueSaidaProduto@destroy')->name('estoque.saida.prod.destroy');

        // devolucao
        Route::get('estoque-devolucao-listar', 'Devolucao@index')->name('estoque.devolucao.listar');
        Route::get('estoque-devolucao-create', 'Devolucao@create')->name('estoque.devolucao.create');
        Route::post('estoque-devolucao-store', 'Devolucao@store')->name('estoque.devolucao.store');
        Route::get('estoque-devolucao-edit/{devolucao}', 'Devolucao@edit')->name('estoque.devolucao.edit');
        Route::put('estoque-devolucao-update', 'Devolucao@update')->name('estoque.devolucao.update');
        Route::post('estoque-devolucao-delete/{devolucao}', 'Devolucao@destroy')->name('estoque.devolucao.destroy');

        // ajustes
        Route::get('estoque-ajuste-listar', 'EstoqueAjuste@index')->name('estoque.ajuste.listar');
        Route::get('estoque-ajuste-create', 'EstoqueAjuste@create')->name('estoque.ajuste.create');
        Route::post('estoque-ajuste-store', 'EstoqueAjuste@store')->name('estoque.ajuste.store');
        Route::get('estoque-ajuste-edit/{ajuste}', 'EstoqueAjuste@edit')->name('estoque.ajuste.edit');
        Route::put('estoque-ajuste-update/{ajuste}', 'EstoqueAjuste@update')->name('estoque.ajuste.update');
        Route::post('estoque-ajuste-destroy/{ajuste}', 'EstoqueAjuste@destroy')->name('estoque.ajuste.destroy');

        Route::post('estoque-ajuste-prod-delete/{ajuste}', 'EstoqueAjusteProduto@destroy')->name('estoque.ajuste-prod.destroy');

        // saldo estoque
        Route::get('estoque-saldo-listar', 'EstoqueSaldo@index')->name('estoque.saldo.listar');

        // perfil profissional
        Route::get('perfil-profissional-listar', 'PerfilProfissional@index')->name('perfil-profi.listar');
        Route::post('perfil-profissional-config', 'PerfilProfissional@storeConfig')->name('perfil-profi.config');
        Route::post('perfil-profissional-certificado', 'PerfilProfissional@storeCertificado')->name('perfil-profi.certificado');
        Route::get('perfil-prof-del-certificado', 'PerfilProfissional@deleteCertificado');

        // Tutorial
        Route::get('tutorial', 'Tutoriais@index')->name('tutorial');


        /* Feriados */
        Route::get('feriados-listar', 'Feriados@index')->name('feriados.listar');
        Route::get('feriados-create', 'Feriados@create')->name('feriados.create');
        Route::post('feriados-store', 'Feriados@store')->name('feriados.store');
        Route::get('feriados-edit/{feriado}', 'Feriados@edit')->name('feriados.edit');
        Route::post('feriados-update/{feriado}', 'Feriados@update')->name('feriados.update');
        Route::post('feriados-delete/{feriado}', 'Feriados@delete')->name('feriados.delete');
        Route::post('feriados-api', 'Feriados@api')->name('feriados.api');


        /* Configuração Financeiro  */
        Route::get('relatorios', 'Relatorios@index')->name('relatorios');
        Route::get('relatorios-relatorios', 'Relatorios@relatorios')->name('relatorios.relatorios');
        Route::get('relatorios-create', 'Relatorios@create')->name('relatorios.add');
        Route::get('relatorios-agendamento', 'Relatorios@agendamento')->name('relatorios.agendamento');
        Route::get('fluxo-caixa', 'Relatorios@fluxo_caixa')->name('relatorios.fluxo.caixa');

        /* Faturamento */
        Route::get('/faturamento-conta', 'Faturamento@contas')->name('faturamento.conta');
        Route::post('faturamento-import', 'Faturamento@import')->name('faturamento.import');

        Route::get('auto-refracao/{agendamento}', 'consultorio_formularios\AutoRefracao@create')->name('auto.refracao.show');
        Route::post('auto-refracao/{agendamento}', 'consultorio_formularios\AutoRefracao@salve')->name('auto.refracao.salve');


        /* Consultorio Oftalmo */  
        Route::get('consultorio-formularios/{agendamento}/{formulario}', 'consultorio_formularios\Consultorio@show')->name('consultorio_oftalmologia.show');

        

         
    });

    Route::group([
        'middleware' => [
            'auth:rpclinica',
            // 'permissoes_usuario'
        ],
        'prefix' => 'json'
    ], function() {

         

        Route::get('/panel-dashboard', [Inicial::class, 'jsonPanel']);
        Route::get('/panel-dashboard-compromisso', [Inicial::class, 'jsonPanelComp']);

        Route::post('/usuario-store', 'Usuarios@jsonStore');
        Route::post('/usuario-update/{usuario}', 'Usuarios@jsonUpdate');
        Route::post('/profissional-store-procedimento', 'Profissionais@storeProcedimento');
        Route::post('/profissional-store-especialidade', 'Profissionais@storeEspecialidade');
        Route::delete('/profissional-delete-procedimento/{procedimento}', 'Profissionais@deleteProcedimento');
        Route::delete('/profissional-delete-especialidade/{especialidade}', 'Profissionais@deleteEspecialidade');

        Route::post('/horarios', [Agendamento::class, 'horariosAgendamentos']);
        Route::post('/horarios-avanc', [Agendamento::class, 'horariosAgendaAvanc']);
        Route::get('/jsonTeste', [Agendamento::class, 'jsonTeste']);


        Route::post('/agenda/horarios', [Agendas::class, 'horarios']);
        Route::any('/agenda/horarios/gerar-agendamentos', [Agendas::class, 'gerarAgendamentos']);
        Route::post('/agenda/horarios/pesquisa-exclusao', [Agendas::class, 'pesquisaExclusao']);
        Route::post('/agenda/horarios/adicionar-data-exclusao', [Agendas::class, 'adicionarDataExclusao']);
        Route::post('/agenda/horarios/excluir-datas', [Agendas::class, 'excluirAgendamentosGerados']);

        // pacientes
        Route::get('/paciente', [Pacientes::class, 'jsonShowPaciente']);
        Route::get('/pacientes', [Pacientes::class, 'jsonIndexPacientes']);
        Route::post('paciente-iniciar-consulta', 'Pacientes@jsonIniciarConsulta');
        Route::post('paciente-vip', 'Pacientes@storeVip');
        Route::post('/pacientes-update-join', 'Pacientes@updateJson')->name('paciente.update.json');
        Route::any('pacientes/documentos/download-pdf', 'Pacientes@downloadPDFDocumento')->name('anamnese.download.documentos.pdf');

        // agendamentos
        Route::post('/agendamento', [Agendamento::class, 'agendamentoManual']);
        Route::put('/agendamento', [Agendamento::class, 'updateAgendamento']);
        Route::delete('/agendamento/{agendamento}', [Agendamento::class, 'destroyAgendamento']);
        Route::post('/horario-livre', [Agendamento::class, 'GetHorariosLivre']);
        Route::post('/reagendamento', [Agendamento::class, 'Reagendamento']);
        Route::post('/reagendamento-manual', [Agendamento::class, 'ReagendamentoManual']);
        Route::post('/horario-sessao', [Agendamento::class, 'GetHorariosSessao']);
        Route::get('/dados-agenda', [Agendamento::class, 'jsonShowAgenda']);
        Route::get('/dados-table', [Agendamento::class, 'jsonShowTable']);
        Route::post('/agendamento/resources', [Agendamento::class, 'jsonShowResources']);
        Route::put('/atualiza-status-agendamento', [Agendamento::class, 'updateStatus']);
        Route::get('/agendamento/conteudo-evento', [Agendamento::class, 'viewEvento']);
        Route::post('/agendamento/recebimento', [Agendamento::class, 'recebimento']);
        Route::get('/agendamento/conteudo-tp-atend', [Agendamento::class, 'viewTipoAtend']);
        Route::post('/escala_manual', [Agendamento::class, 'escalaManual']);
        Route::delete('/escala_manual/{escala}', [Agendamento::class, 'destroyEscalaManual']);
        Route::post('/agendamento/gravar-agenda-rapido', [Agendamento::class, 'storeAgendaRapido']);



        Route::post('/horario-confirm', [Agendamento::class, 'getHorConf']);
        Route::post('/send-horario', [Agendamento::class, 'sendHorConf']);

        Route::get('/valida-whast', [Comunicacoes::class, 'agendaValidaWhast']);
        Route::get('/logs-whast', [Agendamento::class, 'logsAgendamento']);

        Route::get('/agendamento-dados-mes/{data}', [Agendamento::class, 'agendamentoDadosMes']);

        Route::post('/agendamento-sessao', [Agendamento::class, 'agendamentoSessao']);

        Route::post('/agendamento/bloquear-horario', [Agendamento::class, 'bloquearHorario']);
        Route::post('/agendamento/desbloquear-horario', [Agendamento::class, 'desbloquearHorario']);
        Route::post('/agendamento/alterar-horario', [Agendamento::class, 'alterarHorario']);
        Route::post('/agendamento/add-conta-proc', [Agendamento::class, 'addProcConta']);
        Route::delete('/agendamento/delete-conta-proc/{Codigo}', [Agendamento::class, 'destroyProcConta']);
        Route::put('/agendamento/confere-conta-proc', [Agendamento::class, 'confereProcConta']);

        Route::get('/dados-agenda', [Agendamento::class, 'dadosAgenda']);

        // consulta
        Route::post('consulta/documento/{agendamento}', [AgendamentoDocumento::class, 'jsonStore']);
        Route::put('consulta/documento/{documento}', [AgendamentoDocumento::class, 'jsonUpdate']);
        Route::delete('consulta/documento/delete/{documento}', [AgendamentoDocumento::class, 'jsonDestroy']);

        Route::post('consulta/triagem/{agendamento}', 'Consulta@jsonSaveTriagem');
        Route::post('consulta/anamnese/{agendamento}', 'Consulta@jsonSaveAnamnese');
        Route::post('consulta/problemas/{agendamento}', 'Consulta@jsonSaveListaProblemas');
        Route::post('consulta/exames/{agendamento}', 'Consulta@jsonSaveHistoricoExames');

        Route::post('/pesquisa/paciente', [Consultorio::class, 'pesquisaPac']);
        Route::any('/pesquisa/atendimento', [Consultorio::class, 'pesquisaAtend']);
        Route::post('/pesquisa/paciente/atendimento', [Consultorio::class, 'pesquisaPacAtend']);
        Route::get('/pesquisa/pesquisa-pac', [Consultorio::class, 'pesquisaAtendimento']);
        Route::post('/consulta/reabrir', [Consultorio::class, 'reabrirAtendimento']);

        // motivos
        Route::post('motivos', [Motivo::class, 'store']);
        Route::put('motivos', [Motivo::class, 'update']);

        // estoques
        Route::post('produto-lote', [ProdutoLote::class, 'store']);

        Route::get('estoque-saida/{cd_solicitacao}', 'EstoqueSaida@jsonShow');

        // perfil profissional
        Route::post('perfil-profissional-formulario-create', 'PerfilProfissional@jsonCreateFormulario');
        Route::put('perfil-profissional-formulario-update', 'PerfilProfissional@jsonUpdateFormulario');
        Route::delete('perfil-profissional-formulario-delete/{formulario}', 'PerfilProfissional@jsonDeleteFormulario');

        Route::post('perfil-profissional-procedimento-create', 'PerfilProfissional@jsonCreateProcedimento');
        Route::put('perfil-profissional-procedimento-update', 'PerfilProfissional@jsonUpdateProcedimento');

        Route::post('perfil-profissional-especialidade-create', 'PerfilProfissional@jsonCreateEspecialidade');
        Route::put('perfil-profissional-especialidade-update', 'PerfilProfissional@jsonUpdateEspecialidade');

        // select2 - dados dinamicos de campos selects
        Route::group([
            'prefix' => 'select2'
        ], function() {
            Route::get('cid', [Select2Controller::class, 'cid']);
        });

        // agenda: especialidade, procedimentro, profissional, locais, convenios

        Route::post('/agenda-especialidade', [Agendas::class, 'jsonAddEspecialidade']);
        Route::delete('/agenda-especialidade/{cd_agenda_espec}', [Agendas::class, 'jsonDeleteEspecialidade']);

        Route::post('/agenda-procedimento', [Agendas::class, 'jsonAddProcedimento']);
        Route::delete('/agenda-procedimento/{cd_agenda_proc}', [Agendas::class, 'jsonDeleteProcedimento']);
        Route::delete('/delete-escala/{cd_escala}', [Agendas::class, 'jsonDeleteEscala']);

        Route::post('/agenda-profissional', [Agendas::class, 'jsonAddProfissional']);
        Route::delete('/agenda-profissional/{cd_agenda_proc}', [Agendas::class, 'jsonDeleteProfissional']);

        Route::post('/agenda-local', [Agendas::class, 'jsonAddLocal']);
        Route::delete('/agenda-local/{cd_agenda_proc}', [Agendas::class, 'jsonDeleteLocal']);

        Route::post('/agenda-convenio', [Agendas::class, 'jsonAddConvenio']);
        Route::delete('/agenda-convenio/{cd_agenda_conv}', [Agendas::class, 'jsonDeleteConvenio']);

        Route::get('/agendamento-anexos/{agendamento}', [Consulta::class, 'indexAnexos']);
        Route::post('/agendamento-anexos/{cd_agendamento}', [Consulta::class, 'storeAnexos'])->name('agendamento-anexos');
        Route::delete('/agendamento-anexos/{cd_anexo}', [Consulta::class, 'destroyAnexos']);
        Route::post('/assinatura-digital', [Consulta::class, 'assinaturaDigital']);
        Route::delete('/assinatura-delete/{agendamento}/{tipo}/{codigo}', [Consulta::class, 'deleteAssinatura']);
        Route::get('/historico-documento', [Consulta::class, 'historicoDocumento']);

        Route::get('/graficos-inicio', [Inicial::class, 'grafico']);

        /* Financeiro */
        Route::get('/financeiro-relacao', 'Financeiro@relacao');
        Route::post('/financeiro-store-lancamento', 'Financeiro@addLancamento');
        Route::post('/financeiro-update-lancamento', 'Financeiro@updateLancamento');
        Route::post('/financeiro-store-lancamento-transferencia', 'Financeiro@addLancamentoTransferencia');
        Route::delete('/financeiro-excluir-parcela/{cdDocumentoBoleto}', 'Financeiro@excluirParcela');
        Route::put('/financeiro-update-lancamento-parcelas', 'Financeiro@updateLancamentoParcelas');
        Route::get('/financeiro-relacao-cartao', 'Financeiro@relacaoCartao');
        Route::post('/financeiro-fechar-cartao', 'Financeiro@fecharFaturaCartao');
        Route::post('/financeiro-atualizar-cartao', 'Financeiro@pagarFaturaCartao');
        Route::post('/financeiro-abrir-cartao', 'Financeiro@abrirFaturaCartao');


        Route::post('/faturamento-itens-conta', 'Faturamento@jsonItensConta');


    });

});
