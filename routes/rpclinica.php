<?php

use App\Http\Controllers\rpclinica\Agendamento;
use App\Http\Controllers\rpclinica\AgendamentoDocumento;
use App\Http\Controllers\rpclinica\Agendamentos;
use App\Http\Controllers\rpclinica\AgendamentosLista;
use App\Http\Controllers\rpclinica\Agendas;
use App\Http\Controllers\rpclinica\Atendimentos;
use App\Http\Controllers\rpclinica\Comunicacoes;
use App\Http\Controllers\rpclinica\Consulta;
use App\Http\Controllers\rpclinica\Consultorio;
use App\Http\Controllers\rpclinica\Inicial;
use App\Http\Controllers\rpclinica\Motivo;
use App\Http\Controllers\rpclinica\Pacientes;
use App\Http\Controllers\rpclinica\ProdutoLote;
use App\Http\Controllers\rpclinica\Select2Controller;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\TestController;

use App\Http\Controllers\rpclinica\FluxoCaixa;
use App\Http\Controllers\rpclinica\logEnvios;
use App\Http\Controllers\rpclinica\Procedimentos;
use App\Http\Controllers\rpclinica\Relatorios;

Route::get('comunicacao-hook', 'Comunicacoes@hook')->name('comunicacao.hook');


    Route::group([], function () {

    Route::get('login', 'Auth\LoginController@showLoginForm')->name('rpclinica.login');
    Route::any('login-valida', 'Auth\LoginController@valida')->name('rpclinica.valida');
    Route::post('login', 'Auth\LoginController@login')->name('login');
    Route::get('logout', 'Auth\LoginController@logout')->name('rpclinica.logout');
    Route::get('esqueci', 'Auth\LoginController@esqueci')->name('rpclinica.esqueci');
    Route::post('esqueci_email', 'Auth\LoginController@esqueci_email')->name('rpclinica.esqueci_email');

    //link Laudo
    Route::get('laudo-paciente/{exame}/{key}', 'CentralLaudos@laudo_externo')->name('rpclinica.laudo.paciente');
    Route::get('laudo-teste', 'CentralLaudos@teste')->name('rpclinica.laudo.teste');

    //CronTab
    Route::get('crontab-laudos/{key}', 'cronTab@laudos'); 
    Route::get('crontab-grupo/{key}/{empresa}', 'cronTab@whast_grupo'); 
    Route::get('crontab-aws', 'cronTab@aws'); 
    Route::get('crontab-ajuste-agenda', 'cronTab@ajuste_agenda'); 

    //Files
    Route::get('files-arquivos/{empresa}/{key}', 'Files@arquivos'); 
    Route::get('files-validar-arquivos/{empresa}/{key}', 'Files@validar_caminho');  
    Route::get('files-mover-file-s3/{empresa}/{key}', 'Files@mover_file_s3'); 

    
    
    
    //painel de chamada
    Route::get('painel-chamada', 'painel_chamada\Painel@index')->name('painel.chamada');
    Route::get('painel-toten', 'painel_chamada\toten@index')->name('painel.toten');
    Route::get('aws-teste', 'consultorio\ConsultorioGeral@teste_aws')->name('aws.teste'); 

    Route::group([
        'middleware' => [
            'auth:rpclinica',
            'primeiro_acesso',
            'permissoes_usuario',
        ],
    ], function () {

        Route::get('aws', 'Inicial@aws')->name('aws');
        Route::post('aws2', 'Inicial@store_aws')->name('aws2');

        Route::get('menu', 'Inicial@menu')->name('menu');
        Route::get('/', 'Inicial@index')->name('inicio');
        Route::get('inicio', 'Inicial@index')->name('inicio');
        Route::get('inicio-xls-laudo/{data}/{tipo}', 'Inicial@xls_laudo')->name('inicio.xls.laudo');
        
        Route::get('config/geral', 'Config@index')->name('config.geral');

        Route::get('sem-permissao', 'Inicial@semPermissao')->name('sem.permissao');

        // alterar senha
        Route::get('usuario-alterar', 'AlteracaoSenha@edit')->name('rpclinica.usuario.alterar');
        Route::post('usuario-alterar-acao', 'AlteracaoSenha@update')->name('rpclinica.usuario.alterar-acao');

        // logs rotina whast
        Route::get('logs-rotina-whast', 'logEnvios@index')->name('logs.rotina.whast'); 
        Route::post('rotina-whast-lote', 'logEnvios@sendLote')->name('logs.rotina.lote'); 
        Route::get('logs-rotina-whast-rotina', 'logEnvios@getRotina')->name('logs.rotina.manual');

        /* Procedimento */ // feito
        Route::post('procedimento-import', 'Procedimentos@import')->name('procedimento.import');
        Route::get('procedimento-listar', 'Procedimentos@index')->name('procedimento.listar');
        Route::get('procedimento-create', 'Procedimentos@create')->name('procedimento.create');
        Route::post('procedimento-store', 'Procedimentos@store')->name('procedimento.store');
        Route::get('procedimento-edit/{procedimento}', 'Procedimentos@edit')->name('procedimento.edit');
        Route::post('procedimento-update/{procedimento}', 'Procedimentos@update')->name('procedimento.update');
        Route::post('procedimento-delete/{procedimento}', 'Procedimentos@delete')->name('procedimento.delete');

        /* Profissional */ // feito
        Route::get('profissional-listar', 'Profissionais@index')->name('profissional.listar');
        Route::get('profissional-create', 'Profissionais@create')->name('profissional.create');
        Route::post('profissional-store', 'Profissionais@store')->name('profissional.store');
        Route::get('profissional-edit/{profissional}', 'Profissionais@edit')->name('profissional.edit');
        Route::post('profissional-update/{profissional}', 'Profissionais@update')->name('profissional.update');
        Route::post('profissional-delete', 'Profissionais@delete')->name('profissional.delete');
        Route::delete('profissional-assinatura/{profissional}', 'Profissionais@deleteAssinatura')->name('profissional.assinatura');

        /* Grupo de Procedimento */ // feito
        Route::get('grupo-procedimento-listar', 'GrupoProcedimentos@index')->name('grupo.procedimento.listar');
        Route::get('grupo-procedimento-create', 'GrupoProcedimentos@create')->name('grupo.procedimento.create');
        Route::post('grupo-procedimento-store', 'GrupoProcedimentos@store')->name('grupo.procedimento.store');
        Route::get('grupo-procedimento-edit/{grupo}', 'GrupoProcedimentos@edit')->name('grupo.procedimento.edit');
        Route::post('grupo-procedimento-update/{grupo}', 'GrupoProcedimentos@update')->name('grupo.procedimento.update');
        Route::post('grupo-procedimento-delete/{grupo}', 'GrupoProcedimentos@delete')->name('grupo.procedimento.delete');

        /* Escala Localidade */ // feito
        Route::get('localidade-listar', 'Localidades@index')->name('localidade.listar');
        Route::get('localidade-create', 'Localidades@create')->name('localidade.create');
        Route::post('localidade-store', 'Localidades@store')->name('localidade.store');
        Route::get('localidade-edit/{localidade}', 'Localidades@edit')->name('localidade.edit');
        Route::post('localidade-update/{localidade}', 'Localidades@update')->name('localidade.update');
        Route::post('localidade-delete/{localidade}', 'Localidades@delete')->name('localidade.delete');

        /* Escala Tipo */ // feito
        Route::get('escala-tipo-listar', 'EscalasTipos@index')->name('escala-tipo.listar');
        Route::get('escala-tipo-create', 'EscalasTipos@create')->name('escala-tipo.create');
        Route::post('escala-tipo-store', 'EscalasTipos@store')->name('escala-tipo.store');
        Route::get('escala-tipo-edit/{tipo}', 'EscalasTipos@edit')->name('escala-tipo.edit');
        Route::post('escala-tipo-update/{tipo}', 'EscalasTipos@update')->name('escala-tipo.update');
        Route::post('escala-tipo-delete/{tipo}', 'EscalasTipos@delete')->name('escala-tipo.delete');
 
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
        Route::post('convenio-repasse/{convenio}', 'Convenios@repasse')->name('convenio.repasse');
        Route::get('convenio-delete-repasse/{cd_procedimento_repasse}', 'Convenios@repasseDelete')->name('convenio.delete.repasse');

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
        Route::post('empresa-update-conf/{empresa}', 'Empresas@updateConf')->name('empresa.update.config');
        Route::post('empresa-update-msg/{empresa}', 'Empresas@msg')->name('empresa.update.msg');
        Route::post('empresa-delete/{empresa}', 'Empresas@delete')->name('empresa.delete');
        Route::get('empresa-teste', 'Empresas@teste')->name('empresa.teste');
        Route::delete('empresa-delete-img-pesq/{empresa}', 'Empresas@delete_img_pesquisa')->name('empresa.delete.img.pesq');

        /* Comunicacao */
        Route::get('comunicacao-listar', 'Comunicacoes@index')->name('comunicacao.listar');
        Route::get('comunicacao-desc', 'Comunicacoes@desconectar')->name('comunicacao.desc');
        Route::get('comunicacao-check/{number}', 'Comunicacoes@CheckNumber')->name('comunicacao.check');

        
        Route::post('comunicacao-qr-code', 'Comunicacoes@qr_code')->name('comunicacao.qr');
        Route::post('comunicacao-group', 'Comunicacoes@group')->name('comunicacao.group');
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

        /* Financeiro */
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
        Route::get('get-agendamento', 'Agendamento@getAgendamento')->name('get.atendimento');


        // agendamento Novos
        Route::any('agendamentos', 'Agendamentos@index')->name('agendamentos');
        Route::any('agendamentos-show', 'Agendamentos@ShowAgendamento')->name('show.agendamentos');
        Route::any('agendamentos-show2', 'Agendamentos@ShowAgendamento2')->name('show.agendamentos2');

        // agendamento Lista
        Route::any('agendamentos-lista', 'AgendamentosLista@index')->name('agendamentos.lista');
        Route::any('agendamentos-lista-show', 'AgendamentosLista@show')->name('agendamentos.lista.show');
        Route::post('agendamentos-lista-bloqueio-store', 'AgendamentosLista@storeBloqueio')->name('agenda.bloqueio.store');

        // Escala Medica
        Route::get('escala-medica', 'EscalasMedica@index')->name('escala.medica');
        Route::post('json-escala-medica', 'EscalasMedica@json')->name('escala.medica.json');
        Route::post('json-escala-medica-store', 'EscalasMedica@storeEscala')->name('escala.medica.json.store');
        Route::post('json-escala-medica-update/{escala}', 'EscalasMedica@updateEscala')->name('escala.medica.json.update');
        Route::get('/json-escala-medica-prof/{profissional}', 'EscalasMedica@getProfissional')->name('escala.medica.json.prof');
        Route::post('json-escala-medica-check', 'EscalasMedica@checkEscala')->name('escala.medica.json.check'); 

        // Escala Medica
// Route::get('producao-medica', 'ProducaoMedica@index')->name('producao.medica');
        
        // recepção
        Route::any('recepcao', 'Recepcao@index')->name('recepcao');
        Route::any('recepcao-store-atendimento/{agendamento}/{paciente}', 'Recepcao@storeAtend')->name('recepcao-store-atendimento');
        Route::get('/recepcao-etiqueta/{agendamento}','Recepcao@etiqueta')->name('recepcao.etiqueta.pdf');
        Route::get('/recepcao-ficha/{agendamento}','Recepcao@ficha')->name('recepcao.ficha.pdf');

        //Atendimento
        Route::get('atendimento', 'Atendimentos@index')->name('atendimento'); 
        Route::get('atendimento-add', 'Atendimentos@add')->name('atendimento.add'); 
        Route::post('atendimento-create', 'Atendimentos@create')->name('atendimento.create'); 
        Route::get('/atendimento-edit/{atendimento}', 'Atendimentos@edit')->name('atendimento.edit');
        Route::post('/atendimento-update/{atendimento}', 'Atendimentos@update')->name('atendimento.update'); 

        // tesouraria
        Route::any('tesouraria', 'Tesouraria@index')->name('tesouraria'); 

        //Origem
        Route::any('store-origem', 'Origens@store')->name('store-origem');

        //Prof. Externo
        Route::any('store-profissional-externo', 'ProfissionalExterno@store')->name('store-profissional-externo');

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
        Route::get('perfil-prof-del-certificado', 'PerfilProfissional@deleteCertificado')->name('perfil.prof.del.certificado');

         
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

        /* Exames */
        Route::get('exame-listar', 'Exames@index')->name('exame.listar');
        Route::get('exame-create', 'Exames@create')->name('exame.create');
        Route::post('exame-store', 'Exames@store')->name('exame.store');
        Route::get('exame-edit/{exame}', 'Exames@edit')->name('exame.edit');
        Route::post('exame-update/{exame}', 'Exames@update')->name('exame.update');
        Route::post('exame-delete/{exame}', 'Exames@delete')->name('exame.delete');
        Route::get('exame-formulario/{exame}', 'Exames@formulario')->name('exame.formulario');
        Route::post('exame-formulario-store/{exame}', 'Exames@formularioStore')->name('exame.formulario.store');
        Route::post('exame-delete-formulario/{formulario}', 'Exames@formularioDelete')->name('exame.formulario.delete');
        Route::post('exame-modelo-store/{exame}', 'Exames@modeloStore')->name('exame.modelo.store');
 
  
        /* Faturamento */
        Route::get('/faturamento-conta', 'Faturamento@contas')->name('faturamento.conta');
        Route::post('faturamento-import', 'Faturamento@import')->name('faturamento.import');


        /* Consultorio Geral */
        Route::get('consultorio-formularios/{agendamento}', 'consultorio\Consultorio@show')->name('consultorio_oftalmologia.show');


        /* Consultorio Oftalmo */
        Route::get('consultorio-formularios/{agendamento}', 'consultorio\Consultorio@show')->name('consultorio_oftalmologia.show');
        Route::get('consultorio-formularios-oftalmo/{agendamento}/{formulario}', 'consultorio\ConsultorioOftalmo@showOftalmo')->name('consultorio_oftalmologia.oftalmo');
        
        /* auto-refracao */
        Route::post('store-oftalmo-auto-refracao/{agendamento}', 'consultorio\formularios\AutoRefracao@store');
        Route::any('modal-auto-refracao/{paciente}', 'consultorio\formularios\AutoRefracao@modal');
        Route::delete('delete-auto-refracao/{cd_agendamento}', 'consultorio\formularios\AutoRefracao@delete');

        /* ceratometria */
        Route::post('store-oftalmo-ceratometria/{agendamento}', 'consultorio\formularios\Ceratometria@store');
        Route::get('modal-ceratometria/{paciente}', 'consultorio\formularios\Ceratometria@modal');
        Route::delete('delete-ceratometria/{cd_agendamento}', 'consultorio\formularios\Ceratometria@delete');

        /* ceratometria Comp */
        Route::post('store-oftalmo-ceratometria-comp/{agendamento}', 'consultorio\formularios\CeratometriaComp@store');
        Route::get('relacao-ceratometria-comp/{cd_agendamento}', 'consultorio\formularios\CeratometriaComp@index');
        Route::get('modal-completo-ceratometria-comp/{paciente}', 'consultorio\formularios\CeratometriaComp@modalCompleto');
        Route::delete('delete-ceratometria-comp/{cd_image_formulario}', 'consultorio\formularios\CeratometriaComp@delete');

        /* pupilometria */
        //Route::post('store-oftalmo-pupilometria/{agendamento}', 'consultorio_formularios\formularios\Pupilometria@store');
        //Route::get('modal-pupilometria/{paciente}', 'consultorio_formularios\formularios\Pupilometria@modal');
        //Route::delete('delete-pupilometria/{formulario}', 'consultorio_formularios\formularios\Pupilometria@delete');

        /* acuidade visual */
        //Route::post('store-oftalmo-acuidade/{agendamento}', 'consultorio_formularios\formularios\Acuidade@store');
        //Route::get('modal-acuidade/{paciente}', 'consultorio_formularios\formularios\Acuidade@modal');
        //Route::delete('delete-acuidade/{formulario}', 'consultorio_formularios\formularios\Acuidade@delete');
        /* dp */
        //Route::post('store-oftalmo-dp/{agendamento}', 'consultorio_formularios\formularios\Dp@store');
        //Route::get('modal-dp/{paciente}', 'consultorio_formularios\formularios\Dp@modal');
        //Route::delete('delete-dp/{formulario}', 'consultorio_formularios\formularios\Dp@delete');

        /* TonometriaPneumatica */
        //Route::post('store-oftalmo-tonometria_pneumatica/{agendamento}', 'consultorio_formularios\formularios\TonometriaPneumatica@store');
        //Route::get('modal-tonometria_pneumatica/{paciente}', 'consultorio_formularios\formularios\TonometriaPneumatica@modal');
        //Route::delete('delete-tonometria_pneumatica/{formulario}', 'consultorio_formularios\formularios\TonometriaPneumatica@delete');
        
        /* anamnese inicial */
        Route::post('store-oftalmo-anamnese/{agendamento}', 'consultorio\formularios\Anamnese@store');
        Route::get('modal-anamnese/{paciente}', 'consultorio\formularios\Anamnese@modal');
        Route::delete('delete-anamnese/{cd_agendamento}', 'consultorio\formularios\Anamnese@delete');
        /* Ectoscopia */
        //Route::post('store-oftalmo-ectoscopia/{agendamento}', 'consultorio_formularios\formularios\Ectoscopia@store');
        //Route::post('store-oftalmo-ectoscopia-img/{agendamento}', 'consultorio_formularios\formularios\Ectoscopia@storeImg');
        //Route::get('modal-ectoscopia/{paciente}', 'consultorio_formularios\formularios\Ectoscopia@modal');
        //Route::delete('delete-ectoscopia/{formulario}', 'consultorio_formularios\formularios\Ectoscopia@delete');
        
        /* Biomicroscopia */
        //Route::post('store-oftalmo-biomicroscopia/{agendamento}', 'consultorio_formularios\formularios\Biomicroscopia@store');
        //Route::post('store-oftalmo-biomicroscopia-img/{agendamento}', 'consultorio_formularios\formularios\Biomicroscopia@storeImg');
        //Route::get('modal-biomicroscopia/{paciente}', 'consultorio_formularios\formularios\Biomicroscopia@modal');
        //Route::delete('delete-biomicroscopia/{formulario}', 'consultorio_formularios\formularios\Biomicroscopia@delete');

        /* Fundoscopia */
        Route::post('store-oftalmo-fundoscopia/{agendamento}', 'consultorio\formularios\Fundoscopia@store');
        Route::post('store-oftalmo-fundoscopia-img/{agendamento}', 'consultorio\formularios\Fundoscopia@storeImg');
        Route::get('modal-fundoscopia/{paciente}', 'consultorio\formularios\Fundoscopia@modal');
        Route::delete('delete-fundoscopia/{cd_agendamento}', 'consultorio\formularios\Fundoscopia@delete');
        Route::delete('delete-fundoscopia-img/{cd_img_formulario}', 'consultorio\formularios\Fundoscopia@deleteImg');

        /* Tonometria Aplanacao */
        Route::post('store-oftalmo-tonomeria-aplanacao/{agendamento}', 'consultorio\formularios\TonometriaAplanacao@store');
        Route::get('modal-tonomeria-aplanacao/{paciente}', 'consultorio\formularios\TonometriaAplanacao@modal');
        Route::delete('delete-tonomeria-aplanacao/{cd_agendamento}', 'consultorio\formularios\TonometriaAplanacao@delete');

        /* Refracao */
        Route::post('store-oftalmo-refracao/{agendamento}', 'consultorio\formularios\Refracao@store');
        Route::get('modal-refracao/{paciente}', 'consultorio\formularios\Refracao@modal');
        Route::delete('delete-refracao/{cd_agendamento}', 'consultorio\formularios\Refracao@delete');

        /* Diagnostico */
        //Route::post('store-oftalmo-diagnostico/{agendamento}', 'consultorio_formularios\formularios\Diagnostico@store');
        //Route::get('modal-diagnostico/{paciente}', 'consultorio_formularios\formularios\Diagnostico@modal');
        //Route::delete('delete-diagnostico/{formulario}', 'consultorio_formularios\formularios\Diagnostico@delete');

        /* Receita */
        Route::post('store-oftalmo-receita/{agendamento}', 'consultorio\formularios\Receita@store');
        Route::get('modal-receita/{paciente}', 'consultorio\formularios\Receita@modal');
        Route::delete('delete-receita/{formulario}', 'consultorio\formularios\Receita@delete');
        /* Atestado */
        Route::post('store-oftalmo-atestado/{agendamento}', 'consultorio\formularios\Atestado@store');
        Route::get('modal-atestado/{paciente}', 'consultorio\formularios\Atestado@modal');
        Route::delete('delete-atestado/{cd_agendamento}', 'consultorio\formularios\Atestado@delete');
        /* Receita_oculos */
        Route::post('store-oftalmo-receita_oculos/{agendamento}', 'consultorio\formularios\ReceitaOculos@store');
        Route::get('modal-receita_oculos/{paciente}', 'consultorio\formularios\ReceitaOculos@modal');
        Route::delete('delete-receita_oculos/{cd_agendamento}', 'consultorio\formularios\ReceitaOculos@delete');

        /* Selecao de Lentes */
        //Route::post('store-oftalmo-selecao_lentes/{agendamento}', 'consultorio_formularios\formularios\SelecaoLentes@store');
        //Route::get('modal-selecao_lentes/{paciente}', 'consultorio_formularios\formularios\SelecaoLentes@modal');
        //Route::delete('delete-selecao_lentes/{formulario}', 'consultorio_formularios\formularios\SelecaoLentes@delete');

        /* Reserva Cirurgia */
        Route::post('store-reserva-cirurgia/{agendamento}', 'consultorio\formularios\ReservaCirurgia@store');
        Route::get('modal-reserva-cirurgia/{paciente}', 'consultorio\formularios\ReservaCirurgia@modal');
        Route::delete('delete-reserva-cirurgia/{cd_agendamento}', 'consultorio\formularios\ReservaCirurgia@delete');
        // Medicamentos
        // Route::get('/search-medicamentos/{medicamento}', [Medicamentos::class, 'search'])->name('medicamentos.search');
        // Route::get('/medicamentos', [Medicamentos::class, 'index'])->name('medicamentos.index');
 

        // Route::get('/test', [TestController::class, 'index']);

        /* PreExame */
        Route::get('pre-exame', 'PreExame@index')->name('pre-exame.listar');

        /* ReservaCirurgia */
        Route::get('reserva-cirurgia', 'ReservaCirurgiaPainel@index')->name('reserva-cirurgia.listar');

        /* Central Laudos */
        Route::get('central-laudos', 'CentralLaudos@index')->name('central-laudos.listar');
        Route::get('central-laudos-documento/{item}', 'CentralLaudos@laudo')->name('central-laudos.documento'); 
        Route::get('/central-laudos-visualizar/doc/{img}', 'CentralLaudos@visualizarDoc')->name('central-laudos.visualizar');
        Route::get('central-laudos-painel-imagens/{item}', 'CentralLaudos@painelImagens')->name('central.laudos.painel.imagens');
        
        /* Cirurgias */
        Route::get('cirurgias', 'Cirurgias@index')->name('cirurgias.listar');


        /* Relatorios */
        Route::get('relatorios', 'Relatorios@index')->name('relatorios');
        Route::get('relatorios-listar', 'Relatorios@listarRelatorios')->name('relatorios.list');
        Route::get('relatorios-relatorios', 'Relatorios@relatorios')->name('relatorios.relatorios');
        Route::get('imprimir-relatorios', 'Relatorios@getInfoRelatorio')->name('relatorios.imprimir');
        Route::get('relatorios-edit', 'Relatorios@getRelatorio')->name('relatorios.edit');
        Route::get('relatorios-create', 'Relatorios@create')->name('relatorios.add');
        Route::get('relatorios-agendamento', 'Relatorios@agendamento')->name('relatorios.agendamento');
        Route::get('fluxo-caixa', 'Relatorios@fluxo_caixa')->name('relatorios.fluxo.caixa');

        /* Relatorios RPsys */ 
        Route::get('rpsys_producao', 'Relatorios@producao')->name('relatorios.rpsys.producao');
        Route::get('rpsys_producao_pdf', 'Relatorios@producao_pdf')->name('relatorios.rpsys.producao.pdf');
        
        /* Noticias */
        Route::get('noticias', 'Noticias@index')->name('noticias');

    });

    Route::group([
        'middleware' => [
            'auth:rpclinica',
            // 'permissoes_usuario'
        ],
        'prefix' => 'json'
    ], function() {



        Route::get('/panel-dashboard/{data}', [Inicial::class, 'jsonPanel']);
        Route::get('/panel-dashboard-consultorio/{data}', [Inicial::class, 'jsonPanelConsultorio']);
        Route::get('/panel-dashboard-compromisso', [Inicial::class, 'jsonPanelComp']);

        Route::post('/usuario-store', 'Usuarios@jsonStore');
        Route::post('/usuario-up/{usuario}', 'Usuarios@update')->name('usuario.update');
        Route::post('/usuario-update/{usuario}', 'Usuarios@jsonUpdate');
        Route::post('/profissional-store-procedimento', 'Profissionais@storeProcedimento');
        Route::post('/profissional-store-especialidade', 'Profissionais@storeEspecialidade');
        Route::delete('/profissional-delete-procedimento/{procedimento}', 'Profissionais@deleteProcedimento');
        Route::delete('/profissional-delete-especialidade/{especialidade}', 'Profissionais@deleteEspecialidade');

        //Json Agendamentos
        Route::post('/horarios', [Agendamento::class, 'horariosAgendamentos']);
        Route::post('/horarios-avanc', [Agendamento::class, 'horariosAgendaAvanc']);
        Route::get('/jsonTeste', [Agendamento::class, 'jsonTeste']);
        Route::post('/agenda/horarios', [Agendas::class, 'horarios']);
        Route::any('/agenda/horarios/gerar-agendamentos', [Agendas::class, 'gerarAgendamentos']);
        Route::post('/agenda/horarios/pesquisa-exclusao', [Agendas::class, 'pesquisaExclusao']);
        Route::post('/agenda/horarios/adicionar-data-exclusao', [Agendas::class, 'adicionarDataExclusao']);
        Route::post('/agenda/horarios/excluir-datas', [Agendas::class, 'excluirAgendamentosGerados']);

        // agendamentos Novos 
        Route::any('/recepcao-agendamento',[Agendamentos::class, 'modal']);
        Route::any('/recepcao-store-agendamento',[Agendamentos::class, 'StoreAgendamento']);
        Route::delete('/agendamento/{agendamento}', [Agendamentos::class, 'destroyAgendamento']);
        Route::post('/agendamento/bloquear-horario', [Agendamentos::class, 'bloquearHorario']);
        Route::post('/agendamento/desbloquear-horario', [Agendamentos::class, 'desbloquearHorario']);
        Route::post('/agendamento-avanc', [Agendamentos::class, 'ShowAgendamentoAvanc']);
        Route::post('/agendamento-item/{agendamento}', [Agendamentos::class, 'storeItemAgendamento']);
        Route::delete('/agendamento-item/{item}', [Agendamentos::class, 'deleteItemAgendamento']);
        Route::post('/agendamento-confirmacao', [Agendamentos::class, 'ShowAgendamentoConfirm']);
        Route::any('/agendamento-store-confirmacao/{agendamento}/{situacao}',[Agendamentos::class, 'StoreConfirmacao']);
        //Route::post('/agendamento-bloqueio', [Agendamentos::class, 'bloquearHorarioLista']);
        Route::any('/agenda-escala-manual', [Agendas::class, 'storeEscalaManual']);
        Route::post('/agendamento-bloqueio-modal', [Agendamentos::class, 'bloquearHorarioModal']);
        Route::post('/agendamento-escala-diaria', [Agendamentos::class, 'storeAgendamentoEscalaDiaria']);
        
        //Agendamento Lista
        Route::post('/agendamento/dados-modal', [AgendamentosLista::class, 'dadosModal']);
        Route::post('/agendamento-lista/situacao', [AgendamentosLista::class, 'updateStatus']);
        Route::get('/agendamento/add-modal', [AgendamentosLista::class, 'addModal']);
        Route::post('/agendamento-lista/store', [AgendamentosLista::class, 'StoreAgendamento']);
        Route::get('/agendamento/list-bloqueio', [AgendamentosLista::class, 'getBloqueio']);
        Route::delete('/agendamento/delete-bloqueio/{bloqueio}', [AgendamentosLista::class, 'deleteBloqueio']);

        // recepção
        Route::post('show-recepcao', 'Recepcao@show');
        Route::post('/recepcao-store-guia/{agendamento}','Recepcao@storeGuia');
        Route::post('/recepcao-update-guia','Recepcao@updateGuia');
        Route::get('/carrega-guia-itens/{agendamento}','Recepcao@CarregaGuiaItens');

        // tesouraria
        Route::post('show-tesouraria', 'Tesouraria@show');
        Route::post('show-tesouraria-item', 'Tesouraria@show_item');
        Route::post('store-tesouraria-financeiro', 'Tesouraria@addLancamento');
        Route::post('liberar-tesouraria-financeiro/{agendamento}', 'Tesouraria@liberacao');
        Route::delete('tesouraria-excluir-parcela/{documento}/{agendamento}', 'Tesouraria@delete_parcela');
        Route::post('tesouraria-recalcular/{agendamento}', 'Tesouraria@recalcular'); 
        Route::post('tesouraria-fechar-conta/{agendamento}/{tipo}', 'Tesouraria@fechar_conta'); 
        Route::post('tesouraria-desconto/{agendamento}', 'Tesouraria@desconto'); 
        Route::delete('tesouraria-excluir-desconto/{agendamento}', 'Tesouraria@delete_desconto');
        
        
        //Procedimento
        Route::get('/search-procedimento', [Procedimentos::class, 'searchProcedimento']);

        // pacientes
        Route::get('/paciente', [Pacientes::class, 'jsonShowPaciente']);
        Route::get('/pacientes', [Pacientes::class, 'jsonIndexPacientes']);
        Route::post('paciente-iniciar-consulta', 'Pacientes@jsonIniciarConsulta');
        Route::post('paciente-vip', 'Pacientes@storeVip');
        Route::post('/pacientes-update-join', 'Pacientes@updateJson')->name('paciente.update.json');
        Route::any('pacientes/documentos/download-pdf', 'Pacientes@downloadPDFDocumento')->name('anamnese.download.documentos.pdf');
        Route::get('paciente-doc/{documento}/{paciente}', 'Pacientes@pacienteDoc');
        Route::post('storeDocumentoPacinete/{paciente}', 'Pacientes@storeDocumento');
        Route::post('storeMsgPacinete/{paciente}', 'Pacientes@storeMsg');
        Route::get('getDocumentoPacinete/{paciente}', 'Pacientes@getDocumento');
        Route::any('imprimirDocumentoPaciente/{documento}', 'Pacientes@imprimirDocumento');
        Route::delete('deleteDocumentoPac/{paciente}/{documento}', 'Pacientes@deleteDocumento');

        // agendamentos
        Route::post('/agendamento', [Agendamento::class, 'agendamentoManual']);
        Route::put('/agendamento', [Agendamento::class, 'updateAgendamento']);

        Route::post('/horario-livre', [Agendamento::class, 'GetHorariosLivre']);
        Route::post('/reagendamento', [Agendamento::class, 'Reagendamento']);
        Route::post('/reagendamento-manual', [Agendamento::class, 'ReagendamentoManual']);
        Route::post('/horario-sessao', [Agendamento::class, 'GetHorariosSessao']);
        Route::get('/dados-agenda', [Agendamento::class, 'jsonShowAgenda']);
        Route::get('/dados-table', [Agendamento::class, 'jsonShowTable']);
        Route::post('/agendamento/resources', [Agendamento::class, 'jsonShowResources']);
        Route::put('/atualiza-status-agendamento', [Agendamentos::class, 'updateStatus']);
        Route::get('/agendamento/conteudo-evento', [Agendamentos::class, 'viewEvento']);
        Route::post('/agendamento/recebimento', [Agendamento::class, 'recebimento']);
        Route::get('/agendamento/conteudo-tp-atend', [Agendamento::class, 'viewTipoAtend']);
        Route::post('/escala_manual', [Agendamento::class, 'escalaManual']);
        Route::delete('/escala_manual/{escala}', [Agendamento::class, 'destroyEscalaManual']);
        Route::post('/agendamento/gravar-agenda-rapido', [Agendamento::class, 'storeAgendaRapido']);
 
        Route::post('/horario-confirm', [Agendamento::class, 'getHorConf']);
        Route::post('/send-horario', [Agendamento::class, 'sendHorConf']);

        Route::get('/valida-whast', [Comunicacoes::class, 'agendaValidaWhast']);
        Route::get('/logs-whast', [Agendamento::class, 'logsAgendamento']);
        Route::post('/logs-envio-rotina-whast', [logEnvios::class, 'jsonPainel']);
        Route::post('/logs-envio-rotina-whast-agendamentos', [logEnvios::class, 'jsonPainelAgendamento']);
 
        Route::get('/agendamento-dados-mes/{data}', [Agendamento::class, 'agendamentoDadosMes']);

        Route::post('/agendamento-sessao', [Agendamento::class, 'agendamentoSessao']);


        Route::post('/agendamento/alterar-horario', [Agendamento::class, 'alterarHorario']);
        Route::post('/agendamento/add-conta-proc', [Agendamento::class, 'addProcConta']);
        Route::delete('/agendamento/delete-conta-proc/{Codigo}', [Agendamento::class, 'destroyProcConta']);
        Route::put('/agendamento/confere-conta-proc', [Agendamento::class, 'confereProcConta']);
        Route::post('/agendamento-envio-confirmacao', [Comunicacoes::class, 'confirmaAgendamentoManual']);
        Route::post('/agendamento-atualizar-retorno', [Comunicacoes::class, 'atualizarRetornos']);

        

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
        Route::delete('atendimento-delete/{atendimento}', [Atendimentos::class, 'jsonDestroy']);

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
        Route::get('/agendamento-encaixe/{escala}', [Agendas::class, 'agendamentoEncaixe']);
        Route::post('/agenda-escala-encaixe/{agendamento}/{horario}', [Agendas::class, 'storeAgendamentoEncaixe']);

        

        Route::get('/agendamento-anexos/{agendamento}', [Consulta::class, 'indexAnexos']);
        Route::post('/agendamento-anexos/{cd_agendamento}', [Consulta::class, 'storeAnexos'])->name('agendamento-anexos');
        Route::delete('/agendamento-anexos/{cd_anexo}', [Consulta::class, 'destroyAnexos']);
        Route::post('/assinatura-digital', [Consulta::class, 'assinaturaDigital']);
        Route::delete('/assinatura-delete/{agendamento}/{tipo}/{codigo}', [Consulta::class, 'deleteAssinatura']);
        Route::get('/historico-documento', [Consulta::class, 'historicoDocumento']);

        Route::get('/graficos-inicio', [Inicial::class, 'grafico']);


        //Atendimentos
        Route::get('/atendimento-atend/{agendamento}', 'Atendimentos@atend');
        Route::post('/atendimento', 'Atendimentos@jsonPainel');
        Route::get('/atendimento-exame', 'Atendimentos@div_itens');

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
        Route::post('/financeiro-estornar-cartao', 'Financeiro@estornarFaturaCartao');
        Route::get('/financeiro-info-cartao/{cartao}', 'Financeiro@infoFaturaCartao');  
        Route::post('financeiro-cadastro', 'Financeiro@cadastro');
        Route::put('/financeiro-estornar-parcela/{cdDocumentoBoleto}', 'Financeiro@estornarParcela');
        Route::get('/financeiro-json-modal', 'Financeiro@jsonModal');      
        Route::post('/financeiro-quitar-boleto/{tipo}', 'Financeiro@quitarBoleto');
        Route::post('/financeiro-excluir-boleto', 'Financeiro@excluirBoleto');
        

        Route::post('/faturamento-itens-conta', 'Faturamento@jsonItensConta');
        Route::post('/faturamento-store/{item}', 'Faturamento@storeFaturamento');


        /* Consultorio 2.0 */
        //Paciente
        Route::get('/paciente/{agendamento}', 'consultorio\paciente\Paciente@index');
        Route::post('/paciente/{paciente}', 'consultorio\paciente\Paciente@store');
        Route::post('/paciente-obs/{paciente}', 'consultorio\paciente\Paciente@storeObs');
        Route::patch('/paciente/comentario',  'consultorio\paciente\Paciente@updateComentario');

        //Anamnese
        //Route::get('/anamnese/{agendamento}', 'consultorio_formularios\anamneses\AnamneseOftlmo@index');
        //Route::post('/anamnese/{agendamento}', 'consultorio_formularios\anamneses\AnamneseOftlmo@anamnese');
        //Route::get('/anamnese/{agendamento}', 'consultorio_formularios\anamneses\AnamneseOftlmo@index')->name('auto.refracao.salve');

        //Painel Reserva de Cirurgia
        Route::post('/reserva-cirurgia', 'ReservaCirurgiaPainel@jsonPainel');
        Route::post('/reserva-cirurgia/addHist', 'ReservaCirurgiaPainel@addHist');
        Route::post('/reserva-cirurgia/addForm', 'ReservaCirurgiaPainel@addForm');
        Route::get('/reserva-cirurgia/getHist/{cd_reserva_cirurgia}', 'ReservaCirurgiaPainel@getHist');

        //Painel Central de Laudos
        Route::post('/central-laudos', 'CentralLaudos@jsonPainel');
        Route::post('/modal-central-laudos', 'CentralLaudos@jsonModal');
        Route::post('/central-laudos/addHist', 'CentralLaudos@addHist');
        Route::get('/central-laudos/getHist/{cd_agendamento_item}', 'CentralLaudos@getHist');
        Route::post('/central-laudos/saveLaudo/{cd_agendamento_item}', 'CentralLaudos@saveLaudo');
        Route::post('/central-laudos/liberarLaudo/{cd_agendamento_item}', 'CentralLaudos@liberarLaudo');
        Route::post('/central-laudos/img/{cd_agendamento_item}', 'CentralLaudos@storeImg');
        Route::get('/central-laudos/imgs/{cd_agendamento_item}', 'CentralLaudos@relacaoImg');
        Route::delete('/central-laudos-delete/img/{cd_agendamento_item}', 'CentralLaudos@deleteImg');
        Route::get('/central-laudos-carrega-texto-padrao/{formulario}/{agendamento}', 'CentralLaudos@carregaTextoPadrao');

        
        //Painel Cirurgias
        Route::post('/cirurgias', 'Cirurgias@jsonPainel');
        Route::post('/cirurgias-laudos', 'Cirurgias@jsonModal');
        Route::post('/cirurgias/addHist', 'Cirurgias@addHist');
        Route::get('/cirurgias/getHist/{cd_agendamento_item}', 'Cirurgias@getHist'); 
        Route::post('/cirurgias/img/{cd_agendamento_item}', 'Cirurgias@storeImg');
        Route::get('/cirurgias/imgs/{cd_agendamento_item}', 'Cirurgias@relacaoImg'); 

        //Pre Exame
        Route::post('/pre-exame-lista', 'PreExame@jsonPainel');
        Route::post('/pre-exame-finalizar/{agendamento}', 'PreExame@jsonFinalizar');

        //Consultorio
        Route::post('/horarios-consultorio', 'Consultorio@show');

        //Consultorio Geral 
        Route::get('getDados/{agendamento}', 'consultorio\ConsultorioGeral@show');
        Route::post('storeAnamneseGeral/{agendamento}', 'consultorio\ConsultorioGeral@storeAnamnese');
        Route::post('deleteAnamneseGeral/{agendamento}', 'consultorio\ConsultorioGeral@deleteAnamnese');
        Route::post('storeModelo/{tipo}', 'consultorio\ConsultorioGeral@storeModelo');
        Route::post('storeDocumentoGeral/{agendamento}', 'consultorio\ConsultorioGeral@storeDocumento');
        Route::post('storeAnotacaoGeral/{agendamento}', 'consultorio\ConsultorioGeral@storeAnotacao');
        Route::delete('deleteDocumentoGeral/{agendamento}/{documento}', 'consultorio\ConsultorioGeral@deleteDocumento');
        Route::get('finalizarConsultaGeral/{agendamento}', 'consultorio\ConsultorioGeral@finalizarConsulta'); 
        Route::get('imprimirDocumentoGeral/{agendamento}/{documento}', 'consultorio\ConsultorioGeral@imprimirDocumento');
        Route::get('assinarDocumentoGeral/{agendamento}/{documento}', 'consultorio\ConsultorioGeral@assinarDocumento');
        Route::get('imprimirAnamneseGeral/{agendamento}', 'consultorio\ConsultorioGeral@imprimirAnamnese');
        Route::post('storeAnamneseArquivo/{agendamento}', 'consultorio\ConsultorioGeral@storeArquivoAnamnese');
        Route::delete('anamnese-delete/img/{cd_image_formulario}', 'consultorio\ConsultorioGeral@deleteImgAnamnese');
        Route::get('teste_pdf', 'consultorio\ConsultorioGeral@teste_pdf');
 

        
        /* Comunicacao */
        Route::get('comunicacao-send-laudo/{item}', 'Comunicacoes@send_laudo')->name('comunicacao.send.laudo');

        // FLuxo Caixa 
        Route::get('/relacao-fluxo-caixa', 'FluxoCaixa@relacao'); 
        Route::get('/relacao-fluxo-caixa-movimento', 'FluxoCaixa@relacaoMovimento'); 

        // Relatórios
        Route::get('/relacao-fluxo-caixa', [FluxoCaixa::class, 'relacao']);
        Route::get('relacao-mes-fluxo-caixa', [FluxoCaixa::class, 'relacaoMes']);

        Route::prefix('relatorios')->group(function() {
            Route::post('/', [Relatorios::class, 'addRelatorio']);
            Route::put('/', [Relatorios::class, 'updateRelatorio']);
            Route::delete('/', [Relatorios::class, 'deleteRelatorio']);
            Route::get('/conteudo-view', [Relatorios::class, 'getConteudoView']);
        });

        //Logs de Envio 
        Route::get('/log-envio/historico/{item}', 'logEnvios@historico');
        
        /* Faturamento */
        Route::post('/faturamento-conta-json', 'Faturamento@jsonContas')->name('faturamento.conta.json');
        
        /* Faturamento */ 
        Route::get('/texto-padrao/{tipo}', 'PerfilProfissional@relacao_texto');
        Route::post('/store-texto-padrao/{tipo}', 'PerfilProfissional@store_relacao_texto');
        Route::delete('/delete-texto-padrao/{tipo}/{codigo}', 'PerfilProfissional@delete_relacao_texto');

    });
});
