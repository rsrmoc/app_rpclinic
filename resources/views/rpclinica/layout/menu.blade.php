'   <div class="page-sidebar sidebar no-print">
    <div class="page-sidebar-inner slimscroll">
        <div style="text-align: center;  padding-top: 10px;">

        </div>

        <ul class="menu accordion-menu">
            <li class="{{ cssRouteCurrent('inicio') }}">
                <a href="{{ route('inicio') }}" class="waves-effect waves-button">
                    <span class="menu-icon glyphicon glyphicon-home"></span>
                    <p>Inicial</p>
                </a>
            </li>
  
            @if (in_array('Tabela', Session::get('perfil_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')

                <li class="droplink {{ cssRouteGrupoMenu('tab') ? 'active open' : '' }}">

                    <a href="#" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-folder-open"></span>
                        <p>Tabelas</p><span class="arrow"></span>
                    </a>

                    <ul class="sub-menu" style="display: block">

                        @if ((auth()->guard('rpclinica')->user()->cd_profissional)  && auth()->guard('rpclinica')->user()->profissional)
                            <li class="{{ cssRouteMenu('perfil.profissional') }}">
                                <a href="{{ route('perfil-profi.listar') }}">Perfil profissional</a>
                            </li>
                        @endif
 
                         
                        @if (in_array('consultorio', Session::get('perfil_sub_menu')) || (auth()->guard('rpclinica')->user()->admin == 'S'))

                            <li class="droplink {{ cssRouteSubGrupoMenu('tab_consul') }} ">
                                <a href="#">
                                    <p>Consultório</p><span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    @if (in_array('grupo.procedimento.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('grupo.procedimentos') }}">
                                            <a href="{{ route('grupo.procedimento.listar') }}">Grupo de
                                                Procedimentos</a>
                                        </li>
                                    @endif

                                    @if (in_array('procedimento.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('procedimentos') }}">
                                            <a href="{{ route('procedimento.listar') }}">Procedimentos</a>
                                        </li>
                                    @endif

                                    @if (in_array('exame.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('exames') }}">
                                            <a href="{{ route('exame.listar') }}">Itens de Atendimento</a>
                                        </li>
                                    @endif

                                    @if (in_array('especialidade.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('especialidades') }}">
                                            <a href="{{ route('especialidade.listar') }}">Especialidades</a>
                                        </li>
                                    @endif

                                    @if (in_array('convenio.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('convenios') }}">
                                            <a href="{{ route('convenio.listar') }}">Convênios</a>
                                        </li>
                                    @endif

                                    @if (in_array('tipo.atend.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('tipos') }}">
                                            <a href="{{ route('tipo.atend.listar') }}">Tipo de Atendimento</a>
                                        </li>
                                    @endif

                                    @if (in_array('local.atend.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('locais') }}">
                                            <a href="{{ route('local.atend.listar') }}">Local de Atendimento</a>
                                        </li>
                                    @endif

                                    @if (in_array('feriados.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('feriados') }}">
                                            <a href="{{ route('feriados.listar') }}">Feriados</a>
                                        </li>
                                    @endif

                                    @if (in_array('agenda.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('agendas') }}">
                                            <a href="{{ route('agenda.listar') }}">Agenda</a>
                                        </li>
                                    @endif

                                    @if (in_array('profissional.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('profissionais') }}">
                                            <a href="{{ route('profissional.listar') }}">Profissional</a>
                                        </li>
                                    @endif

                                    @if (in_array('localidade.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('localidade') }}">
                                            <a href="{{ route('localidade.listar') }}">Localidade (Escala)</a>
                                        </li>
                                    @endif

                                    @if (in_array('escala-tipo.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('escala-tipo') }}">
                                            <a href="{{ route('escala-tipo.listar') }}">Tipo de Escala</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>

                        @endif

                        @if (in_array('financeiro', Session::get('perfil_sub_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')

                            <li class="droplink {{ cssRouteSubGrupoMenu('tab_finan') }}  ">
                                <a href="#">
                                    <p>Financeiro</p><span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">

                                    @if (in_array('conta.bancaria.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('contas.bancarias') }}">
                                            <a href="{{ route('conta.bancaria.listar') }}">Contas</a>
                                        </li>
                                    @endif

                                    @if (in_array('cartao.credito.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('cartao.credito') }}">
                                            <a href="{{ route('cartao.credito.listar') }}">Cartão de Crédito </a>
                                        </li>
                                    @endif

                                    @if (in_array('forma.pag.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('forma.pag') }}">
                                            <a href="{{ route('forma.pag.listar') }}">Forma de Pagamento</a>
                                        </li>
                                    @endif

                                    @if (in_array('marca.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('marca') }}">
                                            <a href="{{ route('marca.listar') }}">Marca</a>
                                        </li>
                                    @endif

                                    @if (in_array('config.finan', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('config.finan') }}">
                                            <a href="{{ route('config.finan') }}">Configuração Geral</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>

                        @endif

                        @if (in_array('estoque', Session::get('perfil_sub_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')

                            <li class="droplink  {{ cssRouteSubGrupoMenu('tab_est') }} ">
                                <a href="#">
                                    <p>Estoque</p><span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">

                                    @if (in_array('classificacao.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('classificacao') }}">
                                            <a href="{{ route('classificacao.listar') }}">Classificação</a>
                                        </li>
                                    @endif

                                    @if (in_array('produto.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('produtos') }}">
                                            <a href="{{ route('produto.listar') }}">Produto</a>
                                        </li>
                                    @endif

                                    @if (in_array('tab-estoque.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('estoques') }}">
                                            <a href="{{ route('tab-estoque.listar') }}">Estoque</a>
                                        </li>
                                    @endif

                                    @if (in_array('tipoaj.ajuste.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('tipo-ajuste') }}">
                                            <a href="{{ route('tipoaj.ajuste.listar') }}">Tipo de Ajuste</a>
                                        </li>
                                    @endif

                                    @if (in_array('motivo.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                        <li class="{{ cssRouteMenu('motivos') }}">
                                            <a href="{{ route('motivo.listar') }}">Motivo</a>
                                        </li>
                                    @endif

                                </ul>
                            </li>

                        @endif

                        @if (in_array('comunicacao.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('comunicacao') }}">
                                <a href="{{ route('comunicacao.listar') }}">Comunicação</a>
                            </li>
                        @endif

                        @if (in_array('empresa.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('empresa') }}">
                                <a href="{{ route('empresa.listar') }}">Empresa</a>
                            </li>
                        @endif

                        @if (in_array('setor.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('setor') }}">
                                <a href="{{ route('setor.listar') }}">Setor</a>
                            </li>
                        @endif

                        @if (in_array('categoria.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('categoria') }}">
                                <a href="{{ route('categoria.listar') }}">Categoria</a>
                            </li>
                        @endif

                        @if (in_array('fornecedor.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('fornecedor') }}">
                                <a href="{{ route('fornecedor.listar') }}">Fornecedor e Cliente</a>
                            </li>
                        @endif

                    </ul>
                </li>

            @endif

            @if (in_array('Recepcao', Session::get('perfil_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')

                <li class="droplink  {{ cssRouteGrupoMenu('age') }}">
                    <a href="#" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-calendar"></span>
                        <p>Modulo de Recepção</p><span class="arrow"></span>
                    </a>
                    <ul class="sub-menu" style="display: block">

                        @if (in_array('agendamentos', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('agendamentos') }}">
                                <a href="{{ route('agendamentos') }}">Agendamentos</a>
                            </li>
                        @endif

                        @if (in_array('recepcao', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('recepcao') }}">
                                <a href="{{ route('recepcao') }}">Recepção</a>
                            </li>
                        @endif

                        @if (in_array('atendimento', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('atendimento') }}">
                                <a href="{{ route('atendimento') }}">Atendimentos</a>
                            </li>
                        @endif

                        @if (in_array('tesouraria', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('tesouraria') }}">
                                <a href="{{ route('tesouraria') }}">Tesouraria</a>
                            </li>
                        @endif

                        @if (in_array('logs.rotina.whast', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('logs.rotina.whast') }}">
                                <a href="{{ route('logs.rotina.whast') }}">Logs de Envio</a>
                            </li>
                        @endif

                    </ul>
                </li>

            @endif

            @if (in_array('Paciente', Session::get('perfil_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')
                <li class="{{ cssRouteMenu('pacientes') }}">
                    <a href="{{ route('paciente.listar') }}" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-user"></span>
                        <p>Paciente</p>
                    </a>
                </li>
            @endif

  
            @if (in_array('Pre.exame', Session::get('perfil_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')
                <li class="{{ cssRouteMenu('pre.exame') }}">
                    <a href="{{ route('pre-exame.listar') }}" class="waves-effect waves-button ">
                        <span class="menu-icon fa fa-stethoscope" style="font-size: 18px;"></span>
                        <p>Pre Exames </p>
                    </a>
                </li>
            @endif

            @if (in_array('Consultorio', Session::get('perfil_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')

                <li class="droplink  {{ cssRouteGrupoMenu('consul') }}">

                    <a href="#">
                        <span class="menu-icon fa fa-medkit" style="font-size: 14px;"></span>
                        <p>Consultorio</p>
                    </a>

                    <ul class="sub-menu">

                        @if (in_array('consultorio', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('consultorio') }}">
                                <a href="{{ route('consultorio') }}">Consultorio</a>
                            </li>
                        @endif

                        @if (in_array('reserva-cirurgia.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('reserva.cirurgia') }}">
                                <a href="{{ route('reserva-cirurgia.listar') }}">Reserva Cirurgia</a>
                            </li>
                        @endif

                        @if (in_array('central-laudos.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('central.laudos') }}">
                                <a href="{{ route('central-laudos.listar') }}">Cental de Laudos</a>
                            </li>
                        @endif

                        @if (in_array('cirurgias.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('cirurgias') }}">
                                <a href="{{ route('cirurgias.listar') }}">Cirurgias</a>
                            </li>
                        @endif
                    </ul>
                </li>

            @endif

            @if (in_array('Escala.medica', Session::get('perfil_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')

                <li class="droplink  {{ cssRouteGrupoMenu('escala_medica') }}">

                    <a href="#">
                        <span class="menu-icon glyphicon glyphicon-random"></span>
                        <p>Escala Médica</p>
                    </a>

                    <ul class="sub-menu">

                        @if (in_array('escala.medica', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('escala_medica') }}">
                                <a href="{{ route('escala.medica') }}">Escala Médica</a>
                            </li>
                        @endif

                         @if (in_array('producao.medica', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('producao_medica') }}">
                                <a href="{{ route('producao.medica') }}">Produção Médica</a>
                            </li>
                        @endif
 
                    </ul>
                </li>

            @endif

            @if (in_array('Faturamento', Session::get('perfil_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')
                <li class="droplink  {{ cssRouteGrupoMenu('fatura') }}">

                    <a href="#">
                        <span class="menu-icon glyphicon glyphicon-tasks"></span>
                        <p>Faturamento</p><span class="arrow"></span>
                    </a>

                    <ul class="sub-menu">
                        <li class="{{ cssRouteMenu('faturamento') }}">
                            <a href="{{ route('faturamento.conta') }}">Contas</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (in_array('Financeiro', Session::get('perfil_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')
                <li class="droplink  {{ cssRouteGrupoMenu('financ') }}">
                    <a href="#" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-usd"></span>
                        <p>Financeiro</p><span class="arrow"></span>
                    </a>

                    <ul class="sub-menu">
                        <li class="{{ cssRouteMenu('financeiro') }}">
                            <a href="{{ route('financeiro.listar') }}">Lançamentos</a>
                        </li>
                        <li class="{{ cssRouteMenu('financeiro.cartao') }}">
                            <a href="{{ route('financeiro.cartao') }}">Cartão de Crédito</a>
                        </li> 
                        @if (in_array('relatorios.fluxo.caixa', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('relatorios.fluxocaixa') }}">
                                <a href="{{ route('relatorios.fluxo.caixa') }}">Fluxo de Caixa</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (in_array('Estoque', Session::get('perfil_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')

                <li class="droplink  {{ cssRouteGrupoMenu('estoque') }}">
                    <a href="#" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon-barcode"></span>
                        <p>Estoque</p><span class="arrow"></span>
                    </a>

                    <ul class="sub-menu">

                        @if (in_array('estoque.entrada.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('estoque.entrada') }}">
                                <a href="{{ route('estoque.entrada.listar') }}">Entrada</a>
                            </li>
                        @endif

                        @if (in_array('estoque.saida.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('estoque.saida') }}">
                                <a href="{{ route('estoque.saida.listar') }}">Saída</a>
                            </li>
                        @endif

                        @if (in_array('estoque.devolucao.listar', Session::get('perfil')) ||
                                auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('estoque.devolucao') }}">
                                <a href="{{ route('estoque.devolucao.listar') }}">Devolução</a>
                            </li>
                        @endif

                        @if (in_array('estoque.ajuste.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('estoque.ajuste') }}">
                                <a href="{{ route('estoque.ajuste.listar') }}">Ajuste</a>
                            </li>
                        @endif

                        @if (in_array('estoque.saldo.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('estoque.saldo') }}">
                                <a href="{{ route('estoque.saldo.listar') }}">Saldo</a>
                            </li>
                        @endif
                    </ul>
                </li>

            @endif

            @if (in_array('Relatorios', Session::get('perfil_menu')) || auth()->guard('rpclinica')->user()->admin == 'S')


                <li class="droplink  {{ cssRouteGrupoMenu('relatorio') }}">
                    <a href="#" class="waves-effect waves-button">
                        <span class="menu-icon glyphicon glyphicon glyphicon-th"></span>
                        <p>Relatorios</p><span class="arrow"></span>
                    </a>

                    <ul class="sub-menu">
                        @if (in_array('relatorios', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li class="{{ cssRouteMenu('relatorios') }}">
                                <a href="{{ route('relatorios') }}">Relatorios</a>
                            </li>
                        @endif


                    </ul>
                </li>

            @endif

 


        </ul>

    </div><!-- Page Sidebar Inner -->
</div><!-- Page Sidebar -->
